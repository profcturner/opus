<?php

/**
* Handles the storage of user preferences, possibly in a different database 
* @package OPUS
*/
require_once("dto/DTO_Preference.class.php");
/**
* Handles the storage of user preferences, possibly in a different database
*
* It is important to note the key used to store preferences has to be
* specific to the user across several applications. Therefore the user id
* from OPUS should not be used. 
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

  /**
  * return the preferences for a given user
  *
  * This function does not deposit them into the session, it is used to
  * interrogate the preferences of other uses.
  *
  * @param $reg_number the student or staff number of the user
  * @return an array of preference objects for the user
  */
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

  /**
  * load preferences for a given user into the session
  *
  * Usually called only at initial login, since preferences are then
  * stored in the session until being saved at logout.
  *
  * @param $reg_number the student or staff number of the user
  */
  function load_all($reg_number)
  {
    $waf =& UUWAF::get_instance();

    $preferences = Preference::fetch_all($reg_number);

    // Unwind into the session
    foreach($preferences as $preference)
    {
      Preference::set_preference($preference->name, unserialize($preference->value));
    }
    $waf->log(count($preferences) . " preference values loaded", PEAR_LOG_DEBUG, 'debug');
  }

  /**
  * fetches the system theme, as it was potentially shared by applications.
  *
  * For a time the system theme was dictated by PACE and handled rather inelegantly
  * by not being stored in a "common" application area.
  *
  * @deprecated since PACE and OPUS will part company.
  * @return the system theme for OPUS or "blue" if not set
  */
  function get_system_theme($reg_number)
  {	
	  $preferences = $_SESSION['waf'][$waf->title]['preferences'];

	  $system_theme = $preferences['system_theme'];
	
    if ($system_theme == "")
    {	
    	$system_theme = "blue";
    }
    return $system_theme;
  }

  /**
  * commits preference values held in the session to the database
  *
  * @param $reg_number the staff or student number of the user involved
  */
  function save_all($reg_number)
  {
    require_once("model/User.class.php");
    $waf =& UUWAF::get_instance();
    $application = $waf->title;

    if(empty($reg_number))
    {
      $waf->log("preferences cannot be saved for this user without a reg number", PEAR_LOG_DEBUG, 'debug');
      return;
    }
    // Nothing to save?
    if(!isset($_SESSION['waf'][$application]['preferences'])) return;
    

    // Remove what's there
    $pref = new Preference; 
    $pref->_remove_where("where application='$application' and reg_number='$reg_number'");
    $count = 0;

    foreach($_SESSION['waf'][$application]['preferences'] as $name => $value)
    {
      $fields = array();
      $fields['application'] = $application;
      $fields['reg_number'] = $reg_number;
      $fields['name'] = $name;
      $fields['value'] = serialize($value);

      $pref->_insert($fields);
      $count++;
    }
    $waf->log("$count preference values saved for user $reg_number", PEAR_LOG_DEBUG, 'debug');
  }

  /**
  * sets a preference into the session
  *
  * Note that sessions are not stored in the database until the save_all()
  *
  * @param $name the name or key for the preference
  * @param $value the value of the preference which can be any PHP object
  */  
  function set_preference($name, $value)
  {
    $waf =& UUWAF::get_instance();
    $application = $waf->title;

    $_SESSION['waf'][$application]['preferences'][$name] = $value;
  }

  /**
  * an old function for setting the theme
  *
  * @see get_system_theme()
  * @deprecated see comments on get_system_theme()
  */
  function set_theme($name, $value)
  {
    $waf =& UUWAF::get_instance($config['waf']);

    $_SESSION['waf'][$application]['preferences'][$name] = $value;
  }
  
  /**
  * returns a given preference from the session
  *
  * @param $name the name of the preference to return
  * @return the value of the preference
  */
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
