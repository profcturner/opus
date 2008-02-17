<?php
/**
* Encapulates extra data that administrators have as well as user data
* @package OPUS
*/
require_once("dto/DTO_Admin.class.php");
/**
* Encapulates extra data that administrators have as well as user data
*
* The administrator user has significant extra data to handle above and beyond that
* defined in the User class. This class contains that data and the handling and allows
* transparent loading and handling of the composite user objects.
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see User.class.php
* @package OPUS
*
*/
class Admin extends DTO_Admin
{
  /** @var string position in company */
  var $position;
  /** @var string Phone number */
  var $voice;
  /** @var string Fax number */
  var $fax;
  /** @var string Full address */
  var $address;
  /** @var string Signature to use in emails (not supported yet) */
  var $signature;
  /** @var string (yes / no) value as to whether to show in help directory */
  var $help_directory;
  /** @var string status (active / archive) where the administrator is active */
  var $status;
  /** @var string (yes / no) value as to whether the administrators has rights as the institutoinal level */
  var $inst_admin;
  /** @var int the default policy id for this user, NULL means no policy */
  var $policy_id;
  /**
  * @var int matches the id from the User table 
  * @see User.class.php
  */
  var $user_id;

  /**
  * @var static array of header fields, several of these fields actually reside in the User table
  * @see User.class.php
  */
  static $_field_defs = array
  (
    'salutation'=>array('type'=>'text', 'size'=>20, 'header'=>true, 'title'=>'Title', 'mandatory'=>true),
    'firstname'=>array('type'=>'text','size'=>30, 'header'=>true, 'mandatory'=>true),
    'lastname'=>array('type'=>'text','size'=>30, 'header'=>true, 'mandatory'=>true),
    'position'=>array('type'=>'text','size'=>50,'header'=>true),
    'policy_id'=>array('type'=>'lookup', 'object'=>'policy', 'value'=>'name', 'title'=>'Policy', 'var'=>'policies', 'header'=>true),
    'inst_admin'=>array('type'=>'list', 'list'=>array('no', 'yes'), 'title'=>'Institutional Admin'),
    'email'=>array('type'=>'email','size'=>40, 'header'=>true, 'mandatory'=>true),
    'voice'=>array('type'=>'text','size'=>40),
    'fax'=>array('type'=>'text','size'=>40),
    'address'=>array('type'=>'textarea', 'rowsize'=>6, 'colsize'=>40),
    'signature'=>array('type'=>'textarea', 'rowsize'=>6, 'colsize'=>40),
    'help_directory'=>array('type'=>'list', 'list'=>array('yes'=>'Show', 'no'=>'Don\'t Show')),
    'status'=>array('type'=>'list', 'list'=>array('active', 'archive'))
  );

  static $_root_extra_defs = array
  (
    'reg_number'=>array('type'=>'text', size=>'20', 'mandatory'=>true)
  );

  /**
  * @var static array of which field_defs are stored elsewhere
  * @see $_field_defs
  * @see User.class.php
  */
  static $_extended_fields = array
  (
    'salutation','firstname','lastname','email'
  );

  static $_root_extra_extended = array
  (
    'reg_number'
  );

  /**
  * Model Constructor
  */
  function __construct() 
  {
    parent::__construct();
  }

  /**
  * returns header definitions
  * @see $_field_defs
  * @return an array as above
  */
  function get_field_defs()
  {
    if(!User::is_root()) return self::$_field_defs;
    else return array_merge(self::$_field_defs, self::$_root_extra_defs);
  }

  /**
  * returns header definitions pertaining to another class
  * @see $_extended_fields
  * @return an array as above
  */
  function get_extended_fields()
  {
    if(!User::is_root()) return self::$_extended_fields;
    else return array_merge(self::$_extended_fields, self::$_root_extra_extended);
  }

  /**
  * returns custom header definitions for administration directory
  * @see $_field_defs
  * @return an array as above
  */
  function get_admin_list_headings()
  {
    return array(
      'real_name'=>array('type'=>'text','size'=>30, 'header'=>true, title=>'Name'),
      'position'=>array('type'=>'list','size'=>30, 'header'=>true, title=>'Position'),
      'policy_id'=>array('type'=>'lookup', 'object'=>'policy', 'value'=>'name', 'title'=>'Policy', 'var'=>'policies', 'header'=>true),
      'last_time'=>array('type'=>'text', 'header'=>true, 'title'=>'Last Access'),
      'email'=>array('type'=>'email','size'=>40, 'header'=>true)
    );
  }

  /**
  * returns custom header definitions for root users in the administration directory
  * @see $_field_defs
  * @return an array as above
  */
  function get_root_list_headings()
  {
    return array(
      'real_name'=>array('type'=>'text','size'=>30, 'header'=>true, title=>'Name'),
      'position'=>array('type'=>'list','size'=>30, 'header'=>true, title=>'Position'),
      'last_time'=>array('type'=>'text', 'header'=>true, 'title'=>'Last Access'),
      'email'=>array('type'=>'email','size'=>40, 'header'=>true),
      'voice'=>array('type'=>'text','size'=>40, 'header'=>true, title=>'Phone')
    );
  }

  /**
  * loads an administrator user, include underlying data from the user table
  * @param int $id the id from the admin table
  * @return a composite object of admin and user data
  */
  function load_by_id($id) 
  {
     $admin = new Admin;
     $admin->id = $id;
     $admin->_load_by_id();
     return $admin;
  }

  /**
  * loads an administrator user, include underlying data from the user table using the user id
  * @param int $id the id from the user table
  * @return a composite object of admin and user data
  */
  function load_by_user_id($user_id) 
  {
     $admin = new Admin;
     $admin->user_id = $user_id;
     $admin->_load_by_user_id($user_id);
     return $admin;
  }

  /**
  * adds a new admin user, and emails them if possible
  *
  * This is more sophisticated than usual because there are two tables,
  * fields in the user object are automatically created there.
  *
  * @param array $fields an array of key value pairs for the object
  * @return the id from the admin table of the new object
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
    // Only root users should insert, but just in case...potential security issue
    if(!User::is_root()) unset($user_fields['reg_number']);

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

  /**
  * updates an admin user
  *
  * This is more sophisticated than usual because there are two tables,
  * fields in the user object are automatically updated there.
  *
  * @param array $fields an array of key value pairs to modify for the object
  */
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
    if(!User::is_root()) unset($user_fields['reg_number']);
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

  /**
  * gets all fields for admins for a large number of objects
  *
  * the underlying function joins the admin and user tables, so fields
  * from either can be used in the where_clause
  */
  function get_all($where_clause="", $order_by="", $page=0, $end=0) 
  {
    global $config;
    $admin = new Admin;

    if($end != 0) return($admin->_get_all($where_clause, $order_by, $page, $end));
    if ($page <> 0) 
    {
        $start = ($page-1)*$config['opus']['rows_per_page'];
        $limit = $config['opus']['rows_per_page'];
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

  function get_id_and_field($fieldname, $where_clause="") 
  {
    $admins = new Admin;
    return  $admins->_get_id_and_field($fieldname, $where_clause);
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

  function get_user_id($id)
  {
    $id = (int) $id; // Security
    $admin = new Admin;
    return($admin->_get_fields("user_id","where id='$id'"));
  }

  function get_user_id_and_name($where_clause="")
  {
    $admin = new Admin;
    return($admin->_get_user_id_and_name($where_clause));
  }
}

?>