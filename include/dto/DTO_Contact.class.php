<?php

/**
* DTO handling for Contact
* @package OPUS
*/
require_once("dto/DTO.class.php");
/**
* DTO handling for Contact
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see Contact.class.php
* @package OPUS
*
*/

class DTO_Contact extends DTO
{

  function __construct($handle='default') 
  {
    parent::__construct($handle);
  }

  function _load_by_id($id=0)
  {
    require_once("model/User.class.php");
    parent::_load_by_id($id);

    $user = User::load_by_id($this->user_id);
    $fields = $user->_get_fieldnames(false);

    foreach($fields as $field)
    {
      $this->$field = $user->$field;
    }
  }

  function _load_by_user_id($user_id=0)
  {
    require_once("model/User.class.php");
    $this->user_id = $user_id;
    $this->_load_by_field("user_id");

    $user = User::load_by_id($user_id);
    $fields = $user->_get_fieldnames(false);

    foreach($fields as $field)
    {
      $this->$field = $user->$field;
    }
  }

  function _get_all($where_clause="", $order_by="order by user.lastname", $start=0, $limit=MAX_ROWS_RETURNED, $parse = False) 
  {
    $waf =& UUWAF::get_instance();
    $con = $waf->connections[$this->_handle]->con;

    if($waf->waf_debug)
    {
      $waf->log("$class::_get_all() called [$where_clause:$order_by:$start:$limit]", PEAR_LOG_DEBUG, "waf_debug");
    }

    $object_array = array();
    if (!($start >= 0)) $start = 0; 

    try
    {
      $sql = $con->prepare("SELECT contact.id FROM `contact` left join user on contact.user_id = user.id $where_clause $order_by LIMIT $start, $limit;");
      $sql->execute();

      while ($results_row = $sql->fetch(PDO::FETCH_ASSOC))
      {
        $id = $results_row["id"];
        $object_array[] = $this->load_by_id($id, $parse);
      }
    }
    catch (PDOException $e)
    {
      $this->_log_sql_error($e, $class, "_get_all()");
    }
    return $object_array; 
  }

  function _get_all_by_company($company_id = 0)
  {
    $waf =& UUWAF::get_instance();

    require_once("model/CompanyContact.class.php");

    $con = $waf->connections[$this->_handle]->con;
    $object_array = array();
    try
    {
      $sql = $con->prepare("select contact_id, status from contact left join companycontact on contact.user_id = companycontact.contact_id left join user on contact.user_id = user.id where company_id=? order by status, lastname");
      $sql->execute(array($company_id));

      while ($results_row = $sql->fetch(PDO::FETCH_ASSOC))
      {
        $contact_id = $results_row["contact_id"];
        $contact = $this->load_by_user_id($contact_id);
        $contact->status = $results_row['status'];
        array_push($object_array, $contact);
      }
    }
    catch (PDOException $e)
    {
      $this->_log_sql_error($e, $class, "_get_all()");
    }
    return $object_array; 
  }
}

?>