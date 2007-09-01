<?php

/**
* The model object for security policies
* @package OPUS
*/
require_once("dto/DTO_Policy.class.php");

/**
* The Policy model class
*/
class Policy extends DTO_Policy 
{
  var $descript = "";      // Policy name
  var $help = "";
  var $automail = "";
  var $resource = "";
  var $import = "";
  var $status = "";
  var $log = "";
  var $school = "";
  var $course = "";
  var $company = "";
  var $vacancy = "";
  var $contact = "";
  var $staff = "";
  var $student = "";
  var $priority = "";
  var $channel = "";
  var $cvgroup = "";
  var $assessmentgroup = "";

  static $_field_defs = array(
    'name'=>array('type'=>'text', 'size'=>30, 'maxsize'=>100, 'title'=>'Lookup')     );

  function __construct() 
  {
    parent::__construct('default');
  }

  /**
  * returns the statically defined field definitions
  */
  function get_field_defs()
  {
    return(self::$_field_defs);
  }


/////////////////////////////////////

  /**
  * Loads the users default policy into $_SESSION['user']['policy']
  */
  function load_default_policy()
  {
    if(User::is_root()) return FALSE;
  
    if(User::is_admin())
    {
      $query = "SELECT policy_id FROM admins WHERE user_id=" . $_SESSION['user']['id'];
    }
  
    if(is_staff())
    {
      if(!is_course_director()) return FALSE;
      $query = "SELECT policy_id FROM coursedirectors WHERE staff_id=" . $_SESSION['user']['id'];
    }
    $result = mysql_query($query)
      or print_mysql_error2("Unable to obtain policy number", $query);
    $row = mysql_fetch_row($result);
    $policy_id = $row[0];
    mysql_free_result($result);
  
    if(empty($policy_id))
    {
      die_gracefully("There is no security policy defined for your user.");
    }
  
    return(load_policy($policy_id));
  }
  
  
  /**
  **	Checks a loaded policy for a given permission in a category
  **	@param	$category   The major category for the policy eg. student, company
  **      @param	$permission The permission for the category to check for, eg. create, edit
  **	@return Boolean variable which specifies if the subtype is permitted under the policy
  */
  function check_policy($policy, $category, $permission)
  {
    if(User::is_root()) return TRUE;
  
    return(strstr($policy[$category], $permission));
  }
  
  
  /**
  *	Checks the default policy for a given permission in a category
  *	@param	$category	The major category for the policy
  *	@param	$permission	The permission for the category to check for, eg. create
  *	@see check_policy
  */
  function check_default_policy($category, $permission)
  {
    if(User::is_root()) return TRUE;
  
    return(check_policy($_SESSION['user']['policy'], $category, $permission));
  }
  
  
  /**
  **	@function is_auth_for_student
  **	Checks for authorisation at the school or course level for a student
  */
  function is_auth_for_student($student_id, $category, $permission)
  {
    global $log;
    // roots are always authorised
    if(is_root()) return(TRUE);
  
    // Get the course id, if undefined, only root users can deal with them
    $course_id = get_course_id($student_id);
    if(empty($course_id) && !is_root()) return FALSE;
  
    $school_id = get_school_id($course_id);
  
    // See if we are authorised at a school level, otherwise check course level
    if(is_auth_for_school($school_id, $category, $permission)) return TRUE;
    else
    {
      return is_auth_for_course($course_id, $category, $permission);
    }
  }
  
  /**	@function is_auth_for_school
  **	Checks the current user for permission for an action upon a school
  **	Users with root access are automatically granted permission. An
  **	admin level user will have their default policy checked for permission
  **	for this action. If that permission is granted, they will be checked
  **	for authorisation to act for that school, and if that is ok, any
  **	local policy acting upon them in that school will be checked.
  **	@param $school_id	The id of the school to be checked
  **	@param $category	The major category for the policy
  **	@param $permission	The permission sought in the catgory
  **	@return	Boolean variable specifying if permission is granted.
  **
  */
  function is_auth_for_school($school_id, $category, $permission)
  {
    // root users have automatic access by definition
    if(is_root()) return(TRUE);
  
    // Well, the user better be an admin then..
    if(!is_admin()) return(FALSE);
  
    // Ok, now down to basics... Check the major loaded policy
    if(!check_policy($_SESSION['user']['policy'], $category, $permission)) return(FALSE);
  
    if(empty($school_id)) return(FALSE);
    // Finally, check that we are specified for the school and there is
    // no overriding policy in the school
    $query = "SELECT * FROM adminschool WHERE admin_id=" . $_SESSION['user']['id'] .
            " AND school_id=$school_id";
    $result = mysql_query($query)
      or print_mysql_error2("Unable to check admin school policy", $query);
  
    // Determine if the school is specified
    if(!mysql_num_rows($result)) $decision = FALSE;
    else
    {
      // It is, so check for any overriding local policy
      $decision = TRUE;
      $row = mysql_fetch_array($result);
      if(!empty($row["policy_id"]))
      {
        $policy = load_policy($row["policy_id"]);
        $decision = check_policy($policy, $category, $permission);
      }
    }
    mysql_free_result($result);
  
    return($decision);
  }
  
  /**	@function is_auth_for_course
  **	Checks the current user for permission for an action upon a course
  **	Users with root access are automatically granted permission. An
  **	admin level user will have their default policy checked for permission
  **	for this action. If that permission is granted, they will be checked
  **	for authorisation to act for that course, and if that is ok, any
  **	local policy acting upon them in that school will be checked.
  **	Course directors will also be allowed under similar circumstances
  **	these are staff members with a limited policy.
  **	Note that overriding authority granted for a school is NOT checked for.
  **	@param $course_id	The id of the course to be checked
  **	@param $category	The major category for the policy
  **	@param $permission	The permission sought in the catgory
  **	@return	Boolean variable specifying if permission is granted.
  **
  */
  function is_auth_for_course($course_id, $category, $permission)
  {
    // root users have automatic access by definition
    if(is_root()) return(TRUE);
  
    // Well, the user better be an admin or...
    if(!is_admin())
    {
      // ... a course director!
      if(!is_staff()) return(FALSE);
      if(!is_course_director()) return(FALSE);
    }
  
    // Ok, now down to basics... Check the major loaded policy
    if(!check_policy($_SESSION['user']['policy'], $category, $permission)) return(FALSE);
  
    // special check for course director
    if(is_staff())
    {
      $query = "SELECT * FROM coursedirectors WHERE staff_id=" . $_SESSION['user']['id'] .
              " AND course_id=$course_id";
      $result = mysql_query($query)
        or print_mysql_error2("Unable to check course director policy", $query);
  
      // Determine if the course is specified
      if(!mysql_num_rows($result)) $decision = FALSE;
      else
      {
        // It is, so check for any overriding local policy
        $decision = TRUE;
        $row = mysql_fetch_array($result);
        if(!empty($row["policy_id"]))
        {
          $policy = load_policy($row["policy_id"]);
          $decision = check_policy($policy, $category, $permission);
        }
      }
      mysql_free_result($result);
      return($decision);
    }
  
    // Ok, this is an admin user...
    // Finally, check that we are specified for the course and there is
    // no overriding policy in the course
    $query = "SELECT * FROM admincourse WHERE admin_id=" . $_SESSION['user']['id'] .
            " AND course_id=$course_id";
    $result = mysql_query($query)
      or print_mysql_error2("Unable to check admin course policy", $query);
  
    // Determine if the course is specified
    if(!mysql_num_rows($result)) $decision = FALSE;
    else
    {
      // It is, so check for any overriding local policy
      $decision = TRUE;
      $row = mysql_fetch_array($result);
      if(!empty($row["policy_id"]))
      {
        $policy = load_policy($row["policy_id"]);
        $decision = check_policy($policy, $category, $permission);
      }
    }
    mysql_free_result($result);
  
    return($decision);
  }





















/////////////////////////

  function load_by_id($id) 
  {
    $policy = new Policy;
    $policy->id = $id;
    $policy->_load_by_id();
    return $policy;
  }

  function insert($fields) 
  {
    $policy = new Policy;
    $policy->_insert($fields);
  }
  
  function update($fields) 
  {
    $policy = Policy::load_by_id($fields[id]);
    $policy->_update($fields);
  }
  
  /**
  * Wasteful
  */
  function exists($id) 
  {
    $policy = new Policy;
    $policy->id = $id;
    return $policy->_exists();
  }
  
  /**
  * Wasteful
  */
  function count() 
  {
    $policy = new Policy;
    return $policy->_count();
  }

  function get_all($where_clause="", $order_by="ORDER BY id", $page=0)
  {
    $policy = new Policy;
    
    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $policys = $policy->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $policys = $policy->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $policys;
  }

  function get_id_and_field($fieldname) 
  {
    $policy = new Policy;
    $policy_array = $policy->_get_id_and_field($fieldname);
    unset($policy_array[0]);
    return $policy_array;
  }


  function remove($id=0) 
  {  
    $policy = new Policy;
    $policy->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {  
    $policy = new Policy;
    return  $policy->_get_fieldnames($include_id); 
  }
  function request_field_values($include_id = false) 
  {
    $fieldnames = Policy::get_fields($include_id);
    $nvp_array = array();
 
    foreach ($fieldnames as $fn) {
 
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
 
    }

    return $nvp_array;

  }
}
?>