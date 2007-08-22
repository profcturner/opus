<?php

function nav_academic() 
{
  return array
  (
    "home"=>array
    (
      array("home", "home", "home", "home"),
      array("contact details", "home", "contact_details", "contact_details"),
      array("student details", "home", "student_details", "student_details")
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