<?php

/**
* Languages supported by OPUS
* @package OPUS
*/
require_once("dto/DTO_Language.class.php");
/**
* Languages supported by OPUS
*
* This is still an evolving issue, with version 4 about 95% of the work for localization is
* complete, but there are still some outstanding problems which should be address in the
* 4.x lifecycle.
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/

class Language extends DTO_Language 
{
  var $name = "";      // Language name
  var $ident = "";     // Standard identifier (e.g. en for english)

  static $_field_defs = array(
    'name'=>array('type'=>'text', 'size'=>30, 'maxsize'=>100, 'title'=>'Language', 'header'=>true, 'mandatory'=>true),
    'ident'=>array('type'=>'text', 'size'=>10, 'maxsize'=>10, 'title'=>'Identifier', 'header'=>true, 'mandatory'=>true),
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


  public static function load_by_id($id) 
  {
    $language = new Language;
    $language->id = $id;
    $language->_load_by_id();
    return $language;
  }

  public static function insert($fields) 
  {
    $language = new Language;
    return($language->_insert($fields));
  }
  
  public static function update($fields) 
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
  function count($where_clause="") 
  {
    $language = new Language;
    return $language->_count($where_clause);
  }

  function get_all($where_clause="", $order_by="ORDER BY name", $page=0)
  {
    global $config;
    $language = new Language;

    if ($page <> 0) {
      $start = ($page-1)*$config['opus']['rows_per_page'];
      $limit = $config['opus']['rows_per_page'];
      $languages = $language->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $languages = $language->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $languages;
  }

  public static function get_id_and_field($fieldname, $where_clause="") 
  {
    $language = new Language;
    $language_array = $language->_get_id_and_field($fieldname, $where_clause);
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

  public static function get_name($id)
  {
    $id = (int) $id; // Security

    $data = Language::get_id_and_field("name","where id='$id'");
    return($data[$id]);
  }

}
?>
