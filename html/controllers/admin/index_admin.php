<?php

function nav_admin() 
{
  return array
  (
    "home"=>array
    (
      array("home", "home", "home", "home"),
      array("company activity", "home", "company_activity", "company_activity"), 
    ), 
    "directories"=>array
    (
      array("students","directories", "student_directory", "student_directory"), 
      array("companies", "directories", "manage_companies", "manage_companies"),
      array("vacancies", "directories", "manage_vacancies", "manage_vacancies"),
      array("academic staff","directories","staff_directory","staff_directory"),
      array("contacts", "directories", "contact_directory", "contact_directory"),
      array("admin details", "configuration", "manage_admins", "manage_admins")
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
      array("manage help", "configuration", "manage_help", "manage_help"),
      array("import data", "configuration", "import_data", "import_data"),

    ),
    "advanced"=>array
    (
      array("activity types", "advanced", "manage_activitytypes", "manage_activitytypes"),
      array("channels", "advanced", "manage_channels", "manage_channels"),
      array("mime types", "advanced", "manage_mimetypes", "manage_mimetypes"),
      array("mail templates", "advanced", "manage_automail", "manage_automail"),
      array("assessments", "advanced", "list_assessments", "manage_assessments"),

    )
  );
}

?>