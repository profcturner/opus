<?php

/**
* Handles the types of vacancies that OPUS will handle
* @package OPUS
*/
require_once("dto/DTO_Vacancytype.class.php");
/**
* Handles the types of vacancies that OPUS will handle
*
* That is, things like
* <ul>
*   <li>One year full time sandwhich placement</li>
*   <li>Part time placement</li>
*   <li>Summer jobs</li>
*   <li>Graduate vacancies jobs</li>
* </ul>
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see Vacancy.class.php
* @package OPUS
*
*/
class Vacancytype extends DTO_Vacancytype 
{
  var $name = "";        // Name of vacancy type
  var $priority = 0;     // Order in which to list these
  var $help = "";        // XHTML help

  static $_field_defs = array(
    'name'=>array('type'=>'text', 'size'=>40, 'maxsize'=>40, 'title'=>'Name', 'header'=>true, 'listclass'=>'vacancytype_name'),
    'priority'=>array('type'=>'numeric', 'size'=>10, 'maxsize'=>10, 'title'=>'Priority'),
    'help'=>array('type'=>'textarea', 'rowsize'=>10, 'colsize'=>80, 'markup'=>'xhtml')
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
    $vacancytype = new Vacancytype;
    $vacancytype->id = $id;
    $vacancytype->_load_by_id();
    return $vacancytype;
  }

  function insert($fields) 
  {
    $vacancytype = new Vacancytype;
    $vacancytype->_insert($fields);
  }
  
  function update($fields) 
  {
    $vacancytype = Vacancytype::load_by_id($fields[id]);
    $vacancytype->_update($fields);
  }
  
  /**
  * Wasteful
  */
  function exists($id) 
  {
    $vacancytype = new Vacancytype;
    $vacancytype->id = $id;
    return $vacancytype->_exists();
  }
  
  /**
  * Wasteful
  */
  function count() 
  {
    $vacancytype = new Vacancytype;
    return $vacancytype->_count();
  }

  function get_all($where_clause="", $order_by="ORDER BY name", $page=0)
  {
    $vacancytype = new Vacancytype;
    
    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $vacancytypes = $vacancytype->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $vacancytypes = $vacancytype->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $vacancytypes;
  }

  function get_id_and_field($fieldname) 
  {
    $vacancytype = new Vacancytype;
    $vacancytype_array = $vacancytype->_get_id_and_field($fieldname, "", "order by priority");
    unset($vacancytype_array[0]);
    return $vacancytype_array;
  }


  function remove($id=0) 
  {  
    $vacancytype = new Vacancytype;
    $vacancytype->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {  
    $vacancytype = new Vacancytype;
    return  $vacancytype->_get_fieldnames($include_id); 
  }
  function request_field_values($include_id = false) 
  {
    $fieldnames = Vacancytype::get_fields($include_id);
    $nvp_array = array();
 
    foreach ($fieldnames as $fn) {
 
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
 
    }

    return $nvp_array;

  }

  function get_name($id)
  {
    $id = (int) $id; // Security

    $data = Vacancytype::get_id_and_field("name","where id='$id'");
    return($data[$id]);
  }

}
?>