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
  
  /**
  * copies assessment totals from one regime item to another
  * 
  * This function is rarely used, and only for the rare and dangerous
  * occaision that an assessment regime is changed mid flow. It allows
  * all the totals to be copied from essentially the same assessment
  * under a different regime item to its counterpart in a new regime
  *
  * @param $old_regime_id the regime_id of the old assessment
  * @param $new_regime_id the regime_id of the new assessment
  */  
  function _copy_results($old_regime_id, $new_regime_id)
  {
	$waf =& UUWAF::get_instance();
    $con = $waf->connections[$this->_handle]->con;

    try
    {
      $query = "insert into assessmenttotal (regime_id, assessed_id, assessor_id, comments, mark, outof, percentage, created, modified, assessed) select ?, assessed_id,assessor_id, comments, mark, outof, percentage, created, modified, assessed from assessmenttotal where regime_id=?";
      $sql = $con->prepare($query);
      $sql->execute(array($new_regime_id, $old_regime_id));
    }
    catch (PDOException $e)
    {
      $this->_log_sql_error($e, "AssessmentResult", "copy_results($old_regime_id, $new_regime_id)");
    }
    return $object_array; 
  }

}

?>
