<?php

/**
* The model object for Assessments
* @package OPUS
*/
require_once("dto/DTO_Assessment.class.php");

/**
* The Assessment model class
*/
class Assessment extends DTO_Assessment 
{
  var $description = "";         // Unique description for admins
  var $student_description = ""; // Suggested description for students
  var $template_filename = "";   // Path to template the skins the form

  static $_field_defs = array(
    'description'=>array('type'=>'text', 'size'=>40, 'maxsize'=>80, 'title'=>'Admin Description', 'header'=>true, 'listclass'=>'assessment_admin_description'),
    'student_description'=>array('type'=>'text', 'size'=>40, 'maxsize'=>80, 'title'=>'Student Description', 'header'=>true, 'listclass'=>'assessment_student_description'),
    'template_filename'=>array('type'=>'text', 'size'=>40, 'maxsize'=>80, 'title'=>'Template', 'header'=>true, 'listclass'=>'assessment_template_name')
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
  function count() 
  {
    $assessment = new Assessment;
    return $assessment->_count();
  }

  function get_all($where_clause="", $order_by="ORDER BY description", $page=0)
  {
    $assessment = new Assessment;
    
    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $assessments = $assessment->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $assessments = $assessment->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $assessments;
  }

  function get_id_and_field($fieldname) 
  {
    $assessment = new Assessment;
    $assessment_array = $assessment->_get_id_and_field($fieldname);
    $assessment_array[0] = 'Global';
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