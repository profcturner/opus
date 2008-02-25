<?php

/**
* Handles the logic for approving CVs (if this is required by the group)
* @package OPUS
*/
require_once("dto/DTO_CVApproval.class.php");
/**
* Handles the logic for approving CVs (if this is required by the group)
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/

class CVApproval extends DTO_CVApproval 
{
  /** @var int the user_id of the student */
  var $student_id = 0;
  /** @var string an identifying string for the CV resource */
  var $cv_ident = "";
  /** @var int the user_id the user approving the CV */
  var $approver_id = 0;
  /** @var the datestamp of the approval */
  var $datestamp = 0;

  // Not needed, all handled internally
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
    $cvapproval = new CVApproval;
    $cvapproval->id = $id;
    $cvapproval->_load_by_id();
    return $cvapproval;
  }

  function insert($fields) 
  {
    $cvapproval = new CVApproval;
    $cvapproval->_insert($fields);
  }

  function update($fields) 
  {
    $cvapproval = CVApproval::load_by_id($fields[id]);
    $cvapproval->_update($fields);
  }

  function exists($id) 
  {
    $cvapproval = new CVApproval;
    $cvapproval->id = $id;
    return $cvapproval->_exists();
  }

  function count($where_clause="") 
  {
    $cvapproval = new CVApproval;
    return $cvapproval->_count($where_clause);
  }

  function get_all($where_clause="", $order_by="ORDER BY id", $page=0)
  {
    global $config;
    $cvapproval = new CVApproval;

    if ($page <> 0)
    {
      $start = ($page-1)*$config['opus']['rows_per_page'];;
      $limit = $config['opus']['rows_per_page'];;
      $cvapprovals = $cvapproval->_get_all($where_clause, $order_by, $start, $limit);
    }
    else
    {
      $cvapprovals = $cvapproval->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $cvapprovals;
  }

  function get_id_and_field($fieldname, $where_clause="") 
  {
    $cvapproval = new CVApproval;
    $cvapproval_array = $cvapproval->_get_id_and_field($fieldname, $where_clause);
    unset($cvapproval_array[0]);
    return $cvapproval_array;
  }

  function remove($id=0) 
  {
    $cvapproval = new CVApproval;
    $cvapproval->_remove_where("WHERE id=$id");
  }

  function remove_where($where_clause) 
  {
    $cvapproval = new CVApproval;
    $cvapproval->_remove_where($where_clause);
  }

  function get_fields($include_id = false) 
  {
    $cvapproval = new CVApproval;
    return  $cvapproval->_get_fieldnames($include_id); 
  }

  function request_field_values($include_id = false) 
  {
    $fieldnames = CVApproval::get_fields($include_id);
    $nvp_array = array();

    foreach ($fieldnames as $fn)
    {
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
    }
    return $nvp_array;
  }

  function check_approval($student_id, $cv_ident)
  {
    $student_id = (int) $student_id;
    if(!preg_match("/^[a-z]+:[a-z]+:[A-Za-z0-9]+$/", $cv_ident)) return false; // silently fail for now

    return(CVApproval::count("where student_id = $student_id and cv_ident = '$cv_ident'"));
  }

  function approve_cv($student_id, $cv_ident)
  {
    // Remove any existing approval
    CVApproval::revoke_cv($student_id, $cv_ident);

    $fields['student_id'] = $student_id;
    $fields['cv_ident'] = $cv_ident;
    $fields['datestamp'] = date('YmdHis');
    $fields['approver_id'] = User::get_id();

    CVApproval::insert($fields);
  }

  function revoke_cv($student_id, $cv_ident)
  {
    if(!preg_match("/^[a-z]+:[a-z]+:[A-Za-z0-9]+$/", $cv_ident)) return; // silently fail for now
    CVApproval::remove_where("where student_id = $student_id and cv_ident = '$cv_ident'");
  }

}
?>