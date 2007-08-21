<?php

require_once("pds/dto/DTO_Programme_Team.class.php");

/**
* The achievement business model object is used to interact with the project data transition object
*
**/

class Programme_Team extends DTO_Programme_Team 
{
  /** @var integer The user id of the instance owner. */

  var $user_id = 0;

  /** @var string */

  var $programme_team = "";

  
  

/**
 * Constructor for the Programme_Team class, this explicitly calls the constructor of the DTO_Programme class
 *
 * <code>
 *  parent::__construct();
 *  global $logger;
 *  $logger->log("Programme_Team construct called");
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
    $logger->log("Programme_Team construct called");
    $logger->log($this);
  }
  
  function load_by_id($id) 
  {
    $programme_team = new Programme_Team;
    $programme_team->id = $id;
    $programme_team->_load_by_id();
    return $programme_team;
  }

  function load_by_user_id($user_id) 
  {
    $programme_team = new Programme_Team;
    $programme_team->user_id = $user_id;
    $programme_team->_load_by_field("user_id");
    return $programme_team;
  }


  function insert($fields) 
  {
    $programme_team = new Programme_Team;
    $programme_team->_insert($fields);
  }
  
  function update($fields) 
  {
    $programme_team = Programme_Team::load_by_id($fields[id]);
    $programme_team->_update($fields);
  }
  
  function exists($id) 
  {
    $programme_team = new Programme_Team;
    $programme_team->id = $id;
    return $programme_team->_exists();
  }
  
  function count() 
  {
    $programme_team = new Programme_Team;
    return $programme_team->_count();
  }

  function get_all($where_clause="", $order_by="ORDER BY id", $page=0)
  {
    $programme_team = new Programme_Team;
    
    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $programmes = $programme_team->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $programmes = $programme_team->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $programmes;
  }

  function get_id_and_field($fieldname) 
  {
    $programme_team = new Programme_Team;
    return  $programme_team->_get_id_and_field($fieldname);
  }


  function remove($id=0) 
  {  
    $programme_team = new Programme_Team;
    $programme_team->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {  
    $programme_team = new Programme_Team;
    return  $programme_team->_get_fieldnames($include_id); 
  }
  function request_field_values($include_id = false) 
  {
    $fieldnames = Programme_Team::get_fields($include_id);
    $nvp_array = array();
 
    foreach ($fieldnames as $fn) {
 
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
 
    }

    return $nvp_array;

  }
}
?>