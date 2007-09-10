<?php

  // Resources

  function manage_resources(&$opus, $user, $title)
  {
    manage_objects($opus, $user, "Resource", array(array("add","section=configuration&function=add_resource")), array(array('edit', 'edit_resource'), array('remove','remove_resource')), "get_all", "", "admin:configuration:resources:manage_resources");
  }

  function add_resource(&$opus, &$user) 
  {
    add_object($opus, $user, "Resource", array("add", "configuration", "add_resource_do"), array(array("cancel","section=configuration&function=manage_resources")), array(array("user_id",$user["user_id"])), "admin:configuration:resources:add_resource");
  }

  function add_resource_do(&$opus, &$user) 
  {
    add_object_do($opus, $user, "Resource", "section=configuration&function=manage_resources", "add_resource");
  }

  function edit_resource(&$opus, &$user) 
  {
    edit_object($opus, $user, "Resource", array("confirm", "configuration", "edit_resource_do"), array(array("cancel","section=configuration&function=manage_resources")), array(array("user_id",$user["user_id"])), "admin:configuration:resources:edit_resource");
  }

  function edit_resource_do(&$opus, &$user) 
  {
    edit_object_do($opus, $user, "Resource", "section=configuration&function=manage_resources", "edit_resource");
  }

  function remove_resource(&$opus, &$user) 
  {
    remove_object($opus, $user, "Resource", array("remove", "configuration", "remove_resource_do"), array(array("cancel","section=configuration&function=manage_resources")), "", "admin:configuration:resources:remove_resource");
  }

  function remove_resource_do(&$opus, &$user) 
  {
    remove_object_do($opus, $user, "Resource", "section=configuration&function=manage_resources");
  }


  // Organisation

  function organisation_details(&$opus, &$user, $title) 
  {
    manage_faculties(&$opus, &$user, $title);
  }

  // Faculties

  function manage_faculties(&$opus, $user, $title)
  {
    set_navigation_history($opus, "Faculties");

    manage_objects($opus, $user, "Faculty", array(array("add","section=configuration&function=add_faculty")), array(array('admins', 'manage_facultyadmins'), array('schools', 'manage_schools'), array('edit', 'edit_faculty'), array('remove','remove_faculty')), "get_all", "", "admin:configuration:organisation_details:manage_faculties");
  }

  function add_faculty(&$opus, &$user) 
  {
    add_object($opus, $user, "Faculty", array("add", "configuration", "add_faculty_do"), array(array("cancel","section=configuration&function=manage_faculties")), array(array("user_id",$user["user_id"])), "admin:configuration:organisation_details:add_faculty");
  }

  function add_faculty_do(&$opus, &$user) 
  {
    add_object_do($opus, $user, "Faculty", "section=configuration&function=manage_faculties", "add_faculty");
  }

  function edit_faculty(&$opus, &$user) 
  {
    edit_object($opus, $user, "Faculty", array("confirm", "configuration", "edit_faculty_do"), array(array("cancel","section=configuration&function=manage_faculties")), array(array("user_id",$user["user_id"])), "admin:configuration:organisation_details:edit_faculty");
  }

  function edit_faculty_do(&$opus, &$user) 
  {
    edit_object_do($opus, $user, "Faculty", "section=configuration&function=manage_faculties", "edit_faculty");
  }

  function remove_faculty(&$opus, &$user) 
  {
    remove_object($opus, $user, "Faculty", array("remove", "configuration", "remove_faculty_do"), array(array("cancel","section=configuration&function=manage_faculties")), "", "admin:configuration:organisation_details:remove_faculty");
  }

  function remove_faculty_do(&$opus, &$user) 
  {
    remove_object_do($opus, $user, "Faculty", "section=configuration&function=manage_faculties");
  }

  // Schools

  function manage_schools(&$opus, $user, $title)
  {

    $faculty_id = (int) WA::request("id", true);

    require_once("model/Faculty.class.php");
    $faculty = Faculty::load_by_id($faculty_id);

    add_navigation_history($opus, $faculty->name);

    manage_objects($opus, $user, "School", array(array("add","section=configuration&function=add_school")), array(array('admins', 'manage_schooladmins'), array('programmes', 'manage_programmes'), array('edit', 'edit_school'), array('remove','remove_school')), "get_all", "where faculty_id=$faculty_id", "admin:configuration:organisation_details:manage_schools");
  }

  function add_school(&$opus, &$user) 
  {
    $faculty_id = (int) WA::request("id", true);

    add_object($opus, $user, "School", array("add", "configuration", "add_school_do"), array(array("cancel","section=configuration&function=manage_schools")), array(array("user_id",$user["user_id"]), array("faculty_id", $faculty_id)), "admin:configuration:organisation_details:add_school");
  }

  function add_school_do(&$opus, &$user) 
  {
    add_object_do($opus, $user, "School", "section=configuration&function=manage_schools", "add_school");
  }

  function edit_school(&$opus, &$user) 
  {
    $faculty_id = (int) WA::request("id", true);

    edit_object($opus, $user, "School", array("confirm", "configuration", "edit_school_do"), array(array("cancel","section=configuration&function=manage_schools")), array(array("user_id",$user["user_id"]), array("faculty_id", $faculty_id)), "admin:configuration:organisation_details:edit_school");
  }

  function edit_school_do(&$opus, &$user) 
  {
    edit_object_do($opus, $user, "School", "section=configuration&function=manage_schools", "edit_school");
  }

  function remove_school(&$opus, &$user) 
  {
    remove_object($opus, $user, "School", array("remove", "configuration", "remove_school_do"), array(array("cancel","section=configuration&function=manage_schools")), "", "admin:configuration:organisation_details:remove_school");
  }

  function remove_school_do(&$opus, &$user) 
  {
    remove_object_do($opus, $user, "School", "section=configuration&function=manage_schools");
  }

  // Programmes

  function manage_programmes(&$opus, $user, $title)
  {
    add_navigation_history($opus, "Programmes");

    $school_id = (int) WA::request("id", true);

    require_once("model/School.class.php");
    $school = School::load_by_id($school_id);

    add_navigation_history($opus, $school->name);


    manage_objects($opus, $user, "Programme", array(array("add","section=configuration&function=add_programme")), array(array('admins', 'manage_programmeadmins'), array('groups', 'manage_programmegroups'), array('edit', 'edit_programme'), array('remove','remove_programme')), "get_all", "where school_id=$school_id", "admin:configuration:organisation_details:manage_programmes");
  }

  function add_programme(&$opus, &$user) 
  {
    $school_id = (int) WA::request("id", true);

    add_navigation_history($opus, "Add Programme");

    add_object($opus, $user, "Programme", array("add", "configuration", "add_programme_do"), array(array("cancel","section=configuration&function=manage_programmes")), array(array("user_id",$user["user_id"]), array("school_id", $school_id)), "admin:configuration:organisation_details:add_programme");
  }

  function add_programme_do(&$opus, &$user) 
  {
    add_object_do($opus, $user, "Programme", "section=configuration&function=manage_programmes", "add_programme");
  }

  function edit_programme(&$opus, &$user) 
  {
    $school_id = (int) WA::request("id", true);

    edit_object($opus, $user, "Programme", array("confirm", "configuration", "edit_programme_do"), array(array("cancel","section=configuration&function=manage_programmes")), array(array("user_id",$user["user_id"]), array("school_id", $school_id)), "admin:configuration:organisation_details:edit_programme");
  }

  function edit_programme_do(&$opus, &$user) 
  {
    edit_object_do($opus, $user, "Programme", "section=configuration&function=manage_programmes", "edit_programme");
  }

  function remove_programme(&$opus, &$user) 
  {
    remove_object($opus, $user, "Programme", array("remove", "configuration", "remove_programme_do"), array(array("cancel","section=configuration&function=manage_programmes")), "", "admin:configuration:organisation_details:remove_programme");
  }

  function remove_programme_do(&$opus, &$user) 
  {
    remove_object_do($opus, $user, "Programme", "section=configuration&function=manage_programmes");
  }

  // Assessmentgroups

  function manage_assessmentgroups(&$opus, $user, $title)
  {
    manage_objects($opus, $user, "Assessmentgroup", array(array("add","section=configuration&function=add_assessmentgroup")), array(array('regime', 'manage_assessmentregimes'), array('edit', 'edit_assessmentgroup'), array('remove','remove_assessmentgroup')), "get_all", "", "admin:configuration:manage_assessmentgroups:manage_assessmentgroups");
  }

  function add_assessmentgroup(&$opus, &$user) 
  {
    add_object($opus, $user, "Assessmentgroup", array("add", "configuration", "add_assessmentgroup_do"), array(array("cancel","section=configuration&function=manage_assessmentgroups")), array(array("user_id",$user["user_id"])), "admin:configuration:manage_assessmentgroups:add_assessmentgroup");
  }

  function add_assessmentgroup_do(&$opus, &$user) 
  {
    add_object_do($opus, $user, "Assessmentgroup", "section=configuration&function=manage_assessmentgroups", "add_assessmentgroup");
  }

  function edit_assessmentgroup(&$opus, &$user) 
  {
    edit_object($opus, $user, "Assessmentgroup", array("confirm", "configuration", "edit_assessmentgroup_do"), array(array("cancel","section=configuration&function=manage_assessmentgroups")), array(array("user_id",$user["user_id"])), "admin:configuration:manage_assessmentgroups:edit_assessmentgroup");
  }

  function edit_assessmentgroup_do(&$opus, &$user) 
  {
    edit_object_do($opus, $user, "Assessmentgroup", "section=configuration&function=manage_assessmentgroups", "edit_assessmentgroup");
  }

  function remove_assessmentgroup(&$opus, &$user) 
  {
    remove_object($opus, $user, "Assessmentgroup", array("remove", "configuration", "remove_assessmentgroup_do"), array(array("cancel","section=configuration&function=manage_assessmentgroups")), "", "admin:configuration:manage_assessmentgroups:remove_assessmentgroup");
  }

  function remove_assessmentgroup_do(&$opus, &$user) 
  {
    remove_object_do($opus, $user, "Assessmentgroup", "section=configuration&function=manage_assessmentgroups");
  }

  // Assessmentregimes

  function manage_assessmentregimes(&$opus, $user, $title)
  {
    $group_id = (int) WA::request('id', true);

    manage_objects($opus, $user, "Assessmentregime", array(array("add","section=configuration&function=add_assessmentregime")), array(array('edit', 'edit_assessmentregime'), array('remove','remove_assessmentregime')), "get_all", "where group_id=$group_id", "admin:configuration:manage_assessmentgroups:manage_assessmentregimes");
  }

  function add_assessmentregime(&$opus, &$user) 
  {
    $group_id = (int) WA::request('id', true);

    add_object($opus, $user, "Assessmentregime", array("add", "configuration", "add_assessmentregime_do"), array(array("cancel","section=configuration&function=manage_assessmentregimes")), array(array("user_id",$user["user_id"]), array("group_id", $group_id)), "admin:configuration:manage_assessmentgroups:add_assessmentregime");
  }

  function add_assessmentregime_do(&$opus, &$user) 
  {
    add_object_do($opus, $user, "Assessmentregime", "section=configuration&function=manage_assessmentregimes", "add_assessmentregime");
  }

  function edit_assessmentregime(&$opus, &$user) 
  {
    $group_id = (int) WA::request('id', true);

    edit_object($opus, $user, "Assessmentregime", array("confirm", "configuration", "edit_assessmentregime_do"), array(array("cancel","section=configuration&function=manage_assessmentregimes")), array(array("user_id",$user["user_id"]), array("group_id", $group_id)), "admin:configuration:manage_assessmentgroups:edit_assessmentregime");
  }

  function edit_assessmentregime_do(&$opus, &$user) 
  {
    edit_object_do($opus, $user, "Assessmentregime", "section=configuration&function=manage_assessmentregimes", "edit_assessmentregime");
  }

  function remove_assessmentregime(&$opus, &$user) 
  {
    $group_id = (int) WA::request('id', true);

    remove_object($opus, $user, "Assessmentregime", array("remove", "configuration", "remove_assessmentregime_do"), array(array("cancel","section=configuration&function=manage_assessmentregimes")), "", "admin:configuration:manage_assessmentgroups:remove_assessmentregime");
  }

  function remove_assessmentregime_do(&$opus, &$user) 
  {
    remove_object_do($opus, $user, "Assessmentregime", "section=configuration&function=manage_assessmentregimes");
  }

  // CVgroups

  function manage_cvgroups(&$opus, $user, $title)
  {
    manage_objects($opus, $user, "CVgroup", array(array("add","section=configuration&function=add_cvgroup")), array(array('edit', 'edit_cvgroup'), array('remove','remove_cvgroup')), "get_all", "", "admin:configuration:manage_cvgroups:manage_cvgroups");
  }

  function add_cvgroup(&$opus, &$user) 
  {
    add_object($opus, $user, "CVgroup", array("add", "configuration", "add_cvgroup_do"), array(array("cancel","section=configuration&function=manage_cvgroups")), array(array("user_id",$user["user_id"])), "admin:configuration:manage_cvgroups:add_cvgroup");
  }

  function add_cvgroup_do(&$opus, &$user) 
  {
    add_object_do($opus, $user, "CVgroup", "section=configuration&function=manage_cvgroups", "add_cvgroup");
  }

  function edit_cvgroup(&$opus, &$user) 
  {
    edit_object($opus, $user, "CVgroup", array("confirm", "configuration", "edit_cvgroup_do"), array(array("cancel","section=configuration&function=manage_cvgroups")), array(array("user_id",$user["user_id"])), "admin:configuration:manage_cvgroups:edit_cvgroup");
  }

  function edit_cvgroup_do(&$opus, &$user) 
  {
    edit_object_do($opus, $user, "CVgroup", "section=configuration&function=manage_cvgroups", "edit_cvgroup");
  }

  function remove_cvgroup(&$opus, &$user) 
  {
    remove_object($opus, $user, "CVgroup", array("remove", "configuration", "remove_cvgroup_do"), array(array("cancel","section=configuration&function=manage_cvgroups")), "", "admin:configuration:manage_cvgroups:remove_cvgroup");
  }

  function remove_cvgroup_do(&$opus, &$user) 
  {
    remove_object_do($opus, $user, "CVgroup", "section=configuration&function=manage_cvgroups");
  }



  // Help

  function manage_help(&$opus, $user, $title)
  {
    manage_objects($opus, $user, "Help", array(array("add","section=configuration&function=add_help")), array(array('edit', 'edit_help'), array('remove','remove_help')), "get_all", "", "admin:configuration:manage_help:manage_help");
  }

  function add_help(&$opus, &$user) 
  {
    add_object($opus, $user, "Help", array("add", "configuration", "add_help_do"), array(array("cancel","section=configuration&function=manage_help")), array(array("user_id",$user["user_id"])), "admin:configuration:manage_help:add_help");
  }

  function add_help_do(&$opus, &$user) 
  {
    add_object_do($opus, $user, "Help", "section=configuration&function=manage_help", "add_help");
  }

  function edit_help(&$opus, &$user) 
  {
    edit_object($opus, $user, "Help", array("confirm", "configuration", "edit_help_do"), array(array("cancel","section=configuration&function=manage_help")), array(array("user_id",$user["user_id"])), "admin:configuration:manage_help:edit_help", "admin/configuration/edit_help.tpl");
  }

  function edit_help_do(&$opus, &$user) 
  {
    edit_object_do($opus, $user, "Help", "section=configuration&function=manage_help", "edit_help");
  }

  function remove_help(&$opus, &$user) 
  {
    remove_object($opus, $user, "Help", array("remove", "configuration", "remove_help_do"), array(array("cancel","section=configuration&function=manage_help")), "", "admin:configuration:manage_help:remove_help");
  }

  function remove_help_do(&$opus, &$user) 
  {
    remove_object_do($opus, $user, "Help", "section=configuration&function=manage_help");
  }


?>