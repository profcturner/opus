<?php

/**
**	courses.php
**
** This admin script allows the list of courses the
** students may be on to be edited.
**
** Initial coding : Colin Turner
**
*/

// The include files
include('common.php');
include('authenticate.php');
include('lookup.php');
include('wizard.php');
include('pdp.php');

include('Activities.php');
include('WebServices.php');

// Connect to the database on the server
db_connect()
  or die("Unable to connect to server");

// Authenticate user so that the right people see the right thing
auth_user("admin");


$page = new HTMLOPUS("Edit Courses & Groups", "configuration");  // Calls the function for the header

if(empty($mode)) $mode = Schools_DisplayList;

switch($mode){

  case Schools_DisplayList:
    DisplaySchoolList();
    break;

  case Courses_DisplayList:
    DisplayCourseList();
    break;

  case Schools_AddSchool:
    AddSchool();
    break;

  case Courses_AddCourse:
    AddCourse();
    break;

  case Courses_DeleteCourse:
    DelCourse();
    break;

  case Courses_EditCourse:
    EditCourse();
    break;

  case Schools_EditSchool:
    EditSchool();
    break;

  case Courses_AlterCourse:
    AlterCourse();
    break;

  case AddCourseAdmin:
    AddCourseAdmin();
    break;

  case RemoveCourseAdmin:
    RemoveCourseAdmin();
    break;

  case AddSchoolAdmin:
    AddSchoolAdmin();
    break;

  case RemoveSchoolAdmin:
    RemoveSchoolAdmin();
    break;

  case AddCourseDirector:
    AddCourseDirector();
    break;

  case Courses_UpdateCVGroup:
    Courses_UpdateCVGroup();
    break;

  case Courses_AddAssessmentGroup:
    Courses_AddAssessmentGroup();
    break;

  case Courses_RemoveAssessmentGroup:
    Courses_RemoveAssessmentGroup();
    break;

  // Assessment Group Functions
  case AssessmentGroups_List:
    AssessmentGroups_List();
    break;

  case AssessmentGroups_Insert:
    AssessmentGroups_Insert();
    break;

  case AssessmentGroups_Delete:
    AssessmentGroups_Delete();
    break;

  case AssessmentGroups_Edit:
    AssessmentGroups_Edit();
    break;

  case AssessmentGroups_Update:
    AssessmentGroups_Update();
    break;

  case AssessmentGroups_AddAssessment:
    AssessmentGroups_AddAssessment();
    break;

  case AssessmentGroups_DeleteAssessment:
    AssessmentGroups_DeleteAssessment();
    break;

  case AssessmentGroups_EditAssessment:
    AssessmentGroups_EditAssessment();
    break;

  case AssessmentGroups_UpdateAssessment:
    AssessmentGroups_UpdateAssessment();
    break;

  // CV Group Functions
  case CVGroups_List:
    CVGroups_List();
    break;

  case CVGroups_Insert:
    CVGroups_Insert();
    break;

  case CVGroups_Delete:
    CVGroups_Delete();
    break;

  case CVGroups_Edit:
    CVGroups_Edit();
    break;

  case CVGroups_Update:
    CVGroups_Update();
    break;

  // Channel Functions
  case Channels_List:
    Channels_List();
    break;

  case Channels_Insert:
    Channels_Insert();
    break;

  case Channels_Edit:
    Channels_Edit();
    break;

  case Channels_Delete:
    Channels_Delete();
    break;

  case Channels_Update:
    Channels_Update();
    break;

  case ChannelAssociation_Insert:
    ChannelAssociation_Insert();
    break;

  case  ChannelAssociation_Delete:
    ChannelAssociation_Delete();
    break;

  case ChannelAssociation_MoveUp:
    ChannelAssociation_MoveUp();
    break;

  case ChannelAssociation_MoveDown:
    ChannelAssociation_MoveDown();
    break;

  default:
    die_gracefully("Invalid mode");
    break;
}

// Print the footer and finish the page
$page->end();

function EditCourse()
{
  DisplayCourseForm();
  echo "<HR>\n";
  DisplayCourseDirectors();
  echo "<HR>\n";
  DisplayCourseAdmins();
  echo "<HR>\n";
  DisplaySchoolAdmins();
}


function EditSchool()
{
  DisplaySchoolForm();
  echo "<HR>\n";
  DisplaySchoolAdmins();
}

function AddSchool()
{
  $school_name = $_REQUEST['school_name'];
  $www = $_REQUEST['www'];
  $archive = $_REQUEST['archive'];

  if(!empty($archive))
    $status = "archive";
  else $status="";

  $sql = "insert into schools (school_name, www, status) VALUES(" .
    make_null($school_name) . ", " . make_null($www) . ", " . make_null($status) . ")";
  mysql_query($sql)
    or print_mysql_error2("Unable to add school", $sql);

  Schools_DisplayList();
}


function DisplayCourseAdmins()
{
  global $PHP_SELF;
  global $school_id;
  global $course_id;
  global $showaliens;

  echo "<H3 ALIGN=\"CENTER\">Course Administrators</H3>\n";
  
  $query = "SELECT admins.* " . 
           "FROM admincourse, admins WHERE " .
           "admincourse.admin_id=admins.user_id AND " .
           "admincourse.course_id=$course_id ORDER BY policy_id";

  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch course admin list.", $query);

  if(mysql_num_rows($result))
  {
    echo "<TABLE ALIGN=\"CENTER\" BORDER=\"1\">\n";
    echo "<TR><TH>Options</TH><TH>Position</TH><TH>Name</TH><TH>Security Policy</TH></TR>\n";
    while($row = mysql_fetch_array($result))
    {
      echo "<TR><TD><A HREF=\"$PHP_SELF?mode=RemoveCourseAdmin" .
           "&school_id=$school_id&course_id=$course_id&admin_id=" . 
           $row["user_id"] . "\">Delete</A></TD>";
      echo "<TD>" . htmlspecialchars(get_user_name($row["user_id"])) . "</TD>";
      echo "<TD>" . htmlspecialchars($row["position"]) . "</TD>";
      echo "<TD>" . htmlspecialchars(get_policy_name($row["policy_id"])) . "</TD>";
      echo "</TR>\n";
       
    }
    echo "</TABLE>\n";
  }
  else
  {
    echo "<P ALIGN=\"CENTER\">No course level administrators are defined for this course.</P>\n";
  }
  mysql_free_result($result);

  if(is_root())
  {
    echo "<H4 ALIGN=\"CENTER\">Use the form below to add a new course admin</H4>\n";

    echo "<FORM METHOD=\"POST\" ACTION=\"$PHP_SELF\">\n";
    echo "<INPUT TYPE=\"HIDDEN\" NAME=\"mode\" VALUE=\"AddCourseAdmin\">\n";
    echo "<INPUT TYPE=\"HIDDEN\" NAME=\"course_id\" VALUE=\"$course_id\">\n";

    echo "<TABLE ALIGN=\"CENTER\">\n";
    echo "<TR><TH>Administrator</TH><TH>Security Policy</TH></TR><TR>\n";  

    echo "<TD><SELECT NAME=\"admin_id\">\n";
    $query = "SELECT * FROM admins ORDER BY surname";
  
    $result = mysql_query($query)
      or print_mysql_error2("Unable to query admin table.", $query);

    while($row = mysql_fetch_array($result))
    {
      echo "<OPTION VALUE=\"" . $row["user_id"] . "\">" .
         htmlspecialchars($row["surname"].", ".$row["title"]." ".$row["firstname"]) .
         "</OPTION>\n";
    }
    echo "</SELECT></TD>\n";
    mysql_free_result($result);

    echo "<TD><SELECT NAME=\"policy_id\">\n";
    $query = "SELECT * FROM policy ORDER BY descript";
    $result = mysql_query($query)
      or print_mysql_error2("Unable to check policy table", $query);
    echo "<OPTION VALUE=\"0\">Default Policy for user</OPTION>\n";
    while($row = mysql_fetch_array($result))
    {
      echo "<OPTION VALUE=\"" . $row["policy_id"] . "\">" .
           htmlspecialchars($row["descript"]) .
           "</OPTION>\n";
    }
    echo "</SELECT></TD>\n";
    mysql_free_result($result);
    echo "</TABLE>";

    echo "<P ALIGN=\"CENTER\">" .
         "<INPUT TYPE=\"SUBMIT\" VALUE=\"Add\"></P>\n";
    echo "</FORM>\n";
  }
}


function AddCourseAdmin()
{
  global $school_id;
  global $course_id;
  global $admin_id;
  global $policy_id;
  global $log;


  if(!is_root()) die_gracefully("You do not have permission for this action");
  if($policy_id == 0) $policy_id="NULL";

  $query = "INSERT INTO admincourse VALUES(" . 
           "$admin_id, $course_id, $policy_id)";

  mysql_query($query)
    or print_mysql_error2("Unable to add course admin", $query);
 
  $log['admin']->LogPrint("Added " . get_user_name($admin_id) . " as course admin for " .
                          get_course_name($course_id));
  EditCourse();
}


function RemoveCourseAdmin()
{
  global $school_id;
  global $course_id;
  global $admin_id;
  global $log;

  if(!is_root()) die_gracefully("You do not have permission for this action");
  $query = "DELETE FROM admincourse WHERE admin_id=$admin_id AND course_id=$course_id";
  mysql_query($query)
    or print_mysql_error2("Unable to remove course admin", $query);
 
  $log['admin']->LogPrint("Removed " . get_user_name($admin_id) . " as course admin for " .
                          get_course_name($course_id));
  EditCourse();
}
 


function DisplaySchoolAdmins()
{
  global $PHP_SELF;
  global $school_id;

  echo "<H3 ALIGN=\"CENTER\">School Administrators</H3>\n";
  
  $query = "SELECT admins.* " . 
           "FROM adminschool, admins WHERE " .
           "adminschool.admin_id=admins.user_id AND " .
           "adminschool.school_id=$school_id ORDER BY policy_id";

  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch school admin list.", $query);

  if(mysql_num_rows($result))
  {
    echo "<TABLE ALIGN=\"CENTER\" BORDER=\"1\">\n";
    echo "<TR><TH>Options</TH><TH>Position</TH><TH>Name</TH><TH>Security Policy</TH></TR>\n";
    while($row = mysql_fetch_array($result))
    {
      echo "<TR><TD><A HREF=\"$PHP_SELF?mode=RemoveSchoolAdmin" .
           "&school_id=$school_id&course_id=$course_id&admin_id=" . 
           $row["user_id"] . "\">Delete</A></TD>";
      echo "<TD>" . htmlspecialchars(get_user_name($row["user_id"])) . "</TD>";
      echo "<TD>" . htmlspecialchars($row["position"]) . "</TD>";
      echo "<TD>" . htmlspecialchars(get_policy_name($row["policy_id"])) . "</TD>";
      echo "</TR>\n";
       
    }
    echo "</TABLE>\n";
  }
  else
  {
    echo "<P ALIGN=\"CENTER\">No school level administrators are defined for this course.</P>\n";
  }
  mysql_free_result($result);

  if(is_root())
  {
    echo "<H4 ALIGN=\"CENTER\">Use the form below to add a new school admin</H4>\n";

    echo "<FORM METHOD=\"POST\" ACTION=\"$PHP_SELF\">\n";
    echo "<INPUT TYPE=\"HIDDEN\" NAME=\"mode\" VALUE=\"AddSchoolAdmin\">\n";
    echo "<INPUT TYPE=\"HIDDEN\" NAME=\"school_id\" VALUE=\"$school_id\">\n";
    echo "<INPUT TYPE=\"HIDDEN\" NAME=\"course_id\" VALUE=\"$course_id\">\n";

    echo "<TABLE ALIGN=\"CENTER\">\n";
    echo "<TR><TH>Administrator</TH><TH>Security Policy</TH></TR><TR>\n";  

    echo "<TD><SELECT NAME=\"admin_id\">\n";
    $query = "SELECT * FROM admins ORDER BY surname";
  
    $result = mysql_query($query)
      or print_mysql_error2("Unable to query admin table.", $query);

    while($row = mysql_fetch_array($result))
    {
      echo "<OPTION VALUE=\"" . $row["user_id"] . "\">" .
         htmlspecialchars($row["surname"].", ".$row["title"]." ".$row["firstname"]) .
         "</OPTION>\n";
    }
    echo "</SELECT></TD>\n";
    mysql_free_result($result);

    echo "<TD><SELECT NAME=\"policy_id\">\n";
    $query = "SELECT * FROM policy ORDER BY descript";
    $result = mysql_query($query)
      or print_mysql_error2("Unable to check policy table", $query);
    echo "<OPTION VALUE=\"0\">Default Policy for user</OPTION>\n";
    while($row = mysql_fetch_array($result))
    {
      echo "<OPTION VALUE=\"" . $row["policy_id"] . "\">" .
           htmlspecialchars($row["descript"]) .
           "</OPTION>\n";
    }
    echo "</SELECT></TD>\n";
    mysql_free_result($result);
    echo "</TABLE>";

    echo "<P ALIGN=\"CENTER\">" .
         "<INPUT TYPE=\"SUBMIT\" VALUE=\"Add\"></P>\n";
    echo "</FORM>\n";
  }
}


function AddSchoolAdmin()
{
  global $school_id;
  global $course_id;
  global $admin_id;
  global $policy_id;
  global $log;


  if(!is_root()) die_gracefully("You do not have permission for this action");
  if($policy_id == 0) $policy_id="NULL";

  $query = "INSERT INTO adminschool VALUES(" . 
           "$admin_id, $school_id, $policy_id)";

  mysql_query($query)
    or print_mysql_error2("Unable to add school admin", $query);
 
  $log['admin']->LogPrint("Added " . get_user_name($admin_id) . " as school admin for " .
                          get_course_name($course_id));
  if(!empty($course_id))
    EditCourse();
  else
    EditSchool();
}


function RemoveSchoolAdmin()
{
  global $school_id;
  global $course_id;
  global $admin_id;
  global $log;

  if(!is_root()) die_gracefully("You do not have permission for this action");
  $query = "DELETE FROM adminschool WHERE admin_id=$admin_id AND school_id=$school_id";
  mysql_query($query)
    or print_mysql_error2("Unable to remove school admin", $query);
 
  $log['admin']->LogPrint("Removed " . get_user_name($admin_id) . " as school admin for " .
                          get_course_name($course_id));
  if(!empty($course_id))
    EditCourse();
  else
    EditSchool();
}


function DisplayCourseDirectors()
{
  global $PHP_SELF;
  global $school_id;
  global $course_id;
  global $showaliens;

  echo "<H3 ALIGN=\"CENTER\">Course Directors</H3>\n";
  
  $query = "SELECT staff.*, coursedirectors.policy_id " . 
           "FROM coursedirectors, staff WHERE " .
           "coursedirectors.staff_id=staff.user_id AND " .
           "coursedirectors.course_id=$course_id ORDER BY surname";

  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch course director list.", $query);

  if(mysql_num_rows($result))
  {
    echo "<TABLE ALIGN=\"CENTER\" BORDER=\"1\">\n";
    echo "<TR><TH>Options</TH><TH>Name</TH><TH>Security Policy</TH></TR>\n";
    while($row = mysql_fetch_array($result))
    {
      echo "<TR><TD><A HREF=\"$PHP_SELF?mode=RemoveCourseDirector" .
           "&school_id=$school_id&course_id=$course_id&director_id=" . 
           $row["user_id"] . "\">Delete</A></TD>";
      echo "<TD>" . htmlspecialchars($row["title"] . " " . $row["firstname"] . " " . $row["surname"]) . "</TD>";
      echo "<TD>" . htmlspecialchars(get_policy_name($row["policy_id"])) . "</TD>";
      echo "</TR>\n";
       
    }
    echo "</TABLE>\n";
  }
  else
  {
    echo "<P ALIGN=\"CENTER\">No course directors are defined for this course.</P>\n";
  }
  mysql_free_result($result);

  echo "<H4 ALIGN=\"CENTER\">Use the form below to add a new course director</H4>\n";

  // Now a form to add a new course director...
  echo "<FORM METHOD=\"POST\" ACTION=\"$PHP_SELF\">\n";
  echo "<INPUT TYPE=\"HIDDEN\" NAME=\"mode\" VALUE=\"AddCourseDirector\">\n";
  echo "<INPUT TYPE=\"HIDDEN\" NAME=\"course_id\" VALUE=\"$course_id\">\n";

  echo "<TABLE ALIGN=\"CENTER\">\n";
  echo "<TR><TH>Staff Member</TH><TH>Security Policy</TH></TR><TR>\n";  

  echo "<TD><SELECT NAME=\"director_id\">\n";
  $query = "SELECT * FROM staff ";
  if(!$showaliens) $query .= "WHERE school_id=$school_id ";
  $query .= "ORDER BY surname";
  
  $result = mysql_query($query)
    or print_mysql_error2("Unable to query staff table.", $query);

  while($row = mysql_fetch_array($result))
  {
    echo "<OPTION VALUE=\"" . $row["user_id"] . "\">" .
         htmlspecialchars($row["surname"].", ".$row["title"]." ".$row["firstname"]) .
         "</OPTION>\n";
  }
  echo "</SELECT></TD>\n";
  mysql_free_result($result);

  echo "<TD><SELECT NAME=\"policy_id\">\n";
  $query = "SELECT * FROM policy ORDER BY descript";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to check policy table", $query);
  while($row = mysql_fetch_array($result))
  {
    echo "<OPTION VALUE=\"" . $row["policy_id"] . "\">" .
         htmlspecialchars($row["descript"]) .
         "</OPTION>\n";
  }
  echo "</SELECT></TD>\n";
  mysql_free_result($result);
  echo "</TABLE>";

  echo "<P ALIGN=\"CENTER\">" .
       "<INPUT TYPE=\"SUBMIT\" VALUE=\"Add\"></P>\n";
  echo "</FORM>\n";

  echo "<P ALIGN=\"CENTER\"><A HREF=\"$PHP_SELF?mode=Courses_EditCourse" .
       "&school_id=$school_id&course_id=$course_id";
  if($showaliens)
  {
    echo "\">Click here to hide staff from other schools";
  }
  else
  {
    echo "&showaliens=1\">Click here to show staff from other schools";
  }
  echo "</A></P>\n";
}


function AddCourseDirector()
{
  global $school_id;
  global $course_id;
  global $director_id;
  global $policy_id;
  global $log;


  if(!is_root()) die_gracefully("You do not have permission for this action");

  $query = "INSERT INTO coursedirectors VALUES(" . 
           "$course_id, $director_id, $policy_id)";

  mysql_query($query)
    or print_mysql_error2("Unable to add course director.", $query);
 
  $log['admin']->LogPrint("Added " . get_user_name($director_id) . " as course director for " .
                          get_course_name($course_id));
  EditCourse();
}
  
function DisplaySchoolList()
{
  global $PHP_SELF;
  global $log;
  global $showarchive;

  echo "<H2 ALIGN=\"CENTER\">School List</H2>\n";
  print_wizard("Schools");

  if(!check_default_policy("school", "list"))
    die_gracefully("You do not have permission to view lists of schools");
  

  $query = "SELECT * FROM schools ";
  if(!$showarchive) $query .= "WHERE FIND_IN_SET('archive', status) = 0 ";
  $query .= "ORDER BY school_name";

  $result = mysql_query($query)
    or print_mysql_error2("Unable to obtain school listing", $query);

  $school_num = mysql_num_rows($result);

  if($school_num)
  {
    echo "<TABLE BORDER=\"1\" ALIGN=\"CENTER\">\n";
    while($row=mysql_fetch_array($result))
    {
      echo "<TR><TD>";
      if(strstr($row["status"], "archive")) echo "(*) ";
      echo htmlspecialchars($row['school_name']) .
           "</TD><TD> " .
           "<A HREF=\"$PHP_SELF?mode=Schools_EditSchool&school_id=" . 
           $row['school_id'] . "\">[ Edit School ]</A> " .
           "<A HREF=\"$PHP_SELF?mode=Courses_DisplayList&school_id=" . 
           $row['school_id'] . "\">[ Edit Courses ]</A></TD></TR>\n";
    }
    echo "</TABLE>";
  }
  echo "<P>There are $school_num schools listed.</P>";

  if($showarchive)
  {
    echo "<P ALIGN=\"CENTER\">(*) denotes archived school<BR><A HREF=\"$PHP_SELF\">Hide archived schools</A></P>\n";
  }
  else
  {
    echo "<P ALIGN=\"CENTER\"><A HREF=\"$PHP_SELF?showarchive=1\">Show archived schools</A></P>\n";
  }


  $log['admin']->LogPrint("School list viewed");

  DisplaySchoolForm();
}


/**
**	DisplayCourseList()
**
** Shows the current list of courses, for a
** specific school.
**
*/
function DisplayCourseList()
{
  global $PHP_SELF;
  global $school_id;
  global $log;
  global $showarchive;

  if(!check_default_policy("course", "list"))
    die_gracefully("You do not have permission to view lists of schools");

  echo "<H2 ALIGN=\"CENTER\">Course List</H2>\n";
  print_wizard("Courses");
  echo "<H3 ALIGN=\"CENTER\">" . get_school_name($school_id) . "</H3>\n";

  $query = "SELECT * FROM courses WHERE school_id=$school_id ";
  if(!$showarchive) $query .= "AND FIND_IN_SET('archive', status) = 0 ";
  $query .= "ORDER BY course_code";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to access course list.\n", $query);

  $course_num = mysql_num_rows($result);

  if($course_num)
  {
    echo "<TABLE BORDER=\"1\" ALIGN=\"CENTER\">\n";
    echo "<TR><TH>Options</TH><TH><B>Course Code</B></TH>\n";
    echo "<TH><B>Course Name</B></TH></TR>\n";

    while($row = mysql_fetch_array($result))
    {
      echo "<TR><TD><A HREF=$PHP_SELF?mode=Courses_EditCourse&" .
           "school_id=$school_id&course_id=" . $row["course_id"]. ">Edit</A></TD>";
      echo "<TD>" . htmlspecialchars($row["course_code"]) . "</TD><TD>";
      if(strstr($row["status"], "archive")) echo "(*) ";
      echo htmlspecialchars($row["course_name"]) . "</TD></TR>\n";
    }
  printf("</TABLE>");
  }
  echo "<P>There are $course_num courses listed.</P>\n";

  if($showarchive)
  {
    echo "<P ALIGN=\"CENTER\">(*) denotes archived course<BR><A HREF=\"$PHP_SELF?mode=Courses_DisplayList&school_id=$school_id\">Hide archived courses</A></P>\n";
  }
  else
  {
    echo "<P ALIGN=\"CENTER\"><A HREF=\"$PHP_SELF?mode=Courses_DisplayList&school_id=$school_id&showarchive=1\">Show archived courses</A></P>\n";
  }

  echo "<P ALIGN=\"CENTER\"><A HREF=\"$PHP_SELF\">Back to list of schools</A></P>";

  $log['admin']->LogPrint("Course list (" . get_school_name($school_id) . ") displayed.");

  DisplayCourseForm();
}


function DisplayCourseForm()
{
  global $course_id;
  global $school_id;
  global $PHP_SELF;
  global $log;

  if(!check_default_policy("course", "list"))
    die_gracefully("You do not have permission for this action");

  // If course id is set then we are editing, otherwise we
  // are adding.

  if(!empty($course_id)){
    $query = sprintf("SELECT * FROM courses WHERE course_id=%s", $course_id);
    $result = mysql_query($query)
      or print_mysql_error2("Unable to access course data.\n", $query);

    $row = mysql_fetch_array($result);
    mysql_free_result($result);

    $course_code = $row["course_code"];
    $course_name = $row["course_name"];
    $school_id   = $row["school_id"];
    $archive     = strstr($row["status"], "archive");
    $www         = $row["www"];

    $log['admin']->LogPrint("Course $course_name ($course_code) displayed for editing.");

    echo "<H2 ALIGN=\"CENTER\">Edit Course</H2>\n";
    print_wizard("Courses");
    echo "<H3 ALIGN=\"CENTER\">" . htmlspecialchars(get_school_name($school_id)) . "</H3>\n";
    
    echo "<FORM METHOD=\"post\" ACTION=\"$PHP_SELF?mode=Courses_AlterCourse&school_id=$school_id&course_id=$course_id\">\n";
  }
  else{
    if(empty($school_id)) die_gracefully("No school id is set");
    echo "<H2 ALIGN=\"CENTER\">Add New Course</H2>\n";
    echo "Use this form to create a new course in this school.</P>\n";
    echo "<FORM METHOD=\"post\" ACTION=\"$PHP_SELF?mode=Courses_AddCourse&school_id=$school_id\">\n";
  }

  echo "<TABLE ALIGN=\"CENTER\">";

  if(!empty($course_id)){
    echo "<INPUT TYPE=\"HIDDEN\" NAME=\"course_id\" VALUE=\"$course_id\">";
  }

  printf("<TR><TD>Course Code</TD><TD>");
  printf("<INPUT TYPE=\"TEXT\" SIZE=\"20\" NAME=\"course_code\" VALUE=\"%s\"></TD></TR>\n",
          htmlspecialchars($course_code));
 
  printf("<TR><TD>Course Description</TD><TD>");
  printf("<INPUT TYPE=\"TEXT\" SIZE=\"40\" NAME=\"course_name\" VALUE=\"%s\"></TD></TR>\n",
          htmlspecialchars($course_name));

  printf("<TR><TD>Course Webpage</TD><TD>");
  printf("<INPUT TYPE=\"TEXT\" SIZE=\"40\" NAME=\"www\" VALUE=\"%s\"></TD></TR>\n",
         htmlspecialchars($www));

  echo "<TR><TD>Status</TD><TD>" .
       "Archive <INPUT TYPE=\"CHECKBOX\" NAME=\"archive\"";
  if($archive) echo " CHECKED";
  echo "></TD></TR>\n";

   printf("<TR><TD></TD><TD><INPUT TYPE=\"submit\" NAME=\"button\"
            VALUE=\"Update\">");
  printf("<INPUT TYPE=\"reset\" VALUE=\"Reset\">");
  printf("</TD></TR>\n");
  printf("</TABLE>\n");
  printf("</FORM>\n");

  if(!empty($course_id)){
    printf("<P ALIGN=\"CENTER\"><A HREF=\"%s?mode=%s&school_id=%s&course_id=%s\">",
            $PHP_SELF, Courses_DeleteCourse, $school_id, $course_id);
    printf("Click here to delete this course</A></P>\n");
    echo"<hr />\n";
    Courses_DisplayGroups();
  }
}


function DisplaySchoolForm()
{
  global $school_id;
  global $PHP_SELF;
  global $log;

  if(!check_default_policy("school", "list"))
    die_gracefully("You do not have permission for this action");

  // If course id is set then we are editing, otherwise we
  // are adding.

  if(!empty($school_id)){
    $query = sprintf("SELECT * FROM schools WHERE school_id=%s", $school_id);
    $result = mysql_query($query)
      or print_mysql_error2("Unable to access school data.\n", $query);

    $row = mysql_fetch_array($result);
    mysql_free_result($result);

    $school_name = $row["school_name"];
    $school_id   = $row["school_id"];
    $archive     = strstr($row["status"], "archive");
    $www         = $row["www"];

    $log['admin']->LogPrint("School $school_name displayed for editing.");

    echo "<H2 ALIGN=\"CENTER\">Edit School</H2>\n";
    print_wizard("Schools");
    echo "<H3 ALIGN=\"CENTER\">" . htmlspecialchars(get_school_name($school_id)) . "</H3>\n";
    
    echo "<FORM METHOD=\"post\" ACTION=\"$PHP_SELF?mode=Schools_AlterSchool&school_id=$school_id\">\n";
  }
  else{
    echo "<H2 ALIGN=\"CENTER\">Add New School</H2>\n";
    echo "Use this form to create a new school.</P>\n";
    echo "<FORM METHOD=\"post\" ACTION=\"$PHP_SELF?mode=Schools_AddSchool\">\n";
  }

  echo "<TABLE ALIGN=\"CENTER\">";

  if(!empty($school_id)){
    echo "<INPUT TYPE=\"HIDDEN\" NAME=\"school_id\" VALUE=\"$school_id\">";
  }

  printf("<TR><TD>School Name</TD><TD>");
  printf("<INPUT TYPE=\"TEXT\" SIZE=\"40\" NAME=\"school_name\" VALUE=\"%s\"></TD></TR>\n",
          htmlspecialchars($school_name));

  printf("<TR><TD>School Webpage</TD><TD>");
  printf("<INPUT TYPE=\"TEXT\" SIZE=\"40\" NAME=\"www\" VALUE=\"%s\"></TD></TR>\n",
         htmlspecialchars($www));

  echo "<TR><TD>Status</TD><TD>" .
       "Archive <INPUT TYPE=\"CHECKBOX\" NAME=\"archive\"";
  if($archive) echo " CHECKED";
  echo "></TD></TR>\n";

   printf("<TR><TD></TD><TD><INPUT TYPE=\"submit\" NAME=\"button\"
            VALUE=\"Update\">");
  printf("<INPUT TYPE=\"reset\" VALUE=\"Reset\">");
  printf("</TD></TR>\n");
  printf("</TABLE>\n");
  printf("</FORM>\n");

  if(!empty($school_id)){
    printf("<P ALIGN=\"CENTER\"><A HREF=\"%s?mode=%s&school_id=%s\">",
            $PHP_SELF, Courses_DeleteCourse, $school_id);
    printf("Click here to delete this school</A></P>\n");
  }
}


function AlterCourse()
{
  global $school_id;
  global $course_id;
  global $archive;
  global $course_code;
  global $course_name;
  global $www;

  if(!is_auth_for_school($school_id, "course", "edit") &&
     !is_auth_for_course($course_id, "course", "edit"))
    die_gracefully("You do not have permission to edit this course");

  if(empty($course_id))
    die_gracefully("This page should not be accessed without a course id.\n");

  $status="";
  if(!empty($archive)) $status="archive";

  $query = "UPDATE courses SET " .
           "  course_code = " . make_null($course_code) .
           ", course_name = " . make_null($course_name) .
           ", www = " . make_null($www) .
           ", status = '$status'" .
           " WHERE course_id=$course_id";

  mysql_query($query)
    or print_mysql_error2("Unable to update course.\n", $query);

  printf("<P ALIGN=\"CENTER\">Course %s (%s) has been updated.</P>\n",
         htmlspecialchars($course_code),
         htmlspecialchars($course_name));

  DisplayCourseList();

}



function AddCourse()
{
  global $course_code;
  global $course_name;
  global $school_id;
  global $archive;
  global $www;
  global $log;

  if(empty($school_id)) die_gracefully("A school must be defined for this course");

  if(!is_auth_for_school($school_id, "course", "create") &&
     !is_auth_for_course($course_id, "course", "create"))
    die_gracefully("You do not have permission to add this course");

  // Check the course code is unique
  $query = sprintf("SELECT * FROM courses WHERE course_code=%s", make_null($course_code));
  $result = mysql_query($query)
    or print_mysql_error2("Unable to access course data.\n", $query);

  if(mysql_num_rows($result)){
    printf("<P ALIGN=\"CENTER\">The course code %s", $course_code);
    printf("already exists in the database and this entry cannot be created.</P>\n");
    die_gracefully("");
  }
  mysql_free_result($result);
  $course_xml = WebServices::get_course($course_code, substr(get_academic_year(), 2), 1);
  //echo "Debug: " . $course_xml->programme_code . "\n";
  if($course_xml->programme_code == $course_code)
  {
    // Ok, we have some webservices info, augment if needed
    if(empty($course_name))
      $course_name = $course_xml->programme_title;
  }

  $status="";
  if(!empty($archive)) $status="archive";

  $query = "INSERT INTO courses (school_id, course_code, course_name, www, status) " .
           "VALUES($school_id, " . make_null($course_code) . ", " .
           make_null($course_name) . ", " .
           make_null($www) . ", '$status')";

  mysql_query($query)
    or print_mysql_error2("Unable to add course.\n", $query);

  printf("<P ALIGN=\"CENTER\">Course %s (%s) has been added to the database.</P>\n",
         htmlspecialchars($course_code),
         htmlspecialchars($course_name));

  $log['admin']->LogPrint("A new course $course_code ($course_name) has been added.");

  DisplayCourseList();

}


function DelCourse()
{
  global $PHP_SELF;
  global $school_id;
  global $course_id;
  global $confirmed;
  global $log;

  if(!is_root()) die_gracefully("Only super admin users have permission to delete courses");

  // Fetch the other course data
  $query = sprintf("SELECT * FROM courses WHERE course_id=%s", $course_id);
  $result = mysql_query($query)
    or print_mysql_error("Unable to access course data.\n");

  $row = mysql_fetch_array($result);
  mysql_free_result($result);


  // This had better be checked
  if($confirmed != "TRUE"){
    printf("<H2 ALIGN=\"CENTER\">Are You Sure ?</H2>\n");

    printf("<P ALIGN=\"CENTER\">You have selected to delete the ");
    printf("course %s (%s).\n",
           htmlspecialchars($row["course_code"]),
           htmlspecialchars($row["course_name"]));
    printf("Normally courses should not be deleted from the database (but archived).</P>");

    printf("<P ALIGN=\"CENTER\"><A HREF=\"%s?mode=%s&school_id=%s&course_id=%s&confirmed=TRUE\">",
            $PHP_SELF, Courses_DeleteCourse, $school_id, $course_id);
    printf("Click here to confirm</A></P>\n");

    return;
  }
  
  $log_string = get_course_name($course_id);

  // Ok, it has been checked.
  $query = sprintf("DELETE FROM courses WHERE course_id=%s", $course_id);
  mysql_query($query)
    or print_mysql_error("Could not delete course.\n");

  printf("<P ALIGN=\"CENTER\">You have deleted the course ");
  printf("%s (%s).\n",
         htmlspecialchars($row["course_code"]),
         htmlspecialchars($row["course_name"]));

  $log['admin']->LogPrint("Course $log_string (id $course_id) has been deleted.");

  DisplayCourseList();
}


/**
* Displays group (CV and Assessment) membership for a given course
*/
function Courses_DisplayGroups()
{
  global $smarty;
  global $log;

  //print_wizard("Courses");
  
  $course_id = (int) $_REQUEST['course_id'];
  $school_id = (int) $_REQUEST['school_id'];
  
  // Get CV Group, this is a simpler query
  $cv_group_id = backend_lookup("cvgroupcourse", "group_id", "course_id", $course_id);
  if(!$cv_group_id) $cv_group_id=1; // Default group
  $sql = "select * from cvgroups order by name";
  $result = mysql_query($sql)
    or print_mysql_error2("Unable to get cv group list", $sql);
  $cv_groups = array();
  while($cv_group = mysql_fetch_array($result))
  {
    // Build an array for use in the template showing all cv groups, indexed by id
    $cv_groups[$cv_group["group_id"]] = $cv_group["name"];
  }
  mysql_free_result($result);

  // Assessment Group membership is more complex, since it is tracked over time
  $sql = "select assessmentgroups.name, assessmentgroupcourse.* from assessmentgroupcourse " .
    "left join assessmentgroups on assessmentgroupcourse.group_id = assessmentgroups.group_id " .
    "where course_id = $course_id order by startyear, endyear";
  $result = mysql_query($sql)
    or print_mysql_error2("Unable to fetch assessment group membership for course", $sql);
    
  $assessment_groups = array();
  while($assessment_group = mysql_fetch_array($result))
  {
    array_push($assessment_groups, $assessment_group);
  }
  
  $smarty->assign("course_id", $course_id);
  $smarty->assign("school_id", $school_id);
  $smarty->assign("cv_groups", $cv_groups);
  $smarty->assign("cv_group_id", $cv_group_id);
  $smarty->assign("assessment_groups", $assessment_groups);
  $smarty->assign("all_assessment_groups", get_indexed_assessmentgroup_array());
  $smarty->assign("number_assessment_groups", count($assessment_groups));
  $smarty->display("admin/courses/courses_display_groups.tpl");
}


/**
* updates the cv group for a given course
*/
function Courses_UpdateCVGroup()
{
  $course_id = (int) $_REQUEST['course_id'];
  $group_id = (int) $_REQUEST['group_id'];

  if(!is_auth_for_course($course_id, "cvgroup", "edit"))
    die_gracefully("You do not have permission to edit this cvgroup");
    
  $sql = "delete from cvgroupcourse where course_id = $course_id";
  mysql_query($sql)
    or print_mysql_error2("Unable to delete cv group association", $sql);

  $sql = "insert into cvgroupcourse (group_id, course_id) values($group_id, $course_id)";
  mysql_query($sql)
    or print_mysql_error2("Unable to add cv group association", $sql);

  EditCourse();
}


/**
* Adds an assessment group for a given course
*/
function Courses_AddAssessmentGroup()
{
  $course_id = (int) $_REQUEST['course_id'];
  $group_id = (int) $_REQUEST['group_id'];
  $startyear = $_REQUEST['startyear'];
  $endyear = $_REQUEST['endyear'];

  if(!is_auth_for_course($course_id, "assessmentgroup", "edit"))
    die_gracefully("You do not have permission to edit this assessment group");

  $sql = "insert into assessmentgroupcourse (group_id, course_id, startyear, endyear) values($group_id, $course_id, " .
    make_null($startyear) . ", " . make_null($endyear) . ")";
  mysql_query($sql)
    or print_mysql_error2("Unable to add assessment group", $sql);

  EditCourse();
}


/**
* Removes an assessment group for a given course
*/
function Courses_RemoveAssessmentGroup()
{
  $id = (int) $_REQUEST['id'];

  if(!is_auth_for_course($course_id, "assessmentgroup", "edit"))
    die_gracefully("You do not have permission to edit this assessment group");

  $sql = "delete from assessmentgroupcourse where id=$id";
  mysql_query($sql)
    or print_mysql_error2("Unable to remove assessment group", $sql);

  EditCourse();
}


/**
* Lists all available CV Groups
*/
function AssessmentGroups_List()
{
  global $smarty;
  global $log;

  if(!check_default_policy("assessmentgroup", "list"))
    die_gracefully("You do not have permission to list the assessment groups");

  print_wizard("Assessment Groups");

  $query = "SELECT * FROM assessmentgroups ORDER BY name";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch assessment groups", $query);

  $assessment_groups = array();
  while($row = mysql_fetch_array($result))
  {
    array_push($assessment_groups, $row);
  }
  $log['admin']->LogPrint("CV Group Listed");
  $smarty->assign("assessment_groups", $assessment_groups);
  $smarty->display("admin/courses/list_assessment_groups.tpl");
}


/**
* Inserts a new assessment group, with no comment
*/
function AssessmentGroups_Insert()
{
  global $smarty;
  global $log;

  if(!check_default_policy("assessmentgroup", "create"))
    die_gracefully("You do not have permission to create assessment groups");


  $name  = $_REQUEST['name'];
  if(empty($name)) die_gracefully("You must specify a name to add a new group");

  $query = "insert into assessmentgroups (name) values(" .
    make_null($name) . ")";
  mysql_query($query)
    or print_mysql_error2("Unable to add new group", $query);

  $log['admin']->LogPrint("A new assessment group ($name) added");
  AssessmentGroups_List();
}


/**
* Produces the dialog to edit an Assessment group
*/
function AssessmentGroups_Edit()
{
  global $smarty;
  global $log;
  
  if(!check_default_policy("assessmentgroup", "list"))
    die_gracefully("You do not have permission to list the assessment groups");

  print_wizard("Assessment Groups");

  // Get information on the current group
  $group_id = (int) $_REQUEST['group_id'];
  $query = "select * from assessmentgroups where group_id=$group_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch Assessment group information", $query);

  $group_info = mysql_fetch_array($result);
  mysql_free_result($result);

  // Get the assessment regime for this group
  $query = "select * from assessmentregime where group_id=$group_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch assessment regime information", $query);

  $total_weight = 0;
  $assessments = array();
  while($assessment = mysql_fetch_array($result))
  {
    $total_weight += $assessment['weighting'];
    array_push($assessments, $assessment);
  }
  mysql_free_result($result);

  // Get all possible assessments
  $query = "select * from assessment order by description";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch other assessments", $query);

  $possible_assessments = array();
  while($possible_assessment = mysql_fetch_array($result))
  {
    array_push($possible_assessments, $possible_assessment);
  }
  mysql_free_result($result);

  $log['admin']->LogPrint("Assessment Group " . $group_info['name'] . " information viewed");
  $smarty->assign("total_weight", $total_weight);
  $smarty->assign("group_info", $group_info);
  $smarty->assign("assessments", $assessments);
  $smarty->assign("possible_assessments", $possible_assessments);
  $smarty->display("admin/courses/edit_assessment_group.tpl");
}


/**
* Acts upon the output of AssessmentGroups_Edit()
* @see AssessmentGroups_Edit()
*/
function AssessmentGroups_Update()
{
  global $smarty;
  global $log;
  
  if(!check_default_policy("assessmentgroup", "edit"))
    die_gracefully("You do not have permission to edit this assessment group");

  $group_id = (int) $_REQUEST['group_id'];
  $name = $_REQUEST['name'];
  $comments = $_REQUEST['comments'];
  
  if(empty($group_id))
    die_gracefully("You must specify a group_id");
    
  if(empty($name))
    die_gracefully("Your assessment group name must not be blank");
    
  $query = "update assessmentgroups set " .
    "name = " . make_null($name) . ", " .
    "comments = " . make_null($comments) . " where group_id=$group_id";
    
  mysql_query($query)
    or print_mysql_error2("Unable to update assessment group");

  $log['admin']->LogPrint("Assessment Group $name updated");
  AssessmentGroups_List();
}


/**
* Deletes a Assessment group, should not normally be done
*/
function AssessmentGroups_Delete()
{
  global $smarty;
  global $log;

  if(!check_default_policy("assessmentgroup", "delete"))
    die_gracefully("You do not have permission to edit this assessment group");

  $group_id   = (int) $_REQUEST['group_id'];
  $confirmed = (bool) $_REQUEST['confirmed'];

  if(empty($group_id))
  {
    die_gracefully("You must specify a group_id");
  }
  $group_name = backend_lookup("assessmentgroups", "name", "group_id", $group_id);
  $smarty->assign("group_id", $group_id);
  $smarty->assign("group_name", $group_name);

  if(!$confirmed)
  {
    $smarty->display("admin/courses/delete_assessment_groups.tpl");
    return;
  }
  $query = "delete from assessmentgroups where group_id=$group_id";
  mysql_query($query)
    or print_mysql_error2("Unable to delete assessment group", $query);

  $log['admin']->LogPrint("An assessment group ($group_name) was deleted.");
  AssessmentGroups_List();
}


/**
* Adds an assessment to the regime of an assessment group
*/
function AssessmentGroups_AddAssessment()
{
  global $smarty;
  global $log;

  if(!check_default_policy("assessmentgroup", "edit"))
    die_gracefully("You do not have permission to edit this assessment group");

  $group_id = $_REQUEST['group_id'];
  $start = $_REQUEST['start'];
  $end = $_REQUEST['end'];
  $year = (int) $_REQUEST['year'];
  $assessor = $_REQUEST['assessor'];
  $assessment_id = (int) $_REQUEST['assessment_id'];
  $weighting = (float) $_REQUEST['weighting'];
  $student_description = $_REQUEST['student_description'];

  if(empty($group_id)) die_gracefully("A group id must be specified");

  $query = "insert into assessmentregime (group_id, assessment_id, start, end, assessor, weighting, year, student_description) " .
    "values($group_id, $assessment_id, " . make_null($start) . ", " . make_null($end) . ", " . make_null($assessor) . ", " . $weighting .
    ", $year,  " . make_null($student_description) . ")";
  mysql_query($query)
    or print_mysql_error2("Unable to add assessment to regime", $query);

  $log['admin']->LogPrint("An assessment ($assessment_id)  was added to group ($group_id)");
  AssessmentGroups_Edit();
}


/**
* Deletes an assessment from an assessment regime
*/
function AssessmentGroups_DeleteAssessment()
{
  global $smarty;
  global $log;

  if(!check_default_policy("assessmentgroup", "edit"))
    die_gracefully("You do not have permission to edit this assessment group");

  // Get CGI variables
  $group_id   = (int) $_REQUEST['group_id'];
  $cassessment_id = (int) $_REQUEST['cassessment_id'];
  $confirmed = (bool) $_REQUEST['confirmed'];

  if(empty($group_id) || empty($cassessment_id))
  {
    die_gracefully("You must specify a group_id and cassessment_id");
  }
  $group_name = backend_lookup("assessmentgroups", "name", "group_id", $group_id);
  $assessment_name = backend_lookup("assessmentregime", "student_description", "cassessment_id", $cassessment_id);
  
  $smarty->assign("group_id", $group_id);
  $smarty->assign("group_name", $group_name);
  $smarty->assign("cassessment_id", $cassessment_id);
  $smarty->assign("assessment_name", $assessment_name);

  if(!$confirmed)
  {
    print_wizard("Assessment Groups");
    $smarty->display("admin/courses/delete_assessment_instance.tpl");
    return;
  }
  $query = "delete from assessmentregime where group_id=$group_id and " .
    "cassessment_id=$cassessment_id";
  mysql_query($query)
    or print_mysql_error2("Unable to delete assessment instance", $query);

  $log['admin']->LogPrint("An assessment $assessment_name (id $cassessment_id) was deleted " .
    "from assessment group $group_name");
  AssessmentGroups_Edit();
}

/**
* Edits an assessment from an assessment regime
*/
function AssessmentGroups_EditAssessment()
{
  global $smarty;
  global $log;

  if(!check_default_policy("assessmentgroup", "edit"))
    die_gracefully("You do not have permission to edit this assessment group");

  print_wizard("Assessment Groups");

  // Get CGI variables
  $group_id   = (int) $_REQUEST['group_id'];
  $cassessment_id = (int) $_REQUEST['cassessment_id'];

  if(empty($group_id) || empty($cassessment_id))
  {
    die_gracefully("You must specify a group_id and cassessment_id");
  }
  $group_name = backend_lookup("assessmentgroups", "name", "group_id", $group_id);
  $assessment_name = backend_lookup("assessmentregime", "student_description", "cassessment_id", $cassessment_id);
  
  $query = "select * from assessmentregime where cassessment_id=$cassessment_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch assessmentregime information");
  $assessment_info = mysql_fetch_array($result);
  mysql_free_result($result);
  
  $assessor_options = 
    array('student','academic','industrial','other');
    
  $assessment_years = array();
  $assessment_years["-1"] = "pre-placement year (-1)";
  $assessment_years["0"] = "placement year (0)";
  $assessment_years["1"] = "post-placement year";
  
  $smarty->assign("group_id", $group_id);
  $smarty->assign("group_name", $group_name);
  $smarty->assign("cassessment_id", $cassessment_id);
  $smarty->assign("assessment_name", $assessment_name);
  $smarty->assign("assessment_info", $assessment_info);
  $smarty->assign("assessor_options", $assessor_options);
  $smarty->assign("assessment_years", $assessment_years); 

  $smarty->display("admin/courses/edit_assessment_instance.tpl");
  $log['admin']->LogPrint("An assessment $assessment_name (id $cassessment_id) " .
    "from assessment group $group_name was viewed for editing.");
}


/**
* Updates an assessment instance for a given group
*/
function AssessmentGroups_UpdateAssessment()
{
  global $smarty;
  global $log;

  if(!check_default_policy("assessmentgroup", "edit"))
    die_gracefully("You do not have permission to edit this assessment group");

  // Get CGI variables
  $group_id   = (int) $_REQUEST['group_id'];
  $cassessment_id = (int) $_REQUEST['cassessment_id'];
  $student_description = $_REQUEST['student_description'];
  $assessor = $_REQUEST['assessor'];
  $weighting = (float) $_REQUEST['weighting'];
  $year = (int) $_REQUEST['year'];
  $start = $_REQUEST['start'];
  $end = $_REQUEST['end'];
  $outcomes = $_REQUEST['outcomes'];
  $options = $_REQUEST['options'];

  if(empty($group_id) || empty($cassessment_id))
  {
    die_gracefully("You must specify a group_id and cassessment_id");
  }
  $group_name = backend_lookup("assessmentgroups", "name", "group_id", $group_id);
  
  $query = "update assessmentregime set " .
    "student_description = " . make_null($student_description) . ", " .
    "assessor = " . make_null($assessor) . ", " .
    "start = " . make_null($start) . ", " .
    "end = " . make_null($end) . ", " .
    "outcomes = " . make_null($outcomes) . ", " .
    "weighting = $weighting, year = $year where cassessment_id = $cassessment_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to update assessment instance");
  
  $log['admin']->LogPrint("An assessment $student_description (id $cassessment_id) " .
    "from assessment group $group_name was updated.");
  AssessmentGroups_Edit();
}


/**
* Lists all available CV Groups
*/
function CVGroups_List()
{
  global $smarty;
  global $log;
  
  if(!check_default_policy("cvgroup", "list"))
    die_gracefully("You do not have permission to list the cv groups");

  print_wizard("CV Groups");

  $query = "SELECT * FROM cvgroups ORDER BY name";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch CV groups", $query);

  $cv_groups = array();
  while($row = mysql_fetch_array($result))
  {
    array_push($cv_groups, $row);
  }
  $log['admin']->LogPrint("CV Group Listed");
  $smarty->assign("cv_groups", $cv_groups);
  $smarty->display("admin/courses/list_cv_groups.tpl");
}


/**
* Inserts a new CV group, with no comment
*/
function CVGroups_Insert()
{
  global $smarty;
  global $log;

  if(!check_default_policy("cvgroup", "create"))
    die_gracefully("You do not have permission to create new cv groups");


  $name  = $_REQUEST['name'];
  if(empty($name)) die_gracefully("You must specify a name to add a new group");

  $query = "insert into cvgroups (name) values(" .
    make_null($name) . ")";
  mysql_query($query)
    or print_mysql_error2("Unable to add new group", $query);

  $log['admin']->LogPrint("A new cv group ($name) added");
  CVGroups_List();
}


/**
* Deletes a CV group, should not normally be done
*/
function CVGroups_Delete()
{
  global $smarty;
  global $log;
  
  if(!check_default_policy("cvgroup", "delete"))
    die_gracefully("You do not have permission to delete cv groups");  

  $group_id   = (int) $_REQUEST['group_id'];
  $confirmed = (bool) $_REQUEST['confirmed'];

  if(empty($group_id))
  {
    die_gracefully("You must specify a group_id");
  }
  $group_name = backend_lookup("cvgroups", "name", "group_id", $group_id);
  $smarty->assign("group_id", $group_id);
  $smarty->assign("group_name", $group_name);

  if(!$confirmed)
  {
    $smarty->display("admin/courses/delete_cv_groups.tpl");
    return;
  }
  $query = "delete from cvgroups where group_id=$group_id";
  mysql_query($query)
    or print_mysql_error2("Unable to delete cv group", $query);

  $log['admin']->LogPrint("A CV group ($group_name) was deleted.");
  CVGroups_List();
}


/**
* produces the dialogs to edit the basics of a CV group and its permissions in OPUS
*/
function CVGroups_Edit()
{
  global $smarty;
  global $log;

  if(!check_default_policy("cvgroup", "list"))
    die_gracefully("You do not have permission to list the cv groups");

  print_wizard("CV Groups");

  // Get information on the current group
  $group_id = (int) $_REQUEST['group_id'];
  $query = "select * from cvgroups where group_id=$group_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch CV group information", $query);

  $group_info = mysql_fetch_array($result);
  mysql_free_result($result);

  // Get all the templates available from the PDSystem, take off the wrapper :-).
  $pdp_templates_object = PDSystem::get_cv_templates();
  $pdp_templates = $pdp_templates_object->xpath('//template');

  // Explore any template restrictions from the OPUS side of things
  $query = "select * from cvgrouptemplate where group_id=$group_id order by template_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch CV group information", $query);

  $opus_permissions = array();
  while($template = mysql_fetch_array($result))
  {
    $opus_permission = array();

    if(strpos($template[settings], "allow") === false) $opus_permission['allow'] = false;
    else $opus_permission['allow'] = true;
    if(strpos($template[settings], "requiresApproval") === false) $opus_permission['requiresApproval'] = false;
    else $opus_permission['requiresApproval'] = true;

    $opus_permissions[$template["template_id"]] = $opus_permission;
  }
  mysql_free_result($result);

  if(strpos($group_info['permissions'], "allowCustom") === false) $allowCustom = false;
  else $allowCustom = true;

  $log['admin']->LogPrint("CV Group " . $group_info['name'] . " information viewed");
  $smarty->assign("group_info", $group_info);
  $smarty->assign("allowCustom", $allowCustom);
  $smarty->assign("pdp_templates", $pdp_templates);
  $smarty->assign("opus_permissions", $opus_permissions);
  $smarty->display("admin/courses/edit_cv_group.tpl");
}


/**
* Takes the output from CVGroups_Edit() and acts upon it
* @see CVGroups_Edit()
* 
*/
function CVGroups_Update()
{
  global $smarty;
  global $log;
  
  if(!check_default_policy("cvgroup", "edit"))
    die_gracefully("You do not have permission to list the cv groups");

  $group_id = (int) $_REQUEST["group_id"];
  $name = $_REQUEST["name"];
  $comments = $_REQUEST["comments"];
  $default_template = $_REQUEST["default_template"];
  $allowCustom = $_REQUEST["allowCustom"];
  
  if(empty($default_template)) die_gracefully("You must specify a default template");

  if(!empty($allowCustom)) $permissions="allowCustom";

  // Modify the core group information
  $query = "update cvgroups set " .
    "name=" . make_null($name) . ",  " .
    "comments=" . make_null($comments) . ", " .
    "default_template=$default_template, " .
    "permissions=" . make_null($permissions) . " " .
    "where group_id=$group_id";
  mysql_query($query)
    or print_mysql_error2("Unable to update CV group information", $query);

  // Remove all existing template permission information
  $query = "delete from cvgrouptemplate where group_id=$group_id";
  mysql_query($query)
    or print_mysql_error2("Unable to delete old permission information", $query); 

  // Insert new template permission information
  foreach($_POST as $key => $value)
  {
    // if the left of the variable name is tallow then record this
    if(substr($key, 0, 6) == 'tallow')
    {
      $settings = "allow";
      $template_id = substr($key, 7);
  
      // In that case, also check if
      // if the left of the variable name is tapprove then record this
      if(isset($_POST["tapprove_$template_id"]))
        $settings .= ",requiresApproval";

      $query = "insert into cvgrouptemplate (group_id, template_id, settings) " .
        "values($group_id, $template_id, " . make_null($settings) . ")";
      mysql_query($query)
        or print_mysql_error2("Unable to update permission information", $query);
    }
  }

  $log['admin']->LogPrint("CV Group $name updated");
  CVGroups_List();
}


/**
* Lists all the channels available (used for communication with students)
*/
function Channels_List()
{
  global $smarty;
  global $log;
  
  if(!check_default_policy("channel", "list"))
    die_gracefully("You do not have permission to list the channels");

  print_wizard("Channels");

  $sql = "select * from channels order by name";
  $result = mysql_query($sql)
    or print_mysql_error2("Unable to obtain channel listing", $sql);

  $channels = array();
  while($channel = mysql_fetch_array($result))
  {
    array_push($channels, $channel);
  }
  
  $smarty->assign("channels", $channels);
  $smarty->display("admin/courses/list_channels.tpl");
  $log['admin']->LogPrint("Channels listed");
}


/**
* Inserts a new channel, with no description
*/
function Channels_Insert()
{
  global $smarty;
  global $log;

  $name  = $_REQUEST['name'];
  if(empty($name)) die_gracefully("You must specify a name to add a new channel");
  $name = trim($name);
  if(strpos($name, " ")) die_gracefully("Your channel name must not contain a space");

  $query = "insert into channels (name) values(" .
    make_null($name) . ")";
  mysql_query($query)
    or print_mysql_error2("Unable to add new channel", $query);

  $log['admin']->LogPrint("A new channel ($name) added");
  Channels_List();
}


/**
* produces the dialogs to edit the basics of a Channel
*/
function Channels_Edit()
{
  global $smarty;
  global $log;

  if(!check_default_policy("channel", "list"))
    die_gracefully("You do not have permission to list the channels");

  print_wizard("Channels");

  // Get information on the current channel
  $channel_id = (int) $_REQUEST['channel_id'];
  $query = "select * from channels where channel_id=$channel_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch channel information", $query);

  $channel_info = mysql_fetch_array($result);
  mysql_free_result($result);

  // Get all the associations for the channel as well
  $query = "select * from channelassociations where channel_id = $channel_id order by priority";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch channel associations", $query);

  $associations = array();
  while($association = mysql_fetch_array($result))
  {
    // Augment with human readable name
    switch($association['type'])
    {
      case "course" : $name = backend_lookup("courses", "course_name", "course_id", $association['object_id']); break;
      case "school" : $name = backend_lookup("schools", "school_name", "school_id", $association['object_id']); break;
      case "assessmentgroup" : $name = backend_lookup("assessmentgroups", "name", "group_id", $association['object_id']); break;
      case "activity" : $name = backend_lookup("vacancytype", "name", "vacancy_id", $association['object_id']); break;
      default: $name="Unknown object type"; break;
    }
    $association['name'] = $name;
    array_push($associations, $association);
  }
  mysql_free_result($result);

  $smarty->assign("channel_info", $channel_info);
  $smarty->assign("associations", $associations);
  $smarty->assign("courses", get_indexed_course_array());
  $smarty->assign("schools", get_indexed_school_array());
  $smarty->assign("assessment_groups", get_indexed_assessmentgroup_array());
  $smarty->assign("activities", Activities::get_indexed_array());
  $smarty->display("admin/courses/edit_channel.tpl");
  $log['admin']->LogPrint("Channel " . $channel_info['name'] . " information viewed for editing");
}


/**
* updates the channel information
* @see Channels_Edit
*/
function Channels_Update()
{
  global $smarty;
  global $log;
  
  if(!check_default_policy("channel", "edit"))
    die_gracefully("You do not have permission to edit this channel");


  $channel_id = (int) $_REQUEST['channel_id'];
  $name = $_REQUEST['name'];
  $description = $_REQUEST['description'];

  $name = trim($name);
  if(empty($name) || strpos($name, " ")) die_gracefully("The name must not be blank or contain spaces");

  $sql = "update channels set " .
    "name = " . make_null($name) . ", " .
    "description = " . make_null($description) .
    " where channel_id=$channel_id";

  mysql_query($sql)
    or print_mysql_error2("Unable to update channel information", $sql);

  Channels_List();
}


/**
* Deletes a channel, should not normally be done
*/
function Channels_Delete()
{
  global $smarty;
  global $log;
  
  if(!check_default_policy("channel", "delete"))
    die_gracefully("You do not have permission to delete a channel");


  $channel_id   = (int) $_REQUEST['channel_id'];
  $confirmed = (bool) $_REQUEST['confirmed'];

  if(empty($channel_id))
  {
    die_gracefully("You must specify a channel_id");
  }
  $channel_name = backend_lookup("channels", "name", "channel_id", $channel_id);
  $smarty->assign("channel_id", $channel_id);
  $smarty->assign("channel_name", $channel_name);

  if(!$confirmed)
  {
    $smarty->display("admin/courses/delete_channels.tpl");
    return;
  }
  $query = "delete from channels where channel_id=$channel_id";
  mysql_query($query)
    or print_mysql_error2("Unable to delete channel group", $query);

  $log['admin']->LogPrint("A channel ($channel_name) was deleted.");
  Channels_List();
}


/**
* adds an association to the given channel
*/
function ChannelAssociation_Insert()
{
  $channel_id = (int) $_REQUEST['channel_id'];
  $object_id = $_REQUEST['object_id'];
  $type = $_REQUEST['type'];
  $permission = $_REQUEST['permission'];
  
  if(!check_default_policy("channel", "edit"))
    die_gracefully("You do not have permission to edit this channel");
  

  // Can't get the priorities entangled
  ChannelAssociation_Lock();

  // What's the new priority
  $query = "SELECT MAX(priority) FROM channelassociations " .
           "WHERE channel_id=$channel_id";
  $result = mysql_query($query);
  $row = mysql_fetch_row($result);
  mysql_free_result($result);
  $priority = $row[0] + 1;

  // Ok, do the insert
  $query = "insert into channelassociations (permission, type, object_id, priority, channel_id) " .
    "values(" . make_null($permission) . ", " . make_null($type) . ", " . 
    "$object_id, $priority, $channel_id)";
  mysql_query($query)
    or print_mysql_error2("could not add new association", $query);

  // Don't forget to unlock the table
  ChannelAssociation_Unlock();

  Channels_Edit();
}


/**
* removes an association from a given channel (without challenge)
*/
function ChannelAssociation_Delete()
{
  $channel_id = (int) $_REQUEST['channel_id'];
  $association_id = (int) $_REQUEST['association_id'];

  if(!check_default_policy("channel", "edit"))
    die_gracefully("You do not have permission to edit this channel");


  $sql = "delete from channelassociations where id=$association_id";
  mysql_query($sql)
    or print_mysql_error2("Unable to delete channel association", $sql);

  Channels_Edit();
}


/**
* Moves a given association on a channel up one notch (without challenge)
*/
function ChannelAssociation_MoveUp()
{
  $channel_id = (int) $_REQUEST['channel_id'];
  $association_id = (int) $_REQUEST['association_id'];

  if(!check_default_policy("channel", "edit"))
    die_gracefully("You do not have permission to edit this channel");


  ChannelAssociation_Lock();
  $sql = "select * from channelassociations where channel_id=$channel_id order by priority";
  $result = mysql_query($sql)
    or print_mysql_error2("Unable to fetch association order", $sql);

  // Record the previous row, and when we find the specific one
  $previous_priority = 0;
  $found = false;
  while(!$found && $association = mysql_fetch_array($result))
  {
    if($association["id"] == $association_id)
    {
      $current_priority = $association["priority"];
      $found = true;
    }
    else $previous_priority = $association["priority"];
  }
  mysql_free_result($result);


  // We found the row, and a previous one exists
  if($found && $previous_priority)
  {
    ChannelAssociation_SwapRows($channel_id, $current_priority, $previous_priority);
  }
  ChannelAssociation_Unlock();

  Channels_Edit();
}


/**
* Moves a given association on a channel down one notch (without challenge)
*/
function ChannelAssociation_MoveDown()
{
  $channel_id = (int) $_REQUEST['channel_id'];
  $association_id = (int) $_REQUEST['association_id'];

  if(!check_default_policy("channel", "edit"))
    die_gracefully("You do not have permission to edit this channel");


  ChannelAssociation_Lock();
  $sql = "select * from channelassociations where channel_id=$channel_id order by priority DESC";
  $result = mysql_query($sql)
    or print_mysql_error2("Unable to fetch association order", $sql);

  // Record the previous row, and when we find the specific one
  $previous_priority = 0;
  $found = false;
  while(!$found && $association = mysql_fetch_array($result))
  {
    if($association["id"] == $association_id)
    {
      $current_priority = $association["priority"];
      $found = true;
    }
    else $previous_priority = $association["priority"];
  }
  mysql_free_result($result);

  // We found the row, and a previous one exists
  if($found && $previous_priority)
  {
    ChannelAssociation_SwapRows($channel_id, $current_priority, $previous_priority);
  }
  ChannelAssociation_Unlock();

  Channels_Edit();
}


/**
* locks the channel association table so we can safely change priority
*/
function ChannelAssociation_Lock()
{
  // The priority cannot get corrupted while we do this...
  $query = "LOCK TABLES channelassociations WRITE";
  mysql_query($query)
    or print_mysql_error2("Unable to lock channel associations table.", $query);
}


/**
* swaps two entries (by priority) in the associations for a channels
* @param integer $channel_id the unique channel id
* @param integer $priority1 the priority of the first row in the swap
* @param integer $priority2 the priority of the second row in the swap
* @see ChannelAssociation_MoveUp
* @see ChannelAssociation_MoveDown
*/
function ChannelAssociation_SwapRows($channel_id, $priority1, $priority2)
{
  $query = "UPDATE channelassociations SET priority=0 " .
           "WHERE priority=$priority1 AND channel_id=$channel_id";
  mysql_query($query)
    or print_mysql_error2("Unable to update association position.", $query);

  $query = "UPDATE channelassociations SET priority=$priority1 " .
           "WHERE priority=$priority2 AND channel_id=$channel_id";
  mysql_query($query)
    or print_mysql_error2("Unable to update association position.", $query);

  $query = "UPDATE channelassociations SET priority=$priority2 " .
           "WHERE priority=0 AND channel_id=$channel_id";
  mysql_query($query)
    or print_mysql_error2("Unable to update association position.", $query);
}


/**
* unlocks the channel association table
*/
function ChannelAssociation_Unlock()
{
  // The priority cannot get corrupted while we do this...
  $query = "UNLOCK TABLES";
  mysql_query($query)
    or print_mysql_error2("Unable to unlock channel associations table.", $query);
}


function get_indexed_course_array()
{
  $query = "select * from courses order by course_code";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to get courses", $query);

  $courses = array();
  while($course = mysql_fetch_array($result))
  {
    $courses[$course["course_id"]] = $course["course_code"] . " : " . $course["course_name"];
  }
  mysql_free_result($result);
  return($courses);
}


function get_indexed_school_array()
{
  $query = "select * from schools order by school_name";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to get schools", $query);

  $schools = array();
  while($school = mysql_fetch_array($result))
  {
    $schools[$school["school_id"]] = $school["school_name"];
  }
  mysql_free_result($result);
  return($schools);
}


function get_indexed_assessmentgroup_array()
{
  $query = "select * from assessmentgroups order by name";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to get assessment groups", $query);

  $groups = array();
  while($group = mysql_fetch_array($result))
  {
    $groups[$group["group_id"]] = $group["name"];
  }
  mysql_free_result($result);
  return($groups);
}


/**
**	Prints the tabs over the various parts of the page
**
*/
function print_wizard($item)
{
  global $school_id;
  global $smarty;

  $wizard = new TabbedContainer($smarty, "tabs");
  $wizard->addTab("Schools", $_SERVER['PHP_SELF']);
  $wizard->addTab("CV Groups", $_SERVER['PHP_SELF'] . "?mode=CVGroups_List");
  $wizard->addTab("Assessment Groups", $_SERVER['PHP_SELF'] . "?mode=AssessmentGroups_List");
  $wizard->addTab("Channels", $_SERVER['PHP_SELF'] . "?mode=Channels_List");

  if(!empty($school_id))
  {
    $wizard->addTab("Courses", $_SERVER['PHP_SELF'] . "?mode=Courses_DisplayList&school_id=$school_id");
  }

  // Transitionary code
  echo "<div name=\"tabbedContainer\" align=\"center\">\n";
  $wizard->displayTab($item);
  echo "</div>\n";


}

?>