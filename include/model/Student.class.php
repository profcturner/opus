
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

  // Not much here yet...
  static $_field_defs = array
  (
    'placementyear'=>array('type'=>'text','size'=>15, 'header'=>true)
  );

  function __construct() 
  {
    parent::__construct('default');
  }

  function get_field_defs()
  {
    return self::$_field_defs;
  }

  function load_by_id($id) 
  {
     $student = new Student;
     $student->id = $id;
     $student->_load_by_id();
     return $student;
  }


  function insert($fields) 
  {
    $student = new Student;
    return $student->_insert($fields);
  }

  function update($fields) 
  {
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
}

?>