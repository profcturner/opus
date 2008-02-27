<?php

/**
* DTO handling for Resource
* @package OPUS
*/
require_once("dto/DTO.class.php");
/**
* DTO handling for Resource
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see Resource.class.php
* @package OPUS
*
*/
require_once("dto/DTO.class.php");

class DTO_Resource extends DTO
{
  function __construct($handle) 
  {
    parent::__construct($handle);
  }

  /**
  * augments the normal load with the name of any channel
  */
  function _load_by_id() 
  {
    parent::_load_by_id();

    require_once("model/Channel.class.php");
    $channel = Channel::load_by_id($this->channel_id);
    $this->_channel_id = $channel->name;
  }

  /**
  * get resources for a company
  */
  function _get_all_by_company($company_id)
  {
    global $waf;
    $con = $waf->connections[$this->_handle]->con;

    $object_array = array();
    try
    {
      $sql = $con->prepare("select resource.id from resource left join resourcelink on resource.id = resourcelink.resource_id where resourcelink.company_id=? order by description");
      $sql->execute(array($company_id));

      while ($results_row = $sql->fetch(PDO::FETCH_ASSOC))
      {
        $resource = Resource::load_by_id($results_row['id']);
        array_push($object_array, $resource);
      }
    }
    catch (PDOException $e)
    {
      $this->_log_sql_error($e, "Resource", "_get_all_by_company()");
    }
    return $object_array;
  }

  /**
  * get resources for a vacancy
  */
  function _get_all_by_vacancy($vacancy_id)
  {
    global $waf;
    $con = $waf->connections[$this->_handle]->con;

    $object_array = array();
    try
    {
      $sql = $con->prepare("select resource.id from resource left join resourcelink on resource.id = resourcelink.resource_id where resourcelink.vacancy_id=? order by description");
      $sql->execute(array($vacancy_id));

      while ($results_row = $sql->fetch(PDO::FETCH_ASSOC))
      {
        $resource = Resource::load_by_id($results_row['id']);
        array_push($object_array, $resource);
      }
    }
    catch (PDOException $e)
    {
      $this->_log_sql_error($e, "Resource", "_get_all_by_vacancy()");
    }
    return $object_array;
  }
}

?>