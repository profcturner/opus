<?php

/**
* The model object for Programmes
* @package OPUS
*/
require_once("dto/DTO_Programme.class.php");

/**
* The Programme model class
*/
class Programme extends DTO_Programme 
{
  var $name = "";        // Name of programme
  var $www = "";         // Web Address of Programme
  var $srs_ident = "";   // SRS Identifier
  var $status = "";      // Status flags
  var $school_id = "";   // School Id that runs the course

  static $_field_defs = array(
    'name'=>array('type'=>'text', 'size'=>40, 'maxsize'=>200, 'title'=>'Name', 'header'=>true, 'listclass'=>'programme_name'),
    'www'=>array('type'=>'url', 'size'=>60, 'maxsize'=>200, 'title'=>'Web Address'),
    'srs_ident'=>array('type'=>'text', 'size'=>10, 'maxsize'=>10, 'header'=>true, 'title'=>'Code'),
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

  function get_name($id)
  {
    $id = (int) $id; // Security

    $programme = new Programme;
    $data = $programme->_get_id_and_description("where id='$id'");
    return($data[$id]);
  }

  function load_by_id($id) 
  {
    $programme = new Programme;
    $programme->id = $id;
    $programme->_load_by_id();
    return $programme;
  }

  function insert($fields) 
  {
    $programme = new Programme;
    $programme->_insert($fields);
  }
  
  function update($fields) 
  {
    $programme = Programme::load_by_id($fields[id]);
    $programme->_update($fields);
  }
  
  /**
  * Wasteful
  */
  function exists($id) 
  {
    $programme = new Programme;
    $programme->id = $id;
    return $programme->_exists();
  }
  
  /**
  * Wasteful
  */
  function count() 
  {
    $programme = new Programme;
    return $programme->_count();
  }

  function get_all($where_clause="", $order_by="ORDER BY id", $page=0)
  {
    $programme = new Programme;
    
    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $programmes = $programme->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $programmes = $programme->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $programmes;
  }

  function get_id_and_description($where_clause="", $order_clause="order by name")
  {
    $programme = new Programme;
    $programme_array = $programme->_get_id_and_description($where_clause, $order_clause);
    return $programme_array;
  }

  function get_id_and_field($fieldname) 
  {
    $programme = new Programme;
    $programme_array = $programme->_get_id_and_field($fieldname);
    unset($programme_array[0]);
    return $programme_array;
  }

  function remove($id=0) 
  {
    $programme = new Programme;
    $programme->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {
    $programme = new Programme;
    return  $programme->_get_fieldnames($include_id); 
  }

  function request_field_values($include_id = false) 
  {
    $fieldnames = Programme::get_fields($include_id);
    $nvp_array = array();

    foreach ($fieldnames as $fn)
    {
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
    }
    return $nvp_array;
  }
}
?>