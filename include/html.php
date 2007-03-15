<?php

/**
**	html.php
**
** This file provides much of the standard look
** and feel of the placement database and is
** largely editable.
**
** Note however that all function calls must
** be implemented.
**
** Initial coding : Colin Turner incorporating and 
**                  revising code by Andrew Hunter
**
*/

require 'Smarty.class.php';
require 'Navigation.class.php';
require 'Lastitems.class.php';

$smarty = new Smarty;

$smarty->template_dir=$conf['paths']['templates'] . 'templates';
$smarty->compile_dir=$conf['paths']['templates'] . 'templates_c';
$smarty->config_dir=$conf['paths']['templates'] . 'configs';
$smarty->cache_dir=$conf['paths']['templates'] . 'templates_cache';

if($conf["debug"])
{
  $smarty->compile_check = true;
  $smarty->debugging = true;
}


$smarty->assign_by_ref("opus_version", OPUS::get_version());
$smarty->assign_by_ref("conf", $conf);
$smarty->assign_by_ref("page", $page);
$smarty->assign_by_ref("session", $_SESSION);




$page = array();


/**
**	page_header()
**
** This function prints out the basic header of HTML required
** for all pages.
**
*/
function page_header($title)
{
  global $smarty;
  global $page;
  global $conf;
  global $student_id;

  $page['title'] = $title;
  $page['starttime'] = microtime_float();

  $smarty->display('header.tpl');  

  // Experimental
  if(is_admin())
  {
    include("menu/admin.php");
    $smarty->assign("navigation", $navigation);
    if(!empty($student_id))
    {
      include("menu/student.php");
      $smarty->assign("student_navigation", $navigation);
    }
    // Next line is for crude testing
    $smarty->assign("section", "directories");
    $smarty->display("nav.tpl");
  }

  if(is_student())
  {
    include("menu/student.php");
    $smarty->assign("navigation", $navigation);
    $smarty->assign("section", "myplacement");
    $smarty->display("nav.tpl");
  }

  if(is_supervisor())
  {
    include("menu/supervisor.php");
    $smarty->assign("navigation", $navigation);
    $smarty->assign("section", "myplacement");
    $smarty->display("nav.tpl");
  }

  if(is_staff())
  {
    include("menu/staff.php");
    $smarty->assign("navigation", $navigation);
    $smarty->assign("section", "myplacement");
    $smarty->display("nav.tpl");
  }

  if(is_company())
  {
    include("menu/company.php");
    $smarty->assign("navigation", $navigation);
    $smarty->assign("section", "myplacement");
    $smarty->display("nav.tpl");
  }


  //if(is_student() || $_SESSION['LoginFromPDSFail']) $page['nopmsheader'] = TRUE;
  
  
  // PDP clone hack...
  // PDP uses a specific directory structure so that we need to change
  // the root before including their stuff...
  /*if(is_student() || $_SESSION['LoginFromPDSFail'])
  {
    $smarty->template_dir=$conf['paths']['templates'] . 'templates/pdp';
    
    //echo "debug: " . $smarty->template_dir;
    echo "<!-- start of PDSystem templates -->";
    $smarty->display("student/header_student.tpl");
    $smarty->display("student/myplacement/header_myplacement.tpl");
    $smarty->template_dir=$conf['paths']['templates'] . 'templates';
    echo "<!-- end of PDSystem templates -->";
    return;
  }*/

}


/**
**	page_footer()
**
** Finishes the page.
**
*/
function page_footer ()
{
  global $page;
  global $smarty;

  $page['endtime'] = microtime_float();
  $smarty->display('footer.tpl');
}


/**
**	print_menu()
**
** Prints an appropriate menu, with appropriate context
** for the user, as indicated by $user, allowed values are.
**
** student, company and admin
**
*/
function print_menu($user)
{
  global $conf; // We need access to configuration data
  global $student_id;
  global $user;
  global $smarty;

  // Set up preferences
  $menu = array();

  // Start to remove this...
  if(is_admin()) return;

  // Is the user a student, or are we looking at one?
  if(($user == "student") || is_student()){
    $menu["enable_student"]=TRUE;
    if(is_student()) $student_id = get_id();
    $menu["student_id"] = $student_id;
    
    $sql = "SELECT real_name, username FROM id WHERE id_number=$student_id";
    $result = mysql_query($sql)
      or print_mysql_error2("Unable to fetch real name", $sql);
    $row = mysql_fetch_array($result);
    $menu["student_realname"] = $row["real_name"];
    $menu["student_username"] = $row["username"];
    mysql_free_result($result);

    $sql = "SELECT year FROM students WHERE user_id=$student_id";
    $result = mysql_query($sql)
      or print_mysql_error2("Unable to get student placement year");
    $row = mysql_fetch_row($result);
    $menu["student_pyear"] = $row[0];
    mysql_free_result($result);

  }

  if(is_student()) return;

  if(is_supervisor()) return;

  if(is_staff()) return;

  if(is_company()) return;



  printf("<TABLE BORDER=\"0\" WIDTH=\"100%%\" CELLSPACING=\"1\" 
    CELLPADDING=\"2\">\n");
  printf("<TR>\n");

  //printf("<TD WIDTH=\"200px\" VALIGN=\"top\" BGCOLOR=\"#666699\">\n");	

  printf("<TD WIDTH=\"200px\" VALIGN=\"top\" BGCOLOR=\"#C9E1E8\">\n");	

  if((!empty($student_id) && is_admin()) || ($user == "student") || is_student())
  {
   
//    printf("<FONT FACE=\"Arial, Helvetica, sans-serif\" COLOR=\"white\"
//      SIZE=\"2\">Students\n");
    printf("Students\n");
    printf("<BR>\n");
       printf("<BR>\n");
    
    if(empty($student_id) && is_student()) $student_id = get_id();
    $query = "SELECT real_name, username FROM id WHERE id_number=$student_id";
    $result = mysql_query($query)
      or print_mysql_error2("Unable to fetch real name", $query);
    $row = mysql_fetch_row($result);

    if(is_admin())
    {
      printf("<A HREF=\"%s?mode=STUDENT_SHOWCV&student_id=%s\">",
        $conf['scripts']['admin']['studentdir'], $student_id);
       }
    printf("%s (%s)<BR>\n", htmlspecialchars($row[0]), $row[1]);
    if(is_admin()) printf("</A>\n");
    mysql_free_result($result);

    // Transition query - for now we need to establish the placement year
    $query = "SELECT year FROM students WHERE user_id=$student_id";
    $result = mysql_query($query)
      or print_mysql_error2("Unable to get student placement year");
    $row = mysql_fetch_row($result);
    $pyear = $row[0];
    mysql_free_result($result);

    if(!empty($student_id)) $extra = "?student_id=" . $student_id;
   
    if($pyear < 2004)
    {
      // Old CV system
      printf("Edit Your\n");
      printf("<TABLE BORDER=\"0\" WIDTH=\"100%%\" CELLSPACING=\"1\" CELLPADDING=\"2\">\n"); 
  
      print_menu_option("Personal Details", $conf['scripts']['student']['pdetails'] . $extra);
      print_menu_option("Contact Details", $conf['scripts']['student']['cdetails'] . $extra);
      print_menu_option("Educational Results", $conf['scripts']['student']['edetails'] . $extra);
      print_menu_option("Work Experience", $conf['scripts']['student']['wdetails'] . $extra);
      print_menu_option("Other Details", $conf['scripts']['student']['odetails'] . $extra); 

      printf("<TR><TD WIDTH=\"100%%\" ALIGN=\"CENTER\"><HR></TD></TR>\n");

      print_menu_option("View CV", $conf['scripts']['student']['viewcv'] . $extra);
      print_menu_option("View CV as PDF", $conf['scripts']['student']['viewcvpdf'] . $extra);
    }
    if($pyear == 2004)
    {

      printf("<TABLE BORDER=\"0\" WIDTH=\"100%%\" CELLSPACING=\"1\" CELLPADDING=\"2\">\n"); 

      print_menu_option("CV Options", $conf['scripts']['student']['newcvindex'] . $extra);
//      print_menu_option("Edit your CV", $conf['scripts']['student']['neweditcv'] . $extra);
//      print_menu_option("View your CV", $conf['scripts']['student']['newviewcvpdf'] . $extra);
//      print_menu_option("Email your CV", $conf['scripts']['student']['newemailcv'] . $extra);
      printf("<TR><TD WIDTH=\"100%%\" ALIGN=\"CENTER\"><HR></TD></TR>\n");

    }
    if($pyear > 2004)
    {
      printf("<TABLE BORDER=\"0\" WIDTH=\"100%%\" CELLSPACING=\"1\" CELLPADDING=\"2\">\n"); 
      print_menu_option("Edit your CV", "http://pds.ulster.ac.uk/pdp/controller.php?function=view_cv_builder&ques_page=false",  "target=_blank");
      print_menu_option("View default CV", $conf['scripts']['student']['pdpcvpdf'] . $extra . "&template_id=" . get_default_cvtemplate($student_id));
      //print_menu_option("View The Placement CV", $conf['scripts']['student']['pdpcvpdf'] . $extra . "&template_id=5");
      //print_menu_option("View Computing Schools CV", $conf['scripts']['student']['pdpcvpdf'] . $extra . "&template_id=12");
      printf("<TR><TD WIDTH=\"100%%\" ALIGN=\"CENTER\"><HR></TD></TR>\n");
    }

    if($user == "admin" || is_admin())
        print_menu_option("Change Student Password", $conf['scripts']['admin']['password'] . "?user_id=" . $student_id);
    print_menu_option("Companies & Vacancies", $conf['scripts']['company']['directory'] . $extra);
    print_menu_option("Applications", $conf['scripts']['student']['applications'] . $extra);
    print_menu_option("Assessment", $conf['scripts']['student']['assessment'] . $extra);
    print_menu_option("Home Page", $conf['scripts']['student']['index'] . $extra);

    //printf("<TR><TD WIDTH=\"100%%\" ALIGN=\"CENTER\"><HR></TD></TR>\n");
    //print_menu_option("Career Vacancies", "http://careers.ulst.ac.uk/vacancy/searchplacevac.phtml");
    if(($user == "admin") || is_admin())
      printf("<TR><TD WIDTH=\"100%%\" ALIGN=\"CENTER\"><HR></TD></TR>\n");
    printf("</TABLE>");
  }
  if($user == "company" || is_company())
  {
    printf("Companies\n");
    printf("<BR>\n<BR>\n");

    printf("<TABLE BORDER=\"0\" WIDTH=\"100%%\" CELLSPACING=\"1\"
      CELLPADDING=\"2\">\n");

    print_menu_option("Home Page", $conf['scripts']['company']['index']);      
    print_menu_option("Company Directory", $conf['scripts']['company']['directory']);
    print_menu_option("Contact Details", $conf['scripts']['company']['contacts']);

    printf("</TABLE>");
  }
  if(($user == "supervisor") || is_supervisor())
  {
    printf("Supervisors\n");
    printf("<BR>\n<BR>\n");

    if(!empty($user_id)) $extra = "&user_id=$user_id"; 
    printf("<TABLE BORDER=\"0\" WIDTH=\"100%%\" CELLSPACING=\"1\" 
      CELLPADDING=\"2\">\n"); 

    print_menu_option("Home Page", $conf['scripts']['supervisor']['index']);
    if(($user == "admin") || is_admin())
      printf("<TR><TD WIDTH=\"100%%\" ALIGN=\"CENTER\"><HR></TD></TR>\n");
    printf("</TABLE>\n");

  }
  if(($user == "staff") || is_staff())
  {
    printf("Staff\n");
    printf("<BR>\n<BR>\n");

    if(!empty($user_id)) $extra = "&user_id=$user_id"; 
    printf("<TABLE BORDER=\"0\" WIDTH=\"100%%\" CELLSPACING=\"1\" 
      CELLPADDING=\"2\">\n"); 

    print_menu_option("Contact Details", $conf['scripts']['staff']['directory'] .
                      "?mode=BasicEdit" . $extra);
    print_menu_option("Student Details", $conf['scripts']['staff']['directory'] .
                      "?mode=DisplayStudents" . $extra);
    print_menu_option("Browse Companies", $conf['scripts']['company']['directory']);
    if(is_course_director() && check_default_policy("student", "list"))
      print_menu_option("Student Directory", $conf['scripts']['admin']['studentdir']);
     
    print_menu_option("Home Page", $conf['scripts']['staff']['index']);
    if(($user == "admin") || is_admin())
      printf("<TR><TD WIDTH=\"100%%\" ALIGN=\"CENTER\"><HR></TD></TR>\n");
    printf("</TABLE>\n");

  }
  if(($user == "admin") || is_admin())
  {

    printf("Administrators\n");
    printf("<BR>\n<BR>\n");
 
    printf("<TABLE BORDER=\"0\" WIDTH=\"100%%\" CELLSPACING=\"1\" 
      CELLPADDING=\"2\">\n"); 

    print_menu_option("Student Directory", $conf['scripts']['admin']['studentdir']);
    print_menu_option("Staff Directory", $conf['scripts']['staff']['directory']);
    print_menu_option("Company Directory", $conf['scripts']['company']['edit']);
    print_menu_option("Contact Directory", $conf['scripts']['company']['contacts']);
    print_menu_option("Add New Student", $conf['scripts']['admin']['newuser']);
    print_menu_option("Import Data", $conf['scripts']['admin']['import']); 
    print_menu_option("Edit Courses & Groups", $conf['scripts']['admin']['courses']);
 
    print_menu_option("Edit Help", $conf['scripts']['admin']['edithelp']);
    if(check_default_policy("automail", "list"))
      print_menu_option("Edit Templates", $conf['scripts']['admin']['automail']);
    if(check_default_policy("resources", "list"))
      print_menu_option("Edit Resources", $conf['scripts']['admin']['resourcedir']);
    if(is_root())
      print_menu_option("Edit Assessments", $conf['scripts']['admin']['assessments']);
   print_menu_option("Edit Admins", $conf['scripts']['admin']['admindir']);
   if(check_default_policy("log", "access"))
      print_menu_option("View Logs", $conf['scripts']['admin']['viewlog']);
    if(check_default_policy("status", "user"))
      print_menu_option("System Status", $conf['scripts']['admin']['status']);

    if($_SESSION['display_prefs']['edit_channels'])
    {
      // Editing is on, allow it to be turned off
      print_menu_option("Disable Edit Channel Help", $conf['scripts']['admin']['preferences'] . "?mode=EditChannels_Off");
    }
    else
    {
      // Editing is off, allow it to be turned on
      print_menu_option("Enable Edit Channel Help", $conf['scripts']['admin']['preferences'] . "?mode=EditChannels_On");
    }

    print_menu_option("Home Page", $conf['scripts']['admin']['index']);
    printf("</TABLE>\n");
  }

  printf("<TABLE BORDER=\"0\" WIDTH=\"100%%\" CELLSPACING=\"1\"
    CELLPADDING=\"2\">\n");


  printf("<TR><TD WIDTH=\"100%%\" ALIGN=\"CENTER\"><HR></TD></TR>\n");
  print_menu_option("Change Password", $conf['scripts']['admin']['password']);
  print_menu_option("Resources", $conf['scripts']['user']['resources']);
  print_menu_option("UU Home Page", "http://www.ulster.ac.uk");

  // New logout option...
  if(isset($_SESSION['user']))
  {
    printf("<TR><TD WIDTH=\"100%%\" ALIGN=\"CENTER\"><HR></TD></TR>\n");
    print_menu_option("Logout", $conf['scripts']['user']['index'] . "?mode=Logout");
  }
  printf("</TABLE>\n");

//  printf("<left><A HREF=\"http://www.ulst.ac.uk\">UU Homepage</A></left><br>\n");
  //printf("</font>\n");
  printf("</td>\n");
  printf("<td valign=\"top\">\n");

} 


function print_menu_option($text, $link, $more="")
{
  printf("<TR><td WIDTH=\"100%%\" ALIGN=\"LEFT\">\n");
  printf("<A HREF=\"%s\" $more>", $link);
  printf("%s", htmlspecialchars($text));
  printf("</A></TD></TR>\n");
}


/*
**	right_column
**
** This function produces a right column in which a help prompt
** can be displayed.
**
**	page	is a help prompt from the help database.
*/
function right_column($page)
{

  if(check_for_help($page)){
    // Close the middle section of the table
    printf("</TD>\n");

    // Open the table section for the right column
    printf("<TD WIDTH=\"30%%\" VALIGN=\"top\" BGCOLOR=\"#f0f8ff\">");

    printf("<H3 ALIGN=\"CENTER\">Help</H3>\n");
    output_help($page);
  }
}


function welcome_login()
{
  global $conf; // Need access to the configuration.

  printf("<H2 ALIGN=\"CENTER\">Welcome</H2>\n");

  printf("<P ALIGN=\"CENTER\">\n");
  printf("Welcome %s, this seems to be your first login to the system. ", get_name());
  printf("Before you do anything else, please change your password to something ");
  printf("other than the one given to you to login. This will help to keep your ");
  printf("details secure.</P>\n");

  printf("<P ALIGN=\"CENTER\">\n");
  printf("Please click <A HREF=\"%s\">here</A> to change your password.</P>",
         $conf['scripts']['admin']['password']);

  printf("<P ALIGN=\"CENTER\">\n");
  printf("When you have finished using the system, please close ALL copies of ");
  printf("your browser before leaving your computer, if you are using a public ");
  printf("access machine.</P>\n");

}

?>