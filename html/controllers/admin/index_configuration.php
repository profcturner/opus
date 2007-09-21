<?php

  // Resources

  function manage_resources(&$waf, $user, $title)
  {
    manage_objects($waf, $user, "Resource", array(array("add","section=configuration&function=add_resource")), array(array('edit', 'edit_resource'), array('remove','remove_resource')), "get_all", "", "admin:configuration:resources:manage_resources");
  }

  function add_resource(&$waf, &$user) 
  {
    add_object($waf, $user, "Resource", array("add", "configuration", "add_resource_do"), array(array("cancel","section=configuration&function=manage_resources")), array(array("user_id",$user["user_id"])), "admin:configuration:resources:add_resource");
  }

  function add_resource_do(&$waf, &$user) 
  {
    add_object_do($waf, $user, "Resource", "section=configuration&function=manage_resources", "add_resource");
  }

  function edit_resource(&$waf, &$user) 
  {
    edit_object($waf, $user, "Resource", array("confirm", "configuration", "edit_resource_do"), array(array("cancel","section=configuration&function=manage_resources")), array(array("user_id",$user["user_id"])), "admin:configuration:resources:edit_resource");
  }

  function edit_resource_do(&$waf, &$user) 
  {
    edit_object_do($waf, $user, "Resource", "section=configuration&function=manage_resources", "edit_resource");
  }

  function remove_resource(&$waf, &$user) 
  {
    remove_object($waf, $user, "Resource", array("remove", "configuration", "remove_resource_do"), array(array("cancel","section=configuration&function=manage_resources")), "", "admin:configuration:resources:remove_resource");
  }

  function remove_resource_do(&$waf, &$user) 
  {
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

    manage_objects($waf, $user, "School", array(array("add","section=configuration&function=add_school")), array(array('admins', 'manage_school_admins'), array('programmes', 'manage_programmes'), array('edit', 'edit_school'), array('remove','remove_school')), "get_all", "where faculty_id=$faculty_id", "admin:configuration:organisation_details:manage_schools");
  }

  function add_school(&$waf, &$user) 
  {
    $faculty_id = (int) WA::request("id", true);

    add_object($waf, $user, "School", array("add", "configuration", "add_school_do"), array(array("cancel","section=configuration&function=manage_schools")), array(array("user_id",$user["user_id"]), array("faculty_id", $faculty_id)), "admin:configuration:organisation_details:add_school");
  }

  function add_school_do(&$waf, &$user) 
  {
    add_object_do($waf, $user, "School", "section=configuration&function=manage_schools", "add_school");
  }

  function edit_school(&$waf, &$user) 
  {
    $faculty_id = (int) WA::request("id", true);

    edit_object($waf, $user, "School", array("confirm", "configuration", "edit_school_do"), array(array("cancel","section=configuration&function=manage_schools")), array(array("user_id",$user["user_id"]), array("faculty_id", $faculty_id)), "admin:configuration:organisation_details:edit_school");
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


  function manage_school_admins(&$waf, $user, $title)
  {
    $school_id = (int) WA::request("id", true);

    require_once("model/Admin.class.php");
    $objects = Admin::get_all_by_school($school_id);

    $headings = array(
      'real_name'=>array('type'=>'text','size'=>30, 'header'=>true, title=>'Name'),
      'position'=>array('type'=>'list','size'=>30, 'header'=>true, title=>'Position'),
      'email'=>array('type'=>'email','size'=>40, 'header'=>true),
      'voice'=>array('type'=>'text','size'=>40, 'header'=>true, title=>'Phone')
    );
    $action_links = array(array('add', "section=configuration&function=add_school_admin&school_id=$school_id"));
    $actions = array(array('edit', 'edit_school_admin'), array('remove', 'remove_school_admin'));

    $waf->assign("actions", $actions);
    $waf->assign("action_links", $action_links);
    $waf->assign("headings", $headings);
    $waf->assign("objects", $objects);

    //add_navigation_history($waf, $faculty->name);
    $waf->display("main.tpl", "admin:configuration:organisation_details:manage_school_admins", "list.tpl");
  }

  function add_school_admin(&$waf)
  {
    require_once("model/Admin.class.php");
  }

  // Programmes

  function manage_programmes(&$waf, $user, $title)
  {
    add_navigation_history($waf, "Programmes");

    $school_id = (int) WA::request("id", true);

    require_once("model/School.class.php");
    $school = School::load_by_id($school_id);

    add_navigation_history($waf, $school->name);


    manage_objects($waf, $user, "Programme", array(array("add","section=configuration&function=add_programme")), array(array('admins', 'manage_programmeadmins'), array('groups', 'manage_programmegroups'), array('edit', 'edit_programme'), array('remove','remove_programme')), "get_all", "where school_id=$school_id", "admin:configuration:organisation_details:manage_programmes");
  }

  function add_programme(&$waf, &$user) 
  {
    $school_id = (int) WA::request("id", true);

    add_navigation_history($waf, "Add Programme");

    add_object($waf, $user, "Programme", array("add", "configuration", "add_programme_do"), array(array("cancel","section=configuration&function=manage_programmes")), array(array("user_id",$user["user_id"]), array("school_id", $school_id)), "admin:configuration:organisation_details:add_programme");
  }

  function add_programme_do(&$waf, &$user) 
  {
    add_object_do($waf, $user, "Programme", "section=configuration&function=manage_programmes", "add_programme");
  }

  function edit_programme(&$waf, &$user) 
  {
    $school_id = (int) WA::request("id", true);

    edit_object($waf, $user, "Programme", array("confirm", "configuration", "edit_programme_do"), array(array("cancel","section=configuration&function=manage_programmes")), array(array("user_id",$user["user_id"]), array("school_id", $school_id)), "admin:configuration:organisation_details:edit_programme");
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

  // CVgroups

  function manage_cvgroups(&$waf, $user, $title)
  {
    manage_objects($waf, $user, "CVgroup", array(array("add","section=configuration&function=add_cvgroup")), array(array('edit', 'edit_cvgroup'), array('remove','remove_cvgroup')), "get_all", "", "admin:configuration:manage_cvgroups:manage_cvgroups");
  }

  function add_cvgroup(&$waf, &$user) 
  {
    add_object($waf, $user, "CVgroup", array("add", "configuration", "add_cvgroup_do"), array(array("cancel","section=configuration&function=manage_cvgroups")), array(array("user_id",$user["user_id"])), "admin:configuration:manage_cvgroups:add_cvgroup");
  }

  function add_cvgroup_do(&$waf, &$user) 
  {
    add_object_do($waf, $user, "CVgroup", "section=configuration&function=manage_cvgroups", "add_cvgroup");
  }

  function edit_cvgroup(&$waf, &$user) 
  {
    edit_object($waf, $user, "CVgroup", array("confirm", "configuration", "edit_cvgroup_do"), array(array("cancel","section=configuration&function=manage_cvgroups")), array(array("user_id",$user["user_id"])), "admin:configuration:manage_cvgroups:edit_cvgroup");
  }

  function edit_cvgroup_do(&$waf, &$user) 
  {
    edit_object_do($waf, $user, "CVgroup", "section=configuration&function=manage_cvgroups", "edit_cvgroup");
  }

  function remove_cvgroup(&$waf, &$user) 
  {
    remove_object($waf, $user, "CVgroup", array("remove", "configuration", "remove_cvgroup_do"), array(array("cancel","section=configuration&function=manage_cvgroups")), "", "admin:configuration:manage_cvgroups:remove_cvgroup");
  }

  function remove_cvgroup_do(&$waf, &$user) 
  {
    remove_object_do($waf, $user, "CVgroup", "section=configuration&function=manage_cvgroups");
  }



  // Help

  function manage_help(&$waf, $user, $title)
  {
    manage_objects($waf, $user, "Help", array(array("add","section=configuration&function=add_help")), array(array('edit', 'edit_help'), array('remove','remove_help')), "get_all", "", "admin:configuration:manage_help:manage_help");
  }

  function add_help(&$waf, &$user) 
  {
    add_object($waf, $user, "Help", array("add", "configuration", "add_help_do"), array(array("cancel","section=configuration&function=manage_help")), array(array("user_id",$user["user_id"])), "admin:configuration:manage_help:add_help");
  }

  function add_help_do(&$waf, &$user) 
  {
    add_object_do($waf, $user, "Help", "section=configuration&function=manage_help", "add_help");
  }

  function edit_help(&$waf, &$user) 
  {
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