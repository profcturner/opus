<?php

/**
* The model object for help prompts
* @package OPUS
*/
require_once("dto/DTO_AssessmentRegime.class.php");

/**
* The AssessmentRegime model class
*/
class AssessmentRegime extends DTO_AssessmentRegime 
{
  var $group_id;                  // The assessment group this belongs to
  var $assessment_id;             // The assessment id from the assessment table
  var $weighting = 0;             // How much weighting this assessment has in the regime
  var $start = "";                // Start time in format MMDD
  var $end = "";                  // End time in format MMDD
  var $year = 0;                  // Year of assessment, 0= placement year, -1 year before etc
  var $student_description = "";  // Brief description for the student  
  var $outcomes = "";             // Discussion of learning outcomes
  var $assessor = "";             // Assessor

  static $_field_defs = array(
    'assessment_id'=>array('type'=>'lookup', 'object'=>'assessment', 'value'=>'description', 'title'=>'Assessment', 'size'=>20, 'var'=>'assessments'),
    'student_description'=>array('type'=>'text', 'size'=>30, 'maxsize'=>100, 'title'=>'Student Description', 'header'=>true, 'mandatory'=>true),
    'weighting'=>array('type'=>'text', 'size'=>4, 'maxsize'=>4, 'header'=>true, 'mandatory'=>true),
    'assessor'=>array('type'=>'list', 'list'=>array('academic'=>'Academic Tutor','industrial'=>'Workplace Supervisor','student'=>'Student (Self)','other'=>'Other'), 'header'=>true),
    'year'=>array('type'=>'text', 'size'=>4, 'maxsize'=>4),
    'start'=>array('type'=>'text', 'size'=>4, 'maxsize'=>4, 'validation'=>'(^$)|(^[0-9][0-9][0-9][0-9]$)', 'validation_message'=>'must take the form MMDD'),
    'end'=>array('type'=>'text', 'size'=>4, 'maxsize'=>4, 'validation'=>'(^$)|(^[0-9][0-9][0-9][0-9]$)', 'validation_message'=>'must take the form MMDD'),
    'group_id'=>array('type'=>'hidden'),
    'outcomes'=>array('type'=>'textarea', 'rowsize'=>10, 'colsize'=>50)
  );

  function __construct() 
  {
    parent::__construct('default');
  }

  /**
  * returns the statically defined field definitions
  */
  function get_field_defs()
  {
    return(self::$_field_defs);
  }


  function load_by_id($id) 
  {
    $assessmentregime = new AssessmentRegime;
    $assessmentregime->id = $id;
    $assessmentregime->_load_by_id();
    return $assessmentregime;
  }

  function insert($fields) 
  {
    $assessmentregime = new AssessmentRegime;
    $assessmentregime->_insert($fields);
  }
  
  function update($fields) 
  {
    $assessmentregime = AssessmentRegime::load_by_id($fields[id]);
    $assessmentregime->_update($fields);
  }
  
  /**
  * Wasteful
  */
  function exists($id) 
  {
    $assessmentregime = new AssessmentRegime;
    $assessmentregime->id = $id;
    return $assessmentregime->_exists();
  }
  
  /**
  * Wasteful
  */
  function count($where_clause="") 
  {
    $assessmentregime = new AssessmentRegime;
    return $assessmentregime->_count($where_clause);
  }

  function get_all($where_clause="", $order_by="ORDER BY student_description", $page=0)
  {
    global $config;
    $assessmentregime = new AssessmentRegime;

    if ($page <> 0) {
      $start = ($page-1)*$config['opus']['rows_per_page'];
      $limit = $config['opus']['rows_per_page'];
      $assessmentregimes = $assessmentregime->_get_all($where_clause, $order_by, $start, $limit);
    }
    else
    {
      $assessmentregimes = $assessmentregime->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $assessmentregimes;
  }

  /**
  * custom function for sorting arrays of regime items
  *
  * this function is intended to be called by usort
  *
  * @param AssessmentRegime $regime_item1 the first item
  * @param AssessmentRegime $regime_item2 the second item
  * @return -1 if the first item is less than the second, 1 otherwise
  */
  function assessment_date_compare($regime_item1, $regime_item2)
  {
    global $config;
    $yearstart = $config['opus']['yearstart'];
    if(empty($yearstart)) $yearstart="0930";

    $year1 = $regime_item1->year;
    $year2 = $regime_item2->year;

    $days_end1 = AssessmentRegime::get_days_into_year($regime_item1->end);
    $days_end2 = AssessmentRegime::get_days_into_year($regime_item2->end);

    $days_end1 += ($year1 * 365);
    $days_end2 += ($year2 * 365);

    return($days_end1 - $days_end2);
  }

  function get_days_into_year($mmdd)
  {
    global $config;
    $yearstart = $config['opus']['yearstart'];
    if(empty($yearstart)) $yearstart="0930";

    $mm = (int) substr($yearstart, 0, 2);
    $dd = (int) substr($yearstart, 2, 2);

    $academic_start = mktime(0, 0, 0, $mm, $dd, 1970);
    $year_end = mktime(0, 0, 0, 12, 31, 1970);

    $mm = (int) substr($mmdd, 0, 2);
    $dd = (int) substr($mmdd, 2, 2);
    $test_value = mktime(0, 0, 0, $mm, $dd, 1970);

    if((int) $mmdd > (int) $yearstart)
    {
      // Simple case, after "start" before calendar end
      $seconds = ($test_value - $academic_start);
    }
    else
    {
      $seconds = $test_value + ($year_end - $academic_start);
    }

    $days = $seconds / (60*60*24);

    return($days);
  }

  /**
  * determines if an assessment is late or early
  *
  * @param int $assessed_id id from the user table for the student being assessed
  * @return nothing, or a string with "early" or "late"
  * @todo refine this to return a number of days out.
  */
  function get_punctuality($assessed_id)
  {
    global $config;
    $yearstart = $config['opus']['yearstart'];
    if(empty($yearstart)) $yearstart="0930";

    require_once("model/Student.class.php");
    $placement_year = Student::get_placement_year($assessed_id);
    $current_year = get_academic_year();

    // Check years
    $year_difference = $current_year - $placement_year;
    if($year_difference < $this->year)
    {
      return("early");
    }
    elseif($year_difference > $this->year)
    {
      return("late");
    }
    // Ok, the year is OK...
    $mmdd = date("md");
    $days = AssessmentRegime::get_days_into_year($mmdd);
    $start = $this->start;
    $end = $this->end;
    $start_days = AssessmentRegime::get_days_into_year($start);
    $end_days = AssessmentRegime::get_days_into_year($end);

    //echo "now $mmdd, start $start, end $end<br />";

    if($mmdd > $yearstart) $mmdd -= $yearstart;
    if($start > $yearstart) $start -= $yearstart;
    if($end > $yearstart) $end -= $yearstart;

    //echo "now $days, start $start_days, end $end_days<br />";

    if($mmdd > $end) return("late");
    if($mmdd < $start) return("early");

    return("");
  }


  function get_id_and_field($fieldname, $where_clause="") 
  {
    $assessmentregime = new AssessmentRegime;
    $assessmentregime_array = $assessmentregime->_get_id_and_field($fieldname, $where_clause);
    unset($assessmentregime_array[0]);
    return $assessmentregime_array;
  }

  function remove($id=0) 
  {
    $assessmentregime = new AssessmentRegime;
    $assessmentregime->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {
    $assessmentregime = new AssessmentRegime;
    return  $assessmentregime->_get_fieldnames($include_id); 
  }

  function request_field_values($include_id = false) 
  {
    $fieldnames = AssessmentRegime::get_fields($include_id);
    $nvp_array = array();

    foreach ($fieldnames as $fn)
    {
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
    }
    return $nvp_array;
  }


  function get_name($id)
  {
    $id = (int) $id;
    $assessmentregime = new AssessmentRegime;
    return($assessmentregime->_get_fields("student_description", "where id=$id"));
  }
}
?>