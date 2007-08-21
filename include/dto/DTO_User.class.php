<?php
/**
 * @package PDSystem
 *
 *
 */
require_once("uuwaf/dto/DTO.class.php");

class DTO_User extends DTO {

	function DTO_User($handle) 
  {
    global $waf;

    parent::__construct($handle);

    $waf->log("DTO_User construct called", PEAR_LOG_INFO, 'info');
  
	}

}

?>