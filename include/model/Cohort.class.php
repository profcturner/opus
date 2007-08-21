<?php

require_once("pds/dto/DTO_Cohort.class.php");
require_once("pds/model/Cohort_Student_Link.class.php");

/**
* The achievement business model object is used to interact with the project data transition object
*
**/

class Cohort extends DTO_Cohort 
{

  /** @var integer The user id of the instance owner. */

  var $user_id = 0;

  /** @var string */

  var $name = "";

  /** @var string */

  var $role = "";

  /** @var string */

  var $other_role = "";

  /** @var string */

  var $description = "";

  /** @var string*/

  var $notice = "";

  /** @var integer*/

  var $skills = "";

  /** @var integer*/

  var $resources = "";

  /** @var integer*/

  var $files = "";

  /** @var integer*/

  var $calendars = "";

  /** @var array The definitions for each of the variables in this class/object. */

  var $_field_defs = array
  (
    'name'=>array
    (
      'type'=>'text', 
      'size'=>50, 
      'title'=>'Name', 
      'header'=>true
    ),
    'role'=>array
    (
      'type'=>'list', 
      'list'=>array('Programme Director', 'Adviser of Study', 'Placement Tutor', 'Careers Adviser', 'Superviser', 'Other'),
      'title'=>'Role',  
      'header'=>true
    ),
    'other_role'=>array
    (
      'type'=>'text', 
      'size'=>50, 
      'title'=>'Other Role', 
      'header'=>true
    ),
    'description'=>array
    (
      'type'=>'textarea', 
      'rowsize'=>5,
      'colsize'=>60, 
      'title'=>'Description', 
      'header'=>true
    ),
    'notice'=>array
    (
      'type'=>'textarea', 
      'rowsize'=>5,
      'colsize'=>60, 
      'title'=>'Notice', 
      'header'=>true
    ),
    'skills'=>array
    (
      'type'=>'integer', 
      'size'=>4, 
      'title'=>'Skills', 
      'header'=>true
    ),
    'resources'=>array
    (
      'type'=>'integer', 
      'size'=>4, 
      'title'=>'Resources', 
      'header'=>true
    ),
    'files'=>array
    (
      'type'=>'integer', 
      'size'=>4, 
      'title'=>'Files', 
      'header'=>true
    ),
    'calendars'=>array
    (
      'type'=>'integer', 
      'size'=>4, 
      'title'=>'Calendars', 
      'header'=>true
    )
  );

/**
 * Constructor for the Cohort class, this explicitly calls the constructor of the DTO_Cohort class
 *
 * <code>
 *  parent::__construct();
 *  global $logger;
 *  $logger->log("Cohort construct called");
 *  $logger->log($this);
 * </code>
 * @see DTO_Cohort::__construct()
 * 
 *
 */

  function __construct() 
  {
    parent::__construct();
    global $logger;
    $logger->log("Cohort construct called");
    $logger->log($this);
  }
  
/**
 * This returns an array of cohort objects, for which the user is a member.
 *
 * @return array $cohorts An array of cohort objects.
 * @param integer $student_id The user_id of the student member of a cohort.
 * 
 *
 */
  function get_my_cohorts($student_id) 
  { 
    $cohort = new Cohort;
    $cohorts = $cohort->_get_my_cohorts($student_id);
    return $cohorts;
  }

  function load_by_id($id) 
  {
    $cohort = new Cohort;
    $cohort->id = $id;
    $cohort->_load_by_id();
    return $cohort;
  }

  function insert($fields) 
  {
    $cohort = new Cohort;
    $cohort->_insert($fields);
  }
  
  function update($fields) 
  {
    $cohort = Cohort::load_by_id($fields[id]);
    $cohort->_update($fields);
  }
  
  function exists($id) 
  {
    $cohort = new Cohort;
    $cohort->id = $id;
    return $cohort->_exists();
  }
  
  function count() 
  {
    $cohort = new Cohort;
    return $cohort->_count();
  }

  function get_all($where_clause="", $order_by="ORDER BY id", $page=0)
  {
    $cohort = new Cohort;
    
    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $cohorts = $cohort->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $cohorts = $cohort->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $cohorts;
  }

  function get_id_and_field($fieldname) 
  {
    $cohort = new Cohort;
    return  $cohort->_get_id_and_field($fieldname);
  }


  function remove($id=0) 
  {  
    $cohort = new Cohort;
    $cohort->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {  
    $cohort = new Cohort;
    return  $cohort->_get_fieldnames($include_id); 
  }
  function request_field_values($include_id = false) 
  {
    $fieldnames = Cohort::get_fields($include_id);
    $nvp_array = array();
 
    foreach ($fieldnames as $fn) {
 
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
 
    }

    return $nvp_array;

  }
}
?>