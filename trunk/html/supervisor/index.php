<?php

/**
**  index.php
**
** This is the index page for authenticated industrial supervisors
**
** Initial coding : Colin Turner
**
*/

// The include files 
include('common.php');		
include('authenticate.php');
include('lookup.php');	
include('assessment.php');

// Connect to the database on the server
db_connect()
  or die("Unable to connect to server");

// Authenticate user so that the right people see the right thing
auth_user("supervisor");

$page = new HTMLOPUS("Industrial Supervisor", "home");

if(!is_admin())
{
  // Supervisors should of course only see their own record
  $supervisor_id = get_id();
}
if(empty($supervisor_id))
{
  die_gracefully("This page cannot be accessed without a supervisor id");
}

// Work out which placement this person cares for
$supervisor_name = get_user_name($supervisor_id);
$login_name = get_login_name($supervisor_id);
$placement_id = str_replace("supervisor_", "", $login_name);

// Obtain placement info
$sql = "select * FROM placement WHERE placement_id=$placement_id";
$result = mysql_query($sql)
  or print_mysql_error2("Unable to fetch placement info.", $sql);
$placement_info = mysql_fetch_array($result);
mysql_free_result($result);

if(is_admin())
{
  // Now we have the student id, ensure any visiting admin has permission
  if(!is_auth_for_student($placement_info['student_id'], 'student', 'viewStatus'))
    die_gracefully("Sorry, you do not have permission to view this page");
}

$placement_info['company_name'] = get_company_name($placement_info['company_id']);
$placement_info['vacancy_description'] = get_vacancy_description($placement_info['vacancy_description']);

$student_name = get_user_name($placement_info['student_id']);

if($mode=="SupervisorUpdate")
{
  if(is_admin() && !is_auth_for_student($placement_info['student_id'], "student", "editStatus"))
    die_gracefully("Sorry, you do not have permission for this action.");
    
  $supervisor_title = $_REQUEST['supervisor_title'];
  $supervisor_firstname = $_REQUEST['supervisor_firstname'];
  $supervisor_surname = $_REQUEST['supervisor_surname'];
  $supervisor_email = $_REQUEST['supervisor_email'];
  $supervisor_voice = $_REQUEST['supervisor_voice'];
  
  if(empty($supervisor_title))
    die_gracefully("Supervisor title may not be empty");
  if(empty($supervisor_surname))
    die_gracefully("Supervisor surname may not be empty");
    
  $sql = "update placement set " .
    "supervisor_title=" . make_null($supervisor_title) .  ", " .
    "supervisor_firstname=" . make_null($supervisor_firstname) .  ", " .
    "supervisor_surname=" . make_null($supervisor_surname) .  ", " .
    "supervisor_email=" . make_null($supervisor_email) .  ", " .
    "supervisor_voice=" . make_null($supervisor_voice) . 
    " where placement_id=$placement_id";
    
  mysql_query($sql)
      or print_mysql_error2("Unable to update supervisor details", $sql);
      
  // Obtain refreshed placement info
  $sql = "select * FROM placement WHERE placement_id=$placement_id";
  $result = mysql_query($sql)
    or print_mysql_error2("Unable to fetch placement info.", $sql);
  $placement_info = mysql_fetch_array($result);
  mysql_free_result($result);
  
  $supervisor_name = $placement_info['supervisor_title'] . " " . 
    $placement_info['supervisor_firstname'] . " " .
    $placement_info['supervisor_surname'];
    
  $sql = "update id SET real_name=" . make_null($supervisor_name) . " where id_number=$supervisor_id";
  mysql_query($sql)
    or print_mysql_error2("Unable to update real name field", $sql);
    
}

$form = array();
$form['name'] = "placementform";
$form['method'] = "post";
$form['action'] = $_SERVER['PHP_SELF'];

$form['hidden'] = array();
$form['hidden']['supervisor_id']=$supervisor_id;
$form['hidden']['mode']="SupervisorUpdate";

$smarty->assign("form", $form);
$smarty->assign("supervisor_name", $supervisor_name);
$smarty->assign("supervisor_id", $supervisor_id);
$smarty->assign("student_name", $student_name);
$smarty->assign("student_id", $placement_info['student_id']);
$smarty->assign("placement_info", $placement_info);

$smarty->display("supervisor/welcome.tpl");
output_help("SupervisorHome");
$smarty->display("supervisor/placement_form.tpl");

$student_id = $placement_info['student_id'];
  // Can we obtain academic tutor information?
  $query = "SELECT staff.* FROM staff, staffstudent WHERE staff.user_id=" .
           "staffstudent.staff_id AND staffstudent.student_id=$student_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to obtain academic tutor information.", $query);

  if(mysql_num_rows($result))
  {
    $row = mysql_fetch_array($result);
    echo "<H3 ALIGN=\"CENTER\">Academic Tutor</H3>\n";
    echo "<P ALIGN=\"CENTER\">Here are some contact details for the " .
         "academic tutor allocated to this student.</P>\n";

    echo "<TABLE ALIGN=\"CENTER\">\n";
    echo "<TR><TD>Name</TD><TD>" .
         htmlspecialchars($row["title"] . " " . $row["firstname"] . " " . $row["surname"]) .
         "</TD></TR>\n";
    if(!empty($row["position"]))
      echo "<TR><TD>Position</TD><TD>" . htmlspecialchars($row["position"]) . "</TD></TR>\n";
    if(!empty($row["room"]))
      echo "<TR><TD>Room</TD><TD>" . htmlspecialchars($row["room"]) . "</TD></TR>\n";
    if(!empty($row["department"]))
      echo "<TR><TD>Department</TD><TD>" . htmlspecialchars($row["department"]) . "</TD></TR>\n";
    if(!empty($row["address"]))
      echo "<TR><TD>Address</TD><TD>" . htmlspecialchars($row["address"]) . "</TD></TR>\n";
    if(!empty($row["voice"]))
      echo "<TR><TD>Phone</TD><TD>" . htmlspecialchars($row["voice"]) . "</TD></TR>\n";
    if(!empty($row["email"]))
      echo "<TR><TD>Email</TD><TD><A HREF=\"mailto:" . htmlspecialchars($row["email"]) .
           "\">" . htmlspecialchars($row["email"]) . "</A></TD></TR>\n";
    echo "</TABLE>\n";
    mysql_free_result($result);

    // Include a photo too...  
    $query = "SELECT username FROM id WHERE id_number=" . $row["user_id"];
    $result = mysql_query($query)
      or print_mysql_error2("Unable to fetch staff username", $query);
    $row = mysql_fetch_row($result);
 
    echo "<P ALIGN=\"CENTER\">";
    echo "<A HREF=\"" . $conf['scripts']['user']['photos'] .
         "?mode=full&user_id=" . $row[0] . "\">" .
         "<IMG ALIGN=\"CENTER\" BORDER=\"0\" ALT=\"Photo\" SRC=\"" .
         $conf['scripts']['user']['photos'] .
         "?user_id=" . $row[0] . "\"></A><BR>\n";
    echo "</P>\n";
  }

assessment_regime($student_id);

$page->end();

?>