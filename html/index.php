<?php

/**
 *
 * The front controller for OPUS
 *
 * @package OPUS
 * @author Colin Turner <c.turner@ulster.ac.uk>
 * @author Gordon Crawford <g.crawford@ulster.ac.uk>
 * @copyright Copyright (c) 2007, University of Ulster
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @uses opus.conf.php The main opus configuration file.
 * @uses WA.class.php
 *
 */

main();

/**
 * This is the main function that controls OPUS
 *
 * @uses WA.class.php
 * @uses $config
 * @uses WA::request()
 *
 */

function main() 
{
  global $config;
  global $config_sensitive;

  require_once("opus.conf.php");
  require_once("WA.class.php");


  if($config['opus']['benchmarking'])
  {
    require_once("model/Benchmark.class.php");
    $benchmark = new Benchmark;
  }

  // We need requires for any session contained objects before the session starts
  require_once("model/Lastitems.class.php");
  require_once("model/Policy.class.php"); // Not happy to have to read this for all classes

  // Initialise the Web Application Framework
  global $waf;
  $waf = new WA($config['waf']);

  // We want an extra log file for admin users
  $waf->create_log_file("admin");

  // Are there any errors carried in the session? This sometimes repeats the display
  // of an error but it seems a lesser evil than not showing it at all.
  // Best way to solve this is to create a generic error reporting object that the template
  // can interact with after the error has been shown.
  if(isset($_SESSION['waf']['SQL_error'])) $waf->assign("SQL_error", $_SESSION['waf']['SQL_error']);
  unset($_SESSION['waf']['SQL_error']);

  // Make a help prompter object to access XHTML help objects
  require_once("model/HelpPrompter.class.php");
  $help_prompter = new HelpPrompter;
  $waf->assign_by_ref("help_prompter", $help_prompter);
  $waf->assign_by_ref("benchmark", $benchmark);

  // Tell UUWAF about our database connections - there are two
  $waf->register_data_connection('default', $config_sensitive['opus']['database']['dsn'], $config_sensitive['opus']['database']['username'], $config_sensitive['opus']['database']['password']);
  $waf->register_data_connection('preferences', $config_sensitive['opus']['preference']['dsn'], $config_sensitive['opus']['preference']['username'], $config_sensitive['opus']['preference']['password']);

  $system_status = check_system_status($waf);

  // Try to authenticate any username and password credentials
  $user = $waf->login_user(WA::request('username'), WA::request('password')); 

  //assign configuration to smarty
  $waf->assign_by_ref("config", $config);

  // If the authenticators worked...
  if ($user['valid']) 
  {
    $waf->set_log_ident($user['username']);

    // Authentication works, now get all the details, use the username returned
    // by authentication, which might be different
    load_user($user['username']);

    $currentgroup = $waf->user['opus']['user_type'];

    // When closed, only root users can login
    if(!$system_status && $currentgroup != "root")
    {
      logout($waf);
      exit;
    }
    if($currentgroup == "root") $currentgroup="admin";

    //assignment of user
    $waf->assign_by_ref("user", $waf->user);
    $waf->assign_by_ref("currentgroup", $currentgroup);

    // Ok, on with the show
    $section =  $waf->get_section($config['opus']['cleanurls']); // this is the object relating to the object controller that should be loaded via the user tyle controller
    $function = $waf->get_function($config['opus']['cleanurls']); // this is the function that should be called

    // Make sure we take them somewhere!
    if(empty($section)) $section="home";
    if(empty($function)) $function="home";  
    // load controllers based on groups and capture the navigational structure
    $nav = $waf->load_group_controller($currentgroup);
    // load controller based on the object being managed
    $waf->load_section_controller($currentgroup,$section);
    //assignment of nav
    $waf->assign("nav", $nav);
    // call user function
    $waf->call_user_function($user, $section, $function, "home", "error");
  }
  else
  {
    // Show the login screen
    login($waf);
  }
}

function load_user($username)
{
  global $waf;
  $now = date("YmdHis");

  require_once("model/User.class.php");

  // Load the user from the table, these next actions are done each access
  $user = User::load_by_username($username);
  if($user == false)
  {
    $waf->log("no user account found for authenticated user");
    $waf->logout_user();
    unset($_SESSION);
    session_destroy();
    $waf->halt("error:no_user");
  }

  $fields['id'] = $user->id;
  $fields['last_time'] = $now;

  // These actions are done only on initial login
  if(!$waf->user['opus']['user_id'])
  {
    $opus_user = array();
    $opus_user['opus']['user_id']     = $user->id;
    $opus_user['opus']['salutation']  = $user->salutation;
    $opus_user['opus']['firstname']   = $user->firstname;
    $opus_user['opus']['lastname']    = $user->lastname;
    $opus_user['opus']['email']       = $user->email;
    $opus_user['opus']['user_type']   = $user->user_type;
    $opus_user['opus']['last_login']  = $user->login_time;
    $opus_user['opus']['reg_number']  = $user->reg_number;

    // Get, and cache, the complete list of channels
    //$opus_user['opus']['channels'] = User::get_channels($user->id);

    $fields['login_time'] = $now;
    $fields['online'] = "online";

    $waf->user = array_merge($waf->user, $opus_user);

    $waf->log("logging in");
    require_once("model/Preference.class.php");
    Preference::load_all($user->reg_number);

    $_SESSION['lastitems'] = new Lastitems(10);
    $_SESSION['waf']['user'] = $waf->user;
    require_once("model/Policy.class.php");
    Policy::load_default_policy();
  }
  $waf->assign_by_ref("lastitems", $_SESSION['lastitems']);
  User::update($fields);
  drop_cookie();
}

function drop_cookie()
{
  global $waf;
  $reg_number = $waf->user['opus']['reg_number'];

  if(strlen($reg_number))
  {
    require_once("WA.Cookie.class.php");
    $expiry = time() + 1800;
    $cookie_value="reg_number=$reg_number&session_id=" . session_id();
    Cookie::write("OPUSTicket",  $cookie_value, $expiry, '/', 'localhost');
  }
}

function check_system_status(&$waf)
{
  require_once("model/Service.class.php");
  $service_status = Service::checks();
  if($service_status == "opus:ok") return true; // all is well
  if($service_status == "error:opus:old_schema")
  {
    $waf->assign("opus_oldschema", true);
    $waf->assign("opus_closed", true);
  }
  if($service_status == "error:opus:closed")
  {
    $waf->assign("opus_closed", true);
  }
  return(false);
}

/**
 * Sets up the default nav for all user groups, just has the logout defined.
 */

function nav_default() 
{
  return array("logout"=>array(array("logout", "main", "logout", "logout", "logout")));
}

/**
 * A simple function to write the location header.
 * 
 * @param string $function
 */

function goto($section, $function) 
{  
  header("location:?section=$section&function=$function");
}

/**
 * Call the login template
 *
 * @param WA &$waf
 */

function login(&$waf) 
{
  $waf->display("login.tpl", "login");
}

/**
 * Clear the session and call the login template.
 *
 * @param WA &$waf
 */

function logout(&$waf) 
{
  global $waf;

  $id = $waf->user['opus']['user_id'];

  require_once("model/Preference.class.php");
  Preference::save_all($waf->user['opus']['reg_number']);
  $waf->log("logging out");

  if($id)
  {
    $fields = array();
    $fields['id'] = $id;
    $fields['online'] = 'offline';
    User::update($fields);
  }

  $_SESSION["currentgroup"] = "";
  $waf->logout_user();
  unset($_SESSION);
  session_destroy();
  $waf->display("login.tpl", "login");  
}

/**
 * Call the error template
 *
 * @param WA &$waf
 */

function error(&$waf) 
{
  $content = $waf->fetch("error.tpl");
  $waf->assign("content", $content);
  $waf->display("main.tpl");
}

/**
 * Call the about template
 *
 * @param WA &$waf
 * @param array $user
 */

function about(&$waf, $user) 
{
  $content = $waf->fetch("about.tpl");
  $waf->assign("content", $content);
  $waf->display("main.tpl");
}

/**
 * Sets the navigational history back to the current requested uri.
 *
 * @param WA &$waf
 * @param string $title The text to display in the navigational history.
 *
 * @uses $config[pds][session][navigation]
 */

function set_navigation_history(&$waf, $title) 
{
  global $config;

  $_SESSION[$config['opus']['navigation']] = array(array($title, $_SERVER['REQUEST_URI']));
  $waf->assign("navigation_history", $_SESSION[$config['opus']['navigation']]);
}

/**
 * Appends the current uri to the navigational history.
 *
 * @param WA &$waf
 * @param string $title The text to display in the navigational history.
 *
 * @uses $config[pds][session][navigation]
 */

function add_navigation_history(&$waf, $title) 
{
  global $config;

  $nav_history = array();
  $nav_history = $_SESSION[$config['opus']['navigation']];
  $new_nav_history = array();
  $nav_number = count($nav_history);
  $index = 0;
  while (($nav_history[$index][1] != $_SERVER['REQUEST_URI']) && ($index < $nav_number)) {
    array_push($new_nav_history, array($nav_history[$index][0], $nav_history[$index][1]));
    $index++;
  }

  array_push($new_nav_history, array($title, $_SERVER['REQUEST_URI']));

  $_SESSION[$config['opus']['navigation']] = $new_nav_history;

  $waf->assign("navigation_history", $_SESSION[$config[pds][session][navigation]]);
}

/**
 * Assigns a page and objects array to a template and passes the 'list' template
 * to the display method. 
 * 
 * This table creates a table view of a set of objects, based on the $list_tpl.
 * The display method is called on the $waf object and the $config_section and $list_tpl
 * are passed in.
 *
 * @param WA &$waf The web application instance object, pass as reference.
 * @param array $objects The array of objects to be displayed.
 * @param string $config_section The section configuration, used to active the navigational queues.
 * @param string $list_tpl The list template to use (default 'list.tpl').
 *
 * @uses WA::request()
 *
 */

function generate_table(&$waf, $objects, $config_section, $list_tpl='list.tpl') 
{
    $page = WA::request("page", true);
    
    $pages = array();
    $waf->assign("page", $page);
    $waf->assign("objects", $objects);

    $waf->display("main.tpl", $config_section, $list_tpl);

}

/**
 * View an object, normally using a template that presents a read-only view.
 *
 * @param WA &$waf The web application instance object, pass as reference.
 * @param array $user
 * @param string $object_name
 * @param string $page_title
 * @param string $tag_line
 * @param string $action_button
 * @param string $hidden_values
 * @param string $manage_tpl
 * @param string $section
 * @param string $subsection
 * @param integer $id
 * 
 */

function view_object(&$waf, &$user, $object_name, $action_links, $hidden_values, $config_section, $manage_tpl='manage.tpl') {

    $object = str_replace(" ", "_", ucwords($object_name));

    require_once("model/".$object.".class.php");

    $instance = new $object;

    $id = WA::request("id");
    $object = $instance->load_by_id($id, true);
    $waf->assign("action_button", $action_button);
    $waf->assign("action_links", $action_links);
    $waf->assign("mode", "remove");
    $waf->assign("object", $object);
    $waf->assign("headings", $instance->get_field_defs());
    $waf->assign("hidden_values", $hidden_values);
    $content = $waf->fetch($manage_tpl);
    $waf->assign("content", $content);
    $waf->display("main.tpl", $config_section, $manage_tpl);
    
  }

/**
 * Manage an object.  This presents a table view of the objects to be managed and normally
 * allows for the addition, editing and removal of these objects.
 *
 *
 * @param WA &$waf The web application instance object, pass as reference.
 * @param array $user The user array.
 * @param string $object_name The object's name.
 * @param string $action_links The action links that should be displayed.
 * @param string $actions The actions that should be displayed.
 * @param string $get_all_method The get all method, normall 'get_all()'.
 * @param string $get_all_parameter The get all parameters used to filter the object result set.
 * @param string $config_section The config section to get the page title and tag line from.
 * @param string $list_tpl The list template to be used to render the user display.
 * @param string $subsection
 * @param integer $id
 *
 * @uses generate_table
 * 
 */

function manage_objects(&$waf, $user, $object_name, $action_links, $actions, $get_all_method, $get_all_parameter='', $config_section, $list_tpl='list.tpl') {

    $object = str_replace(" ", "_", ucwords($object_name));

    require_once("model/".$object.".class.php");

    $instance = new $object;
    $object_num = $instance->count($get_all_parameter[0]);

    $waf->assign("action_links", $action_links);
    $waf->assign("headings", $instance->get_field_defs());
    $waf->assign("actions", $actions);

    $waf->assign("object_num", $object_num);

    if (is_array($get_all_parameter)) { 
      $objects = call_user_func_array(array($object, $get_all_method), $get_all_parameter);
    } else {
      $objects = call_user_func(array($object, $get_all_method), $get_all_parameter);
    }
    generate_table($waf, $objects, $config_section, $list_tpl);
  }

/**
 * Add an object.  This presents an empty form view for the addition of object information.
 * It is normally called from the manage object UI view. 
 *
 *
 * @param WA &$waf The web application instance object, pass as reference.
 * @param array $user The user array.
 * @param string $object_name The object's name.
 * @param string $action_button The action button that should be displayed.
 * @param array $action_links The action links that should be displayed.
 * @param array $hidden_values Hidden values required for the form to work correctly
 * @param string $config_section The config section to get the page title and tag line from.
 * @param string $manage_tpl The manage template to be used to render the form on the user display.
 *
 * 
 */

  function add_object(&$waf, &$user, $object_name, $action_button, $action_links, $hidden_values, $config_section, $manage_tpl='manage.tpl', $default=false)
  {

    $object = str_replace(" ", "_", ucwords($object_name));

    require_once("model/".$object_name.".class.php");

    $instance = new $object;

    $waf->assign("action_button", $action_button);
    $waf->assign("action_links", $action_links);
    $waf->assign("mode", "add");
    $waf->assign("object", $instance);
    $waf->assign("headings", $instance->get_field_defs());
    assign_lookups($waf, $instance);// check for lookups and populate the required smarty objects
    $waf->assign("hidden_values", $hidden_values);
    $content = $waf->fetch($manage_tpl);
    $waf->assign("content", $content);
    $waf->display("main.tpl", $config_section, $manage_tpl);

  }

/**
 * Process the addition of an object.  
 *
 *
 * @param WA &$waf The web application instance object, pass as reference.
 * @param array $user The user array.
 * @param string $object_name The object's name.
 * @param string $goto The header location to go to after the insertion.
 * @param string $goto_error The function to call if an error occurs.
 *
 * 
 */

  function add_object_do(&$waf, $user, $object_name, $goto, $goto_error='')
  {

    $object = str_replace(" ", "_", ucwords($object_name));

    require_once("model/".$object.".class.php");

    $obj = new $object;

    $nvp_array = call_user_func(array($object, "request_field_values"), False);  // false mean no id is requested
    $validation_messages = $obj->_validate($nvp_array);

    if (count($validation_messages) == 0) {
      $response = $obj->insert($nvp_array);

      if (!is_numeric($response)) 
      {
        $_SESSION['waf']['error_message'] = $response;
      }

      header("location: ?$goto"); 
    } else {
      if ($goto_error == "") $goto_error = "add_".strtolower($object);
      $waf->assign("nvp_array", $nvp_array);
      $waf->assign("validation_messages", $validation_messages);
      $goto_error($waf, $user);
    }
  }

/**
 * Edit an object.  This presents an completed form view for the editing of object information.
 * It is normally called from the manage object UI view. 
 *
 *
 * @param WA &$waf The web application instance object, pass as reference.
 * @param array $user The user array.
 * @param string $object_name The object's name.
 * @param string $action_button The action button that should be displayed.
 * @param array $action_links The action links that should be displayed.
 * @param array $hidden_values Hidden values required for the form to work correctly.
 * @param string $config_section The config section to get the page title and tag line from.
 * @param string $manage_tpl The manage template to be used to render the form on the user display.
 *
 * @uses WA::request()
 * 
 */

  function edit_object(&$waf, $user, $object_name, $action_button, $action_links, $hidden_values, $config_section, $manage_tpl='manage.tpl')
  {

    $object = str_replace(" ", "_", ucwords($object_name));  
    require_once("model/".$object.".class.php");
    $instance = new $object;

    $id = WA::request("id");
    $object = call_user_func(array($object, "load_by_id"), $id);
    $waf->assign("action_button", $action_button);
    $waf->assign("action_links", $action_links);
    $waf->assign("mode", "edit");
    $waf->assign("object", $object);
    $waf->assign("headings", $instance->get_field_defs());
    assign_lookups($waf, $instance);// check for lookups and populate the required smarty objects
    $waf->assign("hidden_values", $hidden_values);
    $content = $waf->fetch($manage_tpl);
    $waf->assign("content", $content);
    $waf->display("main.tpl", $config_section, $manage_tpl);

    // Log view
    if(method_exists($instance, "get_name")) $human_name = "(" .$instance->get_name($id) .")";
    $waf->log("editing $object_name $human_name");
  }

/**
 * Process the edit of an object.  
 *
 *
 * @param WA &$waf The web application instance object, pass as reference.
 * @param array $user The user array.
 * @param string $object_name The object's name.
 * @param string $goto The header location to go to after the insertion.
 * @param string $goto_error The function to call if an error occurs.
 *
 * 
 */

  function edit_object_do(&$waf, $user, $object_name, $goto, $goto_error='') {

    $object = str_replace(" ", "_", ucwords($object_name));
    require_once("model/".$object.".class.php");

    $obj = new $object;
    $nvp_array = call_user_func(array($object, "request_field_values"), True);  // false mean no id is requested
        $validation_messages = $obj->_validate($nvp_array);

    if (count($validation_messages) == 0) {
      $obj->update($nvp_array);
      header("location: ?$goto");
    } else {
         if ($goto_error == "") $goto_error = "edit_".strtolower($object);
      $waf->assign("nvp_array", $nvp_array);
      $waf->assign("validation_messages", $validation_messages);
      $goto_error($waf, $user);
    }

    // Log view
    $id = WA::request("id");
    if(method_exists($instance, "get_name")) $human_name = "(" .$instance->get_name($id) .")";
    $waf->log("changes made to $object_name $human_name");
  }

/**
 * Remove an object.  This presents summary view of the object to be removed and confirmation button 
 *
 *
 * @param WA &$waf The web application instance object, pass as reference.
 * @param array $user The user array.
 * @param string $object_name The object's name.
 * @param string $action_button The action button that should be displayed.
 * @param array $action_links The action links that should be displayed.
 * @param array $hidden_values Hidden values required for the form to work correctly.
 * @param string $config_section The config section to get the page title and tag line from.
 * @param string $manage_tpl The manage template to be used to render the form on the user display.
 *
 * @uses WA::request()
 * 
 */


  function remove_object(&$waf, &$user, $object_name, $action_button, $action_links, $hidden_values, $config_section, $manage_tpl='manage.tpl')
  {

    $object = str_replace(" ", "_", ucwords($object_name));

    require_once("model/".$object.".class.php");

    $instance = new $object;

    $id = WA::request("id");
    $object = call_user_func_array(array($object, "load_by_id"), array($id, true));
    $waf->assign("action_button", $action_button);
    $waf->assign("action_links", $action_links);
    $waf->assign("mode", "remove");
    $waf->assign("object", $object);
    $waf->assign("headings", $instance->get_field_defs());
    $waf->assign("hidden_values", $hidden_values);
    $content = $waf->fetch($manage_tpl);
    $waf->assign("content", $content);
    $waf->display("main.tpl", $config_section, $manage_tpl);

    // Log view
    if(method_exists($instance, "get_name")) $human_name = "(" .$instance->get_name($id) .")";
    $waf->log("possibly removing $object_name $human_name");
  }

/**
 * Process the removal of an object.  
 *
 *
 * @param WA &$waf The web application instance object, pass as reference.
 * @param array $user The user array.
 * @param string $object_name The object's name.
 * @param string $goto The header location to go to after the insertion.
 *
 * 
 */


  function remove_object_do(&$waf, &$user, $object_name, $goto) {

    $object = str_replace(" ", "_", ucwords($object_name));
    require_once("model/".$object.".class.php");

    $nvp_array = call_user_func(array($object, "request_field_values"), True);  // false mean no id is requested
    call_user_func(array($object, "remove"), $nvp_array[id]);

    // Log view
    $id = WA::request("id");
    if(method_exists($instance, "get_name")) $human_name = "(" .$instance->get_name($id) .")";
    $waf->log("deleting $object_name $human_name");

    header("location: ?$goto");
  }

/**
 * This assigns lookup values to for a lookup property to a Smarty variable called '$field_def[var]'.
 * The value assigned is an array of ids and field values.
 * 
 * @param WA &$waf The web application instance object, pass as reference.
 * @param object $instance An actual object instance that may contain lookup values.
 */

  function assign_lookups(&$waf, $instance) 
  {
    foreach ($instance->get_field_defs() as $field_def) 
    {
      if ($field_def['type'] == "lookup") 
      {
        $lookup_name = str_replace(" ", "_", ucwords($field_def['object']));
        require_once("model/".$lookup_name.".class.php");
        $lookup_function = $field_def['lookup_function'];
        if(empty($lookup_function)) $lookup_function="get_id_and_field";
        $lookup_array = call_user_func(array($lookup_name, $lookup_function), $field_def['value']);
        $waf->assign("$field_def[var]", $lookup_array);
      }
    }
  }

  function associate_objects(&$waf, &$user, $object_name, $objects_name, $action_button, $get_all_method, $get_all_parameter="", $config_section, $assign_tpl='assign.tpl') {
  
    $object = str_replace(" ", "_", ucwords($object_name));

    require_once("model/".$object.".class.php");
    
    $instance = new $object;

    $object_num = call_user_func(array($object, "count"));
    
    $waf->assign("action_links", $action_links);
    $waf->assign("headings", $instance->get_field_defs());
    $waf->assign("actions", $actions);
    $waf->assign("object_num", $object_num);

    if (is_array($get_all_parameter)) { 
      $objects = call_user_func_array(array($object, $get_all_method), $get_all_parameter);
    } else {
      $objects = call_user_func(array($object, $get_all_method), $get_all_parameter);
    }

    generate_table($waf, $objects, $config_section, $assign_tpl);
  }

/**
 * This generates an assign table, a table that can be used to assign one object to a number of
 * instance of another object, i.e. assign a user to a number of groups.
 * 
 * @param WA &$waf The web application instance object, pass as reference.
 * @param objects $instance An array of object instances.
 *
 * @uses WA::request()
 */

  function generate_assign_table(&$waf, $objects, $config_section, $assign_tpl='assign.tpl') 
  {


    $page = WA::request("page", true);
    
    $pages = array();
    
    $waf->assign("page", $page);
    $waf->assign("objects", $objects);

    $waf->display("main.tpl", $config_section, $list_tpl);



//    $page = WA::request("page");
    $pages = array();

    $number_of_objects = count($objects);

    for ($i = 1; $i<=$number_of_objects; $i=$i+ROWS_PER_PAGE) 
    {
      $p = (($i-1)/ROWS_PER_PAGE)+1;
      array_push ($pages, $p);
    }
    
    if (count($pages) == 0) $pages = array(1);
    $waf->assign("page", $page);
    $waf->assign("pages", $pages);
    $waf->assign("objects", $objects);

    $object_list = $waf->fetch("assign_list.tpl");
    $waf->assign("content", $object_list);
    $waf->display("main.tpl", $config_section, $assign_tpl);
  }
  
  function assign_objects_do(&$WA) {

  }

/**
 * This validates one field of an object.
 *
 * @uses WA::request()
 * @uses DTO::_validation_response() This is inherited by the $object instance
 *
 */

  function validate_field() 
  {
    $object = WA::request("object");
    $field = WA::request("field");
    $value = WA::request("value");
    
    $lookup_name = str_replace("_", " ", $object);
    $lookup_name = str_replace(" ", "_", ucwords($lookup_name));
   
    require_once("model/".$lookup_name.".class.php");

    $obj = new $lookup_name;
    echo $obj->_validation_response($field, $value);
  }

  function get_academic_year()
  {
    global $config;
    $yearstart = $config['opus']['yearstart'];
    if(empty($yearstart)) $yearstart="0930";

    if(empty($year)){
      if(date("md") < $yearstart) $year = date("Y") - 1;
      else $year = date("Y");
    }
    return($year);
  }

?>
