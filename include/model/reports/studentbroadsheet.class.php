<?php

/**
* Report for obtaining considerable data on students for further playing in a spreadsheet
* @package OPUS
*/
require_once("model/Report.class.php");
/**
* Report for obtaining considerable data on students for further playing in a spreadsheet
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
* @see Report.class.php
*
*/

class studentbroadsheet extends Report
{
  static $extra_flags = array(
    "disability_info" => "Add Disability Info",
    "company_info" => "Add Company Info",
    "vacancy_info" => "Add Vacancy Info",
    "supervisor_info" => "Add Supervisor Info",
    "assessment_info" => "Add Assessment Info"
  );

  /**
  * constructor
  *
  * This calls the parent constructor, and sets up important characteristics of the plugin
  */
  function __construct()
  {
    parent::__construct();

    // Must be unique, don't use u3 unless you're us, so your plugin doesn't get clobbered
    $this->unique_name = "u3:opus:studentbroadsheet";
    // A name for the listing, currently, no translation support
    $this->human_name = "Student Broadsheet";
    $this->description = "Considerable information on students, exported on mass";
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

    // We want all the assessment groups
    require_once("model/AssessmentGroup.class.php");
    $assessmentgroups = AssessmentGroup::get_id_and_field("name");

    // Set up the extras
    $extras = self::$extra_flags;

    $waf->assign("extras", $extras);
    $waf->assign("assessmentgroups", $assessmentgroups);

    $waf->display("main.tpl", "admin:information:list_reports:report_input", "reports/studentbroadsheet/input_stage_1.tpl");
  }

  /**
  * process the first set of questions
  *
  * this is called by the parent class, so the name is important
  */
  function input_stage_do_1($report_options)
  {
    global $waf;

    $output_format = WA::request("output_format");
    $extras = WA::request("extras");
    if(empty($extras)) $extras = array();
    $assessmentgroup = WA::request("assessmentgroup");
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
    $report_options['extras'] = $extras;
    $report_options['assessmentgroup'] = $assessmentgroup;
    $report_options['year'] = $year;
    return($report_options);
  }

  /**
  * returns header columns in a single dimensional array
  */
  function get_header($report_options)
  {
    $extras = $report_options['extras'];
    if(empty($extras)) $extras = array();

    //print_r($report_options); exit;
    $header_basic = array("Status", "Surname", "Title", "First name", "Student #", "Email", "Course Code", "Course Name", "Academic Tutor");
    $header_disability = array("Disability");
    $header_company_extra = array("Company", "Address1", "Address2", "Address3", "Town", "Locality", "Country", "Postcode", "C title", "C ftname", "C sname");
    $header_vacancy_extra = array("Vacancy", "Address1", "Address2", "Address3", "Town", "Locality", "Country", "Postcode");
    $header_supervisor_extra = array("S title", "S ftname", "S sname", "S email");

    // Form header row
    $header = $header_basic;
    if(in_array("disability_info", $extras)) $header = array_merge($header, $header_disability);
    if(in_array("company_info", $extras)) $header = array_merge($header, $header_company_extra);
    if(in_array("vacancy_info", $extras)) $header = array_merge($header, $header_vacancy_extra);
    if(in_array("supervisor_info", $extras)) $header = array_merge($header, $header_supervisor_extra);
    if(in_array("assessment_info", $extras)) $header = array_merge($header, assessment_title_row($group_id));

    return($header);
  }

  /**
  * returns the body of the report in a multidimensional array (rows and columns)
  */
  function get_body($report_options)
  {
    $extras = $report_options['extras'];
    if(empty($extras)) $extras = array();
    $year = $report_options['year'];


    require_once("model/Staff.class.php");
    require_once("model/Programme.class.php");

    $programmes = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10); // Temporary for testing
    require_once("model/Student.class.php");

    $inital_students = Student::get_all_extended("", $year, $programmes, "placement_status", array());
    $new_students = array();

    foreach($inital_students as $student)
    {
      $programme = Programme::load_by_id($student['programme_id']);
      $new_student = array($student['placement_status'], $student['lastname'], $student['salutation'], $student['firstname'], $student['reg_number'], $student['email'], $programme->srs_ident, $programme->name, User::get_name($student['academic_user_id']));

      // extras as needed
      if(in_array("disability_info", $extras)) $new_student = array_merge($new_student, array($student['disability_code']));


      array_push($new_students, $new_student);
    }
    return $new_students;
  }

}


?>