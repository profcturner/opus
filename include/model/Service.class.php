<?php

require_once('pds/dto/DTO_Service.class.php');

class Service extends DTO_Service {

  var $status = "";
  var $transcripts_enabled = False;
  var $emails_enabled = False;

  var $_field_defs = array
   (
      'status'=>array('type'=>'list','values'=>array('started','stopped'), 'header'=>true),
      'transcripts_enabled'=>array('type'=>'list','values'=>array('on','off'), 'header'=>true),
      'emails_enabled'=>array('type'=>'list','values'=>array('on','off'), 'header'=>true)
   );

  function __construct() 
  {
    parent::__construct();
    global $logger;
    $logger->log("Service construct called");
    $logger->log($this);
  }
  
  function load() {
  
    $service = new Service();
    $service->_load();
    
    return $service;
  }

  function is_started() {

    $service = new DTO_Service;
    $service->load();
    
    if ($service->status == "started") {
      return True;
    } else {
      return False;
    } 
  }

  function are_transcripts_enabled() {

    $service = new DTO_Service;
    $service->load();
    
    if ($service->transcripts_enabled == "true") {
      return True;
    } else {
      return False;
    } 
  }

  function are_emails_enabled() {

    $service = new DTO_Service;
    $service->load();
    
    if ($service->emails_enabled == "true") {
      return True;
    } else {
      return False;
    } 
    
  }
  
  function start() {
  
    $service = new DTO_Service;
    $service->set_status("started");
  
  }
  
  function stop() {
  
    $service = new DTO_Service;
    $service->set_status("stopped");
    
  }
  
  function enable_transcripts() {

    $service = new DTO_Service;
    $service->set_transcripts_enabled("true");
  }

  function disable_transcripts() {

    $service = new DTO_Service;
    $service->set_transcripts_enabled("false"); 
  }

}

?>