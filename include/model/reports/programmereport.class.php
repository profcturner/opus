<?php

/**
* Report for status of students in various programmes
* @package OPUS
*/
require_once("model/Report.class.php");
/**
* Report for status of students in various programmes
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
* @see Report.class.php
*
*/

class programmereport extends Report
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
    $this->unique_name = "u3:opus:programmereport";
    // A name for the listing, currently, no translation support
    $this->human_name = "Programme Report";
    $this->description = "Provides a breakdown on students' placement status in a number of programmes";
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

    // We want all the assessment groups
    require_once("model/AssessmentGroup.class.php");
    $assessmentgroups = AssessmentGroup::get_id_and_field("name");

    $waf->assign("assessmentgroups", $assessmentgroups);
    $waf->display("main.tpl", "admin:information:list_reports:report_input", "reports/programmereport/input_stage_1.tpl");
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
    $assessment_group = WA::request("assessment_group");
    $year = WA::request("year");

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
    $report_options['assessment_group'] = $assessment_group;
    $report_options['year'] = $year;
    return($report_options);
  }

  /**
  * returns header columns in a single dimensional array
  */
  function get_header($report_options)
  {
    $header = array('Code', 'Name', 'Total', 'Required', 'Placed', 'LeftCourse', 'Exempt?', 'Exempt', 'NoInfo', 'Suspended', 'FinalYear', 'Countries');

    return($header);
  }

  /**
  * returns the body of the report in a multidimensional array (rows and columns)
  * @todo we should really remove programmes for which no students will appear due to policy issues
  */
  function get_body($report_options)
  {
    $year = (int) $report_options['year'];
    $group_id = (int) $report_options['assessment_group'];

    require_once("model/Programme.class.php");
    require_once("model/AssessmentGroupProgramme.class.php");

    $programmes = AssessmentGroupProgramme::get_all_programmes($group_id, $year);

    $rows = array();
    foreach($programmes as $programme_id)
    {
      array_push($rows, $this->get_body_programme($programme_id, $year));
    }
    return($rows);
  }

  function get_body_programme($programme_id, $year)
  {
    require_once("model/Policy.class.php");
    require_once("model/Programme.class.php");
    require_once("model/Student.class.php");

    $programme = Programme::load_by_id($programme_id);

    // This is probably not an efficient call for this function, but we are not overly concerned in reports
    $students = Student::get_all_extended("", $year, array($programme_id), "placement_status", array());

    foreach($students as $student)
    {
      // Skip students we should not see!
      if(!Policy::is_auth_for_student($student['user_id'], "student", "viewStatus")) continue;

      $status[str_replace(" ", "", $student['placement_status'])]++; // this array will be associative by status codes
      if($student['placement_status'] == 'Placed')
      {
        require_once("model/Placement.class.php");
        $placement = Placement::get_most_recent($student['user_id']);

        require_once("model/Vacancy.class.php");
        $vacancy = Vacancy::load_by_id($placement->vacancy_id);
        $countries[$vacancy->country]++;
      }
    }
    if(empty($countries)) $countries = array();
    reset($countries);
    while(list($key, $value) = each($countries))
    {
      $countries_text .= "$key ($value), ";
    }
    // snip off last comma
    $countries_text = substr($countries_text, 0, -2);

    return(array($programme->srs_ident, $programme->name, count($students), $status['Required'], $status['Placed'], $status['LeftCourse'], $status['ExemptApplied'], $status['ExemptGiven'], $status['NoInfo'], $status['Suspended'], $status['FinalYear'], $countries_text)); //implode(",", $countries)));
  }

}


?>