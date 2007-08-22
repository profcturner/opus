<?php

function nav_admin() 
{
  return array
  (
    "home"=>array
    (
      array("home", "home", "home", "home"), 
    ), 
    "directories"=>array
    (
      array("students","directories", "student_directory", "student_directory"), 
      array("companies & vacancies", "directories", "vacancy_directory", "vacancy_directory"),
      array("academic staff","directories","staff_directory","staff_directory"),
      array("contacts", "directories", "contact_directory", "contact_directory"),
    ), 
    "information"=>array
    (
      array("resources", "information", "list_resources", "list_resources"), 
      array("system status", "information", "system_status", "system_status"), 
      array("view logs", "information", "view_logs", "view_logs") 
    ),
    "configuration"=>array
    (
      array("admin details", "configuration", "list_admins", "list_admins"),
      array("edit resources", "configuration", "manage_resources", "manage_resources"),
      array("courses & groups", "configuration", "list_schools", "list_schools"),
      array("help prompts", "configuration", "list_help", "list_help"),
      array("mail templates", "configuration", "list_templates", "list_templates"),
      array("assessments", "configuration", "list_assessments", "list_assessments"),
      array("import data", "configuration", "import_data", "import_data")
    )
  );
}

?>