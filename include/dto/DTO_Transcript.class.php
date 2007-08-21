<?php
/**
 * @package PDSystem
 *
 * This is the transcript class, it is populated by a web services call and extends from the
 * DTO_Cache so that the web service calls are kept at a minimum.
 *
 *
 */
require_once("uuwaf/dto/DTO_Cache.class.php");

class DTO_Transcript extends DTO_Cache {

  public function __construct() 
  {
    global $config;

    parent::__construct($config['pds']['db']['host'], $config['pds']['db']['user'], $config['pds']['db']['pass'], $config['pds']['db']['name'], 10);

    global $logger;
    $logger->log("DTO_Transcript construct called");
    
    if ($config["pds"]["development_mode"]) $this->_init($config['pds']['db']['host'], $config['pds']['db']['user'], $config['pds']['db']['pass'], $config['pds']['db']['name']);

  }

/** 
 * This is the refresh object call, this is the expensive call that we want to minimise the number of times it
 * is called.
 *
 *
 * Make a call to the Web Service Layer and ask for a transcript to be returned for student registration provided.
 *
 */

  protected function _refresh() 
  { 
    global $config;

    $this->transcript = file_get_contents( 
      $config['pds']['ws']['url']."/index.php?mode=php&function=get_transcript".
      "&reg_number=$reg_num".
      "&username=".$config['pds']['ws']['username'].
      "&password=".$config['pds']['ws']['password']);
    $this->timestamp = date("Y-m-d H:i:s");
    if ( $this->_exists() ) 
    {
      $this->_update();
    } 
    else 
    {
      $this->_insert();   
    }
  }
}

?>