<?php

  function student_directory(&$waf, $user, $title)
  {
    $waf->display("main.tpl", "admin:directories:student_directory:student_directory", "admin/directories/student_directory.tpl");
  }

  function vacancy_directory(&$waf, $user, $title)
  {
    require_once("model/Activitytype.class.php");
    $activity_types = Activitytype::get_id_and_field("name");
    $sort_types = array("name", "locality");
    $other_options = array("ShowClosed" => "Show Closed", "ShowCompanies" => "Show Companies", "ShowVacancies" => "Show Vacancies");

    require_once("model/Preference.class.php");
    $form_options = Preference::get_preference("vacancy_directory_form");

    $waf->assign("sort_types", $sort_types);
    $waf->assign("activity_types", $activity_types);
    $waf->assign("other_options", $other_options);
    $waf->assign("form_options", $form_options);

    $waf->display("main.tpl", "admin:directories:vacancy_directory:vacancy_directory", "admin/directories/vacancy_directory.tpl");
  }

  function search_vacancies(&$waf, $user, $title)
  {
    $search = WA::request("search");
    $year = WA::request("year");
    $activities = WA::request("activities");
    $sort = WA::request("sort");
    $other_options = WA::request("other_options");

    $form_options['search'] = $search;
    $form_options['year'] = $year;
    $form_options['activities'] = $activities;
    $form_options['sort'] = $sort;
    $form_options['other_options'] = $other_options;

    require_once("model/Preference.class.php");
    Preference::set_preference("vacancy_directory_form", $form_options);

    require_once("model/Vacancy.class.php");
    $waf->assign("vacancies", Vacancy::get_all_extended($search, $year, $activities, $sort, $other_options));
    $waf->display("main.tpl", "admin:directories:vacancy_directory:search_vacancies", "admin/directories/search_vacancies.tpl");
  }

  function company_directory(&$waf, $user, $title)
  {
    require_once("model/Activitytype.class.php");
    $activity_types = Activitytype::get_id_and_field("name");
    $sort_types = array("name", "locality");

    require_once("model/Preference.class.php");
    $form_options = Preference::get_preference("company_directory_form");

    $waf->assign("sort_types", $sort_types);
    $waf->assign("activity_types", $activity_types);
    $waf->assign("form_options", $form_options);

    $waf->display("main.tpl", "admin:directories:company_directory:company_directory", "admin/directories/company_directory.tpl");
  }

  function search_companies(&$waf, $user, $title)
  {
    $search = WA::request("search");
    $activities = WA::request("activities");
    $sort = WA::request("sort");

    $form_options['search'] = $search;
    $form_options['activities'] = $activities;
    $form_options['sort'] = $sort;

    require_once("model/Preference.class.php");
    Preference::set_preference("company_directory_form", $form_options);

    require_once("model/Company.class.php");
    // A simplification, doesn't honour switches yet...
    $waf->assign("companies", Company::get_all());
    $waf->assign("action_links", array(array("add","section=directories&function=add_company")));
    $waf->display("main.tpl", "admin:directories:company_directory:search_companies", "admin/directories/search_companies.tpl");
  }


  function manage_companies(&$waf, $user, $title)
  {
    manage_objects($waf, $user, "Company", array(array("add","section=directories&function=add_company")), array(array('edit', 'edit_company'), array('remove','remove_company')), "get_all", "", "admin:directories:companies:manage_companies");
  }

  function add_company(&$waf, &$user) 
  {
    add_object($waf, $user, "Company", array("add", "directories", "add_company_do"), array(array("cancel","section=directories&function=manage_companies")), array(array("user_id",$user["user_id"])), "admin:directories:companies:add_company");
  }

  function add_company_do(&$waf, &$user) 
  {
    add_object_do($waf, $user, "Company", "section=directories&function=manage_companies", "add_company");
  }

  function edit_company(&$waf, &$user) 
  {
    edit_object($waf, $user, "Company", array("confirm", "directories", "edit_company_do"), array(array("cancel","section=directories&function=manage_companies")), array(array("user_id",$user["user_id"])), "admin:directories:companies:edit_company");
  }

  function edit_company_do(&$waf, &$user) 
  {
    edit_object_do($waf, $user, "Company", "section=directories&function=manage_companies", "edit_company");
  }

  function remove_company(&$waf, &$user) 
  {
    remove_object($waf, $user, "Company", array("remove", "directories", "remove_company_do"), array(array("cancel","section=directories&function=manage_companies")), "", "admin:directories:companies:remove_company");
  }

  function remove_company_do(&$waf, &$user) 
  {
    remove_object_do($waf, $user, "Company", "section=directories&function=manage_companies");
  }



  function manage_vacancies(&$waf, $user, $title)
  {
    manage_objects($waf, $user, "Vacancy", array(array("add","section=directories&function=add_vacancy")), array(array('edit', 'edit_vacancy'), array('remove','remove_vacancy')), "get_all", "", "admin:directories:vacancies:manage_vacancies");
  }

  function add_vacancy(&$waf, &$user) 
  {
    add_object($waf, $user, "Vacancy", array("add", "directories", "add_vacancy_do"), array(array("cancel","section=directories&function=manage_vacancies")), array(array("user_id",$user["user_id"])), "admin:directories:vacancies:add_vacancy");
  }

  function add_vacancy_do(&$waf, &$user) 
  {
    add_object_do($waf, $user, "Vacancy", "section=directories&function=manage_vacancies", "add_vacancy");
  }

  function edit_vacancy(&$waf, &$user) 
  {
    edit_object($waf, $user, "Vacancy", array("confirm", "directories", "edit_vacancy_do"), array(array("cancel","section=directories&function=manage_vacancies")), array(array("user_id",$user["user_id"])), "admin:directories:vacancy_directory:edit_vacancy", "admin/directories/edit_vacancy.tpl");
  }

  function edit_vacancy_do(&$waf, &$user) 
  {
    edit_object_do($waf, $user, "Vacancy", "section=directories&function=manage_vacancies", "edit_vacancy");
  }

  function remove_vacancy(&$waf, &$user) 
  {
    remove_object($waf, $user, "Vacancy", array("remove", "directories", "remove_vacancy_do"), array(array("cancel","section=directories&function=manage_vacancies")), "", "admin:directories:vacancies:remove_vacancy");
  }

  function remove_vacancy_do(&$waf, &$user) 
  {
    remove_object_do($waf, $user, "Vacancy", "section=directories&function=manage_vacancies");
  }


  function manage_contacts(&$waf, $user, $title)
  {
    $company_id = (int) WA::request("company_id", true);
    if($company_id)
    {
      $where_clause="where company_id='$company_id'";
    }
    else $where_clause="";

    manage_objects($waf, $user, "Contact", array(array("add","section=directories&function=add_contact")), array(array('edit', 'edit_contact'), array('remove','remove_contact')), "get_all", $where_clause, "admin:directories:contacts:manage_contacts");
  }

  function add_contact(&$waf, &$user) 
  {
    add_object($waf, $user, "Contact", array("add", "directories", "add_contact_do"), array(array("cancel","section=directories&function=manage_contacts")), array(array("user_id",$user["user_id"])), "admin:directories:contacts:add_contact");
  }

  function add_contact_do(&$waf, &$user) 
  {
    add_object_do($waf, $user, "Contact", "section=directories&function=manage_contacts", "add_contact");
  }

  function edit_contact(&$waf, &$user) 
  {
    edit_object($waf, $user, "Contact", array("confirm", "directories", "edit_contact_do"), array(array("cancel","section=directories&function=manage_contacts")), array(array("user_id",$user["user_id"])), "admin:directories:contacts:edit_contact");
  }

  function edit_contact_do(&$waf, &$user) 
  {
    edit_object_do($waf, $user, "Contact", "section=directories&function=manage_contacts", "edit_contact");
  }

  function remove_contact(&$waf, &$user) 
  {
    remove_object($waf, $user, "Contact", array("remove", "directories", "remove_contact_do"), array(array("cancel","section=directories&function=manage_contacts")), "", "admin:directories:contacts:remove_contact");
  }

  function remove_contact_do(&$waf, &$user) 
  {
    remove_object_do($waf, $user, "Contact", "section=directories&function=manage_contacts");
  }





?>