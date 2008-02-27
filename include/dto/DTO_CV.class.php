<?php

/**
* DTO handling for CV
* @package OPUS
*/
require_once("dto/DTO.class.php");
/**
* DTO handling for CV
*
* @author Gordon Crawford <g.crawford@ulster.ac.uk>
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see CV.class.php
* @package OPUS
*
*/

class DTO_CV extends DTO
{
  function __construct($handle = 'default') 
  {
    parent::__construct($handle);
  }

  function _load_by_id($parse = False)
  {
    parent::_load_by_id();
  }
}

?>