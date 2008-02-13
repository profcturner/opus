<?php

/**
* Handles an individual assessment
* @package OPUS
*/
require_once("dto/DTO_Assessment.class.php");
/**
* Handles an individual assessment
*
* Defines the location and name of an individual assessment. The AssessmentStructure class
* defines what variables exist in this object, and the AssessmentRegime class defines how
* this assessment is included in various assessment regimes.
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see AssessmentStructure.class.php
* @see AssessmentRegime.class.php
* @package OPUS
*/
class Assessment extends DTO_Assessment 
{
  var $description = "";         // Unique description for admins
  var $student_description = ""; // Suggested description for students
  var $template_filename = "";   // Path to template the skins the form

  static $_field_defs = array(
    'description'=>array('type'=>'text', 'size'=>40, 'maxsize'=>80, 'title'=>'Admin Description', 'header'=>true, 'listclass'=>'assessment_admin_description', 'mandatory'=>true),
    'student_description'=>array('type'=>'text', 'size'=>40, 'maxsize'=>80, 'title'=>'Student Description', 'header'=>true, 'listclass'=>'assessment_student_description', 'mandatory'=>true),
    'template_filename'=>array('type'=>'text', 'size'=>40, 'maxsize'=>80, 'title'=>'Template', 'header'=>true, 'listclass'=>'assessment_template_name', 'mandatory'=>true)
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
    $assessment = new Assessment;
    $assessment->id = $id;
    $assessment->_load_by_id();
    return $assessment;
  }

  function insert($fields) 
  {
    $assessment = new Assessment;
    $assessment->_insert($fields);
  }
  
  function update($fields) 
  {
    $assessment = Assessment::load_by_id($fields[id]);
    $assessment->_update($fields);
  }
  
  /**
  * Wasteful
  */
  function exists($id) 
  {
    $assessment = new Assessment;
    $assessment->id = $id;
    return $assessment->_exists();
  }
  
  /**
  * Wasteful
  */
  function count($where_clause="") 
  {
    $assessment = new Assessment;
    return $assessment->_count($where_clause);
  }

  function get_all($where_clause="", $order_by="ORDER BY description", $page=0)
  {
    global $config;
    $assessment = new Assessment;

    if ($page <> 0) {
      $start = ($page-1)*$config['opus']['rows_per_page'];
      $limit = $config['opus']['rows_per_page'];
      $assessments = $assessment->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $assessments = $assessment->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $assessments;
  }

  function get_id_and_field($fieldname, $where_clause="") 
  {
    $assessment = new Assessment;
    $assessment_array = $assessment->_get_id_and_field($fieldname, $where_clause);
    unset($assessment_array[0]);
    return $assessment_array;
  }


  function remove($id=0) 
  {  
    $assessment = new Assessment;
    $assessment->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {  
    $assessment = new Assessment;
    return  $assessment->_get_fieldnames($include_id); 
  }
  function request_field_values($include_id = false) 
  {
    $fieldnames = Assessment::get_fields($include_id);
    $nvp_array = array();
 
    foreach ($fieldnames as $fn) {
 
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
 
    }

    return $nvp_array;

  }
}
?>