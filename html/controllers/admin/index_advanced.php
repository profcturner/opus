<?php

/**
* Advanced Menu for Administrators
*
* @package OPUS
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
*/

  // Policies

  function manage_policies(&$waf, $user, $title)
  {
    set_navigation_history($waf, "Policies");

    manage_objects($waf, $user, "Policy", array(array("add","section=advanced&function=add_policy")), array(array('permissions','edit_policy_permissions'), array('edit', 'edit_policy'), array('remove','remove_policy')), "get_all", "", "admin:advanced:manage_policies:manage_policies");
  }

  function edit_policy_permissions(&$waf, $user, $title)
  {
    $id = (int) $_REQUEST["id"];
    require_once("model/Policy.class.php");

    $policy = Policy::load_by_id($id);

    add_navigation_history($waf, "Permissions for " . $policy->name);

    $student_possible = array('list', 'create', 'viewCV', 'editCV', 'viewStatus', 'editStatus', 'viewCompanies', 'editCompanies', 'viewAssessments', 'editAssessments');
    $student_output = $student_possible;
    $student_selected = explode(",", $policy->student);

    $staff_possible = array('list','create','edit','archive');
    $staff_output = $staff_possible;
    $staff_selected = explode(",", $policy->staff);

    $contact_possible = array('list','create','edit','delete', 'archive');
    $contact_output = $contact_possible;
    $contact_selected = explode(",", $policy->contact);

    $company_possible = array('create','edit');
    $company_output = $company_possible;
    $company_selected = explode(",", $policy->company);

    $vacancy_possible = array('create','edit','delete');
    $vacancy_output = $vacancy_possible;
    $vacancy_selected = explode(",", $policy->vacancy);

    $channel_possible = array('list','create','edit','delete','read','write');
    $channel_output = $channel_possible;
    $channel_selected = explode(",", $policy->channel);

    $help_possible = array('list','create','edit','delete');
    $help_output = $help_possible;
    $help_selected = explode(",", $policy->help);

    $cvgroup_possible = array('list','create','edit','delete');
    $cvgroup_output = $cvgroup_possible;
    $cvgroup_selected = explode(",", $policy->cvgroup);

    $assessmentgroup_possible = array('list','create','edit','delete');
    $assessmentgroup_output = $assessmentgroup_possible;
    $assessmentgroup_selected = explode(",", $policy->assessmentgroup);

    $automail_possible = array('list','create','edit','delete');
    $automail_output = $automail_possible;
    $automail_selected = explode(",", $policy->automail);

    $resource_possible = array('list','create','edit','delete');
    $resource_output = $resource_possible;
    $resource_selected = explode(",", $policy->resource);

    $faculty_possible = array('list','create','edit','archive');
    $faculty_output = $faculty_possible;
    $faculty_selected = explode(",", $policy->faculty);

    $school_possible = array('list','create','edit','archive');
    $school_output = $school_possible;
    $school_selected = explode(",", $policy->school);

    $programme_possible = array('list','create','edit','archive');
    $programme_output = $programme_possible;
    $programme_selected = explode(",", $policy->programme);

    $import_possible = array('students','photos');
    $import_output = $import_possible;
    $import_selected = explode(",", $policy->import);

    $status_possible = array('user');
    $status_output = $status_possible;
    $status_selected = explode(",", $policy->status);

    $log_possible = array('general','admin','cron','security','debug','panic','waf_debug','php_errors');
    $log_output = $log_possible;
    $log_selected = explode(",", $policy->log);

    // Assign all these for the template

    $waf->assign("student_possible", $student_possible);
    $waf->assign("student_output", $student_output);
    $waf->assign("student_selected", $student_selected);

    $waf->assign("staff_possible", $staff_possible);
    $waf->assign("staff_output", $staff_output);
    $waf->assign("staff_selected", $staff_selected);

    $waf->assign("contact_possible", $contact_possible);
    $waf->assign("contact_output", $contact_output);
    $waf->assign("contact_selected", $contact_selected);

    $waf->assign("company_possible", $company_possible);
    $waf->assign("company_output", $company_output);
    $waf->assign("company_selected", $company_selected);

    $waf->assign("vacancy_possible", $vacancy_possible);
    $waf->assign("vacancy_output", $vacancy_output);
    $waf->assign("vacancy_selected", $vacancy_selected);

    $waf->assign("help_possible", $help_possible);
    $waf->assign("help_output", $help_output);
    $waf->assign("help_selected", $help_selected);

    $waf->assign("cvgroup_possible", $cvgroup_possible);
    $waf->assign("cvgroup_output", $cvgroup_output);
    $waf->assign("cvgroup_selected", $cvgroup_selected);

    $waf->assign("assessmentgroup_possible", $assessmentgroup_possible);
    $waf->assign("assessmentgroup_output", $assessmentgroup_output);
    $waf->assign("assessmentgroup_selected", $assessmentgroup_selected);

    $waf->assign("channel_possible", $channel_possible);
    $waf->assign("channel_output", $channel_output);
    $waf->assign("channel_selected", $channel_selected);

    $waf->assign("automail_possible", $automail_possible);
    $waf->assign("automail_output", $automail_output);
    $waf->assign("automail_selected", $automail_selected);

    $waf->assign("resource_possible", $resource_possible);
    $waf->assign("resource_output", $resource_output);
    $waf->assign("resource_selected", $resource_selected);

    $waf->assign("faculty_possible", $faculty_possible);
    $waf->assign("faculty_output", $faculty_output);
    $waf->assign("faculty_selected", $faculty_selected);

    $waf->assign("school_possible", $school_possible);
    $waf->assign("school_output", $school_output);
    $waf->assign("school_selected", $school_selected);

    $waf->assign("programme_possible", $programme_possible);
    $waf->assign("programme_output", $programme_output);
    $waf->assign("programme_selected", $programme_selected);

    $waf->assign("import_possible", $import_possible);
    $waf->assign("import_output", $import_output);
    $waf->assign("import_selected", $import_selected);

    $waf->assign("status_possible", $status_possible);
    $waf->assign("status_output", $status_output);
    $waf->assign("status_selected", $status_selected);

    $waf->assign("log_possible", $log_possible);
    $waf->assign("log_output", $log_output);
    $waf->assign("log_selected", $log_selected);

    // Run the template

    $waf->assign("policy", $policy);
    $waf->display("main.tpl", "admin:advanced:manage_policies:edit_policy_permissions", "admin/advanced/edit_policy_permissions.tpl");
  }

  function edit_policy_permissions_do(&$waf, $user, $title)
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");
    require_once("model/Policy.class.php");

    $fields = array();
    $fields['id'] = (int) WA::request("id");

    $keys = array('student','staff','company','contact','vacancy','help','cvgroup','assessmentgroup','channel','resource','automail','faculty','school','programme','import','status','log');

    foreach($keys as $key)
    {
      if(!isset($_REQUEST[$key])) $values = array();
      else $values = $_REQUEST[$key];
      $fields[$key] = implode(",", $values);
    }

    Policy::update($fields);
    goto("advanced", "manage_policies");
  }

  function add_policy(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    add_object($waf, $user, "Policy", array("add", "advanced", "add_policy_do"), array(array("cancel","section=advanced&function=manage_policies")), array(array("user_id",$user["user_id"])), "admin:advanced:manage_policies:add_policy");
  }

  function add_policy_do(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    add_object_do($waf, $user, "Policy", "section=advanced&function=manage_policies", "add_policy");
  }

  function edit_policy(&$waf, &$user) 
  {
    edit_object($waf, $user, "Policy", array("confirm", "advanced", "edit_policy_do"), array(array("cancel","section=advanced&function=manage_policies")), array(array("user_id",$user["user_id"])), "admin:advanced:manage_policies:edit_policy");
  }

  function edit_policy_do(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    edit_object_do($waf, $user, "Policy", "section=advanced&function=manage_policies", "edit_policy");
  }

  function remove_policy(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    remove_object($waf, $user, "Policy", array("remove", "advanced", "remove_policy_do"), array(array("cancel","section=advanced&function=manage_policies")), "", "admin:advanced:manage_policies:remove_policy");
  }

  function remove_policy_do(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    remove_object_do($waf, $user, "Policy", "section=advanced&function=manage_policies");
  }

  // Languages

  function manage_languages(&$waf, $user, $title)
  {
    manage_objects($waf, $user, "Language", array(array("add","section=advanced&function=add_language")), array(array('edit', 'edit_language'), array('remove','remove_language')), "get_all", "", "admin:advanced:manage_languages:manage_languages");
  }

  function add_language(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    add_object($waf, $user, "Language", array("add", "advanced", "add_language_do"), array(array("cancel","section=advanced&function=manage_languages")), array(array("user_id",$user["user_id"])), "admin:advanced:manage_languages:add_language");
  }

  function add_language_do(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    add_object_do($waf, $user, "Language", "section=advanced&function=manage_languages", "add_language");
  }

  function edit_language(&$waf, &$user) 
  {
    edit_object($waf, $user, "Language", array("confirm", "advanced", "edit_language_do"), array(array("cancel","section=advanced&function=manage_languages")), array(array("user_id",$user["user_id"])), "admin:advanced:manage_languages:edit_language");
  }

  function edit_language_do(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    edit_object_do($waf, $user, "Language", "section=advanced&function=manage_languages", "edit_language");
  }

  function remove_language(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    remove_object($waf, $user, "Language", array("remove", "advanced", "remove_language_do"), array(array("cancel","section=advanced&function=manage_languages")), "", "admin:advanced:manage_languages:remove_language");
  }

  function remove_language_do(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    remove_object_do($waf, $user, "Language", "section=advanced&function=manage_languages");
  }

  // Activity types

  function manage_activitytypes(&$waf, $user, $title)
  {
    manage_objects($waf, $user, "Activitytype", array(array("add","section=advanced&function=add_activitytype")), array(array('edit', 'edit_activitytype'), array('remove','remove_activitytype')), "get_all", "", "admin:advanced:manage_activitytypes:manage_activitytypes");
  }

  function add_activitytype(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    add_object($waf, $user, "Activitytype", array("add", "advanced", "add_activitytype_do"), array(array("cancel","section=advanced&function=manage_activitytypes")), array(array("user_id",$user["user_id"])), "admin:advanced:manage_activitytypes:add_activitytype");
  }

  function add_activitytype_do(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    add_object_do($waf, $user, "Activitytype", "section=advanced&function=manage_activitytypes", "add_activitytype");
  }

  function edit_activitytype(&$waf, &$user) 
  {
    edit_object($waf, $user, "Activitytype", array("confirm", "advanced", "edit_activitytype_do"), array(array("cancel","section=advanced&function=manage_activitytypes")), array(array("user_id",$user["user_id"])), "admin:advanced:manage_activitytypes:edit_activitytype");
  }

  function edit_activitytype_do(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    edit_object_do($waf, $user, "Activitytype", "section=advanced&function=manage_activitytypes", "edit_activitytype");
  }

  function remove_activitytype(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    remove_object($waf, $user, "Activitytype", array("remove", "advanced", "remove_activitytype_do"), array(array("cancel","section=advanced&function=manage_activitytypes")), "", "admin:advanced:manage_activitytypes:remove_activitytype");
  }

  function remove_activitytype_do(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    remove_object_do($waf, $user, "Activitytype", "section=advanced&function=manage_activitytypes");
  }

  // Vacancy types

  function manage_vacancytypes(&$waf, $user, $title)
  {
    manage_objects($waf, $user, "Vacancytype", array(array("add","section=advanced&function=add_vacancytype")), array(array('edit', 'edit_vacancytype'), array('remove','remove_vacancytype')), "get_all", "", "admin:advanced:manage_vacancytypes:manage_vacancytypes");
  }

  function add_vacancytype(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    add_object($waf, $user, "Vacancytype", array("add", "advanced", "add_vacancytype_do"), array(array("cancel","section=advanced&function=manage_vacancytypes")), array(array("user_id",$user["user_id"])), "admin:advanced:manage_vacancytypes:add_vacancytype");
  }

  function add_vacancytype_do(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    add_object_do($waf, $user, "Vacancytype", "section=advanced&function=manage_vacancytypes", "add_vacancytype");
  }

  function edit_vacancytype(&$waf, &$user) 
  {
    edit_object($waf, $user, "Vacancytype", array("confirm", "advanced", "edit_vacancytype_do"), array(array("cancel","section=advanced&function=manage_vacancytypes")), array(array("user_id",$user["user_id"])), "admin:advanced:manage_vacancytypes:edit_vacancytype");
  }

  function edit_vacancytype_do(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    edit_object_do($waf, $user, "Vacancytype", "section=advanced&function=manage_vacancytypes", "edit_vacancytype");
  }

  function remove_vacancytype(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    remove_object($waf, $user, "Vacancytype", array("remove", "advanced", "remove_vacancytype_do"), array(array("cancel","section=advanced&function=manage_vacancytypes")), "", "admin:advanced:manage_vacancytypes:remove_vacancytype");
  }

  function remove_vacancytype_do(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    remove_object_do($waf, $user, "Vacancytype", "section=advanced&function=manage_vacancytypes");
  }

  // Channels

  function manage_channels(&$waf, $user, $title)
  {
    if(!Policy::check_default_policy("channel", "list")) $waf->halt("error:policy:permissions");

    set_navigation_history($waf, "Channels");

    manage_objects($waf, $user, "Channel", array(array("add","section=advanced&function=add_channel")), array(array('edit', 'edit_channel'), array('associations', 'manage_channelassociations'), array('remove','remove_channel')), "get_all", "", "admin:advanced:manage_channels:manage_channels");
  }

  function add_channel(&$waf, &$user) 
  {
    if(!Policy::check_default_policy("channel", "create")) $waf->halt("error:policy:permissions");

    add_object($waf, $user, "Channel", array("add", "advanced", "add_channel_do"), array(array("cancel","section=advanced&function=manage_channels")), array(array("user_id",$user["user_id"])), "admin:advanced:manage_channels:add_channel");
  }

  function add_channel_do(&$waf, &$user) 
  {
    if(!Policy::check_default_policy("channel", "create")) $waf->halt("error:policy:permissions");

    add_object_do($waf, $user, "Channel", "section=advanced&function=manage_channels", "add_channel");
  }

  function edit_channel(&$waf, &$user) 
  {
    if(!Policy::check_default_policy("channel", "edit")) $waf->halt("error:policy:permissions");

    $id = (int) WA::request("id");
    require_once("model/Channel.class.php");
    $channel = Channel::load_by_id($id);

    add_navigation_history($waf, $channel->name);

    edit_object($waf, $user, "Channel", array("confirm", "advanced", "edit_channel_do"), array(array("cancel","section=advanced&function=manage_channels")), array(array("user_id",$user["user_id"])), "admin:advanced:manage_channels:edit_channel");
  }

  function edit_channel_do(&$waf, &$user) 
  {
    if(!Policy::check_default_policy("channel", "edit")) $waf->halt("error:policy:permissions");

    edit_object_do($waf, $user, "Channel", "section=advanced&function=manage_channels", "edit_channel");
  }

  function remove_channel(&$waf, &$user) 
  {
    if(!Policy::check_default_policy("channel", "delete")) $waf->halt("error:policy:permissions");

    remove_object($waf, $user, "Channel", array("remove", "advanced", "remove_channel_do"), array(array("cancel","section=advanced&function=manage_channels")), "", "admin:advanced:manage_channels:remove_channel");
  }

  function remove_channel_do(&$waf, &$user) 
  {
    if(!Policy::check_default_policy("channel", "delete")) $waf->halt("error:policy:permissions");

    remove_object_do($waf, $user, "Channel", "section=advanced&function=manage_channels");
  }

  // Channel Associations

  function manage_channelassociations(&$waf, $user, $title)
  {
    if(!Policy::check_default_policy("channel", "edit")) $waf->halt("error:policy:permissions");

    $channel_id = (int) WA::request('id', true);
    require_once("model/Channel.class.php");
    $channel = Channel::load_by_id($channel_id);

    add_navigation_history($waf, $channel->name);
    require_once("model/ChannelAssociation.class.php");

    $action_links = array(
      array("add programme", "section=advanced&function=add_channelassociation_programme&channel_id=$channel_id"),
      array("add school", "section=advanced&function=add_channelassociation_school&channel_id=$channel_id"),
      array("add assessmentgroup", "section=advanced&function=add_channelassociation_assessmentgroup&channel_id=$channel_id"),
      array("add activity", "section=advanced&function=add_channelassociation_activity&channel_id=$channel_id")
    );

    $waf->assign("channel_id", $channel_id);
    $waf->assign("objects", ChannelAssociation::get_all_extended($channel_id));
    $waf->assign("action_links", $action_links);

    $waf->display("main.tpl", "admin:advanced:manage_channelassociations:manage_channelassociations", "admin/advanced/manage_channelassociations.tpl");

  }

  function add_channelassociation_programme(&$waf)
  {
    if(!Policy::check_default_policy("channel", "edit")) $waf->halt("error:policy:permissions");

    require_once("model/Programme.class.php");
    $channel_id = (int) WA::request('id', true);

    $waf->assign("channel_id", $channel_id);
    $waf->assign("permission_array", array('enable'=>'enable', 'disable'=>'disable'));
    $waf->assign("type_array", array('course'=>'programme'));
    $waf->assign("id_array", Programme::get_id_and_description());

    $waf->display("main.tpl", "admin:advanced:manage_channelassociations:add_channelassociation_programme", "admin/advanced/add_channelassociation.tpl");
  }

  function add_channelassociation_school(&$waf)
  {
    if(!Policy::check_default_policy("channel", "edit")) $waf->halt("error:policy:permissions");

    require_once("model/School.class.php");
    $channel_id = (int) WA::request('id', true);

    $waf->assign("channel_id", $channel_id);
    $waf->assign("permission_array", array('enable'=>'enable', 'disable'=>'disable'));
    $waf->assign("type_array", array('school'=>'school'));
    $waf->assign("id_array", School::get_id_and_field("name"));

    $waf->display("main.tpl", "admin:advanced:manage_channelassociations:add_channelassociation_school", "admin/advanced/add_channelassociation.tpl");
  }

  function add_channelassociation_activity(&$waf)
  {
    if(!Policy::check_default_policy("channel", "edit")) $waf->halt("error:policy:permissions");

    require_once("model/Activitytype.class.php");
    $channel_id = (int) WA::request('id', true);

    $waf->assign("channel_id", $channel_id);
    $waf->assign("permission_array", array('enable'=>'enable', 'disable'=>'disable'));
    $waf->assign("type_array", array('activity'=>'activity'));
    $waf->assign("id_array", ActivityType::get_id_and_field("name"));

    $waf->display("main.tpl", "admin:advanced:manage_channelassociations:add_channelassociation_activity", "admin/advanced/add_channelassociation.tpl");
  }

  function add_channelassociation_assessmentgroup(&$waf)
  {
    if(!Policy::check_default_policy("channel", "edit")) $waf->halt("error:policy:permissions");

    require_once("model/AssessmentGroup.class.php");
    $channel_id = (int) WA::request('id', true);

    $waf->assign("channel_id", $channel_id);
    $waf->assign("permission_array", array('enable'=>'enable', 'disable'=>'disable'));
    $waf->assign("type_array", array('assessmentgroup'=>'assessmentgroup'));
    $waf->assign("id_array", AssessmentGroup::get_id_and_field("name"));

    $waf->display("main.tpl", "admin:advanced:manage_channelassociations:add_channelassociation_assessmentgroup", "admin/advanced/add_channelassociation.tpl");
  }

  function add_channelassociation_student(&$waf)
  {
    $channel_id = (int) WA::request('id', true);

    if(!Policy::check_default_policy("channel", "edit")) $waf->halt("error:policy:permissions");
    if(!Policy::is_auth_for_student($student_user_id, "student", "editStatus"));

    require_once("model/Programme.class.php");
    $student_user_id = (int) WA::request('student_id');
    $dummy_id_array[$student_user_id] = User::get_name($student_user_id);

    $waf->assign("channel_id", $channel_id);
    $waf->assign("permission_array", array('enable'=>'enable', 'disable'=>'disable'));
    $waf->assign("type_array", array('user'=>'student'));
    $waf->assign("id_array", $dummy_id_array);

    $waf->display("main.tpl", "admin:advanced:manage_channelassociations:add_channelassociation_student", "admin/advanced/add_channelassociation.tpl");
  }

  function add_channelassociation_do(&$waf, &$user) 
  {
    if(!Policy::check_default_policy("channel", "edit")) $waf->halt("error:policy:permissions");

    add_object_do($waf, $user, "ChannelAssociation", "section=advanced&function=manage_channelassociations", "add_channelassociation");
  }

  function remove_channelassociation(&$waf, &$user) 
  {
    if(!Policy::check_default_policy("channel", "edit")) $waf->halt("error:policy:permissions");

    remove_object($waf, $user, "ChannelAssociation", array("remove", "advanced", "remove_channelassociation_do"), array(array("cancel","section=advanced&function=manage_channelassociations")), "", "admin:advanced:manage_channelassociations:remove_channelassociation");
  }

  function remove_channelassociation_do(&$waf, &$user) 
  {
    if(!Policy::check_default_policy("channel", "edit")) $waf->halt("error:policy:permissions");

    remove_object_do($waf, $user, "ChannelAssociation", "section=advanced&function=manage_channelassociations");
  }

  function move_channelassociation_up(&$waf, &$user)
  {
    if(!Policy::check_default_policy("channel", "edit")) $waf->halt("error:policy:permissions");

    // Get the assessment id and varorder
    $channel_id = (int) WA::request('channel_id');
    $id = (int) WA::request('id');

    require_once('model/ChannelAssociation.class.php');

    ChannelAssociation::move_up($channel_id, $id);
    goto("advanced", "manage_channelassociations&id=$channel_id");
  }

  function move_channelassociation_down(&$waf, &$user)
  {
    if(!Policy::check_default_policy("channel", "edit")) $waf->halt("error:policy:permissions");

    // Get the assessment id and varorder
    $channel_id = (int) WA::request('channel_id');
    $id = (int) WA::request('id');

    require_once('model/ChannelAssociation.class.php');

    ChannelAssociation::move_down($channel_id, $id);
    goto("advanced", "manage_channelassociations&id=$channel_id");
  }


  // Mimetypes

  function manage_mimetypes(&$waf, $user, $title)
  {
    manage_objects($waf, $user, "Mimetype", array(array("add","section=advanced&function=add_mimetype")), array(array('edit', 'edit_mimetype'), array('remove','remove_mimetype')), "get_all", "", "admin:advanced:manage_mimetypes:manage_mimetypes");
  }

  function add_mimetype(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    add_object($waf, $user, "Mimetype", array("add", "advanced", "add_mimetype_do"), array(array("cancel","section=advanced&function=manage_mimetypes")), array(array("user_id",$user["user_id"])), "admin:advanced:manage_mimetypes:add_mimetype");
  }

  function add_mimetype_do(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    add_object_do($waf, $user, "Mimetype", "section=advanced&function=manage_mimetypes", "add_mimetype");
  }

  function edit_mimetype(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    edit_object($waf, $user, "Mimetype", array("confirm", "advanced", "edit_mimetype_do"), array(array("cancel","section=advanced&function=manage_mimetypes")), array(array("user_id",$user["user_id"])), "admin:advanced:manage_mimetypes:edit_mimetype");
  }

  function edit_mimetype_do(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    edit_object_do($waf, $user, "Mimetype", "section=advanced&function=manage_mimetypes", "edit_mimetype");
  }

  function remove_mimetype(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    remove_object($waf, $user, "Mimetype", array("remove", "advanced", "remove_mimetype_do"), array(array("cancel","section=advanced&function=manage_mimetypes")), "", "admin:advanced:manage_mimetypes:remove_mimetype");
  }

  function remove_mimetype_do(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    remove_object_do($waf, $user, "Mimetype", "section=advanced&function=manage_mimetypes");
  }

  // Automail templates

  function manage_automail(&$waf, $user, $title)
  {
    if(!Policy::check_default_policy("automail", "list")) $waf->halt("error:policy:permissions");

    manage_objects($waf, $user, "Automail", array(array("add","section=advanced&function=add_automail")), array(array('edit', 'edit_automail'), array('remove','remove_automail')), "get_all", "", "admin:advanced:manage_automail:manage_automail");
  }

  function add_automail(&$waf, &$user) 
  {
    if(!Policy::check_default_policy("automail", "create")) $waf->halt("error:policy:permissions");

    add_object($waf, $user, "Automail", array("add", "advanced", "add_automail_do"), array(array("cancel","section=advanced&function=manage_automail")), array(array("user_id",$user["user_id"])), "admin:advanced:manage_automail:add_automail");
  }

  function add_automail_do(&$waf, &$user) 
  {
    if(!Policy::check_default_policy("automail", "create")) $waf->halt("error:policy:permissions");

    add_object_do($waf, $user, "Automail", "section=advanced&function=manage_automail", "add_automail");
  }

  function edit_automail(&$waf, &$user) 
  {
    if(!Policy::check_default_policy("automail", "edit")) $waf->halt("error:policy:permissions");

    edit_object($waf, $user, "Automail", array("confirm", "advanced", "edit_automail_do"), array(array("cancel","section=advanced&function=manage_automail")), array(array("user_id",$user["user_id"])), "admin:advanced:manage_automail:edit_automail");
  }

  function edit_automail_do(&$waf, &$user) 
  {
    if(!Policy::check_default_policy("automail", "edit")) $waf->halt("error:policy:permissions");

    edit_object_do($waf, $user, "Automail", "section=advanced&function=manage_automail", "edit_automail");
  }

  function remove_automail(&$waf, &$user) 
  {
    if(!Policy::check_default_policy("automail", "delete")) $waf->halt("error:policy:permissions");

    remove_object($waf, $user, "Automail", array("remove", "advanced", "remove_automail_do"), array(array("cancel","section=advanced&function=manage_automail")), "", "admin:advanced:manage_automail:remove_automail");
  }

  function remove_automail_do(&$waf, &$user) 
  {
    if(!Policy::check_default_policy("automail", "delete")) $waf->halt("error:policy:permissions");

    remove_object_do($waf, $user, "Automail", "section=advanced&function=manage_automail");
  }

  // Assessments

  function manage_assessments(&$waf, $user, $title)
  {
    set_navigation_history($waf, "Assessments");

    manage_objects($waf, $user, "Assessment", array(array("add","section=advanced&function=add_assessment")), array(array('edit', 'edit_assessment'), array('structure', 'manage_assessmentstructure'), array('remove','remove_assessment')), "get_all", "", "admin:advanced:manage_assessments:manage_assessments");
  }

  function add_assessment(&$waf, &$user) 
  {
    add_object($waf, $user, "Assessment", array("add", "advanced", "add_assessment_do"), array(array("cancel","section=advanced&function=manage_assessments")), array(array("user_id",$user["user_id"])), "admin:advanced:manage_assessments:add_assessment");
  }

  function add_assessment_do(&$waf, &$user) 
  {
    add_object_do($waf, $user, "Assessment", "section=advanced&function=manage_assessments", "add_assessment");
  }

  function edit_assessment(&$waf, &$user) 
  {
    edit_object($waf, $user, "Assessment", array("confirm", "advanced", "edit_assessment_do"), array(array("cancel","section=advanced&function=manage_assessments")), array(array("user_id",$user["user_id"])), "admin:advanced:manage_assessments:edit_assessment");
  }

  function edit_assessment_do(&$waf, &$user) 
  {
    edit_object_do($waf, $user, "Assessment", "section=advanced&function=manage_assessments", "edit_assessment");
  }

  function remove_assessment(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    remove_object($waf, $user, "Assessment", array("remove", "advanced", "remove_assessment_do"), array(array("cancel","section=advanced&function=manage_assessments")), "", "admin:advanced:manage_assessments:remove_assessment");
  }

  function remove_assessment_do(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    remove_object_do($waf, $user, "Assessment", "section=advanced&function=manage_assessments");
  }

  // Assessment structure

  function manage_assessmentstructure(&$waf, $user, $title)
  {
    $assessment_id = (int) WA::request('id', true);
    $page = (int) WA::request('page');

    require_once('model/Assessment.class.php');
    $assessment = Assessment::load_by_id($assessment_id);
    add_navigation_history($waf, $assessment->description);

    manage_objects($waf, $user, "AssessmentStructure", array(array("add","section=advanced&function=add_assessmentstructure")), array(array('edit', 'edit_assessmentstructure'), array('up', "move_assessmentstructure_up&assessment_id=$assessment_id"), array('down', "move_assessmentstructure_down&assessment_id=$assessment_id"), array('remove','remove_assessmentstructure')), "get_all", array("where assessment_id=$assessment_id", "order by varorder", $page), "admin:advanced:manage_assessments:manage_assessmentstructures");
  }

  function move_assessmentstructure_up(&$waf, &$user)
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    // Get the assessment id and varorder
    $assessment_id = (int) WA::request('assessment_id');
    $id = (int) WA::request('id');

    require_once('model/AssessmentStructure.class.php');

    AssessmentStructure::move_up($assessment_id, $id);
    goto("advanced", "manage_assessmentstructure&id=$assessment_id");
  }

  function move_assessmentstructure_down(&$waf, &$user)
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    // Get the assessment id and varorder
    $assessment_id = (int) WA::request('assessment_id');
    $id = (int) WA::request('id');

    require_once('model/AssessmentStructure.class.php');

    AssessmentStructure::move_down($assessment_id, $id);
    goto("advanced", "manage_assessmentstructure&id=$assessment_id");
  }


  function add_assessmentstructure(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    add_navigation_history($waf, "Add Item");

    add_object($waf, $user, "AssessmentStructure", array("add", "advanced", "add_assessmentstructure_do"), array(array("cancel","section=advanced&function=manage_assessmentstructures")), array(array("user_id",$user["user_id"])), "admin:advanced:manage_assessments:add_assessmentstructure");
  }

  function add_assessmentstructure_do(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    add_object_do($waf, $user, "AssessmentStructure", "section=advanced&function=manage_assessmentstructures", "add_assessmentstructure");
  }

  function edit_assessmentstructure(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    $id = (int) WA::request('id', true);

    require_once('model/AssessmentStructure.class.php');
    $assessmentstructure = AssessmentStructure::load_by_id($id);
    add_navigation_history($waf, $assessmentstructure->name);

    edit_object($waf, $user, "AssessmentStructure", array("confirm", "advanced", "edit_assessmentstructure_do"), array(array("cancel","section=advanced&function=manage_assessmentstructures")), array(array("user_id",$user["user_id"])), "admin:advanced:manage_assessments:edit_assessmentstructure");
  }

  function edit_assessmentstructure_do(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    edit_object_do($waf, $user, "AssessmentStructure", "section=advanced&function=manage_assessmentstructures", "edit_assessmentstructure");
  }

  function remove_assessmentstructure(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    add_navigation_history($waf, "Remove Item");

    remove_object($waf, $user, "AssessmentStructure", array("remove", "advanced", "remove_assessmentstructure_do"), array(array("cancel","section=advanced&function=manage_assessmentstructures")), "", "admin:advanced:manage_assessments:remove_assessmentstructure");
  }

  function remove_assessmentstructure_do(&$waf, &$user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    remove_object_do($waf, $user, "AssessmentStructure", "section=advanced&function=manage_assessmentstructures");
  }

?>