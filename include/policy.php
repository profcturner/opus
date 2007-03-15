<?php


/**
**	@function load_policy
** 	Loads a given policy into an associative array.
**	@param $policy_id the policy number in the policy table
**	@return an associative array containing the entire policy
*/
function load_policy($policy_id)
{
  $query = "SELECT * FROM policy WHERE policy_id=$policy_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to load security policy", $query);

  $policy = mysql_fetch_array($result);
  mysql_free_result($result);

  return($policy);
}


/**
**	@function load_default_policy
**	Loads the users default policy into $_SESSION['user']['policy']
*/
function load_default_policy()
{
  if(is_root()) return FALSE;

  if(is_admin())
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
**	@function check_policy
**	Checks a loaded policy for a given permission in a category
**	@param	$category   The major category for the policy eg. student, company
**      @param	$permission The permission for the category to check for, eg. create, edit
**	@return Boolean variable which specifies if the subtype is permitted under the policy
*/
function check_policy($policy, $category, $permission)
{
  if(is_root()) return TRUE;

  return(strstr($policy[$category], $permission));
}


/**
**	@function check_default_policy
**	Checks the default policy for a given permission in a category
**	@param	$category	The major category for the policy
**	@param	$permission	The permission for the category to check for, eg. create
**	@see check_policy
*/
function check_default_policy($category, $permission)
{
  if(is_root()) return TRUE;

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



?>
