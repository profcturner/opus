<?php

/**
* Used the define all the activities that exist in the OPUS system
* @package OPUS
*/
require_once("dto/DTO_Activitytype.class.php");
/**
* Used the define all the activities that exist in the OPUS system
*
* That is, things like "Engineering", "Computing", "Social Work" etc.
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/
class Activitytype extends DTO_Activitytype 
{
  var $name = "";        // Name of activity type

  static $_field_defs = array(
    'name'=>array('type'=>'text', 'size'=>40, 'maxsize'=>40, 'title'=>'Name', 'header'=>true, 'listclass'=>'activitytype_name')
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
    $activitytype = new Activitytype;
    $activitytype->id = $id;
    $activitytype->_load_by_id();
    return $activitytype;
  }

  function insert($fields) 
  {
    $activitytype = new Activitytype;
    $activitytype->_insert($fields);
  }
  
  function update($fields) 
  {
    $activitytype = Activitytype::load_by_id($fields[id]);
    $activitytype->_update($fields);
  }
  
  /**
  * Wasteful
  */
  function exists($id) 
  {
    $activitytype = new Activitytype;
    $activitytype->id = $id;
    return $activitytype->_exists();
  }
  
  /**
  * Wasteful
  */
  function count() 
  {
    $activitytype = new Activitytype;
    return $activitytype->_count();
  }

  function get_all($where_clause="", $order_by="ORDER BY name", $page=0)
  {
    $activitytype = new Activitytype;
    
    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $activitytypes = $activitytype->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $activitytypes = $activitytype->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $activitytypes;
  }

  function get_id_and_field($fieldname) 
  {
    $activitytype = new Activitytype;
    $activitytype_array = $activitytype->_get_id_and_field($fieldname, "", "order by name");
    unset($activitytype_array[0]);
    return $activitytype_array;
  }


  function remove($id=0) 
  {  
    $activitytype = new Activitytype;
    $activitytype->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {  
    $activitytype = new Activitytype;
    return  $activitytype->_get_fieldnames($include_id); 
  }
  function request_field_values($include_id = false) 
  {
    $fieldnames = Activitytype::get_fields($include_id);
    $nvp_array = array();
 
    foreach ($fieldnames as $fn) {
 
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
 
    }

    return $nvp_array;

  }

  function get_name($id)
  {
    $id = (int) $id; // Security

    $data = Activitytype::get_id_and_field("name","where id='$id'");
    return($data[$id]);
  }

}
?>