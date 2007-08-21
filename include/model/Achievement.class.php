<?php

require_once("pds/dto/DTO_Achievement.class.php");

/**
* The achievement business model object is used to interact with the project data transition object
*
**/

class Achievement extends DTO_Achievement 
{
  /** @var integer The user id of the instance owner. */

  var $user_id = 0;

  /** @var string */

  var $achievement = "";

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
      'title'=>'Achievement', 
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
 * Constructor for the Achievement class, this explicitly calls the constructor of the DTO_Achievement class
 *
 * <code>
 *  parent::__construct();
 *  global $logger;
 *  $logger->log("Achievement construct called");
 *  $logger->log($this);
 * </code>
 * @see DTO_Achievement::__construct()
 * 
 *
 */

  function __construct() 
  {
    parent::__construct();
    global $logger;
    $logger->log("Achievement construct called");
    $logger->log($this);
  }

  function load_by_id($id) 
  {
    $achievement = new Achievement;
    $achievement->id = $id;
    $achievement->_load_by_id();
    return $achievement;
  }

  function insert($fields) 
  {
    $achievement = new Achievement;
    $achievement->_insert($fields);
  }
  
  function update($fields) 
  {
    $achievement = Achievement::load_by_id($fields[id]);
    $achievement->_update($fields);
  }
  
  function exists($id) 
  {
    $achievement = new Achievement;
    $achievement->id = $id;
    return $achievement->_exists();
  }
  
  function count() 
  {
    $achievement = new Achievement;
    return $achievement->_count();
  }

  function get_all($where_clause="", $order_by="ORDER BY id", $page=0)
  {
    $achievement = new Achievement;
    
    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $achievements = $achievement->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $achievements = $achievement->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $achievements;
  }

  function get_id_and_field($fieldname) 
  {
    $achievement = new Achievement;
    return  $achievement->_get_id_and_field($fieldname);
  }


  function remove($id=0) 
  {  
    $achievement = new Achievement;
    $achievement->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {  
    $achievement = new Achievement;
    return  $achievement->_get_fieldnames($include_id); 
  }
  function request_field_values($include_id = false) 
  {
    $fieldnames = Achievement::get_fields($include_id);
    $nvp_array = array();
 
    foreach ($fieldnames as $fn) {
 
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
 
    }

    return $nvp_array;

  }
}
?>