<?php

/**
* The model object for vacancies
* @package OPUS
*/
require_once("dto/DTO_VacancyActivity.class.php");

/**
* The VacancyActivity model class
*/
class VacancyActivity extends DTO_VacancyActivity 
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
    $vacancyactivity = new VacancyActivity;
    $vacancyactivity->id = $id;
    $vacancyactivity->_load_by_id();
    return $vacancyactivity;
  }

  function insert($fields) 
  {
    $vacancyactivity = new VacancyActivity;
    $vacancyactivity->_insert($fields);
  }

  function update($fields) 
  {
    $vacancyactivity = VacancyActivity::load_by_id($fields[id]);
    $vacancyactivity->_update($fields);
  }

  /**
  * Wasteful
  */
  function exists($id) 
  {
    $vacancyactivity = new VacancyActivity;
    $vacancyactivity->id = $id;
    return $vacancyactivity->_exists();
  }
  
  /**
  * Wasteful
  */
  function count() 
  {
    $vacancyactivity = new VacancyActivity;
    return $vacancyactivity->_count();
  }

  function get_all($where_clause="", $order_by="", $page=0)
  {
    $vacancyactivity = new VacancyActivity;
    
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
    $vacancyactivity = new VacancyActivity;
    $vacancyactivity_array = $vacancyactivity->_get_id_and_field($fieldname);
    unset($vacancyactivity_array[0]);
    return $vacancyactivity_array;
  }


  function remove($id=0) 
  {  
    $vacancyactivity = new VacancyActivity;
    $vacancyactivity->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {  
    $vacancyactivity = new VacancyActivity;
    return  $vacancyactivity->_get_fieldnames($include_id); 
  }
  function request_field_values($include_id = false) 
  {
    $fieldnames = VacancyActivity::get_fields($include_id);
    $nvp_array = array();

    foreach ($fieldnames as $fn) {
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
    }
    return $nvp_array;
  }
}
?>