<?php
/**
 * @package PDSystem
 *
 *
 */
require_once("dto/DTO.class.php");

class DTO_School extends DTO {

  function __construct($handle) 
  {
    parent::__construct($handle);
  }

  function _load_by_id() 
  {
    parent::_load_by_id();

    require_once("model/Faculty.class.php");

    $faculty = Faculty::load_by_id($school->faculty_id);
    $this->_faculty_id = $faculty->name;

  }
}

?>