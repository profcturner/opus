<?php

/**
* The model object for Schools
* @package OPUS
*/
require_once("dto/DTO_Channelassociation.class.php");

/**
* The Channelassociation model class
*/
class Channelassociation extends DTO_Channelassociation 
{
  var $permission = "";  // Either enable or disable
  var $type = "";        // Type of association
  var $object_id = "";   // Object id
  var $priority = 0;     // Priority
  var $channel_id;       // The channel to associate with


  static $_field_defs = array(
    'permission'=>array('type'=>'list', 'list'=>array('enable', 'disable'), 'header'=>true),
    'type'=>array('type'=>'list', 'list'=>array('course','school','assessmentgroup','activity'), 'header'=>'true')
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
    $channelassociation = new Channelassociation;
    $channelassociation->id = $id;
    $channelassociation->_load_by_id();
    return $channelassociation;
  }

  function insert($fields) 
  {
    $channelassociation = new Channelassociation;
    $channelassociation->_insert($fields);
  }
  
  function update($fields) 
  {
    $channelassociation = Channelassociation::load_by_id($fields[id]);
    $channelassociation->_update($fields);
  }
  
  /**
  * Wasteful
  */
  function exists($id) 
  {
    $channelassociation = new Channelassociation;
    $channelassociation->id = $id;
    return $channelassociation->_exists();
  }
  
  /**
  * Wasteful
  */
  function count() 
  {
    $channelassociation = new Channelassociation;
    return $channelassociation->_count();
  }

  function get_all($where_clause="", $order_by="ORDER BY priority", $page=0)
  {
    $channelassociation = new Channelassociation;
    
    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $channelassociations = $channelassociation->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $channelassociations = $channelassociation->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $channelassociations;
  }

  function get_id_and_field($fieldname) 
  {
    $channelassociation = new Channelassociation;
    $channelassociation_array = $channelassociation->_get_id_and_field($fieldname);
    $channelassociation_array[0] = 'Global';
    return $channelassociation_array;
  }


  function remove($id=0) 
  {  
    $channelassociation = new Channelassociation;
    $channelassociation->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {  
    $channelassociation = new Channelassociation;
    return  $channelassociation->_get_fieldnames($include_id); 
  }
  function request_field_values($include_id = false) 
  {
    $fieldnames = Channelassociation::get_fields($include_id);
    $nvp_array = array();
 
    foreach ($fieldnames as $fn) {
 
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
 
    }

    return $nvp_array;

  }
}
?>