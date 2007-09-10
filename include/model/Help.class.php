<?php

/**
* The model object for help prompts
* @package OPUS
*/
require_once("dto/DTO_Help.class.php");

/**
* The Help model class
*/
class Help extends DTO_Help 
{
  var $lookup = "";      // A text lookup field for the prompt
  var $language_id = 0;  // Language help belongs to
  var $channel_id = 0;   // The channel if any, the help belongs to
  var $auth = "";        // Authorisation string to explain who can see the help
  var $description = ""; // Brief description of description
  var $contents = "";    // The message body

  static $_field_defs = array(
    'language_id'=>array('type'=>'lookup', 'object'=>'language', 'value'=>'name', 'title'=>'language', 'var'=>'languages'),
    'channel_id'=>array('type'=>'lookup', 'object'=>'channel', 'value'=>'name', 'title'=>'Channel', 'var'=>'channels', 'header'=>'true'),
    'lookup'=>array('type'=>'text', 'size'=>30, 'maxsize'=>100, 'title'=>'Lookup', 'header'=>true),
    'description'=>array('type'=>'text', 'size'=>80, 'maxsize'=>250, 'title'=>'Description', 'header'=>true, 'listclass'=>'resource_description'),
    'auth'=>array('type'=>'text', 'size'=>60, 'maxsize'=>250, 'title'=>'Authorisation'),
    'contents'=>array('type'=>'textarea', 'rows'=>10, 'cols'=>40, 'maxsize=>32000')

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
    $help = new Help;
    $help->id = $id;
    $help->_load_by_id();
    return $help;
  }

  function insert($fields) 
  {
    $help = new Help;
    $help->_insert($fields);
  }
  
  function update($fields) 
  {
    $help = Help::load_by_id($fields[id]);
    $help->_update($fields);
  }
  
  /**
  * Wasteful
  */
  function exists($id) 
  {
    $help = new Help;
    $help->id = $id;
    return $help->_exists();
  }
  
  /**
  * Wasteful
  */
  function count() 
  {
    $help = new Help;
    return $help->_count();
  }

  function get_all($where_clause="", $order_by="ORDER BY lookup, channel_id", $page=0)
  {
    $help = new Help;
    
    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $helps = $help->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $helps = $help->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $helps;
  }

  function get_id_and_field($fieldname) 
  {
    $help = new Help;
    return  $help->_get_id_and_field($fieldname);
  }


  function remove($id=0) 
  {  
    $help = new Help;
    $help->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {  
    $help = new Help;
    return  $help->_get_fieldnames($include_id); 
  }
  function request_field_values($include_id = false) 
  {
    $fieldnames = Help::get_fields($include_id);
    $nvp_array = array();
 
    foreach ($fieldnames as $fn) {
 
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
 
    }

    return $nvp_array;

  }
}
?>