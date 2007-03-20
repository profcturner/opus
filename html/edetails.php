<?php 

/**
**	edit_edtails.php
**
** This student script allows the student to edit his or her
** educational history.
**
** Initial coding : Andrew Hunter
**
** Modified for new back end code by Colin Turner
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
auth_user("student");

if(!is_admin() && !is_student()){
  die_gracefully("You do not have permission to access this page.");
}

if(is_admin())
{
  if(empty($student_id)){
    printf("<H2 ALIGN=\"CENTER\">Error</H2>\n");
    printf("<P ALIGN=\"CENTER\">");
    printf("Try the <A HREF=\"%s\">Student Directory</A> first.</P>\n",
           $conf['scripts']['admin']['studentdir']);
    die_gracefully("You cannot access this page without a student id.");
  }
}

if(is_student()){
  $student_id = get_id();
}

page_header("Educational History"); // Calls the function for the header
print_menu("student");		    // Print the menu for the student user
  
// The default mode for the global variable
if(empty ($mode)) $mode = DISPLAY_EDETAILS;

// Getting into the right mode for the right job
switch($mode)
{

  case DISPLAY_EDETAILS;
    display_edetails();
    break;

  case ADD_SCHOOL;
    add_school();
    break;

  case DELETE_RECORD;
    delete_record();
    break;

  case EDIT_RESULTS;
    edit_results();
    break;

  case ADD_RESULT;
    add_result();
    break;

  case DELETE_RESULT;
    delete_result();
    break;

  case UPDATE_EDETAILS;
    update_edetails();
    break;
 
}

// Print the footer and finish the page
$page->end();


//
// Start of the display function: this function formats and displays the 
// information appropiately 
//

function display_edetails()	
{
  global $PHP_SELF;
  global $student_id;
  global $log; // Access to logging

  if(is_admin() && !is_auth_for_student($student_id, "student", "viewCV"))
    die_gracefully("You are not permitted to view this student\'s CV");

  // Run a query on the database for the lines of matching information.
  $query = "SELECT * FROM cv_edetails WHERE id=$student_id";

  // Run this by the server
  $result = mysql_query($query)
    or print_mysql_error2("Error fetching educational records.", $query);

  printf("<FONT FACE=\"Arial, Helvetica, sans-serif\" SIZE=\"2\">");

  // Displaying the results in the form of a table
  printf("<H2 ALIGN=\"CENTER\">Education Details</H2>\n");
  printf("<TABLE ALIGN=\"CENTER\" VALIGN=\"top\" CELLSPACING=\"0\"
          CELLPADDING=\"1\">\n");

  // Ok now get the row of results after the query
  while($row = mysql_fetch_row($result))
  {

    printf("<TR>\n");
    printf("<TH>Place</TH>\n");
    printf("<TD>%s</TD>\n", $row[1]);
    printf("</TR>\n"); 
    printf("<TR>\n");
    printf("<TH>Year</TH>\n");
    printf("<TD>%s</TD>\n", $row[2]);
    printf("</TR>\n");  
    printf("<TR>\n");
    printf("<TH>Level</TH>\n");
    printf("<TD>%s</TD>\n", $row[3]);
    printf("</TR>\n");
    if($row[4] != NULL)
    {
      
      printf("<TR>\n");
      printf("<TH>Course</TH>\n");
      printf("<TD>%s</TD>\n", $row[4]);
      printf("</TR>\n");

    }
    printf("<TR>\n");
    printf("<TD></TD>\n");
    printf("<TD>\n");
    printf("<A HREF=\"%s?mode=EDIT_RESULTS&link_no=%s&student_id=%s\">", $PHP_SELF, $row[5], $student_id);
    printf("[ Edit results ]</A> ");
    printf("<A HREF=\"%s?mode=DELETE_RECORD&link_no=%s&student_id=%s\">", $PHP_SELF, $row[5], $student_id);
    printf("[ Delete Record ]</A>");
    printf("</TD></TR>\n");
    printf("<TR><TD COLSPAN=\"2\"><HR></TD></TR>\n");
  } 

  printf("</TABLE>\n");
  printf("</FONT>\n");
  printf("<BR><HR><BR>");
  
  printf("<CENTER>Add a new School or College</CENTER>");  

  printf("<FORM METHOD=\"post\" ACTION=\"%s?mode=%s&student_id=%s\">\n",
            $PHP_SELF, ADD_SCHOOL, $student_id);

  printf("<TABLE ALIGN=\"CENTER\">");
  printf("<TR><TD>Place</TD><TD>");
  printf("<INPUT TYPE=\"TEXT\" SIZE=\"40\" VALUE=\"\" NAME=\"place\"></TD></TR>\n");
  printf("<TR><TD>Year</TD><TD>");
  printf("<INPUT TYPE=\"TEXT\" SIZE=\"5\" VALUE=\"\" NAME=\"year\">( e.g. 90, 01, etc)  </TD></TR>\n");
  printf("<TR><TD>Level</TD><TD>");
  printf("<INPUT TYPE=\"TEXT\" SIZE=\"10\" VALUE=\"\" NAME=\"level\">(e.g. BTEC, GCSE, etc)  </TD></TR>\n");
  printf("<TR><TD>Course (if aplicable)</TD><TD>");
  printf("<INPUT TYPE=\"TEXT\" SIZE=\"40\" VALUE=\"\" NAME=\"course\"></TD></TR>\n");
  printf("<TR><TD></TD><TD><INPUT TYPE=\"submit\" NAME=\"button\" VALUE=\"Update\">");
  printf("<INPUT TYPE=\"reset\" VALUE=\"Reset\">");
  printf("</TD></TR>");
  printf("</TABLE>\n");
  printf("</FORM>\n");

  right_column("StudentCVEducation");

  $log['access']->LogPrint("Education details displayed.");
  
}  


function add_school()
{
  global $PHP_SELF;
  global $student_id;
  global $place, $year, $level, $course;
  global $log;

  if(is_admin() && !is_auth_for_student($student_id, "student", "editCV"))
    die_gracefully("You are not permitted to edit this student\'s CV");

  // Build the query for the update
  $query = sprintf("INSERT INTO cv_edetails (id, place, year, level, course)
    VALUES (%s, %s, %s, %s, %s)",
    $student_id,
    make_null($place),
    make_null($year),        
    make_null($level),
    make_null($course));

  // Try the query 
  $result = mysql_query($query)
    or die_gracefully(mysql_error());

  printf("<P ALIGN=\"CENTER\">Changes have been accepted</P>");

  $log['access']->LogPrint("New educational event ($place, $year) added for student $student_id");
  // Display the edetails 
  display_edetails();  
}

   
function delete_record()
{
  global $link_no;
  global $student_id;

  // Does the logged in user have permission to do this?
  if(is_student()){
    $query = "SELECT * FROM cv_edetails WHERE link_no=$link_no " .
             "AND id = $student_id";
    $result = mysql_query($query)
      or print_mysql_error2("Unable to verify permissions.", $query);
    if(!mysql_num_rows($result))
      die_gracefully("You do not have permission to delete this record.");
    mysql_free_result($result);
  }

  if(is_admin() && !check_default_policy($student_id, "student", "editCV"))
    die_gracefully("You are not permitted to edit this CV");
    
  // Run a query on the database for the lines of matching information.
  $query = sprintf("DELETE FROM cv_edetails WHERE link_no=%s", $link_no);

  // Run this by the server
  $result = mysql_query($query)
    or print_mysql_error2("Unable to delete link.", $query);
  
  display_edetails();
} 


function edit_results()
{
  global $student_id;
  global $link_no;
  global $PHP_SELF;
  global $Subject, $Grade;

  // Does the logged in user have permission to do this?
  if(is_student()){
    $query = "SELECT * FROM cv_edetails WHERE link_no=$link_no " .
             "AND id = $student_id";
    $result = mysql_query($query)
      or print_mysql_error2("Unable to verify permissions.", $query);
    if(!mysql_num_rows($result))
      die_gracefully("You do not have permission to delete this record.");
    mysql_free_result($result);
  }
  
  // Run a query on the database for the lines of matching information.
  $query = sprintf("SELECT * FROM cv_results WHERE link='%d'", $link_no);

  // Run this by the server
  $result = mysql_query($query)
    or die_gracefully(mysql_error());


  printf("<FONT FACE=\"Arial, Helvetica, sans-serif\" SIZE=\"2\">");

  // Displaying the results in the form of a table
  printf("<H2 ALIGN=\"CENTER\">Results</H2>\n");
  printf("<TABLE ALIGN=\"CENTER\" VALIGN=\"top\" CELLSPACING=\"0\"
          CELLPADDING=\"1\">\n");

  printf("<TR><TH>Subject</TH><TH>Grade</TH><TH>Options</TH></TR>\n");
  // Ok now get the row of results after the query
  while($row = mysql_fetch_row($result))
  {

    printf("<TR>\n");
    printf("<TD>%s</TD>\n", $row[1]);
    printf("<TD>%s</TD>\n", $row[2]);
    printf("<TD>\n");
    printf("<A HREF=\"%s?mode=DELETE_RESULT&subject=%s&grade=%s&link_no=%s&student_id=%s\">",
            $PHP_SELF, MD5($row[1]), MD5($row[2]), $row[0], $student_id);
    printf("[ Delete ]</A>\n</TD>");
    printf("</TR>\n");#

  }

  
  printf("</TABLE>\n");
  printf("</FONT>\n");
  printf("<BR><HR><BR>");

    printf("<CENTER>Add a new Result</CENTER>");

  printf("<FORM METHOD=\"post\" ACTION=\"%s?mode=%s&link_no=%s\">",
            $PHP_SELF, ADD_RESULT, $link_no);

  printf("<TABLE ALIGN=\"CENTER\">");
  printf("<TR><TD>Subject</TD><TD>");
  printf("<INPUT TYPE=\"TEXT\" SIZE=\"25\" VALUE=\"\" NAME=\"subject\">
            </TD></TR>\n");
  printf("<TR><TD>Grade</TD><TD>");
  printf("<INPUT TYPE=\"TEXT\" SIZE=\"10\" VALUE=\"\" NAME=\"grade\">
          </TD></TR>\n");
  echo "<INPUT TYPE=\"HIDDEN\" NAME=\"student_id\" VALUE=\"$student_id\"\n";
  printf("<TR><TD></TD><TD><INPUT TYPE=\"submit\" NAME=\"button\"
            VALUE=\"Update\">");
  printf("<INPUT TYPE=\"reset\" VALUE=\"Reset\">");
  printf("</TD></TR>");
  printf("</TABLE>\n");
  printf("</FORM>\n");

  right_column("StudentCVEducationResults");

}


function add_result()
{
  global $student_id;
  global $PHP_SELF, $link_no;
  global $subject, $grade;

  // Does the logged in user have permission to do this?
  if(is_student()){
    $query = "SELECT * FROM cv_edetails WHERE link_no=$link_no " .
             "AND id = $student_id";
    $result = mysql_query($query)
      or print_mysql_error2("Unable to verify permissions.", $query);
    if(!mysql_num_rows($result))
      die_gracefully("You do not have permission to delete this record.");
    mysql_free_result($result);
  }

  if(is_admin() && !is_auth_for_student($student_id, "student", "editCV"))
    die_gracefully("You are not permitted to edit this student\'s CV");

  // Build the query for the update
  $query = sprintf("INSERT INTO cv_results (link, subject, grade)
    VALUES (%s, %s, %s)",
    $link_no,
    make_null($subject),
    make_null($grade));

    // Try the query
  $result = mysql_query($query)
    or die_gracefully(mysql_error());

  printf("<P ALIGN=\"CENTER\">Changes have been accepted</P>");

  // Display the result
  edit_results();
}


function delete_result()
{
  global $student_id;
  global $link_no, $subject, $grade;

  // Does the logged in user have permission to do this?
  if(is_student()){
    $query = "SELECT * FROM cv_edetails WHERE link_no=$link_no " .
             "AND id = $student_id";
    $result = mysql_query($query)
      or print_mysql_error2("Unable to verify permissions.", $query);
    if(!mysql_num_rows($result))
      die_gracefully("You do not have permission to delete this record.");
    mysql_free_result($result);
  }

  if(is_admin() && !is_auth_for_student($student_id, "student", "editCV"))
    die_gracefully("You are not permitted to edit this student\'s CV");
  
  // Run a query on the database for the lines of matching information.
  $query = sprintf("DELETE FROM cv_results WHERE link=%s AND MD5(subject)='%s'
                    AND MD5(grade)='%s'", $link_no, $subject, $grade);

  // Run this by the server
  $result = mysql_query($query)
    or print_mysql_error2("Unable to delete inividual result", $query);

  edit_results();

}  
?>




