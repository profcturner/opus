<?php 

/**
**	index.php
**
** This is the admin index for the site, a central
** listing of resources for administrators.
**
** Initial coding : Colin Turner
**
*/

// The include files
include('common.php');
include('authenticate.php');
include('lookup.php');

// Connect to the database on the server
db_connect()
  or die("Unable to connect to server");

// Authenticate user so that the right people see the right thing
auth_user("admin");

//page_header('Administration Menu');   // Calls the function for the header
//print_menu("admin");                  // Print the menu for the admin user

$page = new HTMLOPUS('Administration Menu', 'home', 'Home');

printf("<H2 ALIGN=\"CENTER\">Welcome</H2>\n");


if(is_root()) output_help("RootHome");
else output_help("AdminHome");


if(!empty($days))
{
  $unixtime = time();
  $unixtime -= ($days * 24 * 60 * 60);
  $last_index = date("YmdHis", $unixtime);
}
else $last_index = $_SESSION['user']['lastindex'];

$new_vacancies = get_new_vacancies();
$mod_vacancies = get_mod_vacancies();
//$smarty->assign("student_id", $student_id);
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
  if(!empty($days)){
    echo "in the last $days days.</P>\n";
  } else echo "since your last visit.</P>\n";
  print_company_result($result, 1);
}
mysql_free_result($result);


// Modified companies on the system
$query = "SELECT name, locality, company_id FROM companies" .
         " WHERE modified > " . $last_index;

$result = mysql_query($query)
  or print_mysql_error2("Unable to fetch current company list.", $query);

echo "<HR>\n<H3 ALIGN=\"CENTER\">Modified companies</H3>\n";

if(mysql_num_rows($result)){
  echo "<P ALIGN=\"CENTER\">These companies have been modified ";
  if(!empty($days)) echo "in the last $days days.</P>\n";
  else echo "since your last visit.</P>\n";
  print_company_result($result, 1);
}
else
{
  echo "<P ALIGN=\"CENTER\">No companies have been modified since your last access.</P>\n";
} 
mysql_free_result($result);

if(empty($days)) $days=7;
echo "<HR>\n<P ALIGN=\"CENTER\"><FORM ACTION=\"$PHP_SELF\">\n";
echo "Show changes in the last " .
     "<INPUT TYPE=\"TEXT\" NAME=\"days\" VALUE=\"$days\" SIZE=\"3\"> days. " .
     "<INPUT TYPE=\"SUBMIT\" NAME=\"BUTTON\" VALUE=\"Submit\">" .
     "</FORM></P>";

touch_last_index();

$page->end();
//page_footer();			// Calls the function for the footer

function print_company_result($result, $start)
{
  global $conf;
  $first = TRUE;

  if(mysql_num_rows($result)){
    echo "<OL TYPE=\"1\" START=\"" . $start . "\">\n";
    while($row = mysql_fetch_row($result))
    {  
       echo "  <LI><A HREF=\"" . $conf['scripts']['company']['directory'] .
            "?mode=CompanyView&company_id=" . $row[2] . "\">" .
            htmlspecialchars($row[0]) . ", " . htmlspecialchars($row[1]) .
            "</A>\n";
       echo "<a href=\"" . $conf['scripts']['company']['edit'] .
	 "?mode=COMPANY_BASICEDIT&company_id=" . $row[2] . "\"> [ Edit ]</a></LI>\n";
    }
    echo "</OL>\n";
  }
}

function get_new_vacancies()
{
  global $last_index;
  global $pyear;
  $vacancies = array();

  $sql = "SELECT * FROM vacancies WHERE " .
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


?>


