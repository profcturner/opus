<?php
/**
 * @package OPUS
 *
 *
 */
require_once("dto/DTO.class.php");

class DTO_AssessmentGroupProgramme extends DTO
{
  function __construct($handle) 
  {
    parent::__construct($handle);
  }

  function _load_by_id($id=0)
  {
    parent::_load_by_id($id);

    require_once("model/AssessmentGroup.class.php");
    $this->_group_id = AssessmentGroup::get_name($this->group_id);
  }
}

?>