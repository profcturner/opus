<?php

/**
* The qualification business model object is used to interact with the project data transition object
*
**/

require_once("dto/DTO_Qualification.class.php");

class Qualification extends DTO_Qualification 
{
  var $user_id = 0;
  var $date_attained = "";
  var $institution = "";
  var $awarding_body = "";
  var $qualification = "";
  var $grade_mark = "";
  var $subjects = "";
  var $further_details = "";
  var $skills_developed = "";

  var $_field_defs = array(
    
    'qualification'=>array('type'=>'text', 'size'=>50, 'title'=>'Qualification', 'header'=>true, 'listclass'=>'qualification_qualification'),
    'subjects'=>array('type'=>'textarea', 'rowsize'=>4, 'colsize'=>50, 'maxsize'=>300, 'title'=>'Subjects', 'header'=>true),
    'grade_mark'=>array('type'=>'text', 'size'=>50,  'title'=>'Grade/Mark', 'header'=>true, 'listclass'=>'qualification_grade_mark'),
    'date_attained'=>array('type'=>'date', 'inputstyle'=>'popup', 'size'=>15, 'title'=>'Date Attained', 'header'=>false, 'listclass'=>'qualification_date_attained'),
    'institution'=>array('type'=>'text', 'size'=>50,  'title'=>'Institution', 'header'=>true, 'listclass'=>'qualification_institution'),
    'awarding_body'=>array('type'=>'text', 'size'=>50,  'title'=>'Awarding Body', 'header'=>true, 'listclass'=>'qualification_awarding_body'),
    'further_details'=>array('type'=>'textarea', 'rowsize'=>6, 'colsize'=>50, 'title'=>'Further Details', 'header'=>false),
    'skills_developed'=>array('type'=>'textarea', 'rowsize'=>6, 'colsize'=>50, 'title'=>'Skills Developed', 'header'=>true, 'listclass'=>'qualification_skills_developed')

    
    );

  function __construct() 
  {
    parent::__construct('default');

  }

  function load_by_id($id) 
  {
    $qual = new Qualification;
    $qual->id = $id;
    $qual->_load_by_id();
    return $qual;
  }

  function insert($fields) 
  {
    $qual = new Qualification;
    $qual->_insert($fields);
  }
  
  function update($fields) 
  {
    $qual = Qualification::load_by_id($fields[id]);
    $qual->_update($fields);
  }
  
  function exists($id) 
  {
    $qual = new Qualification;
    $qual->id = $id;
    return $qual->_exists();
  }
  
  function count() 
  {
    $qual = new Qualification;
    return $qual->_count();
  }

  function get_all($where_clause="", $order_by="ORDER BY id", $page=0)
  {
    $qual = new Qualification;
    
    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $quals = $qual->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $quals = $qual->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $quals;
  }

  function get_id_and_field($fieldname) 
  {
    $qual = new Qualification;
    return  $qual->_get_id_and_field($fieldname);
  }


  function remove($id=0) 
  {  
    $qual = new Qualification;
    $qual->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {  
    $qual = new Qualification;
    return  $qual->_get_fieldnames($include_id); 
  }
  function request_field_values($include_id = false) 
  {
    $fieldnames = Qualification::get_fields($include_id);
    $nvp_array = array();
 
    foreach ($fieldnames as $fn) {
 
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
 
    }

    return $nvp_array;

  }
}
?>