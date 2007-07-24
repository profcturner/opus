<?php 

/**
**	status.php
**
** This script displays useful status information
** for admin users.
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

if(!check_default_policy('status', 'user'))
  print_auth_failure("ACCESS");

$page = new HTMLOPUS("System Status", "information");   // Calls the function for the header

user_status();

function user_status()
{
  global $conf; // Access to configuration
  global $lastin;
  global $PHP_SELF;


  if(empty($lastin)) $lastin = 10;
  
  echo "<p align=\"center\">\n";
  echo "<a href=\"#admins\">[ Admin Users ]</a> ";
  echo "<a href=\"#staff\">[ Academic Staff ]</a> <a href=\"#contacts\">[ Company Contacts ]</a> ";
  echo "<a href=\"#supervisors\">[ Industrial Supervisors ]</a> <a href=\"#students\">[ Students ]</a></p>";
  echo "<a name=\"users\">";
  echo "<H2 ALIGN=\"CENTER\">User Status</H2>\n";

  echo "<FORM ACTION=\"$PHP_SELF\" METHOD=\"POST\"><P>" .
       "Show the last <INPUT NAME=\"lastin\" VALUE=\"$lastin\" SIZE=\"3\"> visitors in " .
       "each category. <INPUT NAME=\"Submit\" TYPE=\"Submit\"></P></FORM>\n";

  $query = "SELECT id.*, admins.position FROM id " .
           "LEFT JOIN admins ON id.id_number = admins.user_id " .
           "WHERE user='root' ORDER BY id.last_time DESC, admins.surname";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to get admin users.", $query);
  
  $root_count = mysql_num_rows($result);
  echo "<a name=\"admins\">";
  echo "<H3 ALIGN=\"CENTER\">Super Admin Users (root)</H3>\n";
  echo "<P>There are " . $root_count . " super admin (root) users in the database, " .
       "which are all listed below for security reasons.</P>\n";
  echo "<TABLE BORDER=\"1\" ALIGN=\"CENTER\">\n";
  echo "<TR><TH>Username</TH><TH>Real name</TH><TH>Last access</TH><TH>Position</TH></TR>\n";
  while($row = mysql_fetch_array($result))
  {
    echo "<TR><TD>" . htmlspecialchars($row["username"]) . 
         "</TD><TD>" . htmlspecialchars($row["real_name"]) .
         "</TD><TD>" . htmlspecialchars($row["last_time"]) .
         "</TD><TD>" . htmlspecialchars($row["position"]) .
         "</TD></TR>\n";
  }
  echo "</TABLE>\n";
  mysql_free_result($result);


  $query = "SELECT id.*, admins.position FROM id " .
           "LEFT JOIN admins ON id.id_number = admins.user_id " .
           "WHERE user='admin' ORDER BY id.last_time DESC, admins.surname";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to get admin users.", $query);
  
  $admin_count = mysql_num_rows($result);
  echo "<H3 ALIGN=\"CENTER\">Admin Users</H3>\n";
  echo "<P>There are " . $admin_count . " admin users in the database, " .
       "which are all listed below for security reasons. These admin users can be limited by policy decisions.</P>\n";
  echo "<TABLE BORDER=\"1\" ALIGN=\"CENTER\">\n";
  echo "<TR><TH>Username</TH><TH>Real name</TH><TH>Last access</TH><TH>Position</TH></TR>\n";
  while($row = mysql_fetch_array($result))
  {
    echo "<TR><TD>" . htmlspecialchars($row["username"]) . 
         "</TD><TD>" . htmlspecialchars($row["real_name"]) .
         "</TD><TD>" . htmlspecialchars($row["last_time"]) .
         "</TD><TD>" . htmlspecialchars($row["position"]) .
         "</TD></TR>\n";
  }
  echo "</TABLE>\n";
  mysql_free_result($result);
  echo "<a href=\"#top\">Back to top</a>\n";

  // staff users
  $query = "SELECT id_number, real_name, username, last_time FROM id WHERE user='staff'" .
           " ORDER BY last_time DESC";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to get staff users.", $query);
  
  $staff_count = mysql_num_rows($result);
  echo "<a name=\"staff\">\n";
  echo "<H3 ALIGN=\"CENTER\">Staff Users</H3>\n";
  echo "<P>There are " . $staff_count . " staff users in the database, " .
       "of which the $lastin most recent visitors are listed below.</P>\n";
  echo "<TABLE BORDER=\"1\" ALIGN=\"CENTER\">\n";
  echo "<TR><TH>Username</TH><TH>Real name</TH><TH>Last access</TH></TR>\n";
  $count = 0;
  while($row = mysql_fetch_array($result))
  {
    $count++;
    echo "<TR><TD>" . htmlspecialchars($row["username"]) . 
         "</TD><TD><A HREF=\"" . $conf['scripts']['staff']['directory'] . 
         "?user_id=" . $row["id_number"] . "\">" .
          htmlspecialchars($row["real_name"]) .
         "</A></TD><TD>" . htmlspecialchars($row["last_time"]) . "</TD></TR>\n";
    if($count == $lastin) break;
  }
  echo "</TABLE>\n";
  echo "<a href=\"#top\">Back to top</a>\n";
  mysql_free_result($result);

  // contact users
  $query = "SELECT id_number, real_name, username, last_time FROM id WHERE user='company'" .
           " ORDER BY last_time DESC";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to get company contacts.", $query);
  
  $contact_count = mysql_num_rows($result);
  echo "<a name=\"contacts\">\n";
  echo "<H3 ALIGN=\"CENTER\">Company Contact Users</H3>\n";
  echo "<P>There are " . $contact_count . " company contact users in the database, " .
       "of which the $lastin most recent visitors are listed below.</P>\n";
  echo "<TABLE BORDER=\"1\" ALIGN=\"CENTER\">\n";
  echo "<TR><TH>Username</TH><TH>Real name</TH><TH>Last access</TH><TH>Company</TH></TR>\n";
  $count = 0;
  while($row = mysql_fetch_array($result))
  {
    $count++;
    echo "<TR><TD>" . htmlspecialchars($row["username"]) . 
         "</TD><TD><A HREF=\"" . $conf['scripts']['company']['contacts'] .
         "?contact_id=" . get_contact_id($row["id_number"]) . "\">" .
         htmlspecialchars($row["real_name"]) .
         "</A></TD><TD>" . htmlspecialchars($row["last_time"]) . "</TD><TD>";

    $mini_query = "SELECT companies.name, companies.company_id FROM " .
                  "companies, companycontact WHERE " .
                  "companies.company_id = companycontact.company_id AND " .
                  "companycontact.contact_id=" . get_contact_id($row["id_number"]);

    $mini_result = mysql_query($mini_query)
       or print_mysql_error2("Unable to fetch company list for contact.", $mini_query);
    while($mini_row = mysql_fetch_array($mini_result))
    {
      echo "<A HREF=\"" . $conf['scripts']['company']['edit'] .
           "?company_id=" . $mini_row["company_id"] . "\">" .
           htmlspecialchars($mini_row["name"]) . "</A><BR>";
    }
    mysql_free_result($mini_result);
    echo "</TD></TR>\n";
    if($count == $lastin) break;
  }
  echo "</TABLE>\n";
  echo "<a href=\"#top\">Back to top</a>\n";

  // industrial supervisors
  $query = "SELECT id_number, real_name, username, last_time FROM id WHERE user='supervisor'" .
           " ORDER BY last_time DESC";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to get company contacts.", $query);
  
  $supervisor_count = mysql_num_rows($result);
  echo "<a name=\"supervisors\">\n";
  echo "<H3 ALIGN=\"CENTER\">Industrial Supervisor Users</H3>\n";
  echo "<P>There are " . $supervisor_count . " industrial supervisor users in the database, " .
       "of which the $lastin most recent visitors are listed below.</P>\n";
  echo "<TABLE BORDER=\"1\" ALIGN=\"CENTER\">\n";
  echo "<TR><TH>Username</TH><TH>Real name</TH><TH>Last access</TH><TH>Placement Student, Vacancy</TH></TR>\n";
  $count = 0;
  while($row = mysql_fetch_array($result))
  {
    $count++;
    
    $placement_id = str_replace("supervisor_", "", $row["username"]);
    echo "<TR><TD>" . htmlspecialchars($row["username"]) . 
         "</TD><TD><A HREF=\"" . $conf['scripts']['supervisor']['index'] .
         "?supervisor_id=" . ($row["id_number"]) . "\">" .
         htmlspecialchars($row["real_name"]) .
         "</A></TD><TD>" . htmlspecialchars($row["last_time"]) . "</TD><TD>";
         
         
         $mini_query = "SELECT * from placement where placement_id=$placement_id";

         $mini_result = mysql_query($mini_query)
            or print_mysql_error2("Unable to fetch company list for contact.", $mini_query);

    $mini_row = mysql_fetch_array($mini_result);
    
    echo "<A HREF=\"" . $conf['scripts']['admin']['studentdir'] .
         "?mode=STUDENT_DISPLAYSTATUS&student_id=" . $mini_row['student_id'] . "\">";
    echo htmlspecialchars(get_user_name($mini_row['student_id'])) . "</a><br />";
    if($mini_row['vacancy_id'])
    {
      echo "<a href=\"" . $conf['scripts']['company']['directory'] . 
        "?mode=VacancyView&vacancy_id=" . $mini_row['vacancy_id'] .
        "\">" . 
          htmlspecialchars(get_vacancy_description($mini_row['vacancy_id'])) . "</a><br />";
    }
    echo "<a href=\"\">" . 
       htmlspecialchars(get_company_name($mini_row['company_id'])) . "</a>";
    echo "</TR></tr>\n";
    mysql_free_result($mini_result);

    if($count == $lastin) break;
  }
  echo "</TABLE>\n";
  echo "<a href=\"#top\">Back to top</a>\n";
  
  
  // student users
  $query = "SELECT id_number, real_name, username, last_time FROM id WHERE user='student'" .
           " ORDER BY last_time DESC";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to get student users.", $query);
  
  $student_count = mysql_num_rows($result);
  echo "<a name=\"students\">\n";
  echo "<H3 ALIGN=\"CENTER\">Student Users</H3>\n";
  echo "<P>There are " . $student_count . " student users in the database, " .
       "of which the $lastin most recent visitors are listed below.</P>\n";
  echo "<TABLE BORDER=\"1\" ALIGN=\"CENTER\">\n";
  echo "<TR><TH>Username</TH><TH>Real name</TH><TH>Last access</TH></TR>\n";
  $count = 0;
  while($row = mysql_fetch_array($result))
  {
    $count++;
    echo "<TR><TD>" . htmlspecialchars($row["username"]) . 
         "</TD><TD><A HREF=\"" . $conf['scripts']['admin']['studentdir'] .
         "?mode=STUDENT_DISPLAYSTATUS&student_id=" . $row["id_number"] . "\">" .
         htmlspecialchars($row["real_name"]) .
         "</A></TD><TD>" . htmlspecialchars($row["last_time"]) . "</TD></TR>\n";
    if($count == $lastin) break;
  }
  echo "</TABLE>\n";
  echo "<a href=\"#top\">Back to top</a>\n";
  mysql_free_result($result);
       
  echo "<H3 ALIGN=\"CENTER\">Summary</H3>\n";  

  echo "<TABLE BORDER=\"1\" ALIGN=\"CENTER\">\n";
  echo "<TR><TD>Super Admin Users</TD><TD>$root_count</TD></TR>\n";
  echo "<TR><TD>Admin Users</TD><TD>$admin_count</TD></TR>\n";
  echo "<TR><TD>Staff Users</TD><TD>$staff_count</TD></TR>\n";
  echo "<TR><TD>Contact Users</TD><TD>$contact_count</TD></TR>\n";
  echo "<TR><TD>Industrial Supervisor Users</TD><TD>$supervisor_count</TD></TR>\n";  
  echo "<TR><TD>Student Users</TD><TD>$student_count</TD></TR>\n";
  echo "<TR><TD><B>Total</B></TD><TD><B>" . 
       ($admin_count + $staff_count + $contact_count + $student_count) . 
       "</TD></TR>\n";
  echo "</TABLE>\n";

/*
  echo "<H3 ALIGN=\"CENTER\">Student breakdown</H3>\n";
  $query = "SELECT year FROM id, students " .
           "WHERE = 
*/

  echo "<FORM ACTION=\"$PHP_SELF\" METHOD=\"POST\"><P>" .
       "Show the last <INPUT NAME=\"lastin\" VALUE=\"$lastin\" SIZE=\"3\"> visitors in " .
       "each category. <INPUT NAME=\"Submit\" TYPE=\"Submit\"></P></FORM>\n";
}

function log_view_form()
{
  global $logname;
  global $search;
  global $lines;
  global $PHP_SELF;

  echo "<FORM METHOD=\"POST\" ACTION=\"$PHP_SELF\">\n";
  echo "Log File <SELECT NAME=\"logname\">";
  echo "<OPTION ";
  if($logname == "access") echo "SELECTED ";
  echo "VALUE=\"access\">General access</OPTION>\n";
  echo "<OPTION ";
  if($logname == "admin") echo "SELECTED ";
  echo "VALUE=\"admin\">Administration access</OPTION>\n";
  echo "<OPTION ";
  if($logname == "security") echo "SELECTED ";
  echo "VALUE=\"security\">Possible security problems</OPTION>\n";
  echo "<OPTION ";
  if($logname == "debug") echo "SELECTED ";
  echo "VALUE=\"debug\">Debugging</OPTION>\n";
  echo "</SELECT>\n ";
  echo "  Search <INPUT NAME=\"search\" VALUE=\"$search\" SIZE=\"10\">\n";
  echo "  Lines <INPUT NAME=\"lines\" VALUE=\"$lines\" SIZE=\"2\">\n";
  echo "  <INPUT TYPE=\"SUBMIT\" VALUE=\"Submit\">\n";
  echo "</FORM>\n";
}


function log_view($logname, $search, $lines)
{
  global $conf;

  $logfile = "";
  switch($logname)
  {
    case "access" :
      $logfile = $conf['logs']['access']['file'];
      break;
    case "admin" :
      $logfile = $conf['logs']['admin']['file'];
      break;
    case "security" :
      $logfile = $conf['logs']['security']['file'];
      break;
    case "debug" :
      $logfile = $conf['logs']['debug']['file'];
      break;
  }

  if(empty($logfile))
    die_gracefully("Invalid log file");

  echo "<H3>" . htmlspecialchars($logname) . "</H3>";

  if(empty($search)) $command = "cat ";
  else $command = "grep \"$search\" ";

  $command .= $logfile;

  if(!empty($lines)) $command .= " | tail -n $lines";

//  echo $command;
  $handle = popen($command, "r");
  if(!$handle){
    echo "<H3>Could not open log file</H3>";
  }
  else{
    echo "<P>";
    echo "<TABLE>";
    $odd = TRUE;
    while(!feof($handle)){
      echo "<TR><TD";
      if(!$odd) echo " BGCOLOR=\"#C4C4FF\"";
      echo "><TT>";
      echo htmlspecialchars(fgets($handle)) . "<BR>\n";
      echo "</TT></TD></TR>\n";
      if($odd) $odd=FALSE; else $odd=TRUE;
    }
    echo "</TABLE>";
    echo "</P>";

  }


}

$page->end();

?>


