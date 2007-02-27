<?php

/**
**	view_cv.php
**
** Initial coding : Andrew Hunter
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

page_header("View CV");               // Calls the function for the header
print_menu("student");                // Print the menu for the student user

if(is_admin())
{
  if(empty($student_id))
  {
    printf("<H2 ALIGN=\"CENTER\">Error</H2>\n");
    printf("<P ALIGN=\"CENTER\">");
    printf("Try the <A HREF=\"%s\">Student Directory</A> first.</P>\n",
           $conf['scripts']['admin']['studentdir']);
    die_gracefully("You cannot access this page without a student id.");
  }
  else
  {
    if(!is_auth_for_student($student_id, "student", "viewCV"))
      die_gracefully("You are not authorised to look at this student\'s CV");
  }
}

if(!is_student() && !is_admin())
  die_gracefully("You do not have permission to access this page.");

if(is_student()) $student_id = get_id();

if(empty($_SESSION['user']['lasttime'])){
  welcome_login();
  page_footer();
  exit;
}

display_studentname();
display_pdetails();
display_cdetails();
display_edetails();
display_work();
display_odetails();

// Print out the help column on rigth hand side
right_column("StudentCVView");

// Print the footer and finish the page
page_footer();


function display_studentname()
{
  global $student_id;

  // Run a query on the database for the lines of matching information.
  $query = sprintf("SELECT * FROM cv_pdetails WHERE id='%d'", $student_id);

  // Run this by the server
  $result = mysql_query($query)
    or die_gracefully(mysql_error());

  // Ok now get the row of results after the query
  $row = mysql_fetch_array($result);
    
  // Title at the top of the page
  printf("<CENTER>\n");
  printf("<FONT FACE=\"Arial, Helvetica, sans-serif\" SIZE=\"4\">\n");
  printf("CV for %s %s %s\n", $row["title"],$row["firstname"],$row["surname"]);
  printf("</FONT>\n");
  printf("</CENTER>\n");
  printf("<BR>\n");
}



/*
** Displays the personal details for the specified student
**
*/
function display_pdetails()
{
  // A required global variable
  global $PHP_SELF;
  global $student_id;
  global $per, $con, $edu, $wor, $oth; // What to display!
  global $conf;

  // Print a menu bar
  print("<A NAME=\"per\">\n");
  printf("<TABLE BGCOLOR=\"silver\" WIDTH=\"100%%\" ALIGN=\"CENTER\"
             VALIGN=\"TOP\" CELLSPACING=\"0\" CELLPADDING=\"0\">\n");
  printf("<TR>\n");
  printf("<TD ALIGN=\"left\" width=\"80%%\">");
  printf("Personal Details");
  printf("</TD>\n");
  printf("<TD ALIGN=\"right\" width=\"10%%\">");
  printf("<A HREF=\"%s?mode=EDIT_PDETAILS&student_id=%s\">\n",
         $conf['scripts']['student']['pdetails'],
         $student_id);
  printf("Edit");
  printf("</A>\n");
  printf("</TD>\n");
  printf("<TD ALIGN=\"right\" width=\"10%%\">");
  
  // A link to show or hide this section (as appopriate)
  printf("<A HREF=\"%s?student_id=%s",
       $PHP_SELF, $student_id);

  // Reverse current setting
  if($per==0) printf("&per=1");

  // Preserve other settings
  if($con==1) printf("&con=1");
  if($edu==1) printf("&edu=1");
  if($wor==1) printf("&wor=1");
  if($oth==1) printf("&oth=1");
    
  printf("#per\">\n");

  if($per==0) printf("View");
  else printf("Hide");
  
  printf("</A>\n");
  printf("</TD>\n");
  printf("</TR>\n");
  printf("</TABLE>\n");


  if($per==0){
    printf("<BR>");
    return; // This info is hidden...
  }
  
  // Get the information from the server
  // Run a query on the database for the lines of matching information.
  $query = sprintf("SELECT *, DATE_FORMAT(dob, '%%D %%M %%Y'),
                      DATE_FORMAT(course_start, '%%M %%y'),
		      DATE_FORMAT(course_end, '%%M %%y')
                      FROM cv_pdetails WHERE id='%d'", $student_id);

  // Run this by the server
  $result = mysql_query($query)
    or die_gracefully(mysql_error());


  // Ok now get the row of results after the query
  $row = mysql_fetch_array($result);


  // start of displaying the information in the form of a table
  printf("<FONT FACE=\"Arial, Helvetica, sans-serif\" SIZE=\"2\">");

  // Displaying the results in the form of a table
  printf("<H2 ALIGN=\"CENTER\">Personal Details</H2>\n");
  printf("<TABLE ALIGN=\"CENTER\" VALIGN=\"TOP\" CELLSPACING=\"0\" 
           CELLPADDING=\"1\">\n");
  printf("<TR>\n");
  printf("<TD>\n");
  printf("<FONT FACE=\"Arial, Helvetica, sans-serif\" SIZE=\"2\">Title\n");
  printf("</TD>\n");
  printf("<TD>%s</TD>\n", $row["title"]);  
  printf("</TR>\n");
  printf("<TR>\n");
  printf("<TD>First Name</TD>\n");
  printf("<TD>%s</TD>\n", $row["firstname"]);  
  printf("</TR>\n");
  printf("<TR>\n");
  printf("<TD>Surname</TD>\n");
  printf("<TD>%s</TD>\n", $row["surname"]);  
  printf("</TR>\n");
  printf("<TR>\n");
  printf("<TD>Student ID number</TD>\n");
  printf("<TD>%s</TD>\n", $row["student_id"]);  
  printf("</TR>\n");
  printf("<TR>\n");
  printf("<TD>Email Address</TD>\n");
  printf("<TD>%s</TD>\n", $row["email"]);  
  printf("</TR>\n");
  printf("<TR>\n");
  printf("<TD>Date of Birth</TD>\n");
  printf("<TD>%s</TD>\n", $row[mysql_num_fields($result)-3]);  
  printf("</TR>\n");
  printf("<TR>\n");
  printf("<TD>Place of Birth</TD>\n");
  printf("<TD>%s</TD>\n", $row["pob"]);  
  printf("</TR>\n");
  printf("<TR>\n");
  printf("<TD>Nationality</TD>\n");
  printf("<TD>%s</TD>\n", $row["nationality"]);  
  printf("</TR>\n");
  printf("<TR>\n");
  printf("<TD>Course</TD>\n");
  printf("<TD>%s</TD>\n", get_course_name($row["course"]));  
  printf("</TR>\n");
  printf("<TR>\n");
  printf("<TD>Started Course</TD>\n");
  printf("<TD>%s</TD>\n", $row[mysql_num_fields($result)-2]);  
  printf("</TR>\n");
  printf("<TR>\n");
  printf("<TD>Curse ending</TD>\n");
  printf("<TD>%s</TD>\n", $row[mysql_num_fields($result)-1]);  
  printf("</TR>\n");
  printf("<TR>\n");
  printf("<TD>Expected Grade</TD>\n");
  printf("<TD>%s</TD>\n", $row["expected_grade"]);  
  printf("</TR>\n");
  printf("</TABLE>\n");
  printf("</FONT>\n");
  printf("<BR>\n");  
}


/*
**    display_cdetails
**
** This function displays the contact details for the student.
**
*/
function display_cdetails()
{
  global $PHP_SELF;
  global $student_id;
  global $per, $con, $edu, $wor, $oth;
  global $conf;

  // Print a menu bar
  print("<A NAME=\"con\">\n");
  printf("<TABLE BGCOLOR=\"silver\" WIDTH=\"100%%\" ALIGN=\"CENTER\"");
  printf("VALIGN=\"TOP\" CELLSPACING=\"0\" CELLPADDING=\"0\">\n");
  printf("<TR>\n");
  printf("<TD ALIGN=\"left\" WIDTH=\"80%%\">");
  printf("Contact Details");
  printf("</TD>\n");
  printf("<TD ALIGN=\"right\" width=\"10%%\">");
  printf("<A HREF=\"%s?mode=EDIT_CDETAILS&student_id=%s\">\n",
         $conf['scripts']['student']['cdetails'],
         $student_id);
  printf("Edit");
  printf("</A>\n");
  printf("</TD>\n");
  printf("<TD ALIGN=\"right\" WIDTH=\"10%%\">");
  
  // A link to show or hide this section (as appopriate)
  printf("<A HREF=\"%s?student_id=%s",
       $PHP_SELF, $student_id);

  // Reverse current setting
  if($con==0) printf("&con=1");

  // Preserve other settings
  if($per==1) printf("&per=1");
  if($edu==1) printf("&edu=1");
  if($wor==1) printf("&wor=1");
  if($oth==1) printf("&oth=1");
    
  printf("#con\">\n");

  if($con==0) printf("View");
  else printf("Hide");
  
  printf("</A>\n");
  printf("</TD>\n");
  printf("</TR>\n");
  printf("</TABLE>\n");

  if($con==0){
    print("<BR>");
    return; // This info is hidden...
  }
  
  // Run a query on the database for the lines of matching information.
  $query = sprintf("SELECT * FROM cv_cdetails WHERE id='%d'", $student_id);

  // Run this by the server
  $result = mysql_query($query)
    or die_gracefully(mysql_error());

  // Ok now get the row of results after the query
  $row = mysql_fetch_array($result);

  printf("<FONT FACE=\"Arial, Helvetica, sans-serif\" SIZE=\"2\">");

  // Displaying the results in the form of a table
  printf("<H2 ALIGN=\"CENTER\">Contact Details</H2>\n");
  printf("<TABLE ALIGN=\"CENTER\" VALIGN=\"top\" CELLSPACING=\"0\"
          CELLPADDING=\"1\">\n");
  printf("<TR>\n");
  printf("<TD>\n");
  printf("<FONT FACE=\"Arial, Helvetica, sans-serif\" SIZE=\"2\">
          Home Address \n");
  printf("</TD>\n");
  printf("<TD>%s</TD>\n", $row["home_add_l1"]);  
  printf("</TR>\n");
  printf("<TR>\n");
  printf("<TD></TD>\n");
  printf("<TD>%s</TD>\n", $row["home_add_l2"]);  
  printf("</TR>\n");
  printf("<TR>\n");
  printf("<TD></TD>\n");
  printf("<TD>%s</TD>\n", $row["home_add_l3"]);  
  printf("</TR>\n");
  printf("<TR>\n");
  printf("<TD>Home Town</TD>\n");
  printf("<TD>%s</TD>\n", $row["home_town"]);  
  printf("</TR>\n");
  printf("<TR>\n");
  printf("<TD>Home County</TD>\n");
  printf("<TD>%s</TD>\n", $row["home_county"]);  
  printf("</TR>\n");
  printf("<TR>\n");
  printf("<TD>Home Country</TD>\n");
  printf("<TD>%s</TD>\n", $row["home_country"]);  
  printf("</TR>\n");
  printf("<TR>\n");
  printf("<TD>Home Postcode or Zipcode</TD>\n");
  printf("<TD>%s</TD>\n", $row["home_pcode"]);  
  printf("</TR>\n");
  printf("<TR>\n");
  printf("<TD>Home Telephone Number</TD>\n");
  printf("<TD>%s</TD>\n", $row["home_tele"]);  
  printf("</TR>\n");

  // if there is nothing to print out d'not print 
  if($row["term_add_l1"] !=null)
  { 
    printf("<TR>\n");
    printf("<TD>Term Address</TD>\n");
    printf("<TD>%s</TD>\n", $row["term_add_l1"]);  
    printf("</TR>\n");
    printf("<TR>\n");
    printf("<TD></TD>\n");
    printf("<TD>%s</TD>\n", $row["term_add_l2"]);  
    printf("</TR>\n");
    printf("<TR>\n");
    printf("<TD></TD>\n");
    printf("<TD>%s</TD>\n", $row["term_add_l3"]);  
    printf("</TR>\n");
    printf("<TR>\n");
    printf("<TD>Term Town</TD>\n");
    printf("<TD>%s</TD>\n", $row["term_town"]);  
    printf("</TR>\n");
    printf("<TR>\n");
    printf("<TD>Term County</TD>\n");
    printf("<TD>%s</TD>\n", $row["term_county"]);
    printf("</TR>\n");
    printf("<TR>\n");
    printf("<TD>Term Post Code</TD>\n");
    printf("<TD>%s</TD>\n", $row["term_pcode"]);
    printf("</TR>\n");
    printf("<TR>\n");
    printf("<TD>Term Telephone Number</TD>\n");
    printf("<TD>%s</TD>\n", $row["term_tele"]);
    printf("</TR>\n");
  }

  // If there is no mobile tele do not print it out
  if($row["mobile_no"] != NULL)
  {
 
    printf("<TR>\n");
    printf("<TD>Mobile Telephone Number</TD>\n");
    printf("<TD>%s</TD>\n", $row["mobile_no"]);
    printf("</TR>\n");

  }

  printf("</TABLE>\n");
  printf("</FONT>\n");

}

/**
**    display_edetails
**
** Shows the education details for a specified student.
**
*/
function display_edetails()
{
  global $PHP_SELF;
  global $student_id;
  global $per, $con, $edu, $wor, $oth;
  global $conf;

  // Print menu bar
  print("<A NAME=\"edu\">\n");
  printf("<TABLE BGCOLOR=\"silver\" WIDTH=\"100%%\" ALIGN=\"CENTER\"
             VALIGN=\"TOP\" CELLSPACING=\"0\" CELLPADDING=\"0\">\n");
  printf("<TR>\n");
  printf("<TD ALIGN=\"left\" WIDTH=\"80%%\">");
  printf("Educational Details");
  printf("</TD>\n");
    printf("<TD ALIGN=\"right\" width=\"10%%\">");
  printf("<A HREF=\"%s?student_id=%s\">\n",
         $conf['scripts']['student']['edetails'],
         $student_id);
  printf("Edit");
  printf("</A>\n");
  printf("</TD>\n");
  printf("<TD ALIGN=\"right\" WIDTH=\"10%%\">");
  
  // A link to show or hide this section (as appopriate)
  printf("<A HREF=\"%s?student_id=%s",
       $PHP_SELF, $student_id);

  // Reverse current setting
  if($edu==0) printf("&edu=1");

  // Preserve other settings
  if($per==1) printf("&per=1");
  if($con==1) printf("&con=1");
  if($wor==1) printf("&wor=1");
  if($oth==1) printf("&oth=1");
    
  printf("#edu\">\n");

  if($edu==0) printf("View");
  else printf("Hide");
  
  printf("</A>\n");
  printf("</TD>\n");
  printf("</TR>\n");
  printf("</TABLE>\n");
  
  if($edu==0){
    print("<BR>");
    return; // This info is hidden...
  }

  // Run a query on the database for the lines of matching information.
  $query = sprintf("SELECT * FROM cv_edetails WHERE id='%d'", $student_id);

  // Run this by the server
  $result = mysql_query($query)
    or die_gracefully(mysql_error());

  printf("<FONT FACE=\"Arial, Helvetica, sans-serif\" SIZE=\"2\">");

  // Displaying the results in the form of a table
  printf("<H2 ALIGN=\"CENTER\">Education Details</H2>\n");

  // Ok now get the row of results after the query
  while($row = mysql_fetch_row($result))
  {
  printf("<TABLE ALIGN=\"CENTER\" VALIGN=\"top\" CELLSPACING=\"0\"
          CELLPADDING=\"1\">\n");

    // Print out the school
    printf("<TR>\n");
    printf("<TD>Place</TD>\n");
    printf("<TD>%s</TD>\n", $row[1]);
    printf("</TR>\n"); 
    printf("<TR>\n");
    printf("<TD>Year</TD>\n");
    printf("<TD>%s</TD>\n", $row[2]);
    printf("</TR>\n");  
    printf("<TR>\n");
    printf("<TD>Level</TD>\n");
    printf("<TD>%s</TD>\n", $row[3]);
    printf("</TR>\n");
    if($row[4] != NULL)
    {
      
      printf("<TR>\n");
      printf("<TD>Course</TD>\n");
      printf("<TD>%s</TD>\n", $row[4]);
      printf("</TR>\n");

    }
    echo "</TABLE>";
    echo "<BR><BR>";

    // Search for the results from the school

    // Run a query on the database for the lines of matching information.
    $query2 = sprintf("SELECT * FROM cv_results WHERE link='%d'", $row[5]);

    // Run this by the server
    $result2 = mysql_query($query2)
      or die_gracefully(mysql_error());

    echo "<TABLE ALIGN=\"CENTER\" COLS=\"2\">";
    echo "<TR><TD>Subject</TD><TD>Result</TD></TR>\n";
    while($row2 = mysql_fetch_row($result2))
    {
      echo "<TR>";

      printf("<TD>%s</TD>\n", $row2[1]);
      printf("<TD>%s</TD>\n", $row2[2]);
      printf("</TR>\n");

    }
    echo "</TABLE>";
    
    echo "<HR>\n";
    
  } 

}


/**
**    display_work
**
** This function displays the work experience of the student
**
*/
function display_work()
{
  global $PHP_SELF;
  global $student_id;
  global $per, $con, $edu, $wor, $edu;
  global $conf;

  // Print Menu bar
  print("<A NAME=\"wor\">\n");
  printf("<TABLE BGCOLOR=\"silver\" WIDTH=\"100%%\" ALIGN=\"CENTER\"
             VALIGN=\"TOP\" CELLSPACING=\"0\" CELLPADDING=\"0\">\n");
  printf("<TR>\n");
  printf("<TD ALIGN=\"left\" WIDTH=\"80%%\">");
  printf("Work Experience");
  printf("</TD>\n");
  printf("<TD ALIGN=\"right\" width=\"10%%\">");
  printf("<A HREF=\"%s?student_id=%s\">\n",
         $conf['scripts']['student']['wdetails'],
         $student_id);
  printf("Edit");
  printf("</A>\n");
  printf("</TD>\n");
  printf("<TD ALIGN=\"right\" WIDTH=\"10%%\">");
  
  // A link to show or hide this section (as appopriate)
  printf("<A HREF=\"%s?student_id=%s",
       $PHP_SELF, $student_id);

  // Reverse current setting
  if($wor==0) printf("&wor=1");

  // Preserve other settings
  if($per==1) printf("&per=1");
  if($con==1) printf("&con=1");
  if($edu==1) printf("&edu=1");
  if($oth==1) printf("&oth=1");
    
  printf("#wor\">\n");

  if($wor==0) printf("View");
  else printf("Hide");
  
  printf("</A>\n");
  printf("</TD>\n");
  printf("</TR>\n");
  printf("</TABLE>\n");
  
  if($wor==0)
  {
    print("<BR>");
    return; // This info is hidden...  
  }

  // Run a query on the database for the lines of matching information.
  $query = sprintf("SELECT *,DATE_FORMAT(start, '%%D %%M %%Y'),
                      DATE_FORMAT(finish, '%%D %%M %%Y') FROM cv_work 
                      WHERE id='%d'", $student_id);

  // Run this by the server
  $result = mysql_query($query)
    or die_gracefully(mysql_error());


  printf("<FONT FACE=\"Arial, Helvetica, sans-serif\" SIZE=\"2\">");

  // Displaying the results in the form of a table
  printf("<H2 ALIGN=\"CENTER\">Work Experience</H2>\n");
 
  // Ok now get the row of results after the query
  while($row = mysql_fetch_row($result))
  {

    printf("<TABLE ALIGN=\"CENTER\" VALIGN=\"top\" CELLSPACING=\"0\"
             CELLPADDING=\"1\">\n");
    printf("<TR>\n");
    printf("<TD>Place</TD>\n");
    printf("<TD>%s</TD>\n", $row[1]);
    printf("</TR>\n"); 
    printf("<TR>\n");
    printf("<TD>Start Date</TD>\n");
    printf("<TD>%s</TD>\n", $row[mysql_num_fields($result)-2]);
    printf("</TR>\n");  
    printf("<TR>\n");
    printf("<TD>Finish Date</TD>\n");
    printf("<TD>%s</TD>\n", $row[mysql_num_fields($result)-1]);
    printf("</TR>\n");
    printf("</TABLE>\n");
   
    printf("<TABLE ALIGN=\"CENTER\" VALIGN=\"top\" CELLSPACING=\"0\"
          CELLPADDING=\"1\">\n");     
    printf("<TR>\n");
    printf("<TD>Description of Work Experience</TD>\n</TR>\n");
    printf("<TR><TD>%s</TD>\n", $row[4]);
    printf("</TR>\n");
    printf("</TABLE>");

  }
}


/**
**    display_odetails
**
** This displays any other details for the student.
*/
function display_odetails()
{
  global $PHP_SELF;
  global $student_id;
  global $per, $con, $edu, $wor, $oth;
  global $conf;

  // Print a menu bar
  print("<A NAME=\"oth\">\n");
  printf("<TABLE BGCOLOR=\"silver\" WIDTH=\"100%%\" ALIGN=\"CENTER\"");
  printf("VALIGN=\"TOP\" CELLSPACING=\"0\" CELLPADDING=\"0\">\n");
  printf("<TR>\n");
  printf("<TD ALIGN=\"left\" WIDTH=\"80%%\">");
  printf("Other Details");
  printf("</TD>\n");
  printf("<TD ALIGN=\"right\" width=\"10%%\">");
  printf("<A HREF=\"%s?student_id=%s\">\n",
         $conf['scripts']['student']['odetails'],
         $student_id);
  printf("Edit");
  printf("</A>\n");
  printf("</TD>\n");
  printf("<TD ALIGN=\"right\" WIDTH=\"10%%\">");
  
  // A link to show or hide this section (as appopriate)
  printf("<A HREF=\"%s?student_id=%s",
       $PHP_SELF, $student_id);

  // Reverse current setting
  if($oth==0) printf("&oth=1");

  // Preserve other settings
  if($per==1) printf("&per=1");
  if($con==1) printf("&con=1");
  if($edu==1) printf("&edu=1");
  if($wor==1) printf("&wor=1");
    
  printf("#oth\">\n");

  if($oth==0) printf("View");
  else printf("Hide");
  
  printf("</A>\n");
  printf("</TD>\n");
  printf("</TR>\n");
  printf("</TABLE>\n");
  
  if($oth==0) return; // This info is hidden...  

  // Run a query on the database for the lines of matching information.
  $query = sprintf("SELECT * FROM cv_odetails WHERE id='%d'", $student_id);

  // Run this by the server
  $result = mysql_query($query)
    or die_gracefully(mysql_error());


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
 
  printf("</TABLE>\n");
  
  printf("<BR>");
}

?>

















