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

  function load_by_placement_id($id=0)
  {
    $supervisor = new Supervisor;
    $supervisor->_load_by_user_id($id);
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
}

?>