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
    'cc_on_email'=>array('type'=>'list', 'list'=>array('No'=>'No', 'Yes'=>'Yes'), 'title'=>'CC on emails')
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
    global $waf;
    $phonehome = PhoneHome::load_by_id($fields[id]);

    // Update any templates
    PhoneHome::update_cc_in_automail($fields['cc_on_email']);

    // Update this data now...
    $phonehome->_update($fields);
    if($fields['send_install'])
    {
      // No time like the present!
      PhoneHome::send_install();
      PhoneHome::send_periodic();
    }
  }

  function update_cc_in_automail($cc_on_email)
  {
    // Update the automail template for the CC fields
    require_once("model/Automail.class.php");
    $automail_install = Automail::load_by_lookup("PhoneHome_Install");
    $automail_periodic = Automail::load_by_lookup("PhoneHome_Periodic");
    $user_email = $waf->user['opus']['email'];

    if($cc_on_email)
    {
      // Only update if cc is empty, to allow manual override
      if(!strlen($automail_install->cch))
      {
        $automail_install->cch = $user_email;
      }
      if(!strlen($automail_periodic->cch))
      {
        $automail_periodic->cch = $user_email;
      }
    }
    else
    {
      $automail_install->cch = null;
      $automail_periodic->cch = null;
    }
    $automail_install->_update();
    $automail_periodic->_update();
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
      $message = "phonehome functionality for install is not enabled";
      if($waf->unattended) echo $message;
      $waf->log($message);
      return false;
    }

    require_once("model/Automail.class.php");
    Automail::sendmail("PhoneHome_Install", array());
    $phonehome->timestamp_periodic = $now;
    $phonehome->_update();
  }

  function send_periodic()
  {
    global $waf;
    $now = date("YmdHis");

    $phonehome = PhoneHome::load_by_id(1);
    if($phonehome->send_periodic != 'Yes')
    {
      $message = "phonehome functionality for periodic updates is not enabled";
      if($waf->unattended) echo $message;
      $waf->log($message);
      return false;
    }
    $timestamps = PhoneHome::get_unixtimes();
    if($timestamps['unix_periodic'] && ((time() - $timestamps['unix_periodic']) < 25*24*60*60))
    {
      $message = "it has been less than 25 days since the last statistics were sent, aborting";
      if($waf->unattended) echo $message;
      $waf->log($message);
      return false;
    }

    $mailfields = array();
    require_once("model/User.class.php");
    $mailfields['custom_roots']       = User::count("where user_type='root'");
    $mailfields['custom_admins']      = User::count("where user_type='admin'");
    $mailfields['custom_contacts']    = User::count("where user_type='company'");
    $mailfields['custom_staff']       = User::count("where user_type='staff'");
    $mailfields['custom_supervisors'] = User::count("where user_type='supervisors'");
    $mailfields['custom_students']    = User::count("where user_type='student'");
    require_once("model/Company.class.php");
    $mailfields['custom_companies'] = Company::count();
    require_once("model/Vacancy.class.php");
    $mailfields['custom_vacancies'] = Vacancy::count();

    require_once("model/Automail.class.php");
    Automail::sendmail("PhoneHome_Periodic", $mailfields);
    $phonehome->timestamp_periodic = $now;
    $phonehome->_update();
  }

  function get_unixtimes() 
  {
    $phonehome = new PhoneHome;
    return $phonehome->_get_unixtimes();
  }
}
?>