<?php
/**
 * @package PDSystem
 *
 */

require_once("model/User.class.php");

Class Academic extends User 
{
	var $room = ''; 
	var $ext = '';

  var $_new_field_defs = array
  (
    'room'=>array('type'=>'text','size'=>15, 'header'=>true),
    'ext'=>array('type'=>'text','size'=>20, 'header'=>false),
  );

  function __construct() 
  {
    
    $this->type = 'academic';
    $this->_field_defs = array_merge($_field_defs, $$this->_new_field_defs);
    parent::__construct();
    global $logger;
    $logger->log("Academic construct called");
    $logger->log($this);
  }
}  

?>