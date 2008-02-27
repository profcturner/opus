<?php

/**
* DTO handling for ProgrammeAdmin
* @package OPUS
*/
require_once("dto/DTO.class.php");
/**
* DTO handling for ProgrammeAdmin
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see ProgrammeAdmin.class.php
* @package OPUS
*
*/

class DTO_ProgrammeAdmin extends DTO
{
  function __construct($handle) 
  {
    parent::__construct($handle);
  }

  /**
  * augments standard loading with policy names
  */
  function _load_by_id($id=0)
  {
    // Base class
    parent::_load_by_id($id);

    require_once("model/Programme.class.php");
    require_once("model/Policy.class.php");
    require_once("model/User.class.php");

    $this->_programme_id = Programme::get_name($this->programme_id);
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