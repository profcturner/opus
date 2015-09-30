<?php

  /**
  * Information Menu for Administrators
  *
  * @package OPUS
  * @author Colin Turner <c.turner@ulster.ac.uk>
  * @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
  */

  function list_resources($opus, $user, $title)
  {
    $opus->assign("nopage", true);

    manage_objects($opus, $user, "Resource", array(), array(array('view', 'view_resource', 'no'), array('info','info_resource')), "get_all", array("where company_id = 0 or company_id is null"), "admin:information:list_resources:list_resources");
  }

  function view_resource($opus, $user, $title)
  {
    $id = (int) $_REQUEST["id"];
    require_once("model/Resource.class.php");

    Resource::view($id); 
  }


  function info_resource($opus, $user, $title)
  {
    $id = (int) $_REQUEST["id"];
    require_once("model/Resource.class.php");

    $resource = Resource::load_by_id($id);

    $opus->assign("resource", $resource);
    $opus->display("popup.tpl", "admin:information:list_resources:info_resource", "general/information/info_resource.tpl");
  }

  function list_reports($waf, $user, $title)
  {
    require_once("model/Report.class.php");
    $reports = Report::get_reports();
    $waf->assign("reports", $reports);
    $waf->display("main.tpl", "admin:information:list_reports:list_reports", "admin/information/list_reports.tpl");
  }

  function report_input($waf)
  {
    require_once("model/Report.class.php");
    $report = Report::make_object(WA::request("name"));
    $input_stage = (int) WA::request("input_stage");

    $report->input($input_stage);
  }

  function report_input_do($waf)
  {
    require_once("model/Report.class.php");
    $report = Report::make_object(WA::request("name"));
    $input_stage = (int) WA::request("input_stage");

    $report->input_do($input_stage);
  }

  function view_logs($opus, $user, $title)
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

  function help_directory($waf)
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

  function system_status($waf)
  {
    // Find any ceiling on the number of users to show
    $max_users = (int) WA::request('max_users');
    if(empty($max_users)) $max_users = 10;
    $waf->assign("max_users", $max_users);

    require_once("model/User.class.php");
    $online_user_count = User::online_user_count();

    // ignore limits on root users
    require_once("model/SystemStatus.class.php");
    $root_users  = SystemStatus::get_root_users();
    $root_headings = SystemStatus::get_root_headings();

    // Get ordinary Admins, headers and actions
    $admin_users = SystemStatus::get_admin_users($max_users);
    $admin_headings = SystemStatus::get_admin_headings();
    $admin_actions = SystemStatus::get_admin_actions();

    // Get Students, headers and actions
    $student_users = SystemStatus::get_student_users($max_users);
    $student_headings = SystemStatus::get_student_headings();
    $student_actions = SystemStatus::get_student_actions();

    // Get Contacts, headers and actions
    $contact_users = SystemStatus::get_contact_users($max_users);
    $contact_headings = SystemStatus::get_contact_headings();
    $contact_actions = SystemStatus::get_contact_actions();

    // Get Contacts, headers and actions
    $staff_users = SystemStatus::get_staff_users($max_users);
    $staff_headings = SystemStatus::get_staff_headings();
    $staff_actions = SystemStatus::get_staff_actions();

    // Get Contacts, headers and actions
    $supervisor_users = SystemStatus::get_supervisor_users($max_users);
    $supervisor_headings = SystemStatus::get_supervisor_headings();
    $supervisor_actions = SystemStatus::get_supervisor_actions();

    // Get user counts
    $root_count       = User::count("where user_type='root'");
    $admin_count      = User::count("where user_type='admin'");
    $company_count    = User::count("where user_type='company'");
    $supervisor_count = User::count("where user_type='supervisor'");
    $staff_count      = User::count("where user_type='staff'");
    $student_count    = User::count("where user_type='student'");
    $total_count = $root_count + $admin_count + $company_count + $supervisor_count + $staff_count + $student_count;

    $waf->assign("online_user_count", $online_user_count);
    $waf->assign("headings", $headings);

    $waf->assign("root_users", $root_users);
    $waf->assign("root_headings", $root_headings);
    $waf->assign("admin_users", $admin_users);
    $waf->assign("admin_headings", $admin_headings);
    $waf->assign("admin_actions", $admin_actions);

    $waf->assign("student_users", $student_users);
    $waf->assign("student_headings", $student_headings);
    $waf->assign("student_actions", $student_actions);

    $waf->assign("contact_users", $contact_users);
    $waf->assign("contact_headings", $contact_headings);
    $waf->assign("contact_actions", $contact_actions);

    $waf->assign("staff_users", $staff_users);
    $waf->assign("staff_headings", $staff_headings);
    $waf->assign("staff_actions", $staff_actions);

    $waf->assign("supervisor_users", $supervisor_users);
    $waf->assign("supervisor_headings", $supervisor_headings);
    $waf->assign("supervisor_actions", $supervisor_actions);

    $waf->assign("root_count", $root_count);
    $waf->assign("admin_count", $admin_count);
    $waf->assign("company_count", $company_count);
    $waf->assign("supervisor_count", $supervisor_count);
    $waf->assign("staff_count", $staff_count);
    $waf->assign("student_count", $student_count);
    $waf->assign("total_count", $total_count);

    $waf->display("main.tpl", "admin:information:system_status:system_status", "admin/information/system_status.tpl");
  }

  function system_statistics($waf)
  {
    // ignore limits on root users
    require_once("model/SystemStatistics.class.php");
		
		$years = SystemStatistics::get_years_of_use();

		$annual_statistics = SystemStatistics::get_statistics_by_year($years);		
		
		$waf->assign("years", $years);
		$waf->assign("annual_statistics", $annual_statistics);

    $waf->display("main.tpl", "admin:information:system_statistics:system_statistics", "admin/information/system_statistics.tpl");
  }

  function about($waf)
  {
    $waf->assign("show_banners", true);
    $waf->assign("ulster_logo", true);
    $waf->display("popup.tpl", "general:information:information:about", "general/information/about.tpl");
  }

  function privacy($waf)
  {
    $waf->assign("show_banners", true);
    $waf->display("popup.tpl", "general:information:information:privacy", "general/information/privacy.tpl");
  }

  function copyright($waf)
  {
    $waf->assign("show_banners", true);
    $waf->display("popup.tpl", "general:information:information:copyright", "general/information/copyright.tpl");
  }

  function terms_conditions($waf)
  {
    $waf->assign("show_banners", true);
    $waf->display("popup.tpl", "general:information:information:terms_conditions", "general/information/terms.tpl");
  }


?>
