<?php

  // Activity types

  // Mimetypes

  function manage_activitytypes(&$opus, $user, $title)
  {
    manage_objects($opus, $user, "Activitytype", array(array("add","section=advanced&function=add_activitytype")), array(array('edit', 'edit_activitytype'), array('remove','remove_activitytype')), "get_all", "", "admin:advanced:activitytypes:manage_activitytypes");
  }

  function add_activitytype(&$opus, &$user) 
  {
    add_object($opus, $user, "Activitytype", array("add", "advanced", "add_activitytype_do"), array(array("cancel","section=advanced&function=manage_activitytypes")), array(array("user_id",$user["user_id"])), "admin:advanced:activitytypes:add_activitytype");
  }

  function add_activitytype_do(&$opus, &$user) 
  {
    add_object_do($opus, $user, "Activitytype", "section=advanced&function=manage_activitytypes", "add_activitytype");
  }

  function edit_activitytype(&$opus, &$user) 
  {
    edit_object($opus, $user, "Activitytype", array("confirm", "advanced", "edit_activitytype_do"), array(array("cancel","section=advanced&function=manage_activitytypes")), array(array("user_id",$user["user_id"])), "admin:advanced:activitytypes:edit_activitytype");
  }

  function edit_activitytype_do(&$opus, &$user) 
  {
    edit_object_do($opus, $user, "Activitytype", "section=advanced&function=manage_activitytypes", "edit_activitytype");
  }

  function remove_activitytype(&$opus, &$user) 
  {
    remove_object($opus, $user, "Activitytype", array("remove", "advanced", "remove_activitytype_do"), array(array("cancel","section=advanced&function=manage_activitytypes")), "", "admin:advanced:activitytypes:remove_activitytype");
  }

  function remove_activitytype_do(&$opus, &$user) 
  {
    remove_object_do($opus, $user, "Activitytype", "section=advanced&function=manage_activitytypes");
  }

  // Channels

  function manage_channels(&$opus, $user, $title)
  {
    manage_objects($opus, $user, "Channel", array(array("add","section=advanced&function=add_channel")), array(array('edit', 'edit_channel'), array('associations', 'manage_channel_associations'), array('remove','remove_channel')), "get_all", "", "admin:advanced:manage_channels:manage_channels");
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
    manage_objects($opus, $user, "Automail", array(array("add","section=advanced&function=add_automail")), array(array('edit', 'edit_automail'), array('remove','remove_automail')), "get_all", "", "admin:advanced:automail:manage_automail");
  }

  function add_automail(&$opus, &$user) 
  {
    add_object($opus, $user, "Automail", array("add", "advanced", "add_automail_do"), array(array("cancel","section=advanced&function=manage_automail")), array(array("user_id",$user["user_id"])), "admin:advanced:automails:add_automail");
  }

  function add_automail_do(&$opus, &$user) 
  {
    add_object_do($opus, $user, "Automail", "section=advanced&function=manage_automail", "add_automail");
  }

  function edit_automail(&$opus, &$user) 
  {
    edit_object($opus, $user, "Automail", array("confirm", "advanced", "edit_automail_do"), array(array("cancel","section=advanced&function=manage_automail")), array(array("user_id",$user["user_id"])), "admin:advanced:automails:edit_automail");
  }

  function edit_automail_do(&$opus, &$user) 
  {
    edit_object_do($opus, $user, "Automail", "section=advanced&function=manage_automail", "edit_automail");
  }

  function remove_automail(&$opus, &$user) 
  {
    remove_object($opus, $user, "Automail", array("remove", "advanced", "remove_automail_do"), array(array("cancel","section=advanced&function=manage_automail")), "", "admin:advanced:automail:remove_automail");
  }

  function remove_automail_do(&$opus, &$user) 
  {
    remove_object_do($opus, $user, "Automail", "section=advanced&function=manage_automail");
  }

  // Assessments

  function manage_assessments(&$opus, $user, $title)
  {
    manage_objects($opus, $user, "Assessment", array(array("add","section=advanced&function=add_assessment")), array(array('edit', 'edit_assessment'), array('structure', 'manage_assessment_structure'), array('remove','remove_assessment')), "get_all", "", "admin:advanced:manage_assessments:manage_assessments");
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

?>