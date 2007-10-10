<?php

/**
* The model object for linking AssessmentGroups with Programmes
* @package OPUS
*/
require_once("dto/DTO_AssessmentGroupProgramme.class.php");

/**
* The AssessmentGroupProgramme model class
*/
class AssessmentGroupProgramme extends DTO_AssessmentGroupProgramme 
{
  var $group_id = 0;     // The id from the assessmentgroup table
  var $startyear = 0;   // Year the programme commenced on this group
  var $endyear = 0;     // Year the programme finished on this group
  var $programme_id = 0;  // The id for the programme

  function __construct() 
  {
    parent::__construct('default');
  }

  static $_field_defs = array
  (
    'group_id'=>array('type'=>'lookup', 'object'=>'AssessmentGroup', 'value'=>'name', 'title'=>'Assessment Group', 'size'=>20, 'var'=>'assessment_groups', 'header'=>true),
    'startyear'=>array('type'=>'text', 'size'=>8, 'title'=>'Start Year', 'header'=>true),
    'endyear'=>array('type'=>'text', 'size'=>8, 'title'=>'End Year', 'header'=>true)
  );


  /**
  * returns the statically defined field definitions
  */
  function get_field_defs()
  {
    return(self::$_field_defs);
  }

  function load_by_id($id) 
  {
    $assessmentgroupprogramme = new AssessmentGroupProgramme;
    $assessmentgroupprogramme->id = $id;
    $assessmentgroupprogramme->_load_by_id();
    return $assessmentgroupprogramme;
  }

  function insert($fields) 
  {
    //if($fields['$startyear'] == 0) $fields['startyear']=null;
    //if($fields['$endyear'] == 0) $fields['endyear']=null;

    $assessmentgroupprogramme = new AssessmentGroupProgramme;
    $assessmentgroupprogramme->_insert($fields);
  }

  function update($fields) 
  {
    //if($fields['$startyear'] == 0) $fields['startyear']=null;
    //if($fields['$endyear'] == 0) $fields['endyear']=null;

    $assessmentgroupprogramme = AssessmentGroupProgramme::load_by_id($fields[id]);
    $assessmentgroupprogramme->_update($fields);
  }
  
  /**
  * Wasteful
  */
  function exists($id) 
  {
    $assessmentgroupprogramme = new AssessmentGroupProgramme;
    $assessmentgroupprogramme->id = $id;
    return $assessmentgroupprogramme->_exists();
  }
  
  /**
  * Wasteful
  */
  function count() 
  {
    $assessmentgroupprogramme = new AssessmentGroupProgramme;
    return $assessmentgroupprogramme->_count();
  }

  function get_all($where_clause="", $order_by="ORDER BY programme_id, startyear, endyear", $page=0)
  {
    $assessmentgroupprogramme = new AssessmentGroupProgramme;
    
    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $assessmentgroupprogrammes = $assessmentgroupprogramme->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $assessmentgroupprogrammes = $assessmentgroupprogramme->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $assessmentgroupprogrammes;
  }

  function get_id_and_field($fieldname) 
  {
    $assessmentgroupprogramme = new AssessmentGroupProgramme;
    $assessmentgroupprogramme_array = $assessmentgroupprogramme->_get_id_and_field($fieldname);
    return $assessmentgroupprogramme_array;
  }

  function remove_by_group($group_id=0) 
  {
    $assessmentgroupprogramme = new AssessmentGroupProgramme;
    $assessmentgroupprogramme->_remove_where("WHERE group_id=$group_id");
  }

  function remove($id=0) 
  {
    $assessmentgroupprogramme = new AssessmentGroupProgramme;
    $assessmentgroupprogramme->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {
    $assessmentgroupprogramme = new AssessmentGroupProgramme;
    return  $assessmentgroupprogramme->_get_fieldnames($include_id); 
  }

  function load_where($where_clause)
  {
    $assessmentgroupprogramme = new AssessmentGroupProgramme;
    return $assessmentgroupprogramme->_load_where($where_clause);
  }


  function request_field_values($include_id = false) 
  {
    $fieldnames = AssessmentGroupProgramme::get_fields($include_id);
    $nvp_array = array();

    foreach ($fieldnames as $fn)
    {
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
    }
    return $nvp_array;
  }
}
?>