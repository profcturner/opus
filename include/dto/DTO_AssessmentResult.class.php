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

  /**
  * copies assessment results from one regime item to another
  * 
  * This function is rarely used, and only for the rare and dangerous
  * occaision that an assessment regime is changed mid flow. It allows
  * all the results to be copied from essentially the same assessment
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
	  $query = "insert into assessmentresult (regime_id, assessed_id, name, contents) select ?, assessed_id, name, contents from assessmentresult where regime_id=?";
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
