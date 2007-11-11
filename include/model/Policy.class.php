<?php

/**
* Handles security limits for administrator users
* @package OPUS
*/
require_once("dto/DTO_Policy.class.php");
/**
* Handles security limits for administrator users
*
* This important functionality handles the limits of powers on various administrator
* users. Policies can be defined and manipulated, and administrators assigned these
* policies. The policy in the Admin object is the absolute ceiling on powers, but
* even lower powers can be granted for a given school, faculty etc..
*
* To be able to act on an entity an administrator normally needs a policy, and a link
* to that entity, an in institutional, faculty, school or programme level.
*
* Note that, super-admin or root users are totally outside the policy framework.
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see Admin.class.php
* @package OPUS
*
*/

class Policy extends DTO_Policy
{
  var $name = "";      // Policy name
  var $help = "";
  var $automail = "";
  var $resource = "";
  var $import = "";
  var $status = "";
  var $log = "";
  var $faculty = "";
  var $school = "";
  var $programme = "";
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
    'name'=>array('type'=>'text', 'size'=>30, 'maxsize'=>100, 'title'=>'Name', 'header'=>true, 'mandatory'=>true),
    'priority'=>array('type'=>'numeric', 'size'=>7, 'title'=>'Priority', 'header'=>true)
  );

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

/*
  function is_auth_for_course($course_id, $category, $permission)
  {
    // root users have automatic access by definition
    if(User::is_root()) return(TRUE);
  
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
*/

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
  function count($where_clause="") 
  {
    $policy = new Policy;
    return $policy->_count($where_clause);
  }

  function get_all($where_clause="", $order_by="ORDER BY priority DESC, name", $page=0)
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

  function get_id_and_field($fieldname, $where_clause="") 
  {
    $policy = new Policy;
    $policy_array = $policy->_get_id_and_field($fieldname, $where_clause);
    $policy_array[0] = "None defined";
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

    foreach ($fieldnames as $fn)
    {
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
    }

    return $nvp_array;
  }

  function get_name($id)
  {
    if(!$id) return("None defined");
    $id = (int) $id; // Security

    $data = Policy::get_id_and_field("name","where id='$id'");
    return($data[$id]);
  }

  /**
  * Loads the users default policy into $_SESSION['user']['policy']
  */
  function load_default_policy()
  {
    global $waf;
    if(isset($_SESSION['user']['policy'])) return true;

    if(User::is_root()) return false;

    if(User::is_admin())
    {
      require_once("model/Admin.class.php");
      $admin = Admin::load_by_user_id(User::get_id());
      $policy_id = $admin->policy_id;

      if(empty($policy_id))
      {
        $waf->log("no policy for admin user");
        $waf->halt("error:policy:no_policy");
      }

      if($admin->inst_admin == 'yes')
      {
        $_SESSION['user']['policy']['institutional_admin'] = true;
      }
    }

    if(User::is_staff())
    {
      // Need to think about this one... for course directors
      return false;
    }
    $_SESSION['user']['policy'] = Policy::load_by_id($policy_id);
    return(true);
  }

  /**
  * Checks a loaded policy for a given permission in a category
  *
  * @param  $category The major category for the policy eg. student, company
  * @param  $permission The permission for the category to check for, eg. create, edit
  * @return Boolean variable which specifies if the subtype is permitted under the policy
  */
  function check_policy($policy, $category, $permission)
  {
    if(User::is_root()) return TRUE;

    return(strstr($policy->$category, $permission));
  }

  /**
  * Checks the default policy for a given permission in a category
  * @param  $category The major category for the policy
  * @param  $permission The permission for the category to check for, eg. create
  * @see check_policy
  */
  function check_default_policy($category, $permission)
  {
    if(User::is_root()) return TRUE;

    return(Policy::check_policy($_SESSION['user']['policy'], $category, $permission));
  }

  /**
  * Checks for authorisation at the school or course level for a student
  */
  function is_auth_for_student($student_id, $category, $permission)
  {
    global $waf;
    // roots are always authorised
    if(User::is_root()) return true;

    // Check for institutional permission
    if(Policy::is_auth_for_university($category, $permission)) return true;

    // Get the programme id, and other unit ids
    // if undefined, only root users can deal with them
    require_once("model/Programme.class.php");
    $programme_id = Student::get_programme_id($student_id);
    if(empty($programme_id)) return false;
    $school_id = Programme::get_school_id($programme_id);
    require_once("model/School.class.php");
    $faculty_id = School::get_faculty_id($school_id);

    // Check from top down, faculty first
    if(Policy::is_auth_for_faculty($faculty_id, $category, $permission)) return true;
    // Then School
    if(Policy::is_auth_for_school($school_id, $category, $permission)) return true;
    // Finally Course
    return Policy::is_auth_for_programme($programme_id, $category, $permission);
  }

  /**
  * Checks the current user for permission for an action upon a institution
  *
  * Users with root access are automatically granted permission. An
  * admin level user will have their default policy checked for permission
  * for this action. If that permission is granted, they will be checked
  * for authorisation to act for that institution, and if that is ok, any
  * local policy acting upon them in that institution will be checked.
  * @param $category  The major category for the policy
  * @param $permission  The permission sought in the catgory
  * @return Boolean variable specifying if permission is granted.
  */
  function is_auth_for_institution($category, $permission)
  {
    // root users have automatic access by definition
    if(User::is_root()) return true;

    // Well, the user better be an admin then..
    if(!User::is_admin()) return false;

    if($_SESSION['user']['policy']['institutional_admin'])
    {
      return(Policy::check_default_policy($category, $permission));
    }
    return false;
  }

  /**
  * Checks the current user for permission for an action upon a faculty
  *
  * Users with root access are automatically granted permission. An
  * admin level user will have their default policy checked for permission
  * for this action. If that permission is granted, they will be checked
  * for authorisation to act for that faculty, and if that is ok, any
  * local policy acting upon them in that faculty will be checked.
  * @param $faculty_id The id of the faculty to be checked
  * @param $category  The major category for the policy
  * @param $permission  The permission sought in the catgory
  * @return Boolean variable specifying if permission is granted.
  */
  function is_auth_for_faculty($faculty_id, $category, $permission)
  {
    $faculty_id = (int) $faculty_id;

    // root users have automatic access by definition
    if(User::is_root()) return true;

    // Well, the user better be an admin then..
    if(!User::is_admin()) return false;

    // Ok, now down to basics... Check the major loaded policy
    if(!check_policy($_SESSION['user']['policy'], $category, $permission)) return false;

    if(empty($faculty_id)) return false;
    // Finally, check that we are specified for the faculty and there is
    // no overriding policy in the faculty

    require_once("model/FacultyAdmin.class.php");
    $admin_id = User::get_id(); // todo, possible conflict user_id, admin_id
    $facultyadmin = SchoolAdmin::load_where("where faculty_id=$faculty_id and admin_id=$admin_id");

    // Determine if the faculty is specified
    if(!$facultyadmin->id) $decision = false;
    else
    {
      // It is, so check for any overriding local policy
      $decision = true;
      $policy = Policy::load_by_id($facultyadmin->policy_id);
      $decision = Policy::check_policy($policy, $category, $permission);
    }
    return($decision);
  }

  /**
  * Checks the current user for permission for an action upon a school
  *
  * Users with root access are automatically granted permission. An
  * admin level user will have their default policy checked for permission
  * for this action. If that permission is granted, they will be checked
  * for authorisation to act for that school, and if that is ok, any
  * local policy acting upon them in that school will be checked.
  * @param $school_id The id of the school to be checked
  * @param $category  The major category for the policy
  * @param $permission  The permission sought in the catgory
  * @return Boolean variable specifying if permission is granted.
  */
  function is_auth_for_school($school_id, $category, $permission)
  {
    $school_id = (int) $school_id;

    // root users have automatic access by definition
    if(User::is_root()) return true;

    // Well, the user better be an admin then..
    if(!User::is_admin()) return false;

    // Ok, now down to basics... Check the major loaded policy
    if(!check_policy($_SESSION['user']['policy'], $category, $permission)) return false;

    if(empty($school_id)) return false;
    // Finally, check that we are specified for the school and there is
    // no overriding policy in the school

    require_once("model/SchoolAdmin.class.php");
    $admin_id = User::get_id(); // todo, possible conflict user_id, admin_id
    $schooladmin = SchoolAdmin::load_where("where school_id=$school_id and admin_id=$admin_id");

    // Determine if the school is specified
    if(!$schooladmin->id) $decision = false;
    else
    {
      // It is, so check for any overriding local policy
      $decision = true;
      $policy = Policy::load_by_id($schooladmin->policy_id);
      $decision = Policy::check_policy($policy, $category, $permission);
    }
    return($decision);
  }

  /**
  * Checks the current user for permission for an action upon a programme
  *
  * Users with root access are automatically granted permission. An
  * admin level user will have their default policy checked for permission
  * for this action. If that permission is granted, they will be checked
  * for authorisation to act for that programme, and if that is ok, any
  * local policy acting upon them in that programme will be checked.
  * @param $programme_id The id of the programme to be checked
  * @param $category  The major category for the policy
  * @param $permission  The permission sought in the catgory
  * @return Boolean variable specifying if permission is granted.
  */
  function is_auth_for_programme($programme_id, $category, $permission)
  {
    $programme_id = (int) $programme_id;

    // root users have automatic access by definition
    if(User::is_root()) return true;

    // Well, the user better be an admin then..
    if(!User::is_admin()) return false;

    // Ok, now down to basics... Check the major loaded policy
    if(!check_policy($_SESSION['user']['policy'], $category, $permission)) return false;

    if(empty($programme_id)) return false;
    // Finally, check that we are specified for the programme and there is
    // no overriding policy in the programme

    require_once("model/ProgrammeAdmin.class.php");
    $admin_id = User::get_id(); // todo, possible conflict user_id, admin_id
    $programmeadmin = SchoolAdmin::load_where("where programme_id=$programme_id and admin_id=$admin_id");

    // Determine if the programme is specified
    if(!$programmeadmin->id) $decision = false;
    else
    {
      // It is, so check for any overriding local policy
      $decision = true;
      $policy = Policy::load_by_id($programmeadmin->policy_id);
      $decision = Policy::check_policy($policy, $category, $permission);
    }
    return($decision);
  }
}
?>