<?php

  function list_resources(&$opus, $user, $title)
  {
    manage_objects($opus, $user, "Resource", array(), array(array('view', 'view_resource'), array('info','info_resource')), "get_all", "", "admin:information:list_resources:list_resources");
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
    $opus->display("main.tpl", "admin:information:resources:info_resource", "general/information/info_resource.tpl");
  }

  function view_logs(&$opus, $user, $title)
  {
    require_once("model/Log_Viewer.class.php");

    $logfile = $_REQUEST['logfile'];
    $search  = $_REQUEST['search'];
    $lines   = $_REQUEST['lines'];

    $log_viewer = new Log_Viewer($logfile, $search, $lines);
    $opus->display("main.tpl", "admin:information:view_logs:view_logs", "admin/information/log_viewer.tpl");
  }
  
?>