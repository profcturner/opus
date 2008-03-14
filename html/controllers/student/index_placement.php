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
      $last_login = $waf->user['opus']['last_login'];
      $pd = date_parse($last_login);
      $since = sprintf("%04u%02u%02u%02u%02u%02u", $pd['year'], $pd['month'], $pd['day'], $pd['hour'], $pd['minute'], $pd['second']);
    }
    $waf->assign("since", $since);

    require_once("model/Vacancy.class.php");
    require_once("model/Company.class.php");

    $vacancy = new Vacancy;
    $company = new Company;
    $vacancies_created = $vacancy->_get_all("where created > " . $since);
    $vacancies_modified = $vacancy->_get_all("where modified > " . $since);
    $companies_created = $company->_get_all("where created > " . $since);
    $companies_modified = $company->_get_all("where modified > " . $since);

    $vacancy_headings = array(
      'description'=>array('type'=>'text','size'=>30, 'header'=>true, 'listclass'=>'vacancy_description'),
      'company_id'=>array('type'=>'lookup', 'size'=>30, 'header'=>true, 'title'=>'Company Name'),
      'locality'=>array('type'=>'list','size'=>30, 'header'=>true, 'listclass'=>'vacancy_locality'),
      'status'=>array('type'=>'text','size'=>30, 'header'=>true, 'listclass'=>'vacancy_status')
    );

    $company_headings = array(
      'name'=>array('type'=>'text','size'=>30, 'header'=>true),
      'locality'=>array('type'=>'list','size'=>30, 'header'=>true)
    );

    $vacancy_actions = array(array('view', 'view_vacancy', 'placement'));
    $company_actions = array(array('view', 'view_company', 'placement'));

    require_once("model/Student.class.php");
    $student = Student::load_by_user_id(User::get_id());

    $waf->assign("student", $student);
    $waf->assign("vacancies_created", $vacancies_created);
    $waf->assign("vacancies_modified", $vacancies_modified);
    $waf->assign("companies_created", $companies_created);
    $waf->assign("companies_modified", $companies_modified);
    $waf->assign("vacancy_headings", $vacancy_headings);
    $waf->assign("company_headings", $company_headings);
    $waf->assign("vacancy_actions", $vacancy_actions);
    $waf->assign("company_actions", $company_actions);
    $waf->assign("since", $since);

    $waf->display("main.tpl", "student:placement:placement_home:placement_home", "student/placement/placement_home.tpl");
  }

  function list_vacancies(&$opus, $user, $title)
  {
    manage_objects($opus, $user, "Vacancy", array(), array(array('view', 'view_vacancy')), "get_all", "", "student:placement:list_vacancies:list_vacancies");
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

    $waf->display("main.tpl", "student:placement:list_assessments:list_assessments", "general/assessment/assessment_results.tpl");
  }

  function list_resources(&$waf)
  {
    $waf->assign("nopage", true);

    manage_objects($waf, $user, "Resource", array(), array(array('view', 'view_resource'), array('info','info_resource')), "get_all", array("WHERE `company_id` is null or `company_id` = 0", "order by channel_id, description", 0), "student:placement:list_resources:list_resources");
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


  // Vacanies

  function vacancy_directory(&$waf, $user, $title)
  {
    require_once("model/Activitytype.class.php");
    require_once("model/Vacancytype.class.php");
    $activity_types = Activitytype::get_id_and_field("name");
    $vacancy_types = Vacancytype::get_id_and_field("name");
    $sort_types = array("name", "locality");
    $other_options = array("ShowClosed" => "Show Closed");

    require_once("model/Preference.class.php");
    $form_options = Preference::get_preference("vacancy_directory_form");

    $waf->assign("sort_types", $sort_types);
    $waf->assign("activity_types", $activity_types);
    $waf->assign("vacancy_types", $vacancy_types);
    $waf->assign("other_options", $other_options);
    $waf->assign("form_options", $form_options);

    $waf->display("main.tpl", "admin:directories:vacancy_directory:vacancy_directory", "admin/directories/vacancy_directory.tpl");
  }

  function search_vacancies(&$waf, $user, $title)
  {
    $search = WA::request("search");
    $year = WA::request("year");
    $activities = WA::request("activities");
    $vacancy_types = WA::request("vacancy_types");
    $sort = WA::request("sort");
    $other_options = WA::request("other_options");

    $form_options['search'] = $search;
    $form_options['year'] = $year;
    $form_options['activities'] = $activities;
    $form_options['vacancy_types'] = $vacancy_types;
    $form_options['sort'] = $sort;
    $form_options['other_options'] = $other_options;

    require_once("model/Preference.class.php");
    Preference::set_preference("vacancy_directory_form", $form_options);

    require_once("model/Vacancy.class.php");

    $waf->assign("activities", $activities);
    $waf->assign("vacancy_types", $vacancy_types);
    $waf->assign("vacancies", Vacancy::get_all_extended($search, $year, $activities, $vacancy_types, $sort, $other_options));
    $waf->display("main.tpl", "admin:directories:vacancy_directory:search_vacancies", "student/placement/search_vacancies.tpl");
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

    $waf->assign("activities", $activities);
    $waf->assign("companies", Company::get_all_extended($search, $activities, $sort));
    $waf->assign("action_links", array(array("add","section=directories&function=add_company")));
    $waf->display("main.tpl", "admin:directories:company_directory:search_companies", "student/placement/search_companies.tpl");
  }

  function view_company(&$waf, &$user)
  {
    // Did we get id passed in?
    $id = (int) WA::request("id");

    // If so use that
    if($id) $company_id = $id;
    else $company_id = (int) WA::request("company_id");

    if($_SESSION['company_id'] != $company_id)
    {
      // If this company isn't the active one, make it so
      $company_id = (int) WA::request("company_id", true);
      goto("placement", "view_company&company_id=$company_id");
    }

    require_once("model/Company.class.php");
    $company = Company::load_by_id($company_id);

    // Make "recent" menu entry
    $company_name = $company->name;
    $_SESSION['lastitems']->add_here("c:$company_name", "c:$company_id", "Company: $company_name");

    // Some lookups
    require_once("model/Activitytype.class.php");
    $company_activity_names = array();
    foreach($company->activity_types as $activity_type)
    {
      array_push($company_activity_names, Activitytype::get_name($activity_type));
    }

    require_once("model/Resource.class.php");
    $resources = Resource::get_all("where company_id=$company_id");
    $resource_headings = Resource::get_field_defs("company");
    $resource_actions = array(array("view", "view_company_resource", "placement"));

    $waf->assign("resources", $resources);
    $waf->assign("resource_headings", $resource_headings);
    $waf->assign("resource_actions", $resource_actions);
    $waf->assign("company", $company);
    $waf->assign("company_activity_names", $company_activity_names);

    $waf->display("main.tpl", "admin:directories:vacancy_directory:view_company", "admin/directories/view_company.tpl");
  }

  function view_company_resource(&$waf, &$user)
  {
    $id = (int) $_REQUEST["id"];
    require_once("model/Resource.class.php");

    Resource::view($id); 
  }

  function view_vacancy(&$waf, &$user)
  {
    $id = (int) WA::request("id");
    $student_id = $_SESSION["student_id"];

    require_once("model/Vacancy.class.php");
    $vacancy = Vacancy::load_by_id($id);

    // Make a "recent" menu item
    $vacancy_desc = $vacancy->description;
    $_SESSION['lastitems']->add_here("v:$vacancy_desc", "v:$id", "Vacancy: $vacancy_desc");

    // Some lookups
    require_once("model/Activitytype.class.php");
    $vacancy_activity_names = array();
    foreach($vacancy->activity_types as $activity_type)
    {
      array_push($vacancy_activity_names, Activitytype::get_name($activity_type));
    }

    require_once("model/Company.class.php");
    $company = new Company;
    $company = Company::load_by_id($vacancy->company_id);

    $company_activity_names = array();
    foreach($company->activity_types as $activity_type)
    {
      array_push($company_activity_names, Activitytype::get_name($activity_type));
    }

    $company_id = $vacancy->company_id;
    $action_links = array(array("apply", "section=placement&function=add_application&id=$id"));

    require_once("model/Resource.class.php");
    $resources = Resource::get_all("where company_id=" . $vacancy->company_id);
    $resource_headings = Resource::get_field_defs("company");
    $resource_actions = array(array("view", "view_company_resource", "placement"));

    $waf->assign("action_links", $action_links);
    $waf->assign("vacancy", $vacancy);
    $waf->assign("company", $company);
    $waf->assign("resources", $resources);
    $waf->assign("resource_headings", $resource_headings);
    $waf->assign("resource_actions", $resource_actions);
    $waf->assign("vacancy_activity_names", $vacancy_activity_names);
    $waf->assign("company_activity_names", $company_activity_names);
    $waf->assign("show_heading", true);

    $waf->display("main.tpl", "admin:directories:vacancy_directory:view_vacancy", "admin/directories/view_vacancy.tpl");
  }

  // Applications


  function list_applications(&$waf, $user, $title)
  {
    $student_id = User::get_id();
    $page = (int) WA::request("page", true);

    manage_objects($waf, $user, "Application", array(), array(array('edit', 'edit_application')), "get_all", array("where student_id=$student_id", "", $page), "student:placement:list_applications:list_applications");
  }

  /**
  * tag a student as having applied for a vacancy
  */
  function add_application(&$waf, &$user)
  {
    $vacancy_id = (int) WA::request("id");
    $student_id = User::get_id();

    require_once("model/Student.class.php");
    $student = Student::load_by_user_id($student_id);
    if($student->placement_status != 'Required') $waf->halt("error:student:not_required");

    // Get the available CVs, both good and bad
    require_once("model/CVCombined.class.php");
    $cv_list = CVCombined::fetch_cvs_for_student($student_id, true);
    foreach($cv_list as $cv)
    {
      if($cv->valid) $valid++;
      else $invalid++;
    }
    // Convert the valid ones to a pull down
    $cv_options = CVCombined::convert_cv_list_to_options($cv_list);

    $eportfolio_list = array("none:none:none" => 'None Available');

    require_once("model/Application.class.php");
    $application = new Application;

    require_once("model/Vacancy.class.php");
    require_once("model/Company.class.php");
    $application->student_id = $student_id;
    $application->vacancy_id = $vacancy_id;
    $application->company_id = Vacancy::get_company_id($vacancy_id);
    $application->_vacancy_id = Vacancy::get_name($vacancy_id);
    $application->_company_id = Company::get_name($application->company_id);

    $waf->assign("mode", "add");
    $waf->assign("is_student", true);
    $waf->assign("application", $application);
    $waf->assign("eportfolio_list", $eportfolio_list);
    $waf->assign("cv_list", $cv_list);
    $waf->assign("cv_options", $cv_options);
    $waf->assign("valid", $valid);
    $waf->assign("invalid", $invalid);
    $waf->display("main.tpl", "student:placement:list_applications:add_application", "admin/directories/edit_application.tpl");
  }

  function add_application_do(&$waf, &$user) 
  {
    $student_id = User::get_id();
    require_once("model/Student.class.php");
    $student = Student::load_by_user_id($student_id);
    if($student->placement_status != 'Required') $waf->halt("error:student:not_required");

    // Check the proposed CV is valid
    require_once("model/CVCombined.class.php");
    if(!CVCombined::check_cv_permission($student_id, WA::request('cv_ident'), &$problem)) $waf->halt("error:student:invalid_cv");

    // Check the vacancy...
    $vacancy_id = (int) WA::request('vacancy_id');
    require_once("model/Vacancy.class.php");
    $vacancy = Vacancy::load_by_id($vacancy_id);
    if($vacancy->status != 'open') $waf->halt("error:student:vacancy_not_open");

    // Check we haven't already applied
    require_once("model/Application.class.php");
    if(Application::count("where vacancy_id=$vacancy_id and student_id=$student_id")) $waf->halt("error:student:cannot_apply_twice");

    add_object_do($waf, $user, "Application", "section=placement&function=list_applications", "add_application");
  }

  /**
  * tag a student as having applied for a vacancy
  */
  function edit_application(&$waf, &$user)
  {
    $application_id = (int) WA::request("id");

    require_once("model/Application.class.php");
    $application = Application::load_by_id($application_id);
    $vacancy_id = $application->vacancy_id;
    $student_id = $application->student_id;

    if($student_id != User::get_id()) $waf->halt("error:student:not_your_user");
    require_once("model/Student.class.php");
    $student = Student::load_by_user_id($student_id);
    if($student->placement_status != 'Required') $waf->halt("error:student:not_required");

    // Get the available CVs, and *do* filter them
    require_once("model/CVCombined.class.php");
    $cv_list = CVCombined::fetch_cvs_for_student($student_id, true);
    foreach($cv_list as $cv)
    {
      if(!$cv->valid) $invalid++;
    }
    $cv_options = CVCombined::convert_cv_list_to_options($cv_list);

    $eportfolio_list = array("none:none:none" => 'None Available');

    require_once("model/Vacancy.class.php");
    require_once("model/Company.class.php");
    $application->student_id = $student_id;
    $application->vacancy_id = $vacancy_id;
    $application->company_id = Vacancy::get_company_id($vacancy_id);
    $application->_vacancy_id = Vacancy::get_name($vacancy_id);
    $application->_company_id = Company::get_name($application->company_id);

    $waf->assign("mode", "edit");
    $waf->assign("is_student", true);
    $waf->assign("application", $application);
    $waf->assign("eportfolio_list", $eportfolio_list);
    $waf->assign("cv_list", $cv_list);
    $waf->assign("cv_options", $cv_options);
    $waf->assign("invalid", $invalid);
    $waf->assign("selected_cv_ident", $application->cv_ident);
    $waf->display("main.tpl", "student:placement:list_applications:edit_application", "admin/directories/edit_application.tpl");
  }

  function edit_application_do(&$waf, &$user) 
  {
    $application_id = (int) WA::request("id");
    require_once("model/Application.class.php");
    $application = Application::load_by_id($application_id);

    $student_id = $application->student_id;

    if($student_id != User::get_id()) $waf->halt("error:student:not_your_user");
    require_once("model/Student.class.php");
    $student = Student::load_by_user_id($student_id);
    if($student->placement_status != 'Required') $waf->halt("error:student:not_required");

    edit_object_do($waf, $user, "Application", "section=placement&function=list_applications", "add_application");
  }

  // Notes

  /**
  * lists all notes associated with a given item
  */
  function list_notes(&$waf, &$user)
  {
    /*
    $object_type = WA::request("object_type");
    $object_id = (int) WA::request("object_id");
    */
    $object_type = "Student";
    $object_id = User::get_id();

    $action_links = array(array("add", "section=placement&function=add_note&object_type=$object_type&object_id=$object_id"));
    require_once("model/Note.class.php");
    $notes = Note::get_all_by_links($object_type, $object_id);
    $waf->assign("notes", $notes);
    $waf->assign("action_links", $action_links);

    $waf->display("main.tpl", "admin:directories:list_notes:list_notes", "admin/directories/search_notes.tpl");
  }

  /**
  * views a specific note
  * @todo show other linked items
  * @todo modify referer code to allow cleanurls
  */
  function view_note(&$waf, &$user)
  {
    $note_id = (int) WA::request("id");

    // Because notes are accessed from all over the place, we don't know where
    // to go back to. So, try and get the referring URL
    if(preg_match("/^.*?(section=.*)$/", $_SERVER['HTTP_REFERER'], $matches))
    {
      $action_links = array(array("back", $matches[1]));
      $waf->assign("action_links", $action_links);
    }
    require_once("model/Note.class.php");
    require_once("model/Notelink.class.php");

    $note = Note::load_by_id($note_id);
    $note_links = Notelink::get_all("where note_id=$note_id");

    $waf->assign("note", $note);
    $waf->assign("note_links", $note_links);

    $waf->display("main.tpl", "admin:directories:list_notes:view_note", "admin/directories/view_note.tpl");
  }

  function add_note(&$waf, &$user) 
  {
    add_object($waf, $user, "Note", array("add", "placement", "add_note_do"), array(array("cancel","section=placement&function=view_notes")), array(array("user_id",$user["user_id"])), "admin:directories:list_notes:add_note");
  }

  function add_note_do(&$waf, &$user) 
  {
    add_object_do($waf, $user, "Note", "section=placement&function=view_notes", "add_note");
  }

?>