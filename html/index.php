<?php

/**
 *
 * The front controller for OPUS
 *
 * @package OPUS
 * @author Colin Turner <c.turner@ulster.ac.uk>
 * @author Gordon Crawford <g.crawford@ulster.ac.uk>
 * @copyright Copyright (c) 2007-2011 Colin Turner, Gordon Crawford and the University of Ulster
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
  require_once("UUWAF.class.php");
  require_once("model/Preference.class.php");


  if($config['opus']['benchmarking'])
  {
    require_once("model/Benchmark.class.php");
    $benchmark = new Benchmark;
  }

  // We need requires for any session contained objects before the session starts
  require_once("model/Lastitems.class.php");
  require_once("model/Policy.class.php"); // Not happy to have to read this for all classes

  // Initialise the Web Application Framework
  $waf =& UUWAF::get_instance($config['waf']);

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
  //print_r($user);die;
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
    $user_id = $waf->user['opus']['user_id'];
	$preference_id = $user_id;
	$preferences = Preference::get_system_theme($preference_id);//print_r($preferences);die;
	$waf->assign("system_theme", $preferences);
    $waf->assign_by_ref("currentgroup", $currentgroup);
    
    $waf->assign("sidebar", true);

    // Is there any redirect, if so, get it, unset it and go there
    if(isset($_SESSION['redirect']))
    {
      $redirect = $_SESSION['redirect'];
      unset($_SESSION['redirect']);
      header("Location: $redirect");
    }

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
    // Some special functions don't require login
    $function = $waf->get_function($config['opus']['cleanurls']);
    unauthenticated_functions($function);
    // Then, they might be trying to go somewhere
    // Show the login screen, no valid user, keep any redirected URI
    $_SESSION['redirect'] = $_SERVER['REQUEST_URI'];
    if(WA::request("username"))
    {
      // Someone *tried* to login
      $waf->assign("failed_login", true);
    }
    login($waf);
  }
}

function unauthenticated_functions($function)
{
  switch($function)
  {
    case "request_recover_password":
      request_recover_password();
      break;
    case "request_recover_password_do":
      request_recover_password_do();
      break;
    case "recover_password_do":
      recover_password_do();
      break;
    default:
      return;
  }
  exit;
}

function load_user($username)
{
	global $config;
	
  $waf =& UUWAF::get_instance();
  $now = date("YmdHis");
	

  require_once("model/User.class.php");

  // Load the user from the table, these next actions are done each access
  $user = User::load_by_username($username);
  if($user == false)
  {
    if(WA::request("function") == 'logout')
    {
      logout($waf);
      exit;
    }
    else
    {
      $waf->log("no user account found for authenticated user [$username]");
			if($config['opus']['enable_self_service_user_creation'])
			{
				$waf->log("self service creation of users is enabled");
				// Self service creation is enabled. So far, only for students
				if(preg_match($config['opus']['student_username_regexp'], $username))
				{
					$waf->log("username looks like a valid student username");
					// And it is a student...
					require_once("model/StudentImport.class.php");
					$student_user_id = StudentImport::auto_add_student($username);
					if($student_user_id)
					{
						$waf->log("a student account was created");
						// Apparent success... try again
						$user = User::load_by_username($username);
					}
					else
					{
						$waf->log("no student account could be created");
					}
				}
				else
				{
					$waf->log("username doesn't match student username format, and only student creation supported");
				}
			}
			else
			{
				$waf->log("self service creation of users is disabled");
			}
			// Check again, because the above may have worked!
			if($user == false)
			{
      	$waf->logout_user();
      	unset($_SESSION);
      	session_destroy();
      	$waf->halt("error:no_user");
		  }
    }
  }

  $fields['id'] = $user->id;
  $fields['last_time'] = $now;

  // These actions are done only on initial login
  if(!$waf->user['opus']['user_id'])
  {
    // change the session id and delete the old session (for fixation attacks) note commit later
    session_regenerate_id(true);
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
    Preference::load_all($user->id);

    $_SESSION['lastitems'] = new Lastitems(10);
    $_SESSION['waf']['user'] = $waf->user;
    require_once("model/Policy.class.php");
    Policy::load_default_policy();
    $fields['session_hash'] = md5("sess_" . session_id());
    // Session serious misbehaves on new id if reloaded too fast (as it will be)
    session_commit();
    
    // Potentially update the online user statistics
    $drop_stats = true;
  }
  $waf->assign_by_ref("lastitems", $_SESSION['lastitems']);
  User::update($fields);
  if($drop_stats) User::drop_online_user_count_file();
  drop_cookies();
}


function request_recover_password()
{  
  $waf =& UUWAF::get_instance();

  $waf->assign("show_banners", true);
  $waf->display("bounded.tpl", "request_recover_password", "general/request_recover_password.tpl");
}

function request_recover_password_do()
{
  $waf =& UUWAF::get_instance();

  require_once("model/User.class.php");
  $recovery_email = WA::request("recovery_email");
  $count = User::send_recovery_email($recovery_email);
  $waf->assign("count", $count);
  $waf->assign("show_banners", true);
  $waf->display("bounded.tpl", "request_recover_password_do", "general/request_recover_password_do.tpl");


}
 
function recover_password_do()
{
  global $config;

  $waf =& UUWAF::get_instance();
  $waf->assign("show_banners", true);
  $waf->assign("password_reset", false);
  
  if($config['opus']['disable_selfservice_password_reset'])
  {
    $waf->log("Self service password reset is disabled by configuration");
    $waf->assign("disabled_password_reset", true);
    $waf->display("bounded.tpl", "recover_password_do", "general/recover_password_do.tpl");
    return false;
  }
  
  require_once("model/User.class.php");
  $user_id = $_REQUEST['user_id'];
  $type = User::get_type($user_id);
  if(in_array($type, $config['opus']['disable_selfservice_password_reset_by_category']))
  {
    $waf->log("Self service password reset is disabled by configuration for $type users");
    $waf->assign("disabled_password_reset_by_user", true);
    $waf->display("bounded.tpl", "recover_password_do", "general/recover_password_do.tpl");
    return false;    
  }
  $hash = $_REQUEST['hash'];
	
  $key = "user:reset_password:$user_id";
  require_once("model/Cache_Object.class.php");

  $cache = new Cache_Object();
  $cached = $cache->load_from_cache($key);

  if($cached)
  {
    // A valid user id and hash was presented, call the reset
    // function, and override the admin only functionality
    if($cached = serialize($hash))
    {
      $waf->log("Self service password reset has been successfully requested");
    
      require_once("model/User.class.php");
      User::reset_password($user_id, true);
      $waf->assign("password_reset", true);
      $waf->display("bounded.tpl", "recover_password_do", "general/recover_password_do.tpl");
      return(true);
    }
    else
    {
      $waf->log("Invalid hash on self service password request");
      $waf->assign("expired_hash", true);
      $waf->display("bounded.tpl", "recover_password_do", "general/recover_password_do.tpl");
      return(false);
    }
  }
  else
  {
    // presumably expired ticket
    $waf->log("Self service password reset has been rejected (invalid or expired)");
    $waf->assign("expired_hash", true);
    $waf->display("bounded.tpl", "recover_password_do", "general/recover_password_do.tpl");
    return(false);
  }
}

/**
* drops two cookies, one for u3 authentication, another to allow other links apps to logout the user
*
* The u3ticket cookie can be used to confirm a valid login, to allow other systems (e.g. PDSystem)
* to have transparent login. Note that this requires they have the same cookie secret defined in their
* WAF config.
*
* The opusticket allows a logout of another system (e.g. PDSystem) to trigger a logout of opus too.
*/
function drop_cookies()
{
  $waf =& UUWAF::get_instance();
  $reg_number = $waf->user['opus']['reg_number'];
  $username = $waf->user['opus']['username'];

  if(strlen($reg_number))
  {
    require_once("WA.Cookie.class.php");
    $expiry = time() + 1800;
    $cookie_value="username=$username&reg_number=$reg_number";
    Cookie::write("u3ticket",  $cookie_value, $expiry, '/');

    $cookie_value="username=$username&reg_number=$reg_number&session_id=" . session_id();
    Cookie::write("opusticket",  $cookie_value, $expiry, '/');
  }
}

function destroy_cookies()
{
  $waf =& UUWAF::get_instance();
  $waf->log("invalidating u3ticket and opusticket", PEAR_LOG_DEBUG, 'debug');
  require_once("WA.Cookie.class.php");
  // destroy the cookies we might have dropped
  Cookie::delete("u3ticket", '/');
  Cookie::delete("opusticket", '/');

  $pds_cookie = Cookie::read("pdsticket");
  if($pds_cookie)
  {
    $waf->log("pdsticket exists", PEAR_LOG_DEBUG, 'debug');
    if($pds_cookie['reg_number'] == $waf->user['opus']['reg_number'])
    {
      $waf->log("killing pds session", PEAR_LOG_DEBUG, 'debug');
      // PDSystem has a valid cookie for the same user, with the same secret, log them out too...
      require_once("model/PDSystem.class.php");
      PDSystem::kill_session($pds_cookie['session_id']);
    }
    else
    {
      $waf->log("pds cookie reg_number is " . $pds_cookie['reg_number'] ." but we are " . $waf->user['opus']['reg_number'], PEAR_LOG_DEBUG, 'debug');
    }
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

function goto_section($section, $function) 
{
  global $config;

  header("location:" . $config['opus']['url'] . "?section=$section&function=$function");
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
  $waf =& UUWAF::get_instance();

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
  
  // Drop the online statistics if required
  User::drop_online_user_count_file();

  destroy_cookies();
  $_SESSION["currentgroup"] = "";
  $waf->logout_user();
  @session_destroy();
  unset($_SESSION);
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

/*function about(&$waf, $user) 
{
  $content = $waf->fetch("about.tpl");
  $waf->assign("content", $content);
  $waf->display("main.tpl");
}
*/

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

function generate_table(&$waf, $objects, $config_section, $list_tpl='list.tpl',$popup=no) 
{
    $page = WA::request("page", true);
    
    $pages = array();
    $waf->assign("page", $page);
    $waf->assign("objects", $objects);
	$waf->assign("popup", $popup);
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

function view_object(&$waf, &$user, $object_name, $action_links, $hidden_values, $config_section, $manage_tpl='manage.tpl')
{

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
 * @param string $field_def_param Used to fine tune the field defs returned by the object
 *
 * @uses generate_table
 * 
 */

function manage_objects(&$waf, $user, $object_name, $action_links, $actions, $get_all_method, $get_all_parameter='', $config_section, $list_tpl='list.tpl', $field_def_param=null, $object_num=null, $popup=no)
{
	$objects = array();
    $object = str_replace(" ", "_", ucwords($object_name));

    require_once("model/".$object.".class.php");

    $instance = new $object;
	if (is_null($object_num)) $object_num = $instance->count($get_all_parameter[0]);

	$waf->assign("popup", $popup);
    $waf->assign("action_links", $action_links);
    $waf->assign("headings", $instance->get_field_defs($field_def_param));
    $waf->assign("actions", $actions);

    $waf->assign("object_num", $object_num);

    if (is_array($get_all_parameter))
    {
      $objects = call_user_func_array(array($object, $get_all_method), $get_all_parameter);
    }
    else
    {
      $objects = call_user_func(array($object, $get_all_method), $get_all_parameter);
    }
    generate_table($waf, $objects, $config_section, $list_tpl, $popup);
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
 * @param array $additional_fields Optional field defs to merge in with existing ones
 * @param string $field_def_param Used to fine tune the field defs returned by the object
 *
 */
function add_object(&$waf, &$user, $object_name, $action_button, $action_links, $hidden_values, $config_section, $manage_tpl='manage.tpl', $additional_fields='', $field_def_param=null)
{

  $object = str_replace(" ", "_", ucwords($object_name));

  require_once("model/".$object_name.".class.php");

  $instance = new $object;

  $headings = $instance->get_field_defs($field_def_param);
  if (is_array($additional_fields)) $headings = array_merge($headings, $additional_fields);
 
  $errors = $waf->errors;
  $waf->assign("action_button", $action_button);
  $waf->assign("action_links", $action_links);
  $waf->assign("mode", "add");
  $waf->assign("object", $instance);
  $waf->assign("headings", $headings);
  assign_lookups($waf, $instance);// check for lookups and populate the required smarty objects
  $waf->assign("hidden_values", $hidden_values);
  $waf->assign("content", $content);
    if($errors != "yes")
    {
    	$waf->display("popup.tpl", $config_section, $manage_tpl);
    }
    else
    {
    	$waf->display("main.tpl", $config_section, $manage_tpl);
    }
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
  global $config;

  $object = str_replace(" ", "_", ucwords($object_name));

  require_once("model/".$object.".class.php");

  $obj = new $object;

  $nvp_array = call_user_func(array($object, "request_field_values"), False);  // false mean no id is requested
  $validation_messages = $obj->_validate($nvp_array);

  if (count($validation_messages) == 0)
  {
    $response = $obj->insert($nvp_array);

    if (!is_numeric($response)) 
    {
      $_SESSION['waf']['error_message'] = $response;
    }
    else
    {
      // Log insert if possible / sensible
      $id = $response;
      if(method_exists($obj, "get_name")) $human_name = "(" .$obj->get_name($id) .")";
      $waf->log("new $object_name added $human_name");
    }
    header("location: " . $config['opus']['url'] . "?$goto");
  }
  else
  {
    if ($goto_error == "") $goto_error = "add_".strtolower($object);
    $waf->assign("nvp_array", $nvp_array);
    $waf->errors = "yes";
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
 * @param array $additional_fields Optional field defs to merge in with existing ones
 * @param string $field_def_param Used to fine tune the field defs returned by the object
 *
 * @uses WA::request()
 * 
 */
function edit_object(&$waf, $user, $object_name, $action_button, $action_links, $hidden_values, $config_section, $manage_tpl='manage.tpl', $additional_fields='', $field_def_param=null)
{
  $object = str_replace(" ", "_", ucwords($object_name));  
  require_once("model/".$object.".class.php");
  $instance = new $object;

  $headings = $instance->get_field_defs($field_def_param);
  if (is_array($additional_fields)) $headings = array_merge($headings, $additional_fields);
  
  $errors = $waf->errors;
  $id = WA::request("id");
  $object = call_user_func(array($object, "load_by_id"), $id);
  $waf->assign("action_button", $action_button);
  $waf->assign("action_links", $action_links);
  $waf->assign("mode", "edit");
  $waf->assign("object", $object);
  $waf->assign("headings", $headings);
  // check for lookups and populate the required smarty objects
  assign_lookups($waf, $instance);
  $waf->assign("hidden_values", $hidden_values);
  $content = $waf->fetch($manage_tpl);
  $waf->assign("content", $content);
     if($errors != "yes" && $object_name != "Service" && $object_name != "PhoneHome" && $object_name != "Staff" && $object_name != "Student" && $object_name != "Company" && $object_name != "Vacancy" && $object_name != "Admin" && $object_name != "Contact")
    {
		$waf->display("popup.tpl", $config_section, $manage_tpl);
    }
    else
    {
    	$waf->display("main.tpl", $config_section, $manage_tpl);
    }
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
function edit_object_do(&$waf, $user, $object_name, $goto, $goto_error='')
{
  global $config;

  $object = str_replace(" ", "_", ucwords($object_name));
  require_once("model/".$object.".class.php");

  $obj = new $object;
  $nvp_array = call_user_func(array($object, "request_field_values"), True);  // false mean no id is requested
      $validation_messages = $obj->_validate($nvp_array);

  if (count($validation_messages) == 0) {
    $obj->update($nvp_array);
    header("location: " . $config['opus']['url'] . "?$goto");
  }
  else
  {
    if ($goto_error == "") $goto_error = "edit_".strtolower($object);
    $waf->assign("nvp_array", $nvp_array);
    $waf->assign("validation_messages", $validation_messages);
    $waf->errors = "yes";
    $goto_error($waf, $user);
  }

  // Log edit
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
function remove_object(&$waf, &$user, $object_name, $action_button, $action_links, $hidden_values, $config_section, $manage_tpl='manage.tpl',  $additional_fields='', $field_def_param=null)
{

  $object = str_replace(" ", "_", ucwords($object_name));

  require_once("model/".$object.".class.php");

  $instance = new $object;

  $headings = $instance->get_field_defs($field_def_param);
  if (is_array($additional_fields)) $headings = array_merge($headings, $additional_fields);

  $id = WA::request("id");
  $object = call_user_func_array(array($object, "load_by_id"), array($id, true));
  $waf->assign("action_button", $action_button);
  $waf->assign("action_links", $action_links);
  $waf->assign("mode", "remove");
  $waf->assign("object", $object);
  $waf->assign("headings", $headings);
  $waf->assign("hidden_values", $hidden_values);
  $content = $waf->fetch($manage_tpl);
  $waf->assign("content", $content);
  $waf->display("popup.tpl", $config_section, $manage_tpl);

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
function remove_object_do(&$waf, &$user, $object_name, $goto)
{
  global $config;

  $object = str_replace(" ", "_", ucwords($object_name));
  require_once("model/".$object.".class.php");

  $nvp_array = call_user_func(array($object, "request_field_values"), True);  // false mean no id is requested
  call_user_func(array($object, "remove"), $nvp_array[id]);

  // Log view
  $id = WA::request("id");
  if(method_exists($instance, "get_name")) $human_name = "(" .$instance->get_name($id) .")";
  $waf->log("deleting $object_name $human_name");

  header("location: " . $config['opus']['url'] . "?$goto");
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

function download_artefact(&$waf)
{
  require_once('model/Artefact.class.php');
  $hash = WA::request('hash');

  try
  {
    $artefact = Artefact::load_by_hash($hash);

    header("Content-type: ".$artefact->file_type."\n");
    header("Content-Disposition: inline; filename=\"".$artefact->file_name."\"\n");
    header("Content-Transfer-Encoding: binary\n"); 
    header("Content-length: " . $artefact->file_size. "\n"); 
    $fullpath = User::upload_path($artefact->user_id).$artefact->hash;

    $fp = fopen($fullpath, "rb");
    while (!feof($fp)) { echo fgets($fp, 65536); }
    fclose($fp);
  }
  catch (Exception $e)
  {
    $waf->log("download_artefact($hash): $e");
  }
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


function edit_preferences(&$waf, $id)
{
  require_once('model/Student.class.php');

  
  $referrer = $_SERVER["HTTP_REFERER"];
  $cancel = explode('?', $referrer);

  $preferences = $_SESSION['waf'][$waf->title]['preferences'];
  if (is_array($preferences))
  {
    foreach ($preferences as $name => $value)
    {
      $waf->assign($name, $value);
    }
  }

  /*$waf->assign('email_accounts', $email_accounts);*/
  $waf->assign('action_links', array(array('cancel', $cancel[1])));
  $waf->assign('referrer', $referrer);
  $waf->display('popup.tpl', 'preferences', 'preferences.tpl');

}


function edit_preferences_do(&$waf, $id)
{
   $waf =& UUWAF::get_instance($config['waf']);
   $user_id = $id['opus']['user_id'];

  require_once('model/Preference.class.php');

  Preference::set_preference('resources_active', WA::request('resources_active'));
  Preference::set_preference('bookmarks_active', WA::request('bookmarks_active'));
  Preference::set_preference('trails_active', WA::request('trails_active'));
  Preference::set_preference('calendar_active', WA::request('calendar_active'));
  Preference::set_preference('system_theme', WA::request('system_theme'));
  Preference::set_preference('preferred_email_account', WA::request('preferred_email_account'));
  Preference::set_preference('calendar_day_starts', WA::request('calendar_day_starts'));
  Preference::set_preference('calendar_day_ends', WA::request('calendar_day_ends'));
  Preference::set_preference('hide_read_messages', WA::request('hide_read_messages'));
  
  Preference::save_all($user_id);


  $goto = WA::request('referrer');

  header("location: $goto");

}

?>
