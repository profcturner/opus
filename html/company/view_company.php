<?php

/**
* CV
*
* Allows companies and vacancies to be viewed by appropriate users,
* and applications to be made.
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/

// The include files 
include('common.php');		
include('authenticate.php');
include('lookup.php');	
include('company_search.php');
include('pdp.php');

// Version 3.0+ includes
require_once 'CV.class.php';

// Connect to the database on the server
db_connect()
  or die("Unable to connect to server");
  
// Authenticate user so that the right people see the right thing
auth_user("user");

// This is used by PDSystem help
$smarty->assign("section", "pms");
if(is_student())
{
  $page['help_page']="company_list";    
  switch($mode)
  {
  case "CompanyView":
  case "VacancyView":
    $page['help_page']="vacancy_company_details";    
    break;
  case COMPANY_ADDCOMPANYSTUDENT:
    $page['help_page']="are_you_sure";    
    break;
  }

}
$page = new HTMLOPUS("Company List", "directories");	// Calls the function for the header

// Students are carefully anchored to themselves only!
if(is_student()){
  $student_id = get_id();
}

$smarty->assign("student_id", $student_id);

if(empty($year) && !empty($student_id))
  $year = get_placement_year($student_id);

$smarty->assign("year", $year);

// The default mode for the global variable
if(empty ($mode)){
  if(!empty($company_id))
    $mode = COMPANY_DISPLAYCOMPANY;
  else $mode = COMPANY_DISPLAYFORM;
}

// Getting into the right mode for the right job
switch($mode)
{

  case COMPANY_DISPLAYFORM:
    company_displayform(FALSE);
    break;

  case COMPANY_DISPLAYLIST:
   $smarty->assign("edit", FALSE);
   if($searchvacancies)
     vacancy_search();
   if($searchcompanies)
     company_displaylist();
   if(!$searchvacancies && !$searchcompanies)
     die_gracefully("You should select to search at least one of companies or vacancies");

    break;

  case "CompanyView":
    company_displaycompany();
    break;
  case "VacancyView":
    vacancy_view();
    break;

  case COMPANY_ADDCOMPANYSTUDENT:
    company_addcompanystudent();
    break;
}

// Print the footer and finish the page
$page->end();

function get_vacancy($vacancy_id)
{
  $sql = "SELECT * FROM vacancies WHERE vacancy_id=$vacancy_id";
  $result = mysql_query($sql)
    or print_mysql_error2("Unable to obtain vacancy information.", $sql);
  if(mysql_num_rows($result))
  {
    $vacancy = mysql_fetch_array($result);
    $brief = xml_parser($vacancy['brief']);
    $vacancy['brief'] = $brief['output'];
    $vacancy['company_name'] = get_company_name($vacancy['company_id']);
  }
  else
  {
    $vacancy = NULL;
  }
  mysql_free_result($result);
  return($vacancy);
}

function get_company($company_id)
{
  $sql = "SELECT * FROM companies WHERE company_id=$company_id";
  $result = mysql_query($sql)
    or print_mysql_error2("Unable to obtain company information.", $sql);
  if(mysql_num_rows($result))
  {
    $company = mysql_fetch_array($result);
    $brief = xml_parser($company['brief']);
    $company['brief'] = $brief['output'];
  }
  else
  {
    $company = NULL;
  }
  mysql_free_result($result);
  return($company);
}

function get_vacancy_activities($vacancy_id)
{
  $vacancy_activities = array();

  $sql = "SELECT vacancytype.* FROM vacancyactivity, vacancytype " .
    "WHERE vacancyactivity.activity_id = vacancytype.vacancy_id " .
    "AND vacancyactivity.vacancy_id = $vacancy_id " .
    "ORDER BY vacancytype.name";
  $result = mysql_query($sql)
    or print_mysql_error2("Unable to get activity data.", $sql);
  while($vacancy_activity = mysql_fetch_array($result))
  {
    array_push($vacancy_activities, $vacancy_activity);
  }
  mysql_free_result($result);
  return($vacancy_activities);
}


function get_company_activities($company_id)
{
  $company_activities = array();

  $sql = "SELECT DISTINCT vacancytype.* FROM companyvacancy, vacancytype " .
    "WHERE companyvacancy.vacancy_id = vacancytype.vacancy_id " .
    "AND companyvacancy.company_id = $company_id " .
    "ORDER BY vacancytype.name";
  $result = mysql_query($sql)
    or print_mysql_error2("Unable to get activity data.", $sql);
  while($company_activity = mysql_fetch_array($result))
  {
    array_push($company_activities, $company_activity);
  }
  mysql_free_result($result);
  return($company_activities);
}


function get_company_resources($company_id)
{
  $company_resources = array();

  $sql = "SELECT resources.* FROM resources, resourcelink " .
    "WHERE resources.resource_id = resourcelink.resource_id " .
    "AND resourcelink.company_id = $company_id ORDER BY description";
  $result = mysql_query($sql)
    or print_mysql_error2("Unable to get activity data.", $sql);
  while($company_resource = mysql_fetch_array($result))
  {
    array_push($company_resources, $company_resource);
  }
  mysql_free_result($result);
  return($company_resources);
}

function get_company_vacancies($company_id)
{
  global $year;

  $company_vacancies = array();

  $sql = "SELECT * FROM vacancies WHERE company_id=$company_id " .
    "AND YEAR(jobstart) = $year ORDER BY status, description";
  $result = mysql_query($sql)
    or print_mysql_error2("Unable to obtain vacancies", $sql);
  while($company_vacancy = mysql_fetch_array($result))
  {
    array_push($company_vacancies, $company_vacancy);
  }
  mysql_free_result($result);
  return($company_vacancies);
}


function vacancy_view()
{
  global $smarty;
  global $company_id;
  global $vacancy_id;

  $vacancy = get_vacancy($vacancy_id);
  $vacancy_activities = get_vacancy_activities($vacancy_id);

  $smarty->assign("vacancy_id", $vacancy_id);
  $smarty->assign_by_ref("vacancy_activities", $vacancy_activities);
  $smarty->assign_by_ref("vacancy", $vacancy);
  $smarty->display("companies/vacancy_view.tpl");

  $company_id = $vacancy["company_id"];
  company_displaycompany();
}


/**
**	@function company_displaycompany();
**	Display the details for a company (visible to general users)
**	@param $company_id (CGI) The company to display
**	@param $student_id (CGI) The student we are acting on behalf of (if any)
**	@param $year (CGI) The year we are seeking placement
*/
function company_displaycompany()
{
  global $PHP_SELF;
  global $conf;
  global $company_id;
  global $student_id;  // Needed in case an admin is working
  global $vacancy_id;
  global $log;
  global $year;

  if(empty($year)) $year = get_academic_year()+1;


  global $smarty;

  $company = get_company($company_id);
  $company_activities = get_company_activities($company_id);
  $company_resources = get_company_resources($company_id);
  $company_vacancies = get_company_vacancies($company_id);

  $smarty->assign("year", $year);
  $smarty->assign("company_id", $company_id);
  $smarty->assign("student_id", $student_id);
  $smarty->assign_by_ref("company_vacancies", $company_vacancies);
  $smarty->assign_by_ref("company_activities", $company_activities);
  $smarty->assign_by_ref("company_resources", $company_resources);
  $smarty->assign_by_ref("company", $company);
  $smarty->display("companies/company_view.tpl");

  if(is_student() || (is_admin() && !empty($student_id)))
  {
    echo "<HR>\n";
    echo "<H1>Application Details</H1>\n";
    // Let's query how many companies the student has selected.
    if(is_admin()){
      echo "<P><B>You are acting on behalf of the student " .
           htmlspecialchars(get_user_name($student_id)) . ".</B></P>";
    }
    $query = "SELECT * FROM companystudent WHERE student_id=" . $student_id;
    $result = mysql_query($query)
      or print_mysql_error2("Unable to fetch company list.", $query);

    $currentcompanies = mysql_num_rows($result);
    $maxcompanies = $conf['prefs']['maxcompanies'];

    $already = FALSE;
    while($row = mysql_fetch_array($result)){
      if($row['company_id'] == $company_id){
	if(empty($vacancy_id) || ($row['vacancy_id'] == $vacancy_id))
	{
	  echo "<P><B>This ";
	  if(empty($vacancy_id)) echo "company";
	  else echo "vacancy";
	  echo " is one of your selections.</B></P>\n";
	  $already = TRUE;
	}
      }
    }

    // Don't allow applications to companies anymore!
    if($vacancy_id)
    {
      echo "<P>You have currently selected <B>" . $currentcompanies .
	"</B> companies / vacancies up to now. ";

      if($currentcompanies < $maxcompanies){
	echo "You still have up to <B>" . 
	  ($maxcompanies - $currentcompanies) .
	  "</B> vacanc";
	echo (($maxcompanies - $currentcompanies) == 1) ? "y" : "ies";
	echo " that you may pick.</P>";

	if($already == FALSE){
	  echo "<P>Click <A HREF=\"" . $PHP_SELF . 
	    "?mode=COMPANY_ADDCOMPANYSTUDENT&company_id=" . $company_id .
	    "&vacancy_id=" . $vacancy_id .
	    "&student_id=" . $student_id . "\">here</A> " .
	    "to add this vacancy to your list.";
	}
      }
      echo "</P>\n";
    }
    else
    {
      echo "<p>You cannot apply to the company itself, please apply to any of its listed vacancies, if any are shown for your year.</p>";
    }

    mysql_free_result($result);
  }

  $log['access']->LogPrint("company " . get_company_name($company_id) .
                           " examined.");


}


/**
* Attempts to add a company to a student's list
*
* @param $company_id (CGI) The company to add
* @param $student_id (CGI) The student to add it to
* @param $confirm (CGI) Whether the action is confirmed
* @todo a horrifying function that needs broken down to smaller parts (rewritten from scratch)
* @todo needs to be template driven
* @todo needs old CV code to be removed when safe to do so
*/
function company_addcompanystudent()
{
  global $PHP_SELF;    // Reference to script
  global $company_id;  // Company to add
  global $vacancy_id;  // Vacancy to add
  global $student_id;  // Student to add it to
  global $prefcvt;     // The preferred CV template
  global $cover;       // Any covering letter
  global $conf;        // Main configuration
  global $confirm;     // Is this action confirmed?
  global $log;         // Logging
  global $user;        // Currently authenticated user
  global $year;

  // Better be an admin or a student
  if(!is_admin() && !is_student())
    die_gracefully("You do not have permission to do this");

  // Check Admin permissions
  if(is_admin() && !is_auth_for_student($student_id, "student", "editCompanies"))
    die_gracefully("You are not permitted to edit this student's company list.");

  if(!empty($vacancy_id)) $vacancy = get_vacancy($vacancy_id);
 
  // Check the current listing for the student
  $query = "SELECT * FROM companystudent WHERE student_id=" . $student_id;
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch company list.", $query);

  if(is_admin())
  {
    echo "<P><B>You are acting on behalf of " . 
         htmlspecialchars(get_user_name($student_id)) . "</B></P>\n";
  }

  // Are there vacancies left? root users may override
  if(!is_root() && (mysql_num_rows($result) >= $conf['prefs']['maxcompanies'])){
    die_gracefully("You have no more available room for company selections.\n");
  }

  // Is the company already in the list?
  while($row = mysql_fetch_array($result)){
    if($row["company_id"] == $company_id)
    {
      // Backwards compatibility. Make an add condition for 4.0.0
      if(empty($vacancy_id) || ($row["vacancy_id"] == $vacancy_id))
      {
	die_gracefully("You have already selected this company / vacancy.\n");
      }
    }
  }

  // Check the student status  
  $query = "SELECT * FROM students WHERE user_id=" . $student_id;
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch student data.", $query);

  $row = mysql_fetch_array($result);
  if($row['status']!='Required'){
    die_gracefully("You are not flagged as requiring placement.");
  }

  if(!empty($vacancy_id) && ($vacancy['status'] != "open") && !is_admin())
  {
    die_gracefully("The status of the vacancy is not open. You may " .
                   "not apply on the system.");
  }

  // Populate smarty with arrays, and get template CVs
  $year = get_placement_year($student_id);
  $default_template_id = get_default_cvtemplate($student_id);
  $cvs = CV::populate_smarty_arrays($student_id);
  $smarty->assign("default_template_id", $default_template_id);
  $smarty->assign("year", $year);
  $smarty->assign("company_name", get_company_name($company_id));
  $smarty->assign("vacancy_description", $vacancy['description']);

  // Still with us? Check for confirmation
  if($confirm!=1){
    echo "<H2 ALIGN=\"CENTER\">Are you sure?</H2>\n";

    echo "<P ALIGN=\"CENTER\">You have elected to add " .
         "<B>";
    if(!empty($vacancy_id)) echo htmlspecialchars($vacancy['description'] . ", ");
    echo get_company_name($company_id) . "</B>" .
         " to your selection. Once added, the entry may " .
         "not be removed.</P>\n";

    output_help("StudentAddCompany");
    // Check smarty vars
    $smarty->display("companies/add_company_student_confirm.tpl");
  }
  else // confirm == 1
  {
    // Ok, we are going for a real insert here, admin's are allowed template 0
    if(!is_admin() && (empty($prefcvt) || ($prefcvt == 0 && is_numeric($prefcvt))))
      die_gracefully("You must select a valid preferred CV layout");

    // Backwards compatibility with OPUS pre version 2, this needs taken out soon.
    if(!empty($vacancy_id)) $vac_insert=$vacancy_id;
    else $vac_insert = "NULL";

    // Two types of insert, one is hash or archived CV, the other is traditional template id
    if(substr($prefcvt, 0, 4) == 'hash')
    {
      $archive_cv_details = explode("_", $prefcvt);
      $archive_hash = $archive_cv_details[1];
      $archive_mime_type = $archive_cv_details[2];

      $query = "INSERT INTO companystudent " .
             "(company_id, vacancy_id, student_id, created, modified, prefcvt, cover, addedby, archive_hash, archive_mime_type) " .
             " VALUES(" .
             "$company_id, $vac_insert, $student_id, '" . date("YmdHis") . "', NULL, 0, " .
             make_null($cover) . ", " . get_id() . ", " . make_null($archive_hash) . ", " . make_null($archive_mime_type) . ")";

    }
    else
    {
      $query = "INSERT INTO companystudent " .
             "(company_id, vacancy_id, student_id, created, modified, prefcvt, cover, addedby) " .
             " VALUES(" .
             "$company_id, $vac_insert, $student_id, '" . date("YmdHis") . "', NULL, $prefcvt, " .
             make_null($cover) . ", " . get_id() . ")";
    }

    mysql_query($query) or
      print_mysql_error2("Unable to add company to list", $query);
    
    echo "<P ALIGN=\"CENTER\">You have successfully added " .
         get_company_name($company_id) . " to your list of companies</P>\n";
    echo "<HR>\n";
    $log['access']->LogPrint("company " . get_company_name($company_id) .
                             " added to selection for " . 
                             get_user_name(get_id()));

    if(!empty($vacancy_id)) vacancy_view();
    else company_displayform();
  }
}

?>