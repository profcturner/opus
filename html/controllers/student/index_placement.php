<?php

  function placement_home(&$waf, $user, $title)
  {
    $days = (int) WA::request("days");
  
    if($days)
    {
      // Look for activity in the last few days
      $waf->assign("days", $days);
      $unixtime = time();
      $unixtime -= ($days * 24 * 60 * 60);
      $since = date("YmdHis", $unixtime);
    }
    else
    {
      // since the last login
      $since = $waf->user['opus']['last_login'];
      if(!$last_login) $since = 0;
    } 
  
    require_once("model/Vacancy.class.php");
    require_once("model/Company.class.php");
  
    $vacancies_created = Vacancy::get_all("where created > " . $since);
    $vacancies_modified = Vacancy::get_all("where modified > " . $since);
    $companies_created = Company::get_all("where created > " . $since);
    $companies_modified = Company::get_all("where modified > " . $since);
  
    $waf->assign("vacancies_created", $vacancies_created);
    $waf->assign("vacancies_modified", $vacancies_modified);
    $waf->assign("companies_created", $companies_created);
    $waf->assign("companies_modified", $companies_modified);
    $waf->assign("since", $since);
  
    $waf->display("main.tpl", "student:placement:placement_home:placement_home", "student/placement/placement_home.tpl");
  }

  function list_vacancies(&$opus, $user, $title)
  {
    manage_objects($opus, $user, "Vacancy", array(), array(array('view', 'view_vacancy')), "get_all", "", "student:placement:list_vacancies:list_vacancies");
  }

  function list_applications(&$opus, $user, $title)
  {
    manage_objects($opus, $user, "Application", array(), array(array('view', 'view_application')), "get_all", "", "student:placement:list_applications:list_applications");
  }


  function list_resources(&$opus, $user, $title)
  {
    manage_objects($opus, $user, "Resource", array(), array(array('view', 'view_resource'), array('info','info_resource')), "get_all", "", "student:placement:list_resources:list_resources");
  }

  function view_resource(&$opus, $user, $title)
  {
    $id = (int) $_REQUEST["id"];
    require_once("model/Resource.class.php");
   
    Resource::view($id); 
  }


  function info_resource(&$opus, $user, $title)
  {
    $id = (int) $_REQUEST["id"];
    require_once("model/Resource.class.php");

    $resource = Resource::load_by_id($id);

    $opus->assign("resource", $resource);
    $opus->display("main.tpl", "student:information:resources:info_resource", "general/information/info_resource.tpl");
  }




?>