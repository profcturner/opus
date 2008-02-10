<?php

/**
* Report for listing companies in the database
* @package OPUS
*/
require_once("model/Report.class.php");
/**
* Report for listing companies in the database
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/

class companylist extends Report
{
  function __construct()
  {
    parent::__construct();

    $this->unique_name = "u3:opus:companylist";
    $this->human_name = "Company List";
    $this->description = "Lists companies that match various criteria";
    $this->version = "1.0";
    $this->input_stages = 1;
  }

  function input_stage_1()
  {
  }

  function get_header()
  {
    return(array("name", "locality"));
  }

  function get_body()
  {
    $results = array();

    require_once("model/Company.class.php");

    // Very primitive proof of concept code
    $companies = Company::get_all();

    foreach($companies as $company)
    {
      array_push($results, array($company->name, $company->locality));
    }
    return $results;
  }

}


?>