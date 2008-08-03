<?php

/**
* The model object for defining alternative assessors for an assessment
* @package OPUS
*/
require_once("dto/DTO_AssessorOther.class.php");

/**
* The AssessorOther model class
*/
class AssessorOther extends DTO_AssessorOther
{
  var $regime_id = "";    // The instance of an assessment, as stored as id in AssessmentRegime
  var $assessed_id = "";  // The id from the User table of the assessed user
  var $assessor_id = "";  // The id from the User table of the assessing user

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
    $assessorother = new AssessorOther;
    $assessorother->id = $id;
    $assessorother->_load_by_id();
    return $assessorother;
  }

  function insert($fields) 
  {
    $assessorother = new AssessorOther;
    $assessorother->_insert($fields);
  }

  function update($fields) 
  {
    $assessorother = AssessorOther::load_by_id($fields[id]);
    $assessorother->_update($fields);
  }

  /**
  * Wasteful
  */
  function exists($id) 
  {
    $assessorother = new AssessorOther;
    $assessorother->id = $id;
    return $assessorother->_exists();
  }

  /**
  * Wasteful
  */
  function count($where_clause="") 
  {
    $assessorother = new AssessorOther;
    return $assessorother->_count($where_clause);
  }

  function get_all($where_clause="", $order_by="ORDER BY id", $page=0)
  {
    global $config;
    $assessorother = new AssessorOther;

    if ($page <> 0) {
      $start = ($page-1)*$config['opus']['rows_per_page'];
      $limit = $config['opus']['rows_per_page'];
      $assessorothers = $assessorother->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $assessorothers = $assessorother->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $assessorothers;
  }
  
  function get_all_by_assessor($assessor_id)
  {
    $assessor_id = (int) $assessor_id;
    
    // Start off by getting all the assessments for this assessor
    $assessments = AssessorOther::get_all("where assessor_id = $assessor_id");
    
    require_once("model/AssessmentCombined.class.php");
    require_once("model/Student.class.php");
    // Now augment each one
    $augmented_assessments = array();
    foreach($assessments as $assessment)
    {
      // Try to load information about the assessment
      $assessment_combined = new AssessmentCombined($assessment->regime_id, $assessment->assessed_id, $assessment->assessor_id);
      
      // Augment assessed information
      $assessment->assessed_name = $assessment_combined->assessed_name;
      $assessment->placement_year = Student::get_placement_year($assessment->assessed_id);
      $assessment->punctuality = 'now';
      if($assessment_combined->early) $assessment->punctuality = 'early';
      if($assessment_combined->late) $assessment->punctuality = 'late';      
      if($assessment_combined->assessment_results)
      {
        $assessment->percentage = $assessment_combined->assessment_results['percentage'];
      }
      else
      {
        $assessment->percentage = '--';
      }
      array_push($augmented_assessments, $assessment);
    }
    // Sort the array
    usort($augmented_assessments, array("AssessorOther", "assessment_augmented_compare"));
    
    return($augmented_assessments);
  }
  
  function assessment_augmented_compare($aug_ass1, $aug_ass2)
  {
    if($aug_ass1->placement_year < $aug_ass2->placement_year) return 1;
    if($aug_ass1->placement_year > $aug_ass2->placement_year) return -1;
    
    return(-(strcasecmp($aug_ass1->assessed_name, $aug_ass2->assessed_name)));
  }

  function get_id_and_field($fieldname, $where_clause="") 
  {
    $assessorother = new AssessorOther;
    $assessorother_array = $assessorother->_get_id_and_field($fieldname, $where_clause);
    unset($assessorother_array[0]);
    return $assessorother_array;
  }


  function remove($id=0) 
  {
    $assessorother = new AssessorOther;
    $assessorother->_remove_where("WHERE id=$id");
  }

  function remove_where($where_clause="where id=0")
  {
    $assessorother = new AssessorOther;
    $assessorother->_remove_where($where_clause);
  }

  function get_fields($include_id = false) 
  {  
    $assessorother = new AssessorOther;
    return  $assessorother->_get_fieldnames($include_id); 
  }

  function load_where($where_clause)
  {
    $assessorother = new AssessorOther;
    $assessorother->_load_where($where_clause);
    return($assessorother);
  }

  function request_field_values($include_id = false) 
  {
    $fieldnames = AssessorOther::get_fields($include_id);
    $nvp_array = array();

    foreach ($fieldnames as $fn) {
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
    }
    return $nvp_array;
  }
}
?>