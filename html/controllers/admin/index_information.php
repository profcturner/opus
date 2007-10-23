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
    $opus->display("main.tpl", "admin:information:list_resources:info_resource", "general/information/info_resource.tpl");
  }

  function view_logs(&$opus, $user, $title)
  {
    require_once("model/Log_Viewer.class.php");
    require_once("model/Preference.class.php");

    if(!isset($_REQUEST['logfile']))
    {
      // being called for the first time, load any preferences
      $form_options = Preference::get_preference("log_viewer_form");
    }
    else
    {
      // Data incoming, save it...
      $form_options = array();

      $form_options['logfile'] = WA::request('logfile');
      $form_options['search']  = WA::request('search');
      $form_options['lines']   = WA::request('lines');

      Preference::set_preference("log_viewer_form", $form_options);
    }

    $log_viewer = new Log_Viewer($form_options['logfile'], $form_options['search'], $form_options['lines']);
    $opus->display("main.tpl", "admin:information:view_logs:view_logs", "admin/information/log_viewer.tpl");
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
      $student = Student::load_by_id($student_id);
      $waf->assign("student_id", $student_id);
      $waf->assign("student", $student);
    }

    $root_admins = HelpDirectory::get_root_admins();

    $admin_headings = Admin::get_admin_list_headings();
    $root_headings = Admin::get_root_list_headings();

    $waf->assign("root_admins", $root_admins);
    $waf->assign("admin_headings", $admin_headings);
    $waf->assign("root_headings", $root_headings);

    $waf->display("main.tpl", "admin:information:help_directory:help_directory", "admin/information/help_directory.tpl");
  }

  function system_status(&$waf)
  {
    // Find any ceiling on the number of users to show
    $max_users = (int) WA::request('max_users');
    if(empty($max_users)) $max_users = 10;
    $waf->assign("max_users", $max_users);

    require_once("model/User.class.php");

    // ignore limits on root users
    $root_users  = User::get_all("where user_type='root'", "order by last_index");

    $admin_users = User::get_all("where user_type='admin'", "order by last_index", 0, $max_users);
    $company_users = User::get_all("where user_type='company'", "order by last_index", 0, $max_users);
    $supervisor_users = User::get_all("where user_type='supervisor'", "order by last_index", 0, $max_users);
    $staff_users = User::get_all("where user_type='staff'", "order by last_index", 0, $max_users);
    $student_users = User::get_all("where user_type='student'", "order by last_index", 0, $max_users);

    $headings = array(
      'firstname'=>array('type'=>'text','size'=>30, 'header'=>true),
      'online'=>array('type'=>'list','values'=>array('no','yes'), 'header'=>true),
      'lastname'=>array('type'=>'text','size'=>30, 'header'=>true)
    );

    // Get user counts
    $root_count       = User::count("where user_type='root'");
    $admin_count      = User::count("where user_type='admin'");
    $company_count    = User::count("where user_type='company'");
    $supervisor_count = User::count("where user_type='supervisor'");
    $staff_count      = User::count("where user_type='staff'");
    $student_count    = User::count("where user_type='student'");
    $total_count = $root_count + $admin_count + $company_count + $supervisor_count + $staff_count + $student_count;

    $waf->assign("headings", $headings);
    $waf->assign("objects", $student_users);

    $waf->assign("root_users", $root_users);
    $waf->assign("admin_users", $admin_users);
    $waf->assign("company_users", $company_users);
    $waf->assign("supervisor_users", $supervisor_users);
    $waf->assign("staff_users", $staff_users);
    $waf->assign("student_users", $student_users);
    $waf->assign("root_count", $root_count);
    $waf->assign("admin_count", $admin_count);
    $waf->assign("company_count", $company_count);
    $waf->assign("supervisor_count", $supervisor_count);
    $waf->assign("staff_count", $staff_count);
    $waf->assign("student_count", $student_count);
    $waf->assign("total_count", $total_count);

    $waf->display("main.tpl", "admin:information:system_status:system_status", "admin/information/system_status.tpl");
  }

?>