<?php

/**
* DTO handling for PhoneHome
* @package OPUS
*/
require_once("dto/DTO.class.php");
/**
* DTO handling for PhoneHome
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see PhoneHome.class.php
* @package OPUS
*
*/

class DTO_PhoneHome extends DTO
{

  function __construct($handle) 
  {
    parent::__construct($handle);
  }

  /**
  * obtain unix timestamps for last notification of phonehome functionality
  * @return an associative array with keys timestamp_install and timestamp_periodic
  */
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