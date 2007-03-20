<?php 

/**
**	edit_cdetails.php
**
** This student script allows a student to edit his or her
** contact details.
**
** Initial coding : Andrew Hunter
**
** Modified by Colin Turner for new backend code.
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

if(is_admin() && !empty($student_id) && !is_auth_for_student($student_id, "student", "viewCV"))
  print_auth_failure("ACCESS");

page_header("Contact Details"); // Calls the function for the header
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
if(empty ($mode)) $mode = EDIT_CDETAILS;

// Getting into the right mode for the right job
switch($mode)
{
  case EDIT_CDETAILS;
    edit_cdetails();
    break;

  case UPDATE_CDETAILS;
    update_cdetails();
    break;
}

// Print out the help column on rigth hand side
right_column("StudentCVContacts");

// Print the footer and finish the page
$page->end();


function create_contact_record($student_id)
{
  global $log; // Access to logging

  if(empty($student_id)){
    $log['debug']->LogPrint("Unable to create blank contact detail - missing id");
    die_gracefully("This page needs a student id with which to be accessed.\n");
  }
  $query = sprintf("INSERT INTO cv_cdetails (id) VALUES(%s)", $student_id);
  mysql_query($query) or
    print_mysql_error("Unable to create blank contact detail record. ($query)\n");

  $log['access']->LogPrint("Created blank contact details record.");  
}


function edit_cdetails()
{
  global $PHP_SELF; // Script reference
  global $student_id;       // Id of student
  global $log;      // Access to logging
  
  // Run a query on the database for the lines of matching information.
  $query = sprintf("SELECT * FROM cv_cdetails WHERE id='%d'", $student_id);

  // Run this by the server
  $result = mysql_query($query)
    or print_mysql_error("Failed to fetch CV details.\n");

  if(!mysql_num_rows($result)) create_contact_record($student_id);

  // Ok now get the row of results after the query
  $row = mysql_fetch_array($result);

  // Display the results in the form of an editable form

  printf("<H2 ALIGN=\"CENTER\">Contact Details</H2>\n");
  
  // Check if they can alter the record if not then print then exit
  if(!is_admin() && is_company())
  {
    
    printf("<P ALIGN=\"CENTER\">You do not have access to alter any part");
    printf("of this CV. If you think you should have access to this CV");
    printf(" Please contact the Webmaster</P>");
    exit;
  
  }
   
  printf("<FORM METHOD=\"post\" ACTION=\"%s?mode=%s&student_id=%s\">\n",
            $PHP_SELF, UPDATE_CDETAILS, $student_id); 

  printf("<TABLE ALIGN=\"CENTER\">");
  printf("<TR><TD>Home Address</TD><TD>");
  printf("<INPUT TYPE=\"TEXT\" SIZE=\"20\" VALUE=\"%s\" NAME=\"home_add_l1\">
            </TD></TR>\n", htmlspecialchars($row["home_add_l1"]));
  printf("<TR><TD></TD><TD>");
  printf("<INPUT TYPE=\"TEXT\" SIZE=\"20\" VALUE=\"%s\" NAME=\"home_add_l2\">
          </TD></TR>\n", htmlspecialchars($row["home_add_l2"]));
  printf("<TR><TD></TD><TD>");
  printf("<INPUT TYPE=\"TEXT\" SIZE=\"20\" VALUE=\"%s\" NAME=\"home_add_l3\">
          </TD></TR>\n", htmlspecialchars($row["home_add_l3"])); 
  printf("<TR><TD>Home Town</TD><TD>");
  printf("<INPUT TYPE=\"TEXT\" SIZE=\"20\" VALUE=\"%s\" NAME=\"home_town\">
          </TD></TR>\n", htmlspecialchars($row["home_town"]));
  printf("<TR><TD>Home County</TD><TD>");
  printf("<INPUT TYPE=\"TEXT\" SIZE=\"20\" VALUE=\"%s\" NAME=\"home_county\">
          </TD></TR>\n", htmlspecialchars($row["home_county"]));
  printf("<TR><TD>Home Country</TD><TD>");
  printf("<INPUT TYPE=\"TEXT\" SIZE=\"20\" VALUE=\"%s\" NAME=\"home_country\">
          </TD></TR>\n", htmlspecialchars($row["home_country"])); 
  printf("<TR><TD>Home Post code or Zip code</TD><TD>");
  printf("<INPUT TYPE=\"TEXT\" SIZE=\"20\" VALUE=\"%s\" NAME=\"home_pcode\">
          </TD></TR>\n", htmlspecialchars($row["home_pcode"]));
  printf("<TR><TD>Home Telephone Number</TD><TD>");
  printf("<INPUT TYPE=\"TEXT\" SIZE=\"20\" VALUE=\"%s\" NAME=\"home_tele\">
          </TD></TR>\n", htmlspecialchars($row["home_tele"]));
  printf("<TR><TD>Term Address</TD><TD>");
  printf("<INPUT TYPE=\"TEXT\" SIZE=\"20\" VALUE=\"%s\" NAME=\"term_add_l1\">
          </TD></TR>\n", htmlspecialchars($row["term_add_l1"]));
  printf("<TR><TD></TD><TD>");
  printf("<INPUT TYPE=\"TEXT\" SIZE=\"20\" VALUE=\"%s\" NAME=\"term_add_l2\">
          </TD></TR>\n", htmlspecialchars($row["term_add_l2"]));  
  printf("<TR><TD></TD><TD>");
  printf("<INPUT TYPE=\"TEXT\" SIZE=\"20\" VALUE=\"%s\" NAME=\"term_add_l3\">
          </TD></TR>\n", htmlspecialchars($row["term_add_l3"]));
  printf("<TR><TD>Term Town</TD><TD>");
  printf("<INPUT TYPE=\"TEXT\" SIZE=\"20\" VALUE=\"%s\" NAME=\"term_town\">
          </TD></TR>\n", htmlspecialchars($row["term_town"]));
  printf("<TR><TD>Term County</TD><TD>");
  printf("<INPUT TYPE=\"TEXT\" SIZE=\"20\" VALUE=\"%s\" NAME=\"term_county\">
          </TD></TR>\n", htmlspecialchars($row["term_county"]));
  printf("<TR><TD>Term Post Code</TD><TD>");
  printf("<INPUT TYPE=\"TEXT\" SIZE=\"20\" VALUE=\"%s\" NAME=\"term_pcode\">
          </TD></TR>\n", htmlspecialchars($row["term_pcode"]));
  printf("<TR><TD>Term Telephone Number</TD><TD>");
  printf("<INPUT TYPE=\"TEXT\" SIZE=\"20\" VALUE=\"%s\" NAME=\"term_tele\">
          </TD></TR>\n", htmlspecialchars($row["term_tele"]));
  printf("<TR><TD>Mobile Telephone Number</TD><TD>");
  printf("<INPUT TYPE=\"TEXT\" SIZE=\"20\" VALUE=\"%s\" NAME=\"mobile_no\">
          </TD></TR>\n", htmlspecialchars($row["mobile_no"]));
  
  printf("<TR><TD></TD><TD><INPUT TYPE=\"submit\" NAME=\"button\" 
            VALUE=\"Update\">");
  printf("<INPUT TYPE=\"reset\" VALUE=\"Reset\">");
  printf("</TD></TR>\n");
  printf("</TABLE>\n");
  printf("</FORM>\n");

  $log['access']->LogPrint("Contact details edit dialog displayed.");
}


function update_cdetails()
{

  // we need the a reference to the script also all the variables
  // needed for the update

  global $PHP_SELF, $student_id;
  global $home_add_l1, $home_add_l2, $home_add_l3, $home_town;
  global $home_county, $home_country, $home_pcode, $home_tele;
  global $term_add_l1, $term_add_l2, $term_add_l3, $term_town;
  global $term_county, $term_county, $term_pcode, $term_tele;
  global $mobile_no;

  global $conf;
  global $log; // Access to logging

  if(is_admin() && !is_auth_for_student($student_id, "student", "editCV"))
    die_gracefully("You do not have permission to edit this CV");

  // Build the query for the update
  $query = sprintf("UPDATE cv_cdetails SET home_add_l1=%s, home_add_l2=%s,
    home_add_l3=%s, home_town=%s, home_county=%s, home_country=%s,
    home_pcode=%s, home_tele=%s, term_add_l1=%s, term_add_l2=%s, 
    term_add_l3=%s, term_town=%s, term_county=%s, term_pcode=%s,
    term_tele=%s, mobile_no=%s WHERE id=%s",
    make_null($home_add_l1),
    make_null($home_add_l2),
    make_null($home_add_l3),        
    make_null($home_town),
    make_null($home_county),
    make_null($home_country),
    make_null($home_pcode),
    make_null($home_tele),
    make_null($term_add_l1),
    make_null($term_add_l2),
    make_null($term_add_l3),
    make_null($term_town),
    make_null($term_county),
    make_null($term_pcode),
    make_null($term_tele),
    make_null($mobile_no), $student_id);

  // Try the query 
  $result = mysql_query($query)
    or die_gracefully(mysql_error());

  printf("<P ALIGN=\"CENTER\">Changes have been accepted<BR>");
  printf("<A HREF=\"%s?student_id=%s&con=1\">Click here</A>",
         $conf['scripts']['student']['viewcv'],
         $student_id);
  printf(" to view the CV.</P>");

  $log_string = sprintf("Contact details updated for user %s (%s)",
                        get_user_name($student_id), $student_id);
  $log['access']->LogPrint($log_string);
}  
   

?>

