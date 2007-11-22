<?php

/**
**  index.php
**
** This is the index page for authenticated members of staff.
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
auth_user("staff");

$page = new HTMLOPUS("Staff Index", "home");	// Calls the function for the header

if(!is_admin() && !is_staff()){
  die_gracefully("You do not have permission to access this page.");
}

if(!is_admin() || empty($student_id)) $staff_id = get_id();

echo "<H2 ALIGN=\"CENTER\">Welcome " .
     get_user_name($staff_id) . "</H2>";

output_help("StaffHome");

$page->end();

?>