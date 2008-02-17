<?php

/**
* Defines and handles workplace supervisors
*
* @package OPUS
*/
require_once("dto/DTO_Supervisor.class.php");
/**
* Defines and handles workplace supervisors
*
* This is a bit of a special case, since supervisors don't have a table of their
* own, but are instead drawn from the user and placement tables.
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/

class Supervisor extends DTO_Supervisor
{
  var $real_name;
  var $user_id;
  var $placement;
  var $student;
  var $company;
  var $vacancy;


  static $_field_defs = array
  (
    'real_name'=>array('type'=>'text','size'=>30, 'header'=>true, title=>'Name'),
    'company_id'=>array('type'=>'lookup','size'=>30, 'header'=>true, title=>'Company'),
    'vacancy_id'=>array('type'=>'lookup','size'=>30, 'header'=>true, title=>'Vacancy'),
    'student_id'=>array('type'=>'lookup','size'=>30, 'header'=>true, title=>'Student')
  );

  function __construct() 
  {
    parent::__construct('default');
  }

  function get_field_defs()
  {
    return self::$_field_defs;
  }

  function load_by_user_id($id=0)
  {
    $supervisor = new Supervisor;
    $supervisor->_load_by_user_id($id);
    return($supervisor);
  }

  function load_by_placement_id($id=0, $halt_on_error = true)
  {
    $supervisor = new Supervisor;
    $supervisor->_load_by_placement_id($id, $halt_on_error);
    return($supervisor);
  }

  function load_by_username($username)
  {
    $supervisor = new Supervisor;
    $supervisor->_load_by_username($username);
    return($supervisor);
  }

  function count($where_clause)
  {
    $supervisor = new Supervisor;
    return($supervisor->_count($where_clause));
  }

  /**
  * inserts data about a new supervisor into the User table
  */
  function insert($fields) 
  {
    require_once("model/User.class.php");

    $user_fields['user_type'] = 'supervisor';
    $user_id = User::insert($fields);
  }

  /**
  * updates data about a supervisor
  */
  function update($fields)
  {
    $fields['id'] = $fields['user_id'];
    unset($fields['user_id']);

    User::update($fields);
  }


  function get_all($where_clause="", $order_by="ORDER BY lastname", $page=0) 
  {
    global $config;
    $supervisor = new Supervisor;

    if ($page <> 0) 
    {
        $start = ($page-1)*$config['opus']['rows_per_page'];
        $limit = $config['opus']['rows_per_page'];
        $supervisors = $supervisor->_get_all($where_clause, $order_by, $start, $limit);
    }
    else
    {
        $supervisors = $supervisor->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $supervisors;
  }

  /**
  * Keeps details for the supervisor account in sync with the details for the placement
  *
  * @param int $placement_id the id from the placement table
  * @param array $fields an associative array of fields passed into the placement insert / update function
  * @see Placement.class.php
  */
  function update_from_placement($placement_id, $fields)
  {
    global $waf;
    $placement_id = (int) $placement_id;

    // Mapping to user table
    $user_fields = array();

    $user_fields['salutation'] = $fields['supervisor_title'];
    $user_fields['firstname'] = $fields['supervisor_firstname'];
    $user_fields['lastname'] = $fields['supervisor_lastname'];
    $user_fields['email'] = $fields['supervisor_email'];
    $user_fields['username'] = "supervisor_" . $placement_id;
    $user_fields['user_type'] = "supervisor";

    // Try to load any existing supervisor
    $supervisor = Supervisor::load_by_placement_id($placement_id, false);

    if(!$supervisor->user_id)
    {
      // Currently no user, do we have enough to proceed?
      if(strlen($user_fields['email']) && strlen($user_fields['email']))
      {
        $waf->log("making new supervisor account " . $user_fields['username']);
        Supervisor::insert($user_fields);
      }
    }
    else
    {
      $user_fields['id'] = $supervisor->user_id;
      Supervisor::update($user_fields);
      // Ok, so the user already exists, has the email changed to a non null address?
      if(strlen($user_fields['email']) && $user_fields['email'] != $supervisor->email)
      {
        // If so, send a new password
        User::reset_password($supervisor->user_id, true); // true overrides security for non admins here
      }
    }
  }
}

?>