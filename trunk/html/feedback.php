<?php 

/**
**	feedback.php
**
** This allows various people related to the site to be 
** contacted.
**
** Initial coding : Colin Turner
*/

// Include some common functions 
include('common.php');
include('authenticate.php');	
include('lookup.php');

// Connect to the database on the server
db_connect()
  or die("Unable to connect to server");

auth_user("user");

page_header("Feedback"); // Calls the function for the header

print_menu("");

output_help("Feedback");

report_form();

$page->end();


$components = array();
$components['all']      = array();
$components['students'] = array();
$components['staff']    = array();
$components['company']  = array();
$components['admin']    = array();
$components['root']     = array();

function report_form()
{
  global $PHP_SELF;

  echo "<FORM METHOD=\"POST\" ACTION=\"$PHP_SELF\">\n";
  echo "<TABLE ALIGN=\"CENTER\" BORDER=\"1\">\n";
  echo "<TR><TH>User type</TH><TD>" . 
       htmlspecialchars($_SESSION['user']['is']) . "</TD></TR>\n";
  echo "<TR><TH>User name</TH><TD>" .
       htmlspecialchars(get_user_name($_SESSION['user']['id'])) . "</TD></TR>\n";

  echo "<TR><TH>Report type</TH><TD>";
  echo "<SELECT>\n" .
       "<OPTION VALUE=\"0\">-- Please Select --</OPTION>\n" .
       "<OPTION VALUE=\"1\">General Comment</OPTION>\n" .
       "<OPTION VALUE=\"2\">Information Request</OPTION>\n" .
       "<OPTION VALUE=\"3\">Bug Report</OPTION>\n" .
       "<OPTION VALUE=\"4\">Wish List</OPTION>\n" .
       "</SELECT></TD></TR>\n";
  echo "<TR><TH>Component</TH><TD>" .
       "<SELECT>\n" .
       "<OPTION VALUE=\"0\">General</OPTION>\n" .
       "<OPTION VALUE=\"1\">Online CV System</OPTION>\n" .
       "<OPTION VALUE=\"2\">Online CV Templates</OPTION>\n" .
       "<OPTION VALUE=\"3\">Company Browser</OPTION>\n";

  echo "</SELECT>\n";
  echo "</TD></TR><TH>Importance</TH><TD>";
  echo "<SELECT>\n" .
       "<OPTION VALUE=\"0\">-- Please Select --</OPTION>\n" .
       "<OPTION VALUE=\"1\">Minor</OPTION>\n" .
       "<OPTION VALUE=\"2\">Normal</OPTION>\n" .
       "<OPTION VALUE=\"3\">Grave</OPTION>\n" .
       "<OPTION VALUE=\"4\">Critical</OPTION>\n" .
       "</SELECT>\n</TD></TR>\n";

  echo "<TR><TH COLSPAN=\"2\">Text</TH></TR>\n";
  echo "<TR><TD COLSPAN=\"2\"><TEXTAREA ROWS=\"20\" COLS=\"60\">" .
       "</TEXTAREA>\n</TD></TR>\n";
 

  echo "</FORM>\n";

}
?>


