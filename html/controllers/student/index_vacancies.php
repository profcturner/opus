<?php

  /**
  * displays student vacancies home page
  * 
  * this displays information about recently created / changes companies and
  * vacancies
  * 
  * 
  * @param $waf reference to the waf object
  * @todo this function is now getting too large, we will soon need to reduce
  */
  function vacancy_home($waf)
  {
    $days = (int) WA::request("days");

    // An installation in Edinburgh shows an odd bug caused by date_parse
    // not being present in a version of PHP that should support it.
    // This workaround prevents this crashing the home page, at the
    // expense of this functionality being slightly crippled.
    if(!function_exists("date_parse"))
    {
      $waf->log("date_parse not available, hardcode days to 7");
      $days = 7;
    }
    if($days)
    {
      // Look for activity in the last few days
      $waf->assign("days", $days);
      // Limit the search to a year
      if($days > 365) $days = 365;
      $unixtime = time();
      $unixtime -= ($days * 24 * 60 * 60);
      $since = date("YmdHis", $unixtime);
    }
    else
    {
      // since the last login
      $last_login = $waf->user['opus']['last_login'];
      if(!$last_login)
      {
				// New logins, try to restrict search a little...
      	$days = 60;
      	$unixtime = time();
      	$unixtime -= ($days * 24 * 60 * 60);
      	$since = date("YmdHis", $unixtime);
			}
			else
			{
      	$pd = date_parse($last_login);
      	$since = sprintf("%04u%02u%02u%02u%02u%02u", $pd['year'], $pd['month'], $pd['day'], $pd['hour'], $pd['minute'], $pd['second']);
			}
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

    $vacancy_actions = array(array('view', 'view_vacancy', 'no'));
    $company_actions = array(array('view', 'view_company'));

    require_once("model/Student.class.php");
    $student = Student::load_by_user_id(User::get_id());

    if($student->placement_status == 'Placed')
    {
      require_once("model/Placement.class.php");
      $placement = Placement::get_most_recent($student->user_id);
      if($placement == false)
      {
        // should never happen!
        $waf->log("can't find most recent placement for placed student");
      }
      else
      {
        $waf->assign("placement", $placement);
        $waf->assign("placement_headings", Placement::get_field_defs());
        $waf->assign("placement_action", array("edit", "placement", "list_placements"));
        
        $academic_user_id = Student::get_academic_user_id(User::get_id());
        if($academic_user_id)
        {
          // Academic tutor has been allocated
          require_once("model/Staff.class.php");
          $academic_tutor = Staff::load_by_user_id($academic_user_id);
          $waf->assign("academic_tutor", $academic_tutor);
          $academic_headings = array
          (
            'real_name'=>array('type'=>'text', 'size'=>50, 'title'=>'Name'),
            'school_id'=>array('type'=>'lookup', 'object'=>'school', 'value'=>'name', 'title'=>'School', 'size'=>20, 'var'=>'schools'),
            'position'=>array('type'=>'text','size'=>50,'header'=>true),
            'email'=>array('type'=>'email','size'=>40, 'header'=>true, 'mandatory'=>true),
            'voice'=>array('type'=>'text','size'=>40),
            'room'=>array('type'=>'text', 'size'=>10, 'header'=>true),
            'address'=>array('type'=>'textarea', 'rowsize'=>6, 'colsize'=>40),
            'postcode'=>array('type'=>'text', 'size'=>10),
          );
          $waf->assign("academic_headings", $academic_headings);
        }
      }
    }

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

    $waf->display("main.tpl", "student:vacancies:vacancy_home:vacancy_home", "student/vacancies/vacancy_home.tpl");
  }
  
  // Photos

  function display_photo($waf, $user)
  {
    $username = WA::request("username");
    $fullsize = WA::request("fullsize");
    require_once("model/Photo.class.php");

    Photo::display_photo($username, $fullsize);
  }

  // Assessments

  /**
  * show an assessment for viewing or editing
  */
  function edit_assessment($waf, $user)
  {
    // Note security is handled internally by the AssessmentCombined object

    // Get the unique identifer for the assessment instance
    $regime_id = (int) WA::request("id");
    // and for whom
    $assessed_id = (int) WA::request("assessed_id");
    require_once("model/AssessmentCombined.class.php");
    $assessment = new AssessmentCombined($regime_id, $assessed_id, User::get_id());
    $waf->assign("assessment", $assessment);
    $waf->display("main.tpl", "admin:directories:list_assessments:edit_assessment", "general/assessment/edit_assessment.tpl");
  }

  /**
  * process inbound assessment information
  */
  function edit_assessment_do($waf, $user)
  {
    // Get the unique identifer for the assessment instance
    $regime_id = (int) WA::request("regime_id");
    // and for whom
    $assessed_id = (int) WA::request("assessed_id");
    require_once("model/AssessmentCombined.class.php");
    $assessment = new AssessmentCombined($regime_id, $assessed_id, User::get_id(), true); // try to save
    $waf->assign("assessment", $assessment);
    $waf->display("main.tpl", "admin:directories:list_assessments:edit_assessment", "general/assessment/edit_assessment.tpl");
  }



  function list_assessments($waf)
  {
    $student_user_id = User::get_id();
    require_once("model/Student.class.php");
    $regime_items = Student::get_assessment_regime($student_user_id,  $aggregate_total, $weighting_total);
    $waf->assign("assessment_section", "placement");
    $waf->assign("regime_items", $regime_items);
    $waf->assign("assessed_id", $student_user_id);
    $waf->assign("aggregate_total", $aggregate_total);
    $waf->assign("weighting_total", $weighting_total);

    $waf->display("main.tpl", "student:placement:list_assessments:list_assessments", "general/assessment/assessment_results.tpl");
  }

  function list_resources($waf)
  {
    $waf->assign("nopage", true);

    manage_objects($waf, $user, "Resource", array(), array(array('view', 'view_resource', 'no'), array('info','info_resource')), "get_all", array("WHERE `company_id` is null or `company_id` = 0", "order by channel_id, description", 0), "student:placement:list_resources:list_resources");
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
    $opus->display("popup.tpl", "student:placement:info_resource:info_resource", "general/information/info_resource.tpl");
  }


  // Vacanies

  function vacancy_directory($waf, $user, $title)
  {
    require_once("model/Activitytype.class.php");
    require_once("model/Vacancytype.class.php");
    $activity_types = Activitytype::get_id_and_field("name");
    $vacancy_types = Vacancytype::get_id_and_field("name");
    $sort_types = array("name" => 'Name', "locality" => 'Locality', "closedate" => 'Closing date');
    $other_options = array("ShowClosed" => "Show Closed");

    require_once("model/Preference.class.php");
    $form_options = Preference::get_preference("vacancy_directory_form");

    $waf->assign("sort_types", $sort_types);
    $waf->assign("activity_types", $activity_types);
    $waf->assign("vacancy_types", $vacancy_types);
    $waf->assign("other_options", $other_options);
    $waf->assign("form_options", $form_options);
$section = "vacancies";

    $waf->display("main.tpl", "admin:directories:vacancy_directory:vacancy_directory", "admin/directories/vacancy_directory.tpl");
  }

  function search_vacancies($waf, $user, $title)
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
    $waf->display("main.tpl", "admin:directories:vacancy_directory:search_vacancies", "student/vacancies/search_vacancies.tpl");
  }

  function company_directory($waf, $user, $title)
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

  function search_companies($waf, $user, $title)
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
    $waf->display("main.tpl", "admin:directories:company_directory:search_companies", "student/vacancies/search_companies.tpl");
  }

  function view_company($waf, $user)
  {
    // Did we get id passed in?
    $id = (int) WA::request("id");

    // If so use that
    if($id) $company_id = $id;
    else $company_id = (int) WA::request("company_id");

    if($_SESSION['company_id'] != $company_id)
    {
      // If this company isn't the active one, make it so
      $_SESSION['company_id'] = $company_id;
      goto_section("vacancies", "view_company&company_id=$company_id");
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
    $resource_actions = array(array("view", "view_company_resource", "no"));

    $waf->assign("resources", $resources);
    $waf->assign("resource_headings", $resource_headings);
    $waf->assign("resource_actions", $resource_actions);
    $waf->assign("company", $company);
    $waf->assign("company_activity_names", $company_activity_names);

    $waf->display("popup.tpl", "admin:directories:vacancy_directory:view_company", "admin/directories/view_company.tpl");
  }

  function view_company_resource($waf, $user)
  {
    $id = (int) $_REQUEST["id"];
    require_once("model/Resource.class.php");

    Resource::view($id); 
  }

  function view_vacancy($waf, $user)
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
    $action_links = array(array("apply", "section=vacancies&function=add_application&id=$id", "thickbox"));

    require_once("model/Resource.class.php");
    $resources = Resource::get_all("where company_id=" . $vacancy->company_id);
    $resource_headings = Resource::get_field_defs("company");
    $resource_actions = array(array("view", "view_company_resource", "no"));

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

  function list_applications($waf, $user, $title)
  {
    $student_id = User::get_id();
    $page = (int) WA::request("page", true);

    manage_objects($waf, $user, "Application", array(), array(array('edit', 'edit_application')), "get_all", array("where student_id=$student_id", "", $page), "student:placement:list_applications:list_applications");
  }

  /**
  * tag a student as having applied for a vacancy
  */
  function add_application($waf, $user)
  {
    $vacancy_id = (int) WA::request("id");
    $student_id = User::get_id();

    require_once("model/Student.class.php");
    if(!Student::is_application_allowed($student_id)) //$waf->halt("error:student:not_required");
		{
			$waf->display("popup.tpl", "error:student:not_required", "error.tpl");
		}
	else
	{
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
		$waf->display("popup.tpl", "student:placement:list_applications:add_application", "admin/directories/edit_application.tpl");
	}
  }

  function add_application_do($waf, $user) 
  {
    $student_id = User::get_id();
    require_once("model/Student.class.php");
    if(!Student::is_application_allowed($student_id)) $waf->halt("error:student:not_required");

    // Check the proposed CV is valid
    require_once("model/CVCombined.class.php");
    if(!CVCombined::check_cv_permission($student_id, WA::request('cv_ident'), $problem)) $waf->halt("error:student:invalid_cv");

    // Check the vacancy...
    $vacancy_id = (int) WA::request('vacancy_id');
    require_once("model/Vacancy.class.php");
    $vacancy = Vacancy::load_by_id($vacancy_id);
    if($vacancy->status != 'open') $waf->halt("error:student:vacancy_not_open");

    // Check we haven't already applied
    require_once("model/Application.class.php");
    if(Application::count("where vacancy_id=$vacancy_id and student_id=$student_id")) $waf->halt("error:student:cannot_apply_twice");

    add_object_do($waf, $user, "Application", "section=vacancies&function=list_applications", "add_application");
  }

  /**
  * tag a student as having applied for a vacancy
  */
  function edit_application($waf, $user)
  {
    $application_id = (int) WA::request("id");

    require_once("model/Application.class.php");
    $application = Application::load_by_id($application_id);
    $vacancy_id = $application->vacancy_id;
    $student_id = $application->student_id;

    //if($student_id != User::get_id()) $waf->halt("error:student:not_your_user");
    require_once("model/Student.class.php");
    $student = Student::load_by_user_id($student_id);
    //if($student->placement_status != 'Required') $waf->halt("error:student:not_required");
	if($student->placement_status != 'Required')
	{
		$waf->display("popup.tpl", "error:student:not_required", "error.tpl");
    }
    elseif($student_id != User::get_id())
    {
		$waf->display("popup.tpl", "error:student:not_your_user", "error.tpl");
    }
    else
    {
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
		$waf->display("popup.tpl", "student:placement:list_applications:edit_application", "admin/directories/edit_application.tpl");
	}
  }

  function edit_application_do($waf, $user) 
  {
    $application_id = (int) WA::request("id");
    require_once("model/Application.class.php");
    $application = Application::load_by_id($application_id);

    $student_id = $application->student_id;

    if($student_id != User::get_id()) $waf->halt("error:student:not_your_user");
    require_once("model/Student.class.php");
    $student = Student::load_by_user_id($student_id);
    if($student->placement_status != 'Required') $waf->errors = "yes";//$waf->halt("error:student:not_required");

    edit_object_do($waf, $user, "Application", "section=vacancies&function=list_applications", "add_application");
  }

?>
