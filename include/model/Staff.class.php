<?php
/**
 * @package PDSystem
 *
 * reg_number -> username
 * user_id -> id
 * last few fields have gone
 */

require_once("dto/DTO_Staff.class.php");

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
    'salutation'=>array('type'=>'text', 'size'=>20, 'header'=>true, 'title'=>'Title'),
    'firstname'=>array('type'=>'text','size'=>30, 'header'=>true),
    'lastname'=>array('type'=>'text','size'=>30, 'header'=>true),
    'school_id'=>array('type'=>'lookup', 'object'=>'school', 'value'=>'name', 'title'=>'School', 'size'=>20, 'var'=>'schools'),
    'position'=>array('type'=>'text','size'=>50,'header'=>true),
    'email'=>array('type'=>'email','size'=>40, 'header'=>true),
    'voice'=>array('type'=>'text','size'=>40),
    'room'=>array('type'=>'text', 'size'=>10, 'header'=>true),
    'address'=>array('type'=>'textarea', 'rowsize'=>6, 'colsize'=>40),
    'postcode'=>array('type'=>'text', 'size'=>10),
    'status'=>array('type'=>'list', 'list'=>array('active', 'archive'))
  );

  // This defines which ones
  static $_extended_fields = array
  (
    'salutation','firstname','lastname','email'
  );

  function __construct() 
  {
    parent::__construct();
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
    $staff = new Staff;

    if($end != 0) return($staff->_get_all($where_clause, $order_by, $page, $end));
    if ($page <> 0) 
    {
        $start = ($page-1)*ROWS_PER_PAGE;
        $limit = ROWS_PER_PAGE;
        $staffs = $staff->_get_all($where_clause, $order_by, $start, $limit);
    }
    else 
    {
        $staffs = $staff->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $staffs;
  }

  function get_id_and_field($fieldname) 
  {
    $staffs = new Staff;
    return  $staffs->_get_id_and_field($fieldname);
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

}

?>