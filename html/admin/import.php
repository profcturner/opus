<?php

/**
**	import.php
**
** This script imports student users into the database given
** data from the University CSV files.
**
** Initial coding : Colin Turner
**
*/

// The include files
include('common.php');
include('authenticate.php');
include('lookup.php');

include('WebServices.php');

// Connect to the database on the server
db_connect()
  or die("Unable to connect to server");

// Authenticate user so that the right people see the right thing
auth_user("admin");

$page = new HTMLOPUS("Import Data", "configuration");     // Calls the function for the header

if(empty($mode)) $mode = ShowForm;

switch($mode)
{
  case ShowForm:
    import_form();
    break;
  case Students_Import:
    Students_Import();
    break;
  default:
    die_gracefully("Invalid Mode");
    break;
}

// Print the footer and finish the page
$page->end();

function Students_Import()
{
  if($_FILES['userfile']['error'] == UPLOAD_ERR_NO_FILE)
  {
    // No File, try a SRS import
    Students_Import_SRS();
  }
  else
  {
    process_upload();
    import_CSV($_FILES['userfile']['tmp_name']);
    unlink($_FILES['userfile']['tmp_name']);
  }
}


function Students_Import_SRS()
{
  global $smarty;
  global $log;  // Access to logging

  $password = $_REQUEST['password'];
  $course_code = $_REQUEST['course_code'];
  $year = $_REQUEST['year'];
  $status = $_REQUEST['status'];
  $test = $_REQUEST['test'];
  $onlyyear = $_REQUEST['onlyyear'];

  $course_id = backend_lookup('courses', 'course_id', 'course_code', make_null($course_code));
  $course_name = get_course_name($course_id);
  if(empty($course_id)) die_gracefully("Unknown course");
  if(empty($onlyyear)) die_gracefully("You must specify the import year in this method");

  // Oddly, for 06/07 the webservice uses 07, not 06!
  $course_xml = WebServices::get_course($course_code, substr(get_academic_year()+1, 2), $onlyyear);
  $students = array();
  foreach($course_xml->students->student as $student)
  {
    $student_array = Student_Import_SRS($student->reg_number);
    // Are they already present?
    if(backend_lookup("id", "id_number", "username", make_null($student->reg_number)))
    {
      // Already exists
      $student_array['result'] = "Exists";
    }
    else
    {
      if(!$test) Student_Insert($student_array, $course_id, $status, $year);
      $student_array['result'] = "Added";
    }
    
    
    array_push($students, $student_array);
  }
  $smarty->assign("test", $test);
  $smarty->assign("course_name", $course_name);
  $smarty->assign("students", $students);
  $smarty->display("admin/import/students_import_srs.tpl");
}

function Student_Import_SRS($reg_number)
{
  $student_xml = WebServices::get_student($reg_number);
 
  $student = array();
  $student['reg_number'] = $reg_number;
  $student['person_title'] = $student_xml->person_title;
  $student['first_name'] = $student_xml->first_name;
  $student['last_name'] =  $student_xml->last_name;
  $student['email_address'] = $student_xml->email_address;
  $student['disability_code'] = $student_xml->disability_code;
  return($student);
}

function Student_Insert($student, $course_id, $status, $year)
{
  $real_name = $student['person_title'] . " " . $student['first_name'] . " " . $student['last_name'];

  // Entry into id table - NULL password, no native login
  $sql = "insert into id (real_name, username, user) " .
    "values(" . make_null(addslashes($real_name)) . ", " .
    make_null($student['reg_number']) . ", 'student')";
  mysql_query($sql)
    or print_mysql_error2("Unable to update id table", $sql);

  // Fetch the allocated user id
  $student_id = mysql_insert_id();

  // Now update the students table...
  $query = "INSERT INTO students (user_id, year, status, disability_code) VALUES(" .
           "$student_id, $year, " . make_null($status) . "," . make_null($student['disability_code']) . ")";
  mysql_query($query)
    or print_mysql_error2("Unable to add student details to students table", $query);

  // Finally, legacy support, update the cv_pdetails
  $query = "INSERT INTO cv_pdetails (id, surname, firstname, title, email, course)" .
           " VALUES($student_id, " . 
           make_null(addslashes($student['last_name'])) . ", " .
           make_null(addslashes($student['first_name'])) . ", " .
           make_null(addslashes($student['person_title'])) . ", " .
           make_null(addslashes($student['email_address'])) . ", $course_id)";
  mysql_query($query)
    or print_mysql_error2("Unable to update cv_pdetails table", $query);


}

function import_form()
{
  global $PHP_SELF;

  if(!check_default_policy('import', 'students'))
    die_gracefully("You do not have permission for this action");

  printf("<H2 ALIGN=\"CENTER\">Import CSV Data</H2>\n");

  output_help("AdminImportCSV");

  echo "<FORM ENCTYPE=\"MULTIPART/FORM-DATA\" ACTION=\"" .
       $PHP_SELF . "?mode=Students_Import\" METHOD=\"POST\">\n";

  printf("<FORM METHOD=\"post\" ACTION=\"%s\">\n",
            $PHP_SELF);

  printf("<TABLE ALIGN=\"CENTER\">");

  printf("<TR><TD>Filename</TD><TD>");
  printf("<INPUT TYPE=\"FILE\" SIZE=\"30\" NAME=\"userfile\"></TD></TR>\n");

  echo "<TR><TD>Course</TD><TD>" .
       "<SELECT NAME=\"course_code\">\n";
  $coursequery = "SELECT course_id, course_code, course_name FROM courses ORDER BY course_code";
  $courseresults = mysql_query($coursequery)
    or print_mysql_error2("Unable to obtain courses.", $coursequery);
  while($courserow = mysql_fetch_array($courseresults))
  {
    echo "<OPTION VALUE=\"" . $courserow["course_code"] . "\">" .
         htmlspecialchars($courserow["course_code"] . ": " . $courserow["course_name"]) . "</OPTION>\n";
  }
  echo "</SELECT></TD></TR>\n";

  echo "<TR><TD>Only import year number</TD><TD>";
  echo "<INPUT TYPE=\"TEXT\" NAME=\"onlyyear\" VALUE=\"2\" SIZE=\"3\"></TD></TR>\n";
  
  echo "<TR><TD>Status</TD><TD>" .
       "<SELECT NAME=\"status\">" .
       "<OPTION>Required</OPTION>\n" .
       "<OPTION>Placed</OPTION>\n" .
       "<OPTION>Exempt Applied</OPTION>\n" .
       "<OPTION>Exempt Given</OPTION>\n" .
       "<OPTION>No Info</OPTION>\n" .
       "<OPTION>Left Course</OPTION>\n" .
       "<OPTION>Suspended</OPTION>\n" .
       "<OPTION>To final year</OPTION>\n</SELECT></TD></TR>\n";
 
  echo "<TR><TD>Password</TD><TD>" .
       "<INPUT TYPE=\"TEXT\" SIZE=\"10\" NAME=\"password\"></TD></TR>\n";

  echo "<TR><TD>For placement in year</TD><TD>" .
       "<INPUT TYPE=\"TEXT\" SIZE=\"4\" VALUE =\"" .
       (get_academic_year() + 1) . "\" NAME=\"year\"></TD></TR>\n";

  echo "<TR><TD>Test Only</TD><TD>" .
       "<INPUT TYPE=\"CHECKBOX\" NAME=\"test\" CHECKED> " .
       "(you must uncheck this to commit changes)</TD></TR>\n";

   printf("<TR><TD></TD><TD><INPUT TYPE=\"submit\" NAME=\"button\"
            VALUE=\"Update\">");
  printf("<INPUT TYPE=\"reset\" VALUE=\"Reset\">");
  printf("</TD></TR>\n");
  printf("</TABLE>\n");
  printf("</FORM>\n");
}


/**
**	process_upload()
**
** This function will fail and close the script if
** something went wrong with the upload procedure.
**
*/
function process_upload()
{
  // Check for various failures...
  switch($_FILES['userfile']['error'])
  {
    case UPLOAD_ERR_INI_SIZE:
    case UPLOAD_ERR_FORM_SIZE:
      die_gracefully("Sorry, your file is above permitted maximum size.");
      break;
    case UPLOAD_ERR_PARTIAL:
      // We need to delete the partial upload (security)
      unlink($_FILES['userfile']['tmp_name']);
      die_gracefully("Sorry, but your upload failed part way through");
      break;
    case UPLOAD_ERR_NO_FILE:
      die_gracefully("Sorry, but no file was received.");
      break;
  }
}

/**
**	validate_CSV
**
** This function takes an array read via fgetcsv() from a
** standard university CSV file and checks its integrity.
**
** Returns a boolean indicating validation success.
*/
function validate_CSV($line)
{
  // There must be a student_id
  $student_id = $line[1];

  if(empty($student_id)) return false;

  return true;
}

function guess_format($fp)
{
  // Attempt to fetch a line
  rewind($fp);
  $line = fgetcsv($fp, 2048);
  if(!$line) return(FALSE); // unknown format, read failed.
  // Put the pointer back where we found it!
  rewind($fp);

  // UU keep changing format without warning...
  // In module CSV the course code is in column 4
  // In course CSV this is attendance mode...

  //echo "Debug: test fragment " . $line[3];
  if(strlen($line[3]) == 6) return("ModuleFormat");
  else return("CourseFormat");
  // Count columns
  /*
  $count = count($line);
  switch($count)
  {
    case 9:
    return("ModuleFormat");
    break;
    case 8:
    return("CourseFormat");
    break;
    default:
    return(FALSE);
    break;
  }
  */
}
    
  

/**
**	import_CSV()
**
** This function actually performs the process
** of reading lines and adding users into the
** database.
**
*/
function import_CSV($filename)
{
  global $log;  // Access to logging
  global $password, $course_code, $year, $status;
  global $test;
  global $onlyyear;

  // Check the current user has permissions for this action
  if(!check_default_policy('import', 'students'))
    die_gracefully("You do not have permission for this action");

  // Make sure we test by default
  if(!empty($test)) $test = TRUE;
  else $test = FALSE;

  // Open the file for read access
  $fp = fopen($filename, "r");
  
  // Work out which format we are using
  $format = guess_format($fp);

  echo "<H2 ALIGN=\"CENTER\">Import Status</H2>\n";
  if($test)
  {
    echo "<H3 ALIGN=\"CENTER\">Test Results Only!</H3>\n";
    echo "<P ALIGN=\"CENTER\">This is a test run, if you are satisfied, please return to" .
         " the previous form and confirm your actions.</P>\n";
  }
  echo "<P ALIGN=\"CENTER\">File format seems to be $format.</P>\n";

  echo "<TABLE BORDER=\"1\" ALIGN=\"CENTER\">\n<TR>" .
       "<TH>Year</TH><TH>Student ID</TH><TH>Name</TH><TH>Course</TH><TH>Email</TH><TH>Status</TH></TR>\n";

  // Read rows from the CSV file
  while($line = fgetcsv ($fp, 2048, ",")){

    if(validate_CSV($line)){

      // Fetch data, they have changed the sodding format...      
      $yearcode  = $line[0];
      $student_id  = $line[1];
      $fullname = addslashes($line[2]);

      if($format=="ModuleFormat")
      {
        $email = $line[8];
        $course_code = $line[3];
      }
      else
      {
        $email = $line[7];
        if(empty($course_code))
          die_gracefully("You must define the course code for course level listings");
      }

      // Parse the fullname, in "SURNAME, INITIALS TITLE" format
      $namefrags = preg_split("/[\s,]+/", $fullname);
      $surname   = $namefrags[0];
      $initials  = $namefrags[1];
      $title     = $namefrags[2];           
      
      // Reassemble fullname in the right order...
      $fullname = sprintf("%s %s %s", $title, $initials, $surname);

      printf("<TR><TD>%s</TD><TD>%s</TD><TD>%s</TD><TD>%s</TD><TD>%s</TD>",
             $yearcode, $student_id, htmlspecialchars($fullname),
             htmlspecialchars($course_code),
             htmlspecialchars($email));

      if(!empty($onlyyear))
      {
        if($onlyyear != $yearcode)
        {
          echo "<TD>Bad year</TD>\n";
          continue;
        }
      }
      // Check the entry does not already exist
      $query = sprintf("SELECT * FROM id WHERE username=%s", make_null($student_id));
     
      $result = mysql_query($query)
        or print_mysql_error("Unable to query id table.");

      if(mysql_num_rows($result)){
        printf("<TD>Exists</TD>\n");
      }
      else
      {
        if(!($test))
        {
          //
          // id Table
          //
          $query = sprintf("INSERT INTO id (username, password, real_name, user)
                            VALUES(%s, MD5(%s), %s, 'student')",
                   make_null($student_id), make_null($password), make_null($fullname));

          mysql_query($query)
            or print_mysql_error("Unable to add student $student_id ($fullname)");

          // Fetch the id from the database just assigned.
          $database_id = mysql_insert_id();

          //
          // students Table
          //
          $query = "INSERT INTO students (user_id, year, status) " .
                   "VALUES($database_id, $year, " . make_null($status) . ")";
          mysql_query($query)
            or print_mysql_error2("Unable to create student record.");

          // Fetch the course_id if possible
          if(!empty($course_code)){
            $query = sprintf("SELECT course_id FROM courses WHERE course_code='%s'",
                             $course_code);

            $result2 = mysql_query($query)
              or print_mysql_error("Unable to fetch course id for $course_code");          

            if(mysql_num_rows($result2)){
              $row = mysql_fetch_row($result2);
  
              $course_id = $row[0];
              mysql_free_result($result2);
            }
            else $course_id=0;
          }
        

          //
          // cv_pdetails table
          //
          // Now use this to create an entry in cv_pdetails to start the
          // student off...
          $query = sprintf("INSERT INTO cv_pdetails (id, surname, firstname, 
                            title, student_id, email, course) VALUES(
                            %s, %s, '%s', %s, %s, '%s', %s)",
                            $database_id,
                            make_null($surname),
                            $initials,
                            make_null($title),
                            make_null($student_id),
                            $email,
                            $course_id);

          mysql_query($query)
            or print_mysql_error("Unable to add CV details for student $student_id ($fullname) $query");
          
        }
        printf("<TD>Added</TD>\n"); 
        $log['admin']->LogPrint("User added $student_id, $fullname (course $course_code)");
      }
      mysql_free_result($result);

    }
    else
    {
      printf("<TR><TD>??</TD><TD>??</TD><TD>??</TD><TD>Validation Error</TD></TR>\n");
    }
    
  }
  printf("</TABLE>\n");
}


?>