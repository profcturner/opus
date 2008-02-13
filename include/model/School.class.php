<?php

/**
* Defines and handles schools
* @package OPUS
*/
require_once("dto/DTO_School.class.php");
/**
* Defines and handles schools
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/

class School extends DTO_School 
{
  var $name = "";        // Name of school
  var $www = "";         // Web Address of School
  var $srs_ident = "";   // SRS Identifier
  var $status = "";      // Status flags
  var $faculty_id = 0;   // ID of faculty this school belongs to

  static $_field_defs = array(
    'name'=>array('type'=>'text', 'size'=>40, 'maxsize'=>200, 'title'=>'Name', 'header'=>true, 'listclass'=>'school_name', 'mandatory'=>true),
    'srs_ident'=>array('type'=>'text', 'size'=>40, 'maxsize'=>60),
    'www'=>array('type'=>'url', 'size'=>60, 'maxsize'=>200, 'title'=>'Web Address'),
    'faculty_id'=>array('type'=>'lookup', 'object'=>'faculty', 'value'=>'name', 'title'=>'Faculty', 'var'=>'faculties'),
    'status'=>array('type'=>'list', 'list'=>array('active', 'archive'))
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
    $school = new School;
    $school->id = $id;
    $school->_load_by_id();
    return $school;
  }

  function insert($fields) 
  {
    $school = new School;
    $school->_insert($fields);
  }
  
  function update($fields) 
  {
    $school = School::load_by_id($fields[id]);
    $school->_update($fields);
  }
  
  /**
  * Wasteful
  */
  function exists($id) 
  {
    $school = new School;
    $school->id = $id;
    return $school->_exists();
  }
  
  /**
  * Wasteful
  */
  function count($where_clause="") 
  {
    $school = new School;
    return $school->_count($where_clause);
  }

  function get_all($where_clause="", $order_by="ORDER BY id", $page=0)
  {
    global $config;
    $school = new School;

    if ($page <> 0) {
      $start = ($page-1)*$config['opus']['rows_per_page'];
      $limit = $config['opus']['rows_per_page'];
      $schools = $school->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $schools = $school->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $schools;
  }

  function get_id_and_field($fieldname, $where_clause="", $order_clause="order by name") 
  {
    $school = new School;
    $school_array = $school->_get_id_and_field($fieldname, $where_clause, $order_clause);
    unset($school_array[0]);
    return $school_array;
  }


  function remove($id=0) 
  {  
    $school = new School;
    $school->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {  
    $school = new School;
    return  $school->_get_fieldnames($include_id); 
  }
  function request_field_values($include_id = false) 
  {
    $fieldnames = School::get_fields($include_id);
    $nvp_array = array();
 
    foreach ($fieldnames as $fn) {
 
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
 
    }

    return $nvp_array;

  }

  function get_name($id)
  {
    $id = (int) $id; // Security

    $data = School::get_id_and_field("name","where id='$id'");
    return($data[$id]);
  }

  function get_faculty_id($id)
  {
    $id = (int) $id; // Security

    $data = School::get_id_and_field("faculty_id","where id='$id'");
    return($data[$id]);
  }
}
?>