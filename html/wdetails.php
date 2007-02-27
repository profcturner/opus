<?php

/**
**	wdetails.php
**
** This student script allows a student to edit his or her
** work experience history.
**
** Initial coding : Andrew Hunter
**
** Modified to reflect new back end code.
**
*/ 
  
// The include files 
include('common.php');		
include('authenticate.php');	

// Connect to the database on the server
db_connect()
  or die("Unable to connect to server");
  
// Authenticate user so that the right people see the right thing
auth_user("student");

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
    
page_header("Work Experience");	// Calls the function for the header
print_menu("student");		// Print the menu for the student user
  
// The default mode for the global variable
if(empty ($mode)) $mode = DISPLAY_WORK;

// Getting into the right mode for the right job
switch($mode)
{
  case DISPLAY_WORK:
    display_work();
    break;

  case INSERT_WORK:
    insert_work();
    break;

  case EDIT_WORK:
    edit_work();
    break;

  case UPDATE_WORK:
    update_work();
    break;

  case DELETE_WORK:
    delete_work();
    break;
}

// Print out the help column on rigth hand side
right_column("cdetails");

// Print the footer and finish the page
page_footer();			



function display_work()
{

  // A required global variable
  global $PHP_SELF;
  global $student_id;

  if(is_admin() && !is_auth_for_student($student_id, "student", "viewCV"))
    die_gracefully("You do not have permission to view this student\'s CV");

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

    printf("<TABLE ALIGN=\"CENTER\" VALIGN=\"top\" CELLSPACING=\"0\" CELLPADDING=\"1\">\n");
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
   
    printf("<TABLE ALIGN=\"CENTER\" VALIGN=\"top\" CELLSPACING=\"0\" CELLPADDING=\"1\">\n");     
    printf("<TR>\n");
    printf("<TD>Description of Work Experience</TD>\n</TR>\n");
    printf("<TR><TD>%s</TD>\n", $row[4]);
    printf("</TR>\n");

    printf("<TR>\n");
    printf("<TD ALIGN=\"CENTER\">\n");
    printf("<A HREF=\"%s?mode=EDIT_WORK&start=%s&student_id=%s\">", $PHP_SELF, $row[2], $student_id);
    printf("Edit Record</A> ");
    printf("<A HREF=\"%s?mode=DELETE_WORK&start=%s&student_id=%s\">", $PHP_SELF, $row[2], $student_id);
    printf("Delete Record</A>");
    printf("</TD></TR>\n");
    printf("</TABLE>\n");
    printf("<BR><HR><BR>");

  } 
 
  printf("</FONT>\n");
  //printf("<BR><HR><BR>");
  
  printf("<CENTER>Add A new Work Experience</CENTER>");  

  printf("<FORM METHOD=\"post\" ACTION=\"%s?mode=%s\">\n",
            $PHP_SELF, INSERT_WORK);

  printf("<TABLE ALIGN=\"CENTER\">");
  printf("<TR><TD>Place</TD><TD>");
  printf("<INPUT TYPE=\"TEXT\" SIZE=\"40\" VALUE=\"\" NAME=\"place\">
            </TD></TR>\n");
  printf("<TR><TD>Start Date</TD><TD>");
  printf("Day: <SELECT NAME=\"start_day\" SIZE=\"1\">\n");
  for($loop = 1; $loop <= 31; $loop++) printf("<OPTION>%s\n", $loop);
  printf(" </SELECT>\n");
  printf("Month : <SELECT NAME=\"start_month\" SIZE=\"1\">\n");
  for($loop = 1; $loop <= 12; $loop++) printf("<OPTION>%s\n", $loop);
  printf("</SELECT>\n");
  printf("Year: eg(1979) <INPUT TYPE=\"TEXT\" SIZE=\"5\" VALUE=\"\" NAME=\"start_year\">");
  printf("</TD></TR>\n");
  printf("<TR><TD>Finish Date</TD><TD>");
  printf("Day: <SELECT NAME=\"finish_day\" SIZE=\"1\">\n");
  printf("<OPTION>-</OPTION>\n");
  for($loop = 1; $loop <= 31; $loop++) printf("<OPTION>%s</OPTION>\n", $loop);
  printf("</SELECT>\n");
  printf("Month : <SELECT NAME=\"finish_month\" SIZE=\"1\">\n");
  printf("<OPTION>-</OPTION>\n");
  for($loop = 1; $loop <= 12; $loop++) printf("<OPTION>%s</OPTION>\n", $loop);
  printf("</SELECT>\n");
  printf("Year: eg(1979) <INPUT TYPE=\"TEXT\" SIZE=\"5\" VALUE=\"\" NAME=\"finish_year\">");
  printf("</TD></TR>\n");
  printf("</TABLE>");

  printf("<TABLE ALIGN=\"CENTER\">");
  printf("<TR><TD>\n");
  printf("Description of the work");
  printf("</TD></TR><TR><TD>\n");
  printf("<TEXTAREA NAME=\"work\" ROWS=6 COLS=40></TEXTAREA>
          </TD></TR>\n");
  printf("<TR><TD><INPUT TYPE=\"submit\" NAME=\"button\" 
            VALUE=\"Update\">");
  printf("<INPUT TYPE=\"reset\" VALUE=\"Reset\">");
  printf("</TD></TR>");
  printf("</TABLE>\n");
  printf("</FORM>\n");

  right_column("StudentCVWork");  
}  


function edit_work()
{
  global $PHP_SELF;
  global $student_id;
  global $start;

  if(is_admin() && !is_auth_for_student($student_id, "student", "viewCV"))
    die_gracefully("You do not have permission to view this student\'s CV");


  $query  = "SELECT * FROM cv_work WHERE start='$start' AND id=$student_id"; 
  $result = mysql_query($query)
    or print_mysql_error("Unable to fetch work experience information.");

  $row = mysql_fetch_array($result);

  $start_year  = substr($row["start"], 0, 4);
  $start_mon   = substr($row["start"], 4, 2);
  $start_day   = substr($row["start"], 6, 2);

  $finish_year = substr($row["finish"], 0, 4);
  $finish_mon  = substr($row["finish"], 4, 2);
  $finish_day  = substr($row["finish"], 6, 2);

  printf("<CENTER>Edit A Work Experience</CENTER>");

  printf("<FORM METHOD=\"post\" ACTION=\"%s?mode=%s\">\n",
            $PHP_SELF, UPDATE_WORK);

  printf("<TABLE ALIGN=\"CENTER\">");
  printf("<TR><TD>Place</TD><TD>");
  printf("<INPUT TYPE=\"HIDDEN\" NAME=\"old_start\" VALUE=\"$start\">\n");
  printf("<INPUT TYPE=\"HIDDEN\" NAME=\"student_id\" VALUE=\"$student_id\">\n");
  printf("<INPUT TYPE=\"TEXT\" SIZE=\"40\" NAME=\"place\" VALUE=\"%s\"></TD></TR>\n",
    htmlspecialchars($row["place"]));
  printf("<TR><TD>Start Date</TD><TD>");
  printf("Day: <SELECT NAME=\"start_day\" SIZE=\"1\">\n");
  for($loop = 1; $loop <= 31; $loop++){
    echo "<OPTION";
    if($loop == $start_day) echo " SELECTED";
    echo ">$loop</OPTION>\n";
  }
  printf(" </SELECT>\n");
  printf("Month : <SELECT NAME=\"start_month\" SIZE=\"1\">\n");
  for($loop = 1; $loop <= 12; $loop++){
    echo "<OPTION";
    if($loop == $start_mon) echo " SELECTED";
    echo ">$loop</OPTION>\n";
  }
  printf("</SELECT>\n");
  printf("Year: eg(1990) <INPUT TYPE=\"TEXT\" SIZE=\"5\" VALUE=\"$start_year\" NAME=\"start_year\">");
  printf("</TD></TR>\n");
  printf("<TR><TD>Finish Date</TD><TD>");
  printf("Day: <SELECT NAME=\"finish_day\" SIZE=\"1\">\n");
  echo "<OPTION>-</OPTION>";
  for($loop = 1; $loop <= 31; $loop++)
  {
    echo "<OPTION";
    if($loop == $finish_day) echo " SELECTED";
    echo ">$loop</OPTION>\n";
  }
  printf("</SELECT>\n");
  printf("Month : <SELECT NAME=\"finish_month\" SIZE=\"1\">\n");
  echo "<OPTION>-</OPTION>";
  for($loop = 1; $loop <= 12; $loop++){
    echo "<OPTION";
    if($loop == $finish_mon) echo " SELECTED";
    echo ">$loop</OPTION>\n";
  }
  printf("</SELECT>\n");
  printf("Year: eg(1990) <INPUT TYPE=\"TEXT\" SIZE=\"5\" VALUE=\"$finish_year\" NAME=\"finish_year\">");
  printf("</TD></TR>\n");
  printf("</TABLE>");
  
  printf("<TABLE ALIGN=\"CENTER\">");
  printf("<TR><TD>\n");
  printf("Description of the work");
  printf("</TD></TR><TR><TD>\n");
  printf("<TEXTAREA NAME=\"work\" ROWS=6 COLS=40>");
  printf("%s", htmlspecialchars($row["work"]));
  printf("</TEXTAREA></TD></TR>\n");
  printf("<TR><TD><INPUT TYPE=\"submit\" NAME=\"button\" VALUE=\"Update\">");
  printf("<INPUT TYPE=\"reset\" VALUE=\"Reset\">");
  printf("</TD></TR>");
  printf("</TABLE>\n");
  printf("</FORM>\n");

}


function insert_work()
{

  // we need the a reference to the script also all the variables
  // needed for the update

  global $PHP_SELF;
  global $student_id;
  global $place, $start_day, $start_month, $start_year;
  global $finish_day, $finish_month, $finish_year; 
  global $work;

  if(is_admin() && !is_auth_for_student($student_id, "student", "editCV"))
    die_gracefully("You do not have permission to edit this student\'s CV");
  
  $start_date  = sprintf("%04s%02s%02s", $start_year, $start_month, $start_day);
  $finish_date = sprintf("%04s%02s%02s", $finish_year, $finish_month, $finish_day);
  // Check for no finish date...
  if(($finish_day == "-") || ($finish_month == "_"))
  {
    echo "undefined...";
    if(!(($finish_day == "-") && ($finish_month == "-")))
      die_gracefully("To show that there is no endpoint for an episode " .
                     "of employment, leave both the day and month undefined (\"-\")." .
                     "You have left one only undefined, either fully specify the date " .
                     "or leave it blank. Hit back on your browser to try again.");
    $finish_date = NULL;
  }

  // Build the query for the update
  $query = sprintf("INSERT INTO cv_work (place, start, finish, work, id)
                    VALUES (%s, %s, %s, %s, %s)",
    make_null($place),
    make_null($start_date),
    make_null($finish_date),
    make_null($work),
    $student_id);
  echo $query;

  // Try the query 
  $result = mysql_query($query)
    or die_gracefully(mysql_error());

  printf("<P ALIGN=\"CENTER\">Changes have been accepted</P>");

  // Display the work experience 
  display_work();
}


function update_work()
{

  // we need the a reference to the script also all the variables
  // needed for the update
  
  global $PHP_SELF;
  global $student_id;
  global $place, $start_day, $start_month, $start_year;
  global $finish_day, $finish_month, $finish_year;
  global $old_start;
  global $work;

  if(is_admin() && !is_auth_for_student($student_id, "student", "editCV"))
    die_gracefully("You do not have permission to edit this student\'s CV");

  
  // Build the query for the update
  $query = "UPDATE cv_work SET" .
           " place = " . make_null($place) .
           ", start = " . sprintf("%04s%02s%02s", $start_year, $start_month, $start_day) .
           ", finish = " . sprintf("%04s%02s%02s", $finish_year, $finish_month, $finish_day) .
           ", work = " . make_null($work) .
           " WHERE id = $student_id AND start = $old_start";

  // Try the query
  $result = mysql_query($query)
    or print_mysql_error("Unable to save work experience changes.");
  
  printf("<P ALIGN=\"CENTER\">Changes have been accepted</P>");
  
  // Display the work experience
  display_work();
}


function delete_work()
{

  global $student_id;
  global $start;

  // Run a query on the database for the lines of matching information.
  $query = sprintf("DELETE FROM cv_work WHERE id=%s AND start=%s", 
                      $student_id, $start);

  // Run this by the server
  $result = mysql_query($query)
    or die_gracefully(mysql_error());
  
  display_work();
}

?>



