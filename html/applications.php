<?php

/**
**  companies.php
**
** This script allows students more control over their company
** subscriptions.
**
** Initial coding : Colin Turner
**
*/

// The include files 
include('common.php');		
include('authenticate.php');
include('lookup.php');	
include('cv.php');

include('pdp.php');
include('CVGroups.class.php');

// Connect to the database on the server
db_connect()
  or die("Unable to connect to server");
  
// Authenticate user so that the right people see the right thing
auth_user("student");

$smarty->assign("section", "pms");
$legacy_page['help_page']="applications";
    
$page = new HTMLOPUS('Applications', 'mycareer', 'applications');  
if(!is_admin() && !is_student()){
  die_gracefully("You do not have permission to access this page.");
}

if(!is_admin() || empty($student_id)) $student_id = get_id();


$pyear = get_placement_year($student_id);


echo "<H2 ALIGN=\"CENTER\">" .
      get_user_name($student_id) . "</H2>";


if(empty($mode)) $mode = SHOW_COMPANIES;

switch($mode)
{
  case SHOW_COMPANIES:
    show_companies();
    break;

  case ZOOM_COMPANY:
    zoom_company();
    break;

  case UPDATE_COMPANY:
    update_company();
    break;

  default:
    die_gracefully("Invalid mode...");
    break;
}


function zoom_company()
{
  global $student_id;
  global $company_id;
  global $vacancy_id;
  global $pyear;
  global $conf;
  global $PHP_SELF;

  if(is_admin() && !is_auth_for_student($student_id, "student", "viewCompanies"))
    die_gracefully("You do not have permission to view this page for this student."); 

  $query = "SELECT * FROM companies WHERE company_id=$company_id";
  $company = mysql_query($query)
    or print_mysql_error2("Unable to fetch company data", $query);
  $company_info = mysql_fetch_array($company);
  mysql_free_result($company);

  $query = "SELECT * FROM companystudent WHERE company_id=$company_id " .
           "AND student_id=$student_id";
  if(!empty($vacancy_id))
  {
    $query .= " AND vacancy_id=$vacancy_id";
  }
  else
  {
    $query .= " AND vacancy_id=NULL";
  }

  $student = mysql_query($query)
    or print_mysql_error2("Unable to fetch student data", $query);
  $student_info = mysql_fetch_array($student);
  mysql_free_result($student);

  echo "<H3 ALIGN=\"CENTER\">" . htmlspecialchars($company_info['name'] . ", " . $company_info['locality']) .
       "</H3>\n";
  if(!empty($vacancy_id))
  {
    echo "<H3 ALIGN=\"CENTER\">" .
      htmlspecialchars(get_vacancy_description($vacancy_id)) . "</H3>\n";
  }

  $completed_cvs = PDSystem::get_valid_templates($student_id);
  $archived_cvs = PDSystem::get_archived_cvs($student_id);
  $archived_cvs = $archived_cvs->xpath('//cv');
  $group_id = get_student_cvgroup($student_id);
  $template_info = CVGroups::get_templates_for_group($group_id);
  echo "You have " . count($completed_cvs) . " completed CVs and " .
    count($archived_cvs) . " archived CVs from the PDSystem to use. You may not have access to all of these depending upon your course team's policies.";
  $default_template_id = get_default_cvtemplate($student_id);

  output_help("StudentCompaniesEditApp");
  echo "<FORM ACTION=\"$PHP_SELF\" METHOD=\"POST\">\n" .
       "<INPUT TYPE=\"HIDDEN\" NAME=\"mode\" VALUE=\"UPDATE_COMPANY\">\n" .
       "<INPUT TYPE=\"HIDDEN\" NAME=\"student_id\" VALUE=\"$student_id\">\n" .
       "<INPUT TYPE=\"HIDDEN\" NAME=\"year\" VALUE=\"$year\">\n" .
    "<INPUT TYPE=\"HIDDEN\" NAME=\"vacancy_id\" VALUE=\"$vacancy_id\">\n";
  echo     "<INPUT TYPE=\"HIDDEN\" NAME=\"company_id\" VALUE=\"$company_id\">\n";

  echo "<TABLE ALIGN=\"CENTER\">\n";
  echo "<TR><TD>Preferred CV</TD><TD><SELECT NAME=\"prefcvt\">";

    foreach($completed_cvs as $pds_cv)
    {
      $template_id = (int) $pds_cv->id;
      if(!$template_info[$template_id]['allow']) continue; // Not allowed
      echo "<option value=\"" . $pds_cv->id . "\"";
      if($pds_cv->id == $student_info['prefcvt']) echo " SELECTED"; 
      echo ">" . $pds_cv->name . " (From PDSystem)</option>\n";
    }
    
  echo "</SELECT>\n</TD></TR>\n";
  echo "<TR><TD COLSPAN=\"2\" ALIGN=\"CENTER\">Cover Letter</TD></TR>\n";
  echo "<TR><TD COLSPAN=\"2\" ALIGN=\"CENTER\"><TEXTAREA " .
        "ROWS=\"20\" COLS=\"60\" WRAP=\"VIRTUAL\" NAME=\"cover\">";
  echo $student_info['cover'];
  echo "</TEXTAREA></TD></TR>\n";

  echo "<TR><TD COLSPAN=\"2\" ALIGN=\"CENTER\"><INPUT TYPE=\"SUBMIT\" VALUE=\"Update Application\">" .
       "</TD></TR>\n";
  echo "</TABLE></FORM>\n";
}

function update_company()
{
  global $student_id;
  global $company_id;
  global $vacancy_id;
  global $prefcvt;
  global $cover;


  if(is_admin() && !is_auth_for_student($student_id, "student", "editCompanies"))
    die_gracefully("You do not have permission to edit this student's choices."); 


  $query = "UPDATE companystudent SET cover=" . make_null($cover) .
           ", prefcvt=" . $prefcvt . ", modified=" . date('YmdHis') .
           " WHERE company_id=$company_id ";
  if(empty($vacancy_id))
  {
    $query .= "AND vacancy_id=NULL";
  }
  else
  {
    $query .= "AND vacancy_id=$vacancy_id";
  }

  $query .= " AND student_id=$student_id";

  mysql_query($query)
    or print_mysql_error2("Unable to update application details");

  show_companies();
}

function show_companies()
{
  global $PHP_SELF;
  global $student_id;
  global $pyear;
  global $conf;

  if(is_admin() && !is_auth_for_student($student_id, "student", "viewCompanies"))
    die_gracefully("You do not have permission to view this page for this student."); 

  echo "<H3 ALIGN=\"CENTER\">Currently Selected Companies</H3>";

  output_help("StudentApplicationsView");

  $query = "SELECT companies.name, companies.locality, companies.company_id, companystudent.* FROM companies" .
           ", companystudent WHERE companies.company_id = companystudent.company_id " .
           "AND student_id=" . $student_id .
           " ORDER BY companystudent.created";

  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch current company list.", $query);

  if(!mysql_num_rows($result))
  {
    echo "<P ALIGN=\"CENTER\">You have not selected any companies yet</P>\n";
    return;
  }
  
  // Keep track of the number of the current company
  $company = 1;

  echo "<TABLE ALIGN=\"CENTER\" BORDER=\"1\">\n";
  while($row = mysql_fetch_array($result))
  {
    echo "<TR><TH>$company</TH><TH>";
    // Create hyperlink on name
    if(is_student())
    {
      echo "<A HREF=\"" . $conf['scripts']['company']['directory'] .
           "?mode=VacancyView&company_id=" . $row["company_id"] . 
           "&vacancy_id=" . $row["vacancy_id"] . "&year=$pyear&student_id=$student_id" .
           "\">";
    }
    else
    {
      echo "<A HREF=\"" . $conf['scripts']['company']['edit'] .
           "?company_id=" . $row["company_id"] . 
	   "&vacancy_id=" . $row["vacancy_id"] . "&year=$pyear" .
           "\">";
    }
                
    if($row["vacancy_id"])
    {
      echo htmlspecialchars(get_vacancy_description($row["vacancy_id"])) . "<br>";
    }
    echo htmlspecialchars($row["name"]) . "</A></TH></TR>\n";
    echo "<TR><TD COLSPAN=\"2\">\n";
    echo "<TABLE>\n";
    echo "<TR><TD>Company Location</TD><TD>" . htmlspecialchars($row["locality"]) . "</TD></TR>\n";
    echo "<TR><TD>Application date</TD><TD>" . $row["created"] . "</TD></TR>\n";
    echo "<TR><TD>Preferred CV template</TD><TD>";
    echo "<A HREF=\"" . cv_link($student_id, $row["prefcvt"]) . "\">" .
 
         htmlspecialchars(get_cv_name($row["prefcvt"])) . "</A></TD></TR>\n";
    echo "<TR><TD>Status</TD><TD>" . htmlspecialchars($row["status"]) . "</TD></TR>\n";
    echo "<TR><TD>Last viewed</TD><TD>" . $row["lastseen"] . "</TD></TR>\n";
    echo "<TR><TD>Cover Letter?</TD><TD>";
    if(empty($row["cover"])) echo "No cover letter\n";
    else echo "Covering letter supplied\n";
    echo "</TD></TR>\n";
    echo "<TD COLSPAN=\"2\" ALIGN=\"CENTER\">";
    echo "<A HREF=\"$PHP_SELF?mode=ZOOM_COMPANY&student_id=$student_id&company_id=" . 
         $row["company_id"] . "&vacancy_id=" . $row["vacancy_id"] .
      "\">Edit details</A></TD></TR>\n";
    echo "</TABLE>\n";
    echo "</TD></TR>\n";
    $company++;
  }
  echo "</TABLE>\n";
}

// Print the footer and finish the page
$page->end();

?>
