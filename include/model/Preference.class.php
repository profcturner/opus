<?php

/**
* Handles the storage of user preferences, possibly in a different database 
* @package OPUS
*/
require_once("dto/DTO_Preference.class.php");
/**
* Handles the storage of user preferences, possibly in a different database 
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/

class Preference extends DTO_Preference
{
  var $application; // The application name
  var $name;        // A unique key field within the application
  var $value;       // The value stored (serialised)
  var $reg_number;  // A code which is constant for the user across applications

  function __construct() 
  {
    global $config;

    // The database *might* be the usual one, or perhaps not
    parent::__construct($config['opus']['pref_ident']);
  }

  function fetch_all($reg_number)
  {
    $waf =& UUWAF::get_instance();
    $application = $waf->title;
    if(empty($reg_number))
    {
      $waf->log("preferences cannot be fetched for this user without a reg_number", PEAR_LOG_DEBUG, 'debug');
      return array();
    }

    $pref = new Preference;

    $preferences = $pref->_get_all("where application='$application' and reg_number='$reg_number'");
    return($preferences);
  }

  function load_all($preference_id)
  {
    $waf =& UUWAF::get_instance();
    $application = $waf->title;
    if(empty($preference_id))
    {
      $waf->log("preferences cannot be loaded for this without a reg number", PEAR_LOG_DEBUG, 'debug');
      return;
    }

    $pref = new Preference;

    $preferences = $pref->_get_all("where application='$application' and reg_number='$preference_id'");

    // Unwind into the session
    foreach($preferences as $preference)
    {
      Preference::set_preference($preference->name, unserialize($preference->value));
    }
    $waf->log(count($preferences) . " preference values loaded", PEAR_LOG_DEBUG, 'debug');
  }

  function get_system_theme($preference_id)
  {
    $waf =& UUWAF::get_instance($config['waf']);

    $application = $waf->title;
  
    if(empty($preference_id))
    {
      $waf->log("preferences cannot be loaded for this user without a reg number", PEAR_LOG_DEBUG, 'debug');
      $system_theme = "blue";
      return $system_theme;
    }

		if(empty($application))
    {
      $waf->log("preferences cannot be loaded for user ".$preference_id." without an application name", PEAR_LOG_DEBUG, 'debug');
      $system_theme = "blue";
      return $system_theme;
    }

	$pref = new Preference;

    $preferences = $pref->_get_all("where application='$application' and reg_number='$preference_id'");

    // Unwind into the session
    foreach($preferences as $preference)
    {
      Preference::set_preference($preference->name, unserialize($preference->value));
    }
	
	$preferences = $_SESSION['waf'][$waf->title]['preferences'];

	$system_theme = $preferences['system_theme'];
	
    if ($system_theme == "")
    {	
    	$system_theme = "blue";
    }
    return $system_theme;
  }

  function save_all($preference_id)
  {
    require_once("model/User.class.php");
    $waf =& UUWAF::get_instance();
    $application = $waf->title;

    if(empty($preference_id))
    {
      $waf->log("preferences cannot be saved for this user without a reg number", PEAR_LOG_DEBUG, 'debug');
      return;
    }
    // Nothing to save?
    if(!isset($_SESSION['waf'][$application]['preferences'])) return;
    

    // Remove what's there
    $pref = new Preference; 
    //$pref->_remove_where("where application='$application' and reg_number='$preference_id'");
    $pref->_remove_where("where application='$application' and reg_number='$preference_id'");
    $pref->_remove_where("where application='pace' and reg_number='$preference_id' and name='system_theme'");
    $count = 0;

    foreach($_SESSION['waf'][$application]['preferences'] as $name => $value)
    {
      $fields = array();
      $fields['application'] = $application;
      $fields['reg_number'] = $preference_id;
      $fields['name'] = $name;
      $fields['value'] = serialize($value);

      $pref->_insert($fields);
      $count++;
        //Write the same theme for PACE
		if($name == 'system_theme')
		{ 
		  $pace_fields = array();
		  
		  $pace_fields['application'] = 'pace';
		  $pace_fields['reg_number'] = $preference_id;
		  $pace_fields['name'] = $name;
		  $pace_fields['value'] = serialize($value);
		  $pref->_insert($pace_fields);
		}
    }
    $waf->log("$count preference values saved $preference_id", PEAR_LOG_DEBUG, 'debug');
  }

  function set_preference($name, $value)
  {
    $waf =& UUWAF::get_instance();
    $application = $waf->title;

    $_SESSION['waf'][$application]['preferences'][$name] = $value;
  }

  function set_theme($name, $value)
  {
     $waf =& UUWAF::get_instance($config['waf']);
    $application = 'pace';

    $_SESSION['waf'][$application]['preferences'][$name] = $value;
  }

  function get_preference($name)
  {
    $waf =& UUWAF::get_instance();
    $application = $waf->title;

    return($_SESSION['waf'][$application]['preferences'][$name]);
  }

  function load_by_id($id) 
  {
    $preference = new Preference;
    $preference->id = $id;
    $preference->_load_by_id();
    return $preference;
  }

}

?>
