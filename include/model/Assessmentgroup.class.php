<?php

/**
* The model object for help prompts
* @package OPUS
*/
require_once("dto/DTO_Assessmentgroup.class.php");

/**
* The Assessmentgroup model class
*/
class Assessmentgroup extends DTO_Assessmentgroup 
{
  var $name = "";         // Assessmentgroup name
  var $comments = "";     // Information about the assessment

  static $_field_defs = array(
    'name'=>array('type'=>'text', 'size'=>30, 'maxsize'=>100, 'title'=>'Assessmentgroup', 'header'=>true),
    'comments'=>array('type'=>'textarea', 'rowsize'=>10, 'colsize'=>50)
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
    $assessmentgroup = new Assessmentgroup;
    $assessmentgroup->id = $id;
    $assessmentgroup->_load_by_id();
    return $assessmentgroup;
  }

  function insert($fields) 
  {
    $assessmentgroup = new Assessmentgroup;
    $assessmentgroup->_insert($fields);
  }
  
  function update($fields) 
  {
    $assessmentgroup = Assessmentgroup::load_by_id($fields[id]);
    $assessmentgroup->_update($fields);
  }
  
  /**
  * Wasteful
  */
  function exists($id) 
  {
    $assessmentgroup = new Assessmentgroup;
    $assessmentgroup->id = $id;
    return $assessmentgroup->_exists();
  }
  
  /**
  * Wasteful
  */
  function count() 
  {
    $assessmentgroup = new Assessmentgroup;
    return $assessmentgroup->_count();
  }

  function get_all($where_clause="", $order_by="ORDER BY name", $page=0)
  {
    $assessmentgroup = new Assessmentgroup;
    
    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $assessmentgroups = $assessmentgroup->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $assessmentgroups = $assessmentgroup->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $assessmentgroups;
  }

  function get_id_and_field($fieldname) 
  {
    $assessmentgroup = new Assessmentgroup;
    $assessmentgroup_array = $assessmentgroup->_get_id_and_field($fieldname);
    unset($assessmentgroup_array[0]);
    return $assessmentgroup_array;
  }


  function remove($id=0) 
  {  
    $assessmentgroup = new Assessmentgroup;
    $assessmentgroup->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {  
    $assessmentgroup = new Assessmentgroup;
    return  $assessmentgroup->_get_fieldnames($include_id); 
  }
  function request_field_values($include_id = false) 
  {
    $fieldnames = Assessmentgroup::get_fields($include_id);
    $nvp_array = array();
 
    foreach ($fieldnames as $fn) {
 
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
 
    }

    return $nvp_array;

  }
}
?>