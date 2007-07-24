<?php 

/******************************************************************************

	Name : Andrew Hunter
	Date : 13th February 2001
	Program : This is the edit page for the CV system.

*****************************************************************************/ 
  
// The include files 
include('common.php');
include('authenticate.php');	
include('lookup.php');

// Connect to the database on the server
db_connect()
  or die("Unable to connect to server");
  
// Authenticate user so that the right people see the right thing
auth_user("student");
    
page_header("Personal Details");// Calls the function for the header
print_menu("student");
  
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


// The default mode for the global variable
if(empty ($mode)) $mode = EDIT_PDETAILS;

// Getting into the right mode for the right job
switch($mode)
{

  case EDIT_PDETAILS;
    edit_pdetails();
    break;

  case UPDATE_PDETAILS;
    update_pdetails();
    break;
 
}

// Print out the help column on rigth hand side
right_column("StudentCVPersonal");

// Print the footer and finish the page
$page->end();


function edit_pdetails()
{
  global $PHP_SELF;   // A reference to this script
  global $student_id; // The student database id
  global $log;        // Access to logging

  if(is_admin() && !is_auth_for_student($student_id, "student", "viewCV"))
    die_gracefully("You are not permitted to view this student\'s CV");


  // Run a query on the database for the lines of matching information.
  $query = sprintf("SELECT *, DATE_FORMAT(dob, '%%d'),
                      DATE_FORMAT(dob, '%%m'),
                      DATE_FORMAT(dob, '%%Y'),
                      DATE_FORMAT(course_start, '%%m'),
 		      DATE_FORMAT(course_start, '%%y'),
		      DATE_FORMAT(course_end, '%%m'),
                      DATE_FORMAT(course_end, '%%y')
                      FROM cv_pdetails WHERE id='%d'", $student_id);

  // Run this by the server
  $result = mysql_query($query)
    or print_mysql_error("Unable to fetch student information.\n");


  // Ok now get the row of results after the query
  $row = mysql_fetch_array($result);

  // Display the results in the form of an editable form

  printf("<H2 ALIGN=\"CENTER\">Personal Details</H2>\n");
  
  // Check if they can alter the record if not then print then exit
  if(!is_admin() && is_company())
  {
    
    printf("<P ALIGN=\"CENTER\">You do not have access to alter any part");
    printf("of this CV. If you think you should have access to this CV");
    printf(" Please contact the Webmaster</P>");
    exit;
  
  }
   
  printf("<FORM METHOD=\"post\" ACTION=\"%s?mode=%s&student_id=%s\">\n",
            $PHP_SELF, UPDATE_PDETAILS, $student_id); 

  printf("<TABLE ALIGN=\"CENTER\">");
  printf("<TR><TD>Title</TD><TD>");
  printf("<SELECT NAME=\"title\" SIZE=\"1\">\n
            <OPTION SELECTED>%s\n
            <OPTION>Mr\n 
            <OPTION>Mrs\n
            <OPTION>Miss\n
            <OPTION>Ms\n
            </SELECT>\n</TD></TR>", htmlspecialchars($row["title"]));
  printf("<TR><TD>First Name</TD><TD>");
  printf("<INPUT TYPE=\"TEXT\" SIZE=\"20\" VALUE=\"%s\" NAME=\"firstname\">
            </TD></TR>\n", htmlspecialchars($row["firstname"]));
  printf("<TR><TD>Surname</TD><TD>");
  printf("<INPUT TYPE=\"TEXT\" SIZE=\"20\" VALUE=\"%s\" NAME=\"surname\">
          </TD></TR>\n", htmlspecialchars($row["surname"]));
  printf("<TR><TD>Student ID Number</TD><TD>");
  printf("<INPUT TYPE=\"TEXT\" SIZE=\"20\" VALUE=\"%s\" NAME=\"student_num\">
          </TD></TR>\n", htmlspecialchars($row["student_id"])); 
  printf("<TR><TD>Email Address</TD><TD>");
  printf("<INPUT TYPE=\"TEXT\" SIZE=\"20\" VALUE=\"%s\" NAME=\"email\">
          </TD></TR>\n", htmlspecialchars($row["email"]));
  printf("<TR><TD>Date of Birth</TD><TD>");
  printf("Day: <SELECT NAME=\"dob_day\" SIZE=\"1\">\n
          <OPTION SELECTED>%s\n
          <OPTION>01\n<OPTION>02\n<OPTION>03\n
          <OPTION>04\n<OPTION>05\n<OPTION>06\n
          <OPTION>07\n<OPTION>08\n<OPTION>09\n
          <OPTION>10\n<OPTION>11\n<OPTION>12\n
          <OPTION>13\n<OPTION>14\n<OPTION>15\n
          <OPTION>16\n<OPTION>17\n<OPTION>18\n
          <OPTION>19\n<OPTION>20\n<OPTION>21\n
          <OPTION>22\n<OPTION>23\n<OPTION>24\n
          <OPTION>25\n<OPTION>26\n<OPTION>27\n
          <OPTION>28\n<OPTION>29\n<OPTION>30\n
          <OPTION>31\n
          </SELECT>\n", htmlspecialchars($row[mysql_num_fields($result)-7]));
  printf("Month : <SELECT NAME=\"dob_month\" SIZE=\"1\">\n
          <OPTION SELECTED>%s\n
          <OPTION>01\n
          <OPTION>02\n
          <OPTION>03\n
          <OPTION>04\n
          <OPTION>05\n
          <OPTION>06\n
          <OPTION>07\n
          <OPTION>08\n
          <OPTION>09\n
          <OPTION>10\n
          <OPTION>11\n
          <OPTION>12\n
          </SELECT>\n", htmlspecialchars($row[mysql_num_fields($result)-6]));  
  printf("Year: eg(1979) <INPUT TYPE=\"TEXT\" SIZE=\"5\" VALUE=\"%s\" 
          NAME=\"dob_year\"></TD></TR>\n", 
          htmlspecialchars($row[mysql_num_fields($result)-5]));
  printf("<TR><TD>Place of Birth</TD><TD>");
  printf("<INPUT TYPE=\"TEXT\" SIZE=\"20\" VALUE=\"%s\" NAME=\"pob\">
          </TD></TR>\n", htmlspecialchars($row["pob"])); 
  printf("<TR><TD>Nationality</TD><TD>");
  printf("<INPUT TYPE=\"TEXT\" SIZE=\"20\" VALUE=\"%s\" NAME=\"nationality\">
          </TD></TR>\n", htmlspecialchars($row["nationality"]));
  printf("<TR><TD>Course</TD><TD>");

  printf("<SELECT NAME=\"course\" SIZE=\"1\">\n");

  $coursequery = sprintf("SELECT * FROM courses ORDER BY course_name");

  $courseresult = mysql_query($coursequery)
     or print_mysql_error("Could not obtain course list.\n");

  print("<OPTION VALUE=\"0\">Select a course</OPTION>");
  while($courserow = mysql_fetch_array($courseresult)){
    printf("<OPTION");
    if($row["course"] == $courserow["course_id"]) printf(" SELECTED");
    printf(" VALUE=\"%s\">%s</OPTION>\n", $courserow["course_id"], $courserow["course_name"]);
  }
  print("</SELECT>\n");
    
  printf("<TR><TD>Course Started</TD><TD>");
  printf("Month : <SELECT NAME=\"course_start_month\" SIZE=\"1\">\n
            <OPTION SELECTED>%s\n
            <OPTION>01\n
            <OPTION>02\n
            <OPTION>03\n
            <OPTION>04\n
            <OPTION>05\n
            <OPTION>06\n
            <OPTION>07\n
            <OPTION>08\n
            <OPTION>09\n
            <OPTION>10\n
            <OPTION>11\n
            <OPTION>12\n
            </SELECT>\n", htmlspecialchars($row[mysql_num_fields($result)-4]));
 printf("Year : <SELECT NAME=\"course_start_year\" SIZE=\"1\">\n
           <OPTION SELECTED>%s\n
           <OPTION>97\n
           <OPTION>98\n
           <OPTION>99\n
           <OPTION>00\n
           <OPTION>01\n
           <OPTION>02\n
           <OPTION>03\n
           <OPTION>04\n
           <OPTION>05\n
           <OPTION>06\n
           <OPTION>07\n
           <OPTION>08\n
           </SELECT>\n", htmlspecialchars($row[mysql_num_fields($result)-3]));
  printf("<TR><TD>Course Ending</TD><TD>");
printf("Month : <SELECT NAME=\"course_end_month\" SIZE=\"1\">\n
          <OPTION SELECTED>%s\n
          <OPTION>01\n
          <OPTION>02\n
          <OPTION>03\n
          <OPTION>04\n
          <OPTION>05\n
          <OPTION>06\n
          <OPTION>07\n
          <OPTION>08\n
          <OPTION>09\n
          <OPTION>10\n
          <OPTION>11\n
          <OPTION>12\n
          </SELECT>\n", htmlspecialchars($row[mysql_num_fields($result)-2]));
printf("Year : <SELECT NAME=\"course_end_year\" SIZE=\"1\">\n
          <OPTION SELECTED>%s\n
          <OPTION>00\n
          <OPTION>01\n
          <OPTION>02\n
          <OPTION>03\n
          <OPTION>04\n
          <OPTION>05\n
          <OPTION>06\n
          <OPTION>07\n
          <OPTION>08\n
          <OPTION>09\n
          <OPTION>10\n
          <OPTION>11\n
          </SELECT>\n", htmlspecialchars($row[mysql_num_fields($result)-1]));  
  printf("<TR><TD>Expected Grade</TD><TD>");
  printf("<INPUT TYPE=\"TEXT\" SIZE=\"20\" VALUE=\"%s\" NAME=\"expected_grade\"
          ></TD></TR>\n", htmlspecialchars($row["expected_grade"]));
  
  printf("<TR><TD></TD><TD><INPUT TYPE=\"submit\" NAME=\"button\" 
            VALUE=\"Update\">");
  printf("<INPUT TYPE=\"reset\" VALUE=\"Reset\">");
  printf("</TD></TR>\n");
  printf("</TABLE>\n");
  printf("</FORM>\n");
}


function update_pdetails()
{

  // we need the a reference to the script also all the variables
  // needed for the update

  global $PHP_SELF, $student_id;
  global $title, $surname, $firstname, $student_num;
  global $email, $dob_day, $dob_month, $dob_year; 
  global $pob, $nationality, $course;
  global $course_start_month, $course_start_year,$course_start;
  global $course_end_month, $course_end_year, $expected_grade;
  global $conf;
  global $log;

  if(is_admin() && !is_auth_for_student($student_id, "student", "editCV"))
    die_gracefully("You are not permitted to edit this student\'s CV");

   
  // Build the query for the update
  $query = sprintf("UPDATE cv_pdetails SET title=%s, surname=%s, firstname=%s,
    student_id=%s, email=%s, dob=%s%s%s, pob=%s, nationality=%s, course=%s,
    course_start=%s%s'01', course_end=%s%s'01', expected_grade=%s WHERE id=%s",
    make_null($title),
    make_null($surname),
    make_null($firstname),        
    make_null($student_num),
    make_null($email),
    make_null($dob_year),
    make_null($dob_month),
    make_null($dob_day),
    make_null($pob),
    make_null($nationality),
    make_null($course),
    make_null($course_start_year),
    make_null($course_start_month),
    make_null($course_end_year),
    make_null($course_end_month),
    make_null($expected_grade), $student_id);

  // Try the query 
  $result = mysql_query($query)
    or print_mysql_error("Unable to update user details.\n");

  printf("<P ALIGN=\"CENTER\">Changes have been accepted<BR>\n");
  printf("<A HREF=\"%s?student_id=%s&per=1\">Click here</A>",
         $conf['scripts']['student']['viewcv'],
         $student_id);
  printf(" to view the CV.</P>");

  $log_string = sprintf("Personal details updated for user %s (%s)",
                        get_user_name($student_id), $student_id);
  $log['access']->LogPrint($log_string);
}  
   

?>


