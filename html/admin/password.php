<?php 

/******************************************************************************

	Name : Andrew Hunter
	Date : 13th February 2001
	Program : This is the edit page for the CV system.

*****************************************************************************/ 
  
// The include files 
include('common.php');		
include('authenticate.php');	

// Connect to the database on the server
db_connect()
  or die("Unable to connect to server");
  
// Authenticate user so that the right people see the right thing
auth_user("user");

$smarty->assign("section", "pms");
if(is_student())
{
  $legacy_page['help_page'] = "change_password";
}
    
$page = new HTMLOPUS("Change Password");	// Calls the function for the header

define(DISPLAY_PASSWORDPROMPT, 0);
define(CHANGE_PASSWORD, 1);
  
// The default action is to display the form
if(empty ($mode)){
  $mode = DISPLAY_PASSWORDPROMPT;
}

// If the user is not root they cannot alter the
// password for any other user, of course ;-).
if(!is_root() || empty($user_id)){
  $user_id = $_SESSION['user']['id'];
}


// Call the right function for the right mode
switch($mode)
{
  case DISPLAY_PASSWORDPROMPT:
    display_passwordprompt();
    break;
  case CHANGE_PASSWORD:
    change_password();
    break;
}


  // Print out the help column on rigth hand side
  right_column("cdetails");

  // Print the footer and finish the page
  $page->end();			


/*
**	display_passwordprompt
**
** This function displays the form for the password
** change on the screen.
**
*/
function display_passwordprompt()
{
  // the  global variables needed are the script name
  // and the user_id passed to the script.
  global $PHP_SELF;
  global $user_id;

  // First, get the user and name
  $query = sprintf("SELECT username, real_name FROM id WHERE id_number=%s",
    $user_id);
 
  // Pass the query to MySQL
  $result = mysql_query($query)
    or print_mysql_error("Unable to execute query");

  $row = mysql_fetch_row($result)
    or die_gracefully("No such user.");

  print("<H2 ALIGN=\"CENTER\">Change Password</H2>\n");
  printf("<H3 ALIGN=\"CENTER\">for %s (%s)</H3>\n",
    htmlspecialchars($row[0]), htmlspecialchars($row[1]));

  printf("<FORM METHOD=\"post\" ACTION=\"%s?mode=%d&user_id=%s\">\n",
    $PHP_SELF, CHANGE_PASSWORD, $user_id);
    
  printf("<TABLE ALIGN=\"CENTER\">");  
 
  printf("<TR><TD>Password</TD><TD><INPUT TYPE=password SIZE=\"20\" NAME=\"pass\"></TD></TR>\n");
  printf("<TR><TD>Confirm password</TD><TD><INPUT TYPE=password SIZE=\"20\" NAME=\"confpass\"></TD></TR>\n");
  
  printf("<TR><TD></TD><TD><INPUT TYPE=\"submit\" NAME=\"button\" VALUE=\"Submit\">");
  printf(" <INPUT TYPE=\"reset\" VALUE=\"Reset\"><TD></TR>\n");
  printf("</TABLE>\n");
  printf("</FORM>\n"); 

}


/*
**	change_password
**
**
*/
function change_password()
{
  // The id of the user to change the password of,
  // a reference to the script name and the password
  // and confirmation password.
  global $PHP_SELF, $user_id;
  global $pass, $confpass;


  print("<H2 ALIGN=\"CENTER\">Password Change</H2>\n");

  if(empty($user_id))
    die_gracefully("This page should not be accessed without a user ID.\n");

  if($pass != $confpass){
    print("<P ALIGN=\"CENTER\">The passwords you entered ");
    print("do not match, please press the back button and ");
    print("try again.</P>\n");
    $page->end();
    exit;
  }

  if(empty($pass)){
    print("<P ALIGN=\"CENTER\">The passwords you entered ");
    print("are empty, please press the back button and ");
    print("try again.</P>\n");
    $page->end();
    exit;
  }

  $formedpass = sprintf("'%s'", MD5($pass));

  // Form the query to update the user password
  $query = sprintf("UPDATE id SET password = %s WHERE id_number=%s",
    $formedpass, $user_id);

  // Attempt to pass the query to MySQL  
  $result = mysql_query($query)
     or print_mysql_error("Unable to execute query");

  print("<H2 ALIGN=\"CENTER\">Password updated.</H2>");
}

  
?>
