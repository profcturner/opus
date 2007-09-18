<?php
/**
 * @package OPUS
 *
 *
 */
require_once("dto/DTO.class.php");

class DTO_Automail extends DTO {

  function __construct($handle) 
  {
    parent::__construct($handle);
  }

  function _load_by_lookup($lookup, $language_id = 1)
  {
    global $waf;

    $con = $waf->connections[$this->_handle]->con;

    try
    {
      $sql = $con->prepare("select id from automail where lookup=? and language_id=?");
      $sql->execute(array($lookup, $language_id));

      $results_row = $sql->fetch(PDO::FETCH_ASSOC);
      if($results_row['id'])
      {
        return $this->load_by_id($id);
      }
      else
      {
        $waf->log("unable to find automail lookup $lookup for language_id $language_id", PEAR_LOG_NOTICE, 'debug');
      }
    }
    catch (PDOException $e)
    {
      $this->_log_sql_error($e, $class, "_get_all()");
    }
    return $object_array; 
  }
}

?>