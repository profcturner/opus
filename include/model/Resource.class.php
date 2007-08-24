<?php

/**
* The model object for Resources
* @package OPUS
*/
require_once("dto/DTO_Resource.class.php");

/**
* The Resource model class
*/
class Resource extends DTO_Resource 
{
  var $lookup = "";      // A text lookup field for the resource
  var $language_id = 0;  // Language resource belongs to
  var $category_id = 0;  // remove
  var $channel_id = 0;   // Channel resource belongs to (if any)
  var $description = ""; // Brief description of resource
  var $author = "";      // Author name
  var $copyright = "";   // Copyright
  var $auth = "";        // Authentication string
  var $mime = 0;         // The mime type id (see that table)
  var $filename = "";    // The suggested filename for download
  var $dcounter = 0;     // The download counter
  var $created = 0;      // The creation timestamp
  var $modified = 0;     // The last modification timestamp
  var $downloaded = 0;   // The last download timestamp
  var $uploader;         // The user_id of the uploader
  var $status = "";      // Various status fields


  var $_field_defs = array(
    'file_upload'=>array('type'=>'file'),
    'description'=>array('type'=>'text', 'size'=>80, 'maxsize'=>250, 'title'=>'Description', 'header'=>true, 'listclass'=>'resource_description'),
    'lookup'=>array('type'=>'text', 'size'=>30, 'maxsize'=>100, 'title'=>'Lookup'),
    'language_id'=>array('type'=>'lookup', 'object'=>'language', 'value'=>'name', 'title'=>'language', 'var'=>'languages'),
    'channel_id'=>array('type'=>'lookup', 'object'=>'channel', 'value'=>'name', 'title'=>'Channel', 'size'=>20, 'var'=>'channels', 'header'=>true),
    'auth'=>array('type'=>'text', 'size'=>30, 'maxsize'=>100, 'title'=>'Authorisation'),
    'filename'=>array('type'=>'text', 'size'=>30, 'maxsize'=>100, 'title'=>'Filename'),
    'author'=>array('type'=>'text', 'size'=>30, 'maxsize'=>100, 'title'=>'Author'),
    'copyright'=>array('type'=>'text', 'size'=>30, 'maxsize'=>100, 'title'=>'Copyright')
    );

  function __construct() 
  {
    parent::__construct('default');
  }

  function load_by_id($id) 
  {
    $resource = new Resource;
    $resource->id = $id;
    $resource->_load_by_id();
    return $resource;
  }

  function insert($fields) 
  {
    $resource = new Resource;
    $resource->_insert($fields);
  }
  
  function update($fields) 
  {
    $resource = Resource::load_by_id($fields[id]);
    $resource->_update($fields);
  }
  
  /**
  * Wasteful
  */
  function exists($id) 
  {
    $resource = new Resource;
    $resource->id = $id;
    return $resource->_exists();
  }
  
  /**
  * Wasteful
  */
  function count() 
  {
    $resource = new Resource;
    return $resource->_count();
  }

  function get_all($where_clause="", $order_by="ORDER BY id", $page=0)
  {
    $resource = new Resource;
    
    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $resources = $resource->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $resources = $resource->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $resources;
  }

  function get_id_and_field($fieldname) 
  {
    $resource = new Resource;
    return  $resource->_get_id_and_field($fieldname);
  }


  function remove($id=0) 
  {  
    $resource = new Resource;
    $resource->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {  
    $resource = new Resource;
    return  $resource->_get_fieldnames($include_id); 
  }
  function request_field_values($include_id = false) 
  {
    $fieldnames = Resource::get_fields($include_id);
    $nvp_array = array();
 
    foreach ($fieldnames as $fn) {
 
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
 
    }

    return $nvp_array;

  }
}
?>