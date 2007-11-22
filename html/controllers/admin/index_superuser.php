<?php

  /**
  * Superuser Menu for Administrators
  *
  * @package OPUS
  * @author Colin Turner <c.turner@ulster.ac.uk>
  * @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
  */

  if(!User::is_root()) $GLOBALS['waf']->halt("error:admin:no_access");

  /**
  * @author Gordon Crawford <g.crawford@ulster.ac.uk>
  */
  function view_phpinfo(&$waf) 
  {
    ob_start();
    phpinfo();
    $php_info = ob_get_contents();
    ob_end_clean(); 
    $php_info = preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $php_info);
    $waf->assign("php_info", $php_info);

    $waf->display("main.tpl", "admin:superuser:view_phpinfo:view_phpinfo", "super/home/view_phpinfo.tpl");
  }

  // PhoneHome

  function edit_phonehome(&$waf, &$user) 
  {
    // Indicate we should not ask again this session
    $_SESSION['phonehome_asked'] = true;

    // This table only has one row...
    $id = 1;

    edit_object($waf, $user, "PhoneHome", array("confirm", "superuser", "edit_phonehome_do"), array(array("cancel","section=home&function=home")), array(array("admin_id",User::get_id())), "admin:superuser:edit_phonehome:edit_phonehome");
  }

  function edit_phonehome_do(&$waf, &$user) 
  {
    edit_object_do($waf, $user, "PhoneHome", "section=home&function=home", "edit_phonehome");
  }

  // Service

  function edit_service(&$waf, &$user) 
  {
    // This table only has one row...
    $id = 1;

    edit_object($waf, $user, "Service", array("confirm", "superuser", "edit_service_do"), array(array("cancel","section=home&function=home")), array(array("admin_id",User::get_id())), "admin:superuser:edit_service:edit_service");
  }

  function edit_service_do(&$waf, &$user) 
  {
    edit_object_do($waf, $user, "Service", "section=superuser&function=edit_service", "edit_service");
  }

  // CSV Mappings

  function manage_csvmappings(&$waf, $user, $title)
  {
    $page = WA::request("page");

    manage_objects($waf, $user, "CSVMapping", array(array("add","section=superuser&function=add_csvmapping")), array(array('edit', 'edit_csvmapping'), array('remove','remove_csvmapping')), "get_all", array("", "order by name", $page), "admin:superuser:csvmappings:manage_csvmappings");
  }

  function add_csvmapping(&$waf, &$user) 
  {
    add_object($waf, $user, "CSVMapping", array("add", "superuser", "add_csvmapping_do"), array(array("cancel","section=superuser&function=manage_csvmappings")), array(array("user_id",$user["user_id"])), "admin:superuser:csvmappings:add_csvmapping");
  }

  function add_csvmapping_do(&$waf, &$user) 
  {
    add_object_do($waf, $user, "CSVMapping", "section=superuser&function=manage_csvmappings", "add_csvmapping");
  }

  function edit_csvmapping(&$waf, &$user) 
  {
    edit_object($waf, $user, "CSVMapping", array("confirm", "superuser", "edit_csvmapping_do"), array(array("cancel","section=superuser&function=manage_csvmappings")), array(array("user_id",$user["user_id"])), "admin:superuser:csvmappings:edit_csvmapping");
  }

  function edit_csvmapping_do(&$waf, &$user) 
  {
    edit_object_do($waf, $user, "CSVMapping", "section=superuser&function=manage_csvmappings", "edit_csvmapping");
  }

  function remove_csvmapping(&$waf, &$user) 
  {
    remove_object($waf, $user, "CSVMapping", array("remove", "superuser", "remove_csvmapping_do"), array(array("cancel","section=superuser&function=manage_csvmappings")), "", "admin:superuser:csvmappings:remove_csvmapping");
  }

  function remove_csvmapping_do(&$waf, &$user) 
  {
    remove_object_do($waf, $user, "CSVMapping", "section=superuser&function=manage_csvmappings");
  }

?>