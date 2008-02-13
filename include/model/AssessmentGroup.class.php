<?php

/**
* The model object for help prompts
* @package OPUS
*/
require_once("dto/DTO_AssessmentGroup.class.php");

/**
* The AssessmentGroup model class
*/
class AssessmentGroup extends DTO_AssessmentGroup 
{
  var $name = "";         // AssessmentGroup name
  var $comments = "";     // Information about the assessment

  static $_field_defs = array(
    'name'=>array('type'=>'text', 'size'=>30, 'maxsize'=>100, 'title'=>'AssessmentGroup', 'header'=>true, 'mandatory'=>true),
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
    $assessmentgroup = new AssessmentGroup;
    $assessmentgroup->id = $id;
    $assessmentgroup->_load_by_id();
    return $assessmentgroup;
  }

  function insert($fields) 
  {
    $assessmentgroup = new AssessmentGroup;
    $assessmentgroup->_insert($fields);
  }
  
  function update($fields) 
  {
    $assessmentgroup = AssessmentGroup::load_by_id($fields[id]);
    $assessmentgroup->_update($fields);
  }
  
  /**
  * Wasteful
  */
  function exists($id) 
  {
    $assessmentgroup = new AssessmentGroup;
    $assessmentgroup->id = $id;
    return $assessmentgroup->_exists();
  }
  
  /**
  * Wasteful
  */
  function count($where_clause="") 
  {
    $assessmentgroup = new AssessmentGroup;
    return $assessmentgroup->_count($where_clause);
  }

  function get_all($where_clause="", $order_by="ORDER BY name", $page=0)
  {
    global $config;
    $assessmentgroup = new AssessmentGroup;

    if ($page <> 0) {
      $start = ($page-1)*$config['opus']['rows_per_page'];
      $limit = $config['opus']['rows_per_page'];
      $assessmentgroups = $assessmentgroup->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $assessmentgroups = $assessmentgroup->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $assessmentgroups;
  }

  function get_id_and_field($fieldname, $where_clause="") 
  {
    $assessmentgroup = new AssessmentGroup;
    $assessmentgroup_array = $assessmentgroup->_get_id_and_field($fieldname, $where_clause);
    unset($assessmentgroup_array[0]);
    return $assessmentgroup_array;
  }


  function remove($id=0) 
  {  
    $assessmentgroup = new AssessmentGroup;
    $assessmentgroup->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {  
    $assessmentgroup = new AssessmentGroup;
    return  $assessmentgroup->_get_fieldnames($include_id); 
  }
  function request_field_values($include_id = false) 
  {
    $fieldnames = AssessmentGroup::get_fields($include_id);
    $nvp_array = array();

    foreach ($fieldnames as $fn) {
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
    }
    return $nvp_array;
  }

  function get_name($id)
  {
    $id = (int) $id; // Security

    $data = AssessmentGroup::get_id_and_field("name","where id='$id'");
    return($data[$id]);
  }
}
?>