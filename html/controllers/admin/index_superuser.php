<?php

  /**
  * Superuser Menu for Administrators
  *
  * @package OPUS
  * @author Colin Turner <c.turner@ulster.ac.uk>
  * @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
  */

  //global $waf;
  if(!User::is_root()) $waf->halt("error:admin:no_access");

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

    manage_objects($waf, $user, "CSVMapping", array(array("add","section=superuser&function=add_csvmapping")), array(array('edit', 'edit_csvmapping'), array('remove','remove_csvmapping')), "get_all", array("", "order by name", $page), "admin:superuser:manage_csvmappings:manage_csvmappings");
  }

  function add_csvmapping(&$waf, &$user) 
  {
    add_object($waf, $user, "CSVMapping", array("add", "superuser", "add_csvmapping_do"), array(array("cancel","section=superuser&function=manage_csvmappings")), array(array("user_id",$user["user_id"])), "admin:superuser:manage_csvmappings:add_csvmapping", "super/home/manage_csvmapping.tpl");
  }

  function add_csvmapping_do(&$waf, &$user) 
  {
    add_object_do($waf, $user, "CSVMapping", "section=superuser&function=manage_csvmappings", "add_csvmapping");
  }

  function edit_csvmapping(&$waf, &$user) 
  {
    edit_object($waf, $user, "CSVMapping", array("confirm", "superuser", "edit_csvmapping_do"), array(array("cancel","section=superuser&function=manage_csvmappings")), array(array("user_id",$user["user_id"])), "admin:superuser:manage_csvmappings:edit_csvmapping", "super/home/manage_csvmapping.tpl");
  }

  function edit_csvmapping_do(&$waf, &$user) 
  {
    edit_object_do($waf, $user, "CSVMapping", "section=superuser&function=manage_csvmappings", "edit_csvmapping");
  }

  function remove_csvmapping(&$waf, &$user) 
  {
    remove_object($waf, $user, "CSVMapping", array("remove", "superuser", "remove_csvmapping_do"), array(array("cancel","section=superuser&function=manage_csvmappings")), "", "admin:superuser:manage_csvmappings:remove_csvmapping");
  }

  function remove_csvmapping_do(&$waf, &$user) 
  {
    remove_object_do($waf, $user, "CSVMapping", "section=superuser&function=manage_csvmappings");
  }

  function manage_api_users(&$waf)
  {
    manage_objects($waf, $user, "User", array(array("add","section=superuser&function=add_api_user")), array(array('remove', 'remove_api_user')), "get_all", array("where user_type='application'", "order by real_name", $page), "admin:superuser:manage_api_users:manage_api_users", "list.tpl", "application");
  }

  function remove_api_user(&$waf, &$user) 
  {
    remove_object($waf, $user, "User", array("remove", "superuser", "remove_api_user_do"), array(array("cancel","section=superuser&function=manage_api_users")), "", "admin:superuser:manage_api_users:remove_api_user", "manage.tpl", "", "application");
  }

  function remove_api_user_do(&$waf, &$user) 
  {
    remove_object_do($waf, $user, "User", "section=superuser&function=manage_api_users");
  }

  function add_api_user(&$waf, &$user) 
  {
    add_object($waf, $user, "User", array("add", "superuser", "add_api_user_do"), array(array("cancel","section=superuser&function=manage_api_users")), "", "admin:superuser:manage_api_users:add_api_user", "manage.tpl", "", "application_add");
  }

  function add_api_user_do(&$waf, &$user) 
  {
    $username = WA::request("username");
    $firstname = WA::request("firstname");
    $password = md5(WA::request("password"));

    require_once("model/User.class.php");

    $fields = array();
    $fields['username'] = $username;
    $fields['firstname'] = $firstname;
    $fields['password'] = $password;
    $fields['user_type'] = 'application';

    User::insert($fields);
    goto("superuser", "manage_api_users");
  }

  function user_directory(&$waf)
  {
    $sort_types = array("lastname" => "Last name", "reg_number" => "Reg Number", "last_time" => "Last Access");

    require_once("model/Preference.class.php");
    $form_options = Preference::get_preference("user_directory_form");

    $waf->assign("sort_types", $sort_types);
    $waf->assign("form_options", $form_options);

    $letters = array();
    for($loop = ord('A'); $loop <= ord('Z'); $loop++) array_push($letters, chr($loop));
    $waf->assign("letters", $letters);

    $waf->display("main.tpl", "admin:superuser:user_directory:user_directory", "super/home/user_directory.tpl");
  }

  function search_users(&$waf)
  {
    require_once("model/User.class.php");
    $search = WA::request("search", true);
    $sort = WA::request("sort");

    if(!preg_match('/^[A-Za-z0-9 ]*$/', $search)) $waf->halt("error:admin:invalid_search");

    $form_options['search'] = $search;
    $form_options['sort'] = $sort;
    require_once("model/Preference.class.php");
    Preference::set_preference("user_directory_form", $form_options);

    if(empty($search))
    {
      $where_clause = "";
    }
    else
    {
      $where_clause = "where lastname like '%$search%' OR firstname like '%$search%' OR reg_number like '%$search%' OR username like '%$search%'";
    }

    if(!in_array($sort, array('lastname', 'reg_number', 'last_time')))
    {
      $sort = 'lastname';
    }
    $sort_clause = "order by $sort";

    $objects = User::get_all($where_clause, $sort_clause);
    $headings = User::get_user_list_headings();
    $actions = array(array('edit', 'edit_user'), array('preferences', 'view_preferences'));

    $waf->assign("actions", $actions);
    $waf->assign("headings", $headings);
    $waf->assign("objects", $objects);

    $waf->display("main.tpl", "admin:superuser:user_directory:search_users", "list.tpl");
  }

  function simple_search_users(&$waf)
  {
    require_once("model/User.class.php");
    $initial = WA::request("initial");

    if(!preg_match('/^[A-Za-z0-9]$/', $initial)) $waf->halt("error:admins:invalid_search");

    $where_clause = "where lastname like '$initial%'";

    $objects = User::get_all($where_clause);
    $headings = User::get_user_list_headings();
    $actions = array(array('edit', 'edit_user'), array('preferences', 'view_preferences'));

    $waf->assign("actions", $actions);
    $waf->assign("headings", $headings);
    $waf->assign("objects", $objects);

    $waf->display("main.tpl", "admin:superuser:user_directory:simple_search_users", "list.tpl");
  }

  function edit_user(&$waf)
  {
    $id = WA::request("id");
    $user = User::load_by_id($id);

    switch($user->user_type)
    {
      case 'student':
        require_once("model/Student.class.php");
        $student = Student::load_by_user_id($id);
        goto("directories", "edit_student&student_id=" . $student->user_id);
        break;
      case 'staff':
        require_once("model/Staff.class.php");
        $staff = Staff::load_by_user_id($id);
        goto("directories", "edit_staff&id=" . $staff->id);
        break;
      case 'company':
        require_once("model/Contact.class.php");
        $contact = Contact::load_by_user_id($id);
        goto("directories", "edit_contact&id=" . $contact->id);
        break;
      case 'admin':
      case 'root':
        require_once("model/Admin.class.php");
        $admin = Admin::load_by_user_id($id);
        goto("directories", "edit_admin&id=" . $admin->id);
        break;
      default:
        $waf->halt("error:edit_user:category_not_supported_for_edit");
        break;
    }
  }

  function view_preferences(&$waf)
  {
    $id = (int) WA::request("id");
    $user = User::load_by_id($id);

    require_once("model/Preference.class.php");
    $preferences = Preference::fetch_all($user->reg_number);

    for($loop = 0; $loop < count($preferences); $loop++)
    {
      $preferences[$loop]->decoded_value = var_export(unserialize($preferences[$loop]->value), true);
    }
    $waf->assign("pref_user", $user);
    $waf->assign("preferences", $preferences);

    $waf->display("main.tpl", "super:home:user_directory:view_preferences", "super/home/view_preferences.tpl");
  }


?>