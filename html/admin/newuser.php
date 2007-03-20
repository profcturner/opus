<?php

/**
**	newuser.php
**
** This administration script allows the creation
** of new users. It is intended for occaisionally
** making one or two users, and more intensive tasks
** should be given to the import script.
**
** Initial coding : Andrew Hunter
**
** Modified by Colin Turner for new configuration
** files and to restrict access.
**
** Modified to only create student users 3/11/2003
*/

// The include files
include('common.php');
include('authenticate.php');
include('users.php');
include('lookup.php');

// Connect to the database on the server
db_connect()
  or die("Unable to connect to server");

auth_user("admin");

// Check the user has basic level access
if(!check_default_policy("student", "create"))
  die_gracefully("You do not have permission to create new students.");

// The Page Header file
page_header("New User Creation");

// The default mode for the global variable
if(empty ($mode)) $mode = NEW_USER;

// Getting into the right mode for the right job
switch($mode)
{

  case NEW_USER;
    new_user();
    break;

  case ADD_NEWUSER;
    add_newuser();
    break;
  
}

  
// Print the footer and finish the page
$page->end();


function new_user()
{

  global $PHP_SELF;

  printf("<H2 ALIGN=\"CENTER\">Add a new Student</H2>\n");

  output_help("AdminNewStudent");

  $year = get_academic_year()+1;

  // Start of new user form
  printf("<FORM METHOD=\"post\" ACTION=\"%s?mode=%s\">\n",
            $PHP_SELF, ADD_NEWUSER);

  printf("<TABLE ALIGN=\"CENTER\">\n");
  printf("<TR><TD>Title</TD>\n<TD>");
  printf("<INPUT TYPE=\"TEXT\" SIZE=\"5\" NAME=\"title\"></TD></TR>\n");
  printf("<TR><TD>First name</TD>\n<TD>");
  printf("<INPUT TYPE=\"TEXT\" SIZE=\"20\" NAME=\"firstname\"></TD></TR>\n");
  printf("<TR><TD>Surname</TD>\n<TD>");
  printf("<INPUT TYPE=\"TEXT\" SIZE=\"20\" NAME=\"surname\"></TD></TR>\n");
  printf("<TR><TD>Email</TD>\n<TD>");
  printf("<INPUT TYPE=\"TEXT\" SIZE=\"40\" NAME=\"email\"></TD></TR>\n");
  printf("<TR><TD>Year seeking placement</TD>\n<TD>");
  printf("<INPUT TYPE=\"TEXT\" SIZE=\"5\" NAME=\"year\" VALUE=\"$year\"></TD></TR>\n");
  printf("<TR><TD>Course</TD>\n<TD><SELECT NAME=\"course\">");
  printf("<OPTION VALUE=\"0\">--- Select a course ---</OPTION>\n");

  $query = "SELECT * FROM courses ORDER BY course_code, course_name";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch course list", $query);
  while($row = mysql_fetch_array($result))
  {
    $school_id = get_school_id($row["course_id"]);
    // Only permit the admin to use a course they are authorised for
    if(is_auth_for_school($school_id, "student", "create") ||
       is_auth_for_course($row["course_id"], "student", "create"))
    {
      echo "<OPTION VALUE=\"" . $row["course_id"] . "\">";
      echo htmlspecialchars($row["course_code"] . ": " . $row["course_name"]);
      echo "</OPTION>\n";
    }
  }
  mysql_free_result($result);
  echo "</SELECT></TD></TR>\n";
  

  printf("<TR>\n<TD>Student Number</TD>\n<TD>\n");
  printf("<INPUT TYPE\"TEXT\" SIZE=\"20\" NAME=\"username\">\n</TD>\n</TR>");
  printf("<TR>\n<TD>Re-enter student Number</TD>\n<TD>\n");
  printf("<INPUT TYPE\"TEXT\" SIZE=\"20\" NAME=\"re_username\">\n</TD>\n</TR>");

  printf("<TR><TD></TD><TD><INPUT TYPE=\"submit\" NAME=\"button\"
            VALUE=\"Submit\">");
  printf("<INPUT TYPE=\"reset\" VALUE=\"Reset\">");
  printf("</TD></TR>\n");
  printf("</TABLE>\n");
  printf("</FORM>\n");

}


function add_newuser()
{

  global $PHP_SELF;
  global $username, $re_username;
  global $title, $firstname, $surname, $email, $year, $course;

  if(empty($username) || ($username != $re_username))
  {
    die_gracefully("Your student number and confirmed student number did not match or one was empty.");
  } 

  if(empty($title) || empty($surname))
  {
    die_gracefully("The title and surname are compulsory fields");
  }

  // Check the username is not taken
  $query = "SELECT * FROM id WHERE username=" . make_null($username);
  $result = mysql_query($query)
    or print_mysql_error2("Unable to check username uniqueness", $query);

  if(mysql_num_rows($result))
    die_gracefully("That student number is already in the database.");
  mysql_free_result($result);

  // Ok, now try to add the details!
  // First of all to the id table... make a password
  $password = user_make_password();

  $query = "INSERT INTO id (real_name, username, password, user) VALUES(" .
           make_null(($title . " " . $firstname . " " . $surname)) . ", " .
           make_null($username) . ", " . make_null(MD5($password)) . ", 'student')";
  mysql_query($query)
    or print_mysql_error2("Unable to add student details to id table", $query);

  // Fetch the allocated user id
  $student_id = mysql_insert_id();

  // Now update the students table...
  $query = "INSERT INTO students (user_id, year, status) VALUES(" .
           "$student_id, $year, 'Required')";
  mysql_query($query)
    or print_mysql_error2("Unable to add student details to students table", $query);

  // Finally, legacy support, update the cv_pdetails
  $query = "INSERT INTO cv_pdetails (id, surname, firstname, title, email, course)" .
           " VALUES($student_id, " . 
           make_null($surname) . ", " .
           make_null($firstname) . ", " .
           make_null($title) . ", " .
           make_null($email) . ", $course)";
  mysql_query($query)
    or print_mysql_error2("Unable to update cv_pdetails table", $query);

  echo "<H2 ALIGN=\"CENTER\">Success</H2>\n";
  echo "<P ALIGN=\"CENTER\">The new student has been created successfully.";

  // If we have an email address, send the details
  if(!empty($email))
  {
    user_notify_password($email, $title, $firstname, $surname, $username, $password, $student_id);
    echo " They have been emailed their username and password.</P>";
  }
  else
  {
    echo " There is no recorded email address to send the password to automatically." .
         " Therefore the credentials are recorded below</P>";
    echo "<P><PRE>Username : $username\nPassword : $password</PRE></P>\n";
  }
}
?>