<?php

/**
* The model object for automail templates
* @package OPUS
*/
require_once("dto/DTO_Automail.class.php");

/**
* The Resource model class
*/
class Automail extends DTO_Automail 
{
  var $lookup = "";      // A text lookup field for the resource
  var $language_id = 0;  // Language resource belongs to
  var $fromh = "";       // The From header
  var $toh = "";         // The To header
  var $cch = "";         // The Carbon Copy Header
  var $bcch = "";        // The Blind Carbon Copy Header
  var $subject = "";     // The subject of the message
  var $description = ""; // Brief description of description
  var $contents = "";    // The message body

  var $_field_defs = array(
    'lookup'=>array('type'=>'text', 'size'=>30, 'maxsize'=>100, 'title'=>'Lookup', 'header'=>true),
    'description'=>array('type'=>'text', 'size'=>80, 'maxsize'=>250, 'title'=>'Description', 'header'=>true, 'listclass'=>'resource_description'),
    'fromh'=>array('type'=>'text', 'size'=>60, 'maxsize'=>250, 'title'=>'From Header'),
    'toh'=>array('type'=>'text', 'size'=>60, 'maxsize'=>250, 'title'=>'To Header'),
    'cch'=>array('type'=>'text', 'size'=>60, 'maxsize'=>250, 'title'=>'CC Header'),
    'bcch'=>array('type'=>'text', 'size'=>60, 'maxsize'=>250, 'title'=>'BCC Header'),
    'subject'=>array('type'=>'text', 'size'=>60, 'maxsize'=>250, 'title'=>'Subject'),
    'contents'=>array('type'=>'textarea', 'rows'=>10, 'cols'=>40, 'maxsize=>32000')

    );

  function __construct() 
  {
    parent::__construct('default');
  }

  function load_by_id($id) 
  {
    $automail = new Automail;
    $automail->id = $id;
    $automail->_load_by_id();
    return $automail;
  }

  function insert($fields) 
  {
    $automail = new Automail;
    $automail->_insert($fields);
  }
  
  function update($fields) 
  {
    $automail = Automail::load_by_id($fields[id]);
    $automail->_update($fields);
  }
  
  /**
  * Wasteful
  */
  function exists($id) 
  {
    $automail = new Automail;
    $automail->id = $id;
    return $automail->_exists();
  }
  
  /**
  * Wasteful
  */
  function count() 
  {
    $automail = new Automail;
    return $automail->_count();
  }

  function get_all($where_clause="", $order_by="ORDER BY id", $page=0)
  {
    $automail = new Automail;
    
    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $automails = $automail->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $automails = $automail->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $automails;
  }

  function get_id_and_field($fieldname) 
  {
    $automail = new Automail;
    return  $automail->_get_id_and_field($fieldname);
  }


  function remove($id=0) 
  {  
    $automail = new Automail;
    $automail->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {  
    $automail = new Automail;
    return  $automail->_get_fieldnames($include_id); 
  }
  function request_field_values($include_id = false) 
  {
    $fieldnames = Automail::get_fields($include_id);
    $nvp_array = array();
 
    foreach ($fieldnames as $fn) {
 
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
 
    }

    return $nvp_array;

  }
}
?>