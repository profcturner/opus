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


  // Assessments

  /**
  * show an assessment for viewing or editing
  */
  function edit_assessment(&$waf, &$user)
  {
    // Note security is handled internally by the AssessmentCombined object

    // Get the unique identifer for the assessment instance
    $regime_id = (int) WA::request("id");
    // and for whom
    $assessed_id = (int) WA::request("assessed_id");
    require_once("model/AssessmentCombined.class.php");
    $assessment = new AssessmentCombined($regime_id, $assessed_id, User::get_id());
    $waf->assign("assessment", $assessment);
    $waf->display("main.tpl", "admin:directories:edit_assessment:edit_assessment", "general/assessment/edit_assessment.tpl");
  }

  /**
  * process inbound assessment information
  */
  function edit_assessment_do(&$waf, &$user)
  {
    // Get the unique identifer for the assessment instance
    $regime_id = (int) WA::request("regime_id");
    // and for whom
    $assessed_id = (int) WA::request("assessed_id");
    require_once("model/AssessmentCombined.class.php");
    $assessment = new AssessmentCombined($regime_id, $assessed_id, User::get_id(), true); // try to save
    $waf->assign("assessment", $assessment);
    $waf->display("main.tpl", "admin:directories:edit_assessment:edit_assessment", "general/assessment/edit_assessment.tpl");
  }



  function list_assessments(&$waf)
  {
    $student_user_id = User::get_id();
    require_once("model/Student.class.php");
    $regime_items = Student::get_assessment_regime($student_user_id,  &$aggregate_total, &$weighting_total);
    $waf->assign("assessment_section", "placement");
    $waf->assign("regime_items", $regime_items);
    $waf->assign("assessed_id", $student_user_id);
    $waf->assign("aggregate_total", $aggregate_total);
    $waf->assign("weighting_total", $weighting_total);

    $waf->display("main.tpl", "student:myplacement:view_assessments:view_assessments", "general/assessment/assessment_results.tpl");
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
    $opus->display("main.tpl", "student:placement:info_resource:info_resource", "general/information/info_resource.tpl");
  }




?>