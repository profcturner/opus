<?php

/**
* Defines and handles the Academic Staff Menu
* @package OPUS
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
*/

/**
* Defines and handles the Academic Staff Menu
*
* @return a multidimensinal array that defines menu structure
*
*/
function nav_staff() 
{
  $basic_nav = array
  (
    "home"=>array
    (
      array("home", "home", "home", "home"),
      array("my details", "home", "edit_staff", "edit_staff"),
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
      array("help directory", "information", "help_directory", "help_directory")
    )
  );

  // If a student is being dealt with, add a dynamic menu
  if(isset($_SESSION['student_id']))
  {
    require_once("model/Student.class.php");
    $student_name = Student::get_name($_SESSION['student_id']);
    if(!strlen($student_name)) $student_name="student";

    $student_nav = array
    (
      $student_name=>array
      (
        array("edit", "student", "edit_student", "edit_student"),
        array("assessment", "student", "view_assessments", "view_assessments"),
        array("notes", "student", "list_notes", "list_notes"),
        array("drop", "student", "drop_student", "drop_student")
      )
    );
    $basic_nav = array_merge_recursive($basic_nav, $student_nav);
  }

  // Finally add the recent items
  return($basic_nav);
}


?>