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
function nav_company() 
{
  $nav = array
  (
    "Home"=>array
    (
      array("home", "home", "home", "home"),
      array("change password", "home", "change_password", "change_password")
    ),
    /*"directories"=>array
    (
      array("companies", "directories", "company_directory", "company_directory"),
      array("vacancies", "directories", "vacancy_directory", "vacancy_directory"),
      array("contacts", "directories", "contact_directory", "contact_directory"),
    ),*/
    "Information"=>array
    (
      array("resources", "information", "list_resources", "list_resources"),
      array("help directory", "information", "help_directory", "help_directory")
    )
  );

  if(!isset($_SESSION['company_id']))
  {
    // No company being tracked, fetch the first one
    require_once("model/Contact.class.php");
    $companies = Contact::get_companies_for_contact(User::get_id());
    if(count($companies)) // This should always be true!
    {
      $first_company = each($companies);
      $_SESSION['company_id'] = $first_company['key'];
    }
  }

  // If a company is being dealt with, add a dynamic menu
  if(isset($_SESSION['company_id']))
  {
    $company_name = "Company";

    require_once("model/Company.class.php");
    $company_name = Company::get_name($_SESSION['company_id']);

    $company_nav = array
    (
      $company_name=>array
      (
        array("edit", "my_company", "edit_company", "edit_company"),
        array("view", "my_company", "view_company", "view_company"),
        array("vacancies", "my_company", "manage_vacancies", "manage_vacancies"),
        array("contacts", "my_company", "manage_contacts", "manage_contacts"),
        array("resources", "my_company", "manage_company_resources", "manage_company_resources"),
        array("notes", "my_company", "list_notes", "list_notes")
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