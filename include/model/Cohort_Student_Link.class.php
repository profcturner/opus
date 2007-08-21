<?php

require_once("waf/dto/DTO.class.php");

/**
* The cohort student link business model object is used to interact with the project data transition object
*
**/

class Cohort_Student_Link extends DTO 
{

  /** @var integer The user id of the instance owner. */

  var $student_id = 0;

  /** @var integer */

  var $cohort_id = 0;


  /** @var array The definitions for each of the variables in this class/object. */

  var $_field_defs = array
  (
    'student_id'=>array
    (
      'type'=>'integer', 
      'size'=>5, 
      'title'=>'student_id', 
      'header'=>true
    ),
    'cohort_id'=>array
    (
      'type'=>'integer', 
      'size'=>5, 
      'title'=>'cohort_id', 
      'header'=>true
    )
  );

/**
 * Constructor for the Cohort_Student_Link class, this explicitly calls the constructor of the DTO_Cohort_Student_Link class
 *
 * <code>
 *  parent::__construct();
 *  global $logger;
 *  $logger->log("Cohort_Student_Link construct called");
 *  $logger->log($this);
 * </code>
 * @see DTO_Cohort_Student_Link::__construct()
 * 
 *
 */

  function __construct() 
  {
    global $config;
    parent::__construct($config['pds']['db']['host'], $config['pds']['db']['user'], $config['pds']['db']['pass'], $config['pds']['db']['name']);
    global $logger;
    $logger->log("Cohort_Student_Link construct called");
    $logger->log($this);
  }
  

}
?>