<?php

/**
* DTO handling for Placement
* @package OPUS
*/
require_once("dto/DTO.class.php");
/**
* DTO handling for Placement
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see Placement.class.php
* @package OPUS
*
*/

class DTO_Placement extends DTO
{

  function __construct($handle='default') 
  {
    parent::__construct($handle);
  }

  /**
  * augments standard loading to add company and vacancy details
  */
  function _load_by_id($id = 0)
  {
    parent::_load_by_id($id);

    require_once("model/Company.class.php");
    $this->_company_id = Company::get_name($this->company_id);
    require_once("model/Vacancy.class.php");
    $this->_vacancy_id = Vacancy::get_name($this->vacancy_id);
  }
}

?>