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

    manage_objects($opus, $user, "Faculty", array(array("add","section=configuration&function=add_faculty")), array(array('schools', 'manage_schools'), array('edit', 'edit_faculty'), array('remove','remove_faculty')), "get_all", "", "admin:configuration:faculties:manage_faculties");
  }

  function add_faculty(&$opus, &$user) 
  {
    add_object($opus, $user, "Faculty", array("add", "configuration", "add_faculty_do"), array(array("cancel","section=configuration&function=manage_faculties")), array(array("user_id",$user["user_id"])), "admin:configuration:faculties:add_faculty");
  }

  function add_faculty_do(&$opus, &$user) 
  {
    add_object_do($opus, $user, "Faculty", "section=configuration&function=manage_faculties", "add_faculty");
  }

  function edit_faculty(&$opus, &$user) 
  {
    edit_object($opus, $user, "Faculty", array("confirm", "configuration", "edit_faculty_do"), array(array("cancel","section=configuration&function=manage_faculties")), array(array("user_id",$user["user_id"])), "admin:configuration:faculties:edit_faculty");
  }

  function edit_faculty_do(&$opus, &$user) 
  {
    edit_object_do($opus, $user, "Faculty", "section=configuration&function=manage_faculties", "edit_faculty");
  }

  function remove_faculty(&$opus, &$user) 
  {
    remove_object($opus, $user, "Faculty", array("remove", "configuration", "remove_faculty_do"), array(array("cancel","section=configuration&function=manage_faculties")), "", "admin:configuration:faculties:remove_faculty");
  }

  function remove_faculty_do(&$opus, &$user) 
  {
    remove_object_do($opus, $user, "Faculty", "section=configuration&function=manage_faculties");
  }

  // Schools

  function manage_schools(&$opus, $user, $title)
  {
    add_navigation_history($opus, "Schools");

    $faculty_id = (int) WA::request("id", true);

    manage_objects($opus, $user, "School", array(array("add","section=configuration&function=add_school")), array(array('programmes', 'list_programmes'), array('edit', 'edit_school'), array('remove','remove_school')), "get_all", "where faculty_id=$faculty_id", "admin:configuration:schools:manage_schools");
  }

  function add_school(&$opus, &$user) 
  {
    $faculty_id = (int) WA::request("id", true);

    add_object($opus, $user, "School", array("add", "configuration", "add_school_do"), array(array("cancel","section=configuration&function=manage_schools")), array(array("user_id",$user["user_id"]), array("faculty_id", $faculty_id)), "admin:configuration:schools:add_school");
  }

  function add_school_do(&$opus, &$user) 
  {
    add_object_do($opus, $user, "School", "section=configuration&function=manage_schools", "add_school");
  }

  function edit_school(&$opus, &$user) 
  {
    $faculty_id = (int) WA::request("id", true);

    edit_object($opus, $user, "School", array("confirm", "configuration", "edit_school_do"), array(array("cancel","section=configuration&function=manage_schools")), array(array("user_id",$user["user_id"]), array("faculty_id", $faculty_id)), "admin:configuration:schools:edit_school");
  }

  function edit_school_do(&$opus, &$user) 
  {
    edit_object_do($opus, $user, "School", "section=configuration&function=manage_schools", "edit_school");
  }

  function remove_school(&$opus, &$user) 
  {
    remove_object($opus, $user, "School", array("remove", "configuration", "remove_school_do"), array(array("cancel","section=configuration&function=manage_schools")), "", "admin:configuration:schools:remove_school");
  }

  function remove_school_do(&$opus, &$user) 
  {
    remove_object_do($opus, $user, "School", "section=configuration&function=manage_schools");
  }

  // Programmes

  function list_programmes(&$opus, $user, $title)
  {
    $school_id = (int) WA::request("id", true);

    manage_programmes(&$opus, $user, $title);
  }

  function manage_programmes(&$opus, $user, $title)
  {
    $school_id = (int) WA::request("id", true);

    manage_objects($opus, $user, "Programme", array(array("add","section=configuration&function=add_programme")), array(array('edit', 'edit_programme'), array('remove','remove_programme')), "get_all", "where school_id=$school_id", "admin:configuration:programmes:manage_programmes");
  }

  function add_programme(&$opus, &$user) 
  {
    $school_id = (int) WA::request("id", true);

    add_object($opus, $user, "Programme", array("add", "configuration", "add_programme_do"), array(array("cancel","section=configuration&function=manage_programmes")), array(array("user_id",$user["user_id"]), array("school_id", $school_id)), "admin:configuration:programmes:add_programme");
  }

  function add_programme_do(&$opus, &$user) 
  {
    add_object_do($opus, $user, "Programme", "section=configuration&function=manage_programmes", "add_programme");
  }

  function edit_programme(&$opus, &$user) 
  {
    $school_id = (int) WA::request("id", true);

    edit_object($opus, $user, "Programme", array("confirm", "configuration", "edit_programme_do"), array(array("cancel","section=configuration&function=manage_programmes")), array(array("user_id",$user["user_id"]), array("school_id", $school_id)), "admin:configuration:programmes:edit_programme");
  }

  function edit_programme_do(&$opus, &$user) 
  {
    edit_object_do($opus, $user, "Programme", "section=configuration&function=manage_programmes", "edit_programme");
  }

  function remove_programme(&$opus, &$user) 
  {
    remove_object($opus, $user, "Programme", array("remove", "configuration", "remove_programme_do"), array(array("cancel","section=configuration&function=manage_programmes")), "", "admin:configuration:programmes:remove_programme");
  }

  function remove_programme_do(&$opus, &$user) 
  {
    remove_object_do($opus, $user, "Programme", "section=configuration&function=manage_programmes");
  }

  // Help

  function manage_help(&$opus, $user, $title)
  {
    manage_objects($opus, $user, "Help", array(array("add","section=configuration&function=add_help")), array(array('edit', 'edit_help'), array('remove','remove_help')), "get_all", "", "admin:configuration:help:manage_help");
  }

  function add_help(&$opus, &$user) 
  {
    add_object($opus, $user, "Help", array("add", "configuration", "add_help_do"), array(array("cancel","section=configuration&function=manage_help")), array(array("user_id",$user["user_id"])), "admin:configuration:helps:add_help");
  }

  function add_help_do(&$opus, &$user) 
  {
    add_object_do($opus, $user, "Help", "section=configuration&function=manage_help", "add_help");
  }

  function edit_help(&$opus, &$user) 
  {
    edit_object($opus, $user, "Help", array("confirm", "configuration", "edit_help_do"), array(array("cancel","section=configuration&function=manage_help")), array(array("user_id",$user["user_id"])), "admin:configuration:helps:edit_help");
  }

  function edit_help_do(&$opus, &$user) 
  {
    edit_object_do($opus, $user, "Help", "section=configuration&function=manage_help", "edit_help");
  }

  function remove_help(&$opus, &$user) 
  {
    remove_object($opus, $user, "Help", array("remove", "configuration", "remove_help_do"), array(array("cancel","section=configuration&function=manage_help")), "", "admin:configuration:help:remove_help");
  }

  function remove_help_do(&$opus, &$user) 
  {
    remove_object_do($opus, $user, "Help", "section=configuration&function=manage_help");
  }


?>