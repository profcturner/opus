<?php

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
      array("PHPinfo", "superuser", "view_phpinfo", "view_phpinfo")
    )
  );

  $last_item_nav = $_SESSION['lastitems']->get_nav();

  if(User::is_root())
  {
    $nav = array_merge_recursive($basic_nav, $root_nav);
  }
  else
  {
    $nav = $basic_nav;
  }
  return(array_merge_recursive($nav, $last_item_nav));
}


?>