<?php
  require_once 'Log.php';

class PDS_Log extends Log 
{
  function __construct() 
  {
    $conf = array('mode' => 0600, 'timeFormat' => '%X %x');
    $logger = &Log::singleton('file', 'out.log', 'ident', $conf);
    //$log = &PDS_Log::singleton('file', 'pds.log', 'PDS');
    return $logger;
  }

  function write($message) 
  {
    $log_fil = new PDS_Log();
    $log_fil->log($message, PEAR_LOG_NOTICE);
  }
}  
  
?>