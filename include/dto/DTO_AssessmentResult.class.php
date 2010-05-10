<?php

/**
* DTO handling for AssessmentResult
* @package OPUS
*/
require_once("dto/DTO.class.php");
/**
* DTO handling for AssessmentResult
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see AssessmentResult.class.php
* @package OPUS
*
*/

class DTO_AssessmentResult extends DTO
{

  function __construct($handle) 
  {
    parent::__construct($handle);
  }
  
  function _copy_results($old_regime_id, $new_regime_id)
  {
	$waf =& UUWAF::get_instance();
    $con = $waf->connections[$this->_handle]->con;

    $tmp_tablename = "tmp_ar_" . $old_regime_id . "_" . $new_regime_id;
    try
    {
      $query = "create temporary table ? type = heap select * from assessmentresult where regime_id= ?";
      $sql = $con->prepare($query);
      $sql->execute(array($tmp_tablename, $old_regime_id));
      
      $query = "update ? set regime_id = ? where regime_id = ?";
      $sql = $con->prepare($query);
      $sql->execute(array($tmp_tablename, $old_regime_id, $new_regime_id));

      $query = "insert into assessmentresult select * from ?";
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
