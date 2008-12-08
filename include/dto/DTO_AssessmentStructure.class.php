<?php

/**
* DTO handling for AssessmentStructure
* @package OPUS
*/
require_once("dto/DTO.class.php");
/**
* DTO handling for AssessmentStructure
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see AssessmentStructure.class.php
* @package OPUS
*
*/

class DTO_AssessmentStructure extends DTO
{

  function __construct($handle) 
  {
    parent::__construct($handle);
  }

  /**
  *
  * Moves a variable downwards in the priority list for an assessment.
  * This function provides full table locking for safety.
  * 
  * @param $assessment_id The assessment to modify
  * @param $varorder The "row" to move downwards, an internal variable
  */
  function _move_down($assessment_id, $varorder)
  {
    $waf =& UUWAF::get_instance();

    $con = $waf->connections[$this->_handle]->con;

    try
    {
      $con->beginTransaction();

      $sql = $con->prepare("SELECT varorder FROM assessmentstructure WHERE assessment_id=? ORDER BY varorder");
      $sql->execute(array($assessment_id));

      // Look for our chosen entry, and the one above...
      $next = 0;
      $found = FALSE;
      while(!$found && ($row = $sql->fetch(PDO::FETCH_NUM)))
      {
        if($row[0] == $varorder){
          $found = TRUE;
          $row = $sql->fetch(PDO::FETCH_NUM);
          $next = $row[0];
        }
      }
      if($found && $next)
      {
        // safe to proceed
        $sql->fetchAll();
        $this->_swaprows($assessment_id, $varorder, $next);
        $con->commit();
      }
      else
      {
        $sql->fetchAll();
        $con->rollBack();
        $waf->log("Unable to reorder assessment structure item down", PEAR_LOG_DEBUG, 'debug');
      }
    }
    catch (PDOException $e)
    {
      $this->_log_sql_error($e, "assessmentstructure", "movedown()");
    }
  }

  /**
  *
  * Moves a variable upwards in the priority list for an assessment.
  * This function provides full table locking for safety.
  * 
  * @param $assessment_id The assessment to modify
  * @param $varorder The "row" to move upwards, an internal variable
  */
  function _move_up($assessment_id, $varorder)
  {
    $waf =& UUWAF::get_instance();

    $con = $waf->connections[$this->_handle]->con;

    try
    {
      $con->beginTransaction();

      $sql = $con->prepare("SELECT varorder FROM assessmentstructure WHERE assessment_id=? ORDER BY varorder");
      $sql->execute(array($assessment_id));

      // Look for our chosen entry, and the one above...
      $previous = 0;
      $found = FALSE;
      while(!$found && ($row = $sql->fetch(PDO::FETCH_NUM)))
      {
        if($row[0] == $varorder) $found = TRUE;
        else $previous = $row[0];
      }
      if($found && $previous)
      {
        // safe to proceed
        $sql->fetchAll();
        $this->_swaprows($assessment_id, $varorder, $previous);
        $con->commit();
      }
      else
      {
        $sql->fetchAll();
        $con->rollBack();
        $waf->log("Unable to reorder assessment structure item up", PEAR_LOG_DEBUG, 'debug');
      }
    }
    catch (PDOException $e)
    {
      $this->_log_sql_error($e, "assessmentstructure", "movedown()");
    }
  }

  function _swaprows($assessment_id, $varorder1, $varorder2)
  {
    $waf =& UUWAF::get_instance();
    $con = $waf->connections[$this->_handle]->con;

    // @todo Need transaction code here really!
    try
    {
      $sql = $con->prepare("UPDATE assessmentstructure SET varorder=? where varorder=? and assessment_id=?");

      // Place one in order zero for now
      $sql->execute(array(0, $varorder1, $assessment_id));
      // Move the new one in
      $sql->execute(array($varorder1, $varorder2, $assessment_id));
      // Move zero to the new value
      $sql->execute(array($varorder2, 0, $assessment_id));
    }
    catch (PDOException $e)
    {
      $this->_log_sql_error($e, "assessmentstructure", "swaprows()");
    }
    return $results_row[0];
  }
  
  function _get_highest_varorder_for_assessment($assessment_id)
  {
    $waf =& UUWAF::get_instance();
    $con = $waf->connections[$this->_handle]->con;

    try
    {
      $sql = $con->prepare("SELECT MAX(varorder) from assessmentstructure where assessment_id=?");
      $sql->execute(array($assessment_id));
      
      $results_row = $sql->fetch(PDO::FETCH_NUM);
    }
    catch (PDOException $e)
    {
      $this->_log_sql_error($e, "assessmentstructure", "get_highest_varorder_for_assessment");
    }
    return $results_row[0];
  }  
}

?>