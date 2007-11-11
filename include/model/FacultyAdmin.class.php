<?php

/**
* Associates Administrators with particular Faculties
* @package OPUS
*/
require_once("dto/DTO_FacultyAdmin.class.php");
/**
* Associates Administrators with particular Faculties
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see Admin.class.php
* @see Faculty.class.php
* @see Policy.class.php
* @package OPUS
*
*/

class FacultyAdmin extends DTO_FacultyAdmin 
{
  var $faculty_id = 0;    // The id from the faculty table
  var $admin_id = 0;     // The id from the user table for the admin
  var $policy_id = 0;    // An override policy id, normally NULL

  static $_field_defs = array(
    'admin_id'=>array('type'=>'lookup', 'object'=>'user', 'value'=>'real_name', 'title'=>'Name', 'var'=>'names'),
    'faculty_id'=>array('type'=>'lookup', 'object'=>'faculty', 'value'=>'name', 'title'=>'Faculty', 'var'=>'facultys', 'header'=>true),
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
    $facultyadmin = new FacultyAdmin;
    $facultyadmin->id = $id;
    $facultyadmin->_load_by_id();
    return $facultyadmin;
  }

  function insert($fields) 
  {
    $facultyadmin = new FacultyAdmin;
    $facultyadmin->_insert($fields);
  }
  
  function update($fields) 
  {
    $facultyadmin = FacultyAdmin::load_by_id($fields[id]);
    $facultyadmin->_update($fields);
  }
  
  /**
  * Wasteful
  */
  function exists($id) 
  {
    $facultyadmin = new FacultyAdmin;
    $facultyadmin->id = $id;
    return $facultyadmin->_exists();
  }
  
  /**
  * Wasteful
  */
  function count($where_clause="") 
  {
    $facultyadmin = new FacultyAdmin;
    return $facultyadmin->_count($where_clause);
  }

  function get_all($where_clause="", $order_by="ORDER BY priority", $page=0)
  {
    $facultyadmin = new FacultyAdmin;
    
    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $facultyadmins = $facultyadmin->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $facultyadmins = $facultyadmin->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $facultyadmins;
  }

  function get_id_and_field($fieldname, $where_clause="") 
  {
    $facultyadmin = new FacultyAdmin;
    $facultyadmin_array = $facultyadmin->_get_id_and_field($fieldname, $where_clause);
    return $facultyadmin_array;
  }


  function remove($id=0) 
  {  
    $facultyadmin = new FacultyAdmin;
    $facultyadmin->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {  
    $facultyadmin = new FacultyAdmin;
    return  $facultyadmin->_get_fieldnames($include_id); 
  }

  function request_field_values($include_id = false) 
  {
    $fieldnames = FacultyAdmin::get_fields($include_id);
    $nvp_array = array();

    foreach ($fieldnames as $fn)
    {
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
    }
    return $nvp_array;
  }
}
?>