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
    $waf =& UUWAF::get_instance();

    require_once("model/Programme.class.php");
    $programme = Programme::load_by_id($programme_id);

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
    $srs_students = WebServices::get_students_by_course($programme->srs_ident, "20" . substr($year-1, 2) . substr($year, 2), $onlyyear);
    $students = array();
    require_once("model/User.class.php");
    foreach($srs_students as $student)
    {
      $student_array = StudentImport::import_student_via_SRS($student[1]);

      // Are they already present?
      if(User::count("where reg_number='" . $student['reg_number'] . "'"))
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
    $student_srs = WebServices::get_student($reg_number);

    $student = array();
    $student['username'] = $username;
    $student['reg_number'] = $reg_number;
    $student['person_title'] = $student_srs['title'];
    $student['first_name'] = $student_srs['first_name'];
    $student['last_name'] =  $student_srs['last_name'];
    $student['email_address'] = $student_srs['email_uni'];
    $student['disability_code'] = $student_srs['disability_code'];
    $student['year_on_course'] = $student_srs['year_on_course'];
    return($student);
  }

  /**
  * attempts to automatically determine the most relevant CSV mapping for a file
  *
  * the first ten lines of a file are examined against each pattern in turn. If
  * more than 80% of the read lines are matched by the pattern then this pattern
  * is assumed to be correct.
  *
  * @param string $filename the name of the file containing the CSV data
  * @return the id of the correct map, or zero if none could be found
  */
  function guess_mapping($filename)
  {
    $waf =& UUWAF::get_instance();

    require_once("model/CSVMapping.class.php");
    $csvmapping = new CSVMapping;
    $allmappings = $csvmapping->_get_all();

    $fp = fopen($filename, "r");

    foreach($allmappings as $csvmap)
    {
      $valid_lines = 0;
      $count = 0;
      // Only look at up to the first ten lines
      while(($line = fgets ($fp, 2048)) && ($count++ < 10))
      {
        // Count the number of valid lines from these
        if(preg_match($csvmap->pattern, $line)) $valid_lines++;
      }
      // Success?
      if(($valid_lines / $count) >= 0.8)
      {
        fclose($fp);
        $waf->log("file being imported is guessed to be of format " . $csvmap->name);
        return($csvmap->id);
      }

      // Back to the start for the next attempt
      fseek($fp, 0);
    }
    fclose($fp);
    $waf->log("file being imported does not match any known mapping");
    return(0);
  }


  function import_csv($filename, $programme_id, $year, $status, $onlyyear, $password, $test, $csvmapping_id)
  {
    // This is the pattern OPUS expects at the end of a mapping
    $standard_pattern =       "/^\"(.*)\",\"(.*)\",\"(.*)\",\"(.*)\",\"(.*)\",\"(.*)\",\"(.*)\",\"(.*)\"$/";
    $waf =& UUWAF::get_instance();
    require_once("model/Programme.class.php");
    require_once("model/CSVMapping.class.php");
    $programme = Programme::load_by_id($programme_id);

    // Do we already have a mapping? If not try and guess one
    if($csvmapping_id == 0) $csvmapping_id = StudentImport::guess_mapping($filename);
    // Still don't know?
    if($csvmapping_id == 0) $waf->halt("error:student_import:no_valid_map");

    // Get the csv mapping
    $csvmap = CSVMapping::load_by_id($csvmapping_id);

    $fp = fopen($filename, "r");

    $students = array();
    $rejected_lines = array();
    $excluded_lines = array();
    while($line = fgets ($fp, 2048))
    {
      $line = trim($line);
      // Valid lines must match the normal pattern
      if(!preg_match($csvmap->pattern, $line))
      {
        array_push($rejected_lines, $line);
        continue; // move on
      }
      // and not be exluded
      if((strlen($csvmap->exclude) && preg_match($csvmap->exclude, $line)))
      {
        array_push($excluded_lines, $line);
        continue; // move on
      }

      // Ok, do the replacement to change to standard format
      $line = preg_replace($csvmap->pattern, $csvmap->replacement, $line);
      // Finally extract data from the standard format to an array as if from SRS
      preg_match($standard_pattern, $line, $matches);

      $student = array();
      $student['year_on_course']  = $matches[1];
      $student['reg_number']      = $matches[2];
      $student['person_title']    = $matches[3];
      $student['first_name']      = $matches[4];
      $student['last_name']       = $matches[5];
      $student['email_address']   = $matches[6];
      $student['programme_code']  = $matches[7];
      $student['disability_code'] = $matches[8];

      if(User::count("where reg_number='" . $student['reg_number'] . "'"))
      {
        // Already exists
        $student['result'] = "Exists";
      }
      elseif(strlen($student['programme_code']) && ($programme->srs_ident != $student['programme_code']))
      {
        $student['result'] = "Invalid Programme";
      }
      elseif(strlen($onlyyear) && $student['year_on_course'] != $onlyyear)
      {
        $student['result'] = "Invalid Year";
        //print_r($student);
        //echo $onlyyear;
      }
      else
      {
        if(!$test) StudentImport::add_student($student, $programme_id, $status, $year);
        $student['result'] = "Added";
      }
      array_push($students, $student);
    }
    $waf->assign("programme", $programme);
    $waf->assign("students", $students);
    $waf->assign("test", $test);
    $waf->assign("year", $year);
    $waf->assign("onlyyear", $onlyyear);
    $waf->assign("status", $status);
    $waf->assign("csvmapping", $csvmap);
    $waf->assign("filename", $_FILES['userfile']['tmp_name']);
    $waf->assign("rejected_lines", $rejected_lines);
    $waf->assign("excluded_lines", $excluded_lines);
    if($test)
    {
      $waf->assign("action_links", array(array('cancel', 'section=configuration&function=import_data')));
    }
    else
    {
      unlink($filename);
    }
  }

  function add_student($student_array, $programme_id, $status, $year)
  {
    $waf =& UUWAF::get_instance();
    // Make their entry in the user table
    require_once("model/Student.class.php");

    $fields = array();
    $fields['reg_number'] = $student_array['reg_number'];
    $fields['username'] = $student_array['reg_number'];
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
  
  function auto_add_student($reg_number)
  {
    $waf = UUWAF::get_instance();
    
    $waf->log("Request to auto add student $reg_number");
    require_once("model/Student.class.php");
    if(Student::count("where reg_number='$reg_number'"))
    {
      $waf->log("Student already in OPUS database, skipping");
      return;
    }
    
    // Get details on the student course
    $course_details = WebServices::get_student_course($reg_number);
    
    $programme_code = $course_details['programme_code'];
    require_once("model/Programme.class.php");
    
    $programme = Programme::load_where("where srs_ident='$programme_code'");
    if($programme->id)
    {
      $waf->log("Missing programme : " . $course_details['programme_title'] . "(" . $course_details['programme_code'] . ")");
      // The course currently does not exist within OPUS
      // Try to create it
      $programme_id = Programme::auto_create($course_details);
      if($programme_id == false)
      {
        $waf->log("Cannot create base programme, so cannot add student");
        return;
      }
    }
    else $programme_id = $programme->id;
    
    // Ok, at last, can we add the student? Get the details
    $student_array = StudentImport::import_student_via_SRS($reg_number);
    // Try to guess the placement year, which is currently a pretty
    // primitive algorithm
    $year_on_course = $student_array['year_on_course'];
    $placement_year = get_academic_year() + (3 - $year_on_course);
    StudentImport::add_student($student_array, $programme_id, "Required", $placement_year);
    
  }
}

?>
