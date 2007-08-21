<?php
/**
 * @package PDSystem
 *
 * This is the programme team class.
 *
 *
 */
require_once("uuwaf/dto/DTO_Cache.class.php");

class DTO_Programme_Team extends DTO_Cache {

  public function __construct() 
  {
    global $config;

    parent::__construct($config['pds']['db']['host'], $config['pds']['db']['user'], $config['pds']['db']['pass'], $config['pds']['db']['name'], 0);

    global $logger;
    $logger->log("DTO_Programme_Team construct called");
    
    if ($config["pds"]["development_mode"]) $this->_init($config['pds']['db']['host'], $config['pds']['db']['user'], $config['pds']['db']['pass'], $config['pds']['db']['name']);

  }

/** 
 * This is the refresh object call, this is the expensive call that we want to minimise the number of times it
 * is called.
 *
 *
 *
 */

  protected function _refresh() 
  { 
    global $config;

    require_once('pds/model/Cohort.class.php');
    require_once('pds/model/Academic.class.php');

    // check through all cohorts and identify cohorts with this students user_id associated
    // for each cohort identify who the cohort owner is and make a call to the Academic object to
    // retreive their details (this is another DTO_Cache object, as it is web service based
    
    $programme_team = array();

    $cohorts = Cohort::get_my_cohorts($this->user_id);

    foreach ($cohorts as $cohort) 
    {
      $academic = Academic::load_by_id($cohort->user_id);
      array_push($programme_team, array($cohort, $academic));
    }

    $this->programme_team = serialize($programme_team);
    $this->timestamp = date("Y-m-d H:i:s");
    if ( $this->_exists() ) 
    {
      $this->_update();
    } 
    else 
    {
      $this->_insert();   
    }
  }
}

?>