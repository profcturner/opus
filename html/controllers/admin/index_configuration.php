<?php

  // Resources

  function manage_resources(&$waf, $user, $title)
  {
    if(!Policy::check_default_policy("resources", "list")) $waf->halt("error:policy:permissions");
    $waf->log("resources listed", PEAR_LOG_NOTICE, 'general');

    manage_objects($waf, $user, "Resource", array(array("add","section=configuration&function=add_resource")), array(array('edit', 'edit_resource'), array('remove','remove_resource')), "get_all", "", "admin:configuration:resources:manage_resources");
  }

  function add_resource(&$waf, &$user) 
  {
    if(!Policy::check_default_policy("resources", "create")) $waf->halt("error:policy:permissions");

    add_object($waf, $user, "Resource", array("add", "configuration", "add_resource_do"), array(array("cancel","section=configuration&function=manage_resources")), array(array("user_id",$user["user_id"])), "admin:configuration:resources:add_resource");
  }

  function add_resource_do(&$waf, &$user) 
  {
    if(!Policy::check_default_policy("resources", "create")) $waf->halt("error:policy:permissions");
    $waf->log("adding new resource");

    add_object_do($waf, $user, "Resource", "section=configuration&function=manage_resources", "add_resource");
  }

  function edit_resource(&$waf, &$user) 
  {
    if(!Policy::check_default_policy("resources", "list")) $waf->halt("error:policy:permissions");
    $waf->log("editing a resource");

    edit_object($waf, $user, "Resource", array("confirm", "configuration", "edit_resource_do"), array(array("cancel","section=configuration&function=manage_resources")), array(array("user_id",$user["user_id"])), "admin:configuration:resources:edit_resource");
  }

  function edit_resource_do(&$waf, &$user) 
  {
    if(!Policy::check_default_policy("resources", "edit")) $waf->halt("error:policy:permissions");
    $waf->log("editing a resource");

    edit_object_do($waf, $user, "Resource", "section=configuration&function=manage_resources", "edit_resource");
  }

  function remove_resource(&$waf, &$user) 
  {
    if(!Policy::check_default_policy("resources", "delete")) $waf->halt("error:policy:permissions");
    $waf->log("deleting a resource");

    remove_object($waf, $user, "Resource", array("remove", "configuration", "remove_resource_do"), array(array("cancel","section=configuration&function=manage_resources")), "", "admin:configuration:resources:remove_resource");
  }

  function remove_resource_do(&$waf, &$user) 
  {
    if(!Policy::check_default_policy("resources", "delete")) $waf->halt("error:policy:permissions");
    $waf->log("deleting a resource");

    remove_object_do($waf, $user, "Resource", "section=configuration&function=manage_resources");
  }


  // Organisation

  function organisation_details(&$waf, &$user, $title) 
  {
    manage_faculties(&$waf, &$user, $title);
  }

  // Faculties

  function manage_faculties(&$waf, $user, $title)
  {
    set_navigation_history($waf, "Faculties");

    manage_objects($waf, $user, "Faculty", array(array("add","section=configuration&function=add_faculty")), array(array('admins', 'manage_facultyadmins'), array('schools', 'manage_schools'), array('edit', 'edit_faculty'), array('remove','remove_faculty')), "get_all", "", "admin:configuration:organisation_details:manage_faculties");
  }

  function add_faculty(&$waf, &$user) 
  {
    add_object($waf, $user, "Faculty", array("add", "configuration", "add_faculty_do"), array(array("cancel","section=configuration&function=manage_faculties")), array(array("user_id",$user["user_id"])), "admin:configuration:organisation_details:add_faculty");
  }

  function add_faculty_do(&$waf, &$user) 
  {
    add_object_do($waf, $user, "Faculty", "section=configuration&function=manage_faculties", "add_faculty");
  }

  function edit_faculty(&$waf, &$user) 
  {
    edit_object($waf, $user, "Faculty", array("confirm", "configuration", "edit_faculty_do"), array(array("cancel","section=configuration&function=manage_faculties")), array(array("user_id",$user["user_id"])), "admin:configuration:organisation_details:edit_faculty");
  }

  function edit_faculty_do(&$waf, &$user) 
  {
    edit_object_do($waf, $user, "Faculty", "section=configuration&function=manage_faculties", "edit_faculty");
  }

  function remove_faculty(&$waf, &$user) 
  {
    remove_object($waf, $user, "Faculty", array("remove", "configuration", "remove_faculty_do"), array(array("cancel","section=configuration&function=manage_faculties")), "", "admin:configuration:organisation_details:remove_faculty");
  }

  function remove_faculty_do(&$waf, &$user) 
  {
    remove_object_do($waf, $user, "Faculty", "section=configuration&function=manage_faculties");
  }

  // Schools

  function manage_schools(&$waf, $user, $title)
  {

    $faculty_id = (int) WA::request("id", true);

    require_once("model/Faculty.class.php");
    $faculty = Faculty::load_by_id($faculty_id);

    add_navigation_history($waf, $faculty->name);

    manage_objects($waf, $user, "School", array(array("add","section=configuration&function=add_school")), array(array('admins', 'manage_schooladmins'), array('programmes', 'manage_programmes'), array('edit', 'edit_school'), array('remove','remove_school')), "get_all", "where faculty_id=$faculty_id", "admin:configuration:organisation_details:manage_schools");
  }

  function add_school(&$waf, &$user) 
  {
    $faculty_id = (int) WA::request("id", true);

    // Make sure the school is set correctly
    $nvp_array["faculty_id"] = $faculty_id;
    $waf->assign("nvp_array", $nvp_array);

    add_object($waf, $user, "School", array("add", "configuration", "add_school_do"), array(array("cancel","section=configuration&function=manage_schools")), array(array("user_id",$user["user_id"])), "admin:configuration:organisation_details:add_school");
  }

  function add_school_do(&$waf, &$user) 
  {
    add_object_do($waf, $user, "School", "section=configuration&function=manage_schools", "add_school");
  }

  function edit_school(&$waf, &$user) 
  {
    $faculty_id = (int) WA::request("id", true);

    edit_object($waf, $user, "School", array("confirm", "configuration", "edit_school_do"), array(array("cancel","section=configuration&function=manage_schools")), array(array("user_id",$user["user_id"])), "admin:configuration:organisation_details:edit_school");
  }

  function edit_school_do(&$waf, &$user) 
  {
    edit_object_do($waf, $user, "School", "section=configuration&function=manage_schools", "edit_school");
  }

  function remove_school(&$waf, &$user) 
  {
    remove_object($waf, $user, "School", array("remove", "configuration", "remove_school_do"), array(array("cancel","section=configuration&function=manage_schools")), "", "admin:configuration:organisation_details:remove_school");
  }

  function remove_school_do(&$waf, &$user) 
  {
    remove_object_do($waf, $user, "School", "section=configuration&function=manage_schools");
  }

  // Faculty Admins

  function manage_facultyadmins(&$waf, $user, $title)
  {
    $faculty_id = (int) WA::request("id", true);

    require_once("model/Admin.class.php");
    $objects = Admin::get_all_by_faculty($faculty_id);

    $headings = array(
      'real_name'=>array('type'=>'text','size'=>30, 'header'=>true, title=>'Name'),
      '_level_policy_name'=>array('type'=>'text','size'=>30, 'header'=>true, 'title'=>'Policy'),
      'email'=>array('type'=>'email','size'=>40, 'header'=>true),
      'voice'=>array('type'=>'text','size'=>40, 'header'=>true, title=>'Phone')
    );
    $action_links = array(array('add', "section=configuration&function=add_facultyadmin&faculty_id=$faculty_id"));
    $actions = array(array('remove', 'remove_facultyadmin'));

    $waf->assign("actions", $actions);
    $waf->assign("action_links", $action_links);
    $waf->assign("headings", $headings);
    $waf->assign("objects", $objects);

    //add_navigation_history($waf, $faculty->name);
    $waf->display("main.tpl", "admin:configuration:organisation_details:manage_facultyadmins", "list.tpl");
  }

  function add_facultyadmin(&$waf)
  {
    $faculty_id = (int) WA::request("faculty_id", true);

    require_once("model/Admin.class.php");
    require_once("model/Policy.class.php");

    // Don't pick up root users, they are irrelevant here.
    $admins = Admin::get_user_id_and_name("where user_type = 'admin'");
    $policies = Policy::get_id_and_field("name");
    $policies[0] = "Default for this Administrator";
    $function = "add_facultyadmin_do";

    $waf->assign("type", "faculty_id");
    $waf->assign("object_id", $faculty_id);
    $waf->assign("function", $function);
    $waf->assign("admins", $admins);
    $waf->assign("policies", $policies);

    $waf->display("main.tpl", "admin:configuration:organisation_details:add_facultyadmin", "admin/configuration/add_level_admin.tpl");
  }

  function add_facultyadmin_do(&$waf, &$user) 
  {
    add_object_do($waf, $user, "FacultyAdmin", "section=configuration&function=manage_facultyadmins", "add_facultyadmin");
  }

  function remove_facultyadmin(&$waf, &$user) 
  {
    remove_object($waf, $user, "FacultyAdmin", array("remove", "configuration", "remove_facultyadmin_do"), array(array("cancel","section=configuration&function=manage_facultyadmins")), "", "admin:configuration:organisation_details:remove_facultyadmin");
  }

  function remove_facultyadmin_do(&$waf, &$user) 
  {
    remove_object_do($waf, $user, "FacultyAdmin", "section=configuration&function=manage_facultyadmins");
  }

  // School Admins

  function manage_schooladmins(&$waf, $user, $title)
  {
    $school_id = (int) WA::request("id", true);

    require_once("model/Admin.class.php");
    $objects = Admin::get_all_by_school($school_id);

    $headings = array(
      'real_name'=>array('type'=>'text','size'=>30, 'header'=>true, title=>'Name'),
      '_level_policy_name'=>array('type'=>'text','size'=>30, 'header'=>true, 'title'=>'Policy'),
      'email'=>array('type'=>'email','size'=>40, 'header'=>true),
      'voice'=>array('type'=>'text','size'=>40, 'header'=>true, title=>'Phone')
    );
    $action_links = array(array('add', "section=configuration&function=add_schooladmin&school_id=$school_id"));
    $actions = array(array('remove', 'remove_schooladmin'));

    $waf->assign("actions", $actions);
    $waf->assign("action_links", $action_links);
    $waf->assign("headings", $headings);
    $waf->assign("objects", $objects);

    //add_navigation_history($waf, $faculty->name);
    $waf->display("main.tpl", "admin:configuration:organisation_details:manage_schooladmins", "list.tpl");
  }

  function add_schooladmin(&$waf)
  {
    $school_id = (int) WA::request("school_id", true);

    require_once("model/Admin.class.php");
    require_once("model/Policy.class.php");

    // Don't pick up root users, they are irrelevant here.
    $admins = Admin::get_user_id_and_name("where user_type = 'admin'");
    $policies = Policy::get_id_and_field("name");
    $policies[0] = "Default for this Administrator";
    $function = "add_schooladmin_do";

    $waf->assign("type", "school_id");
    $waf->assign("object_id", $school_id);
    $waf->assign("function", $function);
    $waf->assign("admins", $admins);
    $waf->assign("policies", $policies);

    $waf->display("main.tpl", "admin:configuration:organisation_details:add_schooladmin", "admin/configuration/add_level_admin.tpl");
  }

  function add_schooladmin_do(&$waf, &$user) 
  {
    add_object_do($waf, $user, "SchoolAdmin", "section=configuration&function=manage_schooladmins", "add_schooladmin");
  }

  function remove_schooladmin(&$waf, &$user) 
  {
    remove_object($waf, $user, "SchoolAdmin", array("remove", "configuration", "remove_schooladmin_do"), array(array("cancel","section=configuration&function=manage_schooladmins")), "", "admin:configuration:organisation_details:remove_schooladmin");
  }

  function remove_schooladmin_do(&$waf, &$user) 
  {
    remove_object_do($waf, $user, "SchoolAdmin", "section=configuration&function=manage_schooladmins");
  }

  // Programme Admins

  function manage_programmeadmins(&$waf, $user, $title)
  {
    $programme_id = (int) WA::request("id", true);

    require_once("model/Admin.class.php");
    $objects = Admin::get_all_by_programme($programme_id);

    $headings = array(
      'real_name'=>array('type'=>'text','size'=>30, 'header'=>true, title=>'Name'),
      '_level_policy_name'=>array('type'=>'text','size'=>30, 'header'=>true, 'title'=>'Policy'),
      'email'=>array('type'=>'email','size'=>40, 'header'=>true),
      'voice'=>array('type'=>'text','size'=>40, 'header'=>true, title=>'Phone')
    );
    $action_links = array(array('add', "section=configuration&function=add_programmeadmin&programme_id=$programme_id"));
    $actions = array(array('remove', 'remove_programmeadmin'));

    $waf->assign("actions", $actions);
    $waf->assign("action_links", $action_links);
    $waf->assign("headings", $headings);
    $waf->assign("objects", $objects);

    //add_navigation_history($waf, $faculty->name);
    $waf->display("main.tpl", "admin:configuration:organisation_details:manage_programmeadmins", "list.tpl");
  }

  function add_programmeadmin(&$waf)
  {
    $programme_id = (int) WA::request("programme_id", true);

    require_once("model/Admin.class.php");
    require_once("model/Policy.class.php");

    // Don't pick up root users, they are irrelevant here.
    $admins = Admin::get_user_id_and_name("where user_type = 'admin'");
    $policies = Policy::get_id_and_field("name");
    $policies[0] = "Default for this Administrator";
    $function = "add_programmeadmin_do";

    $waf->assign("type", "programme_id");
    $waf->assign("object_id", $programme_id);
    $waf->assign("function", $function);
    $waf->assign("admins", $admins);
    $waf->assign("policies", $policies);

    $waf->display("main.tpl", "admin:configuration:organisation_details:add_programmeadmin", "admin/configuration/add_level_admin.tpl");
  }

  function add_programmeadmin_do(&$waf, &$user) 
  {
    add_object_do($waf, $user, "ProgrammeAdmin", "section=configuration&function=manage_programmeadmins", "add_programmeadmin");
  }

  function remove_programmeadmin(&$waf, &$user) 
  {
    remove_object($waf, $user, "ProgrammeAdmin", array("remove", "configuration", "remove_programmeadmin_do"), array(array("cancel","section=configuration&function=manage_programmeadmins")), "", "admin:configuration:organisation_details:remove_programmeadmin");
  }

  function remove_programmeadmin_do(&$waf, &$user) 
  {
    remove_object_do($waf, $user, "ProgrammeAdmin", "section=configuration&function=manage_programmeadmins");
  }

  // Programmes

  function manage_programmes(&$waf, $user, $title)
  {
    add_navigation_history($waf, "Programmes");

    $school_id = (int) WA::request("id", true);

    require_once("model/School.class.php");
    $school = School::load_by_id($school_id);

    add_navigation_history($waf, $school->name);


    manage_objects($waf, $user, "Programme", array(array("add","section=configuration&function=add_programme")), array(array('admins', 'manage_programmeadmins'), array('assessment', 'manage_assessmentgroupprogrammes'), array('edit', 'edit_programme'), array('remove','remove_programme')), "get_all", "where school_id=$school_id", "admin:configuration:organisation_details:manage_programmes");
  }

  function add_programme(&$waf, &$user) 
  {
    $school_id = (int) WA::request("id", true);

    add_navigation_history($waf, "Add Programme");

    // Make sure the school is set correctly
    $nvp_array["school_id"] = $school_id;
    $waf->assign("nvp_array", $nvp_array);

    add_object($waf, $user, "Programme", array("add", "configuration", "add_programme_do"), array(array("cancel","section=configuration&function=manage_programmes")), array(array("user_id",$user["user_id"])), "admin:configuration:organisation_details:add_programme");
  }

  function add_programme_do(&$waf, &$user) 
  {
    add_object_do($waf, $user, "Programme", "section=configuration&function=manage_programmes", "add_programme");
  }

  function edit_programme(&$waf, &$user) 
  {
    $school_id = (int) WA::request("id", true);

    edit_object($waf, $user, "Programme", array("confirm", "configuration", "edit_programme_do"), array(array("cancel","section=configuration&function=manage_programmes")), array(array("user_id",$user["user_id"])), "admin:configuration:organisation_details:edit_programme");
  }

  function edit_programme_do(&$waf, &$user) 
  {
    edit_object_do($waf, $user, "Programme", "section=configuration&function=manage_programmes", "edit_programme");
  }

  function remove_programme(&$waf, &$user) 
  {
    remove_object($waf, $user, "Programme", array("remove", "configuration", "remove_programme_do"), array(array("cancel","section=configuration&function=manage_programmes")), "", "admin:configuration:organisation_details:remove_programme");
  }

  function remove_programme_do(&$waf, &$user) 
  {
    remove_object_do($waf, $user, "Programme", "section=configuration&function=manage_programmes");
  }

  // Assessmentgroups

  function manage_assessmentgroups(&$waf, $user, $title)
  {
    manage_objects($waf, $user, "Assessmentgroup", array(array("add","section=configuration&function=add_assessmentgroup")), array(array('regime', 'manage_assessmentregimes'), array('edit', 'edit_assessmentgroup'), array('remove','remove_assessmentgroup')), "get_all", "", "admin:configuration:manage_assessmentgroups:manage_assessmentgroups");
  }

  function add_assessmentgroup(&$waf, &$user) 
  {
    add_object($waf, $user, "Assessmentgroup", array("add", "configuration", "add_assessmentgroup_do"), array(array("cancel","section=configuration&function=manage_assessmentgroups")), array(array("user_id",$user["user_id"])), "admin:configuration:manage_assessmentgroups:add_assessmentgroup");
  }

  function add_assessmentgroup_do(&$waf, &$user) 
  {
    add_object_do($waf, $user, "Assessmentgroup", "section=configuration&function=manage_assessmentgroups", "add_assessmentgroup");
  }

  function edit_assessmentgroup(&$waf, &$user) 
  {
    edit_object($waf, $user, "Assessmentgroup", array("confirm", "configuration", "edit_assessmentgroup_do"), array(array("cancel","section=configuration&function=manage_assessmentgroups")), array(array("user_id",$user["user_id"])), "admin:configuration:manage_assessmentgroups:edit_assessmentgroup");
  }

  function edit_assessmentgroup_do(&$waf, &$user) 
  {
    edit_object_do($waf, $user, "Assessmentgroup", "section=configuration&function=manage_assessmentgroups", "edit_assessmentgroup");
  }

  function remove_assessmentgroup(&$waf, &$user) 
  {
    remove_object($waf, $user, "Assessmentgroup", array("remove", "configuration", "remove_assessmentgroup_do"), array(array("cancel","section=configuration&function=manage_assessmentgroups")), "", "admin:configuration:manage_assessmentgroups:remove_assessmentgroup");
  }

  function remove_assessmentgroup_do(&$waf, &$user) 
  {
    remove_object_do($waf, $user, "Assessmentgroup", "section=configuration&function=manage_assessmentgroups");
  }

  // Assessmentregimes

  function manage_assessmentregimes(&$waf, $user, $title)
  {
    $group_id = (int) WA::request('id', true);

    manage_objects($waf, $user, "Assessmentregime", array(array("add","section=configuration&function=add_assessmentregime")), array(array('edit', 'edit_assessmentregime'), array('remove','remove_assessmentregime')), "get_all", "where group_id=$group_id", "admin:configuration:manage_assessmentgroups:manage_assessmentregimes");
  }

  function add_assessmentregime(&$waf, &$user) 
  {
    $group_id = (int) WA::request('id', true);

    add_object($waf, $user, "Assessmentregime", array("add", "configuration", "add_assessmentregime_do"), array(array("cancel","section=configuration&function=manage_assessmentregimes")), array(array("user_id",$user["user_id"]), array("group_id", $group_id)), "admin:configuration:manage_assessmentgroups:add_assessmentregime");
  }

  function add_assessmentregime_do(&$waf, &$user) 
  {
    add_object_do($waf, $user, "Assessmentregime", "section=configuration&function=manage_assessmentregimes", "add_assessmentregime");
  }

  function edit_assessmentregime(&$waf, &$user) 
  {
    $group_id = (int) WA::request('id', true);

    edit_object($waf, $user, "Assessmentregime", array("confirm", "configuration", "edit_assessmentregime_do"), array(array("cancel","section=configuration&function=manage_assessmentregimes")), array(array("user_id",$user["user_id"]), array("group_id", $group_id)), "admin:configuration:manage_assessmentgroups:edit_assessmentregime");
  }

  function edit_assessmentregime_do(&$waf, &$user) 
  {
    edit_object_do($waf, $user, "Assessmentregime", "section=configuration&function=manage_assessmentregimes", "edit_assessmentregime");
  }

  function remove_assessmentregime(&$waf, &$user) 
  {
    $group_id = (int) WA::request('id', true);

    remove_object($waf, $user, "Assessmentregime", array("remove", "configuration", "remove_assessmentregime_do"), array(array("cancel","section=configuration&function=manage_assessmentregimes")), "", "admin:configuration:manage_assessmentgroups:remove_assessmentregime");
  }

  function remove_assessmentregime_do(&$waf, &$user) 
  {
    remove_object_do($waf, $user, "Assessmentregime", "section=configuration&function=manage_assessmentregimes");
  }

  // AssessmentGroupProgrammes

  function manage_assessmentgroupprogrammes(&$waf, $user, $title)
  {

    $programme_id = (int) WA::request("id", true);

    add_navigation_history($waf, $faculty->name);

    manage_objects($waf, $user, "AssessmentGroupProgramme", array(array("add","section=configuration&function=add_assessmentgroupprogramme")), array(array('edit', 'edit_assessmentgroupprogramme'), array('remove','remove_assessmentgroupprogramme')), "get_all", "where programme_id=$programme_id", "admin:configuration:organisation_details:manage_assessmentgroupprogrammes");
  }

  function add_assessmentgroupprogramme(&$waf, &$user) 
  {
    $programme_id = (int) WA::request("id", true);

    add_object($waf, $user, "AssessmentGroupProgramme", array("add", "configuration", "add_assessmentgroupprogramme_do"), array(array("cancel","section=configuration&function=manage_assessmentgroupprogrammes")), array(array("user_id",$user["user_id"]), array("programme_id", $programme_id)), "admin:configuration:organisation_details:add_assessmentgroupprogramme");
  }

  function add_assessmentgroupprogramme_do(&$waf, &$user) 
  {
    add_object_do($waf, $user, "AssessmentGroupProgramme", "section=configuration&function=manage_assessmentgroupprogrammes", "add_assessmentgroupprogramme");
  }

  function edit_assessmentgroupprogramme(&$waf, &$user) 
  {
    $programme_id = (int) WA::request("id", true);

    edit_object($waf, $user, "AssessmentGroupProgramme", array("confirm", "configuration", "edit_assessmentgroupprogramme_do"), array(array("cancel","section=configuration&function=manage_assessmentgroupprogrammes")), array(array("user_id",$user["user_id"]), array("programme_id", $programme_id)), "admin:configuration:organisation_details:edit_assessmentgroupprogramme");
  }

  function edit_assessmentgroupprogramme_do(&$waf, &$user) 
  {
    edit_object_do($waf, $user, "AssessmentGroupProgramme", "section=configuration&function=manage_assessmentgroupprogrammes", "edit_assessmentgroupprogramme");
  }

  function remove_assessmentgroupprogramme(&$waf, &$user) 
  {
    remove_object($waf, $user, "AssessmentGroupProgramme", array("remove", "configuration", "remove_assessmentgroupprogramme_do"), array(array("cancel","section=configuration&function=manage_assessmentgroupprogrammes")), "", "admin:configuration:organisation_details:remove_assessmentgroupprogramme");
  }

  function remove_assessmentgroupprogramme_do(&$waf, &$user) 
  {
    remove_object_do($waf, $user, "AssessmentGroupProgramme", "section=configuration&function=manage_assessmentgroupprogrammes");
  }


  // CVGroups

  function manage_cvgroups(&$waf, $user, $title)
  {
    require_once("model/PDSystem.class.php");

    $actions = array(array('edit', 'edit_cvgroup'), array('remove','remove_cvgroup'));
    if(PDSystem::exists())
    {
      array_push($actions, array('templates', 'manage_cvgroup_templates'));
    }

    manage_objects($waf, $user, "CVGroup", array(array("add","section=configuration&function=add_cvgroup")), $actions , "get_all", "", "admin:configuration:manage_cvgroups:manage_cvgroups");
  }

  function add_cvgroup(&$waf, &$user) 
  {
    add_object($waf, $user, "CVGroup", array("add", "configuration", "add_cvgroup_do"), array(array("cancel","section=configuration&function=manage_cvgroups")), array(array("user_id",$user["user_id"])), "admin:configuration:manage_cvgroups:add_cvgroup");
  }

  function add_cvgroup_do(&$waf, &$user) 
  {
    add_object_do($waf, $user, "CVGroup", "section=configuration&function=manage_cvgroups", "add_cvgroup");
  }

  function edit_cvgroup(&$waf, &$user) 
  {
    edit_object($waf, $user, "CVGroup", array("confirm", "configuration", "edit_cvgroup_do"), array(array("cancel","section=configuration&function=manage_cvgroups")), array(array("user_id",$user["user_id"])), "admin:configuration:manage_cvgroups:edit_cvgroup");
  }

  function edit_cvgroup_do(&$waf, &$user) 
  {
    edit_object_do($waf, $user, "CVGroup", "section=configuration&function=manage_cvgroups", "edit_cvgroup");
  }

  function remove_cvgroup(&$waf, &$user) 
  {
    remove_object($waf, $user, "CVGroup", array("remove", "configuration", "remove_cvgroup_do"), array(array("cancel","section=configuration&function=manage_cvgroups")), "", "admin:configuration:manage_cvgroups:remove_cvgroup");
  }

  function remove_cvgroup_do(&$waf, &$user) 
  {
    remove_object_do($waf, $user, "CVGroup", "section=configuration&function=manage_cvgroups");
  }

  function manage_cvgroup_templates(&$waf, $user, $title)
  {
    $group_id = (int) WA::request("id");

    require_once("model/CVGroup.class.php");
    $group_info = CVGroup::load_by_id($group_id);

    require_once("model/PDSystem.class.php");

    // Get possible PDSystem templates
    $pdp_templates_object = PDSystem::get_cv_templates();
    $pdp_templates = $pdp_templates_object->xpath('//template');

    // Get current permissions for this group
    require_once("model/CVGroupTemplate.class.php");
    $cvgrouptemplates = CVGroupTemplate::get_all("where group_id=$group_id");

    // Assemble permissions from these
    $opus_permissions = array();
    foreach($cvgrouptemplates as $template)
    {
      $opus_permission = array();

      // Check if it is allowed
      if(strpos($template->settings, "allow") === false)
      {
        $opus_permission['allow'] = false;
      }
      else
      {
        $opus_permission['allow'] = true;
      }

      // Check if approval is required
      if(strpos($template->settings, "requiresApproval") === false)
      {
        $opus_permission['requiresApproval'] = false;
      }
      else
      {
        $opus_permission['requiresApproval'] = true;
      }
      $opus_permissions[$template->template_id] = $opus_permission;
    }
    $waf->assign("action_links", array(array("cancel","section=configuration&function=manage_cvgroups")));
    $waf->assign("group_info", $group_info);
    $waf->assign("pdp_templates", $pdp_templates);
    $waf->assign("opus_permissions", $opus_permissions);

    $waf->display("main.tpl", "admin:configuration:manage_cvgroups:manage_cvgroup_templates", "admin/configuration/manage_cvgroup_templates.tpl");
  }

  function manage_cvgroup_templates_do(&$waf, $user, $title)
  {
    $group_id = (int) WA::request("group_id");
    $allowed = WA::request("allowed");
    $approval = WA::request("approval");
    $default_template = WA::request("default_template");

    // If nothing is selected, arrays don't exist
    if(empty($allowed)) $allowed = array();
    if(empty($approval)) $approval = array();

    // Nuke current permissions
    require_once("model/CVGroupTemplate.class.php");
    CVGroupTemplate::remove_by_group($group_id);

    // Make new adjustments
    foreach($allowed as $template_id)
    {
      $fields = array();
      $fields['group_id'] = $group_id;
      $fields['template_id'] = $template_id;
      if(in_array($template_id, $approval))
      {
        $fields['settings']='allow,requiresApproval';
      }
      else
      {
        $fields['settings']='allow';
      }
      CVGroupTemplate::insert($fields);
    }

    require_once("model/CVGroup.class.php");
    $cvgroup = CVGroup::load_by_id($group_id);
    $cvgroup->default_template = $default_template;
    $cvgroup->_update();
    goto("configuration", "manage_cvgroups");
  }



  // Help

  function manage_help(&$waf, $user, $title)
  {
    manage_objects($waf, $user, "Help", array(array("add","section=configuration&function=add_help")), array(array('edit', 'edit_help'), array('remove','remove_help')), "get_all", "", "admin:configuration:manage_help:manage_help");
  }

  function add_help(&$waf, &$user) 
  {
    $waf->assign("xinha_editor", true);
    add_object($waf, $user, "Help", array("add", "configuration", "add_help_do"), array(array("cancel","section=configuration&function=manage_help")), array(array("user_id",$user["user_id"])), "admin:configuration:manage_help:add_help");
  }

  function add_help_do(&$waf, &$user) 
  {
    add_object_do($waf, $user, "Help", "section=configuration&function=manage_help", "add_help");
  }

  function edit_help(&$waf, &$user) 
  {
    $waf->assign("xinha_editor", true);
    edit_object($waf, $user, "Help", array("confirm", "configuration", "edit_help_do"), array(array("cancel","section=configuration&function=manage_help")), array(array("user_id",$user["user_id"])), "admin:configuration:manage_help:edit_help", "admin/configuration/edit_help.tpl");
  }

  function edit_help_do(&$waf, &$user) 
  {
    edit_object_do($waf, $user, "Help", "section=configuration&function=manage_help", "edit_help");
  }

  function remove_help(&$waf, &$user) 
  {
    remove_object($waf, $user, "Help", array("remove", "configuration", "remove_help_do"), array(array("cancel","section=configuration&function=manage_help")), "", "admin:configuration:manage_help:remove_help");
  }

  function remove_help_do(&$waf, &$user) 
  {
    remove_object_do($waf, $user, "Help", "section=configuration&function=manage_help");
  }

  function import_data(&$waf, &$user) 
  {
    import_students(&$waf, &$user);
  }

  function import_students(&$waf, &$user) 
  {
    global $config_sensitive;

    if(!empty($config_sensitive['ws']['url'])) $waf->assign("ws_enabled", true);
    else $waf->assign("ws_enabled", false);

    // Normally, we are doing this for students on placement next year
    $year = get_academic_year()+1;

    require_once("model/Programme.class.php");
    $programmes = Programme::get_id_and_description();

    $waf->assign("year", $year);
    $waf->assign("programmes", $programmes);

    $waf->display("main.tpl", "admin:configuration:import_data:import_students", "admin/configuration/import_students_form.tpl");
  }

  function import_students_do(&$waf, &$user) 
  {
    $password     = $_REQUEST['password'];
    $programme_id = (int) $_REQUEST['programme_id'];
    $year         = (int) $_REQUEST['year'];
    $status       = $_REQUEST['status'];
    $test         = $_REQUEST['test'];
    $onlyyear     = (int) $_REQUEST['onlyyear'];

    require_once("model/StudentImport.class.php");
    StudentImport::import_programme_via_SRS($programme_id, $year, $status, $onlyyear, $password, $test);

    $waf->display("main.tpl", "admin:configuration:import_data:import_students_srs", "admin/configuration/import_students_srs.tpl");
  }

?>