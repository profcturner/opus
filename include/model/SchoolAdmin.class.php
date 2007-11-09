<?php

/**
* Associates Administrators with particular Schools
* @package OPUS
*/
require_once("dto/DTO_SchoolAdmin.class.php");
/**
* Associates Administrators with particular Schools
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see Admin.class.php
* @see School.class.php
* @see Policy.class.php
* @package OPUS
*
*/class SchoolAdmin extends DTO_SchoolAdmin 
{
  var $school_id = 0;    // The id from the school table
  var $admin_id = 0;     // The id from the user table for the admin
  var $policy_id = 0;    // An override policy id, normally NULL

  static $_field_defs = array(
    'admin_id'=>array('type'=>'lookup', 'object'=>'user', 'value'=>'real_name', 'title'=>'Name', 'var'=>'names'),
    'school_id'=>array('type'=>'lookup', 'object'=>'school', 'value'=>'name', 'title'=>'School', 'var'=>'schools', 'header'=>true),
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
    $schooladmin = new SchoolAdmin;
    $schooladmin->id = $id;
    $schooladmin->_load_by_id();
    return $schooladmin;
  }

  function load_where($where_clause) 
  {
    $schooladmin = new SchoolAdmin;
    $schooladmin->_load_where($where_clause);
    return $schooladmin;
  }

  function insert($fields) 
  {
    $schooladmin = new SchoolAdmin;
    $schooladmin->_insert($fields);
  }

  function update($fields) 
  {
    $schooladmin = SchoolAdmin::load_by_id($fields[id]);
    $schooladmin->_update($fields);
  }
  
  /**
  * Wasteful
  */
  function exists($id) 
  {
    $schooladmin = new SchoolAdmin;
    $schooladmin->id = $id;
    return $schooladmin->_exists();
  }
  
  /**
  * Wasteful
  */
  function count() 
  {
    $schooladmin = new SchoolAdmin;
    return $schooladmin->_count();
  }

  function get_all($where_clause="", $order_by="ORDER BY priority", $page=0)
  {
    $schooladmin = new SchoolAdmin;
    
    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $schooladmins = $schooladmin->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $schooladmins = $schooladmin->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $schooladmins;
  }

  function get_id_and_field($fieldname) 
  {
    $schooladmin = new SchoolAdmin;
    $schooladmin_array = $schooladmin->_get_id_and_field($fieldname);
    return $schooladmin_array;
  }


  function remove($id=0) 
  {  
    $schooladmin = new SchoolAdmin;
    $schooladmin->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {  
    $schooladmin = new SchoolAdmin;
    return  $schooladmin->_get_fieldnames($include_id); 
  }

  function request_field_values($include_id = false) 
  {
    $fieldnames = SchoolAdmin::get_fields($include_id);
    $nvp_array = array();

    foreach ($fieldnames as $fn)
    {
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
    }
    return $nvp_array;
  }
}
?>