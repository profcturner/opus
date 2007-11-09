<?php

/**
* DTO handling for School
* @package OPUS
*/
require_once("dto/DTO.class.php");
/**
* DTO handling for School
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see School.class.php
* @package OPUS
*
*/

class DTO_School extends DTO
{

  function __construct($handle) 
  {
    parent::__construct($handle);
  }

  /**
  * augments normal loading with the name of any faculty
  */
  function _load_by_id() 
  {
    parent::_load_by_id();

    require_once("model/Faculty.class.php");

    $faculty = Faculty::load_by_id($school->faculty_id);
    $this->_faculty_id = $faculty->name;

  }
}

?>