
<?php
/**
 * @package PDSystem
 *
 * reg_number -> studentname
 * student_id -> id
 * last few fields have gone
 */

require_once("dto/DTO_Student.class.php");

Class Student extends DTO_Student 
{
  var $placement_year   = ""; 
  var $placement_status = "";
  var $programme_id     = ""; // id of programme
  var $user_id          = ""; // Id of user from the user table
  var $progress         = ""; // whether the student has signed off on the disclaimer and other issues
  var $disability_code  = "";

  // Several of these fields actually reside in the User table
  static $_field_defs = array
  (
    'salutation'=>array('type'=>'text', 'size'=>20, 'header'=>true, 'title'=>'Title'),
    'firstname'=>array('type'=>'text','size'=>30, 'header'=>true),
    'lastname'=>array('type'=>'text','size'=>30, 'header'=>true),
    'email'=>array('type'=>'email','size'=>40),
    'progress'=>array('type'=>'list', 'list'=>array()),
    'placementyear'=>array('type'=>'text','size'=>5),
    'placement_status'=>array('type'=>'list', 'list'=>array('Required','Placed','Exempt Applied','Exempt Given','No Info','Left Course','Suspended','To final year','Not Eligible')),
    'programme_id'=>array('type'=>'lookup', 'object'=>'programme', 'value'=>'name', 'title'=>'programme', 'var'=>'programmes', 'lookup_function'=>'get_id_and_description')
  );

  // This defines which ones
  static $_extended_fields = array
  (
    'salutation','firstname','lastname','email'
  );

  function __construct() 
  {
    parent::__construct('default');
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
     $student = new Student;
     $student->id = $id;
     $student->_load_by_id();
     return $student;
  }


  /**
  * inserts data about a new student to the User and Student tables
  *
  * this is more sophisticated that usual because there are two tables.
  */
  function insert($fields) 
  {
    require_once("model/User.class.php");

    $student = new Student;
    $extended_fields = Student::get_extended_fields();
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
    $user_fields['user_type'] = 'student';
    $user_id = User::insert($user_fields);

    // Now we know the user_id, to populate the other tables
    $fields['user_id'] = $user_id;

    return $student->_insert($fields);
  }

  function update($fields) 
  {
    global $waf;
    // We have a potential security problem here, we should check id and user_id are really linked.
    $student = Student::load_by_id($fields['id']);
    if($student->user_id != $fields['user_id'])
    {
      $waf->security_log("attempt to update student with mismatching user_id fields");
      $waf->halt("error:student:user_id_mismatch");
    }

    $extended_fields = Student::get_extended_fields();
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

    $student = Student::load_by_id($fields[id]);
    $student->_update($fields);
  }

  function exists($id) 
  {
    $student = new Student;
    $student->id = $id;
    return $student->_exists();
  }

  function count($where="") 
  {
    $student = new Student;
    return $student->_count($where);
  }

  function get_all($where_clause="", $order_by="ORDER BY lastname", $page=0) 
  {
    $student = new Student;

    if ($page <> 0) 
    {
        $start = ($page-1)*ROWS_PER_PAGE;
        $limit = ROWS_PER_PAGE;
        $students = $student->_get_all($where_clause, $order_by, $start, $limit);
    }
    else 
    {
        $students = $student->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $students;
  }

  function get_id_and_field($fieldname) 
  {
    $students = new Student;
    return  $students->_get_id_and_field($fieldname);
  }

  function get_fields($include_id = false) 
  {
    $student = new Student;
    return  $student->get_fieldnames($include_id);
  }

  function request_field_values($include_id = false) 
  {
    $fieldnames = Student::get_fields($include_id);
    $fieldnames = array_merge($fieldnames, Student::get_extended_fields());

    $nvp_array = array();
    foreach ($fieldnames as $fn) 
    {
        $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
    }
    return $nvp_array;
  }

  function remove($id=0) 
  {
    $student = new Student;
    $student->_remove_where("WHERE id=$id");
  }

  function get_all_extended($search, $year, $programmes, $sort, $other_options)
  {
    $student = new Student;
    return($student->_get_all_extended($search, $year, $programmes, $sort, $other_options));
  }

  function get_all_by_initial($initial)
  {
    $student = new Student;
    return($student->_get_all_by_initial($initial));
  }
}

?>