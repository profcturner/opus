<?php

/**
* The model object for companies
* @package OPUS
*/
require_once("dto/DTO_Company.class.php");
/**
* The model object for companies
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
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
    'name'=>array('type'=>'text', 'size'=>30, 'maxsize'=>100, 'title'=>'Name','header'=>true, 'mandatory'=>true),
    'address1'=>array('type'=>'text', 'size'=>40, 'maxsize'=>100, 'title'=>'Address 1', 'mandatory'=>true),
    'address2'=>array('type'=>'text', 'size'=>40, 'maxsize'=>100, 'title'=>'Address 2'),
    'address3'=>array('type'=>'text', 'size'=>40, 'maxsize'=>100, 'title'=>'Address 3'),
    'town'=>array('type'=>'text', 'size'=>40, 'maxsize'=>100, 'title'=>'Town', 'mandatory'=>true),
    'locality'=>array('type'=>'text', 'size'=>40, 'maxsize'=>100, 'title'=>'Locality', 'header'=>true, 'mandatory'=>true),
    'country'=>array('type'=>'text', 'size'=>30, 'maxsize'=>100, 'title'=>'Country', 'mandatory'=>true),
    'postcode'=>array('type'=>'text', 'size'=>10, 'maxsize'=>20, 'title'=>'Postcode'),
    'activity_types'=>array('type'=>'lookup', 'object'=>'activitytype', 'value'=>'name', 'title'=>'Activities', 'var'=>'activitytypes', 'multiple'=>true, 'mandatory'=>true),
    'www'=>array('type'=>'url', 'size'=>40, 'maxsize'=>80, 'title'=>'Web Address'),
    'voice'=>array('type'=>'text', 'size'=>20, 'maxsize'=>40, 'title'=>'Phone'),
    'fax'=>array('type'=>'text', 'size'=>20, 'maxsize'=>40, 'title'=>'Fax'),
    'allocation'=>array('type'=>'numeric', 'size'=>10, 'title'=>'Space Allocation'),
    'brief'=>array('type'=>'textarea', 'rowsize'=>20, 'colsize'=>80, 'maxsize'=>60000,  'title'=>'Brief', 'markup'=>'xhtml', 'mandatory'=>true)
     );

  // This defines which variables are stored elsewhere
  static $_extended_fields = array
  (
    'activity_types'
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

  function get_extended_fields()
  {
    return self::$_extended_fields;
  }

  function load_by_id($id) 
  {
    $company = new Company;
    $company->id = $id;
    $company->_load_by_id();

    require_once("model/CompanyActivity.class.php");
    $company->activity_types = CompanyActivity::get_activity_ids_for_company($company->id);

    return $company;
  }

  function insert($fields) 
  {
    $company = new Company;
    $fields = Company::set_empty_to_null($fields);

    // Some fields reside elsewhere, grab and unset them
    $activities = $fields['activity_types'];
    unset($fields['activity_types']);

    if(empty($activities)) $activities = array();

    // Make sure there is an array... even an empty one
    if(empty($activities)) $activities = array();

    $fields['created'] = date("YmdHis");
    $company_id = $company->_insert($fields);

    require_once("model/CompanyActivity.class.php");
    CompanyActivity::remove_by_company($company_id);
    foreach($activities as $activity)
    {
      $fields = array();
      $fields['company_id'] = $company_id;
      $fields['activity_id'] = $activity;

      CompanyActivity::insert($fields);
    }
  }

  function update($fields) 
  {
    // Null some fields if empty
    $fields = Company::set_empty_to_null($fields);

    // Some fields reside elsewhere, grab and unset them
    $activities = $fields['activity_types'];
    unset($fields['activity_types']);

    if(empty($activities)) $activities = array();

    $company = Company::load_by_id($fields[id]);
    $fields['modified'] = date("YmdHis");
    $company->_update($fields);

    $company_id = $company->id;
    require_once("model/CompanyActivity.class.php");
    CompanyActivity::remove_by_company($company_id);
    foreach($activities as $activity)
    {
      $fields = array();
      $fields['company_id'] = $company_id;
      $fields['activity_id'] = $activity;

      CompanyActivity::insert($fields);
    }
  }

  /**
  * Goes through certain fields and sets them to null if they are "empty"
  */
  function set_empty_to_null($fields)
  {
    $set_to_null = array("created", "modified");
    foreach($set_to_null as $field)
    {
      if(!strlen($fields[$field])) $fields[$field] = null;
    }
    return($fields);
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
  function count($where_clause="") 
  {
    $company = new Company;
    return $company->_count($where_clause);
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

  function get_all_extended($search, $activities, $sort)
  {
    $company = new Company;
    return($company->_get_all_extended($search, $activities, $sort));
  }


  function get_id_and_field($fieldname, $where_clause="") 
  {
    $company = new Company;
    $company_array = $company->_get_id_and_field($fieldname, $where_clause);
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
    $fieldnames = array_merge($fieldnames, Company::get_extended_fields());

    $nvp_array = array();

    foreach ($fieldnames as $fn)
    {
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
    }
    return $nvp_array;
  }

  function display($show_error = false, $user_id = 0)
  {
    require_once("model/XMLdisplay.class.php");
    $xml_parser = new XMLdisplay($this->brief);
    if($show_error)
    {
      echo $xml_parser->xml_error;
    }
    else
    {
      echo $xml_parser->xml_output;
    }
  }

  function get_name($id)
  {
    $id = (int) $id; // Security

    $data = Company::get_id_and_field("name","where id='$id'");
    return($data[$id]);
  }
}
?>