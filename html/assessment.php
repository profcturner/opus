<?php

/**
**	assessment.php
**
** Initial coding : Colin Turner
**
*/

// The include files
include('common.php');
include('authenticate.php');
include('assessment.php');
include('lookup.php');

// Connect to the database on the server
db_connect()
  or die("Unable to connect to server");

// Authenticate user so that the right people see the right thing
auth_user("student");

if(is_admin() && !empty($student_id) && !is_auth_for_student($student_id, "student", "viewStatus"))
  print_auth_failure("ACCESS");


$smarty->assign("section", "pms");
$page['help_page'] = "assessment";

$page = new HTMLOPUS('Assessment', 'mycareer', 'assessment');  

if(is_admin() && empty($student_id))
{
  printf("<H2 ALIGN=\"CENTER\">Error</H2>\n");
  printf("<P ALIGN=\"CENTER\">");
  printf("Try the <A HREF=\"%s\">Student Directory</A> first.</P>\n",
         $conf['scripts']['admin']['studentdir']);
  die_gracefully("You cannot access this page without a student id.");
}

if(!is_student() && !is_admin())
  die_gracefully("You do not have permission to access this page.");

if(is_student()) $student_id = get_id();

assessment_regime($student_id);

// Print out the help column on rigth hand side
//right_column("StudentCVView");

// Print the footer and finish the page
$page->end();

?>














