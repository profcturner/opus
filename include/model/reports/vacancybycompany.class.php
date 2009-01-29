<?php

/**
* Gives the number of vacancies offered by companies in given years
* @package OPUS
*/
require_once("model/Report.class.php");
/**
* Gives the number of vacancies offered by companies in given years
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
* @see Report.class.php
*
*/

class vacancybycompany extends Report
{
  /**
  * constructor
  *
  * This calls the parent constructor, and sets up important characteristics of the plugin
  */
  function __construct()
  {
    parent::__construct();

    // Must be unique, don't use u3 unless you're us, so your plugin doesn't get clobbered
    $this->unique_name = "u3:opus:vacancybycompany";
    // A name for the listing, currently, no translation support
    $this->human_name = "Vacancies by Companies";
    $this->description = "Provides a breakdown on how many vacancies have been offered by companies";
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
    $waf->display("main.tpl", "admin:information:list_reports:report_input", "reports/vacancybycompany/input_stage_1.tpl");
  }

  /**
  * process the first set of questions
  *
  * this is called by the parent class, so the name is important
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
    $header = array('Company Name', 'Address 1', 'Address 2', 'Address 3', 'Locality', 'Country', 'C Title', 'C firstname', 'C lastname', 'C position', 'C voice','Vacancies Offered', 'Vacancies Filled', 'Placements Offered');

    return($header);
  }

  /**
  * returns the body of the report in a multidimensional array (rows and columns)
  * @todo we should really remove programmes for which no students will appear due to policy issues
  */
  function get_body($report_options)
  {
    $start_year = (int) $report_options['start_year'];
    $end_year = (int) $report_options['end_year'];
    $activities = $report_options['activities'];

    require_once("model/Company.class.php");
    // Get the companies b the normal method
    $companies = Company::get_all_extended("", $activities, "name");    
    
    $rows = array();
    foreach($companies as $company)
    {
      $row = $this->get_body_company($company);
      $row = array_merge($row, $this->get_body_contact($company));
      $row = array_merge($row, $this->get_body_vacancy_counts($company, $start_year, $end_year));
      array_push($rows, $row);
    }
    return($rows);
  }

  private function get_body_company($company)
  {

    return(array($company['name'], $company['address1'], $company['address2'], $company['address3'], $company['locality'], $company['country']));
  }
  
  /**
  * @todo get years working by academic, not calendar years?
  */ 
  private function get_body_vacancy_counts($company, $start_year, $end_year)
  {
    require_once("model/Vacancy.class.php");
    
    $vacancies = Vacancy::get_all("where company_id=" . $company['id']);
    
    echo count($vacancies);
    $vacancy_offered = 0;
    $vacancy_filled = 0;
    $total_students = 0;
    
    foreach($vacancies as $vacancy)
    {
      $vacancy_year = substr($vacancy->jobstart, 0, 4);
      echo $vacancy_year;
      if($vacancy_year < $start_year) continue;
      if($vacancy_year > $end_year) continue;
      // So it was offered in this timespan
      $vacancy_offered++;
      
      require_once("model/Placement.class.php");
      $placements = Placement::count("where vacancy_id=" . $vacancy->id);
      if($placements)
      {
        $vacancy_filled++;
      }
      $total_students += $placements;
    }    
    return(array($vacancy_offered, $vacancy_filled, $total_students));
  }
  
  private function get_body_contact($company)
  {
    require_once("model/Contact.class.php");
    
    $contacts = Contact::get_all_by_company($company['id']);
    $primary = $contacts[0];
    
    return array($primary->salutation, $primary->firstname, $primary->lastname, $primary->position, $primary->voice);
  }

}


?>