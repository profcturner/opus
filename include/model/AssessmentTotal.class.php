<?php

/**
* The model object for holding totals for assessments
* @package OPUS
*/
require_once("dto/DTO_AssessmentTotal.class.php");

/**
* The AssessmentTotal model class
*/
class AssessmentTotal extends DTO_AssessmentTotal 
{
  var $regime_id = "";    // The instance of an assessment, as stored as id in AssessmentRegime
  var $assessed_id = "";  // The id from the User table of the assessed item
  var $assessor_id = "";  // The id from the User table of the assessor
  var $comments = "";     // Overall comments, not used yet (ever?)
  var $mark = 0;          // The mark for the assessment
  var $outof = 0;         // The maximum mark possible (can vary between students)
  var $percentage = 0;    // The calculated percentage
  var $created = 0;       // The assessment creation time
  var $modified = 0;      // Any modification of the assessment is timestamped here
  var $assessed = 0;      // The time the assessment was carried out (user supplied)

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
    $assessmenttotal = new AssessmentTotal;
    $assessmenttotal->id = $id;
    $assessmenttotal->_load_by_id();
    return $assessmenttotal;
  }

  function insert($fields) 
  {
    $assessmenttotal = new AssessmentTotal;
    $assessmenttotal->_insert($fields);
  }
  
  function update($fields) 
  {
    $assessmenttotal = AssessmentTotal::load_by_id($fields[id]);
    $assessmenttotal->_update($fields);
  }
  
  /**
  * Wasteful
  */
  function exists($id) 
  {
    $assessmenttotal = new AssessmentTotal;
    $assessmenttotal->id = $id;
    return $assessmenttotal->_exists();
  }
  
  /**
  * Wasteful
  */
  function count($where_clause="") 
  {
    $assessmenttotal = new AssessmentTotal;
    return $assessmenttotal->_count($where_clause);
  }

  function get_all($where_clause="", $order_by="ORDER BY id", $page=0)
  {
    $assessmenttotal = new AssessmentTotal;
    
    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $assessmenttotals = $assessmenttotal->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $assessmenttotals = $assessmenttotal->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $assessmenttotals;
  }

  function get_id_and_field($fieldname, $where_clause="") 
  {
    $assessmenttotal = new AssessmentTotal;
    $assessmenttotal_array = $assessmenttotal->_get_id_and_field($fieldname, $where_clause);
    unset($assessmenttotal_array[0]);
    return $assessmenttotal_array;
  }


  function remove($id=0) 
  {  
    $assessmenttotal = new AssessmentTotal;
    $assessmenttotal->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {  
    $assessmenttotal = new AssessmentTotal;
    return  $assessmenttotal->_get_fieldnames($include_id); 
  }

  function load_where($where_clause)
  {
    $assessmenttotal = new AssessmentTotal;
    return $assessmenttotal->_load_where($where_clause);
  }

  function request_field_values($include_id = false) 
  {
    $fieldnames = AssessmentTotal::get_fields($include_id);
    $nvp_array = array();

    foreach ($fieldnames as $fn) {
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
    }
    return $nvp_array;
  }
}
?>