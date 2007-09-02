<?php

  function manage_companies(&$opus, $user, $title)
  {
    manage_objects($opus, $user, "Company", array(array("add","section=directories&function=add_company")), array(array('edit', 'edit_company'), array('remove','remove_company')), "get_all", "", "admin:directories:companies:manage_companies");
  }

  function add_company(&$opus, &$user) 
  {
    add_object($opus, $user, "Company", array("add", "directories", "add_company_do"), array(array("cancel","section=directories&function=manage_companies")), array(array("user_id",$user["user_id"])), "admin:directories:companies:add_company");
  }

  function add_company_do(&$opus, &$user) 
  {
    add_object_do($opus, $user, "Company", "section=directories&function=manage_companies", "add_company");
  }

  function edit_company(&$opus, &$user) 
  {
    edit_object($opus, $user, "Company", array("confirm", "directories", "edit_company_do"), array(array("cancel","section=directories&function=manage_companies")), array(array("user_id",$user["user_id"])), "admin:directories:companies:edit_company");
  }

  function edit_company_do(&$opus, &$user) 
  {
    edit_object_do($opus, $user, "Company", "section=directories&function=manage_companies", "edit_company");
  }

  function remove_company(&$opus, &$user) 
  {
    remove_object($opus, $user, "Company", array("remove", "directories", "remove_company_do"), array(array("cancel","section=directories&function=manage_companies")), "", "admin:directories:companies:remove_company");
  }

  function remove_company_do(&$opus, &$user) 
  {
    remove_object_do($opus, $user, "Company", "section=directories&function=manage_companies");
  }



  function manage_vacancies(&$opus, $user, $title)
  {
    manage_objects($opus, $user, "Vacancy", array(array("add","section=directories&function=add_vacancy")), array(array('edit', 'edit_vacancy'), array('remove','remove_vacancy')), "get_all", "", "admin:directories:vacancies:manage_vacancies");
  }

  function add_vacancy(&$opus, &$user) 
  {
    add_object($opus, $user, "Vacancy", array("add", "directories", "add_vacancy_do"), array(array("cancel","section=directories&function=manage_vacancies")), array(array("user_id",$user["user_id"])), "admin:directories:vacancies:add_vacancy");
  }

  function add_vacancy_do(&$opus, &$user) 
  {
    add_object_do($opus, $user, "Vacancy", "section=directories&function=manage_vacancies", "add_vacancy");
  }

  function edit_vacancy(&$opus, &$user) 
  {
    edit_object($opus, $user, "Vacancy", array("confirm", "directories", "edit_vacancy_do"), array(array("cancel","section=directories&function=manage_vacancies")), array(array("user_id",$user["user_id"])), "admin:directories:vacancies:edit_vacancy");
  }

  function edit_vacancy_do(&$opus, &$user) 
  {
    edit_object_do($opus, $user, "Vacancy", "section=directories&function=manage_vacancies", "edit_vacancy");
  }

  function remove_vacancy(&$opus, &$user) 
  {
    remove_object($opus, $user, "Vacancy", array("remove", "directories", "remove_vacancy_do"), array(array("cancel","section=directories&function=manage_vacancies")), "", "admin:directories:vacancies:remove_vacancy");
  }

  function remove_vacancy_do(&$opus, &$user) 
  {
    remove_object_do($opus, $user, "Vacancy", "section=directories&function=manage_vacancies");
  }



?>