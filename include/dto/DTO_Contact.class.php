<?php
/**
 * @package OPUS
 *
 *
 */
require_once("dto/DTO.class.php");

class DTO_Contact extends DTO {

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
      $new_key = "$field";
      $this->$new_key = $user->$field;
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
      $new_key = "$field";
      $this->$new_key = $user->$field;
    }
  }

  function _get_all_by_company($company_id = 0)
  {
    global $waf;

    require_once("model/CompanyContact.class.php");

    $con = $waf->connections[$this->_handle]->con;

    try
    {
      $sql = $con->prepare("select contact_id from contact left join companycontact on contact.user_id = companycontact.contact_id where company_id=?");
      $sql->execute(array($company_id));

      while ($results_row = $sql->fetch(PDO::FETCH_ASSOC))
      {
        $contact_id = $results_row["contact_id"];
        $object_array[] = $this->load_by_user_id($contact_id);
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