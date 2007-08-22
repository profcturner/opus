<?php

  function manage_resources(&$pds, $user, $title)
  {
    manage_objects($pds, $user, "Resource", array(array("add","section=configuration&function=add_resource")), array(array('edit', 'edit_resource'), array('remove','remove_resource')), "get_all", "", "admin:configuration:resources:manage_resources");
  }

  function add_resource(&$pds, &$user) 
  {
    add_object($pds, $user, "Resource", array("add", "configuration", "add_resource_do"), array(array("cancel","section=configuration&function=manage_resources")), array(array("user_id",$user["user_id"])), "admin:configuration:resources:add_resource");
  }

  function add_resource_do(&$pds, &$user) 
  {
    add_object_do($pds, $user, "Resource", "section=configuration&function=manage_resources", "add_resource");
  }

  function edit_resource(&$pds, &$user) 
  {
    edit_object($pds, $user, "Resource", array("confirm", "configuration", "edit_resource_do"), array(array("cancel","section=configuration&function=manage_resources")), array(array("user_id",$user["user_id"])), "admin:configuration:resources:edit_resource");
  }

  function edit_resource_do(&$pds, &$user) 
  {
    edit_object_do($pds, $user, "Resource", "section=configuration&function=manage_resources", "edit_resource");
  }

  function remove_resource(&$pds, &$user) 
  {
    remove_object($pds, $user, "Resource", array("remove", "configuration", "remove_resource_do"), array(array("cancel","section=configuration&function=manage_resources")), "", "admin:configuration:resources:remove_resource");
  }

  function remove_resource_do(&$pds, &$user) 
  {
    remove_object_do($pds, $user, "Resource", "section=configuration&function=manage_resources");
  }


?>