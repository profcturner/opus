<?php

/**
* The model object for help prompts
* @package OPUS
*/
require_once("dto/DTO_Language.class.php");

/**
* The Language model class
*/
class Language extends DTO_Language 
{
  var $name = "";      // Language name

  static $_field_defs = array(
    'name'=>array('type'=>'text', 'size'=>30, 'maxsize'=>100, 'title'=>'Lookup')     );

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
    $language = new Language;
    $language->id = $id;
    $language->_load_by_id();
    return $language;
  }

  function insert($fields) 
  {
    $language = new Language;
    $language->_insert($fields);
  }
  
  function update($fields) 
  {
    $language = Language::load_by_id($fields[id]);
    $language->_update($fields);
  }
  
  /**
  * Wasteful
  */
  function exists($id) 
  {
    $language = new Language;
    $language->id = $id;
    return $language->_exists();
  }
  
  /**
  * Wasteful
  */
  function count() 
  {
    $language = new Language;
    return $language->_count();
  }

  function get_all($where_clause="", $order_by="ORDER BY id", $page=0)
  {
    $language = new Language;
    
    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $languages = $language->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $languages = $language->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $languages;
  }

  function get_id_and_field($fieldname) 
  {
    $language = new Language;
    $language_array = $language->_get_id_and_field($fieldname);
    unset($language_array[0]);
    return $language_array;
  }


  function remove($id=0) 
  {  
    $language = new Language;
    $language->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {  
    $language = new Language;
    return  $language->_get_fieldnames($include_id); 
  }
  function request_field_values($include_id = false) 
  {
    $fieldnames = Language::get_fields($include_id);
    $nvp_array = array();
 
    foreach ($fieldnames as $fn) {
 
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
 
    }

    return $nvp_array;

  }
}
?>