<?php

/**
* The model object for help prompts
* @package OPUS
*/
require_once("dto/DTO_AssessmentRegime.class.php");

/**
* The AssessmentRegime model class
*/
class AssessmentRegime extends DTO_AssessmentRegime 
{
  var $group_id;                  // The assessment group this belongs to
  var $assessment_id;             // The assessment id from the assessment table
  var $weighting = 0;             // How much weighting this assessment has in the regime
  var $start = "";                // Start time in format MMDD
  var $end = "";                  // End time in format MMDD
  var $year = 0;                  // Year of assessment, 0= placement year, -1 year before etc
  var $student_description = "";  // Brief description for the student  
  var $outcomes = "";             // Discussion of learning outcomes
  var $assessor = "";             // Assessor

  static $_field_defs = array(
    'assessment_id'=>array('type'=>'lookup', 'object'=>'assessment', 'value'=>'description', 'title'=>'Assessment', 'size'=>20, 'var'=>'assessments'),
    'student_description'=>array('type'=>'text', 'size'=>30, 'maxsize'=>100, 'title'=>'Student Description', 'header'=>true),
    'weighting'=>array('type'=>'text', 'size'=>4, 'maxsize'=>4, 'header'=>true),
    'assessor'=>array('type'=>'list', 'list'=>array('academic','industrial','student','other'), 'header'=>true),
    'year'=>array('type'=>'text', 'size'=>4, 'maxsize'=>4),
    'start'=>array('type'=>'text', 'size'=>4, 'maxsize'=>4),
    'end'=>array('type'=>'text', 'size'=>4, 'maxsize'=>4),
    'outcomes'=>array('type'=>'textarea', 'rowsize'=>10, 'colsize'=>50)
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
    $assessmentregime = new AssessmentRegime;
    $assessmentregime->id = $id;
    $assessmentregime->_load_by_id();
    return $assessmentregime;
  }

  function insert($fields) 
  {
    $assessmentregime = new AssessmentRegime;
    $assessmentregime->_insert($fields);
  }
  
  function update($fields) 
  {
    $assessmentregime = AssessmentRegime::load_by_id($fields[id]);
    $assessmentregime->_update($fields);
  }
  
  /**
  * Wasteful
  */
  function exists($id) 
  {
    $assessmentregime = new AssessmentRegime;
    $assessmentregime->id = $id;
    return $assessmentregime->_exists();
  }
  
  /**
  * Wasteful
  */
  function count() 
  {
    $assessmentregime = new AssessmentRegime;
    return $assessmentregime->_count();
  }

  function get_all($where_clause="", $order_by="ORDER BY student_description", $page=0)
  {
    $assessmentregime = new AssessmentRegime;
    
    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $assessmentregimes = $assessmentregime->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $assessmentregimes = $assessmentregime->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $assessmentregimes;
  }

  /**
  * custom function for sorting arrays of regime items
  *
  * this function is intended to be called by usort
  *
  * @param AssessmentRegime $regime_item1 the first item
  * @param AssessmentRegime $regime_item2 the second item
  * @return -1 if the first item is less than the second, 1 otherwise
  */
  function assessment_date_compare($regime_item1, $regime_item2)
  {
    global $config;
    $yearstart = $config['opus']['yearstart'];
    if(empty($yearstart)) $yearstart="0930";

    $year1 = $regime_item1->year;
    $year2 = $regime_item2->year;

    $end1 = $regime_item1->end;
    $end2 = $regime_item2->end;

    // Calculate how far this is from the start of the academic
    // year (using a crude estimate in days).
    if($end1 == 0) return -1;
    if($end2 == 0) return 1;

    // Dates are MMDD
    // e.g. 1001 first visit, 0131 final visit

    if($end1 < $yearstart) $year1++;
    if($end2 < $yearstart) $year2++;

    if($year2 > $year1) return -1;
    if($year1 < $year2) return 1;
    return($end1 - $end2);
  }


  function get_id_and_field($fieldname) 
  {
    $assessmentregime = new AssessmentRegime;
    $assessmentregime_array = $assessmentregime->_get_id_and_field($fieldname);
    unset($assessmentregime_array[0]);
    return $assessmentregime_array;
  }


  function remove($id=0) 
  {  
    $assessmentregime = new AssessmentRegime;
    $assessmentregime->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {  
    $assessmentregime = new AssessmentRegime;
    return  $assessmentregime->_get_fieldnames($include_id); 
  }
  function request_field_values($include_id = false) 
  {
    $fieldnames = AssessmentRegime::get_fields($include_id);
    $nvp_array = array();
 
    foreach ($fieldnames as $fn) {
 
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
 
    }

    return $nvp_array;

  }
}
?>