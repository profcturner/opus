<?php

/**
* Defines the activities that are associated with a given company
* @package OPUS
*/
require_once("dto/DTO_CompanyActivity.class.php");
/**
* Defines the activities that are associated with a given company
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/

class CompanyActivity extends DTO_CompanyActivity 
{
  var $company_id = "";   // The company
  var $activity_id = "";  // The linked activity

  static $_field_defs = array(
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
    $companyactivity = new CompanyActivity;
    $companyactivity->id = $id;
    $companyactivity->_load_by_id();
    return $companyactivity;
  }

  function insert($fields) 
  {
    $companyactivity = new CompanyActivity;
    $companyactivity->_insert($fields);
  }

  function update($fields) 
  {
    $companyactivity = CompanyActivity::load_by_id($fields[id]);
    $companyactivity->_update($fields);
  }

  /**
  * Wasteful
  */
  function exists($id) 
  {
    $companyactivity = new CompanyActivity;
    $companyactivity->id = $id;
    return $companyactivity->_exists();
  }
  
  /**
  * Wasteful
  */
  function count($where_clause="") 
  {
    $companyactivity = new CompanyActivity;
    return $companyactivity->_count($where_clause);
  }

  function get_all($where_clause="", $order_by="", $page=0)
  {
    global $config;
    $companyactivity = new CompanyActivity;

    if ($page <> 0) {
      $start = ($page-1)*$config['opus']['rows_per_page'];
      $limit = $config['opus']['rows_per_page'];
      $companyactivitys = $companyactivity->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $companyactivitys = $companyactivity->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $companyactivitys;
  }

  function get_id_and_field($fieldname, $where_clause="") 
  {
    $companyactivity = new CompanyActivity;
    $companyactivity_array = $companyactivity->_get_id_and_field($fieldname, $where_clause);
    unset($companyactivity_array[0]);
    return $companyactivity_array;
  }

  function remove($id=0) 
  {
    $companyactivity = new CompanyActivity;
    $companyactivity->_remove_where("WHERE id=$id");
  }

  function remove_by_company($company_id=0) 
  {
    $companyactivity = new CompanyActivity;
    $companyactivity->_remove_where("WHERE company_id=$company_id");
  }

  function get_fields($include_id = false) 
  {  
    $companyactivity = new CompanyActivity;
    return  $companyactivity->_get_fieldnames($include_id); 
  }
  function request_field_values($include_id = false) 
  {
    $fieldnames = CompanyActivity::get_fields($include_id);
    $nvp_array = array();

    foreach ($fieldnames as $fn) {
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
    }
    return $nvp_array;
  }

  function get_activity_ids_for_company($company_id)
  {
    $companyactivity = new CompanyActivity;
    return($companyactivity->_get_activity_ids_for_company($company_id));
  }
}
?>