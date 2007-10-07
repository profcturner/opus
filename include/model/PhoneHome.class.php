<?php

/**
* This class handles the (optional) sending of non confidential information
* to the University of Ulster to help support our open source activities
*
* @package OPUS
*/
require_once("dto/DTO_PhoneHome.class.php");

/**
* The PhoneHome class
*/
class PhoneHome extends DTO_PhoneHome 
{
  var $send_install = "";       // Are we allowed to notify on new installation?
  var $send_periodic = "";      // Are we allowed to send periodic stats?
  var $timestamp_install = "";  // Last install timestamp
  var $timestamp_periodic = ""; // Last periodic report
  var $admin_id = 0;            // User id of last super admin to make choices
  var $cc_on_email = 0;         // Has the admin elected to be CC'd on any email

  // The questions to ask of the admin
  static $_field_defs = array(
    'send_install'=>array('type'=>'list', 'list'=>array('Ask'=>'Ask Later', 'Yes'=>'Yes', 'No'=>'No'), 'title'=>'Send an email on installation'),
    'send_periodic'=>array('type'=>'list', 'list'=>array('Ask'=>'Ask Later', 'Yes'=>'Yes', 'No'=>'No'), 'title'=>'Send an email every month'),
    'cc_on_email'=>array('type'=>'list', 'list'=>array('No', 'Yes'), 'title'=>'CC on emails')
  );

  function __construct() 
  {
    parent::__construct('default');
  }

  /**
  * returns the statically defined field definitions
  */
  function get_field_defs()
  {
    return(self::$_field_defs);
  }

  function load_by_id($id) 
  {
    $id = 1;
    $phonehome = new PhoneHome;
    $phonehome->id = $id;
    $phonehome->_load_by_id();
    return $phonehome;
  }

  function insert($fields) 
  {
    $phonehome = new PhoneHome;
    $phonehome->_insert($fields);
  }
  
  function update($fields) 
  {
    $phonehome = PhoneHome::load_by_id($fields[id]);
    $phonehome->_update($fields);
  }

  function exists($id) 
  {
    $phonehome = new PhoneHome;
    $phonehome->id = $id;
    return $phonehome->_exists();
  }

  function get_fields($include_id = false) 
  {
    $phonehome = new PhoneHome;
    return  $phonehome->_get_fieldnames($include_id); 
  }

  function request_field_values($include_id = false) 
  {
    $fieldnames = PhoneHome::get_fields($include_id);
    $nvp_array = array();

    foreach ($fieldnames as $fn)
    {
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
    }
    return $nvp_array;
  }

  /**
  * checks if we should still ask about PhoneHome functionality
  *
  * @return true if so, false otherwise
  */
  function ask_later()
  {
    $phonehome = PhoneHome::load_by_id(1);
    if($phonehome->send_install == 'Ask' || $phonehome->send_periodic == 'Ask')
    {
      return true;
    }
    else
    {
      return false;
    }
  }

  function send_install()
  {
    global $waf;
    $now = date("YmdHis");

    $phonehome = PhoneHome::load_by_id(1);
    if($phonehome->send_install != 'Yes')
    {
      $waf->log("phonehome functionality for install is not enabled");
      return false;
    }

    require_once("model/Automail.class.php");
    Automail::sendmail("PhoneHome_Install", array());
    $phonehome->timestamp_install = $now;
    $phonehome->_update();
  }

  function send_periodic()
  {
  }
}
?>