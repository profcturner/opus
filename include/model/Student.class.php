<?php

/**
* Defines the extra fields for students
* @package OPUS
*/
require_once("dto/DTO_Student.class.php");
/**
* Defines the extra fields for students
*
* This melds data from the student and user tables together
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see User.class.php
* @package OPUS
*
*/

class Student extends DTO_Student 
{
  var $placement_year   = ""; 
  var $placement_status = "";
  var $programme_id     = ""; // id of programme
  var $user_id          = ""; // Id of user from the user table
  var $progress         = ""; // whether the student has signed off on the disclaimer and other issues
  var $academic_user_id = ""; // user_id of the academic tutor
  var $disability_code  = "";

  // Several of these fields actually reside in the User table
  static $_field_defs = array
  (
    'salutation'=>array('type'=>'text', 'size'=>20, 'header'=>true, 'title'=>'Title', 'mandatory'=>true),
    'firstname'=>array('type'=>'text','size'=>30, 'header'=>true),
    'lastname'=>array('type'=>'text','size'=>30, 'header'=>true, 'mandatory'=>true),
    'email'=>array('type'=>'email','size'=>40, 'mandatory'=>true),
 //   'progress'=>array('type'=>'list', 'list'=>array()),
    'placement_year'=>array('type'=>'text','size'=>5, 'title'=>'Placement Year', 'mandatory'=>true),
    'placement_status'=>array('type'=>'list', 'list'=>array('Required'=>'Required','Placed'=>'Placed','Exempt Applied'=>'Exempt Applied','Exempt Given'=>'Exempt Given','No Info'=>'No Info','Left Course'=>'Left Course','Suspended'=>'Suspended','To final year'=>'To final year','Not Eligible'=>'Not Eligible')),
    'academic_user_id'=>array('type'=>'lookup', 'object'=>'staff', 'value'=>'dud', 'title'=>'Academic Tutor', 'var'=>'tutors', 'lookup_function'=>'lookup_tutors_by_school'),
    'programme_id'=>array('type'=>'lookup', 'object'=>'programme', 'value'=>'name', 'title'=>'Programme', 'var'=>'programmes', 'lookup_function'=>'get_id_and_description')
  );

  // This defines which ones
  static $_extended_fields = array
  (
    'salutation','firstname','lastname','email'
  );

  function __construct() 
  {
    parent::__construct('default');
  }

  function get_field_defs()
  {
    return self::$_field_defs;
  }

  function get_extended_fields()
  {
    return self::$_extended_fields;
  }

  function load_by_user_id($user_id)
  {
    return(Student::load_by_id(Student::get_id_from_user_id($user_id)));
  }

  function get_id_from_user_id($user_id)
  {
    $user_id = (int) $user_id; // security

    $student = new Student;
    $id = $student->_get_fields("id", "where user_id = $user_id");
    return($id);
  }

  function load_by_id($id) 
  {
     $student = new Student;
     $student->id = $id;
     $student->_load_by_id();
     return $student;
  }


  /**
  * inserts data about a new student to the User and Student tables
  *
  * this is more sophisticated that usual because there are two tables.
  */
  function insert($fields) 
  {
    require_once("model/User.class.php");

    $student = new Student;
    $extended_fields = Student::get_extended_fields();
    $user_fields = array();

    foreach($fields as $key => $value)
    {
      if(in_array($key, $extended_fields))
      {
        // Set these in the other array
        $user_fields[$key] = $value;
        unset($fields[$key]);
      }
    }
    // Insert user data first, adding anything else we need
    $user_fields['user_type'] = 'student';
    $user_id = User::insert($user_fields);

    // Now we know the user_id, to populate the other tables
    $fields['user_id'] = $user_id;

    return $student->_insert($fields);
  }

  function update($fields) 
  {
    global $waf;
    // We have a potential security problem here, we should check id and user_id are really linked.
    $student = Student::load_by_id($fields['id']);
    if(!isset($fields['user_id'])) $fields['user_id'] = $student->user_id;
    if($student->user_id != $fields['user_id'])
    {
      $waf->security_log("attempt to update student with mismatching user_id fields");
      $waf->halt("error:student:user_id_mismatch");
    }

    $extended_fields = Student::get_extended_fields();
    $user_fields = array();

    foreach($fields as $key => $value)
    {
      if(in_array($key, $extended_fields))
      {
        // Set these in the other array
        $user_fields[$key] = $value;
        unset($fields[$key]);
      }
    }
    // Insert user data first, adding anything else we need
    $user_fields['id'] = $fields['user_id'];
    User::update($user_fields);

    $student = Student::load_by_id($fields[id]);
    $student->_update($fields);

    // Finally invalidate any timeline to ensure it is correctly regenerated
    require_once("model/Timeline.class.php");
    Timeline::invalidate($fields['id']);
  }

  function exists($id) 
  {
    $student = new Student;
    $student->id = $id;
    return $student->_exists();
  }

  function count($where="") 
  {
    $student = new Student;
    return $student->_count($where);
  }

  function get_all($where_clause="", $order_by="ORDER BY lastname", $page=0) 
  {
    global $config;
    $student = new Student;

    if ($page <> 0) 
    {
        $start = ($page-1)*$config['opus']['rows_per_page'];
        $limit = $config['opus']['rows_per_page'];
        $students = $student->_get_all($where_clause, $order_by, $start, $limit);
    }
    else 
    {
        $students = $student->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $students;
  }

  function get_ids($where_clause="", $order_clause="")
  {
    $student = new Student;
    return($student->_get_ids($where_clause, $order_clause));
  }

  function get_id_and_field($fieldname, $where_clause="") 
  {
    $students = new Student;
    return  $students->_get_id_and_field($fieldname, $where_clause);
  }

  function get_fields($include_id = false) 
  {
    $student = new Student;
    return  $student->_get_fieldnames($include_id);
  }

  function request_field_values($include_id = false) 
  {
    $fieldnames = Student::get_fields($include_id);
    $fieldnames = array_merge($fieldnames, Student::get_extended_fields());

    $nvp_array = array();
    foreach ($fieldnames as $fn) 
    {
        $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
    }
    return $nvp_array;
  }

  function remove($id=0) 
  {
    $student = new Student;
    $student->_remove_where("WHERE id=$id");
  }

  function get_all_extended($search, $year, $programmes, $sort, $other_options)
  {
    $student = new Student;
    return($student->_get_all_extended($search, $year, $programmes, $sort, $other_options));
  }

  function get_all_by_initial($initial)
  {
    $student = new Student;
    return($student->_get_all_by_initial($initial));
  }

  function get_user_id($id)
  {
    $id = (int) $id; // Security

    $data = Student::get_id_and_field("user_id","where id='$id'");
    return($data[$id]);
  }

  /**
  * gets the programme id
  *
  * @param int $user_id the id from the <strong>user</strong> table
  */
  function get_programme_id($user_id)
  {
    $user_id = (int) $user_id; // Security
    $student = new Student;
    return($student->_get_fields("programme_id","where user_id='$user_id'"));
  }

  /**
  * gets the placement year
  *
  * @param int $user_id the id from the <strong>user</strong> table
  */
  function get_placement_year($user_id)
  {
    $user_id = (int) $user_id; // Security
    $student = new Student;
    return($student->_get_fields("placement_year","where user_id='$user_id'"));
  }

  /**
  * gets the academic tutor id
  *
  * @param int $user_id the id from the <strong>user</strong> table
  */
  function get_academic_user_id($user_id)
  {
    $user_id = (int) $user_id; // Security
    $student = new Student;
    return($student->_get_fields("academic_user_id","where user_id='$user_id'"));
  }

  /**
  * gets the assessment group id
  *
  * @param int $user_id the id from the <strong>user</strong> table
  */
  function get_assessment_group_id($user_id)
  {
    // If no student id is passed in, use current one
    //if(!$id) $id = $this->
    $student = Student::load_by_user_id($user_id);
    if(empty($student->id)) return 1; // student is mangled, play safe
    $programme_id = $student->programme_id;
    $placement_year = $student->placement_year;
    require_once("model/AssessmentGroupProgramme.class.php");

    // Look for explicit bounded match
    $group = AssessmentGroupProgramme::load_where("where programme_id = $programme_id and $placement_year >= startyear and $placement_year <= endyear");
    if($group->group_id) return($group->group_id);

     // Ok, look for a match with an endpoint only
    $group = AssessmentGroupProgramme::load_where("where programme_id = $programme_id and startyear is null and $placement_year <= endyear");
    if($group->group_id) return($group->group_id);

    // Ok, look for a match with an startpoint only
    $group = AssessmentGroupProgramme::load_where("where programme_id = $programme_id and $placement_year >= startyear and endyear is null");
    if($group->group_id) return($group->group_id);

    // Lastly, look for a match with no endpoints
    $group = AssessmentGroupProgramme::load_where("where programme_id = $programme_id and (startyear is null) and (endyear is null)");
    if($group->group_id) return($group->group_id);

    // Bail with the default
    return(1);
  }


  function get_assessment_regime($user_id, &$aggregate_total, &$weighting_total)
  {
    // This will store the items
    $final_items = array();

    // Determine the students assessmentgroup
    $assessmentgroup_id = Student::get_assessment_group_id($user_id);

    // Get the regime items
    require_once("model/AssessmentRegime.class.php");
    $regime_items = AssessmentRegime::get_all("where group_id=$assessmentgroup_id");

    // Sort these appropriately
    usort($regime_items, array("AssessmentRegime", "assessment_date_compare"));

    // Now augment this with data from results
    $weighting_total = 0;
    $aggregate_total = 0;

    foreach($regime_items as $regime_item)
    {
      // Supervisor's don't see other assessments
      if(User::is_supervisor() && ($regime_item->assessor != 'industrial'))
      {
        continue;
      }
      // Do we want hidden stuff for students anymore?

      // Collect the weight as we go...
      $weighting_total += $regime_item->weighting;

      // Try to determine the assessor
      if($regime_item->assessor == 'other')
      {
        require_once("model/AssessorOther.class.php");
        $assessorother = AssessorOther::load_where("where assessed_id=$user_id and regime_id=" . $regime_item->id);
        if($assessorother->id) // valid return
        {
          $regime_item->assessor = User::get_name($assessorother->assessor_id);
        }
      }
      /* this needs to go in the template
      if($row['assessor']=="student") $assessor = "Self Assessment";
      if($row['assessor']=="academic") $assessor = "Academic Tutor";
      if($row['assessor']=="industrial") $assessor = "Industrial Supervisor";

      if($assessor == "Unknown")
      {
        $otherassessor = assessment_get_other_assessor($student_id, $row["cassessment_id"]);
        if($otherassessor != FALSE) $assessor = get_user_name($otherassessor);
      }

      $row['assessor'] = $assessor;
      */

      // Get the results if possible
      require_once("model/AssessmentTotal.class.php");
      $results = AssessmentTotal::load_where("where regime_id = " . $regime_item->id . " and assessed_id=$user_id");

      $percentage = $results->percentage;
      if(empty($percentage))
      {
        $percentage = "--";
        $aggregate = "--";
        $punctuality = $regime_item->get_punctuality($user_id);
        switch($punctuality)
        {
          case "early": $percentage .= " (not due yet)"; break;
          case "late": $percentage .= " (late)"; break;
          default: $percentage .= " (due now)"; break;
        }
      }
      else
      {
        $percentage = sprintf("%.02f", $percentage);
        $aggregate = sprintf("%.02f", $percentage * $regime_item->weighting);
        $aggregate_total += $aggregate;
        $percentage .= "%";
        $aggregate .= "%";
      }
      // Add them to the array
      $regime_item->percentage = $percentage;
      $regime_item->aggregate = $aggregate;
      array_push($final_items, $regime_item);
    }
    return($final_items);
  }

  function get_other_assessors($user_id)
  {
    // This will store the items
    $final_items = array();

    // Determine the students assessmentgroup
    $assessmentgroup_id = Student::get_assessment_group_id($user_id);

    // Get the regime items
    require_once("model/AssessmentRegime.class.php");
    $regime_items = AssessmentRegime::get_all("where assessor='other' and group_id=$assessmentgroup_id");

    // Augment
    foreach($regime_items as $item)
    {
      require_once("model/AssessorOther.class.php");
      $assessorother = AssessorOther::load_where("where assessed_id=$user_id and regime_id=" . $item->id);
      $item->assessor_id = $assessorother->assessor_id;
      array_push($final_items, $item);
    }

    // Sort these appropriately
    usort($final_items, array("AssessmentRegime", "assessment_date_compare"));

    return($final_items);
  }

  function get_last_application_time($id)
  {
    require_once("model/Application.class.php");
    $application = new Application;
    // Get the last application!
    $applications = $application->_get_all("where student_id=$id", "order by created DESC", 0, 1);
    $application = $applications[0];

    return $application->created;
  }

  function get_name($id)
  {
    return(User::get_name(Student::get_user_id($id)));
  }
}

?>