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

  /**
  * Inserts a new resource
  *
  * @todo URGENT: $config is not working here ... ?!
  */
  function insert($fields) 
  {
    global $waf;
    global $config;

    require_once("model/File_Upload.class.php");
    $resource = new Resource;

    // We must check the file first
    $file_var_name = 'file_upload';
    $upload_error = File_Upload::upload_error($file_var_name);
    if($upload_error) $waf->halt($upload_error);

    $resource_id = $resource->_insert($fields);
    // It went Ok, we need to do the copy
    File_Upload::move_file($file_var_name, $config['opus']['paths']['resources'] . $resource_id);
  }
  
  function update($fields) 
  {
    global $waf;
    global $config;

    // Is there a new inbound file?
    if($_FILES['file_upload']['size'])
    {
      require_once("model/File_Upload.class.php");
      //echo "hello world";
      // We must check the file first
      $file_var_name = 'file_upload';
      $upload_error = File_Upload::upload_error($file_var_name);
      if($upload_error) $waf->halt($upload_error);

      File_Upload::move_file($file_var_name, $config['opus']['paths']['resources'] . $fields[id]);
    }
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

  function get_all($where_clause="", $order_by="ORDER BY channel_id, description", $page=0)
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


  /**
  * Removes a resource from file storage as well as the database
  */ 
  function remove($id=0) 
  {
    global $waf;
    global $config;

    // Get details for logging
    $resource=Resource::load_by_id($id);
    $waf->log("removing resource [" . $resource->description . "] Lookup [" . $resource->lookup . "] Channel [" . $resource->_channel_id . "]");
    @unlink($config['opus']['paths']['resources'] . $id);

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