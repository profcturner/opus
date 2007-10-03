<?php

class StudentImport
{

  function import_programme_via_SRS($programme_id, $year, $status, $onlyyear, $password, $test)
  {
    global $config_sensitive;
    global $waf;

    require_once("model/Programme.class.php");
    $programme = Programme::load_by_id($programme_id);

    //if(empty($course_id)) die_gracefully("Unknown course");
    //if(empty($onlyyear)) die_gracefully("You must specify the import year in this method");

    if($config_sensitive['ws']['url'])
    {
      $waf->assign("ws_enabled", true);
    }
    else
    {
      $waf->assign("ws_enabled", false);
    }

    require_once("model/WebServices.php");
    // Oddly, for 06/07 the webservice uses 07, not 06!
    $programme_xml = WebServices::get_course($programme->srs_ident, substr($year+1, 2), $onlyyear);
    $students = array();
    require_once("model/User.class.php");
    foreach($programme_xml->students->student as $student)
    {
      $student_array = StudentImport::import_student_via_SRS($student->reg_number);

      // Are they already present?
      if(User::count("where reg_number='" . $student->reg_number . "'"))
      {
        // Already exists
        $student_array['result'] = "Exists";
      }
      else
      {
        if(!$test) StudentImport::add_student($student_array, $programme_id, $status, $year);
        $student_array['result'] = "Added";
      }

      array_push($students, $student_array);
    }

    $waf->assign("programme", $programme);
    $waf->assign("students", $students);
    $waf->assign("test", $test);
    $waf->assign("year", $year);
    $waf->assign("onlyyear", $onlyyear);
    $waf->assign("status", $status);
    if($test) $waf->assign("action_links", array(array('cancel', 'section=configuration&function=import_data')));
  }

  /**
  * @todo should be able to simplify this due to new evolutions in the WS layer
  */
  function import_student_via_SRS($reg_number)
  {
    $student_xml = WebServices::get_student($reg_number);

    $student = array();
    $student['reg_number'] = $reg_number;
    $student['person_title'] = $student_xml->person_title;
    $student['first_name'] = $student_xml->first_name;
    $student['last_name'] =  $student_xml->last_name;
    $student['email_address'] = $student_xml->email_address;
    $student['disability_code'] = $student_xml->disability_code;
    $student['year_on_course'] = $student_xml->year_on_course;
    return($student);
  }

  function add_student($student_array, $programme_id, $status, $year)
  {
    global $waf;
    // Make their entry in the user table
    require_once("model/Student.class.php");

    $fields = array();
    $fields['reg_number'] = $student_array['reg_number'];
    $fields['username'] = "s" . $student_array['reg_number'];
    $fields['salutation'] = $student_array['person_title'];
    $fields['firstname'] = $student_array['first_name'];
    $fields['lastname'] = $student_array['last_name'];
    $fields['email'] = $student_array['email_address'];
    $fields['placement_year']   = $year;
    $fields['placement_status'] = $status;
    $fields['programme_id']     = $programme_id;
    $fields['disability_code']  = $student_array['disability_code'];
    $fields['user_id']          = $user_id;
    $waf->log("added student " . $fields['firstname'] . " " . $fields['surname']);
    Student::insert($fields);
  }
}

?>