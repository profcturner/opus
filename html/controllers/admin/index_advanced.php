<?php

  // Policies

  function manage_policies(&$opus, $user, $title)
  {
    set_navigation_history($opus, "Policies");

    manage_objects($opus, $user, "Policy", array(array("add","section=advanced&function=add_policy")), array(array('permissions','edit_policy_permissions'), array('edit', 'edit_policy'), array('remove','remove_policy')), "get_all", "", "admin:advanced:manage_policies:manage_policies");
  }

  function edit_policy_permissions(&$opus, $user, $title)
  {
    $id = (int) $_REQUEST["id"];
    require_once("model/Policy.class.php");

    $policy = Policy::load_by_id($id);

    add_navigation_history($opus, "Permissions for " . $policy->name);

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

    $log_possible = array('general','security','debug','panic','waf_debug');
    $log_output = $log_possible;
    $log_selected = explode(",", $policy->log);

    // Assign all these for the template

    $opus->assign("student_possible", $student_possible);
    $opus->assign("student_output", $student_output);
    $opus->assign("student_selected", $student_selected);

    $opus->assign("staff_possible", $staff_possible);
    $opus->assign("staff_output", $staff_output);
    $opus->assign("staff_selected", $staff_selected);

    $opus->assign("contact_possible", $contact_possible);
    $opus->assign("contact_output", $contact_output);
    $opus->assign("contact_selected", $contact_selected);

    $opus->assign("company_possible", $company_possible);
    $opus->assign("company_output", $company_output);
    $opus->assign("company_selected", $company_selected);

    $opus->assign("vacancy_possible", $vacancy_possible);
    $opus->assign("vacancy_output", $vacancy_output);
    $opus->assign("vacancy_selected", $vacancy_selected);

    $opus->assign("help_possible", $help_possible);
    $opus->assign("help_output", $help_output);
    $opus->assign("help_selected", $help_selected);

    $opus->assign("cvgroup_possible", $cvgroup_possible);
    $opus->assign("cvgroup_output", $cvgroup_output);
    $opus->assign("cvgroup_selected", $cvgroup_selected);

    $opus->assign("assessmentgroup_possible", $assessmentgroup_possible);
    $opus->assign("assessmentgroup_output", $assessmentgroup_output);
    $opus->assign("assessmentgroup_selected", $assessmentgroup_selected);

    $opus->assign("channel_possible", $channel_possible);
    $opus->assign("channel_output", $channel_output);
    $opus->assign("channel_selected", $channel_selected);

    $opus->assign("automail_possible", $automail_possible);
    $opus->assign("automail_output", $automail_output);
    $opus->assign("automail_selected", $automail_selected);

    $opus->assign("resource_possible", $resource_possible);
    $opus->assign("resource_output", $resource_output);
    $opus->assign("resource_selected", $resource_selected);

    $opus->assign("faculty_possible", $faculty_possible);
    $opus->assign("faculty_output", $faculty_output);
    $opus->assign("faculty_selected", $faculty_selected);

    $opus->assign("school_possible", $school_possible);
    $opus->assign("school_output", $school_output);
    $opus->assign("school_selected", $school_selected);

    $opus->assign("programme_possible", $programme_possible);
    $opus->assign("programme_output", $programme_output);
    $opus->assign("programme_selected", $programme_selected);

    $opus->assign("import_possible", $import_possible);
    $opus->assign("import_output", $import_output);
    $opus->assign("import_selected", $import_selected);

    $opus->assign("status_possible", $status_possible);
    $opus->assign("status_output", $status_output);
    $opus->assign("status_selected", $status_selected);

    $opus->assign("log_possible", $log_possible);
    $opus->assign("log_output", $log_output);
    $opus->assign("log_selected", $log_selected);

    // Run the template

    $opus->assign("policy", $policy);
    $opus->display("main.tpl", "admin:advanced:manage_policies:edit_policy_permissions", "admin/advanced/edit_policy_permissions.tpl");
  }

  function edit_policy_permissions_do(&$opus, $user, $title)
  {
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

    //print_r($fields); exit;
    Policy::update($fields);
    header("location: ?section=advanced&function=manage_policies");
  }

  function add_policy(&$opus, &$user) 
  {
    add_object($opus, $user, "Policy", array("add", "advanced", "add_policy_do"), array(array("cancel","section=advanced&function=manage_policies")), array(array("user_id",$user["user_id"])), "admin:advanced:manage_policies:add_policy");
  }

  function add_policy_do(&$opus, &$user) 
  {
    add_object_do($opus, $user, "Policy", "section=advanced&function=manage_policies", "add_policy");
  }

  function edit_policy(&$opus, &$user) 
  {
    edit_object($opus, $user, "Policy", array("confirm", "advanced", "edit_policy_do"), array(array("cancel","section=advanced&function=manage_policies")), array(array("user_id",$user["user_id"])), "admin:advanced:manage_policies:edit_policy");
  }

  function edit_policy_do(&$opus, &$user) 
  {
    edit_object_do($opus, $user, "Policy", "section=advanced&function=manage_policies", "edit_policy");
  }

  function remove_policy(&$opus, &$user) 
  {
    remove_object($opus, $user, "Policy", array("remove", "advanced", "remove_policy_do"), array(array("cancel","section=advanced&function=manage_policies")), "", "admin:advanced:manage_policies:remove_policy");
  }

  function remove_policy_do(&$opus, &$user) 
  {
    remove_object_do($opus, $user, "Policy", "section=advanced&function=manage_policies");
  }



  // Languages

  function manage_languages(&$opus, $user, $title)
  {
    manage_objects($opus, $user, "Language", array(array("add","section=advanced&function=add_language")), array(array('edit', 'edit_language'), array('remove','remove_language')), "get_all", "", "admin:advanced:manage_languages:manage_languages");
  }

  function add_language(&$opus, &$user) 
  {
    add_object($opus, $user, "Language", array("add", "advanced", "add_language_do"), array(array("cancel","section=advanced&function=manage_languages")), array(array("user_id",$user["user_id"])), "admin:advanced:manage_languages:add_language");
  }

  function add_language_do(&$opus, &$user) 
  {
    add_object_do($opus, $user, "Language", "section=advanced&function=manage_languages", "add_language");
  }

  function edit_language(&$opus, &$user) 
  {
    edit_object($opus, $user, "Language", array("confirm", "advanced", "edit_language_do"), array(array("cancel","section=advanced&function=manage_languages")), array(array("user_id",$user["user_id"])), "admin:advanced:manage_languages:edit_language");
  }

  function edit_language_do(&$opus, &$user) 
  {
    edit_object_do($opus, $user, "Language", "section=advanced&function=manage_languages", "edit_language");
  }

  function remove_language(&$opus, &$user) 
  {
    remove_object($opus, $user, "Language", array("remove", "advanced", "remove_language_do"), array(array("cancel","section=advanced&function=manage_languages")), "", "admin:advanced:manage_languages:remove_language");
  }

  function remove_language_do(&$opus, &$user) 
  {
    remove_object_do($opus, $user, "Language", "section=advanced&function=manage_languages");
  }

  // Activity types

  function manage_activitytypes(&$opus, $user, $title)
  {
    manage_objects($opus, $user, "Activitytype", array(array("add","section=advanced&function=add_activitytype")), array(array('edit', 'edit_activitytype'), array('remove','remove_activitytype')), "get_all", "", "admin:advanced:manage_activitytypes:manage_activitytypes");
  }

  function add_activitytype(&$opus, &$user) 
  {
    add_object($opus, $user, "Activitytype", array("add", "advanced", "add_activitytype_do"), array(array("cancel","section=advanced&function=manage_activitytypes")), array(array("user_id",$user["user_id"])), "admin:advanced:manage_activitytypes:add_activitytype");
  }

  function add_activitytype_do(&$opus, &$user) 
  {
    add_object_do($opus, $user, "Activitytype", "section=advanced&function=manage_activitytypes", "add_activitytype");
  }

  function edit_activitytype(&$opus, &$user) 
  {
    edit_object($opus, $user, "Activitytype", array("confirm", "advanced", "edit_activitytype_do"), array(array("cancel","section=advanced&function=manage_activitytypes")), array(array("user_id",$user["user_id"])), "admin:advanced:manage_activitytypes:edit_activitytype");
  }

  function edit_activitytype_do(&$opus, &$user) 
  {
    edit_object_do($opus, $user, "Activitytype", "section=advanced&function=manage_activitytypes", "edit_activitytype");
  }

  function remove_activitytype(&$opus, &$user) 
  {
    remove_object($opus, $user, "Activitytype", array("remove", "advanced", "remove_activitytype_do"), array(array("cancel","section=advanced&function=manage_activitytypes")), "", "admin:advanced:manage_activitytypes:remove_activitytype");
  }

  function remove_activitytype_do(&$opus, &$user) 
  {
    remove_object_do($opus, $user, "Activitytype", "section=advanced&function=manage_activitytypes");
  }

  // Channels

  function manage_channels(&$opus, $user, $title)
  {
    set_navigation_history($opus, "Channels");

    manage_objects($opus, $user, "Channel", array(array("add","section=advanced&function=add_channel")), array(array('edit', 'edit_channel'), array('associations', 'manage_channelassociations'), array('remove','remove_channel')), "get_all", "", "admin:advanced:manage_channels:manage_channels");
  }

  function add_channel(&$opus, &$user) 
  {
    add_object($opus, $user, "Channel", array("add", "advanced", "add_channel_do"), array(array("cancel","section=advanced&function=manage_channels")), array(array("user_id",$user["user_id"])), "admin:advanced:manage_channels:add_channel");
  }

  function add_channel_do(&$opus, &$user) 
  {
    add_object_do($opus, $user, "Channel", "section=advanced&function=manage_channels", "add_channel");
  }

  function edit_channel(&$opus, &$user) 
  {
    $id = (int) WA::request("id");
    require_once("model/Channel.class.php");
    $channel = Channel::load_by_id($id);

    add_navigation_history($opus, $channel->name);

    edit_object($opus, $user, "Channel", array("confirm", "advanced", "edit_channel_do"), array(array("cancel","section=advanced&function=manage_channels")), array(array("user_id",$user["user_id"])), "admin:advanced:manage_channels:edit_channel");
  }

  function edit_channel_do(&$opus, &$user) 
  {
    edit_object_do($opus, $user, "Channel", "section=advanced&function=manage_channels", "edit_channel");
  }

  function remove_channel(&$opus, &$user) 
  {
    remove_object($opus, $user, "Channel", array("remove", "advanced", "remove_channel_do"), array(array("cancel","section=advanced&function=manage_channels")), "", "admin:advanced:manage_channels:remove_channel");
  }

  function remove_channel_do(&$opus, &$user) 
  {
    remove_object_do($opus, $user, "Channel", "section=advanced&function=manage_channels");
  }

  // Channel Associations

  function manage_channelassociations(&$opus, $user, $title)
  {
    $channel_id = (int) WA::request('id', true);
    require_once("model/Channel.class.php");
    $channel = Channel::load_by_id($channel_id);

    add_navigation_history($opus, $channel->name);

    manage_objects($opus, $user, "Channelassociation", array(array("add","section=advanced&function=add_channelassociation")), array(array('edit', 'edit_channelassociation'), array('associations', 'manage_channelassociation_associations'), array('remove','remove_channelassociation')), "get_all", "where channel_id=$channel_id", "admin:advanced:manage_channelassociations:manage_channelassociations");
  }

  function add_channelassociation(&$opus, &$user) 
  {
    $channel_id = (int) WA::request('id', true);

    add_object($opus, $user, "Channelassociation", array("add", "advanced", "add_channelassociation_do"), array(array("cancel","section=advanced&function=manage_channelassociations")), array(array("user_id",$user["user_id"]), array("channel_id", $channel_id)), "admin:advanced:manage_channelassociations:add_channelassociation");
  }

  function add_channelassociation_do(&$opus, &$user) 
  {
    add_object_do($opus, $user, "Channelassociation", "section=advanced&function=manage_channelassociations", "add_channelassociation");
  }

  function edit_channelassociation(&$opus, &$user) 
  {
    $channel_id = (int) WA::request('id', true);

    edit_object($opus, $user, "Channelassociation", array("confirm", "advanced", "edit_channelassociation_do"), array(array("cancel","section=advanced&function=manage_channelassociations")), array(array("user_id",$user["user_id"]), array("channel_id", $channel_id)), "admin:advanced:manage_channelassociations:edit_channelassociation");
  }

  function edit_channelassociation_do(&$opus, &$user) 
  {
    edit_object_do($opus, $user, "Channelassociation", "section=advanced&function=manage_channelassociations", "edit_channelassociation");
  }

  function remove_channelassociation(&$opus, &$user) 
  {
    remove_object($opus, $user, "Channelassociation", array("remove", "advanced", "remove_channelassociation_do"), array(array("cancel","section=advanced&function=manage_channelassociations")), "", "admin:advanced:manage_channelassociations:remove_channelassociation");
  }

  function remove_channelassociation_do(&$opus, &$user) 
  {
    remove_object_do($opus, $user, "Channelassociation", "section=advanced&function=manage_channelassociations");
  }

  // Mimetypes

  function manage_mimetypes(&$opus, $user, $title)
  {
    manage_objects($opus, $user, "Mimetype", array(array("add","section=advanced&function=add_mimetype")), array(array('edit', 'edit_mimetype'), array('remove','remove_mimetype')), "get_all", "", "admin:advanced:manage_mimetypes:manage_mimetypes");
  }

  function add_mimetype(&$opus, &$user) 
  {
    add_object($opus, $user, "Mimetype", array("add", "advanced", "add_mimetype_do"), array(array("cancel","section=advanced&function=manage_mimetypes")), array(array("user_id",$user["user_id"])), "admin:advanced:manage_mimetypes:add_mimetype");
  }

  function add_mimetype_do(&$opus, &$user) 
  {
    add_object_do($opus, $user, "Mimetype", "section=advanced&function=manage_mimetypes", "add_mimetype");
  }

  function edit_mimetype(&$opus, &$user) 
  {
    edit_object($opus, $user, "Mimetype", array("confirm", "advanced", "edit_mimetype_do"), array(array("cancel","section=advanced&function=manage_mimetypes")), array(array("user_id",$user["user_id"])), "admin:advanced:manage_mimetypes:edit_mimetype");
  }

  function edit_mimetype_do(&$opus, &$user) 
  {
    edit_object_do($opus, $user, "Mimetype", "section=advanced&function=manage_mimetypes", "edit_mimetype");
  }

  function remove_mimetype(&$opus, &$user) 
  {
    remove_object($opus, $user, "Mimetype", array("remove", "advanced", "remove_mimetype_do"), array(array("cancel","section=advanced&function=manage_mimetypes")), "", "admin:advanced:manage_mimetypes:remove_mimetype");
  }

  function remove_mimetype_do(&$opus, &$user) 
  {
    remove_object_do($opus, $user, "Mimetype", "section=advanced&function=manage_mimetypes");
  }

  // Automail templates

  function manage_automail(&$opus, $user, $title)
  {
    manage_objects($opus, $user, "Automail", array(array("add","section=advanced&function=add_automail")), array(array('edit', 'edit_automail'), array('remove','remove_automail')), "get_all", "", "admin:advanced:manage_automail:manage_automail");
  }

  function add_automail(&$opus, &$user) 
  {
    add_object($opus, $user, "Automail", array("add", "advanced", "add_automail_do"), array(array("cancel","section=advanced&function=manage_automail")), array(array("user_id",$user["user_id"])), "admin:advanced:manage_automail:add_automail");
  }

  function add_automail_do(&$opus, &$user) 
  {
    add_object_do($opus, $user, "Automail", "section=advanced&function=manage_automail", "add_automail");
  }

  function edit_automail(&$opus, &$user) 
  {
    edit_object($opus, $user, "Automail", array("confirm", "advanced", "edit_automail_do"), array(array("cancel","section=advanced&function=manage_automail")), array(array("user_id",$user["user_id"])), "admin:advanced:manage_automail:edit_automail");
  }

  function edit_automail_do(&$opus, &$user) 
  {
    edit_object_do($opus, $user, "Automail", "section=advanced&function=manage_automail", "edit_automail");
  }

  function remove_automail(&$opus, &$user) 
  {
    remove_object($opus, $user, "Automail", array("remove", "advanced", "remove_automail_do"), array(array("cancel","section=advanced&function=manage_automail")), "", "admin:advanced:manage_automail:remove_automail");
  }

  function remove_automail_do(&$opus, &$user) 
  {
    remove_object_do($opus, $user, "Automail", "section=advanced&function=manage_automail");
  }

  // Assessments

  function manage_assessments(&$opus, $user, $title)
  {
    set_navigation_history($opus, "Assessments");

    manage_objects($opus, $user, "Assessment", array(array("add","section=advanced&function=add_assessment")), array(array('edit', 'edit_assessment'), array('structure', 'manage_assessmentstructure'), array('remove','remove_assessment')), "get_all", "", "admin:advanced:manage_assessments:manage_assessments");
  }

  function add_assessment(&$opus, &$user) 
  {
    add_object($opus, $user, "Assessment", array("add", "advanced", "add_assessment_do"), array(array("cancel","section=advanced&function=manage_assessments")), array(array("user_id",$user["user_id"])), "admin:advanced:manage_assessments:add_assessment");
  }

  function add_assessment_do(&$opus, &$user) 
  {
    add_object_do($opus, $user, "Assessment", "section=advanced&function=manage_assessments", "add_assessment");
  }

  function edit_assessment(&$opus, &$user) 
  {
    edit_object($opus, $user, "Assessment", array("confirm", "advanced", "edit_assessment_do"), array(array("cancel","section=advanced&function=manage_assessments")), array(array("user_id",$user["user_id"])), "admin:advanced:manage_assessments:edit_assessment");
  }

  function edit_assessment_do(&$opus, &$user) 
  {
    edit_object_do($opus, $user, "Assessment", "section=advanced&function=manage_assessments", "edit_assessment");
  }

  function remove_assessment(&$opus, &$user) 
  {
    remove_object($opus, $user, "Assessment", array("remove", "advanced", "remove_assessment_do"), array(array("cancel","section=advanced&function=manage_assessments")), "", "admin:advanced:manage_assessments:remove_assessment");
  }

  function remove_assessment_do(&$opus, &$user) 
  {
    remove_object_do($opus, $user, "Assessment", "section=advanced&function=manage_assessments");
  }

  // Assessment structure

  function manage_assessmentstructure(&$opus, $user, $title)
  {
    $assessment_id = (int) WA::request('id', true);

    require_once('model/Assessment.class.php');
    $assessment = Assessment::load_by_id($assessment_id);
    add_navigation_history($opus, $assessment->description);

    manage_objects($opus, $user, "Assessmentstructure", array(array("add","section=advanced&function=add_assessmentstructure")), array(array('edit', 'edit_assessmentstructure'), array('up', "move_assessmentstructure_up&assessment_id=$assessment_id"), array('down', "move_assessmentstructure_down&assessment_id=$assessment_id"), array('remove','remove_assessmentstructure')), "get_all", "where assessment_id=$assessment_id", "admin:advanced:manage_assessments:manage_assessmentstructures");
  }

  function move_assessmentstructure_up(&$opus, &$user)
  {
    // Get the assessment id and varorder
    $assessment_id = (int) WA::request('assessment_id');
    $id = (int) WA::request('id');

    require_once('model/Assessmentstructure.class.php');

    Assessmentstructure::move_up($assessment_id, $id);
    header("location: ?section=advanced&function=manage_assessmentstructure&id=$assessment_id");
  }

  function move_assessmentstructure_down(&$opus, &$user)
  {
    // Get the assessment id and varorder
    $assessment_id = (int) WA::request('assessment_id');
    $id = (int) WA::request('id');

    require_once('model/Assessmentstructure.class.php');

    Assessmentstructure::move_down($assessment_id, $id);
    header("location: ?section=advanced&function=manage_assessmentstructure&id=$assessment_id");
  }


  function add_assessmentstructure(&$opus, &$user) 
  {
    add_navigation_history($opus, "Add Item");

    add_object($opus, $user, "Assessmentstructure", array("add", "advanced", "add_assessmentstructure_do"), array(array("cancel","section=advanced&function=manage_assessmentstructures")), array(array("user_id",$user["user_id"])), "admin:advanced:manage_assessments:add_assessmentstructure");
  }

  function add_assessmentstructure_do(&$opus, &$user) 
  {
    add_object_do($opus, $user, "Assessmentstructure", "section=advanced&function=manage_assessmentstructures", "add_assessmentstructure");
  }

  function edit_assessmentstructure(&$opus, &$user) 
  {
    $id = (int) WA::request('id', true);

    require_once('model/Assessmentstructure.class.php');
    $assessmentstructure = Assessmentstructure::load_by_id($id);
    add_navigation_history($opus, $assessmentstructure->name);

    edit_object($opus, $user, "Assessmentstructure", array("confirm", "advanced", "edit_assessmentstructure_do"), array(array("cancel","section=advanced&function=manage_assessmentstructures")), array(array("user_id",$user["user_id"])), "admin:advanced:manage_assessments:edit_assessmentstructure");
  }

  function edit_assessmentstructure_do(&$opus, &$user) 
  {
    edit_object_do($opus, $user, "Assessmentstructure", "section=advanced&function=manage_assessmentstructures", "edit_assessmentstructure");
  }

  function remove_assessmentstructure(&$opus, &$user) 
  {
    add_navigation_history($opus, "Remove Item");

    remove_object($opus, $user, "Assessmentstructure", array("remove", "advanced", "remove_assessmentstructure_do"), array(array("cancel","section=advanced&function=manage_assessmentstructures")), "", "admin:advanced:manage_assessments:remove_assessmentstructure");
  }

  function remove_assessmentstructure_do(&$opus, &$user) 
  {
    remove_object_do($opus, $user, "Assessmentstructure", "section=advanced&function=manage_assessmentstructures");
  }


?>