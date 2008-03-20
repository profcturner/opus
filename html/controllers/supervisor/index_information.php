<?php

  /**
  * Information Menu for Workplace Supervisors
  *
  * @package OPUS
  * @author Colin Turner <c.turner@ulster.ac.uk>
  * @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
  */

  function list_resources(&$opus, $user, $title)
  {
    $opus->assign("nopage", true);

    manage_objects($opus, $user, "Resource", array(), array(array('view', 'view_resource'), array('info','info_resource')), "get_all", array("where company_id = 0 or company_id is null"), "supervisor:information:list_resources:list_resources");
  }

  function view_resource(&$opus, $user, $title)
  {
    $id = (int) $_REQUEST["id"];
    require_once("model/Resource.class.php");

    Resource::view($id); 
  }


  function info_resource(&$opus, $user, $title)
  {
    $id = (int) $_REQUEST["id"];
    require_once("model/Resource.class.php");

    $resource = Resource::load_by_id($id);

    $opus->assign("resource", $resource);
    $opus->display("main.tpl", "supervisor:information:list_resources:info_resource", "general/information/info_resource.tpl");
  }

  function about(&$waf)
  {
    $waf->assign("show_banners", true);
    $waf->assign("ulster_logo", true);
    $waf->display("bounded.tpl", "general:information:information:about", "general/information/about.tpl");
  }

  function privacy(&$waf)
  {
    $waf->assign("show_banners", true);
    $waf->display("bounded.tpl", "general:information:information:about", "general/information/privacy.tpl");
  }

  function copyright(&$waf)
  {
    $waf->assign("show_banners", true);
    $waf->display("bounded.tpl", "general:information:information:about", "general/information/copyright.tpl");
  }

  function terms_conditions(&$waf)
  {
    $waf->assign("show_banners", true);
    $waf->display("bounded.tpl", "general:information:information:about", "general/information/terms.tpl");
  }


?>