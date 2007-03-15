<?php

/**
**	view_cvpdf.php
**
** This student script attempts to produce a one page summary from
** the available CV information in PDF form.
**
** Initial coding : Colin Turner
*/

// The include files
include('common.php');
include('authenticate.php');
include('lookup.php');

// Connect to the database on the server
db_connect()
  or die("Unable to connect to server");

// Authenticate user so that the right people see the right thing
auth_user("user");

if(!(is_admin() || is_student() || is_staff || is_company))
  print_auth_failure("ACCESS");

if(is_staff() && !is_course_director())
  print_auth_failure("ACCESS");

// Admin or staff user security
if(is_admin() || is_staff())
{
  if(empty($student_id))
  {
    page_header("CV in PDF form");
    printf("<H2 ALIGN=\"CENTER\">Error</H2>\n");
    printf("<P ALIGN=\"CENTER\">");
    printf("Try the <A HREF=\"%s\">Student Directory</A> first.</P>\n",
           $conf['scripts']['admin']['studentdir']);
    die_gracefully("You cannot access this page without a student id.");
  }
  else
  {
    // Is this user authorised?
    if(!is_auth_for_student($student_id, "student", "viewCV"))
      print_auth_failure("ACCESS");
  }
}


if(is_company()){
  $contact_id = get_contact_id(get_id());

  if(empty($student_id) || empty($contact_id)){
    page_header("Error");
    die_gracefully("You cannot access this page without a student id.");
  }

  // Only grant permission if this student has requested the company
  $query = "SELECT companycontact.* FROM companycontact, companystudent " .
           "WHERE companycontact.company_id = companystudent.company_id " .
           "AND companycontact.contact_id = " . $contact_id .
           " AND companystudent.student_id = " . $student_id;
  $result = mysql_query($query)
    or print_mysql_error2("Unable to authenticate company.", $query);

  if(!mysql_num_rows($result))
  {
    page_header("Error");
    die_gracefully("You do not have permission to access this page.");
  }
  mysql_free_result($result);

  // Check the student's placement status if possible to make sure of things
  $status = get_student_status($student_id);
  if($status != "Required"){
    if($status != "Placed"){
      // Student is no longer eligible for placement in one way
      // or another...
      page_header("Error");
      die_gracefully("This student is no longer available on the placement system.");
    }
    else{
      // Ok so the student is placed, is this a contact for the
      // lucky company?
      $query = "SELECT companycontact.* FROM companycontact, placement " .
               "WHERE companycontact.company_id = placement.company_id " .
               "AND companycontact.contact_id = " . $contact_id .
               " AND placement.student_id = " . $student_id;
      $result = mysql_query($query)
        or print_mysql_error2("Unable to authenticate company for placed student.", $query);
      if(!mysql_num_rows($result))
      {
        page_header("Error");
        die_gracefully("Sorry, but this student is now placed with another company.");
      }
      mysql_free_result($result);
    }
  }
}

// Students can ONLY view their own CV
if(is_student()) $student_id = get_id();


$fontsize_header  = "18.0";
$fontsize_caption = "10.0";
$fontsize_text    = "10.0";

$font_header      = "Helvetica-Bold";
$font_caption     = "Helvetica-Bold";
$font_text        = "Helvetica";

$query = sprintf("SELECT student_id FROM cv_pdetails WHERE id=%s", $student_id);
$result = mysql_query($query)
  or print_mysql_error("Unable to fetch student number.");

$row = mysql_fetch_row($result);
$pdffilename = sprintf("cv%s.pdf", $row[0]);
mysql_free_result($result);

$p = PDF_new();
PDF_open_file($p, "");

PDF_set_info($p, "Creator", "Engineering Placement Database");
PDF_set_info($p, "Author", "Colin Turner");
PDF_set_info($p, "Title", "Student CV");

PDF_begin_page($p, 595, 842);
$font = PDF_findfont($p, $font_header, "host", 0);
PDF_setfont($p, $font, $fontsize_header);

// Position for title
$link_width = PDF_stringwidth($p, "Univsersity of Ulster - Application for Industrial Placement" , $font, $fontsize_header);
PDF_set_border_style($p, "solid", 0);
PDF_add_weblink($p, points(5), points(279), points(5)+$link_width, points(279) + $fontsize_header, "http://" . $conf['webhost'] . $conf['paths']['base'] );
PDF_set_text_pos($p, points(5), points(279));
PDF_setcolor($p, "fill", "rgb", (float) 0, (float) 0, (float) 1, (float) 0);
PDF_show($p, "University of Ulster - Application for Industrial Placement");
PDF_setcolor($p, "fill", "rgb", (float) 0, (float) 0, (float) 0, (float) 0);
// Disable fatal errors when no image exists to load.
PDF_set_parameter($p, "imagewarning", "false");


// Position from bottom in mm to start printing at - this is reviewed throughout...
$vpos = 273.0;

display_pdetails();
display_cdetails();
display_edetails();
display_work();
display_odetails();

PDF_end_page($p);
PDF_close($p);

// Prepare to chuck the PDF to the browser
$buf = PDF_get_buffer($p);
$len = strlen($buf);

$header_text = sprintf("Content-Disposition: inline; filename=%s", $pdffilename);

header("Content-type: application/pdf");
header("Content-Length: $len");
//header("Content-Disposition: inline; filename=cv.pdf");
header($header_text);
print $buf;

PDF_delete($p);


/**
**	points()
**
** Converts a dimension in mm to printers points.
*/
function points($mm)
{
  return($mm / .3528);
}


/**
**	mm()
**
** Converts a dimension in points to mm.
*/
function mm($points)
{
  return($points * 0.3528);
}


/**
**	get_height()
**
** This function is used to determine the height that a box must
** be to just accomodate the given information and is used to
** save space.
*/
function get_height($pdf, $string, $corner_x, $corner_y, $width, $just, $increment)
{ 
  $height = $increment;
  $steps = 1;
  
  while(PDF_show_boxed($pdf, $string, $corner_x, $corner_y, $width, $height, $just, "blind")){
    $height = $increment * (++$steps);
  }
  return($height+1);
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
  global $conf;
  global $font_caption, $fontsize_caption;
  global $font_text, $fontsize_text;
  global $p;
  global $vpos;
  global $email_address, $student_number;

  // Run a query on the database for the lines of matching information.
  $query = sprintf("SELECT *, DATE_FORMAT(dob, '%%e %%b %%Y'),
                      DATE_FORMAT(course_start, '%%b %%y'),
                      DATE_FORMAT(course_end, '%%b %%y')
                      FROM cv_pdetails WHERE id='%d'", $student_id);

  // Run this by the server
  $result = mysql_query($query)
    or die_gracefully(mysql_error());


  // Ok now get the row of results after the query
  $row = mysql_fetch_array($result);

  $email_address = $row["email"];
  $student_number = $row["student_id"];

  // First line

  // Print captions  
  $font = PDF_findfont($p, $font_caption, "host", 0);
  PDF_setfont($p, $font, $fontsize_caption);
  PDF_set_text_pos($p, points(5), points($vpos));
  PDF_show($p, "Name");
  PDF_set_text_pos($p, points(70), points($vpos));
  PDF_show($p, "Student Number");
  PDF_set_text_pos($p, points(100), points($vpos));
  PDF_show($p, "Place of Birth");
  PDF_set_text_pos($p, points(140), points($vpos));
  PDF_show($p, "Nationality");
  PDF_set_text_pos($p, points(170), points($vpos));
  PDF_show($p, "DoB");
  $vpos -= mm(fontsize_caption) + 5;

  PDF_set_text_pos($p, points(5), points($vpos));
  $fullname = sprintf("%s %s %s", $row["title"], $row["firstname"], strtoupper($row["surname"]));
  PDF_show($p, $fullname);

  // Print fields
  $font = PDF_findfont($p, $font_text, "host", 0);
  PDF_setfont($p, $font, $fontsize_text);

  // Position text
  PDF_set_text_pos($p, points(70), points($vpos));
  PDF_show($p, $student_number);
  PDF_set_text_pos($p, points(100), points($vpos));
  PDF_show($p, $row["pob"]);
  PDF_set_text_pos($p, points(140), points($vpos));
  PDF_show($p, $row["nationality"]);
  PDF_set_text_pos($p, points(170), points($vpos));
  PDF_show($p, $row[mysql_num_fields($result)-3]);
  $vpos -= mm(fontsize_caption) + 5;

  // Print captions
  $font = PDF_findfont($p, $font_caption, "host", 0);
  PDF_setfont($p, $font, $fontsize_caption);
  PDF_set_text_pos($p, points(5), points($vpos));
  PDF_show($p, "Course");
  PDF_set_text_pos($p, points(110), points($vpos));
  PDF_show($p, "Start");
  PDF_set_text_pos($p, points(135), points($vpos));
  PDF_show($p, "End");
  PDF_set_text_pos($p, points(160), points($vpos));
  PDF_show($p, "Expected Award");
  $vpos -= mm(fontsize_caption) + 5;

  // Print fields
  $font = PDF_findfont($p, $font_text, "host", 0);
  PDF_setfont($p, $font, $fontsize_text);
  PDF_set_text_pos($p, points(5), points($vpos));
  PDF_show($p, get_course_name($row["course"]));
  PDF_set_text_pos($p, points(110), points($vpos));
  PDF_show($p, $row[mysql_num_fields($result)-2]);
  PDF_set_text_pos($p, points(135), points($vpos));
  PDF_show($p, $row[mysql_num_fields($result)-1]);
  PDF_set_text_pos($p, points(160), points($vpos));
  PDF_show($p, $row["expected_grade"]);
  $vpos -= fontsize_caption*0.3528 + 5;

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
  global $conf;
  global $p, $vpos;
  global $font_caption, $fontsize_caption;
  global $font_text, $fontsize_text;
  global $email_address, $student_number;
  
  // Run a query on the database for the lines of matching information.
  $query = sprintf("SELECT * FROM cv_cdetails WHERE id='%d'", $student_id);

  // Run this by the server
  $result = mysql_query($query)
    or die_gracefully(mysql_error());

  // It's not this is really contact details, but there's a gap here to display
  // any picture we may have for the student.
  $image = PDF_open_image_file($p, "jpeg", $conf['paths']['photos'] . "$student_number.jpg", "", 0);
  if($image != 0)
  {
    // The image could be opened, attempt to display it
    $image_height = PDF_get_value($p, "imageheight", $image);
    $image_width  = PDF_get_value($p, "imagewidth", $image);

    $image_size   = 76.0; // The number of points to try and force this to.

    $scaley = $image_size / $image_height;
    $scalex = $image_size / $image_width;

    $scale = min($scalex, $scaley);
    
    $llx   = points(170);
    $lly   = points($vpos) - ($scale * $image_height) + $fontsize_text;

    PDF_place_image($p, $image, $llx, $lly, $scale);

    PDF_moveto($p, $llx, $lly);
    PDF_lineto($p, $llx + ($scale * $image_width), $lly);
    PDF_lineto($p, $llx + ($scale * $image_width), $lly + ($scale * $image_height));
    PDF_lineto($p, $llx, $lly + ($scale * $image_height));
    //PDF_lineto($p, $llx, $lly);
    PDF_closepath_stroke($p);


  } 

  // Ok now get the row of results after the query
  $row = mysql_fetch_array($result);

  $home_address = $row["home_add_l1"];
  if(!(empty($row["home_add_l2"]))){
    $home_address .= "\r";
    $home_address .= $row["home_add_l2"];
  }
  if(!(empty($row["home_add_l3"]))){
    $home_address .= "\r";
    $home_address .= $row["home_add_l3"];
  }
  if(!(empty($row["home_town"]))){
    $home_address .= "\r";
    $home_address .= $row["home_town"];
  }
  if(!(empty($row["home_county"]))){
    $home_address .= ", ";
    $home_address .= $row["home_county"];
  }
  if(!(empty($row["home_country"]))){
    $home_address .= "\r";
    $home_address .= $row["home_country"];
  }
  if(!(empty($row["home_pcode"]))){
    $home_address .= "\r";
    $home_address .= $row["home_pcode"];
  }

  if(empty($row["term_add_l1"])){
    $term_address = "Same as home address";
  } else{

    $term_address = $row["term_add_l1"];
    if(!(empty($row["term_add_l2"]))){
      $term_address .= "\r";
      $term_address .= $row["term_add_l2"];
    }
    if(!(empty($row["term_add_l3"]))){
      $term_address .= "\r";
      $term_address .= $row["term_add_l3"];
    }
    if(!(empty($row["term_town"]))){
      $term_address .= "\r";
      $term_address .= $row["term_town"];
    }
    if(!(empty($row["term_county"]))){
      $term_address .= ", ";
      $term_address .= $row["term_county"];
    }
    if(!(empty($row["term_country"]))){
      $term_address .= "\r";
      $term_address .= $row["term_country"];
    }
    if(!(empty($row["term_pcode"]))){
      $term_address .= "\r";
      $term_address .= $row["term_pcode"];
    }
  }

  // Print captions
  $font = PDF_findfont($p, $font_caption, "host", 0);
  PDF_setfont($p, $font, $fontsize_caption);
  PDF_set_text_pos($p, points(5), points($vpos));
  PDF_show($p, "Home address");
  PDF_set_text_pos($p, points(80), points($vpos));
  PDF_show($p, "Term address");
  $vpos -= mm(fontsize_caption) + 2;

  // Print fields
  $font = PDF_findfont($p, $font_text, "host", 0);
  PDF_setfont($p, $font, $fontsize_text);

  $home_height = get_height($p, $home_address, points(5), points(50), points(70), "left", 1);
  $term_height = get_height($p, $term_address, points(80), points(50), points(70), "left", 1);

  // Make room for biggest field
  $height = max($home_height, $term_height);

  // Make room for photograph too!
  $height = min($height, points(30));

  PDF_show_boxed($p, $home_address, points(5), points($vpos)-$home_height, points(70), $home_height, "left");
  PDF_show_boxed($p, $term_address, points(80), points($vpos)-$term_height, points(70), $term_height, "left");

  $vpos -= mm($height) + 5;

  // Print captions
  $font = PDF_findfont($p, $font_caption, "host", 0);
  PDF_setfont($p, $font, $fontsize_caption);

  // Print titles
  PDF_set_text_pos($p, points(5), points($vpos));
  PDF_show($p, "Home phone");
  PDF_set_text_pos($p, points(40), points($vpos));
  PDF_show($p, "Term phone");
  PDF_set_text_pos($p, points(75), points($vpos));
  PDF_show($p, "Mobile phone");
  PDF_set_text_pos($p, points(110), points($vpos));
  PDF_show($p, "Email address");
  $vpos -= mm($fontsize_caption) + 2;

  // Print captions
  $font = PDF_findfont($p, $font_text, "host", 0);
  PDF_setfont($p, $font, $fontsize_text);

  // Print text
  PDF_set_text_pos($p, points(5), points($vpos));
  PDF_show($p, $row["home_tele"]);
  PDF_set_text_pos($p, points(40), points($vpos));
  PDF_show($p, $row["term_tele"]);
  PDF_set_text_pos($p, points(75), points($vpos));
  PDF_show($p, $row["mobile_no"]);

  if(!empty($email_address)){
    $link_width = PDF_stringwidth($p, $email_address, $font, $fontsize_text);
    PDF_set_border_style($p, "solid", 0);
    PDF_add_weblink($p, points(110), points($vpos), points(110)+$link_width, points($vpos) + $fontsize_text, "mailto:" . $email_address);
    PDF_setcolor($p, "fill", "rgb", (float) 0, (float) 0, (float) 1, (float) 0);
    PDF_set_text_pos($p, points(110), points($vpos));
    PDF_show($p, $email_address);
    PDF_setcolor($p, "fill", "rgb", (float) 0, (float) 0, (float) 0, (float) 0);
    /*
    PDF_moveto($p, points(110), points($vpos) - 5);
    PDF_lineto($p, points(110) + $link_width, points($vpos) - 5);
    PDF_closepath_stroke($p);
    */
  }
  $vpos -= mm($fontsize_caption) + 5;

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
  global $conf;
  global $p, $vpos;
  global $font_caption, $fontsize_caption;
  global $font_text, $fontsize_text;
  global $email_address, $student_number;
  global $notify_list;

  // Run a query on the database for the lines of matching information.
  $query = sprintf("SELECT * FROM cv_edetails WHERE id='%d' ORDER BY YEAR DESC", $student_id);

  // Run this by the server
  $result = mysql_query($query)
    or die_gracefully(mysql_error());

  if(mysql_num_rows($result)==0){
    $notify_list .= "No educational history available.\n";
    return;
  }
  if(mysql_num_rows($result)>2) $notify_list .= "Only the two most recent educational records will be used.\n";

  // Print captions
  $font = PDF_findfont($p, $font_caption, "host", 0);
  PDF_setfont($p, $font, $fontsize_caption);

  // Print titles
  PDF_set_text_pos($p, points(5), points($vpos));
  PDF_show($p, "Education");
  PDF_set_text_pos($p, points(80), points($vpos));
  PDF_show($p, "Examinations and Results");

  $vpos -= mm(fontsize_caption) + 2;

  // Back to text font
  $font = PDF_findfont($p, $font_text, "host", 0);
  PDF_setfont($p, $font, $fontsize_text);

  $count = 1;
  // Ok now get the row of results after the query
  while(($row = mysql_fetch_array($result)) && ($count++ <= 2))
  {
    $location = "";
    $grades = "";

    if(!empty($row["level"])) $location .= $row["level"] .= "\r";
    else $notify_list .= "An education record is missing its level description.\n";
    if(!empty($row["course"])) $location .= "(" . $row["course"] . ")\r";
    if(!empty($row["place"])) $location .= $row["place"];
    if(!empty($row["year"])) $location .= " " . $row["year"];

    $grade = "";

    // Run a query on the database for the lines of matching information.
    $query2 = sprintf("SELECT * FROM cv_results WHERE link='%d' AND (LEFT(grade, 1) BETWEEN 0 AND 9) ORDER BY grade DESC", $row[5]);

    // Run this by the server
    $result2 = mysql_query($query2)
      or die_gracefully(mysql_error());

    $first = TRUE;
    $first_row = TRUE;

    while($row2 = mysql_fetch_array($result2))
    {
      if($row2["grade"] != $grade){
        $grade = $row2["grade"];
        if($first_row) $first_row = FALSE;
        else $grades .= "\r";
        $grades .= $grade . ": ";
        $first = TRUE;
      }

      if(!$first) $grades .= "; ";
      else $first = FALSE;
      $grades .= $row2["subject"];
    }
    mysql_free_result($result2);
 
    // Run a query on the database for the lines of matching information.
    $query2 = sprintf("SELECT * FROM cv_results WHERE link='%d' AND NOT (LEFT(grade, 1) BETWEEN 0 AND 9) ORDER BY grade", $row[5]);

    // Run this by the server
    $result2 = mysql_query($query2)
      or die_gracefully(mysql_error());

    $first = TRUE;
    $first_row = TRUE;

    while($row2 = mysql_fetch_array($result2))
    {
      if($row2["grade"] != $grade){
        $grade = $row2["grade"];
        if($first_row) $first_row = FALSE;
        else $grades .= "\r";
        $grades .= $grade . ": ";
        $first = TRUE;
      }

      if(!$first) $grades .= "; ";
      else $first = FALSE;
      $grades .= $row2["subject"];
    }
    mysql_free_result($result2);


    $loc_height = get_height($p, $location, points(5), points(50), points(70), "left", 1);
    $gra_height = get_height($p, $grades, points(80), points(50), points(120), "left", 1);
    
    $height = max($loc_height, $gra_height);
    
    PDF_show_boxed($p, $location, points(5), points($vpos)-$loc_height, points(70), $loc_height, "left");
    PDF_show_boxed($p, $grades, points(80), points($vpos)-$gra_height, points(120), $gra_height, "left");
    
    $vpos -= mm($height) + 2;
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
  global $conf;
  global $p, $vpos;
  global $font_caption, $fontsize_caption;
  global $font_text, $fontsize_text;
  global $email_address, $student_number;
  global $notify_list;

  // Run a query on the database for the lines of matching information.
  $query = sprintf("SELECT *,DATE_FORMAT(start, '%%e %%b %%Y'),
                      DATE_FORMAT(finish, '%%e %%b %%Y') FROM cv_work 
                      WHERE id='%d' ORDER BY finish DESC", $student_id);

  // Run this by the server
  $result = mysql_query($query)
    or die_gracefully(mysql_error());

  if(mysql_num_rows($result)==0){
    $notify_list .= "No work experience available.\n";
    return;
  }
  if(mysql_num_rows($result)>3) $notify_list .= "Only the three most recent work experience records will be used.\n";

  $vpos -= mm(fontsize_caption) + 5;

  // Print captions
  $font = PDF_findfont($p, $font_caption, "host", 0);
  PDF_setfont($p, $font, $fontsize_caption);

  // Print titles
  PDF_set_text_pos($p, points(5), points($vpos));
  PDF_show($p, "Work Experience Employer");
  PDF_set_text_pos($p, points(80), points($vpos));
  PDF_show($p, "Experience gained");

  $vpos -= mm(fontsize_caption) + 2;
  
  // Back to text font
  $font = PDF_findfont($p, $font_text, "host", 0);
  PDF_setfont($p, $font, $fontsize_text);

  $count = 1;
  // Ok now get the row of results after the query
  while(($row = mysql_fetch_row($result)) && ($count++ <= 3))
  {
    $employer = "";
    $experience = "";

    $dates = sprintf("%s - %s", $row[mysql_num_fields($result)-2], $row[mysql_num_fields($result)-1]);
    $employer .= $dates . "\r" . $row[1];
    $experience = $row[4];

    $emp_height = get_height($p, $employer, points(5), points(50), points(70), "left", 1);
    $exp_height = get_height($p, $experience, points(80), points(50), points(120), "left", 1);

    $height = max($emp_height, $exp_height);
  
    PDF_show_boxed($p, $employer, points(5), points($vpos)-$emp_height, points(70), $emp_height, "left");
    PDF_show_boxed($p, $experience, points(80), points($vpos)-$exp_height, points(120), $exp_height, "left");
  
    $vpos -= mm($height) + 2;
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
  global $conf;
  global $p, $vpos;
  global $font_caption, $fontsize_caption;
  global $font_text, $fontsize_text;
  global $email_address, $student_number;
  global $notify_list;

  // Run a query on the database for the lines of matching information.
  $query = sprintf("SELECT * FROM cv_odetails WHERE id='%d'", $student_id);

  // Run this by the server
  $result = mysql_query($query)
    or die_gracefully(mysql_error());

  // Ok now get the row of results after the query
  $row = mysql_fetch_array($result);

  if(!empty($row["activities"])){
    $vpos -= mm(fontsize_caption) + 5;
    // Print captions
    $font = PDF_findfont($p, $font_caption, "host", 0);
    PDF_setfont($p, $font, $fontsize_caption);
    PDF_set_text_pos($p, points(5), points($vpos));
    PDF_show($p, "Personal Activities and Interests");
    $vpos -= mm(fontsize_caption) + 2;

    $font = PDF_findfont($p, $font_text, "host", 0);
    PDF_setfont($p, $font, $fontsize_text);
    $height = get_height($p, $row["activities"], points(5), points(50), points(185), "left", 1);
    PDF_show_boxed($p, $row["activities"], points(5), points($vpos)-$height, points(185), $height, "left");
    $vpos -= mm($height) + 2;
  }

  if(!empty($row["achievements"])){
    $vpos -= mm(fontsize_caption) + 5;
    // Print captions
    $font = PDF_findfont($p, $font_caption, "host", 0);
    PDF_setfont($p, $font, $fontsize_caption);
    PDF_set_text_pos($p, points(5), points($vpos));
    PDF_show($p, "Qualifications, Abilities and Personal Achievements");
    $vpos -= mm(fontsize_caption) + 2;
  
    $font = PDF_findfont($p, $font_text, "host", 0);
    PDF_setfont($p, $font, $fontsize_text);
    $height = get_height($p, $row["achievements"], points(5), points(50), points(185), "left", 1);
    PDF_show_boxed($p, $row["achievements"], points(5), points($vpos)-$height, points(185), $height, "left");
    $vpos -= mm($height) + 2;
  }

  if(!empty($row["career"])){
    $vpos -= mm(fontsize_caption) + 5;
    // Print captions
    $font = PDF_findfont($p, $font_caption, "host", 0);
    PDF_setfont($p, $font, $fontsize_caption);
    PDF_set_text_pos($p, points(5), points($vpos));
    PDF_show($p, "Career Intentions");
    $vpos -= mm(fontsize_caption) + 2;
  
    $font = PDF_findfont($p, $font_text, "host", 0);
    PDF_setfont($p, $font, $fontsize_text);
    $height = get_height($p, $row["career"], points(5), points(50), points(185), "left", 1);
    PDF_show_boxed($p, $row["career"], points(5), points($vpos)-$height, points(185), $height, "left");
    $vpos -= mm($height) + 2;
  }
}

?>