<?php
/**
 * @package PDSystem
 *
 *
 */
require_once("dto/DTO.class.php");


class DTO_Programme extends DTO {

  function __construct($handle) 
  {
    parent::__construct($handle);
  }

  function _load_by_id() 
  {
    parent::_load_by_id();

    require_once("model/School.class.php");

    $school = School::load_by_id($programme->school_id);
    $this->_school_id = $school->name;

  }


}

?>