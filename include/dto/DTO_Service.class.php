<?php
/**
 * @package PDSystem
 *
 *
 */
require_once("uuwaf/dto/DTO.class.php");

global $config;

class DTO_Service extends DTO {

	function __construct() 
  {
    global $config;

    parent::__construct($config['pds']['db']['host'], $config['pds']['db']['user'], $config['pds']['db']['pass'], $config['pds']['db']['name'], 36);

    global $logger;
    $logger->log("DTO_Service construct called");
    
    if ($config["pds"]["development_mode"]) $this->_init($config['pds']['db']['host'], $config['pds']['db']['user'], $config['pds']['db']['pass'], $config['pds']['db']['name']);


	}
  function _load() {

    $sql = "SELECT * FROM service;";
    $con = $this->_con;
    $con->query($sql);
    
    $service = $con->fetch_array();
    $this->status = $service['status'];
    $this->transcripts_enabled = $service["transcripts_enabled"];
    $this->emails_enabled = $service["emails_enabled"];


    unset($con);
    
  }
  
  function set_status($status) {
  
    $con = new DB_Connection_PDP();
    $sql = "UPDATE service SET status='".$status."';";
    
    $con->query($sql);
  }
  
  function set_transcripts_enabled($setting=False) {

        $con = new DB_Connection_PDP();

        $sql = "UPDATE service SET transcripts_enabled = '".$setting."';";
        
    $con->query($sql);

      unset($con);

    }

  function set_emails_enabled($setting=False) { 

    $con = new DB_Connection_PDP();

        $sql = "UPDATE service SET emails_enabled = '".$setting."';";
        
    $con->query($sql);

      unset($con);
  }

}

?>