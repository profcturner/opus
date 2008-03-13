<?php

/**
* Defines the extra fields for academic staff
* @package OPUS
*/
require_once("dto/DTO_Staff.class.php");
/**
* Defines the extra fields for academic staff
*
* This melds data from the staff and user tables together
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see User.class.php
* @package OPUS
*
*/

class Staff extends DTO_Staff 
{
  var $position;          // position in company
  var $voice;             // Phone number
  var $room;              // Room number
  var $postcode;          // Post code to use in mapping
  var $address;           // Full address
  var $status;            // Archive, or not?
  var $school_id;         // School the member of staff belongs to
  var $user_id;           // Matches id from user table

  // Several of these fields actually reside in the User table
  static $_field_defs = array
  (
    'salutation'=>array('type'=>'text', 'size'=>20, 'header'=>true, 'title'=>'Title', 'mandatory'=>true),
    'firstname'=>array('type'=>'text','size'=>30, 'header'=>true, 'mandatory'=>true),
    'lastname'=>array('type'=>'text','size'=>30, 'header'=>true, 'mandatory'=>true),
    'school_id'=>array('type'=>'lookup', 'object'=>'school', 'value'=>'name', 'title'=>'School', 'size'=>20, 'var'=>'schools'),
    'position'=>array('type'=>'text','size'=>50,'header'=>true),
    'email'=>array('type'=>'email','size'=>40, 'header'=>true, 'mandatory'=>true),
    'voice'=>array('type'=>'text','size'=>40),
    'room'=>array('type'=>'text', 'size'=>10, 'header'=>true),
    'address'=>array('type'=>'textarea', 'rowsize'=>6, 'colsize'=>40),
    'postcode'=>array('type'=>'text', 'size'=>10),
    'status'=>array('type'=>'list', 'list'=>array('active', 'archive'))
  );

  static $_root_extra_defs = array
  (
    'reg_number'=>array('type'=>'text', size=>'20')
  );

  // This defines which ones
  static $_extended_fields = array
  (
    'salutation','firstname','lastname','email'
  );

  static $_root_extra_extended = array
  (
    'reg_number'
  );

  function __construct() 
  {
    parent::__construct();
  }

  function get_field_defs()
  {
    if(!User::is_root()) return self::$_field_defs;
    else return array_merge(self::$_field_defs, self::$_root_extra_defs);
  }

  function get_extended_fields()
  {
    if(!User::is_root()) return self::$_extended_fields;
    else return array_merge(self::$_extended_fields, self::$_root_extra_extended);
  }

  function load_by_id($id) 
  {
     $staff = new Staff;
     $staff->id = $id;
     $staff->_load_by_id();
     return $staff;
  }

  function load_by_user_id($user_id) 
  {
     $staff = new Staff;
     $staff->user_id = $user_id;
     $staff->_load_by_user_id($user_id);
     return $staff;
  }

  /**
  * inserts data about a new staff to the User and Staff tables
  *
  * this is more sophisticated that usual because there are two tables.
  */
  function insert($fields) 
  {
    require_once("model/User.class.php");

    $staff = new Staff;
    $extended_fields = Staff::get_extended_fields();
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
    // potential security issue
    if(!User::is_root()) unset($user_fields['reg_number']);

    // Insert user data first, adding anything else we need
    $user_fields['user_type'] = 'staff';
    $user_id = User::insert($user_fields);

    // Now we know the user_id, to populate the other tables
    $fields['user_id'] = $user_id;

    // We want to email them, if possible
    if($user_fields['email'])
    {
      require_once("model/Automail.class.php");

    }

    return $staff->_insert($fields);
  }

  function update($fields) 
  {
    global $waf;
    // We have a potential security problem here, we should check id and user_id are really linked.
    $staff = Staff::load_by_id($fields['id']);
    if($staff->user_id != $fields['user_id'])
    {
      $waf->security_log("attempt to update staff with mismatching user_id fields");
      $waf->halt("error:staff:user_id_mismatch");
    }

    $extended_fields = Staff::get_extended_fields();
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
    if(!User::is_root()) unset($user_fields['reg_number']);
    // Insert user data first, adding anything else we need
    $user_fields['id'] = $fields['user_id'];
    User::update($user_fields);

    $staff = Staff::load_by_id($fields[id]);
    $staff->_update($fields);
  }

  function exists($id) 
  {
    $staff = new Staff;
    $staff->id = $id;
    return $staff->_exists();
  }

  function count($where="") 
  {
    $staff = new Staff;
    return $staff->_count($where);
  }

  function get_all($where_clause="", $order_by="", $page=0, $end=0) 
  {
    global $config;
    $staff = new Staff;

    if($end != 0) return($staff->_get_all($where_clause, $order_by, $page, $end));
    if ($page <> 0) 
    {
        $start = ($page-1)*$config['opus']['rows_per_page'];
        $limit = $config['opus']['rows_per_page'];
        $staffs = $staff->_get_all($where_clause, $order_by, $start, $limit);
    }
    else 
    {
        $staffs = $staff->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $staffs;
  }

  function get_id_and_field($fieldname, $where_clause="") 
  {
    $staffs = new Staff;
    return  $staffs->_get_id_and_field($fieldname, $where_clause);
  }

  function get_fields($include_id = false) 
  {
    $staff = new Staff;
    return  $staff->_get_fieldnames($include_id);
  }

  function request_field_values($include_id = false) 
  {
    $fieldnames = Staff::get_fields($include_id);
    $fieldnames = array_merge($fieldnames, Staff::get_extended_fields());

    $nvp_array = array();
    foreach ($fieldnames as $fn) 
    {
        $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
    }
    return $nvp_array;
  }

  function remove($id=0) 
  {
    require_once("model/User.class.php");

    $staff = new Staff;
    $staff->load_by_id($id);
    // Remove the user object also
    User::remove($staff->user_id);
    $staff->_remove_where("WHERE id=$id");
  }

  /**
  * provides information for the tutor lookup in the student dialog
  */
  function lookup_tutors_by_school()
  {
    $student_id = WA::request('id');
    require_once("model/Student.class.php");
    $student = Student::load_by_id($student_id);
    $programme_id = Student::get_programme_id($student->user_id);
    require_once("model/Programme.class.php");
    $school_id = Programme::get_school_id($programme_id);

    $staff = Staff::get_all("where school_id=$school_id", "order by lastname");
    if(!count($staff)) $staff = array();

    $results = array();

    // Get the tutors from the school
    $objects = array();
    $objects[0] = "no tutor is selected";
    foreach($staff as $staff_member)
    {
      $objects[$staff_member->user_id] = $staff_member->real_name;
    }
    $results['This School'] = $objects;

    // Others
    $staff = Staff::get_all("where school_id != $school_id", "order by lastname");
    if(!count($staff)) $staff = array();
    $objects = array();
    foreach($staff as $staff_member)
    {
      $objects[$staff_member->user_id] = $staff_member->real_name;
    }
    $results['Other Schools'] = $objects;

    return($results);
  }

  function get_name($id)
  {
    return(User::get_name(Staff::get_user_id($id)));
  }

  function get_user_id($id)
  {
    $id = (int) $id; // Security
    $staff = new Staff;

    return($staff->_get_fields("user_id", "where id='$id'"));
  }

  function get_school_id($user_id)
  {
    $user_id = (int) $user_id; // Security
    $staff = new Staff;

    return($staff->_get_fields("school_id","where user_id='$user_id'"));
  }
}

?>