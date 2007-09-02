<?php

/**
* The model object for vacancies
* @package OPUS
*/
require_once("dto/DTO_Vacancy.class.php");

/**
* The Vacancy model class
*/
class Vacancy extends DTO_Vacancy 
{
  var $name = "";      // Vacancy name
  var $address1 = "";
  var $address2 = "";
  var $address3 = "";
  var $town = "";
  var $locality = "";
  var $country = "";
  var $postcode = "";
  var $www = "";
  var $voice = "";
  var $fax = "";
  var $brief = "";
  var $created = "";
  var $modified = "";
  var $allocation = "";

  static $_field_defs = array(
    'name'=>array('type'=>'text', 'size'=>30, 'maxsize'=>100, 'title'=>'Name','header'=>true),
    'address1'=>array('type'=>'text', 'size'=>40, 'maxsize'=>100, 'title'=>'Address 1'),
    'address2'=>array('type'=>'text', 'size'=>40, 'maxsize'=>100, 'title'=>'Address 2'),
    'address3'=>array('type'=>'text', 'size'=>40, 'maxsize'=>100, 'title'=>'Address 3'),
    'town'=>array('type'=>'text', 'size'=>40, 'maxsize'=>100, 'title'=>'Town'),
    'locality'=>array('type'=>'text', 'size'=>40, 'maxsize'=>100, 'title'=>'Locality', 'header'=>true),
    'country'=>array('type'=>'text', 'size'=>30, 'maxsize'=>100, 'title'=>'Country'),
    'postcode'=>array('type'=>'text', 'size'=>10, 'maxsize'=>20, 'title'=>'Postcode'),
    'www'=>array('type'=>'url', 'size'=>40, 'maxsize'=>80, 'title'=>'Web Address'),
    'voice'=>array('type'=>'text', 'size'=>20, 'maxsize'=>40, 'title'=>'Phone'),
    'fax'=>array('type'=>'text', 'size'=>20, 'maxsize'=>40, 'title'=>'Fax'),
    'allocation'=>array('type'=>'numeric', 'size'=>10, 'title'=>'Space Allocation'),
    'brief'=>array('type'=>'textarea', 'rowsize'=>20, 'colsize'=>60, 'maxsize'=>60000,  'title'=>'Brief')
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
    $vacancy = new Vacancy;
    $vacancy->id = $id;
    $vacancy->_load_by_id();
    return $vacancy;
  }

  function insert($fields) 
  {
    $vacancy = new Vacancy;
    $vacancy->_insert($fields);
  }
  
  function update($fields) 
  {
    $vacancy = Vacancy::load_by_id($fields[id]);
    $vacancy->_update($fields);
  }
  
  /**
  * Wasteful
  */
  function exists($id) 
  {
    $vacancy = new Vacancy;
    $vacancy->id = $id;
    return $vacancy->_exists();
  }
  
  /**
  * Wasteful
  */
  function count() 
  {
    $vacancy = new Vacancy;
    return $vacancy->_count();
  }

  function get_all($where_clause="", $order_by="ORDER BY name, locality", $page=0)
  {
    $vacancy = new Vacancy;
    
    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $vacancys = $vacancy->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $vacancys = $vacancy->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $vacancys;
  }

  function get_id_and_field($fieldname) 
  {
    $vacancy = new Vacancy;
    $vacancy_array = $vacancy->_get_id_and_field($fieldname);
    unset($vacancy_array[0]);
    return $vacancy_array;
  }


  function remove($id=0) 
  {  
    $vacancy = new Vacancy;
    $vacancy->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {  
    $vacancy = new Vacancy;
    return  $vacancy->_get_fieldnames($include_id); 
  }
  function request_field_values($include_id = false) 
  {
    $fieldnames = Vacancy::get_fields($include_id);
    $nvp_array = array();
 
    foreach ($fieldnames as $fn) {
 
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
 
    }

    return $nvp_array;

  }
}
?>