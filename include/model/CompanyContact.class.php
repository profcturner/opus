<?php

/**
* The model object for associating contacts with companies
* @package OPUS
*/
require_once("dto/DTO_CompanyContact.class.php");
/**
* The model object for associating contacts with companies
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/

class CompanyContact extends DTO_CompanyContact 
{
  var $company_id = "";  // The id column from the company table
  var $contact_id = "";  // The id column from the user table for the contact
  var $status = "";      // The status of this contact

  // Is this needed?
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
    $companycontact = new CompanyContact;
    $companycontact->id = $id;
    $companycontact->_load_by_id();
    return $companycontact;
  }

  function insert($fields) 
  {
    $companycontact = new CompanyContact;
    $companycontact->_insert($fields);
  }
  
  function update($fields) 
  {
    $companycontact = CompanyContact::load_by_id($fields[id]);
    $companycontact->_update($fields);
  }
  
  /**
  * Wasteful
  */
  function exists($id) 
  {
    $companycontact = new CompanyContact;
    $companycontact->id = $id;
    return $companycontact->_exists();
  }
  
  /**
  * Wasteful
  */
  function count($where_clause="") 
  {
    $companycontact = new CompanyContact;
    return $companycontact->_count($where_clause);
  }

  function get_all($where_clause="", $order_by="ORDER BY priority", $page=0)
  {
    $companycontact = new CompanyContact;
    
    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $companycontacts = $companycontact->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $companycontacts = $companycontact->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $companycontacts;
  }

  function get_id_and_field($fieldname, $where_clause="") 
  {
    $companycontact = new CompanyContact;
    $companycontact_array = $companycontact->_get_id_and_field($fieldname, $where_clause);
    $companycontact_array[0] = 'Global';
    return $companycontact_array;
  }


  function remove($id=0) 
  {  
    $companycontact = new CompanyContact;
    $companycontact->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {  
    $companycontact = new CompanyContact;
    return  $companycontact->_get_fieldnames($include_id); 
  }
  function request_field_values($include_id = false) 
  {
    $fieldnames = CompanyContact::get_fields($include_id);
    $nvp_array = array();
 
    foreach ($fieldnames as $fn) {
 
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
 
    }

    return $nvp_array;

  }
}
?>