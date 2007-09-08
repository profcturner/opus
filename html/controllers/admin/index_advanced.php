<?php

  // Activity types

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