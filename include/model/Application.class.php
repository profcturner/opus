<?php
/**
* This class defines and handles the applications students make for listed vacancies.
* @package OPUS
*/
require_once("dto/DTO_Application.class.php");
/**
* This class defines and handles the applications students make for listed vacancies.
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see Vacancy.class.php
* @package OPUS
*
*/
class Application extends DTO_Application 
{
  var $company_id = 0;         // Company applied for
  var $vacancy_id = 0 ;        // Vacancy applied for
  var $student_id = 0;         // Student making applications
  var $created = "";           // Initial application timestamp
  var $modified = "";          // Last modification time for application
  var $cv_ident = "";          // Where the CV is coming from (source:type:id)
  var $archive_mime_type = ""; // Mime type for a custom CV
  var $portfolio_ident = "";  // Where the portfolio comes from (if any)
  var $cover = "";             // Cover letter if any
  var $status = "";            // Status as set by company
  var $lastseen = "";          // When last seen by company
  var $status_modified = "";   // When the status was last set
  var $addedby = 0;            // Id of user who added this

  static $_field_defs = array(
    'company_id'=>array('type'=>'lookup', 'size'=>30, 'maxsize'=>100, 'title'=>'Company', 'header'=>true),
    'vacancy_id'=>array('type'=>'lookup', 'size'=>30, 'maxsize'=>100, 'header'=>true, 'title'=>'Vacancy'),
    'status'=>array('type'=>'text', 'size'=>30, 'maxsize'=>100, 'title'=>'Status', 'header'=>true),
    'created'=>array('type'=>'text', 'size'=>30, 'maxsize'=>100, 'title'=>'Applied', 'header'=>true)
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
    $application = new Application;
    $application->id = $id;
    $application->_load_by_id();
    return $application;
  }

  function load_where($where_clause="") 
  {
    $application = new Application;
    $application->_load_where($where_clause);
    return $application;
  }

  function insert($fields) 
  {
    // Null some fields if empty
    $fields = Application::set_empty_to_null($fields);

    // Record who made the initial application (student or admin really)
    $fields['addedby'] = User::get_id();

    $fields['created'] = date("YmdHis");
    $fields['status'] = "unseen";

    // Work out any mime type we need
    $cv_ident_parts = explode(":", $fields['cv_ident']);
    switch($cv_ident_parts[0])
    {
      case 'internal':
        require_once("model/Artefact.class.php");
        $artefact = Artefact::load_by_hash($cv_ident_parts[2]);
        $fields['archive_mime_type'] = $artefact->file_type;
        break;
      case 'pdsystem':
        switch($cv_ident_parts[1])
        {
          case 'hash':
            require_once("model/PDSystem.class.php");
            $fields['archive_mime_type'] = PDSystem::get_artefact_mime_type($fields['student_id'], $cv_ident_parts[2]); // needs
            break;
        }
        break;
    }

    $application = new Application;
    $application->_insert($fields);

    // Invalidate any timeline, causing it to be redrawn
    require_once("model/Timeline.class.php");
    Timeline::invalidate($fields['student_id']);
  }

  function update($fields) 
  {
    // Null some fields if empty
    //$fields = Application::set_empty_to_null($fields);
    // Some extra fields are being nuked, I don't know why...
    unset($fields['created']);

    require_once("model/User.class.php");
    if(User::is_student())
    {
      $fields['modified'] = date("YmdHis");
    }

    // Work out any mime type we need
    $cv_ident_parts = explode(":", $fields['cv_ident']);
    switch($cv_ident_parts[0])
    {
      case 'internal':
        require_once("model/Artefact.class.php");
        $artefact = Artefact::load_by_hash($cv_ident_parts[2]);
        $fields['archive_mime_type'] = $artefact->file_type;
        break;
      case 'pdsystem':
        switch($cv_ident_parts[1])
        {
          case 'hash':
            require_once("model/PDSystem.class.php");
            $fields['archive_mime_type'] = PDSystem::get_artefact_mime_type($fields['student_id'], $cv_ident_parts[2]); // needs
            break;
        }
        break;
    }

    // If we change the status, timestamp that
    if(!empty($fields['status']))
    {
      $fields['status_modified'] =  date("YmdHis");
    }
    $application = Application::load_by_id($fields["id"]);
    $application->_update($fields);
  }

  /**
  * Goes through certain fields and sets them to null if they are "empty"
  */
  function set_empty_to_null($fields)
  {
    $set_to_null = array("created", "modified", "lastseen", "status_modified");
    foreach($set_to_null as $field)
    {
      if(isset($fields[$field]) && !strlen($fields[$field])) $fields[$field] = null;
    }
    return($fields);
  }

  /**
  * Wasteful
  */
  function exists($id) 
  {
    $application = new Application;
    $application->id = $id;
    return $application->_exists();
  }
  
  /**
  * Wasteful
  */
  function count($where_clause="") 
  {
    $application = new Application;
    return $application->_count($where_clause);
  }

  function get_all($where_clause="", $order_by="ORDER BY id", $page=0)
  {
    global $config;
    $application = new Application;

    if ($page <> 0) {
      $start = ($page-1)*$config['opus']['rows_per_page'];
      $limit = $config['opus']['rows_per_page'];
      $applications = $application->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $applications = $application->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $applications;
  }

  /**
  * fetches triaged, augments lists of applications for a vacancy
  *
  * the applications are augmented with the student name and programme name
  *
  * @param int $vacancy_id the id of the vacancy to fetch applications for
  * @return an array, containing arrays of placed, available and unavailable students
  */
  function get_all_triaged($vacancy_id)
  {
    $vacancy_id = (int) $vacancy_id; // security
    // Get an initial list
    $applications = Application::get_all("where vacancy_id = $vacancy_id", "order by created");

    // Now start to triage, and augment.
    $placed = array();
    $available = array();
    $unavailable = array();

    require_once("model/Student.class.php");
    require_once("model/Programme.class.php");
    foreach($applications as $application)
    {
      // Get the student's details
      $student = Student::load_by_user_id($application->student_id);
      // Augment the record
      $application->_student_real_name = $student->real_name;
      $application->_student_programme = Programme::get_name($student->programme_id);
      $application->_student_email = $student->email;
      $application->_student_table_id = $student->id;

      if($student->placement_status == 'Required')
      {
        array_push($available, $application);
      }
      else
      {
        if($student->placement_status == 'Placed')
        {
          // With this company?
          require_once("model/Placement.class.php");
          if(Placement::count("where vacancy_id=$vacancy_id and student_id=" . $student->user_id))
          {
            array_push($placed, $application);
          }
          else
          {
            // Student placed elsewhere
            array_push($unavailable, $application);
          }
        }
        else
        {
          // Otherwise, the student has another status
          array_push($unavailable, $application);
        }
      }
    }
    return(array($placed, $available, $unavailable));
  }

  function get_id_and_field($fieldname, $where_clause="") 
  {
    $application = new Application;
    $application_array = $application->_get_id_and_field($fieldname, $where_clause);
    unset($application_array[0]);
    return $application_array;
  }

  function remove($id=0) 
  {
    $application = new Application;
    $application->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {
    // Too much erases dates, need to look at this problem
    $fields = array('company_id', 'vacancy_id', 'student_id', 'cv_ident', 'portfolio_ident', 'cover');
    if($include_id) $fields = array_merge($fields, array('id'));
    return $fields; 
  }

  function request_field_values($include_id = false) 
  {
    $fieldnames = Application::get_fields($include_id);
    $nvp_array = array();

    foreach ($fieldnames as $fn)
    {
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
    }
    return $nvp_array;
  }

  function get_id_for_vacancy($vacancy_id, $student_id)
  {
    $vacancy_id = (int) $vacancy_id;
    $student_id = (int) $student_id;
    $application = new Application;
    $id = $application->_get_fields("where vacancy_id = $vacancy_id and student_id = $student_id");
    return($id);
  }

  /**
  * labels an application as seen, only if it is currently unseen
  *
  * @param int $application_id the id from the application table
  */
  function ensure_seen($application_id)
  {
    if(!User::is_company()) return; // Only company contacts should trigger this

    $application = Application::load_by_id($application_id);
    if($application->id)
    {
      if($application->status == 'unseen')
      {
        $fields['status'] = 'seen';
        $fields['status_modified'] = date("YmdHis");
        $fields['id'] = $application->id;
        Application::update($fields);
      }
    }
  }

}
?>