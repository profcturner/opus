<?php

/**
**	student_dir.php
**
** This file produced a directory of students on the
** system.
**
** Initial coding : Colin Turner
**
*/

// The include files
include('common.php');
include('authenticate.php');
include('lookup.php');
include('wizard.php');
include('assessment.php');
include('notes.php');
include('users.php');
include('cv.php');
include('pdp.php');
include('supervisors.php');

// Version 3 style includes
require_once 'CV.class.php';
require_once 'Mail.class.php';

// Connect to the database on the server
db_connect()
  or die("Unable to connect to server");

// Authenticate user so that the right people see the right thing
auth_user("user");

// Usually, only admins and roots
if(!(is_admin() || is_staff()))
  print_auth_failure("ACCESS");

if(!is_root() && !check_default_policy("student", "list"))
  print_auth_failure("ACCESS");

if($mode != "StudentBroadSheet")
{
  $page = new HTMLOPUS('Student Directory', 'directories', 'Students');
}

if(empty($mode)) $mode = STUDENT_SIMPLESEARCH;

switch($mode)
{
  case STUDENT_NEWPASSWORD:
    student_newpassword();
    break;
  case STUDENT_SIMPLESEARCH:
    student_simplesearch();
    break;
  case STUDENT_ShowChannels:
    student_show_channels();
    break;
  case STUDENT_SHOWCV:
    student_CV();
    break;
  case "ApproveCV":
    approve_cv();
    break;
  case "RevokeCV":
    revoke_cv();
    break;
  case STUDENT_DISPLAYCOMPANIES:
    student_displaycompanies();
    break;
  case STUDENT_DISPLAYSTATUS:
    student_displaystatus();
    break;
  case STUDENT_ADVANCEDSEARCH:
    student_advancedsearch();
    break;
  case STUDENT_ADVANCEDSEARCHFORM:
    student_advancedsearchform();
    break;
  case STUDENT_UPDATESTATUS:
    student_updatestatus();
    break;
  case STUDENT_UPDATEPLACEMENT;
    student_updateplacement();
    break;
  case STUDENT_PLACECOMPANYFORM:
    student_placecompanyform($company_id);
    break;
  case STUDENT_PLACECOMPANY:
    student_placecompany();
    break;
 case "StudentInsertPlacement":
   student_insert_placement();
   break;
 case "StudentDeletePlacement":
   student_delete_placement();
   break;
  case STUDENT_REMOVECOMPANY:
    student_removecompany();
    break;

  // Reports
  case "StudentManagementStatistics":
    student_management_statistics();
    break;
  case "StudentReportCourses":
    report_courses();
    break;
  case "StudentBroadSheet":
    student_broadsheet();
    break;
  case "StudentAssessmentDetails":
    student_assessment_details();
    break;

  case STUDENT_NOTES:
    student_display_notes();
    break;
  case Display_Single_Note:
    student_display_note();
    break;
  case Insert_Note:
    student_insert_note();
    break;
  case Notes_Search:
    student_notes_search();
    break;
  case NoteForm:
    student_note_form();
    break;
  case "StudentMassEmail":
    student_mass_email();
    break;
  case "StudentSetAssessor":
    student_set_assessor();
    break;
  default:
    echo "<P>Invalid Mode</P>\n";
    break;
}

// Print the footer and finish the page
$page->end();

function student_set_assessor()
{
  global $student_id;

  $student_id     = $_REQUEST['assessed_id'];
  $assessor_id    = $_REQUEST['assessor_id'];
  $cassessment_id = $_REQUEST['cassessment_id']; 

  if(empty($student_id) || empty($cassessment_id))
    die_gracefully("You must specify a student id and assessment");

  // Delete anything there...
  $sql = "delete from assessorother where cassessment_id=$cassessment_id and " .
    "assessed_id=$student_id";
  mysql_query($sql)
    or print_mysql_error2("Unable to remove assessor information", $sql);

  // Only set if the assessor_id is not empty
  if($assessor_id)
  {
    $sql = "insert into assessorother (assessed_id, assessor_id, cassessment_id) " .
      "values($student_id, $assessor_id, $cassessment_id)";
    mysql_query($sql)
      or print_mysql_error2("Unable to set assessor information", $sql);
  }
  student_displaystatus();
}


function student_mass_email()
{
  $student_ids = $_REQUEST['student_ids'];
  $message = $_REQUEST['message'];
  $CC = $_REQUEST['CC'];
  $subject = $_REQUEST['subject'];

  echo "<h2 align=\"center\">Mail Email</h2>";


  if(!empty($message) && count($student_ids))
  {
    $sender_details = get_user_details(get_id());
    $sender_email = 
      $sender_details['title'] . ' ' . $sender_details['firstname'] . ' ' .
      $sender_details['surname'] . " <" . $sender_details['email'] . ">";

    $to_email = 
      "Undisclosed Recipients <" . $sender_details['email'] . ">";


    $extra = "From: $sender_email\r\n";
    if($_REQUEST['CC']) $extra .= "Cc: $sender_email\r\n";

    echo "<ol>\n";
    $bcc = "bcc: ";

    foreach($student_ids as $student_id)
    {
      $student_details = get_user_details($student_id);
 
      $student_email = 
	$student_details['title'] . ' ' . $student_details['firstname'] . ' ' .
	$student_details['surname'] . " <" . $student_details['email'] . ">";

      $bcc .= $student_email . ", ";
      echo "<li>Emailing " . htmlspecialchars($student_email) . "...</li>\n";
    }
    // Trim extra comma off
    $bcc = substr($bcc, 0, -2) . "\r\n";
    $extra .= $bcc;
    echo "</ol>\n";
    $new_mail = new OPUSMail($to_email, $_REQUEST['subject'], $message, $extra);
    $new_mail->send();
    echo "<p align=\"center\">Done</p>";
  }
  else
  {
    echo "<p align=\"center\">Messages was empty, no message sent</p>";
  }
}


/*
**	student_newpassword
**
** Automatically generates a new password for the contact and emails
** it if possible. Otherwise it displays it on screen.
*/
function student_newpassword()
{
  global $PHP_SELF;
  global $log;
  global $student_id;

  if(!is_admin())
    die_gracefully("You are not permitted to perform this action");

  if(!is_auth_for_student($student_id, "student", "create"))
    die_gracefully("You do not have permission to perform this action");

  $query = "SELECT * FROM cv_pdetails WHERE id=" . $student_id;
  $result = mysql_query($query)
    or print_mysql_error2("Unable to obtain student information");
  $row = mysql_fetch_array($result);

  // Fetch matching user information
  $user_query = "SELECT * FROM id WHERE id_number=" . $student_id;
  $user_result = mysql_query($user_query)
    or print_mysql_error2("Unable to obtain user data.", $user_query);
  $user_row = mysql_fetch_array($user_result);

  // Generate a new password
  $password = user_make_password();

  // Put the new password in the database
  $new_query = "UPDATE id SET password=MD5('$password') WHERE id_number=" . $student_id;
  mysql_query($new_query) or print_mysql_error2("Unable to update password.", $new_query);

  if(!empty($row["email"]))
  {
    user_notify_password($row["email"], $row["title"], $row["firstname"], $row["surname"],
                         $user_row["username"], $password, $row["user_id"]);
    echo "<P ALIGN=\"CENTER\">The user has been emailed a username and password.</P>\n";
  }
  else{
    echo "<P ALIGN=\"CENTER\">No email address is listed for this user " .
         "and so it is impossible to send them the new credentials.<BR>" .
         "They have been allocated as follows.<BR>" .
         "<TABLE>\n<TR><TD>Username</TD><TD>" . $user_row["username"] . "</TD></TR>\n" .
         "<TR><TD>Password</TD><TD>" . $password . "</TD></TR>\n</TABLE>\n";
  }

  student_displaystatus();
}



/**
**	@function student_display_notes()
**
*/
function student_display_notes()
{
  global $student_id;

  if(empty($student_id)){
    die_gracefully("This page should not be accessed without a student id.");
  }

  $query = sprintf("SELECT * FROM id WHERE id_number=%s", $student_id);
  $result = mysql_query($query)
    or print_mysql_error("Unable to access user information.");

  $row = mysql_fetch_array($result);
  mysql_free_result($result);

  printf("<H2 ALIGN=\"CENTER\">%s (%s)</H2>\n",
         htmlentities($row["real_name"]), htmlentities($row["username"]));

  printf("<H3 ALIGN=\"CENTER\">Notes</H3>\n");

  if($row["user"]!="student"){
    print("<P ALIGN=\"CENTER\">Error, this is not a student.</P>\n");
    return;
  }

  print_wizard("Notes");

  notes_display_list("Student", $student_id, "student_id=$student_id");
}


function student_notes_search()
{
  global $student_id;

  if(empty($student_id)){
    die_gracefully("This page should not be accessed without a student id.");
  }

  $query = sprintf("SELECT * FROM id WHERE id_number=%s", $student_id);
  $result = mysql_query($query)
    or print_mysql_error("Unable to access user information.");

  $row = mysql_fetch_array($result);
  mysql_free_result($result);

  printf("<H2 ALIGN=\"CENTER\">%s (%s)</H2>\n",
         htmlentities($row["real_name"]), htmlentities($row["username"]));

  printf("<H3 ALIGN=\"CENTER\">Notes</H3>\n");

  if($row["user"]!="student"){
    print("<P ALIGN=\"CENTER\">Error, this is not a student.</P>\n");
    return;
  }

  print_wizard("Notes");
  notes_search_list("Student", $student_id, "student_id=$student_id");

}

function student_insert_note()
{
  notes_insert();
  student_display_notes();
}

function student_note_form()
{
  global $student_id;
  global $PHP_SELF;

  if(empty($student_id)){
    die_gracefully("This page should not be accessed without a student id.");
  }

  $query = sprintf("SELECT * FROM id WHERE id_number=%s", $student_id);
  $result = mysql_query($query)
    or print_mysql_error("Unable to access user information.");

  $row = mysql_fetch_array($result);
  mysql_free_result($result);

  printf("<H2 ALIGN=\"CENTER\">%s (%s)</H2>\n",
         htmlentities($row["real_name"]), htmlentities($row["username"]));

  printf("<H3 ALIGN=\"CENTER\">Notes</H3>\n");

  if($row["user"]!="student"){
    print("<P ALIGN=\"CENTER\">Error, this is not a student.</P>\n");
    return;
  }
  print_wizard("Notes");

  notes_form("Student", $student_id, "student_id=$student_id");

}

function student_display_note()
{
  global $student_id;

  if(empty($student_id)){
    die_gracefully("This page should not be accessed without a student id.");
  }

  $query = sprintf("SELECT * FROM id WHERE id_number=%s", $student_id);
  $result = mysql_query($query)
    or print_mysql_error("Unable to access user information.");

  $row = mysql_fetch_array($result);
  mysql_free_result($result);

  printf("<H2 ALIGN=\"CENTER\">%s (%s)</H2>\n",
         htmlentities($row["real_name"]), htmlentities($row["username"]));

  printf("<H3 ALIGN=\"CENTER\">Notes</H3>\n");

  if($row["user"]!="student"){
    print("<P ALIGN=\"CENTER\">Error, this is not a student.</P>\n");
    return;
  }

  print_wizard("Notes");

  notes_display();
}


/**
 **
 */
function report_courses()
{
  global $smarty;

  $group_id = $_REQUEST['group_id'];
  $year = $_REQUEST['year'];

  if(empty($group_id))
  {
    die_gracefully("You must select an assessment group for the report");
  }
  if(empty($year))
  {
    die_gracefully("You must select an academic year for the report");
  }
  $groupname = backend_lookup("assessmentgroups", "name", "group_id", $group_id);


  // Fetch all the courses for a given assessmentgroup
  $courses = array();
  $sql = "SELECT * FROM courses LEFT JOIN assessmentgroupcourse ON " .
    "courses.course_id = assessmentgroupcourse.course_id " .
    "WHERE assessmentgroupcourse.group_id = $group_id " .
    "ORDER BY course_name";
  $result = mysql_query($sql)
    or print_mysql_error2("Unable to obtain course list.", $sql);
  while($course = mysql_fetch_array($result))
  {
    $course = report_courses_fetch_data($course, $year);
    array_push($courses, $course);
  }
  mysql_free_result($result);
  $smarty->assign("courses", $courses);
  $smarty->assign("format", "html");
  $smarty->assign("year", $year);
  $smarty->assign("groupname", $groupname);
  $smarty->display("admin/student_directory/report_courses.tpl");
}


function report_courses_fetch_data($course, $year)
{
  $countries = array();
  $status    = array();

  $course_id = $course['course_id'];

  $query = "SELECT DISTINCT id.*, students.*, cv_pdetails.course FROM id " .
    "LEFT JOIN cv_pdetails ON id_number=cv_pdetails.id LEFT JOIN students " .
    "ON id_number=students.user_id where user='student' " .
    " AND students.year=$year AND cv_pdetails.course=$course_id";


  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch student list.", $query);
  while($row = mysql_fetch_array($result))
  {
    if(!is_auth_for_student($row['id_number'], "student", "viewStatus")) 
      continue;
    $status[str_replace(" ", "", $row['status'])]++;
    if($row['status'] == 'Placed')
    {
      $company = company_data_for_student($row['id_number']);
      $countries[$company['country']]++;
    }
  }
  mysql_free_result($result);
  $course['countries'] = $countries;
  $course['status'] = $status;

  return($course);
}


/**
**	@function student_management_statistics
**	Begins the creation of a broadsheet for students with assessment information
**
*/
function student_management_statistics()
{
  global $PHP_SELF;
  global $group;
  global $year;
  global $log;
  global $type;
  global $smarty;

  // Unless the user can list and view student status, they should not be here
  if(!(check_default_policy("student", "list") && check_default_policy("student", "viewStatus")))
  {
    print_auth_failure("ACCESS");
  }

  if(empty($year))
    $year = get_academic_year();

  $assessment_groups = array();
  // Loop through all possible assessmentgroups
  $query = "SELECT * FROM assessmentgroups ORDER BY name";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch assessment groups", $query);
    
  while($row = mysql_fetch_array($result))
  {
    array_push($assessment_groups, $row);
  }

  $smarty->assign("year", $year);
  $smarty->assign("assessment_groups", $assessment_groups);
  $smarty->display("admin/student_directory/student_management_statistics.tpl");

}


function student_assessment_details()
{
  global $smarty;
  global $page;

  $group_id = $_REQUEST['group_id'];
  $year = $_REQUEST['year'];
  $format = $_REQUEST['format'];
  $extras = $_REQUEST['extras'];

  if(!empty($_REQUEST['cassessment_id']))
  {
    student_assessment_details_breakdown();
    return;
  }

  if(empty($group_id))
  {
    $page = new HTMLOPUS('Student Directory', 'directories', 'Students');
    die_gracefully("Warning, no assessment regime selected");
  }
  if(empty($year))
  {
    $page = new HTMLOPUS('Student Directory', 'directories', 'Students');
    die_gracefully("Warning, no year selected");
  }
  // Get all the assessments in the structure
  $sql = "select * from assessmentregime where group_id=$group_id order by student_description";
  $result = mysql_query($sql)
    or print_mysql_error2("Unable to fetch assessment regime items", $sql);

  $assessments = array();
  while($assessment = mysql_fetch_array($result))
  {
    array_push($assessments, $assessment);
  }

  $smarty->assign("group_id", $group_id);
  $smarty->assign("group_name", backend_lookup('assessmentgroups', 'name', 'group_id', $group_id));
  $smarty->assign("year", $year);
  $smarty->assign("format", $format);
  $smarty->assign("extras", $extras);
  $smarty->assign("assessments", $assessments);

  $smarty->display("admin/student_directory/student_assessment_details_select.tpl");
}


function student_assessment_details_breakdown()
{
  global $smarty;
  global $page;

  $group_id = $_REQUEST['group_id'];
  $year = $_REQUEST['year'];
  $format = $_REQUEST['format'];
  $cassessment_id = $_REQUEST['cassessment_id'];

  if(empty($group_id))
  {
    $page = new HTMLOPUS('Student Directory', 'directories', 'Students');
    die_gracefully("Warning, no assessment regime selected");
  }
  if(empty($year))
  {
    $page = new HTMLOPUS('Student Directory', 'directories', 'Students');
    die_gracefully("Warning, no year selected");
  }
  if(empty($cassessment_id))
  {
    $page = new HTMLOPUS('Student Directory', 'directories', 'Students');
    die_gracefully("Warning, no assessment selected");
  }
  // Get a list of applicable students

  // Now we need to fetch the data itself and stick it in arrays

  $smarty->assign("group_id", $group_id);
  $smarty->assign("group_name", backend_lookup('assessmentgroups', 'name', 'group_id', $group_id));
  $smarty->assign("year", $year);
  $smarty->assign("format", $format);
  $smarty->assign("extras", $extras);
  $smarty->assign("assessments", $assessments);

  $smarty->display("admin/student_directory/student_assessment_details_select.tpl");
}


function student_fetch_assessment_details()
{
  global $smarty;
  global $page;

  $group_id = $_REQUEST['group_id'];
  $year = $_REQUEST['year'];
  $format = $_REQUEST['format'];
  $extras = $_REQUEST['extras'];
  $cassessment_id = $_REQUEST['cassessment_id'];

  if(empty($group_id) || empty($cassessment_id))
  {
    $page = new HTMLOPUS('Student Directory', 'directories', 'Students');
    die_gracefully("Warning, no assessment selected");
  }
  if(empty($year))
  {
    $page = new HTMLOPUS('Student Directory', 'directories', 'Students');
    die_gracefully("Warning, no year selected");
  }

  switch($format)
  {
  case "HTML":
    $page = new HTMLOPUS('Student Directory', 'directories', 'Students');
    $row_start = "<tr><td>";
    $separator = "</td><td>";
    $row_end = "</td></tr>\n";
    break;
  case "TSVCSV":
    $row_start = "";
    $separator = "\t";
    $row_end = "\n";
    header("Content-type: text/tab-separated-values");
    header("Content-Disposition: attachment; filename=\"Assessment-$cassessment_id-$group_id-$year.csv\"");
    break;
  case "TSV":
    $row_start = "";
    $separator = "\t";
    $row_end = "\n";
    header("Content-type: text/tab-separated-values");
    header("Content-Disposition: attachment; filename=\"Assessment-$cassessment_id-$group_id-$year.tsv\"");
    break;
  }
  $rows = student_broadsheet_data($group_id, $year, $extras);
  $smarty->assign("row_start", $row_start);
  $smarty->assign("row_end", $row_end);
  $smarty->assign("separator", $separator);
  $smarty->assign("broadsheet_data", $rows);
  $smarty->assign("format", $format);
  $smarty->assign("extras", $extras);
  $smarty->compile_check = false;
  $smarty->debugging = false;
  $smarty->display("admin/student_directory/student_broadsheet.tpl");
  switch($format)
  {
  case "TSV":
  case "TSVCSV":
    exit;
  }
}



function student_broadsheet()
{
  global $smarty;
  global $page;

  $group_id = $_REQUEST['group_id'];
  $year = $_REQUEST['year'];
  $format = $_REQUEST['format'];
  $extras = $_REQUEST['extras'];

  if(empty($group_id))
  {
    $page = new HTMLOPUS('Student Directory', 'directories', 'Students');
    die_gracefully("Warning, no assessment regime selected");
  }
  if(empty($year))
  {
    $page = new HTMLOPUS('Student Directory', 'directories', 'Students');
    die_gracefully("Warning, no year selected");
  }

  switch($format)
  {
  case "HTML":
    $page = new HTMLOPUS('Student Directory', 'directories', 'Students');
    $row_start = "<tr><td>";
    $separator = "</td><td>";
    $row_end = "</td></tr>\n";
    break;
  case "TSVCSV":
    $row_start = "";
    $separator = "\t";
    $row_end = "\n";
    header("Content-type: text/tab-separated-values");
    header("Content-Disposition: attachment; filename=\"BroadSheet-$group_id-$year.csv\"");
    break;
  case "TSV":
    $row_start = "";
    $separator = "\t";
    $row_end = "\n";
    header("Content-type: text/tab-separated-values");
    header("Content-Disposition: attachment; filename=\"BroadSheet-$group_id-$year.tsv\"");
    break;
  }
  $rows = student_broadsheet_data($group_id, $year, $extras);
  $smarty->assign("row_start", $row_start);
  $smarty->assign("row_end", $row_end);
  $smarty->assign("separator", $separator);
  $smarty->assign("broadsheet_data", $rows);
  $smarty->assign("format", $format);
  $smarty->assign("extras", $extras);
  $smarty->compile_check = false;
  $smarty->debugging = false;
  $smarty->display("admin/student_directory/student_broadsheet.tpl");
  switch($format)
  {
  case "TSV":
  case "TSVCSV":
    exit;
  }


}

function student_broadsheet_data($group_id, $year, $extras)
{
  global $log;

  // Array for all the data
  $rows = array();

  // Here are some titles possible
  $header_basic = array("Status", "Surname", "Title", "First name", "Student #", "Email",
			"Course Code", "Course Name", "Academic Tutor");
  $header_disability = array("Disability");
  $header_company_extra = array("Company", "Address1", "Address2", "Address3", "Town", "Locality",
				"Country", "Postcode", "C title", "C ftname", "C sname");
  $header_vacancy_extra = array("Vacancy", "Address1", "Address2", "Address3", "Town", "Locality",
        "Country", "Postcode");  
  $header_supervisor_extra = array("S title", "S ftname", "S sname", "S email");

  // Form header row
  $header = $header_basic;
  if(in_array("disability", $extras)) $header = array_merge($header, $header_disability);
  if(in_array("company", $extras)) $header = array_merge($header, $header_company_extra);
  if(in_array("vacancy", $extras)) $header = array_merge($header, $header_vacancy_extra);
  if(in_array("supervisor", $extras)) $header = array_merge($header, $header_supervisor_extra);
  if(in_array("assessment", $extras)) $header = array_merge($header, assessment_title_row($group_id));

  array_push($rows, $header);

  // First get a student list, seeking placement in a certain year
  
  $query = "SELECT DISTINCT id.*, students.*, cv_pdetails.course, cv_pdetails.surname, cv_pdetails.firstname, cv_pdetails.title, cv_pdetails.email FROM id " .
    "LEFT JOIN cv_pdetails ON id_number=cv_pdetails.id LEFT JOIN students " .
    "ON id_number=students.user_id where user='student' " .
    " AND students.year=$year " . 
    "ORDER BY students.status, cv_pdetails.surname";

  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch student list.");

  while($row = mysql_fetch_array($result))
  {
    $student_id = $row["id_number"];

    // Group check
    if(get_student_assessmentgroup($student_id) != $group_id) continue;
    
    // Auth check
    if(!(is_auth_for_student($student_id, "student", "list") &&
         is_auth_for_student($student_id, "student", "viewStatus"))) continue;

    $student_data = array($row["status"], $row["surname"], $row["title"],
			  $row["firstname"], $row["username"], $row["email"],
			  get_course_code($row["course"]),
			  get_course_name($row["course"]),
			  academic_tutor_for_student($student_id));

    if(in_array("disability", $extras))
    {
      $student_data = array_merge($student_data, array(
        $row['disability_code']));
    }

    if(in_array("company", $extras))
    {
      // Get more information on the company
      $company_data = company_data_for_student($student_id);
      $contact_data = primary_contact($company_data["company_id"]);

      $student_data = array_merge($student_data, array(
				    $company_data['name'],
				    $company_data['address1'],
				    $company_data['address2'],
				    $company_data['address3'],
				    $company_data['town'],
				    $company_data['locality'],
				    $company_data['country'],
				    $company_data['postcode'],
				    $contact_data['title'],
				    $contact_data['firstname'],
				    $contact_data['surname']));
    }

    if(in_array("vacancy", $extras))
    {
      // Get more information on the vacancy
      $vacancy_data = vacancy_data_for_student($student_id);

      $student_data = array_merge($student_data, array(
            $vacancy_data['description'],
            $vacancy_data['address1'],
            $vacancy_data['address2'],
            $vacancy_data['address3'],
            $vacancy_data['town'],
            $vacancy_data['locality'],
            $vacancy_data['country'],
            $vacancy_data['postcode']));
    }

    if(in_array("supervisor", $extras))
    {
      $sup_sql = "select * from placement where student_id=$student_id " .
	"order by created desc";
      $sup_result = mysql_query($sup_sql)
	or print_mysql_error2("Unable to fetch supervisor info.", $sup_sql);
      $supervisor_info = mysql_fetch_array($sup_result);
      mysql_free_result($sup_result);

      $student_data = array_merge($student_data, array(
				    $supervisor_info['supervisor_title'],
				    $supervisor_info['supervisor_firstname'],
				    $supervisor_info['supervisor_surname'],
				    $supervisor_info['supervisor_email']));
    }
    if(in_array("assessment", $extras))
    {
      $student_data = array_merge($student_data, assessment_row_for_student($student_id, $group_id));
    }
    array_push($rows, $student_data);
  }
  $log['admin']->LogPrint("assessment sheet launched on student directory");
  return($rows);
}




function assessment_title_row($group_id)
{
  $titles = array();
  $count = 0;

  $query = "SELECT * FROM assessmentregime WHERE group_id=$group_id ORDER BY year, end, start";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch assessments for course", $query);
  while($row = mysql_fetch_array($result))
  {
    $titles[$count++] = $row['student_description'];
    $titles[$count++] = 'Weighting';
  }
  mysql_free_result($result);
  $titles[$count++] = 'Total';
  return($titles);
}


function assessment_row_for_student($student_id, $group_id)
{
  $assresults = array();
  $count = 0;
  $total = 0;


  $query = "SELECT * FROM assessmentregime WHERE group_id=$group_id ORDER BY year, end, start";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch assessments for course", $query);
  while($row = mysql_fetch_array($result))
  {
    // Fetch total information for this...
    $sub_query = "SELECT * FROM assessmenttotals WHERE " .
                 "cassessment_id=" . $row['cassessment_id'] . " AND " .
                 "assessed_id=$student_id";
    $sub_result = mysql_query($sub_query)
      or print_mysql_error2("Unable to obtain marks", $sub_query);
    
    $sub_row = array();
    if(mysql_num_rows($sub_result))
    {
      $sub_row = mysql_fetch_array($sub_result);
      if($sub_row['percentage'] == NULL) $sub_row['percentage']="0";
      $assresults[$count++] = $sub_row['percentage'];
    }
    else
    {
      $assresults[$count++] = NULL;
    }      
    $assresults[$count++] = $row['weighting'];
    $total += ($sub_row['percentage'] * $row['weighting']);
    //$assresults[$count++] = get_user_name($sub_row['assessor_id']);
    mysql_free_result($sub_result);

  }
  mysql_free_result($result);
  $assresults[$count++] = $total;

  return($assresults);
}



function academic_tutor_for_student($student_id)
{
  $name = "Not allocated";
  $query = "SELECT surname, firstname, title FROM staff, staffstudent " .
           "WHERE staff.user_id = staffstudent.staff_id AND " .
           "staffstudent.student_id = $student_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch academic tutor name", $query);
  if(mysql_num_rows($result)){
    $row = mysql_fetch_array($result);
    $name = $row['title'] . " " . $row['firstname'] . " " . $row['surname'];
  }
  mysql_free_result($result);
  return($name);
}

function company_for_student($student_id)
{
  $name = "N/A";
  $query = "SELECT companies.name, companies.locality FROM placement, companies " .
           "WHERE placement.company_id=companies.company_id AND " .
           "placement.student_id=$student_id order by placement.jobstart DESC";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch company information", $query);
  if(mysql_num_rows($result)){
    $row = mysql_fetch_array($result);
    $name = $row['name'] . ", " . $row['locality'];
  }
  mysql_free_result($result);
  return($name);
}

function company_data_for_student($student_id)
{
  $name = "N/A";
  $query = "SELECT companies.* FROM placement, companies " .
           "WHERE placement.company_id=companies.company_id AND " .
           "placement.student_id=$student_id ORDER BY placement.jobstart DESC";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch company information", $query);
  if(mysql_num_rows($result)){
    $row = mysql_fetch_array($result);
  }
  mysql_free_result($result);
  return($row);
}


function vacancy_data_for_student($student_id)
{
  $name = "N/A";
  $query = "SELECT vacancies.* FROM placement, vacancies " .
           "WHERE placement.vacancy_id=vacancies.vacancy_id AND " .
           "placement.student_id=$student_id ORDER BY placement.jobstart DESC";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch vacancy information", $query);
  if(mysql_num_rows($result)){
    $row = mysql_fetch_array($result);
  }
  mysql_free_result($result);
  return($row);
}



function primary_contact($company_id)
{
  if(empty($company_id)) return ($data);
  $query = "SELECT * FROM companycontact WHERE company_id=$company_id " .
           "AND status='primary'";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to find primary contact", $query);
  if(mysql_num_rows($result))
  {
    $row = mysql_fetch_array($result);
    mysql_free_result($result);
    $contact_id = $row["contact_id"];

    $query = "SELECT * FROM contacts WHERE contact_id=$contact_id";
    $result = mysql_query($query)
      or print_mysql_error2("Unable to fetch primary contact data", $query);
    $data = mysql_fetch_array($result);
  }
  mysql_free_result($result);
  return($data);
}

    


/*
**      student_advancedsearchform
**
** Provides a flexible form for sophisticated searching of
** the student directory.
**
** Should be available to admins, and possibly course
** directors, dependant on policy...
*/
function student_advancedsearchform()
{
  global $PHP_SELF;
  global $showarchive;
  global $log;

echo "<script language=\"JavaScript\" type=\"text/javascript\">\n" .
     "<!--\n\n" .
     "function toggleAll(school, checked)\n" .
     "{\n" .
     "  for (i = 0; i < document.search.elements.length; i++) {\n" .
     "    if(school)\n" .
     "    {" .
     "      if(document.search.elements[i].value == school) \n" .
     "         document.search.elements[i].checked = checked;\n" .
     "    }\n" .
     "    else{\n" .
     "      if (document.search.elements[i].name.indexOf('cc') >= 0) {\n" .
     "          document.search.elements[i].checked = checked;\n" .
     "      }\n".
     "    }\n" .
     "  }\n" .
     "}\n" .
     "// -->\n" .
     "</script>\n";



  echo "<H2 ALIGN=\"CENTER\">Student Directory</H2>\n";
  echo "<H3 ALIGN=\"CENTER\">Advanced Search</H3>\n";

  echo "<FORM METHOD=\"POST\" NAME=\"search\" ACTION=\"" . $PHP_SELF . "\">\n";


  echo "<TABLE BORDER=\"0\" ALIGN=\"CENTER\">\n";

  echo "<TR><TH ALIGN=\"CENTER\" COLSPAN=2><B>Search criteria</B></TH></TR>\n";
  echo "<TR><TH>Name fragment or Student Number (if any)</TH>\n";
  echo "<TD><INPUT TYPE=\"TEXT\" NAME=\"search\" SIZE=\"20\"></TD></TR>\n";
  echo "<TR><TH>For placement in</TH>\n";
  echo "<TD><INPUT TYPE=\"TEXT\" NAME=\"year\" VALUE=\"" .
       (get_academic_year() + 1) . "\" SIZE=\"4\">";
  echo "</TD><TR>\n";


  // Provide select all, select none links...
  echo "<TR><TH ALIGN=\"CENTER\" COLSPAN=\"2\">Shows students from these courses ";
  echo "<a href=\"\" onclick=\"toggleAll(0, true); return false;\" " .
       "onmouseover=\"status='Select all'; return true;\">Select all</a> " .
       " | <a href=\"\" onclick=\"toggleAll(0, false); return false;\" " .
       "onmouseover=\"status='Select none'; return true;\">Select none</a></TH></TR>\n";
/*
  if(is_root())
  {
    echo "<TR><TD COLSPAN=\"2\"><B>Orphaned Students (in no course)</B></TD></TR>\n";
    echo "<TR><TD COLSPAN=\"2\"><INPUT TYPE=\"CHECKBOX\" NAME=\"cc0\" CHECKED>" .
         "Orphaned students.</TD></TR>\n";
  }
*/
  // Fetch list of schools...
  $query  = "SELECT * FROM schools ORDER BY school_name";
  $school_result = mysql_query($query)
    or print_mysql_error("Unable to fetch school list.", $query);

  while($school_row = mysql_fetch_array($school_result)){
    $auth_for_school = is_auth_for_school($school_row['school_id'], "student", "list");

    // Look further if the school isn't hidden by reason of being archived
    if(!(strstr($school_row["status"], "archive") && !$showarchive))
    {

      // We might not print anything to screen, so save it in a string
      $sb = "";      

      // Print the school title and links to select all or no courses in the block
      $sb .= "<TR>\n<TH COLSPAN=\"2\">";
      if(strstr($school_row["status"], "archive")) $sb .= "(A) ";
      $sb .= htmlspecialchars($school_row["school_name"]);
      $sb .= "</B> - Select <a href=\"\" onclick=\"toggleAll(" . $school_row["school_id"] . ", true); return false;\" " .
      "onmouseover=\"status='Select all'; return true;\">All</a> " .
      " | <a href=\"\" onclick=\"toggleAll(" . $school_row["school_id"] . ", false); return false;\" " .
      "onmouseover=\"status='Select none'; return true;\">None</a></TH></TR>\n";
      $sb .= "</TD></TR>\n";
      $query = "SELECT * FROM courses " .
               "WHERE school_id=" . $school_row["school_id"] . " ORDER BY course_name";
      $result = mysql_query($query)
        or print_mysql_error2("Unable to fetch courses.", $query);
    
      // Count how many courses will be displayed
      $courses_in_school = 0;
      while($row = mysql_fetch_array($result))
      { 
        // School level authorisation is enough, but otherwise check course level
        if($auth_for_school) $auth_for_course = TRUE;
        else $auth_for_course = is_auth_for_course($row["course_id"], "student", "list");

        // If we are permitted to see it, and we don't hide it for being archived
        if($auth_for_course && !(strstr($row["status"], "archive") && !$showarchive))
        {
          // Write the block, use the school in the "value" as a neat trick
          // for the above JavaScript to toggle by school only...
          $sb .= "<TR>\n";
          $sb .= "<TD COLSPAN=\"2\">";
          $sb .= "<INPUT TYPE=\"CHECKBOX\" NAME=\"cc" . $row[course_id];
          $sb .= "\" VALUE=\"" . $school_row["school_id"] . "\" CHECKED> ";
          if(strstr($row["status"], "archive")) $sb .= "(A) ";
          $sb .= htmlspecialchars($row["course_name"]) .
          " " . htmlspecialchars($row["course_desc"]);
          $sb .= "</TD>\n";
          $sb .= "</TR>\n";
          $courses_in_school++;
        }
      }
      // Show the block for the school ONLY if there is a course to show in it.
      if($courses_in_school) echo $sb;
    }
  }

  echo "<TR><TH ALIGN=\"CENTER\" COLSPAN=2><B>Sort criteria</B></TH></TR>\n";
  echo "<TR><TD ALIGN=\"CENTER\" COLSPAN=2>";
  echo "<INPUT TYPE=\"RADIO\" NAME=\"sort\" VALUE=\"name\" CHECKED> Name";
  echo "<INPUT TYPE=\"RADIO\" NAME=\"sort\" VALUE=\"id\"> Student ID";
  echo "<INPUT TYPE=\"RADIO\" NAME=\"sort\" VALUE=\"access\"> Last Access";
  echo "<INPUT TYPE=\"RADIO\" NAME=\"sort\" VALUE=\"status\"> Status";
  echo "</TD></TR>\n";

  echo "<tr><td><input type=\"checkbox\" name=\"timeline\"> Show Timelines";
  echo "</td></tr>";
  echo "<TR><TD ALIGN=\"CENTER\" COLSPAN=2>";
  echo "<INPUT TYPE=\"HIDDEN\" NAME=\"mode\" VALUE=\"STUDENT_ADVANCEDSEARCH\">\n";
  echo "<INPUT TYPE=\"submit\" NAME=\"button\" VALUE=\"Search\">\n";
  echo "<INPUT TYPE=\"reset\" VALUE=\"Reset\">\n";
  echo "</TD></TR>\n";

  echo "</TABLE>\n";

  echo "<P ALIGN=\"CENTER\"><A HREF=\"$PHP_SELF?mode=STUDENT_ADVANCEDSEARCHFORM";
  if($showarchive)
  {
    echo "\">Hide archived schools and courses (shown by (A))";
  }
  else
  {
    echo "&showarchive=1\">Show archived schools and courses";
  }
  echo "</A></P>\n";

}


/*
**      student_advancedsearch
**
** This script actually performs the advanced search that
** was configured with the above function.
*/
function student_advancedsearch()
{
  global $search;         // Search field (empty is ALL)
  global $sort;           // Sort field
  global $log;            // Reference to the log field
  global $year;           // Year to restrict search (empty is ALL)
  global $HTTP_POST_VARS; // All the POST variables (for checking course info)
  global $_POST;          // See above
  global $conf;           // Configuration
  global $PHP_SELF;       // Reference to this script
  global $smarty;

  $students = array();
  $show_timelines = $_REQUEST['timeline'];


  echo "<H3 ALIGN=\"CENTER\">Advanced Search</H3>\n";

  // Form Search criteria string
  if(!empty($search)){
    $searchc .= " (id.real_name LIKE '%" . $search . "%'" .
                "OR id.username LIKE '%" . $search . "%')";
  }

  // Form Sort criteria string
  $sortc = " ORDER BY surname";
  if($sort == 'id')     $sortc = " ORDER BY username";
  if($sort == 'access') $sortc = " ORDER BY last_time, surname";
  if($sort == 'status') $sortc = " ORDER BY status, surname";

  // Form basic query
  $query = "SELECT DISTINCT id.*, students.*, cv_pdetails.course FROM id " .
    "LEFT JOIN cv_pdetails ON id_number=cv_pdetails.id LEFT JOIN students " .
    "ON id_number=students.user_id where user='student'";

  // Search Criteria (Refining where given)
  if(!empty($searchc)) $query .= " AND" . $searchc;

  // Sort Criteria
  $query .= $sortc;

  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch student list", $query);

  $studentcount = 0;
  while($row = mysql_fetch_array($result)){
    $year_valid   = FALSE;
    $course_valid = FALSE;
    if(!empty($year)){
      // Check the student is applying this year
      if($year == $row["year"]) $year_valid = TRUE;
    }
    else $year_valid = TRUE;

    // Check if the course for this student is checked.
    $cc = "cc" . $row["course"];
    if(!empty($HTTP_POST_VARS[$cc])) $course_valid=TRUE;

    if($year_valid && $course_valid && 
       is_auth_for_student($row["id_number"], "student", "list")){
      $row['cv_link'] = cv_link($row['id_number']);
      array_push($students, $row);
      $studentcount++;
    }
  }

  $smarty->assign("studentcount", $studentcount);
  $smarty->assign("show_timelines", $show_timelines);
  $smarty->assign("students", $students);
  $smarty->display("admin/student_directory/advanced_search.tpl");

  $log['admin']->LogPrint("advanced search launched on student directory");
}

  


/*
**	student_simplesearch
**
** The default, and very simple search, shows all the people whose
** surname begins with a specific letter.
*/
function student_simplesearch()
{
  global $PHP_SELF;      // A reference to the current script
  global $letter;        // Letter selected if any
  global $conf;          // Access to the configuration
  global $log;           // Access to logging

  $log['access']->LogPrint("Students with surnames starting with $letter listed");

  printf("<H2 ALIGN=\"CENTER\">Student List</H2>\n");

  printf("<P ALIGN=\"CENTER\">Select the first letter of a surname below.</P>\n");

  printf("<P ALIGN=\"CENTER\">\n");
  for($loop = ord('A'); $loop <= ord('Z'); $loop++){
    printf("<A HREF=\"%s?letter=%s\">%s</A> ", $PHP_SELF, chr($loop), chr($loop));
  }
  printf("<A HREF=\"%s?letter=ALL\">ALL</A>", $PHP_SELF);
  printf("</P>\n");

  printf("<P ALIGN=\"CENTER\"><A HREF=\"$PHP_SELF?mode=STUDENT_ADVANCEDSEARCHFORM\">" .
         "Advanced Search</A></P>\n");

  printf("<P ALIGN=\"CENTER\"><A HREF=\"$PHP_SELF?mode=StudentManagementStatistics\">" .
         "Student Management Statistics</A></P>\n");

  echo "<P ALIGN=\"CENTER\"><A HREF=\"" . $conf['scripts']['admin']['newuser'] . "\">" .
         "Add an Invidual New Student</A></P>\n";


  
  if($letter=="ALL"){
    printf("<H2 ALIGN=\"CENTER\">All Students</H2>\n");
    // Form query for students with this surname
    $query = sprintf("SELECT id.*, cv_pdetails.course FROM id, cv_pdetails WHERE id.user='student'               
                      AND id.id_number = cv_pdetails.id ORDER BY cv_pdetails.surname", $letter);
  }
  else{
    printf("<H2 ALIGN=\"CENTER\">Students with Name Starting with %s</H2>\n", $letter);
    // Form query for students with this surname
    $query = sprintf("SELECT id.*, cv_pdetails.course FROM id, cv_pdetails WHERE id.user='student'
                      AND LEFT(cv_pdetails.surname, 1)='%s' 
                      AND id.id_number = cv_pdetails.id ORDER BY cv_pdetails.surname", $letter);
  }

  $result = mysql_query($query)
    or print_mysql_error("Unable to retrieve user list.\n");

  if(!mysql_num_rows($result)){
    printf("<P ALIGN=\"CENTER\">No matches for query.</P>\n");
  }
  printf("<table class=\"information\">");
  echo "<tr>\n";
  echo "<th class=\"list_header\">Name</th>\n";
  echo "<th class=\"list_header\">Student Number</th>\n";
  echo "<th class=\"list_header\">Last Access</th>\n";
  echo "<th class=\"list_header_action\">Action</th>\n";
  echo "</tr>\n";
  //printf("<TABLE COLS=\"3\" ALIGN=\"CENTER\" BORDER=\"1\">\n");
  $count = 0;
  while($row = mysql_fetch_array($result))
  {
    if(is_auth_for_student($row["id_number"], "student", "list"))
    {
      printf("<TR class=");
      if($count++ % 2)
      {
        echo "\"list_row_dark\"";
      }
      else
      {
        echo "\"list_row_light\"";
      }
      echo ">";
      printf("<TD>");
      echo "<A HREF=\"" . cv_link($row["id_number"]) . "\">" . $row["real_name"] . "</A>";
      printf("</TD><TD>%s</TD>", htmlspecialchars($row["username"]));
      printf("<TD>%s</TD>", htmlspecialchars($row["last_time"]));
      printf("<TD><A HREF=\"%s?mode=%s&student_id=%s\">Edit</A></TD>",
             $PHP_SELF,
             STUDENT_DISPLAYSTATUS,
             $row["id_number"]);
      printf("</TR>\n");
    }
  }
  printf("</TABLE>\n");
}


/**
* show all CVs the student has completed, and allow approval mechanisms
*
* @todo abstract this to allow internal CV handling once again
*/
function student_CV()
{
  global $PHP_SELF;
  global $smarty;
  global $conf;
  global $student_id;

  if(empty($student_id)){
    die_gracefully("This page should not be accessed without a student id.");
  }

  $query = sprintf("SELECT * FROM id WHERE id_number=%s", $student_id);
  $result = mysql_query($query)
    or print_mysql_error("Unable to access user information.");

  $row = mysql_fetch_array($result);
  mysql_free_result($result);

  printf("<H2 ALIGN=\"CENTER\">%s (%s)</H2>\n",
         htmlentities($row["real_name"]), htmlentities($row["username"]));

  printf("<H3 ALIGN=\"CENTER\">CV</H3>\n");

  if($row["user"]!="student"){
    print("<P ALIGN=\"CENTER\">Error, this is not a student.</P>\n");
    return;
  }

  print_wizard("CV");

  if(!is_auth_for_student($student_id, "student", "viewCV"))
    die_gracefully("You do not have permission to view this student's CV");

  // Get the completed CVs from the PDSystem. I have finally removed backwards compatibility here
  CV::populate_smarty_arrays($student_id);

  $smarty->display("admin/student_directory/list_student_cvs.tpl");

}

function student_show_channels()
{
 global $PHP_SELF;
  global $smarty;
  global $conf;
  global $student_id;

  if(empty($student_id)){
    die_gracefully("This page should not be accessed without a student id.");
  }

  $query = sprintf("SELECT * FROM id WHERE id_number=%s", $student_id);
  $result = mysql_query($query)
    or print_mysql_error("Unable to access user information.");

  $row = mysql_fetch_array($result);
  mysql_free_result($result);

  printf("<H2 ALIGN=\"CENTER\">%s (%s)</H2>\n",
         htmlentities($row["real_name"]), htmlentities($row["username"]));

  printf("<H3 ALIGN=\"CENTER\">Channels</H3>\n");

  if($row["user"]!="student"){
    print("<P ALIGN=\"CENTER\">Error, this is not a student.</P>\n");
    return;
  }

  print_wizard("Channels");
  if(!is_auth_for_student($student_id, "student", "viewCV"))
    die_gracefully("You do not have permission to view this student's CV");
  
  $channels = Channels::get_indexed_array($student_id);
  
  $smarty->assign("channels", $channels);
  $smarty->display("admin/student_directory/list_student_channels.tpl");
  

}


function student_displaycompanies()
{ 
  global $PHP_SELF;
  global $conf;
  global $student_id;
  
  if(empty($student_id)){
    die_gracefully("This page should not be accessed without a student id.");
  }
  
  $query = sprintf("SELECT * FROM id WHERE id_number=%s", $student_id);
  $result = mysql_query($query)
    or print_mysql_error("Unable to access user information.");
  
  $row = mysql_fetch_array($result);
  mysql_free_result($result);

  printf("<H2 ALIGN=\"CENTER\">%s (%s)</H2>\n",
         htmlentities($row["real_name"]), htmlentities($row["username"]));

  printf("<H3 ALIGN=\"CENTER\">Companies</H3>\n");
  
  if($row["user"]!="student"){
    print("<P ALIGN=\"CENTER\">Error, this is not a student.</P>\n");
    return;
  }

  $query = "SELECT * FROM students WHERE user_id=$student_id";
  $result = mysql_query($query)
    or print_mysql_error2("Error fetching extended information.", $query);
  $row = mysql_fetch_array($result);

  if($row["status"] == "Required") $required = TRUE;
  else $required = FALSE;
  mysql_free_result($result);

  print_wizard("Companies");

  if(!is_auth_for_student($student_id, "student", "viewCompanies"))
    die_gracefully("You do not have permission to view this student's companies");

  $query = "SELECT companies.*, companystudent.vacancy_id " .
           "FROM companies, companystudent " .
           "WHERE companies.company_id = companystudent.company_id AND " .
           "companystudent.student_id =" . $student_id;

  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch current student list.", $query);

  echo "<P ALIGN=\"CENTER\">Here is the list of companies selected by this student.\n</P>";

  if(mysql_num_rows($result)){
    echo "<TABLE ALIGN=\"CENTER\">\n";
    while($row = mysql_fetch_array($result))
    {
      echo "<TR><TD><A HREF=\"" . $conf['scripts']['company']['edit'] .
	"?company_id=" . $row["company_id"] . 
	"&vacancy_id=" . $row["vacancy_id"] . "\">" .
	htmlspecialchars($row["name"]);
      if(!empty($row["vacancy_id"]))
      {
	echo "<br><small>(" . htmlspecialchars(
	  get_vacancy_description($row["vacancy_id"])) . ")<small>";
      }
 
      echo "</A></TD>\n";

      if($required){
	echo "<TD><A HREF=\"" .
	  $PHP_SELF . "?mode=STUDENT_REMOVECOMPANY&student_id=" .
	  $student_id . "&company_id=" . $row["company_id"] .
	  "&vacancy_id=" . $row["vacancy_id"] .
	  "\">Remove</A></TD>\n" .           
	  "<TD><A HREF=\"" . $PHP_SELF .
	  "?mode=StudentInsertPlacement&student_id=" .
	  $student_id . "&company_id=" . $row["company_id"] .
	  "&vacancy_id=" . $row["vacancy_id"] .
	  "\">Place</A></TD>\n";
      }
      echo "</TR>\n";
    }
    echo "</TABLE>\n";
  }

  echo "<HR><P>This student has selected <B>" . mysql_num_rows($result) . 
       "</B> companies from a maximum allocation of <B>" .
       $conf[prefs][maxcompanies] . ".</B></P>\n";

  echo "<P ALIGN=\"CENTER\"><A HREF=\"" .
       $conf['scripts']['company']['directory'] . "?student_id=$student_id\">" .
       "Click here to select a company on behalf of this student.</A></P>\n";
}


function student_displaystatus()
{
  global $PHP_SELF;
  global $conf;
  global $student_id;
  global $smarty;


  if(empty($student_id)){
    die_gracefully("This page should not be accessed without a student id.");
  }

  $smarty->assign("student_id", $student_id);


  $query = sprintf("SELECT * FROM id WHERE id_number=%s", $student_id);
  $result = mysql_query($query)
    or print_mysql_error("Unable to access user information.");

  $row = mysql_fetch_array($result);
  mysql_free_result($result);

  printf("<H2 ALIGN=\"CENTER\">%s (%s)\n",
         htmlentities($row["real_name"]), htmlentities($row["username"]));
  $smarty->display("help/student_help_link.tpl");

  printf("</h2>\n");

  // Experimental
  $lastitem = new Lastitem('student', $student_id, 's:' . $row["real_name"], 
    $conf['scripts']['admin']['studentdir']   . "?mode=STUDENT_DISPLAYSTATUS&student_id=$student_id", $row["username"] . ":" . $row["real_name"]);
  $_SESSION['lastitems']->add($lastitem);


  $username = $row["username"];
  printf("<H3 ALIGN=\"CENTER\">Status</H3>\n");

  if($row["user"]!="student"){
    print("<P ALIGN=\"CENTER\">Error, this is not a student.</P>\n");
    //echo $row["user"];
    return;
  }

  print_wizard("Status");

  if(!is_auth_for_student($student_id, "student", "viewStatus"))
    die_gracefully("You do not have permission to view this student's status");

  $course_id   = get_student_course($student_id);
  $course_name = get_course_name($course_id);
  $course_code = get_course_code($course_id);

  // Get any information from cv_pdetails
  $cvpquery = "SELECT * FROM cv_pdetails WHERE id=$student_id";
  $cvpresult = mysql_query($cvpquery)
    or print_mysql_error2("Unable to obtain old cv details", $cvpquery);
  $cvprow = mysql_fetch_array($cvpresult);
  mysql_free_result($cvpresult);

  echo "<H4 ALIGN=\"CENTER\">" .
       htmlspecialchars("Course : $course_code ($course_name)") .
       "</H4>\n";

  $status_query = "SELECT * FROM students WHERE user_id=$student_id";
  $status_result = mysql_query($status_query)
    or print_mysql_error2("Unable to get status for student.", $status_query);
  $status = mysql_fetch_array($status_result);

  echo "<FORM METHOD=\"POST\"" .
       "ACTION=\"$PHP_SELF?mode=STUDENT_UPDATESTATUS&student_id=$student_id\">\n";
  echo "<TABLE ALIGN=\"CENTER\">\n";
  echo "<TR><TH>Title</th><TD><INPUT NAME=\"title\" SIZE=\"5\" " .
       "VALUE=\"" . $cvprow['title'] . "\"></TD></TR>\n";
  echo "<TR><TH>Firstname</TH><TD><INPUT NAME=\"firstname\" SIZE=\"20\" " .
       "VALUE=\"" . $cvprow['firstname'] . "\"></TD></TR>\n";
  echo "<TR><TH>Surname</TH><TD><INPUT NAME=\"surname\" SIZE=\"20\" " .
       "VALUE=\"" . $cvprow['surname'] . "\"></TD></TR>\n";
  echo "<TR><TH>Email</TH><TD><INPUT NAME=\"email\" SIZE=\"30\" " .
       "VALUE=\"" . $cvprow['email'] . "\"></TD></TR>\n";
  echo "<TR><TH>Course</TH>";
  echo "<TD><SELECT NAME=\"course\">\n";
  echo "<OPTION VALUE=\"0\"";
  if(empty($course_id)) echo " SELECTED";
  echo ">--- No course selected ---</OPTION>\n";
  $cquery = "SELECT * FROM courses ORDER BY course_code, course_name";
  $cresult = mysql_query($cquery)
    or print_mysql_error2("Unable to obtain course listing", $cquery);
  while($crow = mysql_fetch_array($cresult))
  {
    echo "<OPTION VALUE=\"" . $crow["course_id"] . "\"";
    if($crow["course_id"] == $course_id) echo " SELECTED";
    echo ">" . htmlspecialchars($crow["course_code"] . ": " . $crow["course_name"]) .
    "</OPTION>\n";
  }
  mysql_free_result($cresult);
  echo "<TR><TH>Year seeking placement</TH>" .
       "<TD><INPUT NAME=\"year\" SIZE=\"4\" VALUE=\"" .
       $status['year'] . "\"></TD></TR>\n";
  echo "<TR><TH>Terms agreed</TH><TD>";
  if(strstr($status['progress'], "disclaimer")) echo "Yes";
  else echo "No";
  echo "</TD></TR>\n";
  echo "<TR><TH>Placement Status</TH>";

  echo "<TD><SELECT NAME=\"statuscode\">\n";

  echo "<OPTION";
  if($status['status'] == "Required") echo " SELECTED>";
  else echo ">";
  echo "Required</OPTION>\n";

  echo "<OPTION";
  if($status['status'] == "Placed") echo " SELECTED>";
  else echo ">";
  echo "Placed</OPTION>\n";

  echo "<OPTION";
  if($status['status'] == "Exempt Applied") echo " SELECTED>";
  else echo ">";
  echo "Exempt Applied</OPTION>\n";

  echo "<OPTION";
  if($status['status'] == "Exempt Given") echo " SELECTED>";
  else echo ">";
  echo "Exempt Given</OPTION>\n";

  echo "<OPTION";
  if($status['status'] == "No Info") echo " SELECTED>";
  else echo ">";
  echo "No Info</OPTION>\n";

  echo "<OPTION";
  if($status['status'] == "Left Course") echo " SELECTED>";
  else echo ">";
  echo "Left Course</OPTION>\n";

  echo "<OPTION";
  if($status['status'] == "Suspended") echo " SELECTED>";
  else echo ">";
  echo "Suspended</OPTION>\n";

  echo "<OPTION";
  if($status['status'] == "To final year") echo " SELECTED>";
  else echo ">";
  echo "To final year</OPTION>\n";

  echo "<OPTION";
  if($status['status'] == "Not Eligible") echo " SELECTED>";
  else echo ">";
  echo "Not Eligible</OPTION>\n";

  echo "</SELECT></TD>";
  echo "<TR><TH>Academic Tutor</TH><TD>\n";
  // Determine if there is an academic tutor...
  $tutor_query = "SELECT staff_id FROM staffstudent WHERE student_id=" . $student_id;
  $tutor_result = mysql_query($tutor_query) or
    print_mysql_error2("Unable to find tutor information.", $tutor_query);
  if(mysql_num_rows($tutor_result)){
    $tutor_row = mysql_fetch_row($tutor_result);
    $tutor_id = $tutor_row[0];
  }
  else $tutor_id = 0;
  mysql_free_result($tutor_result);

  // Fetch whole list...
  $tutor_query = "SELECT surname, firstname, title, user_id FROM staff ORDER BY surname, firstname";
  $tutor_result = mysql_query($tutor_query) or
    print_mysql_error2("Unable to fetch staff details.", $tutor_query);

  echo "<SELECT NAME=\"tutor_id\">\n";
  echo "<OPTION VALUE=\"0\">No tutor selected</OPTION>\n";
  while($tutor_row=mysql_fetch_array($tutor_result)){
    echo "<OPTION";
    if($tutor_row["user_id"] == $tutor_id) echo " SELECTED";
    echo " VALUE=\"" . $tutor_row["user_id"] . "\">" .
      htmlspecialchars($tutor_row["surname"] . ", " . $tutor_row["firstname"] . ", " . $tutor_row["title"]) .
      "</OPTION>\n";
  }
  mysql_free_result($tutor_result);
  echo "</SELECT>\n</TD></TR>";

  echo "<TR><TH>Last Access</TH><TD>" .
       htmlspecialchars($row["last_time"]) . "</TD></TR>\n";
  echo "<TR><TH>Last viewed homepage</TH><TD>" .
       htmlspecialchars($row["last_index"]) . "</TD></TR>\n";

  echo "<TR><TD ALIGN=\"CENTER\" COLSPAN=\"2\">" .
       "<INPUT TYPE=\"submit\" NAME=\"button\" VALUE=\"Submit Changes\">" .
       "<INPUT TYPE=\"reset\" VALUE=\"Reset\"></TD></TR>\n";


  echo "</TABLE>";
  echo "</FORM>";

  if($status['status'] == "Left Course"){
    echo "<P ALIGN=\"CENTER\">Note that this student cannot access the system by reason of their status</P>";
  }

  echo "<h3 align=\"center\">Timeline</h3>";
  echo "<p align=\"center\"><IMG ALIGN=\"CENTER\" BORDER=\"0\" ALT=\"Timeline\" SRC=\"" . 
       $conf['scripts']['student']['timeline'] .
       "?student_id=" . $student_id . "\"></P>\n";

  echo "<h3 align=\"center\">Photo</h3>";
  echo "<P ALIGN=\"CENTER\">";

  echo "<A HREF=\"" . $conf['scripts']['user']['photos'] . 
       "?mode=full&user_id=" . $username . "\">" .
       "<IMG ALIGN=\"CENTER\" BORDER=\"0\" ALT=\"Photo\" SRC=\"" . 
       $conf['scripts']['user']['photos'] .
       "?user_id=" . $username . "\"></A></p>\n";



  $query = "SELECT * FROM placement WHERE student_id=$student_id order by jobstart DESC";
  $result = mysql_query($query)
    or print_mysql_error2("Error querying placement information.", $query);
  $form = array();
  $form['name'] = "placementform";
  $form['method'] = "post";
  $form['action'] = $_SERVER['PHP_SELF'];

  $form['hidden'] = array();
  $form['hidden']['student_id']=$student_id;
  $form['hidden']['mode']="STUDENT_UPDATEPLACEMENT";


  $placements = array();
  while($placement = mysql_fetch_array($result))
  {
    // Augment the record...
    $placement['description'] = get_vacancy_description($placement['vacancy_id']);
    $placement['company_name'] = get_company_name($placement['company_id']);
    array_push($placements, $placement);
  }
  $smarty->assign("form", $form);
  $smarty->assign("placements", $placements);
  $smarty->display('placement_form.tpl');



  assessment_regime($student_id);

  assessment_show_other_assessors($student_id);



  if(is_admin() && is_auth_for_student($student_id, "student", "create"))
  {
    echo "<P ALIGN=\"CENTER\">";
    echo "<A HREF=\"$PHP_SELF?mode=STUDENT_NEWPASSWORD&student_id=$student_id\">" .
         "Click here to send a new password to this student.</A></P>\n";
  }
}


/*
**	student_updatestatus
**
** This function handles the changing of status when selected
** using the form above.
*/
function student_updatestatus()
{
  global $PHP_SELF;
  global $student_id;
  global $tutor_id;
  global $statuscode;
  global $year;
  global $log;

  global $surname, $firstname, $title, $course, $email;

  if(!is_auth_for_student($student_id, "student", "editStatus"))
    die_gracefully("You do not have permission to edit this student's status");
 
  $placementon  = FALSE;
  $placementoff = FALSE;
  
  if(empty($student_id))
    die_gracefully("You cannot access this page without a student id.");

  if(empty($year))
    die_gracefully("You must specify a year in which placement is sought.");

  $query = "SELECT user FROM id WHERE id_number=$student_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch usertype", $query);

  $row = mysql_fetch_row($result);
  if($row[0]!='student') die_gracefully("This does not seem to be a student account.");
  mysql_free_result($result);

  // Attempt to fetch current status
  $query = "SELECT * FROM students WHERE user_id=$student_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch current status.", $query);
  $row = mysql_fetch_array($result);

  if(!mysql_num_rows($result)){
    // No current status, write a new record...
    $query = "INSERT INTO students (user_id, year, status, progress) VALUES($student_id, $year, " .
             make_null($statuscode) . ", NULL)";

    mysql_query($query)
      or print_mysql_error2("Unable to add new status record.");
    $log['admin']->LogPrint("New status record ($statuscode, $year) written for student " .
                             get_user_name($student_id));
    if($statuscode == "Placed") $placementon = TRUE;
  }
  else{
    // Check for major changes of status
    if(($statuscode == "Placed") && $row["status"] != "Placed") $placementon = TRUE; 
    if(($statuscode != "Placed") && $row["status"] == "Placed") $placementoff = TRUE;

    if(!$placementon){
      $query = "UPDATE students SET year=$year, " .
               "status=" . make_null($statuscode) . " WHERE user_id=" . $student_id;
      mysql_query($query)
        or print_mysql_error2("Unable to update status record", $query);
      $log['admin']->LogPrint("Updated status ($statuscode, $year) for student " . get_user_name($student_id));
    }
  }

  // Update Academic Tutor
  $query = "SELECT * FROM staffstudent WHERE student_id=" . $student_id;
  $result = mysql_query($query)
    or print_mysql_error2("Unable to update tutor information.", $query);
  if(mysql_num_rows($result))
  {
    // There's already some sort of data there...
    if(!$tutor_id){
      // looks like the tutor has been deallocated.
      $query = "DELETE FROM staffstudent WHERE student_id=" . $student_id;
      mysql_query($query) or
        print_mysql_error2("Unable to remove academic tutor.", $query);
    }
    else{
      // change what's there
      $query = "UPDATE staffstudent SET staff_id=" . $tutor_id .
               " WHERE student_id=" . $student_id;
      mysql_query($query) or
        print_mysql_error2("Unable to alter academic tutor.", $query);
    }
  }
  else{
    // nothing there yet...
    if($tutor_id){
      // and there is something to add
      $query = "INSERT INTO staffstudent VALUES(" . $tutor_id . ", " . $student_id . ")";
      mysql_query($query) or
        print_mysql_error2("Unable to add academic tutor.", $query);
    }
  }
 

  // Write CV p_details (legacy support)
  $query = "SELECT * FROM cv_pdetails WHERE id=$student_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to query cv_pdetails", $query);
  if(empty($course)) $course = 0;
  if(!mysql_num_rows($result))
  {
    // No entry, make a blank one...
    $squery = "INSERT INTO cv_pdetails " .
              "(id, course, surname, firstname, title, email, pob, nationality) " .
              "VALUES($student_id, $course, " . make_null($surname) .
              ", " . make_null($firstname) .
              ", " . make_null($title) .
              ", " . make_null($email) . ", '', '')";
    mysql_query($squery)
      or print_mysql_error2("Unable to update cv_pdetails", $squery);
  }
  else
  {
    // Update existing entry
    $squery = "UPDATE cv_pdetails SET " .
              "surname=" . make_null($surname) . ", " .
              "firstname=" . make_null($firstname) . ", " .
              "title=" . make_null($title) . ", " .
              "email=" . make_null($email) . ", " .
              "course=$course WHERE id=$student_id";
    mysql_query($squery)
      or print_mysql_error2("Unable to update cv_pdetails", $squery);
  }

  if($placementon){
      // Finally, invalidate the timestamp on the timeline - causing it to be regenerated
      $sql = "update timelines set last_updated='00000000000000' where student_id=$student_id";
      mysql_query($sql)
        or print_mysql_error2("Unable to reset timeline", $sql);
     student_placecompanyform(0);
     return;
  }
  if($placementoff){
    // Finally, invalidate the timestamp on the timeline - causing it to be regenerated
    $sql = "update timelines set last_updated='00000000000000' where student_id=$student_id";
    mysql_query($sql)
      or print_mysql_error2("Unable to reset timeline", $sql);

     $log['admin']->LogPrint("Removed student " . get_user_name($student_id) . " from placement.");
  }
  student_displaystatus();
  echo "<P ALIGN=\"CENTER\">Status updated.</P>\n";
}

/*

function student_unplacecomanyform($company_id)
{
  global $student_id;
  global $year;
  global $log;

  


}
*/


function student_updateplacement()
{
  global $student_id;

  $student_id = $_REQUEST['student_id'];
  $placement_id = $_REQUEST['placement_id'];
  $position = $_REQUEST['position'];
  $salary = $_REQUEST['salary'];
  $voice = $_REQUEST['voice'];
  $email = $_REQUEST['email'];
  $jobstart = $_REQUEST['jobstart'];
  $jobend = $_REQUEST['jobend'];
  $supervisor_title = $_REQUEST['supervisor_title'];
  $supervisor_firstname = $_REQUEST['supervisor_firstname'];
  $supervisor_surname = $_REQUEST['supervisor_surname'];
  $supervisor_voice = $_REQUEST['supervisor_voice'];
  $supervisor_email = $_REQUEST['supervisor_email'];
  $supervisor_oldemail = $_REQUEST['supervisor_oldemail'];

  
  global $log;
  
  if(!is_auth_for_student($student_id, "student", "editStatus"))
    die_gracefully("You do not have permission to edit this student's status");

  // Update placement record itself;
  $query = "UPDATE placement SET " .
    "  position=" . make_null($position) .
    ", salary=" . make_null($salary) .
    ", voice=" . make_null($voice) .
    ", email=" . make_null($email) .
    ", jobstart=" . make_null($jobstart) .
    ", jobend=" . make_null($jobend) .
    ", supervisor_title=" . make_null($supervisor_title) .
    ", supervisor_firstname=" . make_null($supervisor_firstname) .
    ", supervisor_surname=" . make_null($supervisor_surname) .
    ", supervisor_email=" . make_null($supervisor_email) .
    ", supervisor_voice=" . make_null($supervisor_voice) .
    " WHERE placement_id=$placement_id";

  mysql_query($query) or
    print_mysql_error2("Unable to update placement record.");

  if(empty($supervisor_oldemail) && !empty($supervisor_email))
  {
    // Attempt to create a new user and email them...
    create_supervisor($placement_id);
  }

  student_displaystatus();
  echo "<P ALIGN=\"CENTER\">Changes accepted.</P>\n";

  $log["admin"]->LogPrint("placement information updated for " . get_user_name($student_id));
}     



/*
**	student_placecompanyform()
**
** This function displays a form allowing a successful student
** placement to be recorded.
**
*/
function student_placecompanyform()
{
  global $student_id;
  global $log;
  global $PHP_SELF;

  global $smarty;

  if(!is_auth_for_student($student_id, "student", "editStatus"))
    die_gracefully("You do not have permission to edit this student's status");

  $query = "SELECT * FROM id WHERE id_number=$student_id";
  $result = mysql_query($query)
    or print_mysql_error2("Failed to get basic data.", $query);
  $row = mysql_fetch_array($result);

  printf("<H2 ALIGN=\"CENTER\">%s (%s)</H2>\n",
         htmlentities($row["real_name"]), htmlentities($row["username"]));

  printf("<H3 ALIGN=\"CENTER\">Status - Student being Placed</H3>\n");

  print_wizard("Status");
  mysql_free_result($result);

  $query = "SELECT * FROM cv_pdetails WHERE id=$student_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch student data.", $query);

  $row = mysql_fetch_array($result);
  $email = $row["email"];

  $form = array();
  $form['method'] = 'POST';
  $form['action'] = $_SERVER['PHP_SELF'];

  $form['hidden'] = array();
  $form['hidden']['student_id'] = $student_id;
  $form['hidden']['mode'] = "StudentInsertPlacement";

  $form['data'] = array();
  $form['data']['vacancies'] = array();
  $form['data']['vacancies']['titles'] = array();
  $form['data']['vacancies']['ids'] = array();


  $vacancies = get_vacancies_for_student($student_id);
  foreach($vacancies as $vacancy)
  {
    if(empty($vacancy['vacancy_id']))
    {
      $title = $vacancy['company_name'] . " (Old style application)";
      $title = htmlspecialchars($title);
      $id = 'c' . $vacancy['company_id'];
    }
    else
    {
      $title = $vacancy['description'] . " (" . $vacancy['company_name'] . ")";
      $title = htmlspecialchars($title);
      $id = $vacancy['vacancy_id'];
    }

    array_push($form['data']['vacancies']['titles'], $title);
    array_push($form['data']['vacancies']['ids'], $id);

  }

  $smarty->assign('form', $form);
  $smarty->assign('vacancies', $vacancies);
  $smarty->display('placement_form_insert.tpl');

}


function student_insert_placement()
{
  global $log;
  global $student_id;

  $student_id = $_REQUEST['student_id'];
  $company_id = $_REQUEST['company_id'];
  $vacancy_id = $_REQUEST['vacancy_id'];

  if(!is_auth_for_student($student_id, "student", "editStatus"))
    die_gracefully("You do not have permission to edit this student's status");

  $now = date("YmdHis");

  if(substr($vacancy_id, 0, 1) == 'c')
  {
    $company_id = substr($vacancy_id, 1);
    unset($vacancy_id);
  }

  // Check if it's a company first (legacy support)
  if(!empty($company_id) && empty($vacancy_id))
  {
    if(!is_numeric($company_id)) die_gracefully("Illegal company_id");
    
    $sql = "INSERT INTO placement (student_id, company_id, created) " .
      "VALUES($student_id, $company_id, $now)";

    $vacancy_name = get_company_name($company_id);
  }
  else
  {
    // Mainstream case... we have a vacancy...
    $sql = "SELECT * FROM vacancies WHERE vacancy_id=$vacancy_id";
    $result = mysql_query($sql)
      or print_mysql_error2("Unable to fetch vacancy information", $sql);
    $vacancy = mysql_fetch_array($result);
    mysql_free_result($result);

    $sql = "INSERT INTO placement " .
      "(position, jobstart, jobend, salary, created, company_id, vacancy_id, student_id) " .
      "VALUES(" . make_null(addslashes($vacancy['description'])) .
      ", " . make_null(addslashes($vacancy['jobstart'])) .
      ", " . make_null(addslashes($vacancy['jobend'])) .
      ", " . make_null(addslashes($vacancy['salary'])) .
      ", $now, " .
      $vacancy['company_id'] .
      ", $vacancy_id, $student_id)";

    $vacancy_name = get_company_name($vacancy['company_id']) . " (" .
      get_vacancy_description($vacancy['vacancy_id']) . ")";
  }
  mysql_query($sql)
    or print_mysql_error2("Unable to add placement record", $sql);

  // Still here? Change the status to placed...
  $sql = "UPDATE students SET status='placed' WHERE user_id=$student_id";
  mysql_query($sql)
    or print_mysql_error2("Unable to update student status", $sql);

  $log['admin']->LogPrint("Student " . get_user_name($student_id) . " set to placed with $vacancy_name");
  
  student_displaystatus();
}


// New function for template based form.

function get_vacancies_for_student($student_id)
{
  $sql = "SELECT * FROM companystudent " .
    "WHERE student_id=$student_id ORDER BY created";

  $result = mysql_query($sql)
    or print_mysql_error2("Unable to fetch vacancy list for student.", $sql);

  $vacancies = array();
  
  while($vacancy = mysql_fetch_array($result))
  {
    // Augment with some names
    $vacancy['description'] = get_vacancy_description($vacancy['vacancy_id']);
    $vacancy['company_name'] = get_company_name($vacancy['company_id']);
    array_push($vacancies, $vacancy);
  }
  return($vacancies);
}


function student_edit_placement()
{
  global $smarty;

  $student_id = $_REQUEST['student_id'];

  // Is there a specific placement to edit?
  $placement_id = $_REQUEST['placement_id'];




  // If not we are to create a new one
  if(!$placement_id)
  {
    $vacancies = get_vacancies_for_student($student_id);
    foreach($vacancies as $vacancy)
    {
      if(empty($vacancy['vacancy_id']))
      {
	$title = $vacancy['company_name'] . " (Old style application)";
	$title = htmlspecialchars($title);
	$id = 'c' . $vacancy['company_id'];
      }
      else
      {
	$title = $vacancy['description'] . " (" . $vacancy['company_name'] . ")";
	$title = htmlspecialchars($title);
	$id = $vacancy['vacancy_id'];
      }

      array_push($form['data']['vacancies']['titles'], $title);
      array_push($form['data']['vacancies']['ids'], $id);

    }


  }

  $smarty->assign('vacancies', $vacancies);
  $smarty->assign('form', $form);

  $smarty->display('placement_form.tpl');
 
    
}


function student_delete_placement()
{
  global $student_id;
  global $smarty;
  global $log;

  $confirmed = $_REQUEST['confirmed'];
  $placement_id = $_REQUEST['placement_id'];

  if(empty($placement_id))
  {
    die_gracefully("This page requires a placement_id value");
  }

  $sql = "select * from placement where placement_id=$placement_id";
  $result = mysql_query($sql)
    or print_mysql_error2("Unable to fetch placement info", $sql);
  $placement_info = mysql_fetch_array($result);
  $student_id = $placement_info['student_id'];
  $placement_info['student_name'] = get_user_name($student_id);

  if(!is_admin() || !is_auth_for_student($student_id, "student", "editStatus"))
  {
    die_gracefully("You do not have permission for this action");
  }


  if(!$confirmed)
  {
    $smarty->assign("placement_info", $placement_info);
    $smarty->display("admin/student_directory/student_delete_placement.tpl");

    
  }
  else
  {
    $sql = "delete from placement where placement_id=$placement_id";
    mysql_query($sql)
      or print_mysql_error2("Unable to delete placement record", $sql);
    $log['admin']->LogPrint("Deleting placement " . 
			    $placement_info['position'] .
			    " for student " . get_user_name($student_id));
    $sql = "delete from id where username='supervisor_$placement_id'" .
      " and user='supervisor'";
    mysql_query($sql)
      or print_mysql_error2("Unable to delete supervisor", $sql);
    $log['admin']->LogPrint("Deleting placement industrial supervisor for " . 
			    $placement_info['position'] .
			    " for student " . get_user_name($student_id));
    student_displaystatus();

  }
}


/*
**	student_removecompany()
**
** This function removes a student from placement, which should
** only be done in unusual circumstances.
*/
function student_removecompany()
{
  global $student_id;
  global $company_id;
  global $vacancy_id;
  global $confirm;
  global $log;

  if(!is_auth_for_student($student_id, "student", "editCompanies"))
    die_gracefully("You do not have permission to edit this student's companies");

  if(!$confirm){
    echo "<H3 ALIGN=\"CENTER\">Are you sure?</H3>\n" .
         "<P>You have selected to remove the company " .
         get_company_name($company_id) . " from the list of " .
         "companies for the student " . get_user_name($student_id) .
         ". Please note that this should be done with care as the " .
         "company involved may be unhappy with the student vanishing " .
         "from their list.</P>\n";
   
    echo "<P ALIGN=\"CENTER\">" .
         "<A HREF=\"" . $PHP_SELF . "?mode=STUDENT_REMOVECOMPANY" .
         "&student_id=$student_id" .
      "&vacancy_id=$vacancy_id" .
      "&company_id=$company_id&confirm=1\">" .
         "Click here to confirm this action</A></P>\n";
  }
  else{
    $query = "DELETE FROM companystudent WHERE company_id=$company_id " .
             "AND student_id=$student_id";
    if(!empty($vacancy_id))
    {
      $query .= " AND vacancy_id=$vacancy_id";
    }
    else
    {
      $query .= " AND vacancy_id <=> NULL";
    }
    mysql_query($query)
      or print_mysql_error2("Unable to remove company from student listing.");

    $log['admin']->LogPrint("Removed company " . get_company_name($company_id) .
      " from listed companies for student " . get_user_name($student_id));

    echo "<P ALIGN=\"CENTER\">" . get_company_name($company_id) . 
         " removed from list</P>\n".

    student_displaycompanies();
  } 
}


  



/*
**	print_wizard()
**
** This function provides the wizard buttons at the top
** of the edit sections of the script. These are
** currently
**
** 0 : Edit CV
** 1 : Edit Companies
** 2 : Edit Status
** 3 : Notes
*/
function print_wizard($item)
{
  global $PHP_SELF;
  global $conf;
  global $student_id;
  global $smarty;

  $wizard2 = new TabbedContainer($smarty, 'tabs');
  $wizard2->addTab('CV', $_SERVER['PHP_SELF'] . "?mode=STUDENT_SHOWCV&student_id=$student_id");
  $wizard2->addTab('Channels', $_SERVER['PHP_SELF'] . "?mode=STUDENT_ShowChannels&student_id=$student_id");
  $wizard2->addTab('Companies', $_SERVER['PHP_SELF'] . "?mode=STUDENT_DISPLAYCOMPANIES&student_id=$student_id");
  $wizard2->addTab('Status', $_SERVER['PHP_SELF'] . "?mode=STUDENT_DISPLAYSTATUS&student_id=$student_id");
  $wizard2->addTab('Notes', $_SERVER['PHP_SELF'] . "?mode=STUDENT_NOTES&student_id=$student_id");

  // Transitionary code
  echo "<div name=\"tabbedContainer\" align=\"center\">\n";
  $wizard2->displayTab($item);
  echo "</div>\n";
}

/**
* check user input, approve CV and then show CV tab again
*/
function approve_cv()
{
  $student_id = $_REQUEST['student_id'];
  $template_id = $_REQUEST['template_id'];

  if(empty($student_id) || empty($template_id))
    die_gracefully("student_id and template_id must not be empty");

  CV::approve_cv($student_id, $template_id);
  // Show tab again
  student_CV();
}


/**
* check user input, revoke CV and then show CV tab again
*/
function revoke_cv()
{
  $student_id = $_REQUEST['student_id'];
  $template_id = $_REQUEST['template_id'];

  if(empty($student_id) || empty($template_id))
    die_gracefully("student_id and template_id must not be empty");

  CV::revoke_cv($student_id, $template_id);
  // Show tab again
  student_CV();
}

?>