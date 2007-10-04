<?php

  function student_directory(&$waf, $user, $title)
  {
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

    $waf->assign("students", $objects);
    $waf->display("main.tpl", "admin:directories:student_directory:search_students", "admin/directories/search_students.tpl");
  }

  function simple_search_student(&$waf)
  {
    require_once("model/Student.class.php");
    $initial = WA::request("initial");

    require_once("model/Student.class.php");
    $objects = Student::get_all_by_initial($initial);

    $waf->assign("students", $objects);
    $waf->display("main.tpl", "admin:directories:student_directory:search_students", "admin/directories/search_students.tpl");
  }


  function add_student(&$waf, &$user) 
  {
    add_object($waf, $user, "Student", array("add", "directories", "add_student_do"), array(array("cancel","section=directories&function=manage_students")), array(array("user_id",$user["user_id"])), "admin:directories:student_directory:add_student");
  }

  function add_student_do(&$waf, &$user) 
  {
    add_object_do($waf, $user, "Student", "section=directories&function=manage_students", "add_student");
  }

  function edit_student(&$waf, &$user) 
  {
    // Put student in session to "pick it up"
    $id = $_SESSION['student_id'] = WA::request("id");
    $changes = WA::request("changes");

    goto("directories", "edit_student_real&id=$id&changes=$changes");
  }

  function edit_student_real(&$waf, &$user)
  {
    require_once("model/Student.class.php");
    $id = WA::request("id");
    $student = Student::load_by_id($id);
    $waf->assign("changes", WA::request("changes"));

    edit_object($waf, $user, "Student", array("confirm", "directories", "edit_student_do"), array(array("cancel","section=directories&function=student_directory")), array(array("user_id", $student->user_id)), "admin:directories:student_directory:edit_student", "admin/directories/edit_student.tpl");
  }

  function edit_student_do(&$waf, &$user) 
  {
    $id = WA::request("id");

    edit_object_do($waf, $user, "Student", "section=directories&function=edit_student&id=$id&changes=1", "edit_student_real");
  }

  function remove_student(&$waf, &$user) 
  {
    remove_object($waf, $user, "Student", array("remove", "directories", "remove_student_do"), array(array("cancel","section=directories&function=manage_students")), "", "admin:directories:student_directory:remove_student");
  }

  function remove_student_do(&$waf, &$user) 
  {
    remove_object_do($waf, $user, "Student", "section=directories&function=manage_students");
  }

  // Timelines

  function display_timeline(&$waf, &$user)
  {
    $student_id = (int) WA::request("student_id");
    require_once("model/Timeline.class.php");

    Timeline::display_timeline($student_id);
  }

  // Photos

  function display_photo(&$waf, &$user)
  {
    $user_id = (int) WA::request("user_id");
    $fullsize = WA::request("fullsize");
    require_once("model/Photo.class.php");

    Photo::display_photo($user_id, $fullsize);
  }

  // Vacanies

  function vacancy_directory(&$waf, $user, $title)
  {
    require_once("model/Activitytype.class.php");
    require_once("model/Vacancytype.class.php");
    $activity_types = Activitytype::get_id_and_field("name");
    $vacancy_types = Vacancytype::get_id_and_field("name");
    $sort_types = array("name", "locality");
    $other_options = array("ShowClosed" => "Show Closed", "ShowCompanies" => "Show Companies", "ShowVacancies" => "Show Vacancies");

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
    $id = WA::request("id");

    edit_object($waf, $user, "Company", array("confirm", "directories", "edit_company_do"), array(array("cancel","section=directories&function=manage_companies"), array("contacts", "section=directories&function=manage_contacts&company_id=$id"), array("vacancies", "section=directories&function=manage_vacancies&company_id=$id"), array("applicants", "section=directories&function=manage_applicants&company_id=$id")), array(array("user_id",$user["user_id"])), "admin:directories:companies:edit_company");
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

  function view_company(&$waf, &$user)
  {
    $id = (int) WA::request("company_id");

    $action_links = array(array("edit", "section=directories&function=edit_company&id=$id"));

    require_once("model/Company.class.php");
    $company = Company::load_by_id($id);

    // Some lookups
    require_once("model/Activitytype.class.php");
    $company_activity_names = array();
    foreach($company->activity_types as $activity_type)
    {
      array_push($company_activity_names, Activitytype::get_name($activity_type));
    }

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
    $company_id = (int) WA::request("company_id", true);

    manage_objects($waf, $user, "Vacancy", array(array("add","section=directories&function=add_vacancy&company_id=$company_id"), array("edit company", "section=directories&function=edit_company&id=$company_id")), array(array('edit', 'edit_vacancy'), array('clone', 'clone_vacancy'), array('remove','remove_vacancy')), "get_all", "where company_id=$company_id", "admin:directories:vacancies:manage_vacancies");
  }

  function add_vacancy(&$waf, &$user) 
  {
    $company_id = (int) WA::request("company_id", true);
    require_once("model/Company.class.php");
    $company = new Company;
    $company = $company->load_by_id($company_id);

    foreach(array("address1", "address2", "address3", "postcode", "locality", "town", "country") as $field)
    {
      $nvp_array[$field] = $company->$field;
    }
    $waf->assign("nvp_array", $nvp_array);

    add_object($waf, $user, "Vacancy", array("add", "directories", "add_vacancy_do"), array(array("cancel","section=directories&function=manage_vacancies")), array(array("company_id", $company_id), array("user_id",$user["user_id"])), "admin:directories:vacancies:add_vacancy");
  }

  /**
  * @todo activities don't copy across, manage.tpl needs changed.
  */
  function clone_vacancy(&$waf, &$user) 
  {
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

    add_object($waf, $user, "Vacancy", array("add", "directories", "add_vacancy_do"), array(array("cancel","section=directories&function=manage_vacancies")), array(array("company_id", $company_id), array("user_id",$user["user_id"])), "admin:directories:vacancies:clone_vacancy");
  }

  function add_vacancy_do(&$waf, &$user) 
  {
    $company_id = (int) WA::request("company_id", true);

    add_object_do($waf, $user, "Vacancy", "section=directories&function=manage_vacancies&company_id=$company_id", "add_vacancy");
  }

  function edit_vacancy(&$waf, &$user) 
  {
    $id = (int) WA::request("id");
    $company_id = (int) WA::request("company_id", true);
    $waf->assign("xinha_editor", true);

    edit_object($waf, $user, "Vacancy", array("confirm", "directories", "edit_vacancy_do"), array(array("cancel","section=directories&function=manage_vacancies"), array("view","section=directories&function=view_vacancy&id=$id")), array(array("company_id", $company_id), array("user_id",$user["user_id"])), "admin:directories:vacancy_directory:edit_vacancy", "admin/directories/edit_vacancy.tpl");
  }

  function edit_vacancy_do(&$waf, &$user) 
  {
    $company_id = (int) WA::request("company_id", true);

    edit_object_do($waf, $user, "Vacancy", "section=directories&function=manage_vacancies&company_id=$company_id", "edit_vacancy");
  }

  function remove_vacancy(&$waf, &$user) 
  {
    $company_id = (int) WA::request("company_id", true);

    remove_object($waf, $user, "Vacancy", array("remove", "directories", "remove_vacancy_do"), array(array("cancel","section=directories&function=manage_vacancies&company_id=$company_id")), "", "admin:directories:vacancies:remove_vacancy");
  }

  function remove_vacancy_do(&$waf, &$user) 
  {
    $company_id = (int) WA::request("company_id", true);

    remove_object_do($waf, $user, "Vacancy", "section=directories&function=manage_vacancies&company_id=$company_id");
  }

  function view_vacancy(&$waf, &$user)
  {
    $id = (int) WA::request("id");
    $student_id = $_SESSION["student_id"];

    require_once("model/Vacancy.class.php");
    $vacancy = Vacancy::load_by_id($id);

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


    $waf->assign("action_links", $action_links);
    $waf->assign("vacancy", $vacancy);
    $waf->assign("company", $company);
    $waf->assign("vacancy_activity_names", $vacancy_activity_names);
    $waf->assign("company_activity_names", $company_activity_names);
    $waf->assign("show_heading", true);

    $waf->display("main.tpl", "admin:directories:vacancy_directory:view_vacancy", "admin/directories/view_vacancy.tpl");
  }

  // Applications

  /**
  * tag a student as having applied for a vacancy
  */
  function add_application(&$waf, &$user)
  {
    $vacancy_id = (int) WA::request("id");
    $student_id = $_SESSION['student_id'];

    require_once("model/PDSystem.class.php");
    $cv_status = PDSystem::get_cv_status($student_id);

    print_r($cv_status);
  }

  // Contacts

  function contact_directory(&$waf)
  {
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
    require_once("model/Contact.class.php");

    $company_id = (int) WA::request("company_id", true);

    if($company_id)
    {
      $objects = Contact::get_all_by_company($company_id);

      $headings = array(
        'real_name'=>array('type'=>'text','size'=>30, 'header'=>true, title=>'Name'),
        'position'=>array('type'=>'list','size'=>30, 'header'=>true, title=>'Position'),
        'email'=>array('type'=>'email','size'=>40, 'header'=>true),
        'voice'=>array('type'=>'text','size'=>40, 'header'=>true, title=>'Phone')
      );
      $actions = array(array('edit', 'edit_contact'));

      $waf->assign("headings", $headings);
      $waf->assign("objects", $objects);
      $waf->assign("actions", $actions);
      $waf->assign("action_links", array(array("Add", "section=directories&function=add_contact")));
    }
    $waf->display("main.tpl", "admin:directories:contact_directory:search_contacts", "list.tpl");
  }

  function add_contact(&$waf, &$user) 
  {
    $company_id = (int) WA::request("company_id", true);

    add_object($waf, $user, "Contact", array("add", "directories", "add_contact_do"), array(array("cancel","section=directories&function=manage_contacts")), array(array("user_id",$user["user_id"]), array("company_id", $company_id)), "admin:directories:contacts:add_contact");
  }

  function add_contact_do(&$waf, &$user) 
  {
    add_object_do($waf, $user, "Contact", "section=directories&function=manage_contacts", "add_contact");
  }

  function edit_contact(&$waf, &$user) 
  {
    require_once("model/Contact.class.php");
    $id = WA::request("id");
    $contact = Contact::load_by_id($id);

    edit_object($waf, $user, "Contact", array("confirm", "directories", "edit_contact_do"), array(array("cancel","section=directories&function=manage_contacts")), array(array("user_id", $contact->user_id)), "admin:directories:contact_directory:edit_contact");
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

  // Staff

  function staff_directory(&$waf)
  {
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
    require_once("model/Staff.class.php");
    $search = WA::request("search");
    $sort = WA::request("sort");
    $schools = WA::request("schools");

    if(!preg_match('/^[A-Za-z0-9 ]*$/', $search)) $waf->halt("error:staffs:invalid_search");

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

    $waf->assign("actions", $actions);
    $waf->assign("headings", $headings);
    $waf->assign("objects", $objects);

    $waf->display("main.tpl", "admin:directories:staff_directory:search_staff", "list.tpl");
  }

  function simple_search_staff(&$waf)
  {
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

    $waf->assign("actions", $actions);
    $waf->assign("headings", $headings);
    $waf->assign("objects", $objects);

    $waf->display("main.tpl", "admin:directories:staff_directory:simple_search_staff", "list.tpl");

  }


  function manage_staff(&$waf, $user, $title)
  {
    manage_objects($waf, $user, "Staff", array(array("add","section=directories&function=add_staff")), array(array('edit', 'edit_staff'), array('remove','remove_staff')), "get_all", "", "staff:directories:staff_directory:manage_staff");
  }

  function add_staff(&$waf, &$user) 
  {
    add_object($waf, $user, "Staff", array("add", "directories", "add_staff_do"), array(array("cancel","section=directories&function=manage_staff")), array(array("user_id",$user["user_id"])), "staff:directories:staff_directory:add_staff");
  }

  function add_staff_do(&$waf, &$user) 
  {
    add_object_do($waf, $user, "Staff", "section=directories&function=manage_staff", "add_staff");
  }

  function edit_staff(&$waf, &$user) 
  {
    require_once("model/Staff.class.php");
    $id = WA::request("id");
    $staff = Staff::load_by_id($id);

    edit_object($waf, $user, "Staff", array("confirm", "directories", "edit_staff_do"), array(array("cancel","section=directories&function=manage_staff")), array(array("user_id", $staff->user_id)), "staff:directories:staff_directory:edit_staff");
  }

  function edit_staff_do(&$waf, &$user) 
  {
    edit_object_do($waf, $user, "Staff", "section=directories&function=manage_staff", "edit_staff");
  }

  function remove_staff(&$waf, &$user) 
  {
    remove_object($waf, $user, "Staff", array("remove", "directories", "remove_staff_do"), array(array("cancel","section=directories&function=manage_staff")), "", "staff:directories:staff_directory:remove_staff");
  }

  function remove_staff_do(&$waf, &$user) 
  {
    remove_object_do($waf, $user, "Staff", "section=directories&function=manage_staff");
  }

  // Admin

  function manage_admins(&$waf, $user, $title)
  {
    manage_objects($waf, $user, "Admin", array(array("add","section=directories&function=add_admin")), array(array('edit', 'edit_admin'), array('remove','remove_admin')), "get_all", "", "admin:directories:admin_directory:manage_admins");
  }

  function add_admin(&$waf, &$user) 
  {
    add_object($waf, $user, "Admin", array("add", "directories", "add_admin_do"), array(array("cancel","section=directories&function=manage_admins")), array(array("user_id",$user["user_id"])), "admin:directories:admin_directory:add_admin");
  }

  function add_admin_do(&$waf, &$user) 
  {
    add_object_do($waf, $user, "Admin", "section=directories&function=manage_admins", "add_admin");
  }

  function edit_admin(&$waf, &$user) 
  {
    require_once("model/Admin.class.php");
    $id = WA::request("id");
    $admin = Admin::load_by_id($id);

    edit_object($waf, $user, "Admin", array("confirm", "directories", "edit_admin_do"), array(array("cancel","section=directories&function=manage_admins")), array(array("user_id", $admin->user_id)), "admin:directories:admin_directory:edit_admin");
  }

  function edit_admin_do(&$waf, &$user) 
  {
    edit_object_do($waf, $user, "Admin", "section=directories&function=manage_admins", "edit_admin");
  }

  function remove_admin(&$waf, &$user) 
  {
    remove_object($waf, $user, "Admin", array("remove", "directories", "remove_admin_do"), array(array("cancel","section=directories&function=manage_admins")), "", "admin:directories:admin_directory:remove_admin");
  }

  function remove_admin_do(&$waf, &$user) 
  {
    remove_object_do($waf, $user, "Admin", "section=directories&function=manage_admins");
  }




?>