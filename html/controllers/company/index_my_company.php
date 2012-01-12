<?php

/**
* Company Menu for Company Contacts
*
* @package OPUS
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
*/

  /**
  * link back to the edit company page
  */
  function edit_company(&$waf)
  {
    $id = $_SESSION['company_id'];
    goto_section("directories", "edit_company&id=$id");
  }

  function view_company(&$waf)
  {
    $id = $_SESSION['company_id'];
    goto_section("directories", "view_company&company_id=$id");
  }

  function manage_company_resources(&$waf)
  {
    $id = $_SESSION['company_id'];
    goto_section("directories", "manage_company_resources&company_id=" . $_SESSION['company_id']);
  }

  function manage_vacancies(&$waf)
  {
    goto_section("directories", "manage_vacancies&company_id=" . $_SESSION['company_id']);
  }

  function manage_contacts(&$waf)
  {
    goto_section("directories", "manage_contacts&company_id=" . $_SESSION['company_id']);
  }

  function list_notes(&$waf)
  {
    goto_section("directories", "list_notes&object_type=Company&object_id=" . $_SESSION['company_id']);
  }

  /**
  * removes the company from the session
  */
  function drop_company(&$waf)
  {
    unset($_SESSION['company_id']);
    goto_section("home", "home");
  }

?>
