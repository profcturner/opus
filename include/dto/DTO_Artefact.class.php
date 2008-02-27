<?php

/**
* DTO handling for Artefact
* @package OPUS
*/
require_once("dto/DTO.class.php");
/**
* DTO handling for Artefact
*
* @author Gordon Crawford <g.crawford@ulster.ac.uk>
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see Artefact.class.php
* @package OPUS
*
*/

class DTO_Artefact extends DTO 
{
  function __construct($handle = 'default')
  {
    parent::__construct($handle);
  }
}

?>