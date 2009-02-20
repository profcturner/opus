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
      while(!feof($fp) && ($count < 10))
      {
        $line = trim(fgets ($fp, 2048));

        // Ignore empty lines
        if(empty($line)) continue;
        // We also don't count explicit "excludes".
        if(!empty($csvmap->exclude) && preg_match($csvmap->exclude, $line)) continue;

        // Ok, count this line, and then see if it's valid
        $count++;
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
    $standard_pattern = "/^\"(.*)\",\"(.*)\",\"(.*)\",\"(.*)\",\"(.*)\",\"(.*)\",\"(.*)\",\"(.*)\",\"(.*)\"$/";
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
    $mismapped_lines = array();
    while($line = fgets ($fp, 2048))
    {
      $line = trim($line);
      // Valid lines must match the normal pattern
      if(!preg_match($csvmap->pattern, $line))
      {
        array_push($rejected_lines, $line);
        continue; // move on
      }
      // and not be excluded
      if((strlen($csvmap->exclude) && preg_match($csvmap->exclude, $line)))
      {
        array_push($excluded_lines, $line);
        continue; // move on
      }

      // Ok, do the replacement to change to standard format
      $line = preg_replace($csvmap->pattern, trim($csvmap->replacement), $line);
      // Finally extract data from the standard format to an array as if from SRS
      if(!preg_match($standard_pattern, $line, $matches))
      {
        array_push($mismapped_lines, $line);
        continue; // move on
      }

      $student = array();
      $student['year_on_course']  = $matches[1];
      $student['reg_number']      = $matches[2];
      $student['person_title']    = $matches[3];
      $student['first_name']      = $matches[4];
      $student['last_name']       = $matches[5];
      $student['email_address']   = $matches[6];
      $student['programme_code']  = $matches[7];
      $student['disability_code'] = $matches[8];
      $student['username']        = $matches[9];

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
    $waf->assign("mismapped_lines", $mismapped_lines);
    if($test)
    {
      $waf->assign("action_links", array(array('cancel', 'section=configuration&function=import_data')));
    }
    else
    {
      unlink($filename);
    }
  }

  /**
  * Attempts to add a student with relevant information
  * 
  * @param $student_array an array of fields to be used
  * @param $programme_id the id of the programme
  * @param $status the placement status to initially set
  * @param $year the year seeking placement
  * @return the id of the student if created, from the student table
  */
  function add_student($student_array, $programme_id, $status, $year)
  {
    $waf =& UUWAF::get_instance();
    // Make their entry in the user table
    require_once("model/Student.class.php");

    $fields = array();
    $fields['reg_number'] = $student_array['reg_number'];
    // If we have a username, use it, otherwise default to reg_number
    if(!empty($student_array['username']))
    {
      $fields['username'] = $student_array['username'];
    }
    else
    {
      $fields['username'] = $student_array['reg_number'];      
    }
    $fields['quick_note'] = $student_array['quick_note'];
    $fields['salutation'] = $student_array['person_title'];
    $fields['firstname'] = $student_array['first_name'];
    $fields['lastname'] = $student_array['last_name'];
    $fields['email'] = $student_array['email_address'];
    $fields['placement_year']   = $year;
    $fields['placement_status'] = $status;
    $fields['programme_id']     = $programme_id;
    $fields['disability_code']  = $student_array['disability_code'];
    $fields['user_id']          = $user_id;
    $waf->log("added student " . $fields['firstname'] . " " . $fields['lastname'] . " (" . $student_array['reg_number'] . ")");
    return(Student::insert($fields));
  }
  
  /**
  * Attempts to add a student given nothing other than a reg_number
  * 
  * This function requires working web services to work, and can 
  * optionally take a placement status and placement year. This function
  * will attempt to create, via other code, the whole structure
  * necessary to contain an unseen student.
  * 
  * @param $reg_number the reg_number of the prospective student
  * @param $placement_status optionally the placement status, defaults to Required
  * @param $placement_year optionally the year seeking placement, otherwise OPUS guesses
  * @return the student user id if successful
  * @todo improve the placement year if possible
  */
  function auto_add_student($reg_number, $placement_status='Required', $placement_year=0)
  {
    global $config;
    $waf = UUWAF::get_instance();

    $waf->log("Request to auto add student $reg_number");    
    if($config['opus']['disable_auto_add_student'])
    {
      $waf->log('Auto creation of students is disabled in the configuration file');
      return(0);
    }
    
    require_once("model/WebServices.php");
    require_once("model/Student.class.php");
    if(User::count("where reg_number='$reg_number'"))
    {
      $waf->log("Student already in OPUS database, skipping");
      return;
    }
    
    // Get details on the student course
    $course_details = WebServices::get_student_course($reg_number);
    $programme_code = $course_details['programme_code'];
    if(empty($programme_code))
    {
      $waf->log("Cannot obtain programme details, cannot add student");
      return(0);
    }
    
    require_once("model/Programme.class.php");
    $programme = Programme::load_where("where srs_ident='$programme_code'");
    if(!$programme->id)
    {
      $waf->log("Missing programme : " . $course_details['programme_title'] . "(" . $course_details['programme_code'] . ")");
      if($config['opus']['disable_auto_add_student_on_unknown_programme'])
      {
        $waf->log('Automatic creation of students on unknown programmes is disabled in the configuration file');
        return(0);
      }
      // The course currently does not exist within OPUS
      // Try to create it
      $programme_id = Programme::auto_create($course_details);
      if($programme_id == false)
      {
        $waf->log("Cannot create base programme, so cannot add student");
        return(0);
      }
    }
    else $programme_id = $programme->id;
    
    // Ok, at last, can we add the student? Get the details
    $student_array = StudentImport::import_student_via_SRS($reg_number);
    // Try to guess the placement year, which is currently a pretty
    // primitive algorithm, unless we've been given it
    if(!$placement_year)
    {
      $year_on_course = $student_array['year_on_course'];
      $placement_year = get_academic_year() + (3 - $year_on_course);
    }
    
    $student_array['quick_note'] = 'auto_created';
    $student_id = StudentImport::add_student($student_array, $programme_id, "Required", $placement_year);
    
    $student_user_id = Student::get_user_id($student_id);
    if($student_user_id)
    {
      // Add a note on the user
      require_once("model/Note.class.php");
      Note::simple_insert_student($student_user_id, "Automatically created", "This student was automatically created");
      
      // And add them to a channel if possible
      $channel_name = $config['opus']['auto_created_student_channel'];
      if(empty($channel_name)) $channel_name = "AutoCreatedStudents";
      
      require_once("model/Channel.class.php");
      $channel = Channel::load_where("where name='$channel_name'");
      if($channel->id)
      {
        // channel exists
        require_once("model/ChannelAssociation.class.php");
        $fields = array();
        $fields['permission'] = 'enable';
        $fields['channel_id'] = $channel->id;
        $fields['type'] = 'user';
        $fields['object_id'] = $student_user_id;
        ChannelAssociation::insert($fields);
      }
    }
    
    return($student_user_id);
  }
}

?>
