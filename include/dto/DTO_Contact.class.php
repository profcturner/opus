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
    require_once("dto/User.class.php");
    parent::_load_by_id();

    $user = User::load_by_id($this->user_id);
    $fields = $user->_get_fieldnames(false);

    foreach($fields as $key => $value)
    {
      $new_key = "_$key";
      $this->$newkey = $value;
    }
  }

  function _get_all_by_company($company_id = 0)
  {

  }
}

?>