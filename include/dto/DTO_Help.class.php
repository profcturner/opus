<?php

/**
* DTO handling for Help
* @package OPUS
*/
require_once("dto/DTO.class.php");
/**
* DTO handling for Help
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see Help.class.php
* @package OPUS
*
*/

class DTO_Help extends DTO
{

  function __construct($handle) 
  {
    parent::__construct($handle);
  }

  function _load_by_id() 
  {
    parent::_load_by_id();

    require_once("model/Channel.class.php");
    $channel = Channel::load_by_id($this->channel_id);
    $this->_channel_id = $channel->name;
  }

}

?>