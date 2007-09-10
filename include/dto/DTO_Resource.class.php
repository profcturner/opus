<?php
/**
 * @package PDSystem
 *
 *
 */
require_once("dto/DTO.class.php");
require_once("model/Channel.class.php");

class DTO_Resource extends DTO {

  function __construct($handle) 
  {
    parent::__construct($handle);
  }

  function _load_by_id() 
  {
    parent::_load_by_id();

    $channel = Channel::load_by_id($this->channel_id);
    $this->_channel_id = $channel->name;

  }


}

?>