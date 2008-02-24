<?php

/**
* DTO handling for ChannelAssociation
* @package OPUS
*/
require_once("dto/DTO.class.php");
/**
* DTO handling for ChannelAssociation
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see ChannelAssociation.class.php
* @package OPUS
*
*/

class DTO_ChannelAssociation extends DTO
{

  function __construct($handle) 
  {
    parent::__construct($handle);
  }

  function _insert($fields)
  {
    global $waf;

    $con = $waf->connections[$this->_handle]->con;

    try
    {
      $con->beginTransaction();

      $sql = $con->prepare("SELECT MAX(priority) FROM channelassociation WHERE channel_id=?");
      $sql->execute(array($fields['channel_id']));
      $row = $sql->fetch(PDO::FETCH_NUM);

      // Next priority will be one up
      $fields['priority'] = $row[0]+1;
      $sql->fetchAll();

      parent::_insert($fields);
      $con->commit();
    }
    catch (PDOException $e)
    {
      $this->_log_sql_error($e, "channelassociation", "_insert()");
    }
  }
  /**
  *
  * @param $channel_id The assessment to modify
  * @param $priority The "row" to move downwards, an internal variable
  */
  function _move_down($channel_id, $priority)
  {
    global $waf;

    $con = $waf->connections[$this->_handle]->con;

    try
    {
      $con->beginTransaction();

      $sql = $con->prepare("SELECT priority FROM channelassociation WHERE channel_id=? ORDER BY priority");
      $sql->execute(array($channel_id));
  
      // Look for our chosen entry, and the one above...
      $next = 0;
      $found = FALSE;
      while(!$found && ($row = $sql->fetch(PDO::FETCH_NUM)))
      {
        if($row[0] == $priority){
          $found = TRUE;
          $row = $sql->fetch(PDO::FETCH_NUM);
          $next = $row[0];
        }
      }
      if($found && $next)
      {
        // safe to proceed
        $sql->fetchAll();
        $this->_swaprows($channel_id, $priority, $next);
        $con->commit();
      }
      else
      {
        $sql->fetchAll();
        $con->rollBack();
        $waf->log("Unable to reorder channel association item down", PEAR_LOG_DEBUG, 'debug');
      }
    }
    catch (PDOException $e)
    {
      $this->_log_sql_error($e, "channelassociation", "movedown()");
    }
  }

  /**
  *
  * Moves a variable upwards in the priority list for an assessment.
  * This function provides full table locking for safety.
  * 
  * @param $channel_id The assessment to modify
  * @param $priority The "row" to move upwards, an internal variable
  */
  function _move_up($channel_id, $priority)
  {
    global $waf;

    $con = $waf->connections[$this->_handle]->con;

    try
    {
      $con->beginTransaction();

      $sql = $con->prepare("SELECT priority FROM channelassociation WHERE channel_id=? ORDER BY priority");
      $sql->execute(array($channel_id));

      // Look for our chosen entry, and the one above...
      $previous = 0;
      $found = FALSE;
      while(!$found && ($row = $sql->fetch(PDO::FETCH_NUM)))
      {
        if($row[0] == $priority) $found = TRUE;
        else $previous = $row[0];
      }
      if($found && $previous)
      {
        // safe to proceed
        $sql->fetchAll();
        $this->_swaprows($channel_id, $priority, $previous);
        $con->commit();
      }
      else
      {
        $sql->fetchAll();
        $con->rollBack();
        $waf->log("Unable to reorder channel association item up", PEAR_LOG_DEBUG, 'debug');
      }
    }
    catch (PDOException $e)
    {
      $this->_log_sql_error($e, "channelassociation", "movedown()");
    }
  }

  function _swaprows($channel_id, $priority1, $priority2)
  {
    global $waf;
    $con = $waf->connections[$this->_handle]->con;

    // @todo Need transaction code here really!
    try
    {
      $sql = $con->prepare("UPDATE channelassociation SET priority=? where priority=? and channel_id=?");

      // Place one in order zero for now
      $sql->execute(array(0, $priority1, $channel_id));
      // Move the new one in
      $sql->execute(array($priority1, $priority2, $channel_id));
      // Move zero to the new value
      $sql->execute(array($priority2, 0, $channel_id));
    }
    catch (PDOException $e)
    {
      $this->_log_sql_error($e, "channelassociation", "swaprows()");
    }
    return $results_row[0];
  }

  /**
  * Gets all the associations for a channel, with appropriate names
  */
  function _get_all_extended($channel_id)
  {
    global $waf;
    $con = $waf->connections[$this->_handle]->con;

    $object_array = array();
    try
    {
      $sql = $con->prepare("select * from channelassociation where channel_id=? order by priority");
      $sql->execute(array($channel_id));

      while ($results_row = $sql->fetch(PDO::FETCH_ASSOC))
      {
        // Augment array
        switch($results_row['type'])
        {
          case "course" : // legacy name, programme now...
            require_once("model/Programme.class.php");
            $results_row['object_name'] = Programme::get_name($results_row['object_id']);
            $results_row['type'] = "programme";
            break;
          case "school" :
            require_once("model/School.class.php");
            $results_row['object_name'] = School::get_name($results_row['object_id']);
            break;
          case "assessmentgroup" :
            require_once("model/AssessmentGroup.class.php");
            $results_row['object_name'] = AssessmentGroup::get_name($results_row['object_id']);
            break;
          case "activity" :
            require_once("model/Activitytype.class.php");
            $results_row['object_name'] = Activitytype::get_name($results_row['object_id']);
            break;
          case "user" :
            require_once("model/User.class.php");
            $results_row['object_name'] = User::get_name($results_row['object_id']);
            break;

        }
        array_push($object_array, $results_row);
      }
    }
    catch (PDOException $e)
    {
      $this->_log_sql_error($e, "ChannelAssociation", "_get_all_extended()");
    }
    return $object_array;
  }

}

?>