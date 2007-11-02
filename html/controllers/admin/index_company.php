<?php

  /**
  * link back to the edit company page
  */
  function edit_company(&$waf)
  {
    $id = $_SESSION['company_id'];
    goto("directories", "edit_company&id=$id");
  }

  function view_company(&$waf)
  {
    $id = $_SESSION['company_id'];
    goto("directories", "view_company&company_id=$id");
  }

  function manage_vacancies(&$waf)
  {
    goto("directories", "manage_vacancies&company_id=" . $_SESSION['company_id']);
  }

  function manage_contacts(&$waf)
  {
    goto("directories", "manage_contacts&company_id=" . $_SESSION['company_id']);
  }

  function list_notes(&$waf)
  {
    goto("directories", "list_notes&object_type=Company&object_id=" . $_SESSION['company_id']);
  }

  /**
  * removes the company from the session
  */
  function drop_company(&$waf)
  {
    unset($_SESSION['company_id']);
    goto("home", "home");
  }

?>