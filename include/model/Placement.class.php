<?php

/**
* The model object for placements for jobs
* @package OPUS
*/
require_once("dto/DTO_Placement.class.php");

/**
* The Placement model class
*/
class Placement extends DTO_Placement 
{
  var $position;              // The job title
  var $jobstart;              // When the job starts
  var $jobend;                // When the job ends
  var $salary;                // The salary available
  var $voice;                 // The phone number while on placement
  var $email;                 // The email while on placement
  var $created;               // When the record was created
  var $modified;              // When the record was modified
  var $supervisor_title;      // The supervisor salutation
  var $supervisor_firstname;
  var $supervisor_lastname;
  var $supervisor_email;
  var $supervisor_voice;
  var $company_id;            // The company of the placement
  var $vacancy_id;            // The vacancy placed with
  var $student_id;            // The id, from the student table

  static $_field_defs = array(
    'position'=>array('type'=>'text', 'size'=>30, 'maxsize'=>100, 'title'=>'Job Description','header'=>true),
    //'vacancy_type'=>array('type'=>'lookup', 'object'=>'vacancytype', 'value'=>'name', 'title'=>'Type', 'var'=>'vacancytypes'),
    'jobstart'=>array('type'=>'date', 'inputstyle'=>'popup', 'required'=>'true'),
    'jobend'=>array('type'=>'date', 'inputstyle'=>'popup'),
    'salary'=>array('type'=>'text', 'size'=>6, 'maxsize'=>20),
    'supervisor_title'=>array('type'=>'text', 'size'=>5, 'maxsize'=>100, 'title'=>"Supervisor Title<br /><small>Mr, Dr, etc.</small>"),
    'supervisor_firstname'=>array('type'=>'text', 'size'=>20, 'maxsize'=>100, 'title'=>"Supervisor First name"),
    'supervisor_lastname'=>array('type'=>'text', 'size'=>20, 'maxsize'=>100, 'title'=>"Supervisor Last name"),
    'supervisor_email'=>array('type'=>'email', 'size'=>40, 'maxsize'=>100, 'title'=>"Supervisor Email"),
    'supervisor_voice'=>array('type'=>'text', 'size'=>20, 'maxsize'=>100, 'title'=>"Supervisor Phone")
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
    $placement = new Placement;
    $placement->id = $id;
    $placement->_load_by_id();
    return $placement;
  }

  function insert($fields) 
  {
    // Null some fields if empty
    $fields = Placement::set_empty_to_null($fields);

    $fields['created'] = date("YmdHis");
    $placement = new Placement;
    $placement->_insert($fields);

    // Record student as placed
    $student_fields['placement_status'] = 'Placed';
    $student_fields['id'] = $fields['student_id'];
    require_once('model/Student.class.php');
    Student::update($student_fields);

  }

  function update($fields) 
  {
    // Null some fields if empty
    $fields = Placement::set_empty_to_null($fields);
    $fields['modified'] = date("YmdHis");

    $placement = Placement::load_by_id($fields[id]);
    $placement->_update($fields);
  }

  /**
  * Goes through certain fields and sets them to null if they are "empty"
  */
  function set_empty_to_null($fields)
  {
    $set_to_null = array("created", "modified");
    foreach($set_to_null as $field)
    {
      if(!strlen($fields[$field])) $fields[$field] = null;
    }
    return($fields);
  }

  /**
  * Wasteful
  */
  function exists($id) 
  {
    $placement = new Placement;
    $placement->id = $id;
    return $placement->_exists();
  }
  
  /**
  * Wasteful
  */
  function count($where_clause="") 
  {
    $placement = new Placement;
    return $placement->_count($where_clause);
  }

  function get_all($where_clause="", $order_by="ORDER BY id", $page=0)
  {
    $placement = new Placement;
    
    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $placements = $placement->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $placements = $placement->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $placements;
  }

  function get_id_and_field($fieldname) 
  {
    $placement = new Placement;
    $placement_array = $placement->_get_id_and_field($fieldname);
    unset($placement_array[0]);
    return $placement_array;
  }


  function remove($id=0) 
  {  
    $placement = new Placement;
    $placement->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {  
    $placement = new Placement;
    return  $placement->_get_fieldnames($include_id); 
  }
  function request_field_values($include_id = false) 
  {
    $fieldnames = Placement::get_fields($include_id);
    $nvp_array = array();
 
    foreach ($fieldnames as $fn) {
 
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
 
    }

    return $nvp_array;

  }
}
?>