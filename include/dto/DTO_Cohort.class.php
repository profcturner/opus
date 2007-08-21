<?php
/**
 * @package PDSystem
 *
 *
 */
require_once("uuwaf/dto/DTO.class.php");

class DTO_Cohort extends DTO {

  function __construct() 
  {
    global $config;

    parent::__construct($config['pds']['db']['host'], $config['pds']['db']['user'], $config['pds']['db']['pass'], $config['pds']['db']['name'], 36);

    global $logger;
    $logger->log("DTO_Cohort construct called");
    
    if ($config["pds"]["development_mode"]) $this->_init($config['pds']['db']['host'], $config['pds']['db']['user'], $config['pds']['db']['pass'], $config['pds']['db']['name']);

  }

  function _get_my_cohorts($student_id) 
  {
    
    global $con;
    $object_array = array();
    $sql = $con->prepare("SELECT `cohort_id` FROM `cohort_student_link` 
                            WHERE `student_id` = ?");
    $sql->execute(array($student_id));
    while ($results_row = $sql->fetch(PDO::FETCH_ASSOC))
    {
      $object_array[] = $this->load_by_id($results_row["cohort_id"]);
    }
    return $object_array; 
  }
}

?>