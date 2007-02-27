<?php

//echo "+";
//require("class.Navigation.php");

//echo "+";
$pds_url = $conf['pdp']['host'] . "/pdp/controller.php";
$navigation = new Navigation;

//echo "+";
// Directory menu and its sub items
$navigation->add_menu('myPDS', 'mypds',
  $pds_url . "?function=homepage", 'mn0100');
$navigation->add_menu_item('Home', 'mypds',
  $pds_url . "?function=view_homepage");
$navigation->add_menu_item('Messages', 'mypds',
  $pds_url . "?function=list_messages");
$navigation->add_menu_item('Calendar', 'mypds',
  $pds_url . "?function=view_calendar");
$navigation->add_menu_item('Contacts', 'mypds',
  $pds_url . "?function=list_contacts&page=1");
$navigation->add_menu_item('Netmail', 'mypds',
  $pds_url . "?function=open_netmail");
$navigation->add_menu_item('Artifacts', 'mypds',
  $pds_url . "?function=list_artifacts");
$navigation->add_menu_item('Send Comment', 'mypds',
  $pds_url . "?function=add_comment");


$navigation->add_menu('myProfile', 'myprofile',
  $pds_url . "?function=view_personal_details", 'mn0200');
$navigation->add_menu_item('Personal Details', 'myprofile',
  $pds_url . "?function=view_personal_details");
$navigation->add_menu_item('Qualifications', 'myprofile',
  $pds_url . "?function=list_qualifications");
$navigation->add_menu_item('Work Experience', 'myprofile',
  $pds_url . "?function=list_work_experience");
$navigation->add_menu_item('Extra Curricular', 'myprofile',
  $pds_url . "?function=list_extra_curricular");
$navigation->add_menu_item('Achievement', 'myprofile',
  $pds_url . "?function=list_achievements");
$navigation->add_menu_item('Publications', 'myprofile',
  $pds_url . "?function=list_publications");
$navigation->add_menu_item('Conferences', 'myprofile',
  $pds_url . "?function=list_conferences");


$navigation->add_menu('myProgramme', 'myprogramme',
  $pds_url . "?function=view_course_team", 'mn0300');
$navigation->add_menu_item('Team', 'myprogramme',
  $pds_url . "?function=view_course_team");
$navigation->add_menu_item('Resources', 'myprogramme',
  $pds_url . "?function=list_course_resources");
$navigation->add_menu_item('Transcript', 'myprogramme',
  $pds_url . "?function=view_academic");
$navigation->add_menu_item('Advisor Forms', 'myprogramme',
  $pds_url . "?function=view_forms");
$navigation->add_menu_item('Downloadable Forms', 'myprogramme',
  $pds_url . "?function=view_downloadable_forms");


$navigation->add_menu('myReflection', 'myreflection',
  $pds_url . "?function=view_skills&skill_type=generic", 'mn0400');
$navigation->add_menu_item('Skills', 'myreflection',
  $pds_url . "?function=view_skills&skill_type=generic");
$navigation->add_menu_item('Goals', 'myreflection',
  $pds_url . "?function=list_goals");
$navigation->add_menu_item('Planning', 'myreflection',
  $pds_url . "?function=list_plans");
$navigation->add_menu_item('Journals', 'myreflection',
  $pds_url . "?function=view_journals");
$navigation->add_menu_item('Learning Style', 'myreflection',
  $pds_url . "?function=view_learning_style");


$navigation->add_menu('myCV', 'mycv',
  $pds_url . "?function=view_cv_builder", 'mn0500');
$navigation->add_menu_item('CV Builder', 'mycv',
  $pds_url . "?function=view_cv_builder");
$navigation->add_menu_item('CV Archive', 'mycv',
  $pds_url . "?function=list_archived_cvs");
$navigation->add_menu_item('Covering Letters', 'mycv',
  $pds_url . "?function=view_cover_letters");
$navigation->add_menu_item('Application Forms', 'mycv',
  $pds_url . "?function=view_application_forms");
$navigation->add_menu_item('Interviews', 'mycv',
  $pds_url . "?function=view_interviews");
$navigation->add_menu_item('Personal Statements', 'mycv',
  $pds_url . "?function=list_other_statements");


$navigation->add_menu('myPlacement', 'myplacement',
  $conf['scripts']['student']['index'] . "?student_id=$student_id", 'mn0600');
$navigation->add_menu_item('Placement Home', 'myplacement',
  $conf['scripts']['student']['index'] . "?student_id=$student_id");
$navigation->add_menu_item('Vacancies', 'myplacement',
  $conf['scripts']['company']['directory'] . "?student_id=$student_id");
$navigation->add_menu_item('Applications', 'myplacement',
  $conf['scripts']['student']['applications'] . "?student_id=$student_id");
$navigation->add_menu_item('Assessment', 'myplacement',
  $conf['scripts']['student']['assessment'] . "?student_id=$student_id");
$navigation->add_menu_item('Resources', 'myplacement',
  $conf['scripts']['user']['resources'] . "?student_id=$student_id");
$navigation->add_menu_item('Notes', 'myplacement',
  $conf['scripts']['student']['notes'] . "?student_id=$student_id");


$navigation->add_menu('myPortfolio', 'myportfolio',
  $pds_url . "?function=view_portfolios", 'mn0700');
$navigation->add_menu_item('Portfolios', 'myportfolio',
  $pds_url . "?function=view_portfolios");
$navigation->add_menu_item('Shared Portfolios', 'myportfolio',
  $pds_url . "?function=view_shared_portfolios");


?>