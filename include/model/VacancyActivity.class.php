<?php

/**
* The model object for vacancies
* @package OPUS
*/
require_once("dto/DTO_Vacancyactivity.class.php");

/**
* The Vacancyactivity model class
*/
class Vacancyactivity extends DTO_Vacancyactivity 
{
  var $vacancy_id = "";   // The vacancy
  var $activity_id = "";  // The linked activity

  static $_field_defs = array(
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
    $vacancyactivity = new Vacancyactivity;
    $vacancyactivity->id = $id;
    $vacancyactivity->_load_by_id();
    return $vacancyactivity;
  }

  function insert($fields) 
  {
    $vacancyactivity = new Vacancyactivity;
    $vacancyactivity->_insert($fields);
  }

  function update($fields) 
  {
    $vacancyactivity = Vacancyactivity::load_by_id($fields[id]);
    $vacancyactivity->_update($fields);
  }

  /**
  * Wasteful
  */
  function exists($id) 
  {
    $vacancyactivity = new Vacancyactivity;
    $vacancyactivity->id = $id;
    return $vacancyactivity->_exists();
  }
  
  /**
  * Wasteful
  */
  function count() 
  {
    $vacancyactivity = new Vacancyactivity;
    return $vacancyactivity->_count();
  }

  function get_all($where_clause="", $order_by="", $page=0)
  {
    $vacancyactivity = new Vacancyactivity;
    
    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $vacancyactivitys = $vacancyactivity->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $vacancyactivitys = $vacancyactivity->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $vacancyactivitys;
  }

  function get_id_and_field($fieldname) 
  {
    $vacancyactivity = new Vacancyactivity;
    $vacancyactivity_array = $vacancyactivity->_get_id_and_field($fieldname);
    unset($vacancyactivity_array[0]);
    return $vacancyactivity_array;
  }


  function remove($id=0) 
  {  
    $vacancyactivity = new Vacancyactivity;
    $vacancyactivity->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {  
    $vacancyactivity = new Vacancyactivity;
    return  $vacancyactivity->_get_fieldnames($include_id); 
  }
  function request_field_values($include_id = false) 
  {
    $fieldnames = Vacancyactivity::get_fields($include_id);
    $nvp_array = array();

    foreach ($fieldnames as $fn) {
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
    }
    return $nvp_array;
  }
}
?>