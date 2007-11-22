<?php

/**
**	cover.php
**
** This script allows admin and company access to cover letters.
**
** Initial coding : Colin Turner
*/

// The include files
include('common.php');
include('authenticate.php');
include('lookup.php');

// Connect to the database on the server
db_connect()
  or die("Unable to connect to server");

// Authenticate user so that the right people see the right thing
auth_user("user");

if(!(is_admin() || is_staff() || is_company()))
  print_auth_failure("ACCESS");

if(is_staff() && !is_course_director())
  print_auth_failure("ACCESS");

// Admin or staff user security
if(is_admin() || is_staff())
{
  if(empty($student_id))
  {
    $page = new HTMLOPUS("CV in PDF form");
    printf("<H2 ALIGN=\"CENTER\">Error</H2>\n");
    printf("<P ALIGN=\"CENTER\">");
    printf("Try the <A HREF=\"%s\">Student Directory</A> first.</P>\n",
           $conf['scripts']['admin']['studentdir']);
    die_gracefully("You cannot access this page without a student id.");
  }
  else
  {
    // Is this user authorised?
    if(!is_auth_for_student($student_id, "student", "viewCV"))
      print_auth_failure("ACCESS");
  }
}


if(is_company()){
  $contact_id = get_contact_id(get_id());

  if(empty($student_id) || empty($contact_id))
  {
    die_gracefully("You cannot access this page without a student id.");
  }

  // Only grant permission if this student has requested the company
  $query = "SELECT companycontact.* FROM companycontact, companystudent " .
           "WHERE companycontact.company_id = companystudent.company_id " .
           "AND companycontact.contact_id = " . $contact_id .
           " AND companystudent.student_id = " . $student_id;
  $result = mysql_query($query)
    or print_mysql_error2("Unable to authenticate company.", $query);

  if(!mysql_num_rows($result))
  {
    die_gracefully("You do not have permission to access this page.");
  }
  mysql_free_result($result);

  // Check the student's placement status if possible to make sure of things
  $status = get_student_status($student_id);
  if($status != "Required"){
    if($status != "Placed"){
      // Student is no longer eligible for placement in one way
      // or another...
      die_gracefully("This student is no longer available on the placement system.");
    }
    else{
      // Ok so the student is placed, is this a contact for the
      // lucky company?
      $query = "SELECT companycontact.* FROM companycontact, placement " .
               "WHERE companycontact.company_id = placement.company_id " .
               "AND companycontact.contact_id = " . $contact_id .
               " AND placement.student_id = " . $student_id;
      $result = mysql_query($query)
        or print_mysql_error2("Unable to authenticate company for placed student.", $query);
      if(!mysql_num_rows($result))
      {
        die_gracefully("Sorry, but this student is now placed with another company.");
      }
      mysql_free_result($result);
    }
  }
}

$page = new HTMLOPUS("Cover Letter");

$sql = "SELECT cover FROM companystudent WHERE company_id=$company_id " .
       "AND student_id=$student_id";
$result = mysql_query($sql)
  or print_mysql_error2("Unable to obtain cover letter", $sql);
$row = mysql_fetch_row($result);

mysql_free_result($result);

if(empty($row[0])) die_gracefully("There is no cover letter for this application.");
else
{
  echo "<H2 ALIGN=\"CENTER\">" . htmlspecialchars(get_company_name($company_id)) . "</H2>\n";
  echo "<H2 ALIGN=\"CENTER\">Cover letter from " . htmlspecialchars(get_user_name($student_id)) . "</H2>\n";
  $letter = htmlspecialchars($row[0]);
  echo "<P>" . preg_replace("/\n/", "<BR/>\n", $letter) . "</P>\n";
}

$page->end();

?>