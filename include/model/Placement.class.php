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

  static $_field_defs = array(
    'position'=>array('type'=>'text', 'size'=>30, 'maxsize'=>100, 'title'=>'Job Description','header'=>true, 'mandatory'=>true),
    'jobstart'=>array('type'=>'isodate', 'inputstyle'=>'popup', 'required'=>'true', 'title'=>'Job Start Date'),
    'jobend'=>array('type'=>'isodate', 'inputstyle'=>'popup', 'title'=>'Job End Date'),
    'salary'=>array('type'=>'text', 'size'=>6, 'maxsize'=>20),
    'email'=>array('type'=>'email', 'size'=>20, 'maxsize'=>100, 'title'=>'Placement Email'),
    'supervisor_title'=>array('type'=>'text', 'size'=>5, 'maxsize'=>100, 'title'=>"Supervisor Title<br /><small>Mr, Dr, etc.</small>", 'mandatory'=>true),
    'supervisor_firstname'=>array('type'=>'text', 'size'=>20, 'maxsize'=>100, 'title'=>"Supervisor First name"),
    'supervisor_lastname'=>array('type'=>'text', 'size'=>20, 'maxsize'=>100, 'title'=>"Supervisor Last name", 'mandatory'=>true),
    'supervisor_email'=>array('type'=>'email', 'size'=>40, 'maxsize'=>100, 'title'=>"Supervisor Email"),
    'supervisor_voice'=>array('type'=>'text', 'size'=>20, 'maxsize'=>100, 'title'=>"Supervisor Phone"),
    'company_id'=>array('type'=>'hidden'),
    'vacancy_id'=>array('type'=>'hidden'),
  );

  static $_field_defs_admin_override = array(
    'supervisor_title'=>array('type'=>'text', 'size'=>5, 'maxsize'=>100, 'title'=>"Supervisor Title<br /><small>Mr, Dr, etc.</small>"),
    'supervisor_lastname'=>array('type'=>'text', 'size'=>20, 'maxsize'=>100, 'title'=>"Supervisor Last name")
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
    if(User::is_admin()) return(array_merge(self::$_field_defs, self::$_field_defs_admin_override));
    return(self::$_field_defs);
  }


  function load_by_id($id) 
  {
    $placement = new Placement;
    $placement->id = $id;
    $placement->_load_by_id();
    return $placement;
  }

  function insert($fields) 
  {
    // Null some fields if empty
    $fields = Placement::set_empty_to_null($fields);

    require_once('model/Student.class.php');
    // Record student as placed
    $student_fields['placement_status'] = 'Placed';
    $student_fields['id'] = $fields['student_id'];
    Student::update($student_fields);

    // Inbound student_id is not from user table
    $fields['student_id'] = Student::get_user_id($fields['student_id']);
    $fields['created'] = date("YmdHis");
    $placement = new Placement;
    $id = $placement->_insert($fields);

    // See if the supervisor needs created / updated
    require_once("model/Supervisor.class.php");
    Supervisor::update_from_placement($id, $fields);
  }

  function update($fields) 
  {
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
  */
  function set_empty_to_null($fields)
  {
    $set_to_null = array("created", "modified", "jobstart");
    foreach($set_to_null as $field)
    {
      if(!strlen($fields[$field])) $fields[$field] = null;
    }
    return($fields);
  }

  /**
  * Wasteful
  */
  function exists($id) 
  {
    $placement = new Placement;
    $placement->id = $id;
    return $placement->_exists();
  }

  /**
  * Wasteful
  */
  function count($where_clause="") 
  {
    $placement = new Placement;
    return $placement->_count($where_clause);
  }

  function get_most_recent($student_user_id)
  {
    $student_user_id = (int) $student_user_id;
    $placement = new Placement;

    $placements = $placement->_get_all("where student_id=$student_user_id", "order by jobstart DESC", 0, 1);
    if(count($placements)) return($placements[0]);
    else return(false);
  }

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

  function get_id_and_field($fieldname, $where_clause="") 
  {
    $placement = new Placement;
    $placement_array = $placement->_get_id_and_field($fieldname, $where_clause);
    unset($placement_array[0]);
    return $placement_array;
  }


  function remove($id=0)
  {
    $placement = new Placement;
    $placement->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {
    $placement = new Placement;
    return  $placement->_get_fieldnames($include_id);
  }

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