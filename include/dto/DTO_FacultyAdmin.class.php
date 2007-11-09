<?php

/**
* DTO handling for FacultyAdmin
* @package OPUS
*/
require_once("dto/DTO.class.php");
/**
* DTO handling for FacultyAdmin
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see FacultyAdmin.class.php
* @package OPUS
*
*/

class DTO_FacultyAdmin extends DTO
{
  function __construct($handle) 
  {
    parent::__construct($handle);
  }

  function _load_by_id($id=0)
  {
    // Base class
    parent::_load_by_id($id);

    require_once("model/Faculty.class.php");
    require_once("model/Policy.class.php");
    require_once("model/User.class.php");

    $this->_faculty_id = Faculty::get_name($this->faculty_id);
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