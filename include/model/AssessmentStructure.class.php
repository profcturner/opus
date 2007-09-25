<?php

/**
* The model object for AssessmentStructures
* @package OPUS
*/
require_once("dto/DTO_AssessmentStructure.class.php");

/**
* The AssessmentStructure model class
*/
class AssessmentStructure extends DTO_AssessmentStructure 
{
  var $assessment_id = 0;        // The assessment this variable belongs to
  var $human = "";               // A human readable description of the variable
  var $name = "";                // The variable name
  var $type = "";                // The type of field
  var $min = 0;                  // Minimum value (if any)
  var $max = 0;                  // Maximum value (if any)
  var $weighting = 0;            // Weighting of score of this variable in assessment
  var $options = "";             // Whether the item is compulsory or not
  var $varorder = "";            // Order in which variables are examined

  static $_field_defs = array(
    'human'=>array('type'=>'text', 'size'=>40, 'maxsize'=>80, 'title'=>'Description', 'header'=>true, 'listclass'=>'assessmentstructure_description'),
    'name'=>array('type'=>'text', 'size'=>40, 'maxsize'=>80, 'title'=>'Variable Name', 'header'=>true),
    'type'=>array('type'=>'list', 'list'=>array('textual','numeric','checkbox','assesseddate'), 'header'=>true),
    'min'=>array('type'=>'text', 'size'=>3, 'title'=>'Minimum Value / Characters'),
    'max'=>array('type'=>'text', 'size'=>3, 'title'=>'Maximum Value / Characters'),
    'weighting'=>array('type'=>'text', 'size'=>3, 'title'=>'Weighting'),
    'options'=>array('type'=>'list', 'list'=>array('compulsory','optional'))
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

  function move_up($assessment_id, $id)
  {
    $assessmentstructure = AssessmentStructure::load_by_id($id);
    $varorder = $assessmentstructure->varorder;
    $assessmentstructure->_move_up($assessment_id, $varorder);
  }

  function move_down($assessment_id, $id)
  {
    $assessmentstructure = AssessmentStructure::load_by_id($id);
    $varorder = $assessmentstructure->varorder;
    $assessmentstructure->_move_down($assessment_id, $varorder);
  }


  function load_by_id($id) 
  {
    $assessmentstructure = new AssessmentStructure;
    $assessmentstructure->id = $id;
    $assessmentstructure->_load_by_id();
    return $assessmentstructure;
  }

  function insert($fields) 
  {
    $assessmentstructure = new AssessmentStructure;
    $assessmentstructure->_insert($fields);
  }
  
  function update($fields) 
  {
    $assessmentstructure = AssessmentStructure::load_by_id($fields[id]);
    $assessmentstructure->_update($fields);
  }
  
  /**
  * Wasteful
  */
  function exists($id) 
  {
    $assessmentstructure = new AssessmentStructure;
    $assessmentstructure->id = $id;
    return $assessmentstructure->_exists();
  }
  
  /**
  * Wasteful
  */
  function count() 
  {
    $assessmentstructure = new AssessmentStructure;
    return $assessmentstructure->_count();
  }

  function get_all($where_clause="", $order_by="ORDER BY varorder", $page=0)
  {
    $assessmentstructure = new AssessmentStructure;
    
    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $assessmentstructures = $assessmentstructure->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $assessmentstructures = $assessmentstructure->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $assessmentstructures;
  }

  function get_id_and_field($fieldname) 
  {
    $assessmentstructure = new AssessmentStructure;
    $assessmentstructure_array = $assessmentstructure->_get_id_and_field($fieldname);
    return $assessmentstructure_array;
  }


  function remove($id=0) 
  {  
    $assessmentstructure = new AssessmentStructure;
    $assessmentstructure->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {  
    $assessmentstructure = new AssessmentStructure;
    return  $assessmentstructure->_get_fieldnames($include_id); 
  }
  function request_field_values($include_id = false) 
  {
    $fieldnames = AssessmentStructure::get_fields($include_id);
    $nvp_array = array();
 
    foreach ($fieldnames as $fn) {
 
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
 
    }

    return $nvp_array;

  }
}
?>