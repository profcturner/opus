<?php

  function manage_companies(&$opus, $user, $title)
  {
    manage_objects($opus, $user, "Company", array(array("add","section=directories&function=add_company")), array(array('edit', 'edit_company'), array('remove','remove_company')), "get_all", "", "admin:directories:companies:manage_companies");
  }

  function add_company(&$opus, &$user) 
  {
    add_object($opus, $user, "Company", array("add", "directories", "add_company_do"), array(array("cancel","section=directories&function=manage_companies")), array(array("user_id",$user["user_id"])), "admin:directories:companies:add_company");
  }

  function add_company_do(&$opus, &$user) 
  {
    add_object_do($opus, $user, "Company", "section=directories&function=manage_companies", "add_company");
  }

  function edit_company(&$opus, &$user) 
  {
    edit_object($opus, $user, "Company", array("confirm", "directories", "edit_company_do"), array(array("cancel","section=directories&function=manage_companies")), array(array("user_id",$user["user_id"])), "admin:directories:companies:edit_company");
  }

  function edit_company_do(&$opus, &$user) 
  {
    edit_object_do($opus, $user, "Company", "section=directories&function=manage_companies", "edit_company");
  }

  function remove_company(&$opus, &$user) 
  {
    remove_object($opus, $user, "Company", array("remove", "directories", "remove_company_do"), array(array("cancel","section=directories&function=manage_companies")), "", "admin:directories:companies:remove_company");
  }

  function remove_company_do(&$opus, &$user) 
  {
    remove_object_do($opus, $user, "Company", "section=directories&function=manage_companies");
  }



?>