<?php

/**
* Report for listing all the contacts in the database
* @package OPUS
*/
require_once("model/Report.class.php");
/**
* Report for listing all the contacts in the database
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
* @see Report.class.php
*
*/

class contactlist extends Report
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
    $this->unique_name = "u3:opus:contactlist";
    // A name for the listing, currently, no translation support
    $this->human_name = "Contact List";
    $this->description = "Lists contacts that match various criteria";
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
    $waf =& UUWAF::get_instance();

    // We want all the activity types
    require_once("model/Activitytype.class.php");
    $activity_types = Activitytype::get_id_and_field("name");

    $waf->assign("activity_types", $activity_types);
    $waf->display("main.tpl", "admin:information:list_reports:report_input", "reports/contactlist/input_stage_1.tpl");
  }

  /**
  * process the first set of questions
  *
  * this is called by the parent class, so the name is important
  * @todo can some of this move to the parent class?
  */
  function input_stage_do_1($report_options)
  {
    $waf =& UUWAF::get_instance();

    $output_format = WA::request("output_format");
    $start_year = WA::request("start_year");
    $end_year = WA::request("end_year");
    $activities = WA::request("activities");

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

    // Change and save options
    $report_options['output_format'] = $output_format;
    $report_options['activities'] = $activities;
    $report_options['start_year'] = $start_year;
    $report_options['end_year'] = $end_year;

    return($report_options);
  }

  /**
  * returns header columns in a single dimensional array
  */
  function get_header($report_options)
  {
    return(array("salutation", "firstname", "lastname", "position","email","voice","fax"));
  }

  /**
  * returns the body of the report in a multidimensional array (rows and columns)
  */
  function get_body($report_options)
  {
    $results = array();

    require_once("model/Contact.class.php");

    // Very primitive proof of concept code
    $contact = new Contact;
    $contacts = $contact->_get_all("", "order by lastname", 0, 100000);

    foreach($contacts as $contact)
    {
      array_push($results, array($contact->salutation, $contact->firstname, $contact->lastname, $contact->position, $contact->email, $contact->voice, $contact->fax));
    }
    return $results;
  }

}


?>