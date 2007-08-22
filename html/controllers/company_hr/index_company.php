<?php

function nav_company() 
{
  return array
  (
    "home"=>array
    (
      array("home", "home", "home", "home"),
      array("contact details", "home", "contact_details", "contact_details"),
      array("company details", "home", "company_details", "company_details"),
      array("change password", "home", "change_password", "change_password")
    ), 
    "directories"=>array
    (
      array("students","directories", "student_directory", "student_directory"), 
      array("companies & vacancies", "directories", "vacancy_directory", "vacancy_directory")
    ), 
    "information"=>array
    (
      array("resources", "information", "list_resources", "list_resources")
    )
  );
}

?>