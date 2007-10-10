<?php
/**
 * @package PDSystem
 *
 *
 */
require_once("dto/DTO.class.php");

class DTO_Application extends DTO {

  function __construct($handle) 
  {
    parent::__construct($handle);
  }

  function _load_by_id($id = 0)
  {
    parent::_load_by_id($id);

    require_once("model/Company.class.php");
    require_once("model/Vacancy.class.php");
    $this->_company_id = Company::get_name($this->company_id);
    $this->_vacancy_id = Vacancy::get_name($this->vacancy_id);
  }
}

?>