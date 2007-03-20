<?php

// The include files
include('common.php');
include('authenticate.php');
include('lookup.php');
include('wizard.php');

// Connect to the database on the server
db_connect()
  or die("Unable to connect to server");

// Unusual script, we might not require a login...
if(!isset($_SESSION['user']))
{
  page_header("Help Directory");
  visitor_help();
  $root_admins = get_root_admins();
  $smarty->assign("root_admins", $root_admins);
  $smarty->display("help/help_directory/root_admins.tpl");
  $page->end();
  exit;
}

// Authenticate user so that the right people see the right thing
auth_user("user");

$page = new HTMLOPUS("Help Directory");     // Calls the function for the header


$smarty->display("help/help_directory/header.tpl");

if(is_student())
{
  $student_id = get_id();
}

// Always show student help first, if requested...
if($student_id)
{
  student_help($student_id);
}

// Now list specific user classes and what we should do for them...

if(is_supervisor())
{
  // nothing special here yet...
}

if(is_company())
{
  // Student help always comes...
  $sql = "select company_id from companycontact where contact_id=" . get_contact_id(get_id());
  $result = mysql_query($sql)
    or print_mysql_error2("Unable to fetch company list.", $sql);
  $activities = array();
  
  while($company = mysql_fetch_row($result))
  {
    $sql_2 = "SELECT DISTINCT vacancy_id FROM companyvacancy WHERE company_id=" .
      $company[0] ;
    $result_2 = mysql_query($sql_2)
      or print_mysql_error2("Unable to fetch activities for company", $sql_2);
    while($row_2 = mysql_fetch_array($result_2))
    {
      activity_help($row_2['vacancy_id']);
    }
    mysql_free_result($result_2);
  }
  mysql_free_result($result);    
}

if(is_staff())
{
  // School admins!
  $school_id = backend_lookup("staff", "school_id", "user_id", get_id());

  $school_admins = get_school_admins($school_id);
  $smarty->assign("school_admins", $school_admins);
  $smarty->display("help/help_directory/school_admins.tpl");
}

$root_admins = get_root_admins();
$smarty->assign("root_admins", $root_admins);
$smarty->display("help/help_directory/root_admins.tpl");

$page->end();

function visitor_help()
{
  global $smarty;


  $smarty->display("help/help_directory/visitor_help.tpl");
}

function activity_help($activity_id)
{
  global $smarty;

  $activity_admins = array();
  
  $sql = "select admins.* FROM adminactivity, admins, policy where " .
    "adminactivity.admin_id = admins.user_id and " .
    "adminactivity.activity_id = $activity_id and " .
    "admins.policy_id = policy.policy_id ORDER BY policy.priority, admins.surname";

  $result = mysql_query($sql)
    or print_mysql_error2("Unable to fetch course admin list.", $sql);

  while($row = mysql_fetch_array($result))
  {
    if(substr($row['status'], 'help')) array_push($activity_admins, $row);
  }
  mysql_free_result($result);

  $smarty->assign("activity_name", get_activity_name($activity_id));
  $smarty->assign("activity_admins", $activity_admins);
  $smarty->display("help/help_directory/activity_admins.tpl");
}

function student_help($student_id)
{
  global $smarty;

  $course_admins = array();
  $root_admins = array();

  $course_id = get_student_course($student_id);
  $school_id = get_school_id($course_id);

  $smarty->assign_by_ref("course_admins", $course_admins);
  $smarty->assign_by_ref("school_admins", $school_admins);
  $smarty->assign_by_ref("root_admins", $root_admins);

  $smarty->assign("student_name", get_user_name($student_id));
  $smarty->assign("course_name", get_course_name($course_id));
  $smarty->assign("school_name", get_school_name($school_id));


  $query = "SELECT admins.* " . 
    "FROM admincourse, admins, policy WHERE " .
    "admincourse.admin_id=admins.user_id AND " .
    "admincourse.course_id=$course_id AND " .
    "admins.policy_id = policy.policy_id ORDER BY policy.priority, admins.surname";

  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch course admin list.", $query);

  while($row = mysql_fetch_array($result))
  {
    if(substr($row['status'], 'help')) array_push($course_admins, $row);
  }
  mysql_free_result($result);

  $school_admins = get_school_admins($school_id);
  //$root_admins = get_root_admins();


  $smarty->display("help/help_directory/directory_student.tpl");


}

function get_school_admins($school_id)
{
  $school_admins = array();

  $query = "SELECT admins.* " . 
    "FROM adminschool, admins, policy WHERE " .
    "adminschool.admin_id=admins.user_id AND " .
    "adminschool.school_id=$school_id AND " .
    "admins.policy_id = policy.policy_id ORDER BY policy.priority, admins.surname";

  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch school admin list.", $query);

  while($row = mysql_fetch_array($result))
  {
    if(substr($row['status'], 'help')) array_push($school_admins, $row);
  }

  return($school_admins);
}

function get_root_admins()
{
  $root_admins = array();

  $query = "SELECT admins.* FROM admins LEFT JOIN id " . 
    "ON admins.user_id = id.id_number WHERE id.user='root' order by admins.surname";

  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch root admin list.", $query);

  while($row = mysql_fetch_array($result))
  {
    if(substr($row['status'], 'help')) array_push($root_admins, $row);
  }

  return $root_admins;
}

?>