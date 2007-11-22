<?php

/**
**	odetails.php
**
** This student script allows editing of the miscellaneous other data
** for a CV.
**
** Initial coding : Andrew Hunter
**
** Modified by Colin Turner for the new backend.
*/
  
// The include files 
include('common.php');		
include('authenticate.php');	

// Connect to the database on the server
db_connect()
  or die("Unable to connect to server");
  
// Authenticate user so that the right people see the right thing
auth_user("student");
    
page_header("Other CV Details");	// Calls the function for the header
print_menu("student");			// Print the menu for the student user

if(!is_admin() && !is_student()){
  die_gracefully("You do not have permission to access this page.");
}

if(is_admin() && empty($student_id)){
  printf("<H2 ALIGN=\"CENTER\">Error</H2>\n");
  printf("<P ALIGN=\"CENTER\">");
  printf("Try the <A HREF=\"%s\">Student Directory</A> first.</P>\n",
         $conf['scripts']['admin']['studentdir']);
  die_gracefully("You cannot access this page without a student id.");

  die_gracefully("No student id is specified.");
}

if(is_student()){
  $student_id = get_id();
}

// The default mode for the global variable
if(empty ($mode)) $mode = DISPLAY_ODETAILS;

// Getting into the right mode for the right job
switch($mode)
{

  case DISPLAY_ODETAILS;
    display_odetails();
    break;

  case EDIT_ACTIVITIES;
    edit_activities();
    break;

  case UPDATE_ACTIVITIES;
    update_activities();
    break;

  case DELETE_ACTIVITIES;
    delete_activities();
    break;

  case EDIT_ACHIEVEMENTS;
    edit_achievements();
    break;

  case UPDATE_ACHIEVEMENTS;
    update_achievements();
    break;

  case DELETE_ACHIEVEMENTS;
    delete_achievements();
    break;

  case EDIT_CAREER;
    edit_career();
    break;

  case UPDATE_CAREER;
    update_career();
    break;

  case DELETE_CAREER;
    delete_career();
    break; 
  
}

// Print the footer and finish the page
$page->end();


function create_other_record($student_id)
{
  global $log; // Access to logging

  if(empty($student_id)){
    $log['debug']->LogPrint("Unable to create blank contact detail - missing id");
    die_gracefully("This page needs a student id with which to be accessed.\n");
  }
  $query = sprintf("INSERT INTO cv_odetails (id) VALUES(%s)", $student_id);
  mysql_query($query) or
    print_mysql_error("Unable to create blank other detail record.\n");

  $log['access']->LogPrint("Created blank contact details record.");  
}


function display_odetails()
{

  global $PHP_SELF;    // A reference to the script
  global $student_id;  // A student identity
  global $log;         // Access to logging

  if(is_admin() && !is_auth_for_student($student_id, "student", "viewCV"))
    die_gracefully("You are not authorised to view this student\'s CV");

  // Run a query on the database for the lines of matching information.
  $query = sprintf("SELECT * FROM cv_odetails WHERE id='%d'", $student_id);

  // Run this by the server
  $result = mysql_query($query)
    or print_mysql_error("Unable to fetch other details for student $student_id");

  // Make a new record, if required...
  if(!mysql_num_rows($result)) create_other_record($student_id);

  // Ok now get the row of results after the query
  $row = mysql_fetch_array($result);

 // Displaying the results in the form of a table
  printf("<H2 ALIGN=\"CENTER\">Other Details</H2>\n");
  printf("<TABLE ALIGN=\"CENTER\" VALIGN=\"top\" CELLSPACING=\"0\"
          CELLPADDING=\"1\">\n");
  printf("<TR>\n");
  printf("<TD WIDTH=\"5%%\"></TD>\n");
  printf("<TD>\n");
   printf("<FONT FACE=\"Arial, Helvetica, sans-serif\" SIZE=\"2\">\n");
  printf("Activites\n");
  printf("</FONT>");
  printf("</TD>\n");
  printf("<TD WIDTH=\"5%%\"></TD>\n");
  printf("</TR>\n");
  printf("<TR>\n");
  printf("<TD WIDTH=\"5%%\"></TD>\n");
  printf("<TD>\n");
  printf("</TD>\n");
  printf("<TD WIDTH=\"5%%\"></TD>\n");
  printf("</TR>\n");
  printf("<TR>\n");
  printf("<TD WIDTH=\"5%%\"></TD>\n");
  printf("<TD WIDTH=\"90%%\">\n");
  printf("<FONT FACE=\"Arial, Helvetica, sans-serif\" SIZE=\"2\">\n");
  printf("%s", $row["activities"]);
  printf("</FONT>");
  printf("</TD>\n");
  printf("<TD WIDTH=\"5%%\"></TD>\n");
  printf("</TR>\n");
  printf("<TR>\n");
  printf("<TD WIDTH=\"5%%\"></TD>\n");
  printf("<TD>\n");
  printf("<TABLE ALIGN=\"CENTER\" VALIGN=\"top\" CELLSPACING=\"1\"
          CELLPADDING=\"1\">\n");
  printf("<TR>\n<TD>\n");
  printf("<A HREF=\"%s?student_id=%s&mode=EDIT_ACTIVITIES\">\n", $PHP_SELF, $student_id);
  printf("<FONT FACE=\"Arial, Helvetica, sans-serif\" SIZE=\"2\">\n");
  printf("Edit \n</FONT></A>\n");
  printf("</TD><TD>\n");
  printf("<A HREF=\"%s?student_id=%s&mode=DELETE_ACTIVITIES\">\n", $PHP_SELF, $student_id);
  printf("<FONT FACE=\"Arial, Helvetica, sans-serif\" SIZE=\"2\">\n");
  printf("DELETE \n</FONT></A>\n");
  printf("</TD></TR>\n</TABLE>\n"); 
  printf("</TD>\n");
  printf("<TD WIDTH=\"5%%\"></TD>\n");
  printf("</TR>\n");
  printf("<TR>\n");
  printf("<TD WIDTH=\"5%%\"></TD>\n");
  printf("<TD>\n");
  printf("</TD>\n");
  printf("<TD WIDTH=\"5%%\"></TD>\n");
  printf("</TR>\n");
  
  // Start of The achievements display of table
  printf("<TR>\n");
  printf("<TD WIDTH=\"5%%\"></TD>\n");
  printf("<TD>\n");
  printf("<FONT FACE=\"Arial, Helvetica, sans-serif\" SIZE=\"2\" ALIGN=\"center\">\n");
  printf("Achievements\n");
  printf("</FONT>");
  printf("</TD>\n");
  printf("<TD WIDTH=\"5%%\"></TD>\n");
  printf("</TR>\n");
  printf("<TR>\n");
  printf("<TD WIDTH=\"5%%\"></TD>\n");
  printf("<TD>\n");
  printf("</TD>\n");
  printf("<TD WIDTH=\"5%%\"></TD>\n");
  printf("</TR>\n");
  printf("<TR>\n");
  printf("<TD WIDTH=\"5%%\"></TD>\n");
  printf("<TD WIDTH=\"90%%\">\n");
  printf("<FONT FACE=\"Arial, Helvetica, sans-serif\" SIZE=\"2\">\n");
  printf("%s", $row["achievements"]);
  printf("</FONT>");
  printf("</TD>\n");
  printf("<TD WIDTH=\"5%%\"></TD>\n");
  printf("</TR>\n");
  printf("<TR>\n");
  printf("<TD WIDTH=\"5%%\"></TD>\n");
  printf("<TD>\n");
  printf("<TABLE ALIGN=\"CENTER\" VALIGN=\"top\" CELLSPACING=\"1\"
          CELLPADDING=\"1\">\n");
  printf("<TR>\n<TD>\n");
  printf("<A HREF=\"%s?student_id=%s&mode=EDIT_ACHIEVEMENTS\">\n", $PHP_SELF, $student_id);
  printf("<FONT FACE=\"Arial, Helvetica, sans-serif\" SIZE=\"2\">\n");
  printf("Edit \n</FONT></A>\n");
  printf("</TD><TD>\n");
  printf("<A HREF=\"%s?student_id=%s&mode=DELETE_ACHIEVEMENTS\">\n", $PHP_SELF, $student_id);
  printf("<FONT FACE=\"Arial, Helvetica, sans-serif\" SIZE=\"2\">\n");
  printf("DELETE \n</FONT></A>\n");
  printf("</TD></TR>\n</TABLE>\n");
  printf("</TD>\n");
  printf("<TD WIDTH=\"5%%\"></TD>\n");
  printf("</TR>\n");
 
  // Start of the carrer section of the table
  printf("<TR>\n");
  printf("<TD WIDTH=\"5%%\"></TD>\n");
  printf("<TD>\n");
   printf("<FONT FACE=\"Arial, Helvetica, sans-serif\" SIZE=\"2\">\n");
  printf("Career\n");
  printf("</FONT>");
  printf("</TD>\n");
  printf("<TD WIDTH=\"5%%\"></TD>\n");
  printf("</TR>\n");
  printf("<TR>\n");
  printf("<TD WIDTH=\"5%%\"></TD>\n");
  printf("<TD>\n");
  printf("</TD>\n");
  printf("<TD WIDTH=\"5%%\"></TD>\n");
  printf("</TR>\n");
  printf("<TR>\n");
  printf("<TD WIDTH=\"5%%\"></TD>\n");
  printf("<TD WIDTH=\"90%%\">\n");
  printf("<FONT FACE=\"Arial, Helvetica, sans-serif\" SIZE=\"2\">\n");
  printf("%s", $row["career"]);
  printf("</FONT>");
  printf("</TD>\n");
  printf("<TD WIDTH=\"5%%\"></TD>\n");
  printf("</TR>\n");
  printf("<TR>\n");
  printf("<TD WIDTH=\"5%%\"></TD>\n");
  printf("<TD>\n");
  printf("<TABLE ALIGN=\"CENTER\" VALIGN=\"top\" CELLSPACING=\"1\"
          CELLPADDING=\"1\">\n");
  printf("<TR>\n<TD>\n");
  printf("<A HREF=\"%s?student_id=%s&mode=EDIT_CAREER\">\n", $PHP_SELF, $student_id);
  printf("<FONT FACE=\"Arial, Helvetica, sans-serif\" SIZE=\"2\">\n");
  printf("Edit \n</FONT></A>\n");
  printf("</TD><TD>\n");
  printf("<A HREF=\"%s?student_id=%s&mode=DELETE_CAREER\">\n", $PHP_SELF, $student_id);
  printf("<FONT FACE=\"Arial, Helvetica, sans-serif\" SIZE=\"2\">\n");
  printf("DELETE \n</FONT></A>\n");
  printf("</TD></TR>\n</TABLE>\n");
  printf("</TD>\n");
  printf("<TD WIDTH=\"5%%\"></TD>\n");
  printf("</TR>\n");
 

  printf("</TABLE>\n");

  right_column("StudentCVOther");
}


function edit_activities()
{

    // A required global variable
  global $PHP_SELF;
  global $student_id;

  if(is_admin() && !is_auth_for_student($student_id, "student", "viewCV"))
    die_gracefully("You are not authorised to view this student\'s CV");


  // Run a query on the database for the lines of matching information.
  $query = sprintf("SELECT * FROM cv_odetails WHERE id='%d'", $student_id);

  // Run this by the server
  $result = mysql_query($query)
    or die_gracefully(mysql_error());

  // Ok now get the row of results after the query
  $row = mysql_fetch_array($result);

  // Display the results in the form of an editable form

  printf("<H2 ALIGN=\"CENTER\">Activities</H2>\n");
  
  printf("<FORM METHOD=\"post\" ACTION=\"%s?student_id=%s&mode=%s\">\n",
            $PHP_SELF, $student_id, UPDATE_ACTIVITIES); 

  printf("<TABLE ALIGN=\"CENTER\">");
  printf("<TR><TD>");
  printf("<TEXTAREA NAME=\"activities\" ROWS=6 COLS=40>%s</TEXTAREA>
            </TD></TR>\n", htmlspecialchars($row["activities"]));
  printf("<TR><TD>\n");
  printf("<INPUT TYPE=\"submit\" NAME=\"button\" VALUE=\"Update\">");
  printf("<INPUT TYPE=\"reset\" VALUE=\"Reset\">");
  printf("</TD></TR>\n");
  printf("</TABLE>\n");
  printf("</FORM>\n");
}


function update_activities()
{

  // we need the a reference to the script also all the variables
  // needed for the update

  global $PHP_SELF;
  global $student_id;
  global $activities;

  if(is_admin() && !is_auth_for_student($student_id, "student", "editCV"))
    die_gracefully("You are not authorised to edit this student\'s CV");

  
  // Build the query for the update

  $query = sprintf("UPDATE cv_odetails SET activities=%s WHERE id=%s",
    make_null($activities), $student_id);

  // Try the query 
  $result = mysql_query($query)
    or die_gracefully(mysql_error());

  printf("<P ALIGN=\"CENTER\">Changes have been accepted</P>");

  // Display the odetails 
  display_odetails();
}  


function delete_activities()
{
  // we need the a reference to the script also all the variables
  // needed for the update

  global $PHP_SELF;
  global $student_id;

  if(is_admin() && !is_auth_for_student($student_id, "student", "editCV"))
    die_gracefully("You are not authorised to edit this student\'s CV");
  
  // Build the query for the update
  $query = sprintf("UPDATE cv_odetails SET activities=NULL WHERE id=%s", $student_id);

  // Try the query 
  $result = mysql_query($query)
    or die_gracefully(mysql_error());

  printf("<P ALIGN=\"CENTER\">Changes have been accepted</P>");

  // Display the odetails 
  display_odetails();
}


function edit_achievements()
{

    // A required global variable
  global $PHP_SELF;
  global $student_id;

  if(is_admin() && !is_auth_for_student($student_id, "student", "viewCV"))
    die_gracefully("You are not authorised to view this student\'s CV");


  // Run a query on the database for the lines of matching information.
  $query = sprintf("SELECT * FROM cv_odetails WHERE id='%d'", $student_id);

  // Run this by the server
  $result = mysql_query($query)
    or die_gracefully(mysql_error());


  // Ok now get the row of results after the query
  $row = mysql_fetch_array($result);

  // Display the results in the form of an editable form

  printf("<H2 ALIGN=\"CENTER\">Achievements</H2>\n");
  
  printf("<FORM METHOD=\"post\" ACTION=\"%s?student_id=%s&mode=%s\">\n",
            $PHP_SELF, $student_id, UPDATE_ACHIEVEMENTS); 

  printf("<TABLE ALIGN=\"CENTER\">");
  printf("<TR ><TD>");
  printf("<TEXTAREA NAME=\"achievements\" ROWS=6 COLS=40>%s</TEXTAREA>
            </TD></TR>\n", htmlspecialchars($row["achievements"]));
  printf("<TR><TD>\n");
  printf("<INPUT TYPE=\"submit\" NAME=\"button\" VALUE=\"Update\">");
  printf("<INPUT TYPE=\"reset\" VALUE=\"Reset\">");
  printf("</TD></TR>\n");
  printf("</TABLE>\n");
  printf("</FORM>\n");
}


function update_achievements()
{

  // we need the a reference to the script also all the variables
  // needed for the update

  global $PHP_SELF;
  global $student_id;
  global $achievements;
  
  if(is_admin() && !is_auth_for_student($student_id, "student", "editCV"))
    die_gracefully("You are not authorised to edit this student\'s CV");


  // Build the query for the update

  $query = sprintf("UPDATE cv_odetails SET achievements=%s WHERE id=%s",
    make_null($achievements), $student_id);

  // Try the query 
  $result = mysql_query($query)
    or die_gracefully(mysql_error());

  printf("<P ALIGN=\"CENTER\">Changes have been accepted</P>");

  // Display the odetails 
  display_odetails();
}  


function delete_achievements()
{
  // we need the a reference to the script also all the variables
  // needed for the update

  global $PHP_SELF;
  global $student_id;

  if(is_admin() && !is_auth_for_student($student_id, "student", "editCV"))
    die_gracefully("You are not authorised to edit this student\'s CV");

  
  // Build the query for the update
  $query = sprintf("UPDATE cv_odetails SET achievements=NULL WHERE id=%s", $student_id);

  // Try the query 
  $result = mysql_query($query)
    or die_gracefully(mysql_error());

  printf("<P ALIGN=\"CENTER\">Changes have been accepted</P>");

  // Display the odetails 
  display_odetails();
}


function edit_career()
{

    // A required global variable
  global $PHP_SELF;
  global $student_id;

  if(is_admin() && !is_auth_for_student($student_id, "student", "viewCV"))
    die_gracefully("You are not authorised to view this student\'s CV");


  // Run a query on the database for the lines of matching information.
  $query = sprintf("SELECT * FROM cv_odetails WHERE id='%d'", $student_id);

  // Run this by the server
  $result = mysql_query($query)
    or die_gracefully(mysql_error());

  // Ok now get the row of results after the query
  $row = mysql_fetch_array($result);

  // Display the results in the form of an editable form
  printf("<H2 ALIGN=\"CENTER\">Career</H2>\n");
  
  printf("<FORM METHOD=\"post\" ACTION=\"%s?student_id=%s&mode=%s\">\n",
            $PHP_SELF, $student_id, UPDATE_CAREER); 

  printf("<TABLE ALIGN=\"CENTER\">");
  printf("<TR ><TD>");
  printf("<TEXTAREA NAME=\"career\" ROWS=6 COLS=40>%s</TEXTAREA>\n", htmlspecialchars($row["career"]));
  printf("</TD></TR>\n");
  printf("<TR><TD>\n");
  printf("<INPUT TYPE=\"submit\" NAME=\"button\" VALUE=\"Update\">");
  printf("<INPUT TYPE=\"reset\" VALUE=\"Reset\">");
  printf("</TD></TR>\n");
  printf("</TABLE>\n");
  printf("</FORM>\n");
}


function update_career()
{

  // we need the a reference to the script also all the variables
  // needed for the update

  global $PHP_SELF;
  global $student_id;
  global $career;

  if(is_admin() && !is_auth_for_student($student_id, "student", "editCV"))
    die_gracefully("You are not authorised to edit this student\'s CV");

  
  // Build the query for the update
  $query = sprintf("UPDATE cv_odetails SET career=%s WHERE id=%s",
    make_null($career), $student_id);

  // Try the query 
  $result = mysql_query($query)
    or die_gracefully(mysql_error());

  printf("<P ALIGN=\"CENTER\">Changes have been accepted</P>");

  // Display the odetails 
  display_odetails();
}  


function delete_career()
{
  // we need the a reference to the script also all the variables
  // needed for the update

  global $PHP_SELF;
  global $student_id;

  if(is_admin() && !is_auth_for_student($student_id, "student", "editCV"))
    die_gracefully("You are not authorised to edit this student\'s CV");

  
  // Build the query for the update
  $query = sprintf("UPDATE cv_odetails SET career=NULL WHERE id=%s", $student_id);

  // Try the query 
  $result = mysql_query($query)
    or die_gracefully(mysql_error());

  printf("<P ALIGN=\"CENTER\">Changes have been accepted</P>");

  // Display the odetails 
  display_odetails();
}

?>








