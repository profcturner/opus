<?php

/**
**  index.php
**
** This is the index page for authenticated company contacts.
**
** Initial coding : Colin Turner
**
*/

// The include files 
include('common.php');		
include('authenticate.php');
include('lookup.php');	
include('cv.php');


// Connect to the database on the server
db_connect()
  or die("Unable to connect to server");
  
// Authenticate user so that the right people see the right thing
auth_user("company");

$page = new HTMLOPUS("Company Home Page", "Home");       // Calls the function for the header

if(!is_admin() && !is_company()){
  die_gracefully("You do not have permission to access this page.");
}

if(!is_admin() || empty($user_id)) $user_id = get_id();

$last_index = get_last_index();

$mode = $_REQUEST['mode'];
echo "<H2 ALIGN=\"CENTER\">Welcome " .
     get_user_name($user_id) . "</H2>";

if($mode != 'CompanySmallMenu')
  output_help("ContactHome");

// First fetch a list of companies this person represents

$contact_id = get_contact_id($user_id);

$query = "SELECT companies.name, companies.locality, companies.company_id " .
         "FROM companycontact, companies WHERE companycontact.company_id=" .
         "companies.company_id AND companycontact.contact_id=" . $contact_id;

$result = mysql_query($query)
  or print_mysql_error2("Unable to fetch company list.", $query);

while($row = mysql_fetch_array($result))
{
  echo "<H1 ALIGN=\"CENTER\">" . htmlspecialchars($row["name"]) . "</H1>";
  print_company_quick_info($row["company_id"]);
  echo "<BR>\n";
}

mysql_free_result($result);

touch_last_index();

function print_company_quick_info($company_id)
{
  global $conf;
  global $year;

  echo "<ol>\n";
  echo "<li><A HREF=\"" .
    $conf['scripts']['company']['edit'] . "?company_id=" .
    $company_id . "\">Click here to edit main company details.</A></li>";

  echo "<li><A HREF=\"" .
    $conf['scripts']['company']['edit'] . "?mode=CompanyVacancyList&company_id=" .
    $company_id . "\">Click here to edit / create job adverts.</A></li>";    
    
  echo "<li><A HREF=\"" .
    $conf['scripts']['company']['edit'] . 
    "?mode=COMPANY_DISPLAYSTUDENTS&company_id=" .
    $company_id . "\">Click here to view student applications.</A></li>";

}



// Print the footer and finish the page
$page->end();

?>
