<?php

/**
* The conference business model object is used to interact with the project data transition object
*
**/

require_once("pds/dto/DTO_Conference.class.php");

class Conference extends DTO_Conference 
{
  var $user_id = 0;
  var $date = "";
  var $location = "";
  var $name = "";
  var $description = "";
  var $skills_developed = "";
  var $further_details = "";

  var $_field_defs = array(
    
    'date'=>array('type'=>'date', 'inputstyle'=>'popup', 'size'=>15, 'title'=>'Date', 'header'=>true),
    'location'=>array('type'=>'text', 'size'=>50, 'title'=>'Location', 'header'=>true),
    'name'=>array('type'=>'text', 'size'=>50, 'title'=>'Name', 'header'=>true),
    'description'=>array('type'=>'textarea', 'rowsize'=>6, 'colsize'=>50, 'title'=>'Description', 'header'=>true),
    'further_details'=>array('type'=>'textarea', 'rowsize'=>6, 'colsize'=>50, 'title'=>'Further Details', 'header'=>false),
    'skills_developed'=>array('type'=>'textarea', 'rowsize'=>6, 'colsize'=>50, 'title'=>'Skills Developed', 'header'=>true, 'listclass'=>'conference_skills_developed')

    
    );

  function __construct() 
  {
    parent::__construct();
    global $logger;
    $logger->log("Conference construct called");
    $logger->log($this);
  }

  function load_by_id($id) 
  {
    $conference = new Conference;
    $conference->id = $id;
    $conference->_load_by_id();
    return $conference;
  }

  function insert($fields) 
  {
    $conference = new Conference;
    $conference->_insert($fields);
  }
  
  function update($fields) 
  {
    $conference = Conference::load_by_id($fields[id]);
    $conference->_update($fields);
  }
  
  function exists($id) 
  {
    $conference = new Conference;
    $conference->id = $id;
    return $conference->_exists();
  }
  
  function count() 
  {
    $conference = new Conference;
    return $conference->_count();
  }

  function get_all($where_clause="", $order_by="ORDER BY id", $page=0)
  {
    $conference = new Conference;
    
    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $conferences = $conference->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $conferences = $conference->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $conferences;
  }

  function get_id_and_field($fieldname) 
  {
    $conference = new Conference;
    return  $conference->_get_id_and_field($fieldname);
  }


  function remove($id=0) 
  {  
    $conference = new Conference;
    $conference->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {  
    $conference = new Conference;
    return  $conference->_get_fieldnames($include_id); 
  }
  function request_field_values($include_id = false) 
  {
    $fieldnames = Conference::get_fields($include_id);
    $nvp_array = array();
 
    foreach ($fieldnames as $fn) {
 
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
 
    }

    return $nvp_array;

  }
}
?>