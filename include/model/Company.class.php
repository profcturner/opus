<?php

/**
* The model object for companies
* @package OPUS
*/
require_once("dto/DTO_Company.class.php");

/**
* The Company model class
*/
class Company extends DTO_Company 
{
  var $name = "";      // Company name
  var $address1 = "";
  var $address2 = "";
  var $address3 = "";
  var $town = "";
  var $locality = "";
  var $country = "";
  var $postcode = "";
  var $www = "";
  var $voice = "";
  var $fax = "";
  var $brief = "";
  var $created = "";
  var $modified = "";
  var $allocation = "";

  static $_field_defs = array(
    'name'=>array('type'=>'text', 'size'=>30, 'maxsize'=>100, 'title'=>'Name','header'=>true),
    'address1'=>array('type'=>'text', 'size'=>40, 'maxsize'=>100, 'title'=>'Address 1'),
    'address2'=>array('type'=>'text', 'size'=>40, 'maxsize'=>100, 'title'=>'Address 2'),
    'address3'=>array('type'=>'text', 'size'=>40, 'maxsize'=>100, 'title'=>'Address 3'),
    'town'=>array('type'=>'text', 'size'=>40, 'maxsize'=>100, 'title'=>'Town'),
    'locality'=>array('type'=>'text', 'size'=>40, 'maxsize'=>100, 'title'=>'Locality', 'header'=>true),
    'country'=>array('type'=>'text', 'size'=>30, 'maxsize'=>100, 'title'=>'Country'),
    'postcode'=>array('type'=>'text', 'size'=>10, 'maxsize'=>20, 'title'=>'Postcode'),
    'www'=>array('type'=>'url', 'size'=>40, 'maxsize'=>80, 'title'=>'Web Address'),
    'voice'=>array('type'=>'text', 'size'=>20, 'maxsize'=>40, 'title'=>'Phone'),
    'fax'=>array('type'=>'text', 'size'=>20, 'maxsize'=>40, 'title'=>'Fax'),
    'allocation'=>array('type'=>'numeric', 'size'=>10, 'title'=>'Space Allocation'),
    'activities'=>array('type'=>'lookup', 'object'=>'activitytype', 'value'=>'name', 'var'=>'activities', 'multiple'=>true),
    'brief'=>array('type'=>'textarea', 'rowsize'=>20, 'colsize'=>80, 'maxsize'=>60000,  'title'=>'Brief', 'markup'=>'xhtml')
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
    $company = new Company;
    $company->id = $id;
    $company->_load_by_id();
    return $company;
  }

  function insert($fields) 
  {
    $company = new Company;
    $fields['created'] = date("YmdHis");
    $company->_insert($fields);
  }
  
  function update($fields) 
  {
    $company = Company::load_by_id($fields[id]);
    $fields['modified'] = date("YmdHis");
    $company->_update($fields);
  }
  
  /**
  * Wasteful
  */
  function exists($id) 
  {
    $company = new Company;
    $company->id = $id;
    return $company->_exists();
  }
  
  /**
  * Wasteful
  */
  function count() 
  {
    $company = new Company;
    return $company->_count();
  }

  function get_all($where_clause="", $order_by="ORDER BY name, locality", $page=0)
  {
    $company = new Company;
    
    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $companys = $company->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $companys = $company->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $companys;
  }

  function get_id_and_field($fieldname) 
  {
    $company = new Company;
    $company_array = $company->_get_id_and_field($fieldname);
    unset($company_array[0]);
    return $company_array;
  }


  function remove($id=0) 
  {  
    $company = new Company;
    $company->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {  
    $company = new Company;
    return  $company->_get_fieldnames($include_id); 
  }
  function request_field_values($include_id = false) 
  {
    $fieldnames = Company::get_fields($include_id);
    $nvp_array = array();
 
    foreach ($fieldnames as $fn) {
 
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
 
    }

    return $nvp_array;

  }
}
?>