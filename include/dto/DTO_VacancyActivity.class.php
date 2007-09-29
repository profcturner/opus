<?php
/**
 * @package OPUS
 *
 *
 */
require_once("dto/DTO.class.php");

class DTO_VacancyActivity extends DTO {

  function __construct($handle) 
  {
    parent::__construct($handle);
  }

  function _get_activity_ids_for_vacancy($vacancy_id)
  {
    global $waf;
    $con = $waf->connections[$this->_handle]->con;

    $results_array = array();
    try
    {
      $sql = $con->prepare("select activity_id from vacancyactivity where vacancy_id=?");
      $sql->execute(array($vacancy_id));

      while($results = $sql->fetch(PDO::FETCH_ASSOC))
      {
        array_push($results_array, $results['activity_id']);
      }
    }
    catch (PDOException $e)
    {
      $this->_log_sql_error($e, "VacancyActivity", "_get_all($vacancy_id)");
    }
    return $results_array;
  }
}

?>