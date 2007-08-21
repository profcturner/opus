<?php

require_once("pds/dto/DTO_Transcript.class.php");

/**
* The achievement business model object is used to interact with the project data transition object
*
**/

class Transcript extends DTO_Transcript 
{
  /** @var integer The user id of the instance owner. */

  var $user_id = 0;

  /** @var string */

  var $transcript = "";


  /** @var array The definitions for each of the variables in this class/object. */

  var $_field_defs = array
  (
    'transcript'=>array
      (
      'type'=>'textarea', 
      'rowsize'=>15, 
      'colsize'=>60, 
      'title'=>'Transcript', 
      'header'=>True
    )
  );

/**
 * Constructor for the Transcript class, this explicitly calls the constructor of the DTO_Transcript class
 *
 * <code>
 *  parent::__construct();
 *  global $logger;
 *  $logger->log("Transcript construct called");
 *  $logger->log($this);
 * </code>
 * @see DTO_Transcript::__construct()
 * 
 *
 */

  function __construct() 
  {
    parent::__construct();
    global $logger;
    $logger->log("Transcript construct called");
    $logger->log($this);
  }

  function load_by_id($id) 
  {
    $transcript = new Transcript;
    $transcript->id = $id;
    $transcript->_load_by_id();
    return $transcript;
  }

/**
 * This loads the student's transcript based on the user_id of the current user.
 *
 *
 */

  function load_by_user_id($user_id) 
  {
    $transcript = new Transcript;
    $transcript->user_id = $user_id;
    $transcript->_load_by_field("user_id");
    return $transcript;
  }

  function insert($fields) 
  {
    $transcript = new Transcript;
    $transcript->_insert($fields);
  }
  
  function update($fields) 
  {
    $transcript = Transcript::load_by_id($fields[id]);
    $transcript->_update($fields);
  }
  
  function exists($id) 
  {
    $transcript = new Transcript;
    $transcript->id = $id;
    return $transcript->_exists();
  }
  
  function count() 
  {
    $transcript = new Transcript;
    return $transcript->_count();
  }

  function get_all($where_clause="", $order_by="ORDER BY id", $page=0)
  {
    $transcript = new Transcript;
    
    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $transcripts = $transcript->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $transcripts = $transcript->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $transcripts;
  }

  function get_id_and_field($fieldname) 
  {
    $transcript = new Transcript;
    return  $transcript->_get_id_and_field($fieldname);
  }


  function remove($id=0) 
  {  
    $transcript = new Transcript;
    $transcript->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {  
    $transcript = new Transcript;
    return  $transcript->_get_fieldnames($include_id); 
  }
  function request_field_values($include_id = false) 
  {
    $fieldnames = Transcript::get_fields($include_id);
    $nvp_array = array();
 
    foreach ($fieldnames as $fn) {
 
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
 
    }

    return $nvp_array;

  }
}
?>