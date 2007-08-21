<?php

/**
* The work experience business model object is used to interact with the project data transition object
*
**/

require_once("pds/dto/DTO_Work_Experience.class.php");

class Work_Experience extends DTO_Work_Experience 
{
  var $user_id = 0;
  var $from = "";
  var $to = "";
  var $mode_of_work = "";
  var $employer = "";
  var $job_title = "";
  var $responsibilities = "";
  var $skills_developed = "";
  var $further_details = "";

  var $_field_defs = array
  (
    'from'=>array('type'=>'flexidate', 'inputstyle'=>'popup', 'size'=>15, 'title'=>'From', 'header'=>true),
    'to'=>array('type'=>'flexidate', 'inputstyle'=>'popup', 'size'=>15, 'title'=>'To', 'header'=>true),
    'mode_of_work'=>array('type'=>'list', 'list'=>array('Full-Time', 'Part-Time', 'Contract', 'Casual'), 'header'=>true),
    'employer'=>array('type'=>'text', 'size'=>50,  'title'=>'Employer', 'header'=>true),
    'job_title'=>array('type'=>'text', 'size'=>50,  'title'=>'Job Title', 'header'=>true),
    'responsibilities'=>array('type'=>'textarea', 'rowsize'=>6, 'colsize'=>50, 'title'=>'Responsibilities', 'header'=>false),
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
    $work_exp = new Work_Experience;
    $work_exp->id = $id;
    $work_exp->_load_by_id();
    return $work_exp;
  }

  function insert($fields) 
  {
    $work_exp = new Work_Experience;
    $work_exp->_insert($fields);
  }
  
  function update($fields) 
  {
    $work_exp = Work_Experience::load_by_id($fields[id]);
    $work_exp->_update($fields);
  }
  
  function exists($id) 
  {
    $work_exp = new Work_Experience;
    $work_exp->id = $id;
    return $work_exp->_exists();
  }
  
  function count() 
  {
    $work_exp = new Work_Experience;
    return $work_exp->_count();
  }

  function get_all($where_clause="", $order_by="ORDER BY id", $page=0)
  {
    $work_exp = new Work_Experience;
    
    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $work_exps = $work_exp->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $work_exps = $work_exp->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $work_exps;
  }

  function get_id_and_field($fieldname) 
  {
    $work_exp = new Work_Experience;
    return  $work_exp->_get_id_and_field($fieldname);
  }


  function remove($id=0) 
  {  
    $work_exp = new Work_Experience;
    $work_exp->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {  
    $work_exp = new Work_Experience;
    return  $work_exp->_get_fieldnames($include_id); 
  }
  function request_field_values($include_id = false) 
  {
    $fieldnames = Work_Experience::get_fields($include_id);
    $nvp_array = array();
 
    foreach ($fieldnames as $fn) {
 
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
 
    }

    return $nvp_array;

  }
}
?>