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
    $report_options['extras'] = $extras;
    $report_options['assessment_group'] = $assessment_group;
    $report_options['year'] = $year;
    return($report_options);
  }

  /**
  * returns header columns in a single dimensional array
  */
  function get_header($report_options)
  {
//    print_r($report_options);
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
    if(in_array("assessment_info", $extras)) $header = array_merge($header, $this->get_header_assessment($report_options['assessment_group']));

    return($header);
  }

  /**
  * returns the body of the report in a multidimensional array (rows and columns)
  */
  function get_body($report_options)
  {
    $extras = $report_options['extras'];
    if(empty($extras)) $extras = array();
    $year = (int) $report_options['year'];
    $group_id = (int) $report_options['assessment_group'];

    require_once("model/Policy.class.php");
    require_once("model/Staff.class.php");
    require_once("model/Programme.class.php");
    require_once("model/Placement.class.php");
    require_once("model/AssessmentGroupProgramme.class.php");

    $programmes = AssessmentGroupProgramme::get_all_programmes($group_id, $year); //array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10); // Temporary for testing
    require_once("model/Student.class.php");

    $inital_students = Student::get_all_extended("", $year, $programmes, "placement_status", array());
    $new_students = array();

    // Will we need assessment info?
    if(in_array("assessment_info", $extras))
    {
      require_once("model/AssessmentRegime.class.php");
      $assessmentregimes = AssessmentRegime::get_all("where group_id=$group_id", "order by year, end, start");
    }

    foreach($inital_students as $student)
    {
      // Skip students we should not see!
      if(!Policy::is_auth_for_student($student['user_id'], "student", "viewStatus")) continue;
      $programme = Programme::load_by_id($student['programme_id']);
      $new_student = array($student['placement_status'], $student['lastname'], $student['salutation'], $student['firstname'], $student['reg_number'], $student['email'], $programme->srs_ident, $programme->name, User::get_name($student['academic_user_id']));

      // extras as needed
      if(in_array("disability_info", $extras)) $new_student = array_merge($new_student, array($student['disability_code']));
      if(count($extras)) // crude for now
      {
        $placement = Placement::get_most_recent($student['user_id']);
      }
      if(in_array("company_info", $extras)) $new_student = array_merge($new_student, $this->get_body_company($placement));
      if(in_array("vacancy_info", $extras)) $new_student = array_merge($new_student, $this->get_body_vacancy($placement));
      if(in_array("supervisor_info", $extras)) $new_student = array_merge($new_student, $this->get_body_supervisor($placement));
      if(in_array("assessment_info", $extras)) $new_student = array_merge($new_student, $this->get_body_assessment($assessmentregimes, $student['user_id']));

      array_push($new_students, $new_student);
    }
    return $new_students;
  }

  private function get_header_assessment($group_id)
  {
    $group_id = (int) $group_id; // security

    $titles = array();
    $count = 0;

    require_once("model/AssessmentRegime.class.php");
    $assessmentregimes = AssessmentRegime::get_all("where group_id=$group_id", "order by year, end, start");

    foreach($assessmentregimes as $assessmentregime)
    {
      $titles[$count++] = $assessmentregime->student_description;
      $titles[$count++] = 'Weighting';
    }
    $titles[$count++] = 'Total';
    return($titles);
  }

  private function get_body_assessment($assessmentregimes, $student_user_id)
  {
    $assresults = array();
    $count = 0;
    $total = 0;

    require_once("model/AssessmentTotal.class.php");

    // Step through in the same order every time
    foreach($assessmentregimes as $assessmentregime)
    {
      // Fetch total information for this...
      $total_info = AssessmentTotal::get_totals_with_stamps($student_user_id, $assessmentregime->id);

      if($total_info['id']) // We have real total data
      {
        if($total_info['percentage'] == NULL) $total_info['percentage'] = "0";
        $assresults[$count++] = $total_info['percentage'];
      }
      else
      {
        $assresults[$count++] = "";
      }
      $assresults[$count++] = $assessmentregime->weighting;
      $total += ($total_info['percentage'] * $assessmentregime->weighting);
    }
    $assresults[$count++] = $total;
    return($assresults);
  }


  private function get_body_company($placement)
  {
    if($placement == false)
    {
      return array("", "", "", "", "", "", "", "", "", "", "");
    }
    require_once("model/Company.class.php");
    $company = Company::load_by_id($placement->company_id);
    require_once("model/Contact.class.php");
    $contacts = Contact::get_all_by_company($placement->company_id);

    return array($company->name, $company->address1, $company->address2, $company->address3, $company->town, $company->locality, $company->country, $company->postcode, $contacts[0]->salutation, $contacts[0]->firstname, $contacts[0]->lastname);
  }

  private function get_body_vacancy($placement)
  {
    if($placement == false)
    {
      return array("", "", "", "", "", "", "", "");
    }
    require_once("model/Vacancy.class.php");
    $vacancy = Vacancy::load_by_id($placement->vacancy_id);
    return array($vacancy->description, $vacancy->address1, $vacancy->address2, $vacancy->address3, $vacancy->town, $vacancy->locality, $vacancy->country, $vacancy->postcode);
  }

  private function get_body_supervisor($placement)
  {
    if($placement == false)
    {
      return array("", "", "", "");
    }
    require_once("model/Supervisor.class.php");
    $supervisor = Supervisor::load_by_placement_id($placement->id, false); // Don't halt on error, this call seems slow for this purpose
    return array($supervisor->salutation, $supervisor->firstname, $supervisor->lastname, $supervisor->email);
  }

}


?>