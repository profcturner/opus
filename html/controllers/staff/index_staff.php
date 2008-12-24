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
    "Home"=>array
    (
      array("home", "home", "home", "home"),
      array("my details", "home", "edit_staff", "edit_staff"),
      array("other students", "home", "other_assessees", "other_assessees"),
      array("company activity", "home", "company_activity", "company_activity"),
      array("change password", "home", "change_password", "change_password")
    ),
    "Directories"=>array
    (
      array("companies", "directories", "company_directory", "company_directory"),
      array("vacancies", "directories", "vacancy_directory", "vacancy_directory"),
      array("administrators", "directories", "manage_admins", "manage_admins")
    ), 
    "Information"=>array
    (
      array("resources", "information", "list_resources", "list_resources")
    )
  );

  // If a student is being dealt with, add a dynamic menu
  if(isset($_SESSION['student_id']))
  {
    require_once("model/Student.class.php");
    $student_name = Student::get_name($_SESSION['student_id']);
    if(!strlen($student_name)) $student_name="Student";

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