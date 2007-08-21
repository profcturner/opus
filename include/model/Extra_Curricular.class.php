<?php

/**
* The work experience business model object is used to interact with the project data transition object
*
**/

require_once("pds/dto/DTO_Extra_Curricular.class.php");

class Extra_Curricular extends DTO_Extra_Curricular 
{
  var $user_id = 0;
  var $date = "";
  var $title = "";
  var $activities = "";
  var $skills_developed = "";
  var $further_details = "";

  var $_field_defs = array
  (
    'date'=>array('type'=>'flexidate', 'inputstyle'=>'popup', 'size'=>15, 'title'=>'Date', 'header'=>true),
    'title'=>array('type'=>'text', 'size'=>50,  'title'=>'Title', 'header'=>true),
    'activities'=>array('type'=>'textarea', 'rowsize'=>6, 'colsize'=>50, 'title'=>'Activities', 'header'=>false),
    'further_details'=>array('type'=>'textarea', 'rowsize'=>6, 'colsize'=>50, 'title'=>'Further Details', 'header'=>false),
    'skills_developed'=>array('type'=>'textarea', 'rowsize'=>6, 'colsize'=>50, 'title'=>'Skills Developed', 'header'=>true)
  );

  function __construct() 
  {
    parent::__construct();
    global $logger;
    $logger->log("Work Experience construct called");
    $logger->log($this);
  }

  function load_by_id($id) 
  {
    $extra_curricular = new Extra_Curricular;
    $extra_curricular->id = $id;
    $extra_curricular->_load_by_id();
    return $extra_curricular;
  }

  function insert($fields) 
  {
    $extra_curricular = new Extra_Curricular;
    $extra_curricular->_insert($fields);
  }
  
  function update($fields) 
  {
    $extra_curricular = Extra_Curricular::load_by_id($fields[id]);
    $extra_curricular->_update($fields);
  }
  
  function exists($id) 
  {
    $extra_curricular = new Extra_Curricular;
    $extra_curricular->id = $id;
    return $extra_curricular->_exists();
  }
  
  function count() 
  {
    $extra_curricular = new Extra_Curricular;
    return $extra_curricular->_count();
  }

  function get_all($where_clause="", $order_by="ORDER BY id", $page=0)
  {
    $extra_curricular = new Extra_Curricular;
    
    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $extra_curriculars = $extra_curricular->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $extra_curriculars = $extra_curricular->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $extra_curriculars;
  }

  function get_id_and_field($fieldname) 
  {
    $extra_curricular = new Extra_Curricular;
    return  $extra_curricular->_get_id_and_field($fieldname);
  }


  function remove($id=0) 
  {  
    $extra_curricular = new Extra_Curricular;
    $extra_curricular->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {  
    $extra_curricular = new Extra_Curricular;
    return  $extra_curricular->_get_fieldnames($include_id); 
  }
  function request_field_values($include_id = false) 
  {
    $fieldnames = Extra_Curricular::get_fields($include_id);
    $nvp_array = array();
 
    foreach ($fieldnames as $fn) {
 
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
 
    }

    return $nvp_array;

  }
}
?>