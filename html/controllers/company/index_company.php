<?php

/**
* Defines and handles the Company Contact HR Menu
* @package OPUS
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
*/

/**
* Defines and handles the Company Contact HR Menu
*
* This is a complicated example since many aspects of the Administrator menu
* are dynamic.
*
* @return a multidimensinal array that defines menu structure
*
*/
function nav_admin() 
{
  $basic_nav = array
  (
    "home"=>array
    (
      array("home", "home", "home", "home"),
      array("change password", "home", "change_password", "change_password")
    ),
    "directories"=>array
    (
      array("companies", "directories", "company_directory", "company_directory"),
      array("vacancies", "directories", "vacancy_directory", "vacancy_directory"),
      array("contacts", "directories", "contact_directory", "contact_directory"),
    ),
    "information"=>array
    (
      array("resources", "information", "list_resources", "list_resources"),
      array("help directory", "information", "help_directory", "help_directory")
    )
  );

  // If a company is being dealt with, add a dynamic menu
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

  if(isset($_SESSION['company_id']))
  {
    $nav = array_merge_recursive($nav, $company_nav);
  }

  // Finally add the recent items
  return($nav);
}

?>