<?php

/**
* Handles which programmes and administrator has rights over
* @package OPUS
*/
require_once("dto/DTO_ProgrammeAdmin.class.php");
/**
* Handles which programmes and administrator has rights over
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see Admin.class.php
* @see Programme.class.php
* @see Policy.class.php
* @package OPUS
*
*/

class ProgrammeAdmin extends DTO_ProgrammeAdmin 
{
  var $programme_id = 0;    // The id from the programme table
  var $admin_id = 0;     // The id from the user table for the admin
  var $policy_id = 0;    // An override policy id, normally NULL

  static $_field_defs = array(
    'admin_id'=>array('type'=>'lookup', 'object'=>'user', 'value'=>'real_name', 'title'=>'Name', 'var'=>'names'),
    'programme_id'=>array('type'=>'lookup', 'object'=>'programme', 'value'=>'name', 'title'=>'Programme', 'var'=>'programmes', 'header'=>true),
    'policy_id'=>array('type'=>'lookup', 'object'=>'policy', 'value'=>'name', 'title'=>'Policy', 'var'=>'policies', 'header'=>true),
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
    $programmeadmin = new ProgrammeAdmin;
    $programmeadmin->id = $id;
    $programmeadmin->_load_by_id();
    return $programmeadmin;
  }

  function insert($fields) 
  {
    $programmeadmin = new ProgrammeAdmin;
    $programmeadmin->_insert($fields);
  }
  
  function update($fields) 
  {
    $programmeadmin = ProgrammeAdmin::load_by_id($fields[id]);
    $programmeadmin->_update($fields);
  }
  
  /**
  * Wasteful
  */
  function exists($id) 
  {
    $programmeadmin = new ProgrammeAdmin;
    $programmeadmin->id = $id;
    return $programmeadmin->_exists();
  }
  
  /**
  * Wasteful
  */
  function count($where_clause="") 
  {
    $programmeadmin = new ProgrammeAdmin;
    return $programmeadmin->_count($where_clause);
  }

  function get_all($where_clause="", $order_by="ORDER BY priority", $page=0)
  {
    $programmeadmin = new ProgrammeAdmin;
    
    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $programmeadmins = $programmeadmin->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $programmeadmins = $programmeadmin->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $programmeadmins;
  }

  function get_id_and_field($fieldname, $where_clause="") 
  {
    $programmeadmin = new ProgrammeAdmin;
    $programmeadmin_array = $programmeadmin->_get_id_and_field($fieldname, $where_clause);
    return $programmeadmin_array;
  }


  function remove($id=0) 
  {  
    $programmeadmin = new ProgrammeAdmin;
    $programmeadmin->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {  
    $programmeadmin = new ProgrammeAdmin;
    return  $programmeadmin->_get_fieldnames($include_id); 
  }

  function request_field_values($include_id = false) 
  {
    $fieldnames = ProgrammeAdmin::get_fields($include_id);
    $nvp_array = array();

    foreach ($fieldnames as $fn)
    {
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
    }
    return $nvp_array;
  }
}
?>