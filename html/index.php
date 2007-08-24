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
 * This is the main function that control the PDS application, it implements a simple front controller
 * pattern.
 *
 * @uses WA.class.php
 * @uses $config
 * @uses $logger
 * @uses WA::request()
 *
 */

function main() 
{

  require_once('opus.conf.php');
  require_once("WA.class.php");

  // Initialise the Web Application Framework
  global $waf;
  $waf = new WA($config);

  $waf->register_data_connection('default', 'mysql:host=localhost;dbname=opus4', 'root', 'test');

  $user = $waf->login_user(WA::request('username'), WA::request('password')); 
  $currentgroup = WA::request("currentgroup", True);
  
  if (strlen($currentgroup) == 0) $currentgroup = $user[groups][0];
  
  //assign configuration to smarty
  $waf->assign_by_ref("config", $config);
  
  //assignment of user
  $waf->assign("user", $user);
  $waf->assign("currentgroup", $currentgroup);

  if ($user[valid]) 
  {
    $section =  $waf->get_section($config['opus']['cleanurls']); // this is the object relating to the object controller that should be loaded via the user tyle controller
    $function = $waf->get_function($config['opus']['cleanurls']); // this is the function that should be called
  
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

function goto($function) 
{  
  header("location:?function=".$function);
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
  $_SESSION["currentgroup"] = "";
  $waf->logout_user($waf->title."_user");
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

  $_SESSION[$config['pds']['session']['navigation']] = array(array($title, $_SERVER['REQUEST_URI']));
  $waf->assign("navigation_history", $_SESSION[$config['pds']['session']['navigation']]);
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
  $nav_history = $_SESSION[$config[pds][session][navigation]];
  $new_nav_history = array();
  $nav_number = count($nav_history);
  $index = 0;
  while (($nav_history[$index][1] != $_SERVER['REQUEST_URI']) && ($index < $nav_number)) {
    array_push($new_nav_history, array($nav_history[$index][0], $nav_history[$index][1]));
    $index++;
  }

  array_push($new_nav_history, array($title, $_SERVER['REQUEST_URI']));

  $_SESSION[$config[pds][session][navigation]] = $new_nav_history;

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
    $page = WA::request("page");
    
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

function view_object(&$waf, $user, $object_name, $page_title, $tag_line, $action_button, $hidden_values, $manage_tpl, $section, $subsection, $id) {

    $object = str_replace(" ", "_", ucwords($object_name));

    require_once("model/".$object.".class.php");

    $instance = new $object;

    $object = call_user_func(array($object, "load_by_id"), $id);
    //$fieldnames = call_user_func(array(ucwords($object_name), "get_fields"));
    $waf->assign("page_title", $page_title);
    $waf->assign("action_button", $action_button);
    $waf->assign("tag_line", $tag_line);
    $waf->assign("mode", "remove");
    $waf->assign("object", $object);
    $waf->assign("headings", $instance->_field_defs);
    $waf->assign("hidden_values", $hidden_values);
    $content = $waf->fetch($manage_tpl);
    $waf->assign("content", $content);
    $waf->assign("section", $section);
    $waf->assign("subsection", $subsection);
    $waf->display("main.tpl");
    
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
    
    $waf->assign("action_links", $action_links);
    $waf->assign("headings", $instance->_field_defs);
    $waf->assign("actions", $actions);

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

  function add_object(&$waf, &$user, $object_name, $action_button, $action_links, $hidden_values, $config_section, $manage_tpl='manage.tpl') {

    $object = str_replace(" ", "_", ucwords($object_name));

    require_once("model/".$object.".class.php");

    $instance = new $object;

    $waf->assign("action_button", $action_button);
    $waf->assign("action_links", $action_links);
    $waf->assign("mode", "add");
    $waf->assign("object", $instance);
    $waf->assign("headings", $instance->_field_defs);
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

  function add_object_do(&$waf, $user, $object_name, $goto, $goto_error='') {

    $object = str_replace(" ", "_", ucwords($object_name));
    
    require_once("model/".$object.".class.php");
    
    $obj = new $object;

    $nvp_array = call_user_func(array($object, "request_field_values"), False);  // false mean no id is requested
    $validation_messages = $obj->_validate($nvp_array);

    if (count($validation_messages) == 0) {
      call_user_func(array($object, "insert"), $nvp_array);
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

  function edit_object(&$waf, $user, $object_name, $action_button, $action_links, $hidden_values, $config_section, $manage_tpl='manage.tpl') {

    $object = str_replace(" ", "_", ucwords($object_name));  
    require_once("model/".$object.".class.php");
    $instance = new $object;

    $id = WA::request("id");
    $object = call_user_func(array($object, "load_by_id"), $id);
    $waf->assign("action_button", $action_button);
    $waf->assign("action_links", $action_links);
    $waf->assign("mode", "edit");
    $waf->assign("object", $object);
    $waf->assign("headings", $instance->_field_defs);
    assign_lookups($waf, $instance);// check for lookups and populate the required smarty objects
    $waf->assign("hidden_values", $hidden_values);
    $content = $waf->fetch($manage_tpl);
    $waf->assign("content", $content);
    $waf->display("main.tpl", $config_section, $manage_tpl);
    
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
      call_user_func(array($object, "update"), $nvp_array);
      header("location: ?$goto");
    } else {
         if ($goto_error == "") $goto_error = "edit_".strtolower($object);
      $waf->assign("nvp_array", $nvp_array);
      $waf->assign("validation_messages", $validation_messages);
      $goto_error($waf, $user);
       
    }

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


  function remove_object(&$waf, &$user, $object_name, $action_button, $action_links, $hidden_values, $config_section, $manage_tpl='manage.tpl') {

    $object = str_replace(" ", "_", ucwords($object_name));

    require_once("model/".$object.".class.php");

    $instance = new $object;

    $id = WA::request("id");
    $object = call_user_func(array($object, "load_by_id"), $id);
    $waf->assign("action_button", $action_button);
    $waf->assign("action_links", $action_links);
    $waf->assign("mode", "remove");
    $waf->assign("object", $object);
    $waf->assign("headings", $instance->_field_defs);
    $waf->assign("hidden_values", $hidden_values);
    $content = $waf->fetch($manage_tpl);
    $waf->assign("content", $content);
    $waf->display("main.tpl", $config_section, $manage_tpl);
    
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
    foreach ($instance->_field_defs as $field_def) 
    {
      if ($field_def['type'] == "lookup") 
      {
        $lookup_name = str_replace(" ", "_", ucwords($field_def['object']));
        require_once("model/".$lookup_name.".class.php");
        $lookup_array = call_user_func(array($lookup_name, "get_id_and_field"), $field_def['value']);
        $waf->assign("$field_def[var]", $lookup_array);
      }
    }
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

  function generate_assign_table(&$waf, $objects) 
  {
    $page = WA::request("page");
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
    $waf->display("main.tpl");
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

 
?>