<?php

/**
* The model object for CVgroups
* @package OPUS
*/
require_once("dto/DTO_CVgroup.class.php");

/**
* The CVgroup model class
*/
class CVgroup extends DTO_CVgroup 
{
  var $name = "";            // Name of cvgroup
  var $comments = "";     // Description of group
  var $permissions = "";     // Permissions for templates
  var $default_template = 0; // Default PDSystem template

  static $_field_defs = array(
    'name'=>array('type'=>'text', 'size'=>40, 'maxsize'=>80, 'title'=>'Name', 'header'=>true, 'listclass'=>'cvgroup_name'),
    'description'=>array('type'=>'textarea', 'rowsize'=>10, 'colsize'=>40, 'maxsize'=>1000),
    'permissions'=>array('type'=>'list', 'list'=>array('allowAllTemplates','allowCustom'), 'multiple'=>true),
    'default_template'=>array('type'=>'text')
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
    $cvgroup = new CVgroup;
    $cvgroup->id = $id;
    $cvgroup->_load_by_id();
    $cvgroup->permissions = explode(",", $cvgroup->permissions);
    return $cvgroup;
  }

  function insert($fields) 
  {
    $cvgroup = new CVgroup;
    $cvgroup->_insert($fields);
  }
  
  function update($fields) 
  {
    $cvgroup = CVgroup::load_by_id($fields[id]);
    $cvgroup->_update($fields);
  }
  
  /**
  * Wasteful
  */
  function exists($id) 
  {
    $cvgroup = new CVgroup;
    $cvgroup->id = $id;
    return $cvgroup->_exists();
  }
  
  /**
  * Wasteful
  */
  function count() 
  {
    $cvgroup = new CVgroup;
    return $cvgroup->_count();
  }

  function get_all($where_clause="", $order_by="ORDER BY id", $page=0)
  {
    $cvgroup = new CVgroup;
    
    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $cvgroups = $cvgroup->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $cvgroups = $cvgroup->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $cvgroups;
  }

  function get_id_and_field($fieldname) 
  {
    $cvgroup = new CVgroup;
    $cvgroup_array = $cvgroup->_get_id_and_field($fieldname);
    $cvgroup_array[0] = 'Global';
    return $cvgroup_array;
  }


  function remove($id=0) 
  {  
    $cvgroup = new CVgroup;
    $cvgroup->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {  
    $cvgroup = new CVgroup;
    return  $cvgroup->_get_fieldnames($include_id); 
  }
  function request_field_values($include_id = false) 
  {
    $fieldnames = CVgroup::get_fields($include_id);
    $nvp_array = array();
 
    foreach ($fieldnames as $fn) {
 
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
 
    }

    return $nvp_array;

  }
}
?>