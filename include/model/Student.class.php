<?php
/**
 * @package PDSystem
 *
 * reg_number -> studentname
 * student_id -> id
 * last few fields have gone
 */

require_once("dto/DTO_Student.class.php");

class Student extends DTO_Student 
{
  var $placement_year   = ""; 
  var $placement_status = "";
  var $programme_id     = ""; // id of programme
  var $user_id          = ""; // Id of user from the user table
  var $progress         = ""; // whether the student has signed off on the disclaimer and other issues
  var $disability_code  = "";

  // Several of these fields actually reside in the User table
  static $_field_defs = array
  (
    'salutation'=>array('type'=>'text', 'size'=>20, 'header'=>true, 'title'=>'Title'),
    'firstname'=>array('type'=>'text','size'=>30, 'header'=>true),
    'lastname'=>array('type'=>'text','size'=>30, 'header'=>true),
    'email'=>array('type'=>'email','size'=>40),
 //   'progress'=>array('type'=>'list', 'list'=>array()),
    'placement_year'=>array('type'=>'text','size'=>5, 'title'=>'Placement Year'),
    'placement_status'=>array('type'=>'list', 'list'=>array('Required','Placed','Exempt Applied','Exempt Given','No Info','Left Course','Suspended','To final year','Not Eligible')),
    'programme_id'=>array('type'=>'lookup', 'object'=>'programme', 'value'=>'name', 'title'=>'Programme', 'var'=>'programmes', 'lookup_function'=>'get_id_and_description')
  );

  // This defines which ones
  static $_extended_fields = array
  (
    'salutation','firstname','lastname','email','username','reg_number'
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
    $student = new Student;

    if ($page <> 0) 
    {
        $start = ($page-1)*ROWS_PER_PAGE;
        $limit = ROWS_PER_PAGE;
        $students = $student->_get_all($where_clause, $order_by, $start, $limit);
    }
    else 
    {
        $students = $student->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $students;
  }

  function get_id_and_field($fieldname) 
  {
    $students = new Student;
    return  $students->_get_id_and_field($fieldname);
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

  function get_programme_id($id)
  {
    $id = (int) $id; // Security

    $data = Student::get_id_and_field("programme_id","where id='$id'");
    return($data[$id]);
  }

  function get_assessment_group_id($id=0)
  {
    // If no student id is passed in, use current one
    //if(!$id) $id = $this->
    $student = Student::load_by_id($id);
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
    $group = AssessmentGroupProgramme::load_where("where programme_id = $programme_id and startyear is null and endyear is null");
    if($group->group_id) return($group->group_id);

    // Bail with the default
    return(1);
  }


  function get_assessment_regime($id)
  {
    // This will store the items
    $regime_items = array();

    // Determine the students assessmentgroup
    $assessmentgroup_id = Student::get_assessment_group_id($id);

    // Get the regime items
    require_once("model/AssessmentRegime.class.php");
    $regime_items = AssessmentRegime::get_all("where group_id=$assessmentgroup_id");

    // Sort these appropriately
    usort($regime_items, array("AssessmentRegime", "assessment_date_compare"));

    // Now augment this with data from results
    $weighting_total = 0;
    $aggregate_total = 0;

    for($loop = 0; $loop < count($regime_items); $loop++)
    {
      // Supervisor's don't see other assessments
      if(User::is_supervisor() && $regime_items[$loop]->assessor != 'industrial')
      {
        unset($regime_items[$loop]);
        continue;
      }
      // Do we want hidden stuff for students anymore?

      // Collect the weight as we go...
      $weighting_total += $regime_items[$loop]->weighting;

      // Try to determine the assessor
      /* this needs to go in the template"
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
      $results = AssessmentTotal::load_where("where regime_id = " . $regime_items[$loop]->id . " and assessed_id=" . Student::get_user_id($id));

      $percentage = $results->percentage;
      if(empty($percentage))
      {
        $percentage = "--";
        $aggregate = "--";
      }
      else
      {
        $percentage = sprintf("%.02f", $percentage);
        $aggregate = sprintf("%.02f", $percentage * $row['weighting']);
        $aggregate_total += $aggregate;
        $percentage .= "%";
        $aggregate .= "%";
      }
      // Add them to the array
      $regime_items[$loop]->percentage = $percentage;
      $regime_items[$loop]->aggregate = $aggregate;
    }
    return($regime_items);
/*
    // Custom sort
    usort($regime_items, "assessment_date_compare");
  
    $smarty->assign("student_id", $student_id);
    $smarty->assign("regime_items", $regime_items);
    $smarty->assign("aggregate_total", $aggregate_total);
    $smarty->assign("weighting_total", $weighting_total);
    $smarty->display("assessment/assessment_results.tpl");*/
  }



  function get_name($id)
  {
    return(User::get_name(Student::get_user_id($id)));
  }
}

?>