<?php

/**
* The model object for linking CVGroups with PDSystem Templates
* @package OPUS
*/
require_once("dto/DTO_CVGroupTemplate.class.php");

/**
* The CVGroupTemplate model class
*/
class CVGroupTemplate extends DTO_CVGroupTemplate 
{
  var $group_id = 0;     // The id from the cvgroup table
  var $template_id = 0;  // The id for the template
  var $settings = "";    // A set variable, contains allow and requiresApproval

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
    $cvgrouptemplate = new CVGroupTemplate;
    $cvgrouptemplate->id = $id;
    $cvgrouptemplate->_load_by_id();
    return $cvgrouptemplate;
  }

  function insert($fields) 
  {
    $cvgrouptemplate = new CVGroupTemplate;
    $cvgrouptemplate->_insert($fields);
  }
  
  function update($fields) 
  {
    $cvgrouptemplate = CVGroupTemplate::load_by_id($fields[id]);
    $cvgrouptemplate->_update($fields);
  }
  
  /**
  * Wasteful
  */
  function exists($id) 
  {
    $cvgrouptemplate = new CVGroupTemplate;
    $cvgrouptemplate->id = $id;
    return $cvgrouptemplate->_exists();
  }
  
  /**
  * Wasteful
  */
  function count() 
  {
    $cvgrouptemplate = new CVGroupTemplate;
    return $cvgrouptemplate->_count();
  }

  function get_all($where_clause="", $order_by="ORDER BY template_id", $page=0)
  {
    $cvgrouptemplate = new CVGroupTemplate;
    
    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $cvgrouptemplates = $cvgrouptemplate->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $cvgrouptemplates = $cvgrouptemplate->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $cvgrouptemplates;
  }

  function get_id_and_field($fieldname) 
  {
    $cvgrouptemplate = new CVGroupTemplate;
    $cvgrouptemplate_array = $cvgrouptemplate->_get_id_and_field($fieldname);
    return $cvgrouptemplate_array;
  }

  function remove_by_group($group_id=0) 
  {
    $cvgrouptemplate = new CVGroupTemplate;
    $cvgrouptemplate->_remove_where("WHERE group_id=$group_id");
  }

  function remove($id=0) 
  {
    $cvgrouptemplate = new CVGroupTemplate;
    $cvgrouptemplate->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {
    $cvgrouptemplate = new CVGroupTemplate;
    return  $cvgrouptemplate->_get_fieldnames($include_id); 
  }

  function request_field_values($include_id = false) 
  {
    $fieldnames = CVGroupTemplate::get_fields($include_id);
    $nvp_array = array();

    foreach ($fieldnames as $fn)
    {
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
    }
    return $nvp_array;
  }
}
?>