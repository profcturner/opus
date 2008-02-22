<?php

  /**
  * Information Menu for Company Contacts
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

  function help_directory(&$waf)
  {
    require_once("model/Admin.class.php");
    require_once("model/HelpDirectory.class.php");

    $student_id = $_SESSION['student_id'];
    if(empty($student_id)) $student_id = WA::request("student_id");

    if($student_id)
    {
      require_once("model/Student.class.php");
      $student = Student::load_by_user_id($student_id);
      $specific_admins = HelpDirectory::get_student_admins($student_id);
      $waf->assign("student_id", $student_id);
      $waf->assign("student", $student);
    }

    $root_admins = HelpDirectory::get_root_admins();
    $inst_admins = HelpDirectory::get_institutional_admins();

    $admin_headings = Admin::get_admin_list_headings();
    $root_headings = Admin::get_root_list_headings();

    $waf->assign("root_admins", $root_admins);
    $waf->assign("inst_admins", $inst_admins);
    $waf->assign("specific_admins", $specific_admins);
    $waf->assign("admin_headings", $admin_headings);
    $waf->assign("root_headings", $root_headings);

    $waf->display("main.tpl", "admin:information:help_directory:help_directory", "admin/information/help_directory.tpl");
  }


  function about(&$waf)
  {
    $waf->assign("show_banners", true);
    $waf->assign("ulster_logo", true);
    $waf->display("bounded.tpl", "general:information:information:about", "general/information/about.tpl");
  }

?>