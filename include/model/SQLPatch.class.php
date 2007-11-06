<?php
/**
 * @package OPUS
 *
 */

require_once("dto/DTO_SQLPatch.class.php");

class SQLPatch extends DTO_SQLPatch
{
  function __construct() 
  {
    parent::__construct('default');
  }
}