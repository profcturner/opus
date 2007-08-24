<?php

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


  function manage_channels(&$opus, $user, $title)
  {
    manage_objects($opus, $user, "Channel", array(array("add","section=configuration&function=add_channel")), array(array('edit', 'edit_channel'), array('remove','remove_channel')), "get_all", "", "admin:configuration:channels:manage_channels");
  }

  function add_channel(&$opus, &$user) 
  {
    add_object($opus, $user, "Channel", array("add", "configuration", "add_channel_do"), array(array("cancel","section=configuration&function=manage_channels")), array(array("user_id",$user["user_id"])), "admin:configuration:channels:add_channel");
  }

  function add_channel_do(&$opus, &$user) 
  {
    add_object_do($opus, $user, "Channel", "section=configuration&function=manage_channels", "add_channel");
  }

  function edit_channel(&$opus, &$user) 
  {
    edit_object($opus, $user, "Channel", array("confirm", "configuration", "edit_channel_do"), array(array("cancel","section=configuration&function=manage_channels")), array(array("user_id",$user["user_id"])), "admin:configuration:channels:edit_channel");
  }

  function edit_channel_do(&$opus, &$user) 
  {
    edit_object_do($opus, $user, "Channel", "section=configuration&function=manage_channels", "edit_channel");
  }

  function remove_channel(&$opus, &$user) 
  {
    remove_object($opus, $user, "Channel", array("remove", "configuration", "remove_channel_do"), array(array("cancel","section=configuration&function=manage_channels")), "", "admin:configuration:channels:remove_channel");
  }

  function remove_channel_do(&$opus, &$user) 
  {
    remove_object_do($opus, $user, "Channel", "section=configuration&function=manage_channels");
  }


  function manage_automail(&$opus, $user, $title)
  {
    manage_objects($opus, $user, "Automail", array(array("add","section=configuration&function=add_automail")), array(array('edit', 'edit_automail'), array('remove','remove_automail')), "get_all", "", "admin:configuration:automail:manage_automail");
  }

  function add_automail(&$opus, &$user) 
  {
    add_object($opus, $user, "Automail", array("add", "configuration", "add_automail_do"), array(array("cancel","section=configuration&function=manage_automail")), array(array("user_id",$user["user_id"])), "admin:configuration:automails:add_automail");
  }

  function add_automail_do(&$opus, &$user) 
  {
    add_object_do($opus, $user, "Automail", "section=configuration&function=manage_automail", "add_automail");
  }

  function edit_automail(&$opus, &$user) 
  {
    edit_object($opus, $user, "Automail", array("confirm", "configuration", "edit_automail_do"), array(array("cancel","section=configuration&function=manage_automail")), array(array("user_id",$user["user_id"])), "admin:configuration:automails:edit_automail");
  }

  function edit_automail_do(&$opus, &$user) 
  {
    edit_object_do($opus, $user, "Automail", "section=configuration&function=manage_automail", "edit_automail");
  }

  function remove_automail(&$opus, &$user) 
  {
    remove_object($opus, $user, "Automail", array("remove", "configuration", "remove_automail_do"), array(array("cancel","section=configuration&function=manage_automail")), "", "admin:configuration:automail:remove_automail");
  }

  function remove_automail_do(&$opus, &$user) 
  {
    remove_object_do($opus, $user, "Automail", "section=configuration&function=manage_automail");
  }



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