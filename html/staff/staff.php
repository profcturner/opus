<?php

/**
** staff.php
**
** Allows staff members (and administrators) to configure
** basic staff information.
**
** Initial coding : Colin Turner
**
*/

// The include files 
include('common.php');		
include('authenticate.php');
include('lookup.php');	
include('wizard.php');
include('users.php');
include('assessment.php');

// Connect to the database on the server
db_connect()
  or die("Unable to connect to server");
  
// Authenticate user so that the right people see the right thing
auth_user("staff");

$page = new HTMLOPUS("Staff Directory", "directories");

// Ordinary users can only view themselves
if(is_staff()){
  $user_id = get_id();
  if(empty($mode)) $mode = DisplayStudents;

  if(!empty($student_id))
  {
    if(get_academic_tutor($student_id) != get_id())
      die_gracefully("Sorry, you do not have permission to access this student.");
  }
}

if(!empty($user_id))
{
  // Fetch the school information for this user
  $query = "SELECT school_id FROM staff WHERE user_id=$user_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch school id.", $query);
  $row = mysql_fetch_array($result);
  $school_id=$row[0];
  mysql_free_result($result);
}

if(is_admin()){
  if(empty($mode) && !empty($user_id)) $mode = BasicEdit;
  if(empty($mode)) $mode = SimpleSearch;
}
           

// Getting into the right mode for the right job
switch($mode)
{

  case SimpleSearch:
    staff_simplesearch();
    break;

  case ShowSearch:
    staff_showsearch();
    break;

  case AdvancedSearchForm:
    staff_advancedsearchform();
    break;

  case AdvancedSearch:
    staff_advancedsearch();
    break;

  case BasicEdit:
    staff_basicedit();
    break;

  case BasicUpdate:
    staff_basicupdate();
    break;

  case DisplayStudents:
    staff_displaystudents();
    break;

  case StudentDisplay:
    staff_studentdisplay();
    break;

  case StartAdd:
    staff_startadd();
    break;

  case Add:
    staff_add();
    break;

  case Delete:
    staff_delete();
    break;

  case NewPassword:
    staff_newpassword();
    break;

  case JustOnce:
    just_once();
    break;

  default:
    echo "Invalid mode...";
    break;

}

// Print the footer and finish the page
$page->end();


function staff_simplesearch()
{
  global $PHP_SELF;

  echo "<H2 ALIGN=\"CENTER\">Staff Directory</H2>\n";

  printf("<P ALIGN=\"CENTER\">Select the first letter of a surname below.</P>\n");

  if(!is_admin())
    die_gracefully("You are not permitted to perform this action");

  printf("<P ALIGN=\"CENTER\">\n");
  for($loop = ord('A'); $loop <= ord('Z'); $loop++){
    printf("<A HREF=\"%s?mode=ShowSearch&letter=%s\">%s</A> ", 
      $PHP_SELF, chr($loop), chr($loop));
  }
  printf("<A HREF=\"%s?mode=ShowSearch&letter=ALL\">ALL</A>", $PHP_SELF);
  printf("</P>\n");

  printf("<P ALIGN=\"CENTER\"><A HREF=\"$PHP_SELF?mode=AdvancedSearchForm\">" .
         "Advanced Search</A></P>\n");

  printf("<P ALIGN=\"CENTER\"><A HREF=\"$PHP_SELF?mode=StartAdd\">" .
         "Add a new staff member</A></P>\n");

}


/*
**      staff_advancedsearchform
**
** Provides a flexible form for sophisticated searching of
** the staff directory.
**
*/
function staff_advancedsearchform()
{
  global $PHP_SELF;
  global $showarchive;
  global $log;

  if(!is_admin())
    die_gracefully("You are not permitted to perform this action");

echo "<script language=\"JavaScript\" type=\"text/javascript\">\n" .
     "<!--\n\n" .
     "function toggleAll(school, checked)\n" .
     "{\n" .
     "  for (i = 0; i < document.search.elements.length; i++) {\n" .
     "    if(school)\n" .
     "    {" .
     "      if(document.search.elements[i].value == school) \n" .
     "         document.search.elements[i].checked = checked;\n" .
     "    }\n" .
     "    else{\n" .
     "      if (document.search.elements[i].name.indexOf('sc') >= 0) {\n" .
     "          document.search.elements[i].checked = checked;\n" .
     "      }\n".
     "    }\n" .
     "  }\n" .
     "}\n" .
     "// -->\n" .
     "</script>\n";



  echo "<H2 ALIGN=\"CENTER\">Staff Directory</H2>\n";
  echo "<H3 ALIGN=\"CENTER\">Advanced Search</H3>\n";

  echo "<FORM METHOD=\"POST\" NAME=\"search\" ACTION=\"" . $PHP_SELF . "\">\n";


  echo "<TABLE ALIGN=\"CENTER\">\n";

  echo "<TR><TH ALIGN=\"CENTER\" COLSPAN=2><B>Search criteria</B></TH></TR>\n";
  echo "<TR><TH>Name fragment (if any)</TH>\n";
  echo "<TD><INPUT TYPE=\"TEXT\" NAME=\"search\" SIZE=\"20\"></TD></TR>\n";

  // Provide select all, select none links...
  echo "<TR><TH ALIGN=\"CENTER\" COLSPAN=\"2\">Shows staff from these schools ";
  echo "<a href=\"\" onclick=\"toggleAll(0, true); return false;\" " .
       "onmouseover=\"status='Select all'; return true;\">Select all</a> " .
       " | <a href=\"\" onclick=\"toggleAll(0, false); return false;\" " .
       "onmouseover=\"status='Select none'; return true;\">Select none</a></TH></TR>\n";

  // Fetch list of schools...
  $query  = "SELECT * FROM schools ORDER BY school_name";
  $result = mysql_query($query)
    or print_mysql_error("Unable to fetch school list.", $query);

  while($row = mysql_fetch_array($result)){
    $auth_for_school = is_auth_for_school($row['school_id'], "staff", "list");

    // Look further if the school isn't hidden by reason of being archived
    if(!(strstr($row["status"], "archive") && !$showarchive))
    {
      if($auth_for_school)
      {
        echo "<TR>\n";
        echo "<TD COLSPAN=\"2\">";
        echo "<INPUT TYPE=\"CHECKBOX\" NAME=\"sc" . $row["school_id"];
        echo "\" CHECKED> ";
        if(strstr($row["status"], "archive")) echo "(A) ";
        echo htmlspecialchars($row["school_name"]);
        echo "</TD>\n";
        echo "</TR>\n";
      }
    }
  }

  echo "<TR><TH ALIGN=\"CENTER\" COLSPAN=2><B>Sort criteria</B></TH></TR>\n";
  echo "<TR><TD ALIGN=\"CENTER\" COLSPAN=2>";
  echo "<INPUT TYPE=\"RADIO\" NAME=\"sort\" VALUE=\"name\" CHECKED> Name";
  echo "<INPUT TYPE=\"RADIO\" NAME=\"sort\" VALUE=\"access\"> Last Access";
  echo "</TD></TR>\n";

  echo "<TR><TD ALIGN=\"CENTER\" COLSPAN=2>";
  echo "<INPUT TYPE=\"HIDDEN\" NAME=\"mode\" VALUE=\"AdvancedSearch\">\n";
  echo "<INPUT TYPE=\"submit\" NAME=\"button\" VALUE=\"Search\">\n";
  echo "<INPUT TYPE=\"reset\" VALUE=\"Reset\">\n";
  echo "</TD></TR>\n";

  echo "</TABLE>\n";

  echo "<P ALIGN=\"CENTER\"><A HREF=\"$PHP_SELF?mode=AdvancedSearchForm";
  if($showarchive)
  {
    echo "\">Hide archived schools (shown by (A))";
  }
  else
  {
    echo "&showarchive=1\">Show archived schools";
  }
  echo "</A></P>\n";

}


function staff_showsearch()
{
  global $PHP_SELF;
  global $letter;
  global $log;

  if(!is_admin())
    die_gracefully("You do not have permission to access this page.");
  
  staff_simplesearch();

  echo "<H3 ALIGN=\"CENTER\">Search Result</H3>\n";

  // Form Search criteria string
  $query = "SELECT DISTINCT staff.*, id.last_time FROM id " .
           "LEFT JOIN staff ON staff.user_id = id.id_number " .
           "WHERE id.user = 'staff' ";
  if($letter!='ALL') $query .= "AND LEFT(surname, 1) = '$letter'";
  $query .= " ORDER BY surname";

  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch staff list", $query);
  
  if($letter!='ALL')
    printf("<H3 ALIGN=\"CENTER\">Staff with Name Starting with %s</H3>\n", $letter);


  // If there are no entries then say so...  
  if(!mysql_num_rows($result)){
    printf("<H2 ALIGN=\"CENTER\">No Matches Found</H2>");
    
    printf("<P ALIGN=\"CENTER\">No staff members could be found to match ");
    printf("the search criteria.</P>");
  
    $page->end();
    exit(0);
  }

  // Keep a count of what we are showing
  $staff_shown = 0;
  echo "<TABLE ALIGN=\"CENTER\" BORDER=\"1\">\n";
  echo "<TR><TH>Name</TH><TH>Last access</TH></TR>\n";
  while($row = mysql_fetch_array($result)){
    if(is_auth_for_school($row['school_id'], 'staff', 'list'))
    {
      echo "<TR><TD><A HREF=\"" . $PHP_SELF .
           "?mode=BasicEdit&school_id=" . $row["school_id"] . "&user_id=" .
           $row["user_id"] . "\">" . htmlspecialchars($row["title"]) .
           " " . htmlspecialchars($row["firstname"]) .
           " " . htmlspecialchars($row["surname"]) .
           "</A></TD>";
      echo "<TD>" . $row["last_time"] . "</TD></TR>\n";
      $staff_shown++;
    }
  }
  printf("</TABLE>\n");

  printf("<HR>\n");

  echo "<P>$staff_shown staff members met your search criterion.</P>\n";
 
  $log['access']->LogPrint("staff search performed.");

}


/*
**      staff_advancedsearch
**
** This script actually performs the advanced search that
** was configured with the above function.
*/
function staff_advancedsearch()
{
  global $search;         // Search field (empty is ALL)
  global $sort;           // Sort field
  global $log;            // Reference to the log field
  global $showarchive;    // SHow archived members of staff
  global $HTTP_POST_VARS; // All the POST variables (for checking course info)
  global $_POST;          // See above
  global $conf;           // Configuration
  global $PHP_SELF;       // Reference to this script

  if(!is_admin())
    die_gracefully("You are not permitted to perform this action");

  echo "<H3 ALIGN=\"CENTER\">Advanced Search</H3>\n";

  // Form Search criteria string
  if(!empty($search)){
    $searchc .= " (staff.firstname LIKE '%$search%' OR " .
                " staff.surname LIKE '%$search%')";
  }

  // Form Sort criteria string
  $sortc = " ORDER BY staff.surname";
  if($sort == 'access') $sortc = " ORDER BY last_time DESC, staff.surname";

  // Form basic query
  $query = "SELECT DISTINCT id.*, staff.* FROM " .
           "id, staff WHERE id.user='staff' AND " .
           "id.id_number=staff.user_id ";

  // Search Criteria (Refining where given)
  if(!empty($searchc)) $query .= " AND" . $searchc;

  // Sort Criteria
  $query .= $sortc;

  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch staff list", $query);

  // If there are no entries then say so...(if we know this already!)
  if(!mysql_num_rows($result)){
    printf("<H2 ALIGN=\"CENTER\">No Matches Found</H2>");

    printf("<P ALIGN=\"CENTER\">No staff  could be found to match ");
    printf("the search criteria.</P>");
    return;
  }

  // Keep a count of what we are showing
  $staff_shown = 0;
  echo "<TABLE ALIGN=\"CENTER\" BORDER=\"1\">\n";
  echo "<TR><TH>Name</TH><TH>Last access</TH></TR>\n";
  while($row = mysql_fetch_array($result)){
    $school_valid=FALSE;
    $sc = "sc" . $row["school_id"];
    if(!empty($_POST[$sc])) $school_valid=TRUE;
    if($school_valid && is_auth_for_school($row['school_id'], 'staff', 'list'))
    {
      echo "<TR><TD><A HREF=\"" . $PHP_SELF .
           "?mode=BasicEdit&school_id=" . $row["school_id"] . "&user_id=" .
           $row["user_id"] . "\">" . htmlspecialchars($row["title"]) .
           " " . htmlspecialchars($row["firstname"]) .
           " " . htmlspecialchars($row["surname"]) .
           "</A></TD>";
      echo "<TD>" . $row["last_time"] . "</TD></TR>\n";
      $staff_shown++;
    }
  }
  printf("</TABLE>\n");

  printf("<HR>\n");

  echo "<P>$staff_shown staff members met your search criterion.</P>\n";

  $log['admin']->LogPrint("advanced search launched on staff directory");

}



function staff_basicedit()
{
  global $PHP_SELF;
  global $school_id;
  global $user_id;
  global $conf;
  global $log;


  $query = "SELECT * FROM staff WHERE user_id=$user_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch staff data.", $query);

  $row = mysql_fetch_array($result);

  echo "<H2 ALIGN=\"CENTER\">" . 
       htmlspecialchars($row["title"] . " " . $row["firstname"] . " " . $row["surname"]) .
       "</H2>\n";
  echo "<H3 ALIGN=\"CENTER\">Basic Details</H3>\n";
  print_wizard("Basics");

  if(is_admin() && !is_auth_for_school($school_id, "staff", "list"))
    die_gracefully("You do not have permission to view this member of staff");

  staff_basicform($row);

  $user_name = backend_lookup("id", "username", "id_number", $user_id);
  echo "<P ALIGN=\"CENTER\">";
  echo "<A HREF=\"" . $conf['scripts']['user']['photos'] . 
       "?mode=full&user_id=" . $user_name . "\">" .
       "<IMG ALIGN=\"CENTER\" BORDER=\"0\" ALT=\"Photo\" SRC=\"" . 
       $conf['scripts']['user']['photos'] .
       "?user_id=" . $user_name . "\"></A><BR></P>\n";


  $log['access']->LogPrint("Basic details for staff member " . 
                           $row["title"] . " " .
                           $row["firstname"] . " " .
                           $row["surname"] . "viewed.");

  if(is_admin()){
    echo "<P ALIGN=\"CENTER\">";

    if(is_root())
    {
      echo "<A HREF=\"$PHP_SELF?mode=Delete&user_id=$user_id\">" .
           "Click here to delete this staff member.</A><BR>\n";
    }
    echo "<A HREF=\"$PHP_SELF?mode=NewPassword&user_id=$user_id\">" .
         "Click here to send a new password to this staff member.</A></P>\n";
  }
}


function staff_basicform($row)
{
  global $PHP_SELF;
  global $user_id;

  echo "<FORM METHOD=\"POST\" ";
  echo "ACTION=\"" . $PHP_SELF;

  if(!empty($user_id))
    echo "?mode=BasicUpdate&user_id=$user_id";
  else
    echo "?mode=Add";

  echo "\">\n";
  
  echo "<TABLE ALIGN=\"CENTER\">\n";

  echo "<TR><TH>Title</TH><TD>" .
       "<INPUT TYPE=\"TEXT\" NAME=\"title\" SIZE=\"10\" VALUE=\"" .
       htmlspecialchars($row["title"]) . "\"></TD></TR>\n";

  echo "<TR><TH>First name</TH><TD>" .
       "<INPUT TYPE=\"TEXT\" NAME=\"firstname\" SIZE=\"30\" VALUE=\"" .
       htmlspecialchars($row["firstname"]) . "\"></TD></TR>\n";

  echo "<TR><TH>Surname</TH><TD>" .
       "<INPUT TYPE=\"TEXT\" NAME=\"surname\" SIZE=\"30\" VALUE=\"" .
       htmlspecialchars($row["surname"]) . "\"></TD></TR>\n";

  echo "<TR><TH>Position</TH><TD>" .
       "<INPUT TYPE=\"TEXT\" NAME=\"position\" SIZE=\"30\" VALUE=\"" .
       htmlspecialchars($row["position"]) . "\"></TD></TR>\n";
 
  echo "<TR><TH>Room Number</TH><TD>" .
       "<INPUT TYPE=\"TEXT\" NAME=\"room\" SIZE=\"10\" VALUE=\"" .
       htmlspecialchars($row["room"]) . "\"></TD></TR>\n";

  echo "<TR><TH>School</TH><TD>" .
       "<SELECT NAME=\"school_id\"> ";

  $school_query = "SELECT * FROM schools ORDER by school_name";
  $school_result = mysql_query($school_query)
    or print_mysql_error2("Unable to fetch school list", $school_query);

  while($srow = mysql_fetch_array($school_result))
  {
    if($srow["school_id"] == $row["school_id"] ||
       is_auth_for_school($srow["school_id"], "staff", "create"))
    {
      echo "<OPTION VALUE=\"" . $srow["school_id"] . "\"";
      if($srow["school_id"] == $row["school_id"]) echo " SELECTED";
      echo ">";
      echo htmlspecialchars($srow["school_name"]);
      echo "</OPTION>\n";
    }
  }
  mysql_free_result($school_result);
  echo "</SELECT></TD></TR>\n";

  echo "<TR><TH>Department</TH><TD>" .
       "<INPUT TYPE=\"TEXT\" NAME=\"department\" SIZE=\"40\" VALUE=\"" .
       htmlspecialchars($row["department"]) . "\"></TD></TR>\n";

  echo "<TR><TH>Address</TH><TD>" .
       "<INPUT TYPE=\"TEXT\" NAME=\"address\" SIZE=\"40\" VALUE=\"" .
       htmlspecialchars($row["address"]) . "\"></TD></TR>\n";

  echo "<TR><TH>Phone</TH><TD>" .
       "<INPUT TYPE=\"TEXT\" NAME=\"voice\" SIZE=\"30\" VALUE=\"" .
       htmlspecialchars($row["voice"]) . "\"></TD></TR>\n";

  echo "<TR><TH>Email</TH><TD>" .
       "<INPUT TYPE=\"TEXT\" NAME=\"email\" SIZE=\"50\" VALUE=\"" .
       htmlspecialchars($row["email"]) . "\"></TD></TR>\n";

  if(empty($user_id))
  {
    // user related fields
    echo "<TR><TH>Username</TH><TD>" .
         "<INPUT TYPE=\"TEXT\" NAME=\"username\" SIZE=\"10\" VALUE=\"auto\"></TD></TR>\n";

    echo "<TR><TH>Real Name</TH><TD>" .
         "<INPUT TYPE=\"TEXT\" NAME=\"realname\" SIZE=\"30\" VALUE=\"auto\"></TD></TR>\n";


    echo "<INPUT TYPE=\"HIDDEN\" NAME=\"password\" SIZE=\"10\" VALUE=\"auto\"></TD></TR>\n";
    echo "<INPUT TYPE=\"HIDDEN\" NAME=\"cpassword\" SIZE=\"10\" VALUE=\"auto\"></TD></TR>\n";
  }

  echo "<TR><TD COLSPAN=\"2\" ALIGN=\"CENTER\">";
  echo "<INPUT TYPE=\"submit\" NAME=\"button\" VALUE=\"Submit\">";
  echo "<INPUT TYPE=\"reset\" VALUE=\"Reset\"></TD></TR>\n";


  echo "</TABLE>\n";
  echo "</FORM>\n";
}


function staff_basicupdate()
{
  global $title, $firstname, $surname;
  global $position, $voice, $email, $room;
  global $address, $department, $school_id;
  global $user_id;


  if(empty($user_id))
    die_gracefully("You cannot access this page without a user id.");
  
  if(empty($school_id))
    die_gracefully("You cannot access this page without a school id.");

  if(is_admin() && !is_auth_for_school($school_id, "staff", "edit"))
    die_gracefully("You do not have permission to edit this staff member.");

  // Form the query
  $query = "UPDATE staff SET" .
             "  title="      . make_null($title) .
             ", firstname="  . make_null($firstname) .
             ", surname="    . make_null($surname) .
             ", position="   . make_null($position) .
             ", address="    . make_null($address) .
             ", department=" . make_null($department) .
             ", voice="      . make_null($voice) .
             ", room="       . make_null($room) .
             ", email="      . make_null($email) .
             ", school_id="  . make_null($school_id) .
             " WHERE user_id=" . $user_id;

  // Now try it
  mysql_query($query)
    or print_mysql_error2("Unable to update staff record.", $query);

  echo "<H2 ALIGN=\"CENTER\">Staff record updated</H2>\n";

  staff_basicedit();
}

function staff_get_otherstudents()
{
  global $conf;
  global $smarty;
  global $year;
  global $user_id;

  // Works out what "other" students we might have to assess
  $query = "select * from assessorother where assessor_id=" . $user_id;
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch other assessments", $query);

  $assessments = array();
  while($row = mysql_fetch_array($result))
  {
    // Check it's the correct year...
    if($year == get_placement_year($row['assessed_id']))
    {
      // Ok, so let's start to build a picture, augment the row
      $row['user_name'] = get_user_name($row['assessed_id']);
      $row['assessment_description'] = get_cassessment_description($row['cassessment_id']);
      // get any mark present...
      $sql = "select percentage from assessmenttotals where assessed_id=" . $row['assessed_id'] .
	" and cassessment_id=" . $row['cassessment_id'];
      $result2 = mysql_query($sql)
        or print_mysql_error2("Unable to fetch total information", $sql);
      $row2 = mysql_fetch_array($result2);
      $row['percentage'] = $row2['percentage'];
      mysql_free_result($result2);
      if($row['percentage']) $row['percentage'] .= "%";
      else $row['percentage'] = "--";
      array_push($assessments, $row);
    }
  }
  mysql_free_result($result);

  $smarty->assign("other_assessments", $assessments);
  $smarty->display("staff/other_assessments.tpl");

}


function staff_displaystudents()
{
  global $conf;
  global $log;
  global $year;
  global $user_id;
  global $school_id;
  global $PHP_SELF;

  if(empty($user_id))
    die_gracefully("This page cannot be accessed without a user id.");
  
  if(empty($school_id))
    die_gracefully("You cannot access this page without a school id.");

  if(is_admin() && !is_auth_for_school($school_id, "staff", "list"))
    die_gracefully("You do not have permission to view this information.");


  if(empty($year)) $year = get_academic_year();

  $query = "SELECT * FROM staff WHERE user_id=" . $user_id;
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch staff information.", $query);
  $staff = mysql_fetch_array($result);
  mysql_free_result($result);


  echo "<H2 ALIGN=\"CENTER\">" .
       htmlspecialchars($staff['title'] . " " . $staff['firstname'] . " " . $staff['surname']) .
       "</H2>\n";

  echo "<H3 ALIGN=\"CENTER\">Student List for year (" . $year . " - " . ($year+1) . ")</H3>\n";
  echo "<p>You are <b>Academic Tutor</b> for the following students.</p>";

  print_wizard("Students");

  $query = "SELECT staffstudent.student_id, title, firstname" .
           ", surname FROM staffstudent, cv_pdetails LEFT JOIN students ON " .
           "students.user_id=staffstudent.student_id WHERE staffstudent.student_id=" .
           "cv_pdetails.id AND staffstudent.staff_id=$user_id AND students.year=$year";

  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch current student list.", $query);

  if(mysql_num_rows($result)){
    echo "<TABLE ALIGN=\"CENTER\" BORDER=\"1\">\n";
    echo "<tr><th>Student</th><th>Company</th></tr>\n";
    while($row = mysql_fetch_array($result))
    {
       echo "<TR><TD><A HREF=\"" . $PHP_SELF .
            "?mode=StudentDisplay&student_id=" . $row["student_id"] . 
            "&school_id=$school_id&user_id=" . $user_id . "\">" .
            htmlspecialchars($row["title"] . " " . $row["firstname"] . " " . $row["surname"]) 
             .
            "</A></TD><TD>\n";

       // fetch and display some placement info...
       $p_query = "SELECT name, locality FROM placement, companies " .
                  "WHERE placement.company_id = companies.company_id AND " .
                  "placement.student_id=" . $row["student_id"];
       $p_result = mysql_query($p_query)
         or print_mysql_error2("Unable to fetch placement info.", $p_query);
       if(mysql_num_rows($p_result)){
         $p_row = mysql_fetch_array($p_result);
         echo htmlspecialchars($p_row["name"] . "(" . $p_row["locality"] . ")");
       }
       mysql_free_result($p_result);
       echo "</TD></TR>\n";
    }
    echo "</TABLE>\n";
  }
  else{
    echo "<P>No students are allocated to this member of staff as yet (for this year).</P>\n";
  }
  staff_get_otherstudents();

  echo "<FORM METHOD=\"POST\" ACTION=\"$PHP_SELF?mode=DisplayStudents" .
       "&school_id=$school_id&user_id=$user_id\">\nTo see students seeking placement " .
       "starting in a different year click here " .
       "<INPUT TYPE=\"TEXT\" SIZE=\"4\" NAME=\"year\" VALUE=\"$year\">\n" .
       "<INPUT TYPE=\"submit\" NAME=\"button\" VALUE=\"Submit\">\n" .
       "</FORM>\n";



  $log['access']->LogPrint("student list for " . 
                           $staff['title'] . " " . $staff['firstname'] . " " .
                           $staff['surname'] . " for year " . $year . " viewed.");
}


function staff_studentdisplay()
{
  global $user_id;
  global $school_id;
  global $student_id;
  global $conf;
  global $log;
  global $smarty;

  $query = "SELECT * FROM staffstudent WHERE " .
           "staff_id=$user_id AND student_id=$student_id";
  $result = mysql_query($query)
    or print_mysql_error2("Cannot check staff student link", $query);
  if(!mysql_num_rows($result))
  {
    $log['security']->LogPrint("Invalid student " .
      get_user_name($student_id) . " for staff member " .
      get_user_name($user_id));
    die_gracefully("Invalid student for this staff member.");
  }
  mysql_free_result($result);

  if(is_admin())
  {
    if(!(is_auth_for_school($school_id, "staff", "list") &&
       is_auth_for_student($student_id, "student", "viewStatus")))
      die_gracefully("You do not have permission for this action"); 
  }

  $query = "SELECT * FROM cv_pdetails WHERE id=" . $student_id;
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch cv details", $query);
  $student = mysql_fetch_array($result);
  mysql_free_result($result);

  $placements = array();
  $query = "SELECT * FROM placement WHERE student_id=$student_id " .
    "order by created";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch placement details", $query);
  while($placement = mysql_fetch_array($result))
  {
    // Augment with information
    $placement['company_name'] = get_company_name($placement['company_id']);
    $placement['vacancy_description'] =
      get_vacancy_description($placement['vacancy_id']);
    // Company details (for address)
    $sub_query = "select * from companies where company_id=" .
      $placement['company_id'];
    $sub_result = mysql_query($sub_query)
      or print_mysql_error2("Unable to get company info", $sub_query);
    $company_info = mysql_fetch_array($sub_result);
    mysql_free_result($sub_result);

    // HR contact details (from vacancy)
    if($placement['vacancy_id'])
    {
      $sub_query = "select contact_id from vacancies where " .
	"vacancy_id=" . $placement['vacancy_id'];
      $sub_result = mysql_query($sub_query)
	or print_mysql_error2("Unable to get contact id", $sub_query);
      $temp_info = mysql_fetch_array($sub_result);
      mysql_free_result($sub_result);
      $contact_id = $temp_info['contact_id'];
    }
    else
    {
      $sub_query = "select contacts.contact_id from contacts left join " .
	"companycontact on contacts.contact_id = companycontact.contact_id " .
	"where companycontact.status='primary' and company_id=" .
	$placement['company_id'];
      $sub_result = mysql_query($sub_query)
	or print_mysql_error2("Unable to get contact id", $sub_query);
      $temp_info = mysql_fetch_array($sub_result);
      mysql_free_result($sub_result);
      $contact_id = $temp_info['contact_id'];
    }
    if($contact_id)
    {
      $sub_query = "select * from contacts where contact_id=$contact_id";
      $sub_result = mysql_query($sub_query)
	or print_mysql_error2("Unable to get company info", $sub_query);
      $contact_info = mysql_fetch_array($sub_result);
    }
      
    $placement['contact'] = $contact_info;
    $placement['company'] = $company_info;

    array_push($placements, $placement);
  }
  mysql_free_result($result);

  $query = "SELECT companies.* FROM companies, placement WHERE " .
           "companies.company_id = placement.company_id AND " .
           "placement.student_id = " . $student_id;
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch company information", $query);
  $company = mysql_fetch_array($result);
  mysql_free_result($result);

  $query = "SELECT * FROM staff WHERE user_id=" . $user_id;
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch staff information", $query);
  $staff = mysql_fetch_array($result);
  mysql_free_result($result);

  $smarty->assign("staff", $staff);
  $smarty->assign("placements", $placements);


 echo "<H2 ALIGN=\"CENTER\">" .
       htmlspecialchars($staff['title'] . " " . $staff['firstname'] . " " . $staff['surname']) .
       "</H2>\n";

  echo "<H3 ALIGN=\"CENTER\">" .
       htmlspecialchars($student["title"] . " " . $student["firstname"] . " " . $student["surname"]) .
       "</H3>\n";

  print_wizard("Students");

  echo "<H3 ALIGN=\"CENTER\">Student Details</H3>\n";

  echo "<P ALIGN=\"CENTER\">";

  echo "<A HREF=\"" . $conf['scripts']['user']['photos'] . 
       "?mode=full&user_id=" . $student['student_id'] . "\">" .
       "<IMG ALIGN=\"CENTER\" BORDER=\"0\" ALT=\"Photo\" SRC=\"" . 
       $conf['scripts']['user']['photos'] .
       "?user_id=" . $student['student_id'] . "\"></A><BR>\n";

  echo "Student Id : " . htmlspecialchars($student["student_id"]) . "<BR>\n";
  echo "Date of Birth : " . htmlspecialchars($student["dob"]);
  if(!empty($student["pob"])) echo " (" . htmlspecialchars($student["pob"]) . ")";
  echo "<BR>\n";

  if(!empty($student["email"]))
    echo "Regular Email : <A HREF=\"mailto:" .
         $student["email"] . "\">" . htmlspecialchars($student["email"]) . "</A><BR>\n";
  echo "Course : " . htmlspecialchars(get_course_name($student["course"])) . "<BR>\n";
  echo "Course start and end (YYMM) : " . 
       htmlspecialchars($student["course_start"] . " - " . $student["course_end"]) . "<BR>\n";
  echo "Expected grade : " . htmlspecialchars($student["expected_grade"]) . "<BR>\n";


  if(!empty($placement["position"]))
    echo "Placement Position : " . htmlspecialchars($placement["position"]) . "<BR>\n";
  if(!empty($placement["start"]))
    echo "Placement Start Date : " . htmlspecialchars($placement["start"]) . "<BR>\n";
  if(!empty($placement["voice"]))
    echo "Placement Phone : " . htmlspecialchars($placement["voice"]) . "<BR>\n";
  if(!empty($placement["email"]))
    echo "Placement Email : <A HREF=\"mailto:" .
         $placement["email"] . "\">" . htmlspecialchars($placement["email"]) . "</A><BR>\n";

  $smarty->display("staff/staff_display_student.tpl");

  assessment_regime($student_id);
}


function staff_startadd()
{
  global $log;

  echo "<H2 ALIGN=\"CENTER\">Adding a new staff member</H2>\n";
  echo "<H3 ALIGN=\"CENTER\">Basic Details</H3>\n";
  staff_basicform("");

  $log['admin']->LogPrint("starting to create new staff member");
}


function staff_add()
{
  global $PHP_SELF;
  global $title, $firstname, $surname, $position, $school_id;
  global $voice, $department, $address, $email, $status, $school_id;
  global $username, $realname, $password;
  global $conf;

  if(!is_admin())
    die_gracefully("You do not have permission to access this page.");

  if(!is_auth_for_school($school_id, "staff", "create"))
    die_gracefully("You are not permitted to add staff to this school.");

  if(empty($surname))
    die_gracefully("The surname field cannot be empty.");

  // We need to make the user entry
  if($username=="auto"){
    $username = user_make_username($title, $firstname, $surname);
    if($username==FALSE){
      die_gracefully("Automatic username allocation failed, please use the back button and manually try a username.");
    }
  }
  else{
    $query = "SELECT * FROM id WHERE username='$username'";
    $result = mysql_query($query)
      or print_mysql_error2("Unable to query id table.", $query);
    if(mysql_num_rows($result)) die_gracefully("The username $username is already in use, please select another.");
    mysql_free_result($result);
  }
  if($password=="auto"){
    $password = user_make_password();
  }
  if($realname=="auto"){
    $realname = $title . " " . $firstname . " " . $surname;
  }
  $query = "INSERT INTO id VALUES(" .
           make_null($realname) . ", " .
           make_null($username) . ", " .
           make_null(MD5($password)) . ", 'staff', NULL, NULL, 0)";

  mysql_query($query)
    or print_mysql_error2("Unable to create new user entry.", $query);

  // Fetch the user id just allocated.
  $user_id = mysql_insert_id();
           
  // Form contacts table query
  $query = "INSERT INTO staff (title, firstname, surname, position, room, department, address, " .
           "voice, email, status, school_id, user_id) VALUES(" .
           make_null($title) . ", " .
           make_null($firstname) . ", " .
           make_null($surname) . ", " .
           make_null($position) . ", " .
           make_null($room) . ", " .
           make_null($department) . ", " .
           make_null($address) . ", " .
           make_null($voice) . ", " .
           make_null($email) . ", " .
           "'$status'" . ", " .
           $school_id . ", " . $user_id . ")";

  mysql_query($query)
    or print_mysql_error2("Unable to make new staff table entry.", $query);

  // Find the contact id given.
  $contact_id = mysql_insert_id();

  if(!empty($email))
  {
    user_notify_password($email, $title, $firstname, $surname, $username, $password, $user_id, "NewPassword_Staff");
    echo "<P ALIGN=\"CENTER\">The user has been emailed a username and password.</P>\n";
  }
  else{
    echo "<P ALIGN=\"CENTER\">No email address is listed for this user " .
         "and so it is impossible to send them the new credentials.<BR>" .
         "They have been allocated as follows.<BR>" .
         "<TABLE>\n<TR><TD>Username</TD><TD>" . $username . "</TD></TR>\n" .
         "<TR><TD>Password</TD><TD>" . $password . "</TD></TR>\n</TABLE>\n";
  }
  staff_simplesearch();
}


/*
**	staff_newpassword
**
** Automatically generates a new password for the contact and emails
** it if possible. Otherwise it displays it on screen.
*/
function staff_newpassword()
{
  global $PHP_SELF;
  global $log;
  global $user_id;
  global $school_id;

  if(!is_admin())
    die_gracefully("You are not permitted to perform this action");

  if(!is_auth_for_school($school_id, "staff", "edit"))
    die_gracefully("You do not have permission to perform this action");

  $query = "SELECT * FROM staff WHERE user_id=" . $user_id;
  $result = mysql_query($query)
    or print_mysql_error2("Unable to obtain staff information");
  $row = mysql_fetch_array($result);

  // Fetch matching user information
  $user_query = "SELECT * FROM id WHERE id_number=" . $user_id;
  $user_result = mysql_query($user_query)
    or print_mysql_error2("Unable to obtain user data.", $user_query);
  $user_row = mysql_fetch_array($user_result);

  // Generate a new password
  $password = user_make_password();

  // Put the new password in the database
  $new_query = "UPDATE id SET password=MD5('$password') WHERE id_number=" . $user_id;
  mysql_query($new_query) or print_mysql_error2("Unable to update password.", $new_query);

  if(!empty($row["email"]))
  {
    user_notify_password($row["email"], $row["title"], $row["firstname"], $row["surname"],
                         $user_row["username"], $password, $row["user_id"]);
    echo "<P ALIGN=\"CENTER\">The user has been emailed a username and password.</P>\n";
  }
  else{
    echo "<P ALIGN=\"CENTER\">No email address is listed for this user " .
         "and so it is impossible to send them the new credentials.<BR>" .
         "They have been allocated as follows.<BR>" .
         "<TABLE>\n<TR><TD>Username</TD><TD>" . $user_row["username"] . "</TD></TR>\n" .
         "<TR><TD>Password</TD><TD>" . $password . "</TD></TR>\n</TABLE>\n";
  }

  staff_basicedit();
}


function staff_delete()
{
  global $PHP_SELF;
  global $log;
  global $user_id;
  global $confirmed;

  if(!is_root()) die_gracefully("You do not have permission to access this page.");

  $user_name = get_user_name($user_id);
  if($confirmed==1){

    // Root and branch delete
    // Delete all links to students
    $query = "DELETE FROM staffstudent WHERE staff_id=$user_id";
    mysql_query($query)
      or print_mysql_error2("Failed to delete all links for contact.", $query);

    $query = "DELETE FROM id WHERE id_number=$user_id";
    mysql_query($query)
      or print_mysql_error2("Failed to delete user entry for staff member.", $query);

    $query = "DELETE FROM staff WHERE user_id=$user_id";
    mysql_query($query)
      or print_mysql_error2("Failed to delete data for staff member.", $query);

    $log['admin']->LogPrint("contact " . $user_name . " was totally removed " .
                              "from the system.");
    echo "<P ALIGN=\"CENTER\">Staff member " . htmlspecialchars($user_name) .
         " was totally removed from system.</P>";
  }
  else{

    echo "<H2 ALIGN=\"CENTER\">Are you sure?</H2>\n";
    echo "<P ALIGN=\"CENTER\">You have started the process to delete a staff member " .
         htmlspecialchars($user_name) . ". Normally this should never be done.</P>";

    echo "<P ALIGN=\"CENTER\"><A HREF=\"$PHP_SELF?mode=Delete&user_id=$user_id" .
         "&confirmed=1\">Click here to confirm delete</A></P>\n";
  }
}


function print_wizard($item)
{
  global $conf;
  global $user_id;
  global $school_id;
  global $smarty;

  $wizard2 = new TabbedContainer($smarty, 'tabs');
  $wizard2->addTab('Basics', $_SERVER['PHP_SELF'] . "?mode=BasicEdit&school_id=$school_id&user_id=$user_id");
  $wizard2->addTab('Students', $_SERVER['PHP_SELF'] . "?mode=DisplayStudents&school_id=$school_id&user_id=$user_id");

  // Transitionary code
  echo "<div name=\"tabbedContainer\" align=\"center\">\n";
  $wizard2->displayTab($item);
  echo "</div>\n";


}

?>


