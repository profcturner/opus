<?php

/**
* DTO handling for Resource
* @package OPUS
*/
require_once("dto/DTO.class.php");
/**
* DTO handling for Resource
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see Resource.class.php
* @package OPUS
*
*/
require_once("dto/DTO.class.php");

class DTO_Resource extends DTO
{
  function __construct($handle) 
  {
    parent::__construct($handle);
  }

  /**
  * augments the normal load with the name of any channel
  */
  function _load_by_id() 
  {
    parent::_load_by_id();

    require_once("model/Channel.class.php");
    $channel = Channel::load_by_id($this->channel_id);
    $this->_channel_id = $channel->name;
  }
}

?>