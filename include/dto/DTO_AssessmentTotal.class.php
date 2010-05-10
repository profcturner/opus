<?php

/**
* DTO handling for AssessmentTotal
* @package OPUS
*/
require_once("dto/DTO.class.php");
/**
* DTO handling for AssessmentTotal
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see AssessmentTotal.class.php
* @package OPUS
*
*/

class DTO_AssessmentTotal extends DTO
{
  function __construct($handle) 
  {
    parent::__construct($handle);
  }

  function _get_totals_with_stamps($assessed_id, $regime_id)
  {
    $waf =& UUWAF::get_instance();
    $con = $waf->connections[$this->_handle]->con;

    $query = "select *, UNIX_TIMESTAMP(created) as created_unix, UNIX_TIMESTAMP(assessed) as assessed_unix from assessmenttotal where assessed_id=? and regime_id=?";
    try
    {
      $sql = $con->prepare($query);
      $sql->execute(array($assessed_id, $regime_id));

      $results_row = $sql->fetch(PDO::FETCH_ASSOC);
      return($results_row);
    }
    catch (PDOException $e)
    {
      $this->_log_sql_error($e, "AssessmentTotal", "_get_totals_with_stamps()");
    }
    return $object_array;
  }
  
  function _copy_results($old_regime_id, $new_regime_id)
  {
	$waf =& UUWAF::get_instance();
    $con = $waf->connections[$this->_handle]->con;

    $tmp_tablename = "tmp_at_" . $old_regime_id . "_" . $new_regime_id;
    try
    {
      $query = "create temporary table ? type = heap select * from assessmenttotal where regime_id= ?";
      $sql = $con->prepare($query);
      $sql->execute(array($tmp_tablename, $old_regime_id));
      
      $query = "update ? set regime_id = ? where regime_id = ?";
      $sql = $con->prepare($query);
      $sql->execute(array($tmp_tablename, $old_regime_id, $new_regime_id));

      $query = "insert into assessmenttotal select * from ?";
      $sql = $con->prepare($query);
      $sql->execute(array($tmp_tablename));
      
      $query = "drop table ?";
      $sql = $con->prepare($query);
      $sql->execute(array($tmp_tablename));
    }
    catch (PDOException $e)
    {
      $this->_log_sql_error($e, "AssessmentResult", "copy_results($old_regime_id, $new_regime_id)");
    }
    return $object_array; 
  }

}

?>
