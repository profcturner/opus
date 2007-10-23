<?php

// All admin users need to know about policy code, even root users
require_once("model/Policy.class.php");

// The admin log file should be created, and it should be the default
$GLOBALS['waf']->set_default_log("admin");

function nav_admin() 
{
  $basic_nav = array
  (
    "home"=>array
    (
      array("home", "home", "home", "home"),
      array("company activity", "home", "company_activity", "company_activity"),
      array("change password", "home", "change_password", "change_password")
    ),
    "directories"=>array
    (
      array("students","directories", "student_directory", "student_directory"), 
      array("companies", "directories", "company_directory", "company_directory"),
      array("vacancies", "directories", "vacancy_directory", "vacancy_directory"),
      array("academic staff","directories","staff_directory","staff_directory"),
      array("contacts", "directories", "contact_directory", "contact_directory"),
      array("administrators", "directories", "manage_admins", "manage_admins")
    ), 
    "information"=>array
    (
      array("resources", "information", "list_resources", "list_resources"),
      array("help directory", "information", "help_directory", "help_directory"), 
      array("reports", "information", "list_reports", "list_reports"),
      array("system status", "information", "system_status", "system_status"), 
      array("view logs", "information", "view_logs", "view_logs") 
    ),
    "configuration"=>array
    (
      array("manage resources", "configuration", "resources", "manage_resources"),
      array("organisation details", "configuration", "organisation_details", "organisation_details"),
      array("CV groups", "configuration", "manage_cvgroups", "manage_cvgroups"),
      array("Assessment groups", "configuration", "manage_assessmentgroups", "manage_assessmentgroups"),
      array("manage help", "configuration", "manage_help", "manage_help"),
      array("import data", "configuration", "import_data", "import_data"),

    ),
    "advanced"=>array
    (
      array("assessments", "advanced", "manage_assessments", "manage_assessments"),
      array("activity types", "advanced", "manage_activitytypes", "manage_activitytypes"),
      array("vacancy types", "advanced", "manage_vacancytypes", "manage_vacancytypes"),
      array("channels", "advanced", "manage_channels", "manage_channels"),
      array("languages", "advanced", "manage_languages", "manage_languages"),
      array("mime types", "advanced", "manage_mimetypes", "manage_mimetypes"),
      array("mail templates", "advanced", "manage_automail", "manage_automail"),
      array("policies", "advanced", "manage_policies", "manage_policies")
    )
  );

  $root_nav = array
  (
    "superuser"=>array
    (
      array("PHPinfo", "superuser", "view_phpinfo", "view_phpinfo"),
      array("Phone Home", "superuser", "edit_phonehome", "edit_phonehome")
    )
  );

  if(isset($_SESSION['student_id']))
  {
    $student_name = "student";

    require_once("model/Student.class.php");
    $student_name = Student::get_name($_SESSION['student_id']);

    $student_nav = array
    (
      $student_name=>array
      (
        array("edit", "student", "edit_student", "edit_student"),
        array("home", "student", "placement_home", "placement_home"),
        array("vacancies", "student", "vacancy_directory", "vacancy_directory"),
        array("applications", "student", "manage_applications", "manage_applications"),
        array("assessment", "student", "view_assessments", "view_assessments"),
        array("notes", "student", "list_notes", "list_notes"),
        array("drop", "student", "drop_student", "drop_student")
      )
    );
  }

  if(isset($_SESSION['company_id']))
  {
    $company_name = "company";

    require_once("model/Company.class.php");
    $company_name = Company::get_name($_SESSION['company_id']);

    $company_nav = array
    (
      $company_name=>array
      (
        array("edit", "company", "edit_company", "edit_company"),
        array("view", "company", "view_company", "view_company"),
        array("vacancies", "company", "manage_vacancies", "manage_vacancies"),
        array("contacts", "company", "manage_contacts", "manage_contacts"),
        array("notes", "company", "list_notes", "list_notes"),
        array("drop", "company", "drop_company", "drop_company")
      )
    );
  }

  $last_item_nav = $_SESSION['lastitems']->get_nav();

  if(User::is_root())
  {
    $nav = array_merge_recursive($basic_nav, $root_nav);
  }
  else
  {
    $nav = $basic_nav;
  }

  if(isset($_SESSION['student_id']))
  {
    $nav = array_merge_recursive($nav, $student_nav);
  }
  if(isset($_SESSION['company_id']))
  {
    $nav = array_merge_recursive($nav, $company_nav);
  }


  return(array_merge_recursive($nav, $last_item_nav));
}


?>