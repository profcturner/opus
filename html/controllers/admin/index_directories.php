<?php

  /**
  * Directory Menu for Administrators
  *
  * @package OPUS
  * @author Colin Turner <c.turner@ulster.ac.uk>
  * @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
  */

  function student_directory(&$waf, $user, $title)
  {
    if(!Policy::check_default_policy("student", "list")) $waf->halt("error:policy:permissions");

    $letters = array();
    for($loop = ord('A'); $loop <= ord('Z'); $loop++) array_push($letters, chr($loop));
    $waf->assign("letters", $letters);

    $sort_types = array("lastname" => "Last name", "reg_number" => "Student Number", "last_time" => "Last Access", "placement_status" => "Placement Status");
    $other_options = array("ShowTimelines" => "Show Timelines");

    require_once("model/Preference.class.php");
    $form_options = Preference::get_preference("student_directory_form");

    $waf->assign("sort_types", $sort_types);
    $waf->assign("other_options", $other_options);
    $waf->assign("form_options", $form_options);

    require_once("model/Programme.class.php");
    $waf->assign("structure", Programme::get_all_organisation());

    $waf->display("main.tpl", "admin:directories:student_directory:student_directory", "admin/directories/student_directory.tpl");
  }

  function search_students(&$waf)
  {
    if(!Policy::check_default_policy("student", "list")) $waf->halt("error:policy:permissions");

    $search = WA::request("search");
    $year = WA::request("year");
    $programmes = WA::request("programmes");
    $sort = WA::request("sort");
    $other_options = WA::request("other_options");

    $form_options['search'] = $search;
    $form_options['year'] = $year;
    $form_options['programmes'] = $programmes;
    $form_options['sort'] = $sort;
    $form_options['other_options'] = $other_options;

    require_once("model/Preference.class.php");
    Preference::set_preference("student_directory_form", $form_options);

    require_once("model/Student.class.php");
    $objects = Student::get_all_extended($search, $year, $programmes, $sort, $other_options);

    $other_options = WA::request("other_options");
    if(empty($other_options)) $other_options = array();
    if(in_array("ShowTimelines", $other_options)) $waf->assign("show_timelines", true);

    $waf->assign("students", $objects);
    $waf->assign("action_links", array(array('add', 'section=directories&function=add_student')));
    $waf->assign("student_count", count($objects));
    $waf->display("main.tpl", "admin:directories:student_directory:search_students", "admin/directories/search_students.tpl");
  }

  /**
  * searchs for students by first letter of initial
  */
  function simple_search_student(&$waf)
  {
    if(!Policy::check_default_policy("student", "list")) $waf->halt("error:policy:permissions");

    require_once("model/Student.class.php");
    $initial = WA::request("initial");

    require_once("model/Student.class.php");
    $objects = Student::get_all_by_initial($initial);

    $other_options = WA::request("other_options");
    if(empty($other_options)) $other_options = array();
    if(in_array("ShowTimelines", $other_options)) $waf->assign("show_timelines", true);

    $waf->assign("students", $objects);
    $waf->assign("action_links", array(array('add', 'section=directories&function=add_student')));
    $waf->assign("student_count", count($objects));
    $waf->display("main.tpl", "admin:directories:student_directory:search_students", "admin/directories/search_students.tpl");
  }

  function mass_email(&$waf)
  {
    $users = WA::request('users');
    $message = WA::request('message');
    $cc_me = WA::request('CC');
    $subject = WA::request('subject');
    $redirect_url = WA::request("redirect_url");

    $valid_recipients = array();
    $invalid_recipients = array();

    if(!empty($message) && count($users))
    {
      require_once("model/User.class.php");
      $sender_details = User::load_by_id(User::get_id());

      $sender_email =
        $sender_details->real_name . " <" . $sender_details->email . ">";

      $to_email =
        "Undisclosed Recipients <" . $sender_details->email . ">";

      $extra = "From: $sender_email\r\n";
      if($cc_me) $extra .= "Cc: $sender_email\r\n";

      $bcc = "bcc: ";

      foreach($users as $user_id)
      {
        $user = User::load_by_id($user_id);

        $user_email = $user->real_name . " <" . $user->email . ">";

        // Do we in fact, have an email address to send to?
        if(strlen($user->email))
        {
          $bcc .= $user_email . ", ";
          array_push($valid_recipients, $user);
        }
        else
        {
          array_push($invalid_recipients, $user);
        }
      }

      // Trim extra comma off
      $bcc = substr($bcc, 0, -2) . "\r\n";
      $extra .= $bcc;

      require_once("model/OPUSMail.class.php");

      $new_mail = new OPUSMail($to_email, $subject, $message, $extra);
      $new_mail->send();
    }
    else
    {
      $waf->assign("invalid_email", true);
      // No message, or no users selected
    }
    $waf->assign("action_links", array(array('done', $redirect_url)));
    $waf->assign("valid_recipients", $valid_recipients);
    $waf->assign("invalid_recipients", $invalid_recipients);
    $waf->display("main.tpl", "admin:directories:mass_email:mass_email", "admin/directories/mass_email.tpl");
  }


  function add_student(&$waf, &$user) 
  {
    if(!Policy::check_default_policy("student", "create")) $waf->halt("error:policy:permissions");

    global $config_sensitive;

    if(!empty($config_sensitive['ws']['url'])) $waf->assign("ws_enabled", true);
    else $waf->assign("ws_enabled", false);

    add_object($waf, $user, "Student", array("add", "directories", "add_student_do"), array(array("cancel","section=directories&function=student_directory")), array(array("user_id",$user["user_id"])), "admin:directories:student_directory:add_student", "admin/directories/add_student.tpl");
  }

  function add_student_do(&$waf, &$user) 
  {
    if(!Policy::check_default_policy("student", "create")) $waf->halt("error:policy:permissions");

    add_object_do($waf, $user, "Student", "section=directories&function=student_directory", "add_student");
  }
  
  function auto_add_student_do(&$waf, &$user)
  {
    $reg_number = WA::request("reg_number");
    
    require_once("model/StudentImport.class.php");
    $student_user_id = StudentImport::auto_add_student($reg_number);
    
    if($student_user_id)
    {
      // We were able to add them, let's "edit" for better info
      $SESSION['student_id'] = $student_user_id;
      goto("directories", "edit_student&student_id=$student_user_id");
    }
    else
    {
      // Something went wrong...
      $waf->halt("error:student_import:cannot_import");
    }
  }

  /**
  * pick up the student into the session so that the context menu appears
  *
  * this is a little complex, since it is often handed the id from the student table
  * as part of the framework, or the student_id (id from the user table) in most of
  * the other workflow.
  */
  function edit_student(&$waf, &$user) 
  {
    require_once("model/Student.class.php");
    // Prefer a passed in student_id, i.e. the id from the user table for the student
    $student_id = WA::request("student_id");
    $id = WA::request("id");

    if(!$student_id && !$id)
    {
      // No inbound clues, is there a student in the session? If so, use it.
      $student_id = $_SESSION['student_id'];
      if(!$student_id) $waf->halt("error:edit_student:no_student");
    }
    if($student_id)
    {
      $student = Student::load_by_user_id($student_id);
      $id = $student->id;
    }
    else
    {
      // We don't have a user id, but the id from the student table
      $id = WA::request("id");
      $student = Student::load_by_id($id);
      $student_id = $student->user_id;
    }
    if(!$student->id) $waf->halt("error:edit_student:invalid_student");
    if(!Policy::is_auth_for_student($student_id, "student", "viewStatus")) $waf->halt("error:policy:permissions");

    // Put this in the session for next time
    $_SESSION['student_id'] = $student_id;

    $changes = WA::request("changes");

    $student_name = User::get_name($student_id);
    $_SESSION['lastitems']->add_here("s:$student_name", "s:$student_id", "Student: $student_name");

    goto("directories", "edit_student_real&id=$id&changes=$changes");
  }

  /**
  * shows the edit student dialog once the student is in the session
  */
  function edit_student_real(&$waf, &$user)
  {
    require_once("model/Student.class.php");
    require_once("model/Policy.class.php");

    $id = (int) WA::request("id");
    $student = Student::load_by_id($id);

    if(!Policy::is_auth_for_student($student->user_id, "student", "viewStatus")) $waf->halt("error:policy:permissions");
    $assessment_group_id = Student::get_assessment_group_id($student->user_id);
    $regime_items = Student::get_assessment_regime($student->user_id, &$aggregate_total, &$weighting_total);
    $other_items = Student::get_other_assessors($student->user_id);
    require_once("model/Placement.class.php");
    $placements = Placement::get_all("where student_id=" . $student->user_id, "order by jobstart");
    $placement_fields = array(
       'position'=>array('type'=>'text', 'size'=>30, 'maxsize'=>100, 'title'=>'Job Description','header'=>true),
       'company_id'=>array('type'=>'lookup', 'size'=>30, 'maxsize'=>100, 'title'=>'Company','header'=>true),
       'jobstart'=>array('type'=>'text', 'size'=>20, 'title'=>'Start','header'=>true),
       'jobend'=>array('type'=>'text', 'size'=>20, 'title'=>'End','header'=>true)
    );
    $placement_options = array(array('edit', 'edit_placement'), array('remove','remove_placement'), array('vacancy', 'view_placement_vacancy'));
    require_once("model/Staff.class.php");
    $academic_tutors = Staff::lookup_tutors_by_school(); // this wasn't intended to be called directly, rework...

    $waf->assign("changes", WA::request("changes"));
    $waf->assign("assessment_group_id", $assessment_group_id);
    $waf->assign("regime_items", $regime_items);
    $waf->assign("other_items", $other_items);
    $waf->assign("assessed_id", $student->user_id);
    $waf->assign("aggregate_total", $aggregate_total);
    $waf->assign("weighting_total", $weighting_total);
    $waf->assign("placements", $placements);
    $waf->assign("placement_fields", $placement_fields);
    $waf->assign("placement_options", $placement_options);
    $waf->assign("academic_tutors", $academic_tutors);

    edit_object($waf, $user, "Student", array("confirm", "directories", "edit_student_do"), array(array("cancel","section=directories&function=student_directory"), array("reset password", "section=directories&function=reset_password&user_id=" . $student->user_id), array("manage applications", "section=directories&function=manage_applications&page=")), array(array("user_id", $student->user_id)), "admin:directories:student_directory:edit_student", "admin/directories/edit_student.tpl");
  }

  /**
  * processes changes to a student
  */
  function edit_student_do(&$waf, &$user) 
  {
    $id = (int) WA::request("id");
    // Need the id from the user table
    require_once("model/Student.class.php");
    $student_id = Student::get_user_id($id);

    if(!Policy::is_auth_for_student($student_id, "student", "editStatus")) $waf->halt("error:policy:permissions");

    edit_object_do($waf, $user, "Student", "section=directories&function=edit_student&id=$id&changes=1", "edit_student_real");
  }

  function edit_assessor_do(&$waf)
  {
    $student_id = (int) WA::request("student_id");
    require_once("model/Student.class.php");
    $student_user_id = Student::get_user_id($student_id);

    require_once("model/AssessorOther.class.php");

    // Remove current associations
    AssessorOther::remove_where("where assessed_id=$student_user_id");

    // Write new ones
    $other_items = Student::get_other_assessors($student_user_id);
    foreach($other_items as $item)
    {
      $assessor_id = (int) WA::request("tutor_" . $item->id);
      if($assessor_id) // Don't write "no assessor"
      {
        $fields['regime_id'] = $item->id;
        $fields['assessor_id'] = $assessor_id;
        $fields['assessed_id'] = $student_user_id;

        AssessorOther::insert($fields);
      }
    }
    goto("directories", "edit_student_real&id=$student_id&changes=1");
  }

  function remove_student(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    remove_object($waf, $user, "Student", array("remove", "directories", "remove_student_do"), array(array("cancel","section=directories&function=manage_students")), "", "admin:directories:student_directory:remove_student");
  }

  function remove_student_do(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    remove_object_do($waf, $user, "Student", "section=directories&function=manage_students");
  }

  // Channels

  function list_student_channels(&$waf)
  {
    $student_id = (int) WA::request("student_id", true);

    if(!Policy::is_auth_for_student($student_id, "student", "viewStatus")) $waf->halt("error:policy:permissions");

    require_once("model/Channel.class.php");
    $channels = Channel::get_channels_for_user($student_id);
    $all_channels = Channel::get_all();

    $waf->assign("channels", $channels);
    $waf->assign("all_channels", $all_channels);
    $waf->assign("student_id", $student_id);

    $waf->display("main.tpl", "admin:directories:student_directory:list_student_channels", "admin/directories/list_student_channels.tpl");
  }

  // CVs

  function list_student_cvs(&$waf)
  {
    $student_id = (int) WA::request("student_id", true);

    if(!Policy::is_auth_for_student($student_id, "student", "viewStatus")) $waf->halt("error:policy:permissions");

    require_once("model/CVCombined.class.php");
    $cvs = CVCombined::fetch_cvs_for_student($student_id);

    $waf->assign("student_id", $student_id);
    $waf->assign("cvs", $cvs);
    $waf->assign("action_links", array(array('manage internal cvs', 'section=directories&function=manage_cvs')));    

    $waf->display("main.tpl", "admin:directories:student_directory:display_cv_list", "admin/directories/list_student_cvs.tpl");
  }

  function view_cv(&$waf)
  {
    $student_id = (int) WA::request("student_id", true);
    $cv_ident = WA::request("cv_ident");

    // security is handled in the CVCombined object
    require_once("model/CVCombined.class.php");
    CVCombined::view_cv($student_id, $cv_ident);
  }

  function approve_cv(&$waf)
  {
    $student_id = (int) WA::request("student_id", true);
    $cv_ident = WA::request("cv_ident");

    if(!Policy::is_auth_for_student($student_id, "student", "editStatus")) $waf->halt("error:policy:permissions");

    require_once("model/CVApproval.class.php");
    CVApproval::approve_cv($student_id, $cv_ident);

    goto("directories", "list_student_cvs");
  }

  function revoke_cv(&$waf)
  {
    $student_id = (int) WA::request("student_id", true);
    $cv_ident = WA::request("cv_ident");

    if(!Policy::is_auth_for_student($student_id, "student", "editStatus")) $waf->halt("error:policy:permissions");

    require_once("model/CVApproval.class.php");
    CVApproval::revoke_cv($student_id, $cv_ident);

    goto("directories", "list_student_cvs");
  }

  // Timelines

  function display_timeline(&$waf, &$user)
  {
    $student_id = (int) WA::request("student_id");
    require_once("model/Timeline.class.php");

    if(!Policy::is_auth_for_student($student_id, "student", "viewStatus")) $waf->halt("error:policy:permissions");

    Timeline::display_timeline($student_id);
  }

  // Photos

  function display_photo(&$waf, &$user)
  {
    $username = WA::request("username");
    $fullsize = WA::request("fullsize");
    require_once("model/Photo.class.php");

    Photo::display_photo($username, $fullsize);
  }

  // Vacanies

  function vacancy_directory(&$waf, $user, $title)
  {
    require_once("model/Activitytype.class.php");
    require_once("model/Vacancytype.class.php");
    $activity_types = Activitytype::get_id_and_field("name");
    $vacancy_types = Vacancytype::get_id_and_field("name");
    $sort_types = array("name" => 'Name', "locality" => 'Locality', "closedate" => 'Closing date');
    $other_options = array("ShowClosed" => "Show Closed");
    //, "ShowCompanies" => "Show Companies", "ShowVacancies" => "Show Vacancies");

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

    $waf->assign("activities", $activities);
    $waf->assign("companies", Company::get_all_extended($search, $activities, $sort));
    $waf->assign("action_links", array(array("add","section=directories&function=add_company")));
    $waf->display("main.tpl", "admin:directories:company_directory:search_companies", "admin/directories/search_companies.tpl");
  }

  function add_company(&$waf, &$user) 
  {
    if(!Policy::check_default_policy("company", "create")) $waf->halt("error:policy:permissions");

    add_object($waf, $user, "Company", array("add", "directories", "add_company_do"), array(array("cancel","section=directories&function=company_directory")), array(array("user_id",$user["user_id"])), "admin:directories:companies:add_company");
  }

  function add_company_do(&$waf, &$user) 
  {
    if(!Policy::check_default_policy("company", "create")) $waf->halt("error:policy:permissions");

    add_object_do($waf, $user, "Company", "section=directories&function=company_directory", "add_company");
  }

  function edit_company(&$waf, &$user)
  {
    if(!Policy::check_default_policy("company", "edit")) $waf->halt("error:policy:permissions");

    // Put company in session to "pick it up"
    $id = $_SESSION['company_id'] = WA::request("id");

    require_once("model/Company.class.php");
    $company_name = Company::get_name($id);
    $_SESSION['lastitems']->add_here("c:$company_name", "c:$id", "Company: $company_name");

    goto("directories", "edit_company_real&id=$id");
  }

  function edit_company_real(&$waf, &$user) 
  {
    if(!Policy::check_default_policy("company", "create")) $waf->halt("error:policy:permissions");

    $id = WA::request("id");
    $waf->assign("changes", WA::request("changes"));
    $waf->assign("xinha_editor", true);

    edit_object($waf, $user, "Company", array("confirm", "directories", "edit_company_do"), array(array("cancel","section=directories&function=company_directory"), array("contacts", "section=directories&function=manage_contacts&company_id=$id"), array("vacancies", "section=directories&function=manage_vacancies&company_id=$id&page=1"), array("notes", "section=directories&function=list_notes&object_type=Company&object_id=$id")), array(array("user_id",$user["user_id"])), "admin:directories:companies:edit_company", "admin/directories/edit_company.tpl");
  }

  function edit_company_do(&$waf, &$user) 
  {
    $id = (int) WA::request("id");
    if(!Policy::check_default_policy("company", "create")) $waf->halt("error:policy:permissions");

    edit_object_do($waf, $user, "Company", "section=directories&function=edit_company_real&id=$id&changes=1", "edit_company_real");
  }

  function remove_company(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    remove_object($waf, $user, "Company", array("remove", "directories", "remove_company_do"), array(array("cancel","section=directories&function=company_directory")), "", "admin:directories:companies:remove_company");
  }

  function remove_company_do(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    remove_object_do($waf, $user, "Company", "section=directories&function=company_directory");
  }

  function view_company(&$waf, &$user)
  {
    $company_id = (int) WA::request("company_id");
    if($_SESSION['company_id'] != $company_id)
    {
      // If this company isn't the active one, make it so
      $company_id = (int) WA::request("company_id", true);
      goto("directories", "view_company&company_id=$company_id");
    }

    $action_links = array(array("edit", "section=directories&function=edit_company&id=$company_id"));

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
    $resource_actions = array(array("view", "view_company_resource", "directories"));

    $waf->assign("resources", $resources);
    $waf->assign("resource_headings", $resource_headings);
    $waf->assign("resource_actions", $resource_actions);
    $waf->assign("action_links", $action_links);
    $waf->assign("company", $company);
    $waf->assign("company_activity_names", $company_activity_names);

    $waf->display("main.tpl", "admin:directories:vacancy_directory:view_company", "admin/directories/view_company.tpl");
  }

  /**
  * manages vacancies for a specific company
  */
  function manage_vacancies(&$waf, $user, $title)
  {
    $page = (int) WA::request("page", true);

    $company_id = (int) WA::request("company_id", true);
    if($company_id && ($_SESSION['company_id'] != $company_id))
    {
      // If this company isn't the active one, make it so
      $company_id = (int) WA::request("company_id", true);
      goto("directories", "manage_vacancies&company_id=$company_id&page=$page");
    }

    require_once("model/Vacancy.class.php");
    $objects = Vacancy::get_all("where company_id=$company_id", "order by year(jobstart) DESC, status, description", $page);
    require_once("model/Application.class.php");
    $object_num = Vacancy::count("where company_id=$company_id");
    for($loop = 0; $loop < count($objects); $loop++)
    {
      $objects[$loop]->startyear = substr($objects[$loop]->jobstart, 0, 4);
      $objects[$loop]->applicants = Application::count("where vacancy_id=" . $objects[$loop]->id);
    }

    $headings = array(
      'description'=>array('type'=>'text', 'size'=>30, 'maxsize'=>100, 'title'=>'Job Description','header'=>true),
      'closedate'=>array('type'=>'text', 'header'=>true),
      'startyear'=>array('type'=>'text', 'header'=>true),
      'applicants'=>array('type'=>'text', 'header'=>true),
      'status'=>array('type'=>'list', 'list'=>array("open", "closed", "special"), 'header'=>true)
    );

    $actions = array(array('edit', 'edit_vacancy'), array('applicants', 'manage_applicants'), array('clone', 'clone_vacancy'), array('remove','remove_vacancy'));

    $waf->assign("headings", $headings);
    $waf->assign("objects", $objects);
    $waf->assign("object_num", $object_num);
    $waf->assign("actions", $actions);
    $waf->assign("action_links", array(array("add","section=directories&function=add_vacancy&company_id=$company_id"), array("edit company", "section=directories&function=edit_company&id=$company_id")));

    $waf->display("main.tpl", "admin:directories:vacancies:manage_vacancies", "list.tpl");
  }

  function add_vacancy(&$waf, &$user) 
  {
    if(!Policy::check_default_policy("vacancy", "create")) $waf->halt("error:policy:permissions");

    $company_id = (int) WA::request("company_id", true);
    require_once("model/Company.class.php");
    $company = new Company;
    $company = $company->load_by_id($company_id);

    $existing_nvp_array = $waf->get_template_vars("nvp_array");
    if(!strlen($existing_nvp_array['locality']))
    {
      foreach(array("address1", "address2", "address3", "postcode", "locality", "town", "country") as $field)
      {
        $nvp_array[$field] = $company->$field;
      }
      $waf->assign("nvp_array", $nvp_array);
    }
    $waf->assign("page_title_extra", " (" . $company->name . ")");

    add_object($waf, $user, "Vacancy", array("add", "directories", "add_vacancy_do"), array(array("cancel","section=directories&function=manage_vacancies")), array(array("company_id", $company_id), array("user_id",$user["user_id"])), "admin:directories:vacancies:add_vacancy");
  }

  /**
  * clone the vacancy, but make sure the correct company is selected first
  *
  */
  function clone_vacancy(&$waf)
  {
    require_once("model/Vacancy.class.php");
    $id = WA::request("id");
    $changes = WA::request("changes");

    $vacancy = Vacancy::load_by_id($id);

    // Do we currently have a company being looked at?
    $company_id = $_SESSION['company_id'];
    if(!$company_id)
    {
      // No
      $_SESSION['company_id'] = $company_id;
    }
    else
    {
      // Yes
      if($company_id != $vacancy->company_id)
      {
        // But it's a different one!
        $_SESSION['company_id'] = $vacancy->company_id;
      }
    }
    // Ok, all should now be safe...
    goto("directories", "clone_vacancy_real&id=$id&changes=$changes");
  }


  /**
  * clone the vacancy, called when the company is correctly established
  */
  function clone_vacancy_real(&$waf, &$user) 
  {
    if(!Policy::check_default_policy("vacancy", "create")) $waf->halt("error:policy:permissions");

    $company_id = (int) WA::request("company_id", true);
    $id = (int) WA::request("id");

    require_once("model/Vacancy.class.php");
    $vacancy = new Vacancy;
    $vacancy = $vacancy->load_by_id($id);

    $copy_fields = array_merge(Vacancy::get_fields(), Vacancy::get_extended_fields());
    foreach($copy_fields as $field)
    {
      $nvp_array[$field] = $vacancy->$field;
    }
    $waf->assign("nvp_array", $nvp_array);

    require_once("model/Company.class.php");
    $waf->assign("page_title_extra", " (" . Company::get_name($company_id) . ")");

    add_object($waf, $user, "Vacancy", array("add", "directories", "add_vacancy_do"), array(array("cancel","section=directories&function=manage_vacancies")), array(array("company_id", $company_id), array("user_id",$user["user_id"])), "admin:directories:vacancies:clone_vacancy");
  }

  function add_vacancy_do(&$waf, &$user) 
  {
    if(!Policy::check_default_policy("vacancy", "create")) $waf->halt("error:policy:permissions");

    $company_id = (int) WA::request("company_id", true);

    add_object_do($waf, $user, "Vacancy", "section=directories&function=manage_vacancies&company_id=$company_id", "add_vacancy");
  }

  /**
  * edit the vacancy, but make sure the correct company is selected first
  *
  */
  function edit_vacancy(&$waf)
  {
    require_once("model/Vacancy.class.php");
    $id = WA::request("id");
    $changes = WA::request("changes");

    $vacancy = Vacancy::load_by_id($id);

    // Do we currently have a company being looked at?
    $company_id = $_SESSION['company_id'];
    if(!$company_id)
    {
      // No
      $_SESSION['company_id'] = $company_id;
    }
    else
    {
      // Yes
      if($company_id != $vacancy->company_id)
      {
        // But it's a different one!
        $_SESSION['company_id'] = $vacancy->company_id;
      }
    }
    // Ok, all should now be safe...
    goto("directories", "edit_vacancy_real&id=$id&changes=$changes");
  }


  /**
  * the company should have been taken care of now, so really edit the vacancy
  */
  function edit_vacancy_real(&$waf, &$user) 
  {
    if(!Policy::check_default_policy("vacancy", "edit")) $waf->halt("error:policy:permissions");

    $id = (int) WA::request("id");

    // Make a "recent" menu item
    require_once("model/Vacancy.class.php");
    $vacancy_desc = Vacancy::get_name($id);
    $_SESSION['lastitems']->add_here("v:$vacancy_desc", "v:$id", "Vacancy: $vacancy_desc");

    $company_id = (int) WA::request("company_id", true);
    $waf->assign("xinha_editor", true);

    edit_object($waf, $user, "Vacancy", array("confirm", "directories", "edit_vacancy_do"), array(array("cancel","section=directories&function=manage_vacancies"), array("view","section=directories&function=view_vacancy&id=$id")), array(array("company_id", $company_id), array("user_id",$user["user_id"])), "admin:directories:vacancy_directory:edit_vacancy", "admin/directories/edit_vacancy.tpl");
  }


  function edit_vacancy_do(&$waf, &$user) 
  {
    if(!Policy::check_default_policy("vacancy", "edit")) $waf->halt("error:policy:permissions");

    $company_id = (int) WA::request("company_id", true);

    edit_object_do($waf, $user, "Vacancy", "section=directories&function=manage_vacancies&company_id=$company_id", "edit_vacancy_real");
  }

  function remove_vacancy(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    $company_id = (int) WA::request("company_id", true);

    remove_object($waf, $user, "Vacancy", array("remove", "directories", "remove_vacancy_do"), array(array("cancel","section=directories&function=manage_vacancies&company_id=$company_id")), "", "admin:directories:vacancies:remove_vacancy");
  }

  function remove_vacancy_do(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    $company_id = (int) WA::request("company_id", true);

    remove_object_do($waf, $user, "Vacancy", "section=directories&function=manage_vacancies&company_id=$company_id");
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
    $action_links = array(array("edit", "section=directories&function=edit_vacancy&id=$id"), array("edit company", "section=directories&function=edit_company&id=$company_id"));
    if($student_id)
    {
      array_push($action_links, array("apply with student", "section=directories&function=add_application&id=$id"));
    }

    require_once("model/Resource.class.php");
    $resources = Resource::get_all("where company_id=" . $vacancy->company_id);
    $resource_headings = Resource::get_field_defs("company");
    $resource_actions = array(array("view", "view_company_resource", "directories"));

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

  function manage_applications(&$waf, $user, $title)
  {
    $student_id = (int) WA::request("student_id", true);
    $page = (int) WA::request("page", true);

    if(!Policy::is_auth_for_student($student_id, "student", "viewCompanies")) $waf->halt("error:policy:permissions");

    manage_objects($waf, $user, "Application", array(array("edit student", "section=directories&function=edit_student&student_id=$student_id")), array(array('edit', 'edit_application'), array('remove','remove_application'), array('place','add_placement')), "get_all", array("where student_id=$student_id", "", $page), "admin:directories:student_directory:manage_applications");
  }

  /**
  * tag a student as having applied for a vacancy
  */
  function add_application(&$waf, &$user)
  {
    $vacancy_id = (int) WA::request("id");
    $student_id = $_SESSION['student_id'];

    if(!Policy::is_auth_for_student($student_id, "student", "editCompanies")) $waf->halt("error:policy:permissions");

    // Get the available CVs, and *do* filter them
    require_once("model/CVCombined.class.php");
    $cv_list = CVCombined::fetch_cvs_for_student($student_id, true);
    foreach($cv_list as $cv)
    {
      if($cv->valid) $valid++;
      else $invalid++;
    }
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
    $waf->assign("application", $application);
    $waf->assign("eportfolio_list", $eportfolio_list);
    $waf->assign("cv_list", $cv_list);
    $waf->assign("cv_options", $cv_options);
    $waf->assign("valid", $valid);
    $waf->assign("invalid", $invalid);
    $waf->display("main.tpl", "admin:directories:vacancy_directory:add_application", "admin/directories/edit_application.tpl");
  }

  function add_application_do(&$waf, &$user) 
  {
    $student_id = (int) WA::request("student_id", true);

    if(!Policy::is_auth_for_student($student_id, "student", "editCompanies")) $waf->halt("error:policy:permissions");

    add_object_do($waf, $user, "Application", "section=directories&function=manage_applications&student_id=$student_id", "add_application");
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

    if(!Policy::is_auth_for_student($student_id, "student", "editCompanies")) $waf->halt("error:policy:permissions");

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
    $waf->assign("application", $application);
    $waf->assign("eportfolio_list", $eportfolio_list);
    $waf->assign("cv_list", $cv_list);
    $waf->assign("cv_options", $cv_options);
    $waf->assign("invalid", $invalid);
    $waf->assign("selected_cv_ident", $application->cv_ident);
    $waf->display("main.tpl", "admin:directories:vacancy_directory:add_application", "admin/directories/edit_application.tpl");
  }

  function edit_application_do(&$waf, &$user) 
  {
    $application_id = (int) WA::request("id");
    require_once("model/Application.class.php");
    $application = Application::load_by_id($application_id);

    $student_id = $application->student_id;

    if(!Policy::is_auth_for_student($student_id, "student", "editCompanies")) $waf->halt("error:policy:permissions");

    edit_object_do($waf, $user, "Application", "section=directories&function=manage_applications&student_id=$student_id", "add_application");
  }


  function remove_application(&$waf, &$user) 
  {
    $student_id = (int) WA::request("student_id", true);

    if(!Policy::is_auth_for_student($student_id, "student", "editCompanies")) $waf->halt("error:policy:permissions");

    remove_object($waf, $user, "Application", array("remove", "directories", "remove_application_do"), array(array("cancel","section=directories&function=manage_applications&student_id=$student_id")), "", "admin:directories:student_directory:remove_application");
  }

  function remove_application_do(&$waf, &$user) 
  {
    $student_id = (int) WA::request("student_id", true);

    if(!Policy::is_auth_for_student($student_id, "student", "editCompanies")) $waf->halt("error:policy:permissions");

    remove_object_do($waf, $user, "Application", "section=directories&function=manage_applications&student_id=$student_id");
  }

  function manage_applicants(&$waf, $user, $title)
  {
    $vacancy_id = (int) WA::request("id");
    require_once("model/Vacancy.class.php");

    $vacancy = Vacancy::load_by_id($vacancy_id);
    $waf->log("applicants for vacancy " . $vacancy->description . "(" . $vacancy->_company_id . ") viewed");

    require_once("model/Application.class.php");
    $possible_status = array('unseen','seen','invited to interview','missed interview','offered','unsuccessful');
    $all_applications = Application::get_all_triaged($vacancy_id);
    $waf->assign("placed", $all_applications[0]);
    $waf->assign("available", $all_applications[1]);
    $waf->assign("unavailable", $all_applications[2]);
    $waf->assign("status_values", $possible_status);
    $waf->assign("vacancy_id", $vacancy_id);
    $waf->display("main.tpl", "admin:directories:vacancy_directory:manage_applicants", "admin/directories/manage_applicants.tpl");
  }

  function manage_applicants_do(&$waf)
  {
    $status = WA::request("status");                // New status array
    $old_status = WA::request("old_status");        // Original status array
    $send = WA::request("send");                    // Array of "send" checkboxes
    $vacancy_id = (int) WA::request("vacancy_id");
    require_once("model/Vacancy.class.php");
    $vacancy = Vacancy::load_by_id($vacancy_id);
    $waf->assign("vacancy", $vacancy);

    $action_links = array(array("back", "section=directories&function=manage_applicants&id=$vacancy_id"));

    // Array of student ids for which status is changed
    $status_changes = array();
    foreach($old_status as $key => $value)
    {
      if($status[$key] != $value)
      {
        require_once("model/Application.class.php");
        $application = Application::load_where("where vacancy_id = $vacancy_id and student_id=" . (int) $key);
        $fields = array();
        $fields['id'] = (int) $application->id;
        $fields['status'] = $status[$key];
        Application::update($fields);
        array_push($status_changes, $key);
        $student_names[$key] = User::get_name($key);
      }
    }

    // Check if CVs were requested
    if(!empty($send))
    {
      require_once("model/CVCombined.class.php");
      // Send CVs via email
      foreach($send as $student_id)
      {
        // Send me the combined CV
        require_once("model/Application.class.php");
        $application = Application::load_where("where vacancy_id = $vacancy_id and student_id=" . (int) $student_id);
        CVCombined::email_cv(User::get_id(), $application->id);
      }
    }

    // Check if changes were made to status, if so offer up a dialog
    if(count($status_changes))
    {
      $waf->log("status changes were made to applicants on vacancy " . Vacancy::get_name($vacancy_id));
      $waf->assign("action_links", $action_links);
      $waf->assign("old_status", $old_status);
      $waf->assign("student_name", $student_names);
      $waf->assign("status", $status);
      $waf->assign("status_changes", $status_changes);
      $waf->assign("vacancy_id", $vacancy_id);
      $waf->display("main.tpl", "admin:directories:vacancy_directory:message_applicants", "admin/directories/message_applicants.tpl");
    }
    else
    {
      // No changes, back to same screen
      goto("directories", "manage_applicants&id=$vacancy_id");
    }
  }

  function view_cv_by_application(&$waf)
  {
    $application_id = WA::request("application_id");

    // security is handled in the model layer
    require_once("model/CVCombined.class.php");
    CVCombined::view_cv_for_application($application_id);
  }

  function view_cover_by_application(&$waf)
  {
    require_once("model/CVCombined.class.php");
    require_once("model/Application.class.php");

    $application_id = WA::request("application_id");
    $application = Application::load_by_id($application_id);

    $action_links = array(array("fetch cv", "section=directories&function=view_cv_by_application&application_id=$application_id"), array("back to applications", "section=directories&function=manage_applicants&id=" .$application->vacancy_id));


    // Security is same as for CV
    if(!CVCombined::is_auth_to_view_cv($application->cv_ident, $application->student_id))
    {
      $waf->halt("error:cv:not_authorised");
    }
    $waf->assign("action_links", $action_links);
    $waf->assign("letter", $application->cover);

    $waf->display("main.tpl", "admin:directories:vacancy_directory:manage_applicants", "admin/directories/view_cover_letter.tpl");
  }

  // Placements

  /**
  * record a placement with a vacancy
  * 
  * the original data for the form is read from the vacancy record where possible
  */
  function add_placement(&$waf, &$user)
  {
    $application_id = (int) WA::request("id");
    if(!$application_id) $application_id = (int) WA::request("application_id");

    require_once("model/Application.class.php");
    $application = Application::load_by_id($application_id);
    require_once("model/Vacancy.class.php");
    $vacancy = Vacancy::load_by_id($application->vacancy_id);

    if(!Policy::is_auth_for_student($application->student_id, "student", "editStatus")) $waf->halt("error:policy:permissions");

    // Get any existing data
    $nvp_array = $waf->get_template_vars("nvp_array");
    if(empty($nvp_array['jobstart'])) $nvp_array['jobstart'] = $vacancy->jobstart;
    if(empty($nvp_array['jobend'])) $nvp_array['jobend'] = $vacancy->jobend;
    if(empty($nvp_array['position'])) $nvp_array['position'] = $vacancy->description;
    if(empty($nvp_array['salary'])) $nvp_array['salary'] = $vacancy->salary;
    $waf->assign("nvp_array", $nvp_array);

    add_object($waf, $user, "Placement", array("add", "directories", "add_placement_do"), array(array("cancel","section=directories&function=edit_student&student_id=" . $application->student_id)), array(array("company_id", $application->company_id), array("vacancy_id", $application->vacancy_id), array("student_id", $application->student_id), array("application_id", $application_id)), "admin:directories:student_directory:add_placement");
  }

  function add_placement_do(&$waf, &$user) 
  {
    $student_id = (int) WA::request("student_id", true);
    $application_id = (int) WA::request("application_id", true);

    if(!Policy::is_auth_for_student($student_id, "student", "editStatus")) $waf->halt("error:policy:permissions");

    add_object_do($waf, $user, "Placement", "section=directories&function=edit_student&student_id=$student_id", "add_placement");
  }

  function edit_placement(&$waf, &$user) 
  {
    $student_id = (int) WA::request("student_id", true);

    if(!Policy::is_auth_for_student($student_id, "student", "viewStatus")) $waf->halt("error:policy:permissions");

    edit_object($waf, $user, "Placement", array("confirm", "directories", "edit_placement_do"), array(array("cancel","section=directories&function=edit_student&student_id=$student_id")), array(array("student_id", $student_id), array("user_id",$user["user_id"])), "admin:directories:placement_directory:edit_placement");
  }

  function edit_placement_do(&$waf, &$user) 
  {
    $student_id = (int) WA::request("student_id", true);

    if(!Policy::is_auth_for_student($student_id, "student", "editStatus")) $waf->halt("error:policy:permissions");

    edit_object_do($waf, $user, "Placement", "section=directories&function=edit_student&student_id=$student_id", "edit_placement");
  }


  function remove_placement(&$waf, &$user) 
  {
    $student_id = (int) WA::request("student_id", true);

    if(!Policy::is_auth_for_student($student_id, "student", "editStatus")) $waf->halt("error:policy:permissions");

    remove_object($waf, $user, "Placement", array("remove", "directories", "remove_placement_do"), array(array("cancel","section=directories&function=edit_student&student_id=$student_id")), "", "admin:directories:student_directory:remove_placement");
  }

  function remove_placement_do(&$waf, &$user) 
  {
    $student_id = (int) WA::request("student_id", true);

    if(!Policy::is_auth_for_student($student_id, "student", "editStatus")) $waf->halt("error:policy:permissions");

    remove_object_do($waf, $user, "Placement", "section=directories&function=edit_student&student_id=$student_id");
  }

  function view_placement_vacancy(&$waf)
  {
    $placement_id = (int) WA::request("id");

    require_once("model/Placement.class.php");
    $placement = Placement::load_by_id($placement_id);
    goto('directories', 'view_vacancy&id=' . $placement->vacancy_id);
  }



  // Contacts

  function contact_directory(&$waf)
  {
    if(!Policy::check_default_policy("contact", "list")) $waf->halt("error:policy:permissions");

    require_once("model/Preference.class.php");
    $form_options = Preference::get_preference("contact_directory_form");

    $waf->assign("form_options", $form_options);

    $letters = array();
    for($loop = ord('A'); $loop <= ord('Z'); $loop++) array_push($letters, chr($loop));
    $waf->assign("letters", $letters);

    $waf->display("main.tpl", "admin:directories:contact_directory:contact_directory", "admin/directories/contact_directory.tpl");
  }

  function search_contacts(&$waf)
  {
    if(!Policy::check_default_policy("contact", "list")) $waf->halt("error:policy:permissions");

    require_once("model/Contact.class.php");
    $search = WA::request("search");
    $sort = WA::request("sort");

    if(!preg_match('/^[A-Za-z0-9 ]*$/', $search)) $waf->halt("error:contacts:invalid_search");

    $form_options['search'] = $search;
    $form_options['sort'] = $sort;

    require_once("model/Preference.class.php");
    Preference::set_preference("contact_directory_form", $form_options);

    if(empty($search))
    {
      $where_clause = "";
    }
    else
    {
      $where_clause = "where lastname like '%$search%' OR firstname like '%$search%'";
    }

    $objects = Contact::get_all($where_clause);

    $headings = array(
      'real_name'=>array('type'=>'text','size'=>30, 'header'=>true, title=>'Name'),
      'position'=>array('type'=>'list','size'=>30, 'header'=>true, title=>'Position'),
      'email'=>array('type'=>'email','size'=>40, 'header'=>true),
      'voice'=>array('type'=>'text','size'=>40, 'header'=>true, title=>'Phone')
    );
    $actions = array(array('edit', 'edit_contact'));

    $waf->assign("actions", $actions);
    $waf->assign("headings", $headings);
    $waf->assign("objects", $objects);

    $waf->display("main.tpl", "admin:directories:contact_directory:search_contacts", "list.tpl");
  }

  function simple_search_contact(&$waf)
  {
    if(!Policy::check_default_policy("contact", "list")) $waf->halt("error:policy:permissions");

    require_once("model/Contact.class.php");
    $initial = WA::request("initial");

    if(!preg_match('/^[A-Za-z0-9]$/', $initial)) $waf->halt("error:contacts:invalid_search");

    $where_clause = "where lastname like '$initial%'";

    $objects = Contact::get_all($where_clause);

    $headings = array(
      'real_name'=>array('type'=>'text','size'=>30, 'header'=>true, title=>'Name'),
      'position'=>array('type'=>'list','size'=>30, 'header'=>true, title=>'Position'),
      'email'=>array('type'=>'email','size'=>40, 'header'=>true),
      'voice'=>array('type'=>'text','size'=>40, 'header'=>true, title=>'Phone')
    );
    $actions = array(array('edit', 'edit_contact'));

    $waf->assign("actions", $actions);
    $waf->assign("headings", $headings);
    $waf->assign("objects", $objects);

    $waf->display("main.tpl", "admin:directories:contact_directory:simple_search_contacts", "list.tpl");

  }

  function manage_contacts(&$waf, $user, $title)
  {
    if(!Policy::check_default_policy("contact", "list")) $waf->halt("error:policy:permissions");

    $company_id = (int) WA::request("company_id");
    if($company_id && ($_SESSION['company_id'] != $company_id))
    {
      // If this company isn't the active one, make it so
      $company_id = (int) WA::request("company_id", true);
      goto("directories", "manage_contacts&company_id=$company_id");
    }
    elseif(!$company_id && $_SESSION['company_id'])
    {
      // Nothing inbound, but something in the session
      $company_id = $_SESSION['company_id'];
    }
    require_once("model/Contact.class.php");

    if($company_id)
    {
      $objects = Contact::get_all_by_company($company_id);

      $headings = array(
        'real_name'=>array('type'=>'text','size'=>30, 'header'=>true, title=>'Name'),
        'position'=>array('type'=>'list','size'=>30, 'header'=>true, title=>'Position'),
        'email'=>array('type'=>'email','size'=>40, 'header'=>true),
        'voice'=>array('type'=>'text','size'=>40, 'header'=>true, title=>'Phone'),
        'status'=>array('type'=>'text','size'=>40, 'header'=>true, title=>'Status')
      );
      $actions = array(array('edit', 'edit_contact'), array('status', 'edit_contact_status'));

      $waf->assign("headings", $headings);
      $waf->assign("objects", $objects);
      $waf->assign("actions", $actions);
      $waf->assign("action_links", array(array("add", "section=directories&function=add_contact")));
    }
    $waf->display("main.tpl", "admin:directories:contact_directory:company_contacts", "list.tpl");
  }

  function add_contact(&$waf, &$user) 
  {
    if(!Policy::check_default_policy("contact", "create")) $waf->halt("error:policy:permissions");

    $company_id = (int) WA::request("company_id", true);

    add_object($waf, $user, "Contact", array("add", "directories", "add_contact_do"), array(array("cancel","section=directories&function=manage_contacts&company_id=$company_id")), array(array("user_id",$user["user_id"]), array("company_id", $company_id)), "admin:directories:contact_directory:add_contact");
  }

  function add_contact_do(&$waf, &$user) 
  {
    if(!Policy::check_default_policy("contact", "create")) $waf->halt("error:policy:permissions");

    $company_id = (int) WA::request("company_id", true);

    add_object_do($waf, $user, "Contact", "section=directories&function=manage_contacts&company_id=$company_id", "add_contact");
  }

  function edit_contact_status(&$waf)
  {
    if(!Policy::check_default_policy("contact", "edit")) $waf->halt("error:policy:permissions");

    require_once("model/Contact.class.php");
    require_once("model/CompanyContact.class.php");
    $id = WA::request("id");
    $companycontact = CompanyContact::load_by_contact_id(Contact::get_user_id($id));
    // Naughty tweak...
    $_REQUEST['id'] = $companycontact->id;

    edit_object($waf, $user, "CompanyContact", array("confirm", "directories", "edit_contact_status_do"), array(array("cancel","section=directories&function=manage_contacts")), array(array("user_id", $contact->user_id)), "admin:directories:contact_directory:edit_contact_status");
  }

  function edit_contact_status_do(&$waf, &$user) 
  {
    if(!Policy::check_default_policy("contact", "edit")) $waf->halt("error:policy:permissions");

    edit_object_do($waf, $user, "CompanyContact", "section=directories&function=manage_contacts", "edit_contact_status");
  }

  function edit_contact(&$waf, &$user) 
  {
    if(!Policy::check_default_policy("contact", "edit")) $waf->halt("error:policy:permissions");

    require_once("model/Contact.class.php");
    $id = WA::request("id");
    $contact = Contact::load_by_id($id);
    $changes = WA::request("changes");
    $waf->assign("changes", $changes);

    edit_object($waf, $user, "Contact", array("confirm", "directories", "edit_contact_do"), array(array("cancel","section=directories&function=manage_contacts"), array("reset password", "section=directories&function=reset_password&user_id=" . $contact->user_id)), array(array("user_id", $contact->user_id)), "admin:directories:contact_directory:edit_contact", "admin/directories/edit_contact.tpl");
  }

  function edit_contact_do(&$waf, &$user) 
  {
    if(!Policy::check_default_policy("contact", "edit")) $waf->halt("error:policy:permissions");

    edit_object_do($waf, $user, "Contact", "section=directories&function=manage_contacts", "edit_contact");
  }

  function remove_contact(&$waf, &$user) 
  {
    if(!Policy::check_default_policy("contact", "delete")) $waf->halt("error:policy:permissions");

    remove_object($waf, $user, "Contact", array("remove", "directories", "remove_contact_do"), array(array("cancel","section=directories&function=manage_contacts")), "", "admin:directories:contacts:remove_contact");
  }

  function remove_contact_do(&$waf, &$user) 
  {
    if(!Policy::check_default_policy("contact", "delete")) $waf->halt("error:policy:permissions");

    remove_object_do($waf, $user, "Contact", "section=directories&function=manage_contacts");
  }

  // Supervisors

  function supervisor_directory(&$waf)
  {
    if(!Policy::check_default_policy("contact", "list")) $waf->halt("error:policy:permissions");

    require_once("model/Preference.class.php");
    $form_options = Preference::get_preference("supervisor_directory_form");

    $waf->assign("form_options", $form_options);

    $letters = array();
    for($loop = ord('A'); $loop <= ord('Z'); $loop++) array_push($letters, chr($loop));
    $waf->assign("letters", $letters);

    $waf->display("main.tpl", "admin:directories:supervisor_directory:supervisor_directory", "admin/directories/supervisor_directory.tpl");
  }

  function search_supervisors(&$waf)
  {
    if(!Policy::check_default_policy("contact", "list")) $waf->halt("error:policy:permissions");

    require_once("model/Supervisor.class.php");
    $search = WA::request("search");

    if(!preg_match('/^[A-Za-z0-9 ]*$/', $search)) $waf->halt("error:supervisors:invalid_search");

    $form_options['search'] = $search;

    require_once("model/Preference.class.php");
    Preference::set_preference("supervisor_directory_form", $form_options);

    if(empty($search))
    {
      $where_clause = "";
    }
    else
    {
      $where_clause = "where (lastname like '%$search%' OR firstname like '%$search%')";
    }

    $objects = Supervisor::get_all($where_clause);
    $waf->assign("objects", $objects);

    $waf->display("main.tpl", "admin:directories:supervisor_directory:search_supervisors", "admin/directories/search_supervisors.tpl");
  }

  function simple_search_supervisors(&$waf)
  {
    if(!Policy::check_default_policy("contact", "list")) $waf->halt("error:policy:permissions");

    require_once("model/Supervisor.class.php");
    $initial = WA::request("initial");

    if(!preg_match('/^[A-Za-z0-9]$/', $initial)) $waf->halt("error:supervisors:invalid_search");

    $where_clause = "where (lastname like '$initial%')";

    $objects = Supervisor::get_all($where_clause);
    $waf->assign("objects", $objects);

    $waf->display("main.tpl", "admin:directories:supervisor_directory:search_supervisors", "admin/directories/search_supervisors.tpl");

  }

  function supervisor_resetpassword(&$waf)
  {
  }

  function supervisor_student(&$waf)
  {
  }

  // Staff

  function staff_directory(&$waf)
  {
    if(!Policy::check_default_policy("staff", "list")) $waf->halt("error:policy:permissions");

    require_once("model/Preference.class.php");
    $form_options = Preference::get_preference("staff_directory_form");

    require_once("model/School.class.php");
    $schools = School::get_id_and_field("name");

    $waf->assign("form_options", $form_options);

    $letters = array();
    for($loop = ord('A'); $loop <= ord('Z'); $loop++) array_push($letters, chr($loop));
    $waf->assign("letters", $letters);
    $waf->assign("schools", $schools);

    $waf->display("main.tpl", "admin:directories:staff_directory:staff_directory", "admin/directories/staff_directory.tpl");
  }

  function search_staff(&$waf)
  {
    if(!Policy::check_default_policy("staff", "list")) $waf->halt("error:policy:permissions");

    require_once("model/Staff.class.php");
    $search = WA::request("search", true);
    $sort = WA::request("sort", true);
    $schools = WA::request("schools", true);

    if(!preg_match('/^[A-Za-z0-9 ]*$/', $search)) $waf->halt("error:staff:invalid_search");

    $form_options['search'] = $search;
    $form_options['sort'] = $sort;
    $form_options['schools'] = $schools;

    require_once("model/Preference.class.php");
    Preference::set_preference("staff_directory_form", $form_options);

    if(empty($search))
    {
      $where_clause = "";
    }
    else
    {
      $where_clause = "where lastname like '%$search%' OR firstname like '%$search%'";
    }

    $provisional_objects = Staff::get_all($where_clause);
    // Check schools
    $objects = array();
    foreach($provisional_objects as $object)
    {
      if(in_array($object->school_id, $schools)) array_push($objects, $object);
    }
    //$objects = $provisional_objects;

    $headings = array(
      'real_name'=>array('type'=>'text','size'=>30, 'header'=>true, title=>'Name'),
      'position'=>array('type'=>'list','size'=>30, 'header'=>true, title=>'Position'),
      'email'=>array('type'=>'email','size'=>40, 'header'=>true),
      'voice'=>array('type'=>'text','size'=>40, 'header'=>true, title=>'Phone')
    );
    $actions = array(array('edit', 'edit_staff'));
    
    // Now allow staff to be added
    $action_links = array(array('add', 'section=directories&function=add_staff'));
    
    $waf->assign("action_links", $action_links);
    $waf->assign("actions", $actions);
    $waf->assign("headings", $headings);
    $waf->assign("objects", $objects);

    $waf->display("main.tpl", "admin:directories:staff_directory:search_staff", "list.tpl");
  }

  function simple_search_staff(&$waf)
  {
    if(!Policy::check_default_policy("staff", "list")) $waf->halt("error:policy:permissions");

    require_once("model/Staff.class.php");
    $initial = WA::request("initial");

    if(!preg_match('/^[A-Za-z0-9]$/', $initial)) $waf->halt("error:staffs:invalid_search");

    $where_clause = "where lastname like '$initial%'";

    $objects = Staff::get_all($where_clause);

    $headings = array(
      'real_name'=>array('type'=>'text','size'=>30, 'header'=>true, title=>'Name'),
      'position'=>array('type'=>'list','size'=>30, 'header'=>true, title=>'Position'),
      'email'=>array('type'=>'email','size'=>40, 'header'=>true),
      'voice'=>array('type'=>'text','size'=>40, 'header'=>true, title=>'Phone')
    );
    $actions = array(array('edit', 'edit_staff'));

    // Now allow staff to be added
    $action_links = array(array('add', 'section=directories&function=add_staff'));
    
    $waf->assign("action_links", $action_links);
    $waf->assign("actions", $actions);
    $waf->assign("headings", $headings);
    $waf->assign("objects", $objects);

    $waf->display("main.tpl", "admin:directories:staff_directory:simple_search_staff", "list.tpl");

  }

  function add_staff(&$waf, &$user) 
  {
    if(!Policy::check_default_policy("staff", "create")) $waf->halt("error:policy:permissions");

    add_object($waf, $user, "Staff", array("add", "directories", "add_staff_do"), array(array("cancel","section=directories&function=staff_directory")), array(array("user_id",$user["user_id"])), "admin:directories:staff_directory:add_staff");
  }

  function add_staff_do(&$waf, &$user) 
  {
    if(!Policy::check_default_policy("staff", "create")) $waf->halt("error:policy:permissions");

    add_object_do($waf, $user, "Staff", "section=directories&function=staff_directory", "add_staff");
  }

  function edit_staff(&$waf, &$user) 
  {
    if(!Policy::check_default_policy("staff", "edit")) $waf->halt("error:policy:permissions");

    require_once("model/Staff.class.php");
    $id = WA::request("id");
    $staff = Staff::load_by_id($id);
    $changes = WA::request("changes");

    $waf->assign("changes", $changes);

    edit_object($waf, $user, "Staff", array("confirm", "directories", "edit_staff_do"), array(array("cancel","section=directories&function=staff_directory"), array("reset password", "section=directories&function=reset_password&user_id=" . $staff->user_id), array("list students / view home", "section=directories&function=list_students_for_staff&id=" . $id)), array(array("user_id", $staff->user_id)), "admin:directories:staff_directory:edit_staff", "admin/directories/edit_staff.tpl");
  }

  function edit_staff_do(&$waf, &$user) 
  {
    $id = WA::request("id");
    if(!Policy::check_default_policy("staff", "edit")) $waf->halt("error:policy:permissions");

    edit_object_do($waf, $user, "Staff", "section=directories&function=edit_staff&id=$id&changes=true", "edit_staff");
  }

  function remove_staff(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    remove_object($waf, $user, "Staff", array("remove", "directories", "remove_staff_do"), array(array("cancel","section=directories&function=staff_directory")), "", "admin:directories:staff_directory:remove_staff");
  }

  function remove_staff_do(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    remove_object_do($waf, $user, "Staff", "section=directories&function=staff_directory");
  }

  function list_students_for_staff(&$waf, &$user)
  {
    if(!Policy::check_default_policy("staff", "edit")) $waf->halt("error:policy:permissions");

    require_once("model/Staff.class.php");
    $id = WA::request("id", true);
    $staff = Staff::load_by_id($id);

    $page = (int) WA::request("page", true);

    $alt_headings = array
    (
      'real_name'=>array('type'=>'text', 'size'=>30, 'header'=>true, 'title'=>'Name'),
      'placement_year'=>array('type'=>'text','size'=>5, 'title'=>'Placement Year', 'header'=>true),
    );
    $waf->assign("alt_headings", $alt_headings);
    $waf->assign("staff", $staff);

    manage_objects($waf, $user, "Student", array(array("back", "section=directories&function=edit_staff&id=" . $id)), array(array('edit', 'edit_student')), "get_all", array("where academic_user_id = " . $staff->user_id, "order by placement_year desc, lastname", $page), "staff:home:home:home", "staff/home/home.tpl");
  }

  // Admin

  function admin_directory(&$waf)
  {
    if(!Policy::check_default_policy("admin", "list")) $waf->halt("error:policy:permissions");

    require_once("model/Preference.class.php");
    $form_options = Preference::get_preference("admin_directory_form");

    $waf->assign("form_options", $form_options);

    $letters = array();
    for($loop = ord('A'); $loop <= ord('Z'); $loop++) array_push($letters, chr($loop));
    $waf->assign("letters", $letters);
    $waf->assign("schools", $schools);

    $waf->display("main.tpl", "admin:directories:admin_directory:admin_directory", "admin/directories/admin_directory.tpl");
  }

  function search_admins(&$waf)
  {
    require_once("model/Admin.class.php");
    $search = WA::request("search", true);

    if(!preg_match('/^[A-Za-z0-9 ]*$/', $search)) $waf->halt("error:admin:invalid_search");

    $form_options['search'] = $search;
    require_once("model/Preference.class.php");
    Preference::set_preference("admin_directory_form", $form_options);

    if(empty($search))
    {
      $where_clause = "";
    }
    else
    {
      $where_clause = "where lastname like '%$search%' OR firstname like '%$search%'";
    }

    $objects = Admin::get_all($where_clause);
    $headings = Admin::get_admin_list_headings();
    if(User::is_root())
    {
      $actions = array(array('edit', 'edit_admin'), array('remove', 'remove_admin'), array('promote', 'promote_admin'));
    }
    else
    {
      $actions = array(array('edit', 'edit_admin'));
    }
    $action_links = array(array('add', 'section=directories&function=add_admin'));

    $waf->assign("actions", $actions);
    $waf->assign("headings", $headings);
    $waf->assign("objects", $objects);
    $waf->assign("action_links", $action_links);


    $waf->display("main.tpl", "admin:directories:admin_directory:search_admin", "list.tpl");
  }

  function simple_search_admins(&$waf)
  {
    if(!Policy::check_default_policy("admin", "list")) $waf->halt("error:policy:permissions");

    require_once("model/Admin.class.php");
    $initial = WA::request("initial");

    if(!preg_match('/^[A-Za-z0-9]$/', $initial)) $waf->halt("error:admins:invalid_search");

    $where_clause = "where lastname like '$initial%'";

    $objects = Admin::get_all($where_clause);
    $headings = Admin::get_admin_list_headings();
    if(User::is_root())
    {
      $actions = array(array('edit', 'edit_admin'), array('remove', 'remove_admin'), array('promote', 'promote_admin'));
    }
    else
    {
      $actions = array(array('edit', 'edit_admin'));
    }
    $action_links = array(array('add', 'section=directories&function=add_admin'));

    $waf->assign("actions", $actions);
    $waf->assign("headings", $headings);
    $waf->assign("objects", $objects);
    $waf->assign("action_links", $action_links);

    $waf->display("main.tpl", "admin:directories:admin_directory:simple_search_admin", "list.tpl");
  }

  function manage_admins(&$waf, $user, $title)
  {
    require_once("model/Admin.class.php");

    $page = WA::request("page", true);

    $admin_objects = Admin::get_all("where user_type = 'admin'", "order by lastname", $page);
    $object_num = Admin::count("where user_type = 'admin'");

    $root_objects  = Admin::get_all("where user_type = 'root'", "order by lastname");

    $admin_headings = Admin::get_admin_list_headings();
    $root_headings = Admin::get_root_list_headings();

    $actions = array(array('edit', 'edit_admin'), array('remove', 'remove_admin'));

    $waf->assign("root_headings", $root_headings);
    $waf->assign("admin_headings", $admin_headings);
    $waf->assign("admin_objects", $admin_objects);
    $waf->assign("root_objects", $root_objects);
    $waf->assign("actions", $actions);
    $waf->assign("action_links", array(array("add", "section=directories&function=add_admin")));
    $waf->assign("object_num", $object_num);

    $waf->display("main.tpl", "admin:directories:admin_directory:manage_admins", "admin/directories/list_admins.tpl");
  }

  function add_admin(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    add_object($waf, $user, "Admin", array("add", "directories", "add_admin_do"), array(array("cancel","section=directories&function=admin_directory")), array(array("user_id",$user["user_id"])), "admin:directories:admin_directory:add_admin");
  }

  function add_admin_do(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    add_object_do($waf, $user, "Admin", "section=directories&function=admin_directory", "add_admin");
  }

  function edit_admin(&$waf, &$user) 
  {
    require_once("model/Admin.class.php");
    $id = WA::request("id");
    if(!User::is_root() && (Admin::get_user_id($id) != User::get_id()))  $waf->halt("error:policy:permissions");

    $admin = Admin::load_by_id($id);
    $changes = WA::request("changes");
    $waf->assign("changes", $changes);

    if(User::is_root(Admin::get_user_id($id)))
    {
      $return_function = "section=directories&function=manage_super_admins";
    }
    else
    {
      $return_function = "section=directories&function=admin_directory";
    }

    edit_object($waf, $user, "Admin", array("confirm", "directories", "edit_admin_do"), array(array("cancel",$return_function), array("reset password", "section=directories&function=reset_password&user_id=" . $admin->user_id)), array(array("user_id", $admin->user_id)), "admin:directories:admin_directory:edit_admin", "admin/directories/edit_admin.tpl");
  }

  function edit_admin_do(&$waf, &$user) 
  {
    require_once("model/Admin.class.php");
    $id = WA::request("id");
    if(!User::is_root() && (Admin::get_user_id($id) != User::get_id()))  $waf->halt("error:policy:permissions");

    if(User::is_root(Admin::get_user_id($id)))
    {
      $return_function = "section=directories&function=manage_super_admins";
    }
    else
    {
      $return_function = "section=directories&function=admin_directory";
    }

    edit_object_do($waf, $user, "Admin", $return_function, "edit_admin");
  }

  function remove_admin(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    remove_object($waf, $user, "Admin", array("remove", "directories", "remove_admin_do"), array(array("cancel","section=directories&function=admin_directory")), "", "admin:directories:admin_directory:remove_admin");
  }

  function remove_admin_do(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    remove_object_do($waf, $user, "Admin", "section=directories&function=admin_directory");
  }

  function promote_admin(&$waf, &$user)
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    $id = (int) WA::request("id");
    require_once("model/Admin.class.php");
    $admin = Admin::load_by_id($id);

    $waf->assign("admin", $admin);
    $waf->assign("action_links", array(array("cancel", "section=directories&function=manage_super_admins")));
    $waf->display("main.tpl", "admin:directories:admin_directory:promote_admin", "admin/directories/promote_admin.tpl");
  }

  function promote_admin_do(&$waf, &$user)
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    $id = (int) WA::request("id");
    require_once("model/Admin.class.php");
    $admin = Admin::load_by_id($id);

    $waf->log("Admin user " . $admin->real_name . " upgraded to root user");
    $waf->security_log("Admin user " . $admin->real_name . " upgraded to root user");

    $fields = array();
    $fields['id'] = $admin->user_id;
    $fields['user_type'] = 'root';

    User::update($fields);
    goto("directories", "manage_super_admins");
  }

  function demote_admin(&$waf, &$user)
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    $id = (int) WA::request("id");
    require_once("model/Admin.class.php");
    $admin = Admin::load_by_id($id);

    $waf->assign("admin", $admin);
    $waf->assign("action_links", array(array("cancel", "section=directories&function=manage_super_admins")));
    $waf->display("main.tpl", "admin:directories:admin_directory:demote_admin", "admin/directories/demote_admin.tpl");
  }


  function demote_admin_do(&$waf, &$user)
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    $id = (int) WA::request("id");
    require_once("model/Admin.class.php");
    $admin = Admin::load_by_id($id);

    $waf->log("Root user " . $admin->real_name . " demoted to normal admin user");
    $waf->security_log("Root user " . $admin->real_name . " demoted to normal admin user");

    $fields = array();
    $fields['id'] = $admin->user_id;
    $fields['user_type'] = 'admin';

    User::update($fields);
    goto("directories", "manage_super_admins");
  }


  function manage_super_admins(&$waf, $user, $title)
  {
    require_once("model/Admin.class.php");

    $page = WA::request("page", true);

    $root_objects  = Admin::get_all("where user_type = 'root'", "order by lastname");
    $root_headings = Admin::get_root_list_headings();

    if(User::is_root())
    {
      $actions = array(array('edit', 'edit_admin'), array('demote', 'demote_admin'));
    }
    else
    {
      $actions = array(array('edit', 'edit_admin'));
    }

    $waf->assign("root_headings", $root_headings);
    $waf->assign("root_objects", $root_objects);
    $waf->assign("actions", $actions);
    $waf->assign("object_num", $object_num);

    $waf->display("main.tpl", "admin:directories:manage_super_admins:manage_super_admins", "admin/directories/list_super_admins.tpl");
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


  // Notes

  /**
  * lists all notes associated with a given item
  */
  function list_notes(&$waf, &$user)
  {
    $object_type = WA::request("object_type");
    $object_id = (int) WA::request("object_id");

    $action_links = array(array("add", "section=directories&function=add_note&object_type=$object_type&object_id=$object_id"));
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
    $object_type = WA::request("object_type");
    $object_id = WA::request("object_id");

    $mainlink = $object_type . "_" . $object_id;

    // Get any inbound variables (validation fail), and make the default auth all
    $nvp_array = $waf->get_template_vars("nvp_array");
    if(!strlen($nvp_array['all']))
    {
      $nvp_array['auth'] = 'all';
      $waf->assign("nvp_array", $nvp_array);
    }
    // This needs an override because we use an alternative template, don't know why yet.
    $waf->assign("xinha_editor", true);

    add_object($waf, $user, "Note", array("add", "directories", "add_note_do"), array(array("cancel","section=directories&function=list_notes&object_type=$object_type&object_id=$object_id")), array(array("mainlink", $mainlink)), "admin:directories:list_notes:add_note", "admin/directories/add_note.tpl");
  }

  function add_note_do(&$waf, &$user) 
  {
    $mainlink = WA::request("mainlink");
    $parts = explode("_", $mainlink);
    $object_type = $parts[0];
    $object_id = $parts[1];

    add_object_do($waf, $user, "Note", "section=directories&function=list_notes&object_type=$object_type&object_id=$object_id", "add_note");
  }

  // Company / Vacancy resources

  function manage_company_resources(&$waf)
  {
    if(empty($_SESSION['company_id']))
    {
      // If this company isn't already the active one, make it so
      $company_id = (int) WA::request("company_id", true);
      goto("directories", "manage_company_resources&company_id=$company_id");
    }
    $company_id = (int) WA::request("company_id", true);

    // Ignore pagination for complex reasons
    $waf->assign("nopage", true);

    if(!Policy::check_default_policy("resource", "list")) $waf->halt("error:policy:permissions");
    $waf->log("resources listed", PEAR_LOG_NOTICE, 'general');

    manage_objects($waf, $user, "Resource", array(array("add","section=directories&function=add_company_resource&company_id=$company_id")), array(array('view', 'view_company_resource'), array('edit', 'edit_company_resource'), array('remove','remove_company_resource')), "get_all", array("where company_id=$company_id", "", $page), "admin:configuration:resources:manage_resources", "list.tpl", "company");
  }

  function view_company_resource(&$waf, &$user)
  {
    $id = (int) $_REQUEST["id"];
    require_once("model/Resource.class.php");

    Resource::view($id); 
  }

  function add_company_resource(&$waf, &$user) 
  {
    $company_id = (int) WA::request("company_id", true);
    if(!Policy::check_default_policy("resource", "create")) $waf->halt("error:policy:permissions");

    add_object($waf, $user, "Resource", array("add", "directories", "add_company_resource_do"), array(array("cancel","section=directories&function=manage_company_resources")), array(array("company_id", $company_id), array("lookup", "PRIVATE"), array("auth", "all"), array("channel_id", 0)), "admin:configuration:resources:add_resource", "manage.tpl", "", "company");
  }

  function add_company_resource_do(&$waf, &$user) 
  {
    $company_id = (int) WA::request("company_id", true);
    if(!Policy::check_default_policy("resource", "create")) $waf->halt("error:policy:permissions");
    $waf->log("adding new resource");

    add_object_do($waf, $user, "Resource", "section=directories&function=manage_company_resources&company_id=$company_id", "add_company_resource");
  }

  function edit_company_resource(&$waf, &$user) 
  {
    $company_id = (int) WA::request("company_id", true);
    if(!Policy::check_default_policy("resource", "list")) $waf->halt("error:policy:permissions");
    $waf->log("editing a resource");

    edit_object($waf, $user, "Resource", array("confirm", "directories", "edit_company_resource_do"), array(array("cancel","section=directories&function=manage_company_resources")), array(array("company_id", $company_id), array("lookup", "PRIVATE"), array("auth", "all"), array("channel_id", 0)), "admin:configuration:resources:edit_resource", "manage.tpl", "", "company");
  }

  function edit_company_resource_do(&$waf, &$user) 
  {
    $company_id = (int) WA::request("company_id", true);
    if(!Policy::check_default_policy("resource", "edit")) $waf->halt("error:policy:permissions");
    $waf->log("editing a resource");

    edit_object_do($waf, $user, "Resource", "section=directories&function=manage_company_resources", "edit_company_resource");
  }

  function remove_company_resource(&$waf, &$user) 
  {
    $company_id = (int) WA::request("company_id", true);
    if(!Policy::check_default_policy("resource", "delete")) $waf->halt("error:policy:permissions");
    $waf->log("deleting a resource");

    remove_object($waf, $user, "Resource", array("remove", "directories", "remove_company_resource_do"), array(array("cancel","section=directories&function=manage_company_resources&company_id=$company_id")), "", "admin:configuration:resources:remove_resource");
  }

  function remove_company_resource_do(&$waf, &$user) 
  {
    $company_id = (int) WA::request("company_id", true);
    if(!Policy::check_default_policy("resource", "delete")) $waf->halt("error:policy:permissions");
    $waf->log("deleting a resource");

    remove_object_do($waf, $user, "Resource", "section=directories&function=manage_company_resources&company_id=$company_id");
  }

  // CV management, to allow Admins to manipulate student CVs

  function manage_cvs(&$waf, $user)
  {
    $student_id = (int) WA::request("student_id", true);
    if(!$student_id) $waf->halt("error:student_cv:no_student_id");
    if(!Policy::is_auth_for_student($student_id, "student", "editStatus")) $waf->halt("error:policy:permissions");
    
    $page = WA::request('page', true);
    manage_objects($waf, User::get_id(), "CV", array(array("add","section=directories&function=add_cv")), array(array('edit', 'edit_cv'), array('remove','remove_cv')), "get_all", array("WHERE `user_id`=\"".$student_id."\"", "", $page, True), "student:career:cv_store:manage_cvs");
  }

  function add_cv(&$waf, $user) 
  {
    $student_id = (int) WA::request("student_id", true);
    if(!$student_id) $waf->halt("error:student_cv:no_student_id");
    if(!Policy::is_auth_for_student($student_id, "student", "editStatus")) $waf->halt("error:policy:permissions");
    
    add_object($waf, User::get_id(), "CV", array("add", "directories", "add_cv_do"), array(array("cancel","section=directories&function=manage_cvs")), array(array("user_id", $student_id)), "student:career:cv_store:add_cv");
  }

  function add_cv_do(&$waf, $user) 
  {
    $student_id = (int) WA::request("student_id", true);
    if(!$student_id) $waf->halt("error:student_cv:no_student_id");
    if(!Policy::is_auth_for_student($student_id, "student", "editStatus")) $waf->halt("error:policy:permissions");
    
    add_object_do($waf, User::get_id(), "CV", "section=directories&function=manage_cvs", "add_cv");
  }

  function edit_cv(&$waf, $user) 
  {
    $student_id = (int) WA::request("student_id", true);
    if(!$student_id) $waf->halt("error:student_cv:no_student_id");
    if(!Policy::is_auth_for_student($student_id, "student", "editStatus")) $waf->halt("error:policy:permissions");
    
    edit_object($waf, User::get_id(), "CV", array("confirm", "directories", "edit_cv_do"), array(array("cancel","section=directories&function=manage_cvs")), array(array("user_id",$student_id)), "student:career:cv_store:edit_cv");
  }

  function edit_cv_do(&$waf, $user) 
  {
    $student_id = (int) WA::request("student_id", true);
    if(!$student_id) $waf->halt("error:student_cv:no_student_id");
    if(!Policy::is_auth_for_student($student_id, "student", "editStatus")) $waf->halt("error:policy:permissions");
    
    edit_object_do($waf, User::get_id(), "CV", "section=directories&function=manage_cvs", "edit_cv");
  }

  function remove_cv(&$waf, $user) 
  {
    $student_id = (int) WA::request("student_id", true);
    if(!$student_id) $waf->halt("error:student_cv:no_student_id");
    if(!Policy::is_auth_for_student($student_id, "student", "editStatus")) $waf->halt("error:policy:permissions");
        
    remove_object($waf, User::get_id(), "CV", array("remove", "directories", "remove_cv_do"), array(array("cancel","section=directories&function=manage_cvs")), "", "student:career:cv_store:remove_cv");
  }

  function remove_cv_do(&$waf, $user) 
  {
    $student_id = (int) WA::request("student_id", true);
    if(!$student_id) $waf->halt("error:student_cv:no_student_id");
    if(!Policy::is_auth_for_student($student_id, "student", "editStatus")) $waf->halt("error:policy:permissions");
    
    remove_object_do($waf, User::get_id(), "CV", "section=directories&function=manage_cvs");
  }

  // Password reset

  function reset_password(&$waf)
  {
    $user_id = (int) WA::request("user_id");
    $error_function = WA::request("error_function");
    $done_function = WA::request("done_function");

    require_once("model/User.class.php");
    $success = User::reset_password($user_id);

    if($success || empty($error_function))
    {
      if(!empty($done_function)) goto("directories", "$done_function");
      else header("location:" . $_SERVER['HTTP_REFERER'] . "&changes=true");
    }
    else
    {
      goto("directories", "$error_function");
    }
  }

?>