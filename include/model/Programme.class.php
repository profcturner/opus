<?php

require_once("pds/dto/DTO_Programme.class.php");

/**
* The achievement business model object is used to interact with the project data transition object
*
**/

class Programme extends DTO_Programme 
{
  /** @var integer The user id of the instance owner. */

  var $user_id = 0;

  /** @var string */

  var $programme = "";

  /** @var date */

  var $date_attained = "";

  /** @var string */

  var $skills_developed = "";

  /** @var string*/

  var $further_details = "";

  /** @var array The definitions for each of the variables in this class/object. */

  var $_field_defs = array
  (
    'achievement'=>array
    (
      'type'=>'text', 
      'size'=>50, 
      'title'=>'Programme', 
      'header'=>true
    ),
    'date_attained'=>array
      (
      'type'=>'date', 
      'inputstyle'=>'popup', 
      'size'=>15, 
      'title'=>'Date Attained', 
      'header'=>false, 
      'listclass'=>'achievement_date_attained'
    ),
    'further_details'=>array
    (
      'type'=>'textarea', 
      'rowsize'=>6, 
      'colsize'=>50, 
      'title'=>'Further Details', 
      'header'=>false
    ),
    'skills_developed'=>array
    (
      'type'=>'textarea', 
      'rowsize'=>6, 
      'colsize'=>50, 
      'title'=>'Skills Developed', 
      'header'=>true, 
      'listclass'=>'achievement_skills_developed'
    )
  );

/**
 * Constructor for the Programme class, this explicitly calls the constructor of the DTO_Programme class
 *
 * <code>
 *  parent::__construct();
 *  global $logger;
 *  $logger->log("Programme construct called");
 *  $logger->log($this);
 * </code>
 * @see DTO_Programme::__construct()
 * 
 *
 */

  function __construct() 
  {
    parent::__construct();
    global $logger;
    $logger->log("Programme construct called");
    $logger->log($this);
  }

  function load_by_id($id) 
  {
    $programme = new Programme;
    $programme->id = $id;
    $programme->_load_by_id();
    return $programme;
  }

  function insert($fields) 
  {
    $programme = new Programme;
    $programme->_insert($fields);
  }
  
  function update($fields) 
  {
    $programme = Programme::load_by_id($fields[id]);
    $programme->_update($fields);
  }
  
  function exists($id) 
  {
    $programme = new Programme;
    $programme->id = $id;
    return $programme->_exists();
  }
  
  function count() 
  {
    $programme = new Programme;
    return $programme->_count();
  }

  function get_all($where_clause="", $order_by="ORDER BY id", $page=0)
  {
    $programme = new Programme;
    
    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $programmes = $programme->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $programmes = $programme->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $programmes;
  }

  function get_id_and_field($fieldname) 
  {
    $programme = new Programme;
    return  $programme->_get_id_and_field($fieldname);
  }


  function remove($id=0) 
  {  
    $programme = new Programme;
    $programme->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {  
    $programme = new Programme;
    return  $programme->_get_fieldnames($include_id); 
  }
  function request_field_values($include_id = false) 
  {
    $fieldnames = Programme::get_fields($include_id);
    $nvp_array = array();
 
    foreach ($fieldnames as $fn) {
 
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
 
    }

    return $nvp_array;

  }
}
?>