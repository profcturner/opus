<?php

require_once("dto/DTO_Preference.class.php");

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

  function load_all($reg_number)
  {
    global $waf;
    $application = $waf->title;

    $pref = new Preference;

    $preferences = $pref->_get_all("where application='$application' and reg_number='$reg_number'");

    // Unwind into the session
    foreach($preferences as $preference)
    {
      //print_r($preference);
      Preference::set_preference($preference->name, unserialize($preference->value));
    }
    //print_r($_SESSION); exit;
  }

  function save_all($reg_number)
  {
    global $waf;
    $application = $waf->title;

    // Nothing to save?
    if(!isset($_SESSION['waf'][$application]['preferences'])) return;

    // Remove what's there
    $pref = new Preference;
    $pref->_remove_where("where application='$application' and reg_number='$reg_number'");

    foreach($_SESSION['waf'][$application]['preferences'] as $name => $value)
    {
      $fields = array();
      $fields['application'] = $application;
      $fields['reg_number'] = $reg_number;
      $fields['name'] = $name;
      $fields['value'] = serialize($value);

      $pref->_insert($fields);
    }
  }

  function set_preference($name, $value)
  {
    global $waf;
    $application = $waf->title;

    $_SESSION['waf'][$application]['preferences'][$name] = $value;
  }

  function get_preference($name)
  {
    global $waf;
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