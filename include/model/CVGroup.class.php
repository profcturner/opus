<?php

/**
* Defines the groups of programmes that have different CV handling
* @package OPUS
*/
require_once("dto/DTO_CVGroup.class.php");
/**
* Defines the groups of programmes that have different CV handling
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/

class CVGroup extends DTO_CVGroup 
{
  var $name = "";            // Name of cvgroup
  var $comments = "";     // Description of group
  var $permissions = "";     // Permissions for templates
  var $description = "";     // The description of the group
  var $default_template = 0; // Default PDSystem template

  static $_field_defs = array(
    'name'=>array('type'=>'text', 'size'=>40, 'maxsize'=>80, 'title'=>'Name', 'header'=>true, 'listclass'=>'cvgroup_name', 'mandatory'=>true),
    'description'=>array('type'=>'textarea', 'rowsize'=>10, 'colsize'=>40, 'maxsize'=>1000),
    'permissions'=>array('type'=>'list', 'list'=>array('allowAllTemplates'=>"Allow All PDSystem Templates",'allowCustom'=>"Allow Custom CVs"), 'multiple'=>true)
    //'default_template'=>array('type'=>'text') handled in CVGroupTemplate
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
    $cvgroup = new CVGroup;
    $cvgroup->id = $id;
    $cvgroup->_load_by_id();
    $cvgroup->permissions = explode(",", $cvgroup->permissions);
    return $cvgroup;
  }

  function insert($fields) 
  {
    $fields['permissions'] = implode(",", $fields['permissions']);

    $cvgroup = new CVGroup;
    return($cvgroup->_insert($fields));
  }

  function update($fields) 
  {
    // Special handling for multiple set field
    $fields['permissions'] = implode(",", $fields['permissions']);

    $cvgroup = CVGroup::load_by_id($fields[id]);
    $cvgroup->_update($fields);
  }

  /**
  * Wasteful
  */
  function exists($id) 
  {
    $cvgroup = new CVGroup;
    $cvgroup->id = $id;
    return $cvgroup->_exists();
  }
  
  /**
  * Wasteful
  */
  function count($where_clause="") 
  {
    $cvgroup = new CVGroup;
    return $cvgroup->_count($where_clause);
  }

  function get_all($where_clause="", $order_by="ORDER BY id", $page=0)
  {
    global $config;
    $cvgroup = new CVGroup;

    if ($page <> 0) {
      $start = ($page-1)*$config['opus']['rows_per_page'];;
      $limit = $config['opus']['rows_per_page'];;
      $cvgroups = $cvgroup->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $cvgroups = $cvgroup->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $cvgroups;
  }

  function get_id_and_field($fieldname, $where_clause="") 
  {
    $cvgroup = new CVGroup;
    $cvgroup_array = $cvgroup->_get_id_and_field($fieldname, $where_clause);
    unset($cvgroup_array[0]);
    return $cvgroup_array;
  }

  function remove($id=0) 
  {
    $cvgroup = new CVGroup;
    $cvgroup->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {
    $cvgroup = new CVGroup;
    return  $cvgroup->_get_fieldnames($include_id); 
  }

  function request_field_values($include_id = false) 
  {
    $fieldnames = CVGroup::get_fields($include_id);
    $nvp_array = array();

    foreach ($fieldnames as $fn)
    {
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
    }
    return $nvp_array;
  }

  function check_permission($group_id, $permission)
  {
    $cvgroup = CVGroup::load_by_id($group_id);
    return(in_array($permission, $cvgroup->permissions));
  }
}
?>