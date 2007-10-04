<?php
/**
 * @package PDSystem
 *
 * reg_number -> username
 * user_id -> id
 * last few fields have gone
 */

require_once("dto/DTO_Admin.class.php");

class Admin extends DTO_Admin 
{
  var $position;          // position in company
  var $voice;             // Phone number
  var $fax;               // Fax number
  var $address;           // Full Address
  var $signature;         // Signature to use in emails (not supported yet)
  var $help_directory;    // Show in help directory?
  var $status;            // Archive or not?
  var $policy_id;         // The default policy for this user
  var $user_id;           // Matches id from user table

  // Several of these fields actually reside in the User table
  static $_field_defs = array
  (
    'salutation'=>array('type'=>'text', 'size'=>20, 'header'=>true, 'title'=>'Title'),
    'firstname'=>array('type'=>'text','size'=>30, 'header'=>true),
    'lastname'=>array('type'=>'text','size'=>30, 'header'=>true),
    'position'=>array('type'=>'text','size'=>50,'header'=>true),
    'policy_id'=>array('type'=>'lookup', 'object'=>'policy', 'value'=>'name', 'title'=>'Policy', 'var'=>'policies', 'header'=>true),
    'email'=>array('type'=>'email','size'=>40, 'header'=>true),
    'voice'=>array('type'=>'text','size'=>40),
    'fax'=>array('type'=>'text','size'=>40),
    'address'=>array('type'=>'textarea', 'rowsize'=>6, 'colsize'=>40),
    'signature'=>array('type'=>'textarea', 'rowsize'=>6, 'colsize'=>40),
    'help_directory'=>array('type'=>'list', 'list'=>array('yes', 'no')),
    'status'=>array('type'=>'list', 'list'=>array('active', 'archive'))
  );

  // This defines which ones
  static $_extended_fields = array
  (
    'salutation','firstname','lastname','email','username','password'
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
     $admin = new Admin;
     $admin->id = $id;
     $admin->_load_by_id();
     return $admin;
  }

  function load_by_user_id($user_id) 
  {
     $admin = new Admin;
     $admin->user_id = $user_id;
     $admin->_load_by_user_id($user_id);
     return $admin;
  }

  /**
  * inserts data about a new admin to the User and Admin tables
  *
  * this is more sophisticated that usual because there are two tables.
  */
  function insert($fields) 
  {
    require_once("model/User.class.php");

    $admin = new Admin;
    $extended_fields = Admin::get_extended_fields();
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

    $user_fields['user_type'] = 'admin';
    $user_id = User::insert($user_fields);

    // Now we know the user_id, to populate the other tables
    $fields['user_id'] = $user_id;

    // We want to email them, if possible
    if($user_fields['email'])
    {
      require_once("model/Automail.class.php");

    }

    return $admin->_insert($fields);
  }

  function update($fields) 
  {
    global $waf;
    // We have a potential security problem here, we should check id and user_id are really linked.
    $admin = Admin::load_by_id($fields['id']);
    if($admin->user_id != $fields['user_id'])
    {
      $waf->security_log("attempt to update admin with mismatching user_id fields");
      $waf->halt("error:admin:user_id_mismatch");
    }

    $extended_fields = Admin::get_extended_fields();
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

    $admin = Admin::load_by_id($fields[id]);
    $admin->_update($fields);
  }

  function exists($id) 
  {
    $admin = new Admin;
    $admin->id = $id;
    return $admin->_exists();
  }

  function count($where="") 
  {
    $admin = new Admin;
    return $admin->_count($where);
  }

  function get_all($where_clause="", $order_by="", $page=0, $end=0) 
  {
    $admin = new Admin;

    if($end != 0) return($admin->_get_all($where_clause, $order_by, $page, $end));
    if ($page <> 0) 
    {
        $start = ($page-1)*ROWS_PER_PAGE;
        $limit = ROWS_PER_PAGE;
        $admins = $admin->_get_all($where_clause, $order_by, $start, $limit);
    }
    else 
    {
        $admins = $admin->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $admins;
  }

  function get_all_by_faculty($faculty_id)
  {
    $admin = new Admin;
    return $admin->_get_all_by_faculty($faculty_id);
  }

  function get_all_by_school($school_id)
  {
    $admin = new Admin;
    return $admin->_get_all_by_school($school_id);
  }

  function get_all_by_programme($programme_id)
  {
    $admin = new Admin;
    return $admin->_get_all_by_programme($programme_id);
  }

  function get_id_and_field($fieldname) 
  {
    $admins = new Admin;
    return  $admins->_get_id_and_field($fieldname);
  }

  function get_fields($include_id = false) 
  {
    $admin = new Admin;
    return  $admin->_get_fieldnames($include_id);
  }

  function request_field_values($include_id = false) 
  {
    $fieldnames = Admin::get_fields($include_id);
    $fieldnames = array_merge($fieldnames, Admin::get_extended_fields());

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

    $admin = new Admin;
    $admin->load_by_id($id);
    // Remove the user object also
    User::remove($admin->user_id);
    $admin->_remove_where("WHERE id=$id");
  }

  function get_user_id_and_name($where_clause="")
  {
    $admin = new Admin;
    return($admin->_get_user_id_and_name($where_clause));
  }
}

?>