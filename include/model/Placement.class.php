<?php

/**
* Handles the recording of placements themselves
* @package OPUS
*/
require_once("dto/DTO_Placement.class.php");
/**
* Handles the recording of placements themselves
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/

class Placement extends DTO_Placement 
{
  var $position;              // The job title
  var $jobstart;              // When the job starts
  var $jobend;                // When the job ends
  var $salary;                // The salary available
  var $voice;                 // The phone number while on placement
  var $email;                 // The email while on placement
  var $created;               // When the record was created
  var $modified;              // When the record was modified
  var $supervisor_title;      // The supervisor salutation
  var $supervisor_firstname;
  var $supervisor_lastname;
  var $supervisor_email;
  var $supervisor_voice;
  var $company_id;            // The company of the placement
  var $vacancy_id;            // The vacancy placed with
  var $student_id;            // The id, from the user table for the student


  /**
  * field definitions used to define how certain table fields should be treated
  */ 
  static $_field_defs = array(
    'position'=>array('type'=>'text', 'size'=>30, 'maxsize'=>100, 'title'=>'Job Description','header'=>true, 'mandatory'=>true),
    'jobstart'=>array('type'=>'isodate', 'inputstyle'=>'popup', 'required'=>'true', 'title'=>'Job Start Date'),
    'jobend'=>array('type'=>'isodate', 'inputstyle'=>'popup', 'title'=>'Job End Date'),
    'salary'=>array('type'=>'text', 'size'=>6, 'maxsize'=>20),
    'voice'=>array('type'=>'text', 'size'=>20, 'maxsize'=>40, 'title'=>'Placement Phone'),
    'email'=>array('type'=>'email', 'size'=>20, 'maxsize'=>100, 'title'=>'Placement Email'),
    'supervisor_title'=>array('type'=>'text', 'size'=>5, 'maxsize'=>100, 'title'=>"Supervisor Title<br /><small>Mr, Dr, etc.</small>", 'mandatory'=>true),
    'supervisor_firstname'=>array('type'=>'text', 'size'=>20, 'maxsize'=>100, 'title'=>"Supervisor First name"),
    'supervisor_lastname'=>array('type'=>'text', 'size'=>20, 'maxsize'=>100, 'title'=>"Supervisor Last name", 'mandatory'=>true),
    'supervisor_email'=>array('type'=>'email', 'size'=>40, 'maxsize'=>100, 'title'=>"Supervisor Email"),
    'supervisor_voice'=>array('type'=>'text', 'size'=>20, 'maxsize'=>100, 'title'=>"Supervisor Phone"),
    'company_id'=>array('type'=>'hidden'),
    'vacancy_id'=>array('type'=>'hidden'),
    'student_id'=>array('type'=>'hidden')
  );

  /**
  * overrides to standard field definitions for admin users
  * 
  * @see $_field_defs
  */
  static $_field_defs_admin_override = array(
    'supervisor_title'=>array('type'=>'text', 'size'=>5, 'maxsize'=>100, 'title'=>"Supervisor Title<br /><small>Mr, Dr, etc.</small>"),
    'supervisor_lastname'=>array('type'=>'text', 'size'=>20, 'maxsize'=>100, 'title'=>"Supervisor Last name")
  );

  /**
  * overrides to standard field definitions for supervisor users
  * 
  * @see $_field_defs
  */
  static $_field_defs_supervisor_override = array(
    'position'=>array('type'=>'text', 'size'=>30, 'maxsize'=>100, 'title'=>'Job Description','header'=>true, 'mandatory'=>true, 'readonly'=>'true'),
    'jobstart'=>array('type'=>'isodate', 'inputstyle'=>'popup', 'required'=>'true', 'title'=>'Job Start Date', 'readonly'=>'true'),
    'jobend'=>array('type'=>'isodate', 'inputstyle'=>'popup', 'title'=>'Job End Date', 'readonly'=>'true'),
  );

  /**
  * overrides to standard field definitions for student users
  * 
  * @see $_field_defs
  */
  static $_field_defs_student_override = array(
    'company_id'=>array('type'=>'lookup', 'header'=>true, 'object'=>'company', 'value'=>'name', 'title'=>'Company', 'var'=>'companies', 'readonly'=>'true'),
    'position'=>array('type'=>'text', 'size'=>30, 'maxsize'=>100, 'title'=>'Job Description','header'=>true, 'mandatory'=>true, 'readonly'=>'true'),
    'jobstart'=>array('type'=>'isodate', 'inputstyle'=>'popup', 'required'=>'true', 'title'=>'Job Start Date', 'readonly'=>'true'),
    'jobend'=>array('type'=>'isodate', 'inputstyle'=>'popup', 'title'=>'Job End Date', 'readonly'=>'true'),
  );

  /**
  * constructor that uses the default database for placement objects
  */ 
  function __construct() 
  {
    parent::__construct('default');
  }

  /**
  * returns the statically defined field definitions
  */
  function get_field_defs()
  {
    if(User::is_admin()) return(array_merge(self::$_field_defs, self::$_field_defs_admin_override));
    if(User::is_supervisor()) return(array_merge(self::$_field_defs, self::$_field_defs_supervisor_override));
    if(User::is_student()) return(array_merge(self::$_field_defs, self::$_field_defs_student_override));
    return(self::$_field_defs);
  }

  /**
  * load a given object by its id
  * 
  * @param int $id the unique id for the record
  * @return a placement object, check id is non-zero to indicate success
  */ 
  function load_by_id($id) 
  {
    $placement = new Placement;
    $placement->id = $id;
    $placement->_load_by_id();
    return $placement;
  }

  /**
  * inserts a new placement record in the database
  * 
  * This automatically marks the student as placed, sets the created timestamp
  * on the placement record, and attempts to see if sufficient data is available
  * and or changed to warrant creating a supervisor user or updating one.
  * 
  * @param array $fields an associative array of fields for the table
  * @see Supervisor::update_from_placement()
  */
  function insert($fields) 
  {
    global $waf;
    // Null some fields if empty
    $fields = Placement::set_empty_to_null($fields);

    require_once('model/Student.class.php');
    // Record student as placed
    $student_fields['placement_status'] = 'Placed';
    // inbound $student_id is the user id, not the id from the student table
    $student_fields['id'] = Student::get_id_from_user_id($fields['student_id']);
    $waf->log("updating student", PEAR_LOG_DEBUG, 'debug');
    Student::update($student_fields);

    // Now the main insertion
    $fields['created'] = date("YmdHis");
    $placement = new Placement;
    $id = $placement->_insert($fields);

    // See if the supervisor needs created / updated
    $waf->log("updating supervisor", PEAR_LOG_DEBUG, 'debug');    
    require_once("model/Supervisor.class.php");
    Supervisor::update_from_placement($id, $fields);
  }

  /**
  * updates a placement record in the database
  * 
  * This protects key fields from alteration, and updated the modified 
  * timestamp automatically. It also attempts to see if sufficient data is 
  * available and or changed to warrant creating a supervisor user or updating
  * one.
  * 
  * @param array $fields an associative array of fields for the table
  * @see Supervisor::update_from_placement()
  */
  function update($fields) 
  {
    // Some fields should never change
    unset($fields['created']);
    unset($fields['company_id']);
    unset($fields['student_id']);
    // Some fields cannot be reset by non admins
    if(!User::is_admin())
    {
      unset($fields['jobstart']);
      unset($fields['jobend']);
      unset($fields['position']);
    }
    if(User::is_student())
    {
      unset($fields['position']);
    }
    // Null some fields if empty
    $fields = Placement::set_empty_to_null($fields);
    $fields['modified'] = date("YmdHis");

    $placement = Placement::load_by_id($fields['id']);
    $placement->_update($fields);

    // See if the supervisor needs created / updated
    require_once("model/Supervisor.class.php");
    Supervisor::update_from_placement($fields['id'], $fields);
  }

  /**
  * Goes through certain fields and sets them to null if they are "empty"
  * 
  * @param array $fields an array of fieldnames to set to null
  */
  function set_empty_to_null($fields)
  {
    $set_to_null = array("created", "modified", "jobstart", "jobend");
    foreach($set_to_null as $field)
    {
      if(isset($fields[$field]) && !strlen($fields[$field])) $fields[$field] = null;
    }
    return($fields);
  }

  /**
  * checks whether a given placement record exists
  * 
  * @param int $id the unique id to check for
  * @return true if the record exists, false otherwise
  */
  function exists($id) 
  {
    $placement = new Placement;
    $placement->id = $id;
    return $placement->_exists();
  }

  /**
  * counts the number of placement records that meet search criteria
  * 
  * @param string $where_clause an optional clause to limit the searched records
  * @return the number of matching records
  */
  function count($where_clause="") 
  {
    $placement = new Placement;
    return $placement->_count($where_clause);
  }

  /**
  * obtains the most recent placement for a specific student, if one exists
  * 
  * @param int $student_user_id the id from the user table for the student
  * @return the placement object if found, false otherwise
  */
  function get_most_recent($student_user_id)
  {
    $student_user_id = (int) $student_user_id;
    $placement = new Placement;

    $placements = $placement->_get_all("where student_id=$student_user_id", "order by jobstart DESC", 0, 1);
    if(count($placements)) return($placements[0]);
    else return(false);
  }

  /**
  * get all placement records with certain criteria, with paging
  * 
  * @param string $where_clause the where clause for the query
  * @param string $order_by the ordering criteria (defaults to id)
  * @param page the page number (zero means initial search)
  * @return array of placement objects
  */
  function get_all($where_clause="", $order_by="ORDER BY id", $page=0)
  {
    global $config;
    $placement = new Placement;

    if ($page <> 0) {
      $start = ($page-1)*$config['opus']['rows_per_page'];
      $limit = $config['opus']['rows_per_page'];
      $placements = $placement->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $placements = $placement->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $placements;
  }

  /**
  * gets an array of the values of a given field, indexed by id
  * 
  * @param string $fieldname the field to fetch
  * @param string $where_clause an optional where clause to limit the search
  * @return an array of the field values indexed by the id field
  */
  function get_id_and_field($fieldname, $where_clause="") 
  {
    $placement = new Placement;
    $placement_array = $placement->_get_id_and_field($fieldname, $where_clause);
    unset($placement_array[0]);
    return $placement_array;
  }

  /**
  * removes a given placement record
  * 
  * @param int $id the unique id (defaults to zero for safety)
  */ 
  function remove($id=0)
  {
    $placement = new Placement;
    $placement->_remove_where("WHERE id=$id");
  }

  /**
  * returns all the fieldnames in use
  * 
  * @param boolean $include_id include the id field in the list
  * @return an array of fieldnames
  */ 
  function get_fields($include_id = false) 
  {
    $placement = new Placement;
    return  $placement->_get_fieldnames($include_id);
  }

  /**
  * obtains all the fields from the $_REQUEST array
  * 
  * Some of these should be carefully dismissed in the relevant insert and
  * update functions.
  * 
  * @param boolean $include_id include the id field in the list
  * @return an associative array of field, value type
  */ 
  function request_field_values($include_id = false)
  {
    $fieldnames = Placement::get_fields($include_id);
    $nvp_array = array();

    foreach ($fieldnames as $fn)
    {
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
    }
    return $nvp_array;
  }
}
?>
