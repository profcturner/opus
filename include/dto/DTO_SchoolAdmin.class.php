<?php

/**
* DTO handling for SchoolAdmin
* @package OPUS
*/
require_once("dto/DTO.class.php");
/**
* DTO handling for SchoolAdmin
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see SchoolAdmin.class.php
* @package OPUS
*
*/

class DTO_SchoolAdmin extends DTO
{
  function __construct($handle) 
  {
    parent::__construct($handle);
  }

  /**
  * augments normal loading with any policy name
  */
  function _load_by_id($id=0)
  {
    // Base class
    parent::_load_by_id($id);

    require_once("model/School.class.php");
    require_once("model/Policy.class.php");
    require_once("model/User.class.php");

    $this->_school_id = School::get_name($this->school_id);
    $this->_admin_id = User::get_name($this->admin_id);

    if($this->policy_id)
    {
      $this->_policy_id = Policy::get_name($this->policy_id);
    }
    else
    {
      $this->_policy_id = "Default for Administrator";
    }
  }
}

?>