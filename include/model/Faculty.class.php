<?php

/**
* The model object for Facultys
* @package OPUS
*/
require_once("dto/DTO_Faculty.class.php");

/**
* The Faculty model class
*/
class Faculty extends DTO_Faculty 
{
  var $name = "";        // Name of faculty
  var $www = "";         // Web Address of Faculty
  var $srs_ident = "";   // SRS Identifier
  var $status = "";      // Status flags

  static $_field_defs = array(
    'name'=>array('type'=>'text', 'size'=>40, 'maxsize'=>200, 'title'=>'Name', 'header'=>true, 'listclass'=>'faculty_name'),
    'www'=>array('type'=>'url', 'size'=>60, 'maxsize'=>200, 'title'=>'Web Address'),
    'srs_ident'=>array('type'=>'text', 'size'=>40, 'maxsize'=>60),
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
    $faculty = new Faculty;
    $faculty->id = $id;
    $faculty->_load_by_id();
    return $faculty;
  }

  function insert($fields) 
  {
    $faculty = new Faculty;
    $faculty->_insert($fields);
  }
  
  function update($fields) 
  {
    $faculty = Faculty::load_by_id($fields[id]);
    $faculty->_update($fields);
  }
  
  /**
  * Wasteful
  */
  function exists($id) 
  {
    $faculty = new Faculty;
    $faculty->id = $id;
    return $faculty->_exists();
  }
  
  /**
  * Wasteful
  */
  function count() 
  {
    $faculty = new Faculty;
    return $faculty->_count();
  }

  function get_all($where_clause="", $order_by="ORDER BY id", $page=0)
  {
    $faculty = new Faculty;
    
    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $facultys = $faculty->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $facultys = $faculty->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $facultys;
  }

  function get_id_and_field($fieldname) 
  {
    $faculty = new Faculty;
    $faculty_array = $faculty->_get_id_and_field($fieldname);
    $faculty_array[0] = 'Global';
    return $faculty_array;
  }


  function remove($id=0) 
  {  
    $faculty = new Faculty;
    $faculty->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {  
    $faculty = new Faculty;
    return  $faculty->_get_fieldnames($include_id); 
  }
  function request_field_values($include_id = false) 
  {
    $fieldnames = Faculty::get_fields($include_id);
    $nvp_array = array();
 
    foreach ($fieldnames as $fn) {
 
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
 
    }

    return $nvp_array;

  }
}
?>