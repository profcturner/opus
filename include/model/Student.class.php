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
  var $voice            = ""; // phone number
  var $address          = ""; // address details
  var $quick_note       = ""; // quick note
//  var $vacancy_type     = ""; // the preferred vacancy type 

  // Several of these fields actually reside in the User table
  static $_field_defs = array
  (
    'salutation'=>array('type'=>'text', 'size'=>20, 'header'=>true, 'title'=>'Title', 'mandatory'=>true),
    'firstname'=>array('type'=>'text','size'=>30, 'header'=>true),
    'lastname'=>array('type'=>'text','size'=>30, 'header'=>true, 'mandatory'=>true),
    'email'=>array('type'=>'email','size'=>40, 'mandatory'=>true),
    'reg_number'=>array('type'=>'text', 'size'=>15, 'readonly'=>true, 'mandatory'=>true),
    'placement_year'=>array('type'=>'text','size'=>5, 'title'=>'Placement Year', 'mandatory'=>true),
    'placement_status'=>array('type'=>'list', 'list'=>array('Required'=>'Required','Placed'=>'Placed','Exempt Applied'=>'Exempt Applied','Exempt Given'=>'Exempt Given','No Info'=>'No Info','Left Course'=>'Left Course','Suspended'=>'Suspended','To final year'=>'To final year','Not Eligible'=>'Not Eligible')),
    'academic_user_id'=>array('type'=>'lookup', 'object'=>'staff', 'value'=>'dud', 'title'=>'Academic Tutor', 'var'=>'tutors', 'lookup_function'=>'lookup_tutors_by_school'),
    'programme_id'=>array('type'=>'lookup', 'object'=>'programme', 'value'=>'name', 'title'=>'Programme', 'var'=>'programmes', 'lookup_function'=>'get_id_and_description'),
    'voice'=>array('type'=>'text' ,'size'=>20, 'title'=>'Phone Number'),
    'address'=>array('type'=>'textarea', 'rowsize'=>5, 'colsize'=>40, 'maxsize'=>1000),
    'quick_note'=>array('type'=>'text' ,'size'=>40, 'title'=>'Quick Note', 'header'=>true),
//    'vacancy_type'=>array('type'=>'lookup', 'object'=>'vacancytype', 'value'=>'name', 'title'=>'Type', 'var'=>'vacancytypes'),
  );

  // Root users can edit reg_numbers
  static $_root_field_defs_override = array
  (
    'reg_number'=>array('type'=>'text', 'size'=>15, 'mandatory'=>true),
  );

  // This defines which ones
  static $_extended_fields = array
  (
    'salutation','firstname','lastname','email','reg_number','username'
  );

  /**
  * constructor establishes that this uses the default database connection
  */
  function __construct() 
  {
    parent::__construct('default');
  }

  /**
  * obtain all the field_defs
  * 
  * there are some overrides for root users.
  * @return the standard or augmented field_defs as required by user class
  */
  function get_field_defs()
  {
    $field_defs = self::$_field_defs;
    if(User::is_root())
    {
      $field_defs = array_merge($field_defs, self::$_root_field_defs_override);
    }
    return $field_defs;
  }

  /**
  * obtain all the extra fields stored in other tables
  * 
  * @return an array of field names
  */
  function get_extended_fields()
  {
    return self::$_extended_fields;
  }

  /**
  * load a student by the id from the user table instead of the student table
  * 
  * @param int $student_user_id the id from the user table for the student
  * @return the student object
  */
  function load_by_user_id($student_user_id)
  {
    return(Student::load_by_id(Student::get_id_from_user_id($student_user_id)));
  }

  /**
  * obtain the id from the student table, given a user id
  * 
  * @param int $student_user_id the id from the user table for the student
  * @return the id from the student table for the same student
  */
  function get_id_from_user_id($student_user_id)
  {
    $student_user_id = (int) $student_user_id; // security

    $student = new Student;
    $id = $student->_get_fields("id", "where user_id = $student_user_id");
    return($id);
  }

  /**
  * load a student object based on the id from the student table
  * 
  * @param int $id the id from the student table
  * @return the student object, check id in object for validity
  */
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
  * 
  * @param array $fields an associative array of fields to insert
  * @return the id allocated to the inserted object
  * @see User::insert()
  */
  function insert($fields) 
  {
    $waf =& UUWAF::get_instance();
    require_once("model/User.class.php");

    $student = new Student;
    $extended_fields = Student::get_extended_fields();
    $user_fields = array();

    // Extract user data
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
    if(empty($user_fields['username'])) $user_fields['username'] = $user_fields['reg_number'];
    if(empty($user_fields['username'])) $waf->halt("error:student_insert:no_username");
    $user_fields['user_type'] = 'student';
    $user_id = User::insert($user_fields);

    // Now we know the user_id, to populate the other tables
    $fields['user_id'] = $user_id;

    return $student->_insert($fields);
  }

  /**
  * updates data about a student to the User and Student tables
  *
  * This is more sophisticated that usual because there are two tables.
  * Certain fields are unset for security reasons.
  * 
  * @param array $fields an associative array of fields to update
  * @return the id allocated to the inserted object
  * @see User::update()
  */
  function update($fields) 
  {
    $waf =& UUWAF::get_instance();
    // We have a potential security problem here, we should check id and user_id are really linked.
    $student = Student::load_by_id($fields['id']);

    if(!isset($fields['user_id'])) $fields['user_id'] = $student->user_id;
    if($student->user_id != $fields['user_id'])
    {
      $waf->security_log("attempt to update student with mismatching user_id fields");
      $waf->halt("error:student:user_id_mismatch");
    }

    // Only root users can change reg numbers
    if(!User::is_root()) unset($fields['reg_number']);
    // No-one can change usernames
    unset($fields['username']);
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
    // Sometimes there is nothing to change, and this results in a harmless,
    // but annoying, SQL error
    if(count($user_fields) > 1)
    {
      // Not just the id field
      User::update($user_fields);
    }

    // If the status has changed, make a note to that effect
    if(!empty($fields['placement_status']))
    {
      if($fields['placement_status'] != $student->placement_status)
      {
        require_once("model/Note.class.php");
        Note::simple_insert_student($student->user_id, "placement status change", "status changed from " . $student->placement_status . " to " . $fields['placement_status']);
      }
    }
    $student->_update($fields);

    // Finally invalidate any timeline to ensure it is correctly regenerated
    require_once("model/Timeline.class.php");
    Timeline::invalidate($fields['id']);
  }

  /**
  * checks if a student exists based on id in the student table
  * @param $id the id from the student table
  * @return true if the student exists, false otherwise
  */
  function exists($id) 
  {
    $student = new Student;
    $student->id = $id;
    return $student->_exists();
  }

  /**
  * counts the number of students who meet certain criteria
  * 
  * @param string $where optional where clause (defaults to empty)
  * @return the number of matching students
  */
  function count($where="") 
  {
    $student = new Student;
    return $student->_count($where);
  }

  /**
  * function to fetch all students with paging
  * 
  * @param string $where_clause an optional where clause (defaults to empty)
  * @param string $order_by an optional order clause (defaults to lastname)
  * @param int $page a page number, set to zero for initial search
  */
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

  /**
  * fetches only matching ids for students
  * 
  * to minimise returned data for complex queries, this returns ids from the
  * student table only
  * 
  * @param string $where_clause optional where clause (defaults to empty)
  * @param string $order_clause optional order clause (defaults to empty)
  * @return array of ids from the student table
  */
  function get_ids($where_clause="", $order_clause="")
  {
    $student = new Student;
    return($student->_get_ids($where_clause, $order_clause));
  }

  /**
  * fetches the values of a given field, indexed by id from the student table
  * 
  * @param string $fieldname the name of the field to fetch
  * @param string $where_clause an optional where clause (defaults to empty)
  * @return the array of values of the field, indexed by id
  */
  function get_id_and_field($fieldname, $where_clause="") 
  {
    $students = new Student;
    return  $students->_get_id_and_field($fieldname, $where_clause);
  }

  /**
  * return an array of all column names used in the student table
  * 
  * @param boolean $include_id whether to include id (defaults to false)
  * @return array of field names
  */
  function get_fields($include_id = false) 
  {
    $student = new Student;
    return  $student->_get_fieldnames($include_id);
  }

  /**
  * obtains all the relevant field values from the the request variables
  * 
  * @param boolean $include_id whether to include id (defaults to false)
  * @return associative array of fieldname, value pairs
  */
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

  /**
  * removes a given student record
  * 
  * @param int $id the id from the student table to remove (defaults to zero for safety)
  */
  function remove($id=0) 
  {
    $student = new Student;
    $student->_remove_where("WHERE id=$id");
  }

  /**
  * fetches a list of students who meet certain search criteria
  * 
  * the list of students will be filtered for the permissions of the logged in
  * user.
  * 
  * @param string $search optional search field
  * @param int $year optional year placement should commence
  * @param array $programmes array of programme ids
  * @param string $sort a sort criterion
  * @param array $other_options various other tweaks to the search
  * @return an array of student information, itself in arrays
  */
  function get_all_extended($search, $year, $programmes, $sort, $other_options)
  {
    $student = new Student;
    return($student->_get_all_extended($search, $year, $programmes, $sort, $other_options));
  }

  /**
  * fetches a list of all students whose last name begins with an initial
  * 
  * the list of students will be filtered for the permissions of the logged in
  * user.
  * 
  * @param string $initial letter to match against last name
  * @return an array of student information, itself in arrays
  */
  function get_all_by_initial($initial)
  {
    $student = new Student;
    return($student->_get_all_by_initial($initial));
  }

  /**
  * fetch the id from the user table for a given student
  * 
  * @param int $id the id from the student table
  * @return the id from the user table (or zero if missing)
  */
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
  * @return the id from the programme table (or zero if missing)
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
  * @return the year in which placement would start
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
  * @return the id from the user table for the academic tutor (or zero if none)
  */
  function get_academic_user_id($user_id)
  {
    $user_id = (int) $user_id; // Security
    $student = new Student;
    return($student->_get_fields("academic_user_id","where user_id='$user_id'"));
  }

  /**
  * gets the cv group id
  *
  * @param int $user_id the id from the <strong>user</strong> table
  * @return the id of the cv group
  */
  function get_cv_group_id($user_id)
  {
    require_once("model/Programme.class.php");
    $programme_id = Student::get_programme_id($user_id);
    $group_id = Programme::get_cv_group_id($programme_id);
    if(!$group_id) $group_id=1; // Null means default group
    return($group_id);
  }

  /**
  * gets the assessment group id
  *
  * @param int $user_id the id from the <strong>user</strong> table
  * @return the id of the assessment group
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

  /**
  * fetches all assessment information for a student thus far recorded
  * 
  * This function performs a great deal of tasks, it obtains the whole
  * list of assessment regime items a student will undertake, augments them
  * with assessor information and marks so far, and sorts them, as well as
  * indicating which is early, pending or late.
  * 
  * @param int $student_user_id the id of the student from the user table
  * @param int $aggregate_total a reference into which to fill the total so far
  * @param int $weighting_total a reference into which to fill the total weight
  * @return an array of assessment regime items, augmented and sorted
  */
  function get_assessment_regime($student_user_id, &$aggregate_total, &$weighting_total)
  {
    // This will store the items
    $final_items = array();

    // Determine the students assessmentgroup
    $assessmentgroup_id = Student::get_assessment_group_id($student_user_id);

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
        $assessorother = AssessorOther::load_where("where assessed_id=$student_user_id and regime_id=" . $regime_item->id);
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
      $results = AssessmentTotal::load_where("where regime_id = " . $regime_item->id . " and assessed_id=$student_user_id");

      $percentage = $results->percentage;
      if(empty($results->id))
      {
        $percentage = "--";
        $aggregate = "--";
        $punctuality = $regime_item->get_punctuality($student_user_id);
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

  /**
  * fetch a list of assessments and assessors currently allocated for "other"
  * 
  * Most assessments are categorised against academic tutors, workplace
  * supervisors and so on, but some are simply labelled as "other". These can
  * be assigned to certain academic staff within OPUS, and this function
  * allows for a full list of these, and the assessors to be obtained
  * 
  * @param int $student_user_id the id of the student from the user table
  * @return an array of AssessmentRegime items, augmented with assessor_id
  * @see AssessorOther.class.php
  * @see AssessmentRegime.class.php
  */
  function get_other_assessors($student_user_id)
  {
    // This will store the items
    $final_items = array();

    // Determine the students assessmentgroup
    $assessmentgroup_id = Student::get_assessment_group_id($student_user_id);

    // Get the regime items
    require_once("model/AssessmentRegime.class.php");
    $regime_items = AssessmentRegime::get_all("where assessor='other' and group_id=$assessmentgroup_id");

    // Augment
    foreach($regime_items as $item)
    {
      require_once("model/AssessorOther.class.php");
      $assessorother = AssessorOther::load_where("where assessed_id=$student_user_id and regime_id=" . $item->id);
      $item->assessor_id = $assessorother->assessor_id;
      array_push($final_items, $item);
    }

    // Sort these appropriately
    usort($final_items, array("AssessmentRegime", "assessment_date_compare"));

    return($final_items);
  }

  /**
  * obtains the last time at which a student made an application (if any)
  * 
  * @param int $id the id of the student from the user table
  * @return a standard database datetime field (or empty if there are none)
  */
  function get_last_application_time($student_user_id)
  {
    require_once("model/Application.class.php");
    $application = new Application;
    // Get the last application!
    $applications = $application->_get_all("where student_id=$student_user_id", "order by created DESC", 0, 1);
    $application = $applications[0];

    return $application->created;
  }
	
	/**
	* returns if it is permissible for a student to apply for placement
	* 
	* @param int $user_id the id of the student from the user table
	* @return true if applications are allowed, false otherwise
	* @todo return more detailed information about why for ui dislay
	* @todo date_compare would be better here, but that is problematic
	*/
	function is_application_allowed($user_id)
	{
		$allowed_status =  array('Required','Exempt Applied','Exempt Given','To final year');

		$student = Student::load_by_user_id($user_id);
		
		// Fast return in simplest case
		if(in_array($student->placement_status, $allowed_status)) return true;
		
		if($student->placement_status == 'Placed')
		{
			// But are these all over?
			require_once("model/Placement.class.php");
			$placement = Placement::get_most_recent($user_id);
			
			if(!$placement)
			{
				return false; // no placement found
			}
			else
			{
				// If we are missing the jobend, we can't make assumptions
				if(empty($placement->jobend)) return false;
				
				// Are we past the end of the jobend date?
				// Nasty, possible problematic (see todo)
				if(strcmp($placement->jobend, date("Y-m-d")) < 0) return true;
				
				// Finally, we aren't, so we're still "placed" now.
				return false;
			}
		}
		
		return false; // otherwise no...
	}

  /**
  * obtains the full name of the student
  * 
  * this call is widely used in an automated fashion by various insert / edit
  * calls and so on.
  * 
  * @param int $id the id of the student, from the student table
  * @return the string with the real name field (title firstname lastname)
  */
  function get_name($id)
  {
    return(User::get_name(Student::get_user_id($id)));
  }
}

?>