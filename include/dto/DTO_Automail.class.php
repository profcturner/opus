<?php

/**
* DTO handling for Automail
* @package OPUS
*/
require_once("dto/DTO.class.php");
/**
* DTO handling for Automail
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see Automail.class.php
* @package OPUS
*
*/

class DTO_Automail extends DTO
{

  function __construct($handle) 
  {
    parent::__construct($handle);
  }

  function _load_by_id($id = 0)
  {
    parent::_load_by_id($id = 0);
    require_once("model/Language.class.php");
    $this->_language_id = Language::get_name($this->language_id);
  }

  function _load_by_lookup($lookup, $language_id = 1)
  {
    $waf =& UUWAF::get_instance();

    $con = $waf->connections[$this->_handle]->con;

    try
    {
      $sql = $con->prepare("select id from automail where lookup=? and language_id=?");
      $sql->execute(array($lookup, $language_id));

      $results_row = $sql->fetch(PDO::FETCH_ASSOC);
      if($results_row['id'])
      {
        return $this->load_by_id($results_row['id']);
      }
      else
      {
        $waf->log("unable to find automail lookup $lookup for language_id $language_id", PEAR_LOG_NOTICE, 'debug');
        return false;
      }
    }
    catch (PDOException $e)
    {
      $this->_log_sql_error($e, $class, "_get_all()");
    }
  }
}

?>