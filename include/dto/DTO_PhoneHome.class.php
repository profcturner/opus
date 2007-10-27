<?php
/**
 * @package OPUS
 *
 *
 */
require_once("dto/DTO.class.php");

class DTO_PhoneHome extends DTO {

  function __construct($handle) 
  {
    parent::__construct($handle);
  }

  function _get_unixtimes()
  {
    global $waf;
    $con = $waf->connections[$this->_handle]->con;
    try
    {
      $sql = $con->prepare("select unix_timestamp(timestamp_install) as unix_install, unix_timestamp(timestamp_periodic) as unix_periodic from phonehome where id=1");
      $sql->execute();

      $results_row = $sql->fetch(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e)
    {
      $this->_log_sql_error($e, $class, "_get_unixtimes()");
    }
    return $results_row;
  }

}

?>