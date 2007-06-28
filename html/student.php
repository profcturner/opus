<?php

/**
**  student.php
**
** This is the index page for authenticated students.
**
** Initial coding : Colin Turner
**
*/

// The include files 
include('common.php');		
include('authenticate.php');
include('lookup.php');	
include('supervisors.php');

// Connect to the database on the server
db_connect()
  or die("Unable to connect to server");
  
// Authenticate user so that the right people see the right thing
auth_user("student");
    
$page = new HTMLOPUS('Home Page', 'myCareer', 'Home');

if(!is_admin() && !is_student()){
  die_gracefully("You do not have permission to access this page.");
}

if(!is_admin() || empty($student_id)) $student_id = get_id();

if(is_admin() && !is_auth_for_student($student_id, "student", "viewStatus"))
  die_gracefully("You do not have permission to view this page for this student.");

if(!empty($days))
{
  $unixtime = time();
  $unixtime -= ($days * 24 * 60 * 60);
  $last_index = date("YmdHis", $unixtime);
}
else
{
  if(is_student())
  {
    $last_index = $_SESSION['user']['lastindex'];
  }
  else
  {
    // Not logged in as student, get what their last index should be
    $sql = "SELECT DATE_FORMAT(last_index, '%Y%m%d%H%i%s') " .
      "AS last_index_iso FROM id WHERE id_number=$student_id";
    $result = mysql_query($sql)
      or print_mysql_error2("Unable to get student last index", $sql);
    $row = mysql_fetch_array($result);
    $last_index = $row['last_index_iso'];
    mysql_free_result($result);

    if(empty($last_index)) $last_index = 0;
    //echo "Debug: $sql, $last_index";
  }
}

$pyear = get_placement_year($student_id);

if($mode=="STUDENT_UPDATEPLACEMENT")
  student_updateplacement();

echo "<H2 ALIGN=\"CENTER\">Welcome " .
     get_user_name($student_id) . "</H2>";



output_help("StudentHome", $student_id);
output_help("StudentHome" . $pyear, $student_id);

echo "<p align=\"center\">Please note there are " .
  "<a href=\"#otherresources\">other resources</a> " .
  "to help you find placement</p>";

function get_new_vacancies()
{
  global $last_index;
  global $pyear;
  $vacancies = array();

  $sql = "SELECT * FROM vacancies WHERE " .
    "year(jobstart) = $pyear AND " .
    "created > $last_index " .
    "ORDER BY created";

  $result = mysql_query($sql)
    or print_mysql_error2("Unable to fetch vacancies", $sql);
  while($vacancy = mysql_fetch_array($result))
  {
    $vacancy['company_name'] = get_company_name($vacancy['company_id']);
    array_push($vacancies, $vacancy);
  }
  mysql_free_result($result);
  return($vacancies);
}

function get_mod_vacancies()
{
  global $last_index;
  global $pyear;
  $vacancies = array();

  $sql = "SELECT * FROM vacancies WHERE " .
    "year(jobstart) = $pyear AND " .
    "modified > $last_index " .
    "ORDER BY created";

  $result = mysql_query($sql)
    or print_mysql_error2("Unable to fetch vacancies", $sql);
  while($vacancy = mysql_fetch_array($result))
  {
    $vacancy['company_name'] = get_company_name($vacancy['company_id']);
    array_push($vacancies, $vacancy);
  }
  mysql_free_result($result);
  return($vacancies);
}


if(get_student_status($student_id) == "Required")
{
  output_help("StudentHomeRequired", $student_id);
  output_help("StudentHomeRequired" . $pyear, $student_id);
}

if(get_student_status($student_id) == "Placed")
{
  echo "<P>";
  output_help("StudentHomePlaced", $student_id);
  output_help("StudentHomePlaced" . $pyear, $student_id);
  echo "</P>";  
}

  $query = "SELECT * FROM placement WHERE student_id=$student_id";
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
  $smarty->display('student/placement_form.tpl');

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
         "academic tutor you have been allocated.</P>\n";

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


echo "<H3 ALIGN=\"CENTER\">Currently Selected Companies</H3>";

// Selected companies that have changed

$query = "SELECT DISTINCT companies.name, companies.locality, companies.company_id FROM companies" .
         ", companystudent WHERE companies.company_id = companystudent.company_id " .
         "AND student_id=" . $student_id .
         " AND companies.modified > " . $last_index;

$result = mysql_query($query)
  or print_mysql_error2("Unable to fetch current company list.", $query);

if(mysql_num_rows($result)){
  echo "<P ALIGN=\"CENTER\">The following companies are those you " .
       "have selected and that seem to have been modified ";
       if(!empty($days)) echo "in the last $days days.</P>\n";
       else echo "since you last visited this page:</P>\n";
  echo "<p>\n";
  $companies += mysql_num_rows($result);
  print_company_result($result, 1);
  echo "</p>\n";
  echo "<P ALIGN=\"CENTER\">your other selections follow.</P>\n";
}
mysql_free_result($result);

// Selected companies that have not changed
$query = "SELECT companies.name, companies.locality, companies.company_id " .
   ",companystudent.vacancy_id FROM companies" .
         ", companystudent WHERE companies.company_id = companystudent.company_id " .
         "AND student_id=" . $student_id .
         " AND (companies.modified <=> NULL OR companies.modified <= " . $last_index . ")";

$result = mysql_query($query)
  or print_mysql_error2("Unable to fetch current company list.", $query);

echo "<p>\n";
print_company_result($result, $companies + 1);
$companies += mysql_num_rows($result);
echo "</p>\n";
mysql_free_result($result);

if($companies){
  echo "<P>You have currently selected <B>" . $companies .
       "</B> companies out of your allocation of <B>" .
       $conf['prefs']['maxcompanies'] . "</B>.</P>\n";
}
else{
  echo "<P>You have not selected any companies as yet.</P>\n";
}

$new_vacancies = get_new_vacancies();
$mod_vacancies = get_mod_vacancies();
$smarty->assign("student_id", $student_id);
$smarty->assign_by_ref("days", $days);
$smarty->assign_by_ref("new_vacancies", $new_vacancies);
$smarty->assign_by_ref("mod_vacancies", $mod_vacancies);

$smarty->display("companies/new_mod_vacancies.tpl");

// New companies on the system
$query = "SELECT name, locality, company_id FROM companies" .
         " WHERE created > " . $last_index;

$result = mysql_query($query)
  or print_mysql_error2("Unable to fetch current company list.", $query);

if(mysql_num_rows($result)){
  echo "<HR>\n<H3 ALIGN=\"CENTER\">New companies</H3>\n";
  echo "<P ALIGN=\"CENTER\">These companies have been added ";
  if(!empty($days)) echo "in the last $days days.</P>\n";
  else echo "since your last visit.</P>\n";
  print_company_result($result, 1);
}
mysql_free_result($result);


// Modified companies on the system
$query = "SELECT name, locality, company_id FROM companies" .
         " WHERE modified > " . $last_index;

$result = mysql_query($query)
  or print_mysql_error2("Unable to fetch current company list.", $query);

if(mysql_num_rows($result)){
  echo "<HR>\n<H3 ALIGN=\"CENTER\">Modified companies</H3>\n";
  echo "<P ALIGN=\"CENTER\">These companies have been modified ";
  if(!empty($days)) echo "in the last $days days.</P>\n";
  else echo "since your last visit.</P>\n";
  print_company_result($result, 1);
} 
mysql_free_result($result);

if(empty($days)) $days=7;
echo "<HR>\n<P ALIGN=\"CENTER\"><FORM ACTION=\"$PHP_SELF\">\n";
echo "Show changes in the last " .
     "<INPUT TYPE=\"HIDDEN\" NAME=\"student_id\" VALUE=\"$student_id\">" .
     "<INPUT TYPE=\"TEXT\" NAME=\"days\" VALUE=\"$days\" SIZE=\"3\"> days. " .
     "<INPUT TYPE=\"SUBMIT\" NAME=\"BUTTON\" VALUE=\"Submit\">" .
     "</FORM></P>";

if(is_student()) touch_last_index();

echo "<a name=\"otherresources\">";
$smarty->display("other_student_resources.tpl");
// Print the footer and finish the page
$page->end();

function print_company_result($result, $start)
{
  global $conf;
  $first = TRUE;

  if(mysql_num_rows($result)){
    echo "<OL TYPE=\"1\" START=\"" . $start . "\">\n";
    while($row = mysql_fetch_array($result))
    {  
       echo "  <LI><A HREF=\"" . $conf['scripts']['company']['directory'] .
	 "?company_id=" . $row[2] . 
	 "&vacancy_id=" . $row["vacancy_id"];
       if(!empty($row['vacancy_id'])) echo "&mode=VacancyView";
       else echo "&mode=CompanyView";
       echo "\">";
       if(!empty($row['vacancy_id']))
       {
	 echo htmlspecialchars(get_vacancy_description($row['vacancy_id'])) . ", ";
       }
       echo     htmlspecialchars($row[0]) . ", " . htmlspecialchars($row[1]) .
            "</A></LI>\n";
    }
    echo "</OL>\n";
  }
}

function student_updateplacement()
{
  global $log;
  global $student_id;

  $placement_id = $_REQUEST['placement_id'];
  $voice = $_REQUEST['voice'];
  $email = $_REQUEST['email'];
  $supervisor_title = $_REQUEST['supervisor_title'];
  $supervisor_firstname = $_REQUEST['supervisor_firstname'];
  $supervisor_surname = $_REQUEST['supervisor_surname'];
  $supervisor_voice = $_REQUEST['supervisor_voice'];
  $supervisor_email = $_REQUEST['supervisor_email'];
  $supervisor_oldemail = $_REQUEST['supervisor_oldemail'];

  
  global $log;
  
  if(is_admin() && !is_auth_for_student($student_id, "student", "editStatus"))
    die_gracefully("You do not have permission to edit this student's status");

  // Update placement record itself;
  $query = "UPDATE placement SET " .
    "  voice=" . make_null($voice) .
    ", email=" . make_null($email) .
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

  $log["admin"]->LogPrint("placement information updated for " . get_user_name($student_id));
}     


?>