<?php

/**
* Report for obtaining a detailed breakdown of a single assessment for a given cohort
* @package OPUS
*/
require_once("model/Report.class.php");
/**
* Report for obtaining a detailed breakdown of a single assessment for a given cohort
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
* @see Report.class.php
*
*/

class assessmentbreakdown extends Report
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
    $this->unique_name = "u3:opus:assessmentbreakdown";
    // A name for the listing, currently, no translation support
    $this->human_name = "Assessment Breakdown";
    $this->description = "Detailed breakdown of an individual assessment";
    $this->version = "1.0";
    // This is how many stages of questioning to work out what is required, 1 is typical
    $this->input_stages = 2;
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

    $waf->assign("assessmentgroups", $assessmentgroups);
    $waf->display("main.tpl", "admin:information:list_reports:report_input", "reports/assessmentbreakdown/input_stage_1.tpl");
  }

  /**
  * process the first set of questions
  *
  * this is called by the parent class, so the name is important
  */
  function input_stage_do_1($report_options)
  {
    global $waf;

    $assessment_group = WA::request("assessment_group");

    // Change and save options
    $report_options['assessment_group'] = $assessment_group;
    return($report_options);
  }

  /**
  * ask which assessment and year is being looked at
  */
  function input_stage_2($report_options)
  {
    global $waf;

    $group_id = (int) $report_options['assessment_group'];

    require_once("model/AssessmentRegime.class.php");
    $assessmentregimes = AssessmentRegime::get_id_and_field("student_description", "where group_id=$group_id");

    $waf->assign("assessmentregimes", $assessmentregimes);
    $waf->display("main.tpl", "admin:information:list_reports:report_input", "reports/assessmentbreakdown/input_stage_2.tpl");
  }

  function input_stage_do_2($report_options)
  {
    $assessment_regime_id = (int) WA::request("assessment_regime_id");
    $output_format = WA::request("output_format");
    $year = (int) WA::request("year");

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

    $report_options['assessment_regime_id'] = $assessment_regime_id;
    $report_options['output_format'] = $output_format;
    $report_options['year'] = $year;

    return($report_options);
  }

  /**
  * returns header columns in a single dimensional array
  */
  function get_header($report_options)
  {
    $items = array("Name", "Student #");

    $assessment_regime_id = (int) $report_options['assessment_regime_id'];

    require_once("model/AssessmentRegime.class.php");
    $assessment_regime = AssessmentRegime::load_by_id($assessment_regime_id);

    // Now we can find the actual underlying assessment used by the regime item
    require_once("model/Assessment.class.php");
    $assessment = Assessment::load_by_id($assessment_regime->assessment_id);

    // And finally, the structure of this assessment
    require_once("model/AssessmentStructure.class.php");
    $assessment_structure = AssessmentStructure::get_all("where assessment_id=" . $assessment_regime->assessment_id);

    foreach($assessment_structure as $item)
    {
      array_push($items, $item->name); // Not very satisfactory...name is often obscure
    }
    return($items);
  }

  /**
  * returns the body of the report in a multidimensional array (rows and columns)
  */
  function get_body($report_options)
  {
    require_once("model/Policy.class.php");
    $results = array();

    // Boringly, get the same thing for now
    $assessment_regime_id = (int) $report_options['assessment_regime_id'];
    $year = (int) $report_options['year'];
    $group_id = (int) $report_options['assessment_group'];

    require_once("model/AssessmentRegime.class.php");
    $assessment_regime = AssessmentRegime::load_by_id($assessment_regime_id);

    // Now we can find the actual underlying assessment used by the regime item
    require_once("model/Assessment.class.php");
    $assessment = Assessment::load_by_id($assessment_regime->assessment_id);

    // And finally, the structure of this assessment
    require_once("model/AssessmentStructure.class.php");
    $assessment_structure = AssessmentStructure::get_all("where assessment_id=" . $assessment_regime->assessment_id);

    // Get all the effected students
    require_once("model/AssessmentGroupProgramme.class.php");
    $programmes = AssessmentGroupProgramme::get_all_programmes($group_id, $year);
    require_once("model/Student.class.php");

    $students = Student::get_all_extended("", $year, $programmes, "placement_status", array());

    // Ok, now for each get their records
    foreach($students as $student)
    {
      if(Policy::is_auth_for_student($student['user_id'], "student", "viewAssessments")) array_push($results, $this->get_body_student_results($student, $assessment_structure, $assessment_regime_id));
    }
    return($results);
  }

  private function get_body_student_results($student, $assessment_structure, $assessment_regime_id)
  {
    $result_array = array($student['real_name'], $student['reg_number']);

    $student_id = $student['user_id'];
    require_once("model/AssessmentResult.class.php");

    foreach($assessment_structure as $item)
    {
      $name = $item->name;
      $result = AssessmentResult::load_where("where assessed_id=$student_id and regime_id=$assessment_regime_id and name='$name'");
      // I think we might need some post processing for some output formats here
      array_push($result_array, $result->contents);
    }
    return($result_array);
  }

}


?>