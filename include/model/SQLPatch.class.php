<?php

/**
* Functionality to upgrade SQL schema from one version to another
* @package OPUS
*/
require_once("dto/DTO_SQLPatch.class.php");
/**
* Functionality to upgrade SQL schema from one version to another
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/

class SQLPatch extends DTO_SQLPatch
{
  function __construct() 
  {
    parent::__construct('default');
  }
}

?>