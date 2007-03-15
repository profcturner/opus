<?php

/**
**	pdp_cv.php
**
** This script fetched a CV from the PDP system.
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

if(!(is_admin() || is_student() || is_staff() || is_company()))
  print_auth_failure("ACCESS");

if(is_staff() && !is_course_director())
  print_auth_failure("ACCESS");

// Admin or staff user security
if(is_admin() || is_staff())
{
  if(empty($student_id))
  {
    page_header("CV in PDF form");
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

  if(empty($student_id) || empty($contact_id)){
    page_header("Error");
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
    page_header("Error");
    die_gracefully("You do not have permission to access this page.");
  }
  mysql_free_result($result);

  // Check the student's placement status if possible to make sure of things
  $status = get_student_status($student_id);
  if($status != "Required"){
    if($status != "Placed"){
      // Student is no longer eligible for placement in one way
      // or another...
      page_header("Error");
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
        page_header("Error");
        die_gracefully("Sorry, but this student is now placed with another company.");
      }
      mysql_free_result($result);
    }
  }

  // Still here? Update the stats in companystudent to say when things were
  // last viewed. Update for every company the contact acts for (normally 1).
  $query = "SELECT company_id FROM companycontact WHERE contact_id=$contact_id";
  $result = mysql_query($query);
  while($row = mysql_fetch_array($result))
  {
    $squery = "UPDATE companystudent SET status='seen' " .
              "WHERE company_id=" . $row["company_id"] .
              " AND student_id=$student_id AND status='unseen'";
    mysql_query($squery)
      or print_mysql_error2("Unable to update last seen timestamp", $squery);

    $squery = "UPDATE companystudent SET lastseen=" .
              make_null(date("YmdHis")) .
              " WHERE company_id=" . $row["company_id"] .
              " AND student_id=$student_id";
    mysql_query($squery)
      or print_mysql_error2("Unable to update last seen timestamp", $squery);
  }
  mysql_free_result($result);

}

// Students can ONLY view their own CV
if(is_student()) $student_id = get_id();

// Fetch the username
$student_reg = get_login_name($student_id);

// And the real name
$student_name = get_user_name($student_id);

// Eek, hack!
if(empty($template_id)) $template_id=5;

// Is this an archive CV? Here is how we will know
$vacancy_id = $_REQUEST['vacancy_id'];

if(!empty($vacancy_id))
{
  $data = fetch_archive_cv($student_id, $vacancy_id);
  $mime_type = $data[0];
  $file = $data[1];
  header("Content-type: $mime_type");
  $extension = "";

  // Fetch information on the mime type from the database
  $sql = "select * from mime_types where type=" . make_null($mime_type);
  $result = mysql_query($sql)
    or print_mysql_error2("Unable to fetch mime type information", $sql);
  $mime_info = mysql_fetch_array($result);
  // Get the allowable extensions
  $extensions = explode(" ", $mime_info['extensions']);
  // Use the first one
  $extension = $extensions[0];
  mysql_free_result($result);

}
else
{
  $file = fetch_cv($student_reg, $template_id);
  header("Content-type: application/pdf");
  $extension = "pdf";
}

$len = strlen($file);

$filename = $student_reg . "." . $extension;


header("Content-Length: $len");
header("Content-Disposition: inline; filename=\"$filename\"");
print $file;
$log['access']->LogPrint("CV for student $student_reg fetched ($filename)");


/**
* fetches an archive (custom) CV from the PDSystem
*
*/ 
function fetch_archive_cv($student_id, $vacancy_id)
{
  global $conf;
  global $log;
  global $student_name;

  // Get the hash
  $sql = "select archive_hash, archive_mime_type from companystudent where student_id=$student_id and vacancy_id=$vacancy_id";
  $result = mysql_query($sql)
    or print_mysql_error2("Unable to fetch hash", $sql);
  $row = mysql_fetch_row($result);
  $hash = $row[0];
  $mime_type = $row[1];
  mysql_free_result($result);

  $url = $conf['pdp']['host'] . "/pdp/controller.php?" .
    "function=open_artifact&hash=$hash&" .
    "username=" . $conf['pdp']['user'] . "&password=" . $conf['pdp']['pass'];

  $log['security']->LogPrint("Fetching file $url");
  $file = @file_get_contents($url);

  if($file == FALSE)
  {
    page_header("Error");
    print_menu("");
    $log['debug']->LogPrint("Unable to fetch CV for $student_name ($student_id) FROM PDS, is it running?");
    die_gracefully("The PMS was unable to acquire the CV from the PDP system.");
  }
 
  // Hmm, hard to do sanity checking here :-(
  
  $log['access']->LogPrint("CV for student $student_name ($student_id) fetched from PDP System");

  return array($mime_type, $file);
}



function fetch_cv($student_id, $template_id)
{
  global $conf;
  global $log;
  global $student_name;

  $url = $conf['pdp']['host'] . "/pdp/controller.php?" .
    "function=get_pdf_cv&template_id=$template_id" .
    "&reg_number=$student_id&" .
    "username=" . $conf['pdp']['user'] . "&password=" . $conf['pdp']['pass'];

  $log['security']->LogPrint("Fetching file $url");
  $file = @file_get_contents($url);

  if($file == FALSE)
  {
    page_header("Error");
    print_menu("");
    $log['debug']->LogPrint("Unable to fetch CV for $student_name ($student_id) FROM PDS, is it running?");
    die_gracefully("The PMS was unable to acquire the CV from the PDP system.");
  }
 
  if(substr($file, 0, 4) !=  "%PDF")
  {
    page_header("Error");
    print_menu("");
    output_help("PDSCVFetchFailure");
    $log['debug']->LogPrint("Unable to fetch valid CV for $student_name ($student_id) FROM PDS, is it closed?");
    die_gracefully("The PMS was unable to acquire a valid CV from the PDP system.<BR>It may be that a central University System is offline.");
  }

  $log['access']->LogPrint("CV for student $student_name ($student_id) fetched from PDP System");

  return $file;
}


?>