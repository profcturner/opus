<?php

/**
* Functionality to edit companies and associated data
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
include('wizard.php');
include('notes.php');
include('resources.php');
include('cv.php');
include('company_search.php');
include('pdp.php');
include('mail.php');

// Connect to the database on the server
db_connect()
  or die("Unable to connect to server");
  
// Authenticate user so that the right people see the right thing
auth_user("company");

// We need to enable HTMLArea for certain modes

switch($_REQUEST['mode'])
{
 case "":
 case "COMPANY_BASICEDIT":
 case "COMPANY_DISPLAYFORM":
 case "COMPANY_BASICUPDATE":
 case "COMPANY_STARTADD":
 case "VacancyEdit":
 case "VacancyAdd":
 case "VacancyClone":
  $page['editor'] = TRUE;
  break;
}

$page = new HTMLOPUS('Company Editor', 'directories', 'companies');

// If we know the vacancy_id but not the company_id, deduce the latter...
if(empty($company_id) && !empty($vacancy_id))
{
  $sql = "SELECT * FROM vacancies WHERE vacancy_id=$vacancy_id";
  $result = mysql_query($sql)
    or print_mysql_error2("Unable to fetch vacancy information", $sql);
  $vacancy = mysql_fetch_array($result);
  mysql_free_result($result);
  $company_id = $vacancy["company_id"];
}


// Further checks for a company user
if(is_company()){
  if(empty($company_id))
    die_gracefully("You cannot access this page without a company_id.");

  // Ok, check the company id matches for this company
  $contact_id = get_contact_id(get_id());

  $query = "SELECT * FROM companycontact WHERE " .
           "contact_id=" . $contact_id . " AND " .
           "company_id=" . $company_id;

  $result = mysql_query($query)
    or print_mysql_error2("Unable to authenticate company.", $query);

  if(!mysql_num_rows($result))
    die_gracefully("You do not have permission to edit this company.");
  mysql_free_result($result);
}




// The default mode for the global variable
if(empty ($mode)){
  if(!empty($company_id))
    $mode = COMPANY_BASICEDIT;
  else $mode = COMPANY_DISPLAYFORM;
}


// Getting into the right mode for the right job
switch($mode)
{

 case COMPANY_DISPLAYFORM:
   company_displayform(TRUE);
   break;

 case COMPANY_DISPLAYLIST:
   $smarty->assign("edit", TRUE);
   if($searchvacancies)
     vacancy_search();
   if($searchcompanies)
     company_displaylist();
   if(!$searchvacancies && !$searchcompanies)
     die_gracefully("You should select to search at least one of companies or vacancies");
   break;

 case COMPANY_DISPLAYCOMPANY:
   company_displaycompany();
   break;
 
 case COMPANY_DISPLAYCONTACTS:
   company_displaycontacts();
   break;

 case COMPANY_BASICEDIT:
   company_basicedit();
   break;

 case COMPANY_BASICUPDATE:
   company_basicupdate();
   break;

 case "CompanyVacancyList":
   company_vacancy_list();
   break;

 case COMPANY_DISPLAYSTUDENTS:
   company_displaystudents();
   break;

 case COMPANY_STARTADD:
   company_startadd();
   break;

 case COMPANY_ADD:
   company_add();
   break;

 case DISPLAY_NOTES:
   company_display_notes();
   break;
 case Display_Single_Note:
   company_display_note();
   break;
 case Insert_Note:
   company_insert_note();
   break;
 case Notes_Search:
   company_notes_search();
   break;
 case NoteForm:
   company_note_form();
   break;
 case COMPANYSTUDENT_STATUS_UPDATE:
   companystudent_status_update();
   break;

   // New style
 case "VacancyAdd":
   vacancy_add();
   break;
 case "VacancyInsert":
   vacancy_insert();
   break;
 case "VacancyEdit":
   vacancy_edit();
   break;
 case "VacancyClone":
   vacancy_clone();
   break;
 case "VacancyUpdate":
   vacancy_update();
   break;
 case "VacancyDelete":
   vacancy_delete();
   break;
 case "EmailCV":
   email_cv();
   break;


 default:
   echo "<P>Invalid Mode</P>\n";
   break;


}


// Print out the help column on rigth hand side
//right_column("pdetails");

// Print the footer and finish the page
$page->end();			


/**
**	@function company_display_notes()
**	Show all authenticated notes for a company
**	@param $company_id (CGI) The company to display notes for
*/
function company_display_notes()
{
  global $company_id;

  if(empty($company_id)){
    die_gracefully("This page should not be accessed without a company id.");
  }

  echo "<H2 ALIGN=\"CENTER\">" .
       htmlspecialchars(get_company_name($company_id)) . "</H2>\n";

  echo "<H3 ALIGN=\"CENTER\">Notes</H3>\n";

  print_wizard("Notes");

  notes_display_list("Company", $company_id, "company_id=$company_id");
}


/**
**	@function company_notes_search()
**	Begins the dialog to search notes on a company
**	@param $company_id (CGI) The selected company
*/
function company_notes_search()
{
  global $company_id;

  if(empty($company_id)){
    die_gracefully("This page should not be accessed without a company id.");
  }

  echo "<H2 ALIGN=\"CENTER\">" .
       htmlspecialchars(get_company_name($company_id)) . "</H2>\n";

  echo "<H3 ALIGN=\"CENTER\">Notes</H3>\n";

  print_wizard("Notes");

  notes_search_list("Company", $company_id, "company_id=$company_id");

}


/**
**	@function company_insert_note()
**	Adds a new note on a company
*/
function company_insert_note()
{
  notes_insert();
  company_display_notes();
}


/**
**	@function company_note_form()
**	Shows the form to begin creating a new note on a company
**	@param $company_id (CGI) The selected company
*/
function company_note_form()
{
  global $company_id;
  global $PHP_SELF;

  if(empty($company_id)){
    die_gracefully("This page should not be accessed without a company id.");
  }

  echo "<H2 ALIGN=\"CENTER\">" .
       htmlspecialchars(get_company_name($company_id)) . "</H2>\n";

  echo "<H3 ALIGN=\"CENTER\">Notes</H3>\n";

  print_wizard("Notes");
  notes_form("Company", $company_id, "company_id=$company_id");
}


/**
**	@function company_display_note()
**	Displays a specific note for a company, if possible.
**	@param $company_id The selected company
*/
function company_display_note()
{
  global $company_id;

  if(empty($company_id)){
    die_gracefully("This page should not be accessed without a company id.");
  }

  echo "<H2 ALIGN=\"CENTER\">" .
       htmlspecialchars(get_company_name($company_id)) . "</H2>\n";

  echo "<H3 ALIGN=\"CENTER\">Notes</H3>\n";

  notes_display();
}






function company_displaycontacts()
{
  global $PHP_SELF;
  global $conf;
  global $company_id;
  global $log;

  printf("<H2 ALIGN=\"CENTER\">%s</H2>\n", 
          htmlspecialchars(get_company_name($company_id)));

  echo "<H3 ALIGN=\"CENTER\">Contact Details</H3>\n";

  print_wizard("Contacts");


  $query = "SELECT contacts.*, companycontact.status FROM contacts, companycontact " .
           "WHERE contacts.contact_id=companycontact.contact_id " .
           "AND companycontact.company_id=" . $company_id .
           " ORDER BY companycontact.status DESC, contacts.surname";

  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch contact information.", $query);

  if(!mysql_num_rows($result)){
    echo "<P ALIGN=\"CENTER\">There are currently no contacts for this company.</P>";
  }
  else{

    echo "<TABLE BORDER=\"1\" ALIGN=\"CENTER\">\n" .
         "<TR><th>Name</th><th>Position</th><th>Email</th>";
    if(is_admin())
      echo "<th>Status</th><th></th>";
    echo "</TR>\n";
    

    while($row=mysql_fetch_array($result)){
    
      printf("<TR><TD>%s %s %s</TD><TD>%s</TD><TD>" .
             "<A HREF=\"mailto:%s\">%s</A></TD>",
             htmlspecialchars($row["title"]),
             htmlspecialchars($row["firstname"]),
             htmlspecialchars($row["surname"]),
             htmlspecialchars($row["position"]),
             htmlspecialchars($row["email"]),
             htmlspecialchars($row["email"]));

      if(is_admin()){
        printf("<TD>%s</TD>", $row["status"]);
        printf("<TD><A HREF=\"%s?contact_id=%s&company_id=%s\">",
                $conf['scripts']['company']['contacts'], $row["contact_id"], $company_id);
        printf("Edit</A></TD>\n");
      }
      echo "</TR>\n";
    }
    printf("</TABLE>");
  }
  
  if(is_admin()){
    echo "<P ALIGN=\"CENTER\"><A HREF=\"" .
         $conf['scripts']['company']['contacts'] .
         "?mode=CONTACT_STARTADD&company_id=$company_id\">" .
         "Add a new contact</A></P>\n";
  }
  $log['access']->LogPrint("Contact details for " . get_company_name($company_id) . " viewed.");
}


/**
**	last_year_for_company
**
** This function establishes the last year for which
** a company showed activities for placement. It returns
** zero if no such year exists.
**
*/
function last_year_for_company($company_id)
{
  $query = "SELECT * FROM companyvacancy WHERE company_id=$company_id " .
           "ORDER BY year DESC";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to check last active year.", $query);

  if(!mysql_num_rows($result)) $year = 0;
  else{
    // top row will by definition contain the last active year
    $row = mysql_fetch_array($result);
    $year = $row["year"];
  }
  mysql_free_result($result);

  return($year);
}


/**
**	activities_in_year
**
** Returns the number of activities (vacancies) listed
** against a company for that year.
**
*/
function activities_in_year($company_id, $year)
{
  $query = "SELECT * FROM companyvacancy WHERE company_id=$company_id " .
           "AND year = $year";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to obtain number of activities.", $query);
  $rows = mysql_num_rows($result);
  mysql_free_result($result);

  return($rows);
}


/**
**	@function company_startadd()
**	Displays the form for creating a new company.
*/
function company_startadd()
{
  global $log;

  if(!is_admin() || !check_default_policy('company', 'create'))
    die_gracefully("You do not have permission to do this.");

  echo "<H2 ALIGN=\"CENTER\">New Company</H2>\n";
  echo "<H3 ALIGN=\"CENTER\">Basic Details</H3>\n";

  company_basicform("");

  $log['admin']->logprint("starting to create a new company.");
}



function company_basicedit()
{
  global $conf;
  global $company_id;
  global $log;

  $query = "SELECT * FROM companies WHERE company_id=" . $company_id;
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch company data.", $query);

  $row = mysql_fetch_array($result);

  echo "<H2 ALIGN=\"CENTER\">" . 
       htmlspecialchars(get_company_name($company_id)) .
       "</H2>\n";
  echo "<H3 ALIGN=\"CENTER\">Basic Details</H3>\n";
  print_wizard("Basics");
  company_basicform($row);

  // Experimental
  $lastitem = new Lastitem('company', $company_id, 'c:' . get_company_name($company_id), 
    $conf['scripts']['company']['edit']   . "?mode=COMPANY_BASICEDIT&company_id=$company_id");
  $_SESSION['lastitems']->add($lastitem);


  $log['access']->logprint("Basic details for " . get_company_name($company_id) . "viewed.");
}


function company_basicform($company)
{
  global $conf;
  global $company_id;
  global $smarty;

  $form = array();
  $form['name'] = "companyform";
  $form['method'] = "post";
  $form['action'] = $_SERVER['PHP_SELF'];

  $form['hidden'] = array();
  $form['hidden']['company_id']=$company_id;


  // Fetch the complete list of activities, and prepare to get the current
  // list for the students...
  $activities = activities_fetch();
  $company_activities = array();

  if($company_id)
  {
    $company_activities = company_activities_fetch($company_id);
    $company_allocation = (int) (used_allocation($company_id) / 1024);
    $company['allocation_used'] = $company_allocation;
    $form['hidden']['mode'] = 'COMPANY_BASICUPDATE';
    $form['hidden']['company_id'] = $company_id;
  }
  else
  {
    $form['hidden']['mode'] = 'COMPANY_ADD';
  }

  $brief = xml_parser($company['brief']);


  $smarty->assign("brief", $brief);
  $smarty->assign("form", $form);
  $smarty->assign("activities", $activities);
  $smarty->assign("company_activities", $company_activities);
  $smarty->assign("company", $company);
  $smarty->display("companies/company_basics_form.tpl");

  if(!empty($company_id))
  {
    company_resource_list();
  }
}


function clean_bad_xhtml($input)
{
/*
  $badchr = array(
    "\\xe2\\x80\\xa6",        // ellipsis
    "\\xe2\\x80\\x93",        // long dash
    "\\xe2\\x80\\x94",        // long dash
    "\\xe2\\x80\\x98",        // single quote opening
    "\\xe2\\x80\\x99",        // single quote closing
    "\\xe2\\x80\\x9c",        // double quote opening
    "\\xe2\\x80\\x9d",        // double quote closing
    "\\xe2\\x80\\xa2"        // dot used for bullet points
    );

  $goodchr = array(
    '...',
    '-',
    '-',
   '\\'',
   ''',
    '"',
    '"',
   '*'
    );
*/
 
  $badwordchars=array(
    "\xe2\x80\x98", // left single quote
    "\xe2\x80\x99", // right single quote
    "\xe2\x80\x9c", // left double quote
    "\xe2\x80\x9d", // right double quote
    "\xe2\x08\x94", // long dash
    "\xe2\x80\x94", // em dash
    "\xe2\x80\xa6", // elipses
    "\xe2\x80\xa2" // bullet point
    );
  $fixedwordchars=array(
    "&#8216;",
    "&#8217;",
    '&#8220;',
    '&#8221;',
    '&mdash;',
    '&mdash;',
    '&#8230;',
    '*'
    );
  return(str_replace($badwordchars,$fixedwordchars,$input));

}

function company_add()
{
  global $name, $address1, $address2, $address3;
  global $town, $locality, $country, $postcode;
  global $www, $voice, $fax, $brief, $allocation;
  global $company_id;
  global $log;

  $activities = $_REQUEST['activities'];

  if(!is_numeric($allocation)) $allocation = 'NULL';

  if(!is_admin())
    die_gracefully("You do not have permission to access this page.");

  if(!check_default_policy("company", "create"))
    die_gracefully("You are not permitted to create companies.");

  // Form the query
  $query = "INSERT INTO companies VALUES(" .
           make_null($name) . ", " .
           make_null($address1) . ", " .
           make_null($address2) . ", " .
           make_null($address3) . ", " .
           make_null($town) . ", " .
           make_null($locality) . ", " .
           make_null($country) . ", " .
           make_null($postcode) . ", " .
           make_null($www) . ", " .
           make_null($voice) . ", " .
           make_null($fax) . ", " .
           make_null($brief) . ", " .
           make_null(date("YmdHis")) . ", NULL, $allocation, 0)";

  // Now try it
  mysql_query($query)
    or print_mysql_error2("Unable to create company record.", $query);

  $company_id = mysql_insert_id();
  foreach($activities as $activity)
  {
    $sql = "insert into companyvacancy (company_id, vacancy_id) " .
      "values($company_id, $activity)";
    mysql_query($sql)
      or print_mysql_error2("Unable to add company activity.", $sql);
  }


  echo "<H2 ALIGN=\"CENTER\">Company record updated</H2>\n";
  $log['admin']->LogPrint("new company $name added to database.");

  if(!is_numeric($allocation)) $allocation="default";
  company_basicedit();
}


function company_basicupdate()
{
  global $name, $address1, $address2, $address3;
  global $town, $locality, $country, $postcode;
  global $www, $voice, $fax, $brief, $allocation;
  global $company_id;

  $activities = $_REQUEST['activities'];

  if(!is_numeric($allocation)) $allocation='NULL';

  if(is_admin() && !check_default_policy("company", "edit"))
    die_gracefully("You are not permitted to edit company records.");

  $query = "UPDATE companies SET" .
    "  name="     . make_null($name) .
    ", address1=" . make_null($address1) .
    ", address2=" . make_null($address2) .
    ", address3=" . make_null($address3) .
    ", town="     . make_null($town) .
    ", locality=" . make_null($locality) .
    ", country="  . make_null($country) .
    ", postcode=" . make_null($postcode) .
    ", www="      . make_null($www) .
    ", voice="    . make_null($voice) .
    ", fax="      . make_null($fax) .
    ", allocation=$allocation" .
    ", brief="    . make_null($brief);

  $query .=  ", modified=" . date("YmdHis") .
             " WHERE company_id=" . $company_id;

  // Now try it
  mysql_query($query)
    or print_mysql_error2("Unable to update company record.", $query);

  // Update activities, delete them first
  $sql = "delete from companyvacancy where company_id = $company_id";
  mysql_query($sql)
    or print_mysql_error2("Unable to remove company activities.", $sql);
  foreach($activities as $activity)
  {
    $sql = "insert into companyvacancy (company_id, vacancy_id) " .
      "values($company_id, $activity)";
    mysql_query($sql)
      or print_mysql_error2("Unable to add company activity.", $sql);
  }

  echo "<H2 ALIGN=\"CENTER\">Company record updated</H2>\n";

  if(!is_numeric($allocation)) $allocation='default';
  company_basicedit();
}


function company_vacancy_list()
{
  global $company_id;
  global $vacancy_id;
  global $year;

  

  $query = "SELECT * FROM companies WHERE company_id=" . $company_id;
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch company data.", $query);

  $row = mysql_fetch_array($result);

  echo "<H2 ALIGN=\"CENTER\">" . 
       htmlspecialchars(get_company_name($company_id)) .
       "</H2>\n";
  echo "<H3 ALIGN=\"CENTER\">Vacancies</H3>\n";

  print_wizard("Vacancies");
  vacancy_list();
}


/**
*	Updates the status field in companystudent with CGI data.
*
*/
function companystudent_status_update()
{
  global $company_id;
  global $vacancy_id;
  global $student_id;
  global $status;
  global $log;
  global $smarty;
  global $year;
  
  $confirmed = $_REQUEST['confirmed'];
  $message = $_REQUEST['message'];

  // Check this user is permitted to do this:
  if(is_admin() && !is_auth_for_student($student_id, "student", "editStatus"))
    die_gracefully("You do not have permission to alter the status of this student");

  // Company authentication is taken care of at the top of the script

  // Status should never be set back to unseen - simply ignore such requests
  if($status=='unseen')
  {
    company_displaystudents();
  }
  
  if($confirmed)
  {
    
    if(empty($vacancy_id)) $vacancy='NULL';
    else $vacancy = $vacancy_id;

    $query = "UPDATE companystudent SET status=" . make_null($status) .
      " WHERE company_id=$company_id AND student_id=$student_id ";

    if(!empty($vacancy_id)) $query .= "AND vacancy_id <=> $vacancy_id";
    mysql_query($query)
      or print_mysql_error2("Unable to update company student status", $query);
  $log['access']->LogPrint("Status for student " . get_user_name($student_id) . " changed to " .
                           "$status for company " . get_company_name($company_id) . "for vacancy " .
                           "$vacancy");
  if(!empty($message))
  {
    $student_details = get_user_details($student_id);
    $sender_details = get_user_details(get_id());

    $student_email = 
      $student_details['title'] . ' ' . $student_details['firstname'] . ' ' .
      $student_details['surname'] . " <" . $student_details['email'] . ">";

    $sender_email = 
      $sender_details['title'] . ' ' . $sender_details['firstname'] . ' ' .
      $sender_details['surname'] . " <" . $sender_details['email'] . ">";

    $extra = "From: $sender_email\r\n";
    if($_REQUEST['CC']) $extra .= "Cc: $sender_email\r\n";
    
    mail($student_email, $_REQUEST['subject'], $message, $extra);
  }

  company_displaystudents();
  }
  else
  {
    // Display a form to capture information to be emailed...
    $smarty->assign("vacancy_id", $vacancy_id);
    $smarty->assign("vacancy_name", get_vacancy_description($vacancy_id));
    $smarty->assign("company_id", $company_id);
    $smarty->assign("company_name", get_company_name($company_id));
    $smarty->assign("status", $status);
    $smarty->assign("student_id", $student_id);
    $smarty->assign("student_name", get_user_name($student_id));
    $smarty->assign("year", $year);


    $smarty->display("companies/companystudent_status_update.tpl");
    
  }
}

/**
**	@function embed_status_form()
**
** Output a form that will allow the status (companystudent) to be modified.
**
**	@var $row	an associative array of the output from companystudent
**	@return	a string containing the form
*/
function embed_status_form($row)
{
  global $PHP_SELF;
  global $year;
    
  $options = array('unseen', 'seen', 'invited to interview', 'missed interview', 'offered', 'unsuccessful');
  $output =  "<FORM ACTION=\"$PHP_SELF\" METHOD=\"POST\">\nStatus " .
             "<INPUT TYPE=\"HIDDEN\" NAME=\"mode\" VALUE=\"COMPANYSTUDENT_STATUS_UPDATE\">\n" .
             "<INPUT TYPE=\"HIDDEN\" NAME=\"vacancy_id\" VALUE=\"" . $row["vacancy_id"] . "\">\n" .
             "<INPUT TYPE=\"HIDDEN\" NAME=\"company_id\" VALUE=\"" . $row["company_id"] . "\">\n" .
             "<INPUT TYPE=\"HIDDEN\" NAME=\"year\" VALUE=\"" . $year . "\">\n" .
             "<INPUT TYPE=\"HIDDEN\" NAME=\"student_id\" VALUE=\"" . $row["student_id"] . "\">\n";

  $output .=  "<SELECT NAME=\"status\">\n";
  foreach($options as $option)
  {
    $output .= "<OPTION";
    if($option == $row['status']) $output .= " SELECTED";
    $output .= ">$option</OPTION>\n";
  }
  $output .= "</SELECT>\n";
  $output .= "<INPUT TYPE=\"SUBMIT\" VALUE=\"Update\">\n";
  $output .= "</FORM>&nbsp;";

  return($output);
}


function company_count_applications($company_id, $vacancy_id)
{

  if(!$vacancy_id) $vacancy_id = "NULL";

  $sql = "SELECT COUNT(*) FROM companystudent " .
    "WHERE company_id=$company_id AND vacancy_id <=> $vacancy_id";

  $result = mysql_query($sql)
    or print_mysql_error2("Unable to get number of applications", $sql);
  $data = mysql_fetch_row($result);
  mysql_free_result($result);
  return($data[0]);
}
  

function company_displaystudents()
{
  global $company_id;
  global $vacancy_id;
  global $conf;
  global $log;
  global $year;

  $options = array('unseen', 'seen', 'invited to interview', 'missed interview', 'offered', 'unsuccessful');

  if(empty($year)) $year = get_academic_year() + 1;

  echo "<H2 ALIGN=\"CENTER\">" .
       htmlspecialchars(get_company_name($company_id)) .
       "</H2>\n";

  echo "<H3 ALIGN=\"CENTER\">Student List for placement in ($year - " . 
       ($year + 1) . ")</H3>\n";

  print_wizard("Students");

  $vacancies = array();
  $sql = "SELECT * FROM vacancies WHERE year(jobstart)=$year " .
    "AND company_id=$company_id ORDER BY status, description";
  $result = mysql_query($sql)
    or print_mysql_error2("Unable to get vacancy information.", $sql);

  while($vacancy = mysql_fetch_array($result))
  {
    $vacancy["applications"] 
      = company_count_applications($company_id, $vacancy["vacancy_id"]);
    array_push($vacancies, $vacancy);
  }
  mysql_free_result($result);
  $vacancy["description"] = get_company_name($company_id);
  $vacancy["vacancy_id"] = NULL;
  $vacancy["applications"]   
     = company_count_applications($company_id, $vacancy["vacancy_id"]);
  array_push($vacancies, $vacancy);

  $placed_students = array();
  $available_students = array();
  $unavailable_students = array();
  $student_count = 0;

  $query = "SELECT cv_pdetails.*, companystudent.* FROM cv_pdetails, companystudent " .
           "LEFT JOIN students ON students.user_id=cv_pdetails.id " .
           "WHERE cv_pdetails.id = companystudent.student_id AND " .
           "companystudent.company_id =" . $company_id;

  if($vacancy_id)
  {
    $query .= " AND vacancy_id = " . $vacancy_id;
  }
  else
  {
    // Old company code
    $query .= " AND vacancy_id <=> NULL AND students.year=$year";
  }

  $query.= " ORDER BY created";

  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch current student list.", $query);


  while($row = mysql_fetch_array($result))
  {
    //print_r($row);
    // Check if the student is unseen, or changed since the company last looked...
    if($row["status"]=="unseen" || (!empty($row["modified"]) && ($row["modified"] > $row["lastseen"])))
    {
      $row["changed"]="Changed";
    }

    // Populate the row with extra information, namely the course name, and the link to the CV
    $row["course_name"] = get_course_name($row["course"]);
    if(!empty($row['archive_hash']))
    {
      $row["cv_link"] = archive_cv_link($row["id"], $vacancy_id);
    }
    else
    {
      $row["cv_link"] = cv_link($row["id"], $row["prefcvt"]);
    }

    // Check the student's placement status
    $status = get_student_status($row["id"]);
    $link = TRUE;
    if($status != "Required"){
      if($status == "Placed"){
	if($vacancy_id)
	{
	  $place_query = "SELECT * FROM placement WHERE student_id=" . $row["id"] .
	    " AND vacancy_id=$vacancy_id";
	}
	else
	{
	  $place_query = "SELECT * FROM placement WHERE student_id=" . $row["id"] .
	    " AND company_id=$company_id";
	}
	$place_result = mysql_query($place_query)
	  or print_mysql_error2("Unable to find placement company", $place_query);
	if(mysql_num_rows($place_result))
	{
          // The student is placed with this company
	  array_push($placed_students, $row);
	  $student_count++;
	  continue;
	}
	else
	{
          // The student is placed, but not with this company
	  $link = FALSE;
	}
	mysql_free_result($place_result);
      }
      else
      {
	// The student isn't labelled as "Required" or placed with this company - unavailable now!
	$link = FALSE;
      }
    }
    if($link)
    {
      // This student is still up for grabs
      array_push($available_students, $row);
      $student_count++;
    }
    else
    {
      // This student is no longer available
      array_push($unavailable_students, $row);
      $student_count++;
    }
  }


  
  echo "<FORM METHOD=\"POST\" ACTION=\"$PHP_SELF?mode=COMPANY_DISPLAYSTUDENTS" .
       "&company_id=$company_id\">\nTo see students seeking placement " .
       "starting in a different year click here " .
       "<INPUT TYPE=\"TEXT\" SIZE=\"4\" NAME=\"year\" VALUE=\"$year\">\n" .
       "<INPUT TYPE=\"submit\" NAME=\"button\" VALUE=\"Submit\">\n" .
       "</FORM>\n";

  $log['access']->LogPrint("Student list for " . get_company_name($company_id) . " viewed.");
  global $smarty;

  $smarty->assign("student_count", $student_count);
  $smarty->assign("company_id", $company_id);
  $smarty->assign("vacancy_id", $vacancy_id);
  $smarty->assign("year", $year);
  $smarty->assign_by_ref("vacancies", $vacancies);
  $smarty->assign_by_ref("status_options", $options);
  $smarty->assign_by_ref("placed_students", $placed_students);
  $smarty->assign_by_ref("available_students", $available_students);
  $smarty->assign_by_ref("unavailable_students", $unavailable_students);
  $smarty->display("companies/company_student_list.tpl");
}

function company_resource_list()
{
  global $conf;
  global $company_id;

  global $vacancy_id;


  echo "<H3 ALIGN=\"CENTER\">Private Resources Available</H3>\n";
  $query = "SELECT resources.* FROM resources, resourcelink WHERE " .
           "resources.resource_id = resourcelink.resource_id AND " .
           "resourcelink.company_id=$company_id ORDER BY description";

  $result = mysql_query($query)
    or print_mysql_error2("Unable to obtain private resources.", $query);

  if(mysql_num_rows($result))
  {
    echo "<TABLE ALIGN=\"CENTER\">\n";
    echo "<TR><TH>Language</TH><TH>Category</TH><TH>Description</TH><TH>File size</TH></TR>\n";
    while($row = mysql_fetch_array($result))
    {
      echo "<TR><TD>" . get_language_name($row["language_id"]) . "</TD>";
      echo "<TD>" . get_category_name($row["category_id"]) . "</TD>";
      echo "<TD><A HREF=\"" .$conf['scripts']['user']['resources'] .
           "?resource_id=" . $row["resource_id"]. "\">" . htmlspecialchars($row["description"]) . "</A></TD>";
      echo "<TD>" . (filesize($conf['paths']['resources'] . $row["resource_id"])) . "</TD></TR>";
    }
    echo "</TABLE>\n";
  }
  mysql_free_result($result);

  echo "<P ALIGN=\"CENTER\"><A HREF=\"" . $conf['scripts']['admin']['resourcedir'] .
       "?company_id=$company_id\">Click here to edit resources</A></P>\n";
}

function vacancy_delete()
{
  global $smarty;
  global $log;
  global $page;

  $confirmed  = $_REQUEST["confirmed"];
  $vacancy_id = $_REQUEST["vacancy_id"];

  $sql = "SELECT * FROM vacancies WHERE vacancy_id = $vacancy_id";
  $result = mysql_query($sql)
    or print_mysql_error2("Unable to fetch vacancy information", $sql);
  $vacancy = mysql_fetch_array($result);
  mysql_free_result($result);
  $company_name = get_company_name($vacancy["company_id"]);

  if(!is_admin() || !check_default_policy('vacancy', 'delete'))
  {
    die_gracefully("You do not have permission to delete vacancies.");
  }

  if($confirmed)
  {
    $sql = "DELETE FROM vacancies WHERE vacancy_id = $vacancy_id";
    mysql_query($sql)
      or print_mysql_error2("Unable to delete vacancy.", $sql);
    $notify = "Vacancy [" .
      $vacancy["description"] . "] deleted for company " .
      $company_name;

    $log['access']->LogPrint[$notify];
    $page["notify"] .= $notify;
    
    company_vacancy_list();
  }
  else
  {
    $vacancy["company_name"] = $company_name;
    $smarty->assign_by_ref("vacancy", $vacancy);
    $smarty->display("companies/vacancy_delete.tpl");
  }
}
 

function vacancy_update()
{
  global $log;
  global $page;
  global $company_id;
  global $smarty;

  $vacancy_id = $_REQUEST["vacancy_id"];
  $activities = $_REQUEST["activities"];

  if(is_admin() && !check_default_policy('vacancy', 'edit'))
  {
    die_gracefully("You do not have permission to edit vacancies.");
  }


  // Merge date and time fields for closedate
  $closedate_time = $_REQUEST["closedate_time"];
  $closedate = $_REQUEST["closedate"];
  $closedate = parse_and_check_date($closedate);
  if($closedate['sql'] != 'NULL')
  {
    // There's a given date, is it valid?
    if(!$closedate['valid'])
    {
      die_gracefully("Your closing date given is invalid. Please use the back " .
        "button on your browser and correct the problem.");
    }
     
    // Ok, it was valid so add the time stamp
    // We need to remove any spaces etc from it
    $closedate_time_sql = preg_replace("/[: ]/", "", $closedate_time);
    $closedate['sql'] .= "$closedate_time_sql";
  }
  else
  {
    $closedate = "";
  }

  $jobstart  = parse_and_check_date($_REQUEST["jobstart"]);
  $jobend    = parse_and_check_date($_REQUEST["jobend"]);

  if(($jobstart['sql'] != 'NULL') && (!$jobstart['valid']))
  {
    die_gracefully("Your starting date given is invalid. Please use the back " .
      "button on your browser and correct the problem.");    
  }
  if(($jobend['sql'] != 'NULL') && (!$jobend['valid']))
  {
    die_gracefully("Your job end date given is invalid. Please use the back " .
      "button on your browser and correct the problem.");    
  }
  
  
  if(empty($_REQUEST['jobstart']))
  {
    die_gracefully("A start date (even approximate) is necessary so that the system can determine " .
		   "which students should see your vacancy. Please use the back button on the browser " .
		   "to go back and correct this.");
  }


  $sql = "UPDATE vacancies SET" .
    " description = " . make_null($_REQUEST["description"]) .
    ",modified = " .    make_null(date("YmdHis")) . 
    ",closedate = " .   make_null($closedate["sql"]) .
    ",jobstart = " .    make_null($jobstart["sql"]) .
    ",jobend = " .      make_null($jobend["sql"]) . 
    ",address1 = " .    make_null($_REQUEST["address1"]) .
    ",address2 = " .    make_null($_REQUEST["address2"]) .
    ",address3 = " .    make_null($_REQUEST["address3"]) .
    ",town = " .        make_null($_REQUEST["town"]) .
    ",locality = " .    make_null($_REQUEST["locality"]) .
    ",country = " .     make_null($_REQUEST["country"]) .
    ",postcode = " .    make_null($_REQUEST["postcode"]) . 
    ",www = " .         make_null($_REQUEST["www"]) .
    ",salary = " .      make_null($_REQUEST["salary"]) .
    ",brief = " .       make_null($_REQUEST["brief"]) .
    ",status = " .      make_null($_REQUEST["status"]) .
    ",contact_id = " .  make_null($_REQUEST["contact_id"]) .
    " WHERE vacancy_id=$vacancy_id";

  mysql_query($sql)
    or print_mysql_error2("Unable to update vacancy", $sql);


  // Cleanest way to update activities is to delete them and
  // create them anew.
  $sql = "DELETE FROM vacancyactivity WHERE vacancy_id=$vacancy_id";
  mysql_query($sql)
    or print_mysql_error2("Unable to delete activities for vacancy", $sql);
  foreach($activities as $activity_id)
  {
    $sql = "INSERT INTO vacancyactivity (vacancy_id, activity_id) " .
      "VALUES($vacancy_id, $activity_id)";
    mysql_query($sql)
      or print_mysql_error2("Unable to insert activity types", $sql);
  }

  $company_id = $_REQUEST["company_id"];
  $notify ="Vacancy [" .
    $_REQUEST["description"] .
    "] updated on company " .
    get_company_name($_REQUEST["company_id"]);

  $log['access']->LogPrint($notify);

  $page["notify"] .= $notify;
  
  // Check the parsing of the code
  $brief = xml_parser(clean_bad_xhtml(stripslashes($_REQUEST['brief'])));
  if($brief['errors'])
  {
    $smarty->assign("parsedxml", $brief);
    $smarty->assign("vacancy_id", $vacancy_id);
    $smarty->display("companies/vacancy_parse_error.tpl");
  }

  company_vacancy_list();
}


function vacancy_edit()
{
  global $conf;
  global $company_id;
  $vacancy_id = $_REQUEST["vacancy_id"];

  $sql = "SELECT * FROM vacancies WHERE vacancy_id=$vacancy_id";
  $result = mysql_query($sql)
    or print_mysql_error2("Unable to fetch vacancy information", $sql);
  $vacancy = mysql_fetch_array($result);
  mysql_free_result($result);
  $company_id = $vacancy["company_id"];

  // Experimental
  $lastitem = new Lastitem('vacancy', $vacancy_id, 'v:' . $vacancy["description"], 
    $conf['scripts']['company']['edit']   . "?mode=VacancyEdit&vacancy_id=$vacancy_id");
  $_SESSION['lastitems']->add($lastitem);


  vacancy_basicform($vacancy);
}

function vacancy_clone()
{
  global $company_id;
  $vacancy_id = $_REQUEST["vacancy_id"];

  $sql = "SELECT * FROM vacancies WHERE vacancy_id=$vacancy_id";
  $result = mysql_query($sql)
    or print_mysql_error2("Unable to fetch vacancy information", $sql);
  $vacancy = mysql_fetch_array($result);
  mysql_free_result($result);
  $company_id = $vacancy["company_id"];

  vacancy_basicform($vacancy, TRUE);
}


function vacancy_list()
{
  global $smarty;
  global $company_id;
  global $year;
//  $company_id = $_REQUEST["company_id"];
  $vacancies = array();

  $sql = "SELECT vacancies.*, year(jobstart) as start_year FROM vacancies WHERE company_id=$company_id ";

  $showyear = $_REQUEST['showyear'];
  if($showyear)
  {
    if(empty($year)) $year = (get_academic_year() + 1);
    $sql .= " AND year(jobstart)=$year ";
  }

  $sql .= "ORDER BY year(jobstart) desc, status, description";
  $result = mysql_query($sql)
    or print_mysql_error2("Unable to fetch vacancy list", $sql);
  while($row=mysql_fetch_array($result))
  {
    array_push($vacancies, $row);
  }
  mysql_free_result($result);

  $smarty->assign("company_id", $company_id);
  $smarty->assign_by_ref("vacancies", $vacancies);
  $smarty->assign("showyear", $showyear);
  $smarty->assign("year", $year);
  $smarty->display("companies/vacancy_list.tpl");
}


/**
 **@function vacancy_basicform()
 **
 **Shows the form for created or editing a vacancy
 **
 **@var $vacancy an associative array with the vacancy elements, or NULL
 */
function vacancy_basicform($vacancy, $clone = FALSE)
{
  global $conf;
  global $company_id;
  global $vacancy_id;
  global $smarty;

  print_wizard("Vacancies");

  $activities = activities_fetch();

  $form = array();
  $form['name'] = "vacancyform";
  $form['method'] = "post";
  $form['action'] = $_SERVER['PHP_SELF'];
  $form['charset'] = "ISO-8859-1";

  $form['hidden'] = array();
  $form['hidden']['company_id']=$company_id;

  $smarty->assign("clone", $clone);

  if(empty($vacancy_id) || $clone)
  {
    // No existing vacancy id, so this must be INSERT
    // or it is to be a clone of an existing one...
    $form['hidden']['mode']="VacancyInsert";
    $selected_activities = array();
 
    if(!$clone)
    {
      // Get some company information to insert defaults
      $query = "SELECT * FROM companies WHERE company_id=$company_id";
      $result = mysql_query($query)
	or print_mysql_error2("Unable to fetch company info.", $query);
      $company_info = mysql_fetch_array($result);
      mysql_free_result($result);
    
      $vacancy = array();
      $vacancy["address1"] = $company_info["address1"];
      $vacancy["address2"] = $company_info["address2"];
      $vacancy["address3"] = $company_info["address3"];
      $vacancy["town"]     = $company_info["town"];
      $vacancy["locality"] = $company_info["locality"];
      $vacancy["country"]  = $company_info["country"];
      $vacancy["postcode"] = $company_info["postcode"];
      $vacancy["www"]      = $company_info["www"];

      // Non persistent (for template only)
      $vacancy["company_name"] = $company_info["name"];
    }
    else
    {
      $selected_activities = vacancy_activities_fetch($vacancy_id);
      $vacancy["company_name"] = get_company_name($company_id);

      // Separate the time and date
      $vacancy["closedate_time"] = substr($vacancy['closedate'], 11);
      $vacancy["closedate"] = substr($vacancy['closedate'], 0, 10);
    }    
  }
  else
  {
    $form['hidden']['vacancy_id']=$vacancy_id;
    $form['hidden']['mode']="VacancyUpdate";
    $selected_activities = vacancy_activities_fetch($vacancy_id);
    $vacancy["company_name"] = get_company_name($company_id);

    // Separate the time and date
    $vacancy["closedate_time"] = substr($vacancy['closedate'], 11);
    $vacancy["closedate"] = substr($vacancy['closedate'], 0, 10);
  }
  
  if(empty($vacancy['closedate_time'])) $vacancy['closedate_time'] = '17:00:00';
  // Get list of company contacts
  $query = "SELECT contacts.* FROM contacts, companycontact WHERE " .
           "contacts.contact_id = companycontact.contact_id AND " .
           "company_id=$company_id ORDER BY surname";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch contact list.", $query);
  $form['data'] = array();
  $form['data']['contacts'] = array();
  $form['data']['contacts']['ids'] = array();
  $form['data']['contacts']['names'] = array();
  while($row = mysql_fetch_array($result))
  {
    array_push($form['data']['contacts']['ids'], $row['contact_id']);
    array_push($form['data']['contacts']['names'], ($row['title'] . " " . $row['firstname'] . " " . $row['surname']));
  }
  mysql_free_result($result);
  $form['data']['status']=array('open', 'closed', 'special');

  $brief = xml_parser(clean_bad_xhtml($vacancy['brief']));

  $smarty->assign("form", $form);
  $smarty->assign("vacancy", $vacancy);
  $smarty->assign("brief", $brief);
  $smarty->assign("activities", $activities);
  $smarty->assign("selected_activities", $selected_activities);
  $smarty->display("companies/vacancy_form.tpl");
}


function vacancy_insert()
{
  global $smarty;
  global $page;
  global $log;

  // Merge date and time fields for closedate
  $closedate_time = $_REQUEST["closedate_time"];
  $closedate = $_REQUEST["closedate"];
  $closedate = parse_and_check_date($closedate);
  if($closedate['sql'] != 'NULL')
  {
    // There's a given date, is it valid?
    if(!$closedate['valid'])
    {
      die_gracefully("Your closing date given is invalid. Please use the back " .
        "button on your browser and correct the problem.");
    }
     
    // Ok, it was valid so add the time stamp
    // We need to remove any spaces etc from it
    $closedate_time_sql = preg_replace("/[: ]/", "", $closedate_time);
    $closedate['sql'] .= "$closedate_time_sql";
  }
  else
  {
    $closedate = "";
  }

  $jobstart  = parse_and_check_date($_REQUEST["jobstart"]);
  $jobend    = parse_and_check_date($_REQUEST["jobend"]);

  if(($jobstart['sql'] != 'NULL') && (!$jobstart['valid']))
  {
    die_gracefully("Your starting date given is invalid. Please use the back " .
      "button on your browser and correct the problem.");    
  }
  if(($jobend['sql'] != 'NULL') && (!$jobend['valid']))
  {
    die_gracefully("Your job end date given is invalid. Please use the back " .
      "button on your browser and correct the problem.");    
  }
  if(empty($_REQUEST['jobstart']))
  {
    die_gracefully("A start date (even approximate) is necessary so that the system can determine " .
       "which students should see your vacancy. Please use the back button on the browser " .
       "to go back and correct this.");
  }

  
  
  $activities = $_REQUEST["activities"];


  if(!count($activities))
  {
    die_gracefully("You must select at least one activity type.");
  }

  $sql = "INSERT INTO vacancies " .
    "(company_id, description, created, modified, " .
    "closedate, jobstart, jobend, address1, address2, " .
    "address3, town, locality, country, postcode, www, " .
    "salary, brief, status, contact_id) VALUES(" .
    $_REQUEST["company_id"] . ", " .
    make_null($_REQUEST["description"]) . ", " .
    make_null(date("YmdHis")) . ", NULL, " .
    make_null($closedate["sql"]) . ", " .
    make_null($jobstart["sql"]) . ", " .
    make_null($jobend["sql"]) . ", " .
    make_null($_REQUEST["address1"]) . ", " .
    make_null($_REQUEST["address2"]) . ", " .
    make_null($_REQUEST["address3"]) . ", " .
    make_null($_REQUEST["town"]) . ", " .
    make_null($_REQUEST["locality"]) . ", " .
    make_null($_REQUEST["country"]) . ", " .
    make_null($_REQUEST["postcode"]) . ", " .
    make_null($_REQUEST["www"]) . ", " .
    make_null($_REQUEST["salary"]) . ", " .
    make_null($_REQUEST["brief"]) . ", " .
    make_null($_REQUEST["status"]) . ", " .
    make_null($_REQUEST["contact_id"]) . ")";

  mysql_query($sql)
    or print_mysql_error2("Unable to insert vacancy", $sql);

  // Get the allocated vacancy_id
  $vacancy_id = mysql_insert_id();

  foreach($activities as $activity_id)
  {
    $sql = "INSERT INTO vacancyactivity (vacancy_id, activity_id) " .
      "VALUES($vacancy_id, $activity_id)";
    mysql_query($sql)
      or print_mysql_error2("Unable to insert activity types", $sql);
  }
  $notify = "New vacancy [" .
    $_REQUEST["description"] .
    "] added to company " .
    get_company_name($_REQUEST["company_id"]);

  $log['access']->LogPrint($notify);

  $page["notify"] .= $notify;
    
  // Check the parsing of the code
  $brief = xml_parser(clean_bad_xhtml(stripslashes($_REQUEST['brief'])));
  if($brief['errors'])
  {
    $smarty->assign("parsedxml", $brief);
    $smarty->assign("vacancy_id", $vacancy_id);
    $smarty->display("companies/vacancy_parse_error.tpl");
  }

  company_vacancy_list();
}


function vacancy_add()
{
  vacancy_basicform(NULL);
}



function activities_fetch()
{
  $sql = "SELECT * FROM vacancytype ORDER BY name";
  $result = mysql_query($sql)
    or print_mysql_error2("Unable to fetch activity types", $sql);

  $activities = array();
  while($activity = mysql_fetch_array($result))
  {
    $activities[$activity["vacancy_id"]] = $activity["name"];
  }
  mysql_free_result($result);
  return($activities);
}


function vacancy_activities_fetch($vacancy_id)
{
  // Get the master list
  $selected = array();

  $sql = "SELECT * FROM vacancyactivity WHERE vacancy_id=$vacancy_id";
  $result = mysql_query($sql)
    or print_mysql_error2("Unable to fetch activities for vacancy", $sql);
  while($row = mysql_fetch_array($result))
  {
    array_push($selected, $row["activity_id"]);
  }
  mysql_free_result($result);
  return($selected);
}

function company_activities_fetch($company_id)
{
  // Get the master list
  $selected = array();

  $sql = "SELECT DISTINCT vacancy_id FROM companyvacancy WHERE company_id=$company_id";
  $result = mysql_query($sql)
    or print_mysql_error2("Unable to fetch activities for company", $sql);
  while($row = mysql_fetch_array($result))
  {
    array_push($selected, $row["vacancy_id"]);
  }
  mysql_free_result($result);
  return($selected);
}



function email_cv()
{
  $company_id  = $_REQUEST['company_id'];
  $vacancy_id  = $_REQUEST['vacancy_id'];
  $template_id = $_REQUEST['template_id'];
  $student_id  = $_REQUEST['student_id'];

  $vacancy_description = get_vacancy_description($vacancy_id);
  $company_name = get_company_name($company_id);


  $student_reg = get_login_name($student_id);

  $text = "Please find enclosed a CV for a student\n" .
    "Student : " . get_user_name($student_id) . "\n" .
    "Vacancy : $vacancy_description\n" .
    "Company : $company_name\n";

  $files = array();
  $file  = array();
  $file['data'] = PDSystem::fetch_cv($student_id, $template_id);
  $file['type'] = "application/pdf";
  $file['name'] = "$student_reg.pdf";

  $student_details = get_user_details($student_id);
  $sender_details = get_user_details(get_id());

  $student_name = 
    $student_details['title'] . ' ' . $student_details['firstname'] . ' ' .
    $student_details['surname'];

  $sender_email = 
    $sender_details['title'] . ' ' . $sender_details['firstname'] . ' ' .
    $sender_details['surname'] . " <" . $sender_details['email'] . ">";


  $headers = array(
              'From'    => $sender_email,
              'Subject' => "CV for vacancy $vacancy_description, student $student_name"
              );

  array_push($files, $file);
  
  send_email($headers, $text, $files, $sender_email);
  company_displaystudents();
}

function print_wizard($item)
{
  global $conf;
  global $company_id;
  global $smarty;
  global $year;

  if(empty($year)) $year = get_academic_year() + 1;

  $wizard2 = new TabbedContainer($smarty, 'tabs');
  $wizard2->addTab('Basics', $_SERVER['PHP_SELF'] . "?mode=COMPANY_BASICEDIT&company_id=$company_id&year=$year");
  $wizard2->addTab('Vacancies', $_SERVER['PHP_SELF'] . "?mode=CompanyVacancyList&company_id=$company_id&year=$year");
  $wizard2->addTab('Contacts', $_SERVER['PHP_SELF'] . "?mode=COMPANY_DISPLAYCONTACTS&company_id=$company_id&year=$year");
  $wizard2->addTab('Students', $_SERVER['PHP_SELF'] . "?mode=COMPANY_DISPLAYSTUDENTS&company_id=$company_id&year=$year");
  $wizard2->addTab('Notes', $_SERVER['PHP_SELF'] . "?mode=DISPLAY_NOTES&company_id=$company_id&year=$year");

  // Transitionary code
  echo "<div name=\"tabbedContainer\" align=\"center\">\n";
  $wizard2->displayTab($item);
  echo "</div>\n";
}


?>


