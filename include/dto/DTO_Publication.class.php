<?php
/**
 * @package PDSystem
 *
 *
 */
require_once("uuwaf/dto/DTO.class.php");

class DTO_Publication extends DTO {

  function __construct() 
  {
    global $config;

    parent::__construct($config['pds']['db']['host'], $config['pds']['db']['user'], $config['pds']['db']['pass'], $config['pds']['db']['name'], 36);

    global $logger;
    $logger->log("DTO_Publication construct called");
    
    if ($config["pds"]["development_mode"]) $this->_init($config['pds']['db']['host'], $config['pds']['db']['user'], $config['pds']['db']['pass'], $config['pds']['db']['name']);


  }

}

?>