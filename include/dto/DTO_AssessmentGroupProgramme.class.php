<?php
/**
 * @package OPUS
 *
 *
 */
require_once("dto/DTO.class.php");

class DTO_AssessmentGroupProgramme extends DTO
{
  function __construct($handle) 
  {
    parent::__construct($handle);
  }

  function _load_by_id($id=0)
  {
    parent::_load_by_id($id);

    require_once("model/AssessmentGroup.class.php");
    $this->_group_id = AssessmentGroup::get_name($this->group_id);
  }

  function _get_all_programmes($group_id, $year)
  {
    global $waf;
    $con = $waf->connections[$this->_handle]->con;

    try
    {
      $sql = $con->prepare("select * from assessmentgroupprogramme where group_id = ?");
      $sql->execute(array($group_id));

      while ($results_row = $sql->fetch(PDO::FETCH_ASSOC))
      {

      }
    }
    catch (PDOException $e)
    {
      $this->_log_sql_error($e, "AssessmentGroupProgramme", "_get_all_programmes($group_id, $year)");
    }
    return $object_array;
  }
}

?>