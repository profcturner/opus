<?php

/**
* The model object for holding individual results for assessments
* @package OPUS
*/
require_once("dto/DTO_AssessmentResult.class.php");

/**
* The AssessmentResult model class
*/
class AssessmentResult extends DTO_AssessmentResult 
{
  var $regime_id = "";    // The instance of an assessment, as stored as id in AssessmentRegime
  var $assessed_id = "";  // The id from the User table of the assessed item
  var $name = "";         // The name of the variable from AssessmentStructure
  var $contents = "";     // The contents of the result, numeric or text

  // Probably not needed, this is all automatically maintained
  static $_field_defs = array(
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
    $assessmentresult = new AssessmentResult;
    $assessmentresult->id = $id;
    $assessmentresult->_load_by_id();
    return $assessmentresult;
  }

  function insert($fields) 
  {
    $assessmentresult = new AssessmentResult;
    $assessmentresult->_insert($fields);
  }

  function update($fields) 
  {
    $assessmentresult = AssessmentResult::load_by_id($fields[id]);
    $assessmentresult->_update($fields);
  }

  /**
  * Wasteful
  */
  function exists($id) 
  {
    $assessmentresult = new AssessmentResult;
    $assessmentresult->id = $id;
    return $assessmentresult->_exists();
  }

  /**
  * Wasteful
  */
  function count($where_clause="") 
  {
    $assessmentresult = new AssessmentResult;
    return $assessmentresult->_count($where_clause);
  }

  function get_all($where_clause="", $order_by="ORDER BY id", $page=0)
  {
    $assessmentresult = new AssessmentResult;

    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $assessmentresults = $assessmentresult->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $assessmentresults = $assessmentresult->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $assessmentresults;
  }

  function get_id_and_field($fieldname, $where_clause="") 
  {
    $assessmentresult = new AssessmentResult;
    $assessmentresult_array = $assessmentresult->_get_id_and_field($fieldname, $where_clause);
    unset($assessmentresult_array[0]);
    return $assessmentresult_array;
  }


  function remove($id=0) 
  {
    $assessmentresult = new AssessmentResult;
    $assessmentresult->_remove_where("WHERE id=$id");
  }

  function remove_where($where_clause="where id=0")
  {
    $assessmentresult = new AssessmentResult;
    $assessmentresult->_remove_where($where_clause);
  }

  function get_fields($include_id = false) 
  {  
    $assessmentresult = new AssessmentResult;
    return  $assessmentresult->_get_fieldnames($include_id); 
  }

  function load_where($where_clause)
  {
    $assessmentresult = new AssessmentResult;
    return $assessmentresult->_load_where($where_clause);
  }

  function request_field_values($include_id = false) 
  {
    $fieldnames = AssessmentResult::get_fields($include_id);
    $nvp_array = array();

    foreach ($fieldnames as $fn) {
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
    }
    return $nvp_array;
  }
}
?>