<?php

/**
* Report for listing companies in the database
* @package OPUS
*/
require_once("model/Report.class.php");
/**
* Report for listing companies in the database
*
* This is currently a very trivial, but hopefully well documented example, of
* how report plugins are written.
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
* @see Report.class.php
*
*/

class companylist extends Report
{
  /**
  * constuctor
  *
  * This calls the parent constructor, and sets up important characteristics of the plugin
  */
  function __construct()
  {
    parent::__construct();

    // Must be unique, don't use u3 unless you're us, so your plugin doesn't get clobbered
    $this->unique_name = "u3:opus:companylist";
    // A name for the listing, currently, no translation support
    $this->human_name = "Company List";
    $this->description = "Lists companies that match various criteria";
    $this->version = "1.0";
    // This is how many stages of questioning to work out what is required, 1 is typical
    $this->input_stages = 1;
    $this->available_formats = array("html", "csv", "tsv");
  }

  /**
  * the first set of questions
  *
  * this is called by the parent class, so the name is important
  */
  function input_stage_1($report_options)
  {
    global $waf;

    // We won't be supplying a template, this is the only question
    $waf->assign("standalone", true);
    $waf->display("main.tpl", "admin:information:list_reports:report_input", "reports/format_selector.tpl");
  }

  /**
  * process the first set of questions
  *
  * this is called by the parent class, so the name is important
  * @todo can some of this move to the parent class?
  */
  function input_stage_do_1($report_options)
  {
    global $waf;

    $output_format = WA::request("output_format");
    if(!in_array($output_format, $this->available_formats))
    {
      // Someone is messing with the data
      $waf->halt("error:report:invalid_format");
    }
    else
    {
      // It's OK
      $this->output_format = $output_format;
    }

    $report_options['output_format'] = $output_format;
    return($report_options);
  }

  /**
  * returns header columns in a single dimensional array
  */
  function get_header($report_options)
  {
    return(array("name", "address1", "address2", "address3", "locality"));
  }

  /**
  * returns the body of the report in a multidimensional array (rows and columns)
  */
  function get_body($report_options)
  {
    $results = array();

    require_once("model/Company.class.php");

    // Very primitive proof of concept code
    $company = new Company;
    $companies = $company->_get_all("", "order by name, locality", 0, 100000);

    foreach($companies as $company)
    {
      array_push($results, array($company->name, $company->address1, $company->address2, $company->address3, $company->locality));
    }
    return $results;
  }

}


?>