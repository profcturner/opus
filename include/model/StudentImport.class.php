<?php

/**
* Handles the mass import of students
* @package OPUS
*/

/**
* Handles the mass import of students
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/
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

  function import_via_file($programme_id, $year, $status, $onlyyear, $password, $test, $csv_mapping)
  {
    StudentImport::import_csv($_FILES['userfile']['tmp_name'], 1); //hardcode for test
    unlink($_FILES['userfile']['tmp_name']);
  }


  function import_csv($filename, $csvmap_id)
  {
    $standard_pattern =       "/^\"(.*)\",\"(.*)\",\"(.*)\",\"(.*)\",\"(.*)\",\"(.*)\",\"(.*)\",\"(.*)\"$/";
    global $waf;
    require_once("model/CSVMapping.class.php");

    // Get the csv mapping
    $csvmap = CSVMapping::load_by_id($csvmap_id);

    $fp = fopen($filename, "r");

    $rejected_lines = array();
    while($line = fgets ($fp, 2048))
    {
      $line = trim($line);
      // Valid lines must match the normal pattern, and not any exclude
      if(!preg_match($csvmap->pattern, $line) || (strlen($csvmap->exclude) && preg_match($csvmap->exclude, $line)))
      {
        array_push($rejected_lines, $line);
        continue; // move on
      }
      // Ok, do the replacement to change to standard format
      $line = preg_replace($csvmap->pattern, $csvmap->replacement, $line);
      // Finally extract data from the standard format to an array as if from SRS
      if(preg_match($standard_pattern, $line, $matches))
      {
        $student = array();
        $student['year']            = $matches[1];
        $student['reg_number']      = $matches[2];
        $student['person_title']    = $matches[3];
        $student['first_name']      = $matches[4];
        $student['last_name']       = $matches[5];
        $student['email_address']   = $matches[6];
        $student['programme_code']  = $matches[7];
        $student['disability_code'] = $matches[8];
        print_r($student);
        // debug for now.
      }
    }
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