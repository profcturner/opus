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

}

?>