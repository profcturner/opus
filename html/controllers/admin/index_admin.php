<?php

/**
* Defines and handles the Administrator Menu
* @package OPUS
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
*/

// All admin users need to know about policy code, even root users
require_once("model/Policy.class.php");

// The admin log file should be created, and it should be the default
$waf = UUWAF::get_instance();
$waf->set_default_log("admin");

/**
* Defines and handles the Administrator Menu
*
* This is a complicated example since many aspects of the Administrator menu
* are dynamic.
*
* @return a multidimensinal array that defines menu structure
*
*/
function nav_admin() 
{
	$waf = UUWAF::get_instance();
	$waf->language_load();

  $basic_nav = array
  (
    /*"Welcome"=>array
    (
      array($waf->T('welcome'), "welcome", "welcome", "home")
    ),*/
    "Home"=>array
    (
      array($waf->T('home'), "home", "home", "home"),
      array($waf->T('company_activity'), "home", "company_activity", "company_activity"),
      array($waf->T('change_password'), "home", "change_password", "change_password")
    ),
    "Directories"=>array
    (
      array($waf->T('students'),"directories", "student_directory", "student_directory"), 
      array($waf->T('companies'), "directories", "company_directory", "company_directory"),
      array($waf->T('vacancies'), "directories", "vacancy_directory", "vacancy_directory"),
      array($waf->T('academic_staff_plural'),"directories","staff_directory","staff_directory"),
      array($waf->T('contacts'), "directories", "contact_directory", "contact_directory"),
      array($waf->T('supervisors'), "directories", "supervisor_directory", "supervisor_directory"),
      array($waf->T('administrators'), "directories", "admin_directory", "admin_directory"),
      array($waf->T('superusers'), "directories", "manage_super_admins", "manage_super_admins")
    ), 
    "Information"=>array
    (
      array($waf->T('resources'), "information", "list_resources", "list_resources"),
      array($waf->T('help_directory'), "information", "help_directory", "help_directory"), 
      array($waf->T('reports'), "information", "list_reports", "list_reports"),
      array($waf->T('system_status'), "information", "system_status", "system_status"),
			array($waf->T('system_statistics'), "information", "system_statistics", "system_statistics"), 
      array($waf->T('view_logs'), "information", "view_logs", "view_logs") 
    ),
    "Configuration"=>array
    (
      array($waf->T('manage_resources'), "configuration", "resources", "manage_resources"),
      array($waf->T('organisation_details'), "configuration", "organisation_details", "organisation_details"),
      array($waf->T('CV_groups'), "configuration", "manage_cvgroups", "manage_cvgroups"),
      array($waf->T('assessment_groups'), "configuration", "manage_assessmentgroups", "manage_assessmentgroups"),
      array($waf->T('manage_help'), "configuration", "manage_help", "manage_help"),
      array($waf->T('import_data'), "configuration", "import_data", "import_data"),
    ),
    "Advanced"=>array
    (
      array($waf->T('assessments'), "advanced", "manage_assessments", "manage_assessments"),
      array($waf->T('activity_types'), "advanced", "manage_activitytypes", "manage_activitytypes"),
      array($waf->T('vacancy_types'), "advanced", "manage_vacancytypes", "manage_vacancytypes"),
      array($waf->T('channels'), "advanced", "manage_channels", "manage_channels"),
      array($waf->T('languages'), "advanced", "manage_languages", "manage_languages"),
      array($waf->T('mime_types'), "advanced", "manage_mimetypes", "manage_mimetypes"),
      array($waf->T('mail_templates'), "advanced", "manage_automail", "manage_automail"),
      array($waf->T('policies'), "advanced", "manage_policies", "manage_policies")
    )
  );

  $root_nav = array
  (
    "Superuser"=>array
    (
      array("services", "superuser", "edit_service", "edit_service"),
      array("Phone Home", "superuser", "edit_phonehome", "edit_phonehome"),
      array("User Directory", "superuser", "user_directory", "user_directory"),
      array("API users", "superuser", "manage_api_users", "manage_api_users"),
      array("CSV Mapping", "superuser", "manage_csvmappings", "manage_csvmappings"),
      array("PHP info", "superuser", "view_phpinfo", "view_phpinfo")
    )
  );

  // If a student is being dealt with, add a dynamic menu
  if(isset($_SESSION['student_id']))
  {
    require_once("model/Student.class.php");
    $student_name = User::get_name($_SESSION['student_id']);
    if(!strlen($student_name)) $student_name="Student";

    $student_nav = array
    (
      $student_name=>array
      (
        array("edit", "student", "edit_student", "edit_student"),
        array("home", "student", "placement_home", "placement_home"),
        array("vacancies", "student", "vacancy_directory", "vacancy_directory"),
        array("CVs", "student", "list_student_cvs", "list_student_cvs"),
        array("applications", "student", "manage_applications", "manage_applications"),
        array("assessment", "student", "view_assessments", "view_assessments"),
        array("channels", "student", "list_student_channels", "list_student_channels"),
        array("notes", "student", "list_notes", "list_notes"),
        array("drop", "student", "drop_student", "drop_student")
      )
    );
  }

  // If a company is being dealt with, add a dynamic menu
  if(isset($_SESSION['company_id']))
  {
    $company_name = "Company";

    require_once("model/Company.class.php");
    $company_name = Company::get_name($_SESSION['company_id']);
    if(!strlen($company_name)) $company_name="Company";

    $company_nav = array
    (
      $company_name=>array
      (
        array("edit", "company", "edit_company", "edit_company"),
        array("view", "company", "view_company", "view_company"),
        array("vacancies", "company", "manage_vacancies", "manage_vacancies"),
        array("contacts", "company", "manage_contacts", "manage_contacts"),
        array("resources", "company", "manage_resources", "manage_resources"),
        array("notes", "company", "list_notes", "list_notes"),
        array("drop", "company", "drop_company", "drop_company")
      )
    );
  }

  // Get the Recent items
  $last_item_nav = $_SESSION['lastitems']->get_nav();

  // Merge in root functionality if appropriate
  if(User::is_root())
  {
    $nav = array_merge_recursive($basic_nav, $root_nav);
  }
  else
  {
    $nav = $basic_nav;
  }

  // And the context menus
  if(isset($_SESSION['student_id']))
  {
    $nav = array_merge_recursive($nav, $student_nav);
  }
  if(isset($_SESSION['company_id']))
  {
    $nav = array_merge_recursive($nav, $company_nav);
  }

  // Finally add the recent items
  return(array_merge_recursive($nav, $last_item_nav));
  //return $nav;
}


?>
