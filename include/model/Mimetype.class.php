<?php

/**
* Defines the mimetypes of files that OPUS should handle in some way
* @package OPUS
*/
require_once("dto/DTO_Mimetype.class.php");
/**
* Defines the mimetypes of files that OPUS should handle in some way
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/
class Mimetype extends DTO_Mimetype 
{
  var $type = "";        // Very brief mimetype name
  var $comment = ""; // Brief description of mimetype
  var $extensions = ""; // Allowable filename extensions
  var $uploadable = ""; // Various flags

  static $_field_defs = array(
    'type'=>array('type'=>'text', 'size'=>40, 'maxsize'=>100, 'title'=>'Type', 'header'=>true, 'listclass'=>'mimetype_type', 'mandatory'=>true),
    'extensions'=>array('type'=>'text', 'size'=>40, 'maxsize'=>100, 'title'=>'Extensions', 'listclass'=>'mimetype_extensions', 'mandatory'=>true),
    'comment'=>array('type'=>'text', 'size'=>80, 'maxsize'=>250, 'title'=>'Comment', 'header'=>true, 'listclass'=>'comment'),
    'uploadable'=>array('type'=>'list', 'list'=>array('yes', 'no')),
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
    $mimetype = new Mimetype;
    $mimetype->id = $id;
    if($id)
    {
      $mimetype->_load_by_id();
    }
    else
    {
      $mimetype->name="Global";
      $mimetype->description="Visible to all";
    }
    return $mimetype;
  }

  function insert($fields) 
  {
    $mimetype = new Mimetype;
    $mimetype->_insert($fields);
  }
  
  function update($fields) 
  {
    $mimetype = Mimetype::load_by_id($fields[id]);
    $mimetype->_update($fields);
  }
  
  /**
  * Wasteful
  */
  function exists($id) 
  {
    $mimetype = new Mimetype;
    $mimetype->id = $id;
    return $mimetype->_exists();
  }
  
  /**
  * Wasteful
  */
  function count() 
  {
    $mimetype = new Mimetype;
    return $mimetype->_count();
  }

  function get_all($where_clause="", $order_by="ORDER BY id", $page=0)
  {
    $mimetype = new Mimetype;
    
    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $mimetypes = $mimetype->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $mimetypes = $mimetype->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $mimetypes;
  }

  function get_id_and_field($fieldname) 
  {
    $mimetype = new Mimetype;
    $mimetype_array = $mimetype->_get_id_and_field($fieldname);
    $mimetype_array[0] = 'Global';
    return $mimetype_array;
  }


  function remove($id=0) 
  {  
    $mimetype = new Mimetype;
    $mimetype->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {  
    $mimetype = new Mimetype;
    return  $mimetype->_get_fieldnames($include_id); 
  }
  function request_field_values($include_id = false) 
  {
    $fieldnames = Mimetype::get_fields($include_id);
    $nvp_array = array();
 
    foreach ($fieldnames as $fn) {
 
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
 
    }

    return $nvp_array;

  }
}
?>