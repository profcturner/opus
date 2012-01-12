<?php
/**
 * @package PDSystem
 *
 */

require_once("dto/DTO_Student_Detail.class.php");

Class Student_Detail extends DTO_Student_Detail 
{
  var $user_id = 0;
	var $reg_number = '';
  var $title = '';
  var $first_name = '';
  var $middle_names = '';
  var $last_name = '';
  var $dob = '';
  var $mobile = '';
  var $email_uni = '';
  var $email_alt = '';
  var $home_house_name_number = '';
  var $home_street = '';
  var $home_town = '';
  var $home_county = '';
  var $home_postcode = '';
  var $home_country = '';
  var $home_telephone = '';
  var $term_house_name_number = '';
  var $term_street = '';
  var $term_town = '';
  var $term_county = '';
  var $term_postcode = '';
  var $term_country = '';
  var $term_telephone = '';
  var $personal_web_address = '';
  var $driving_licence = '';
  var $programme_instance_code = '';  // course or programme instance code may be the same as the course code

  static $_field_defs = array
  ( 
    'title'=>array
    (
      'type'=>'list', 'list'=>array('Mr'=>'Mr', 'Miss'=>'Miss', 'Mrs'=>'Mrs', 'Ms'=>'Ms', 'Dr'=>'Dr', 'Prof'=>'Prof', 'Father'=>'Father', 'Rev'=>'Rev', 'Captain'=>'Captain'), 'header'=>true
    ),
    'first_name'=>array
    (
      'type'=>'text', 'size'=>20, 'header'=>true
    ),
    'middle_names'=>array
    (
      'type'=>'text', size=>30, 'header'=>true
    ),
    'last_name'=>array
    (
      'type'=>'text', 'size'=>20, 'header'=>true
    ),
    'dob'=>array
    (
      'type'=>'date', 'year_start'=>"1900", 'year_end'=>"2000", 'header'=>true
    ),
    'mobile'=>array
    (
      'type'=>'text', 'size'=>30
    ),
    'email_uni'=>array
    (
      'type'=>'email', 'header'=>true
    ),
    'email_alt'=>array
    (
      'type'=>'email'
    ),
    'home_house_name_number'=>array
    (
      'type'=>'text', 'size'=>20
    ),
    'home_street'=>array
    (
      'type'=>'text', 'size'=>30
    ),
    'home_town'=>array
    (
      'type'=>'text', 'size'=>20
    ),
    'home_county'=>array
    (
      'type'=>'text', 'size'=>20
    ),
    'home_postcode'=>array
    (
      'type'=>'postcode'
    ),
    'home_country'=>array
    (
      'type'=>'text', 'size'=>20
    ),
    'home_telephone'=>array
    (
      'type'=>'text', 'size'=>20
    ),
    'term_house_name_number'=>array
    (
      'type'=>'text', 'size'=>20
    ),
    'term_street'=>array
    (
      'type'=>'text', 'size'=>30
    ),
    'term_town'=>array
    (
      'type'=>'text', 'size'=>20
    ),
    'term_county'=>array
    (
      'type'=>'text', 'size'=>20
    ),
    'term_postcode'=>array
    (
      'type'=>'postcode'
    ),
    'term_country'=>array
    (
      'type'=>'text', 'size'=>20
    ),
    'term_telephone'=>array
    (
      'type'=>'text', 'size'=>20
    ),
    'personal_web_address'=>array
    (
      'type'=>'url', 'size'=>50
    ),
    'driving_licence'=>array
    (
      'type'=>'list', 'list'=>array('no'=>'no', 'yes'=>'yes')
    ),
    'programme_instance_code'=>array
    (
      'type'=>'text', 'hidden'=>true, 'size'=>200
    )
  );

  function __construct() 
  {
    parent::__construct();
  }

  function get_field_defs()
  {
    return self::$_field_defs;
  }

  function load_by_id($id, $parse = False) 
  {
    $student_detail = new Student_Detail;
    $student_detail->id = $id;
    $student_detail->_load_by_id($parse);
    return $student_detail;
  }

  function load_by_user_id($user_id) 
  {
    $student_detail = new Student_Detail;
    $student_detail->user_id = $user_id;
    $student_detail->_load_by_field('user_id');
    return $student_detail;
  }

  function load_by_reg_number($reg_number) 
  {
    $student_detail = new Student_Detail;
    $student_detail->reg_number = $reg_number;
    $student_detail->_load_by_field("reg_number");
   
    return $student_detail;
  }

  function initialise($fields)
  {
    require_once('model/Programme_Detail.class.php');
    if (is_array($fields) )
    {
      Student_Detail::insert($fields);
      Programme_Detail::load_by_programme_code($fields['programme_instance_code']);
    }
  }

  function initialise_student($reg_number, $user_id)
  {
    require_once('model/Web_Service.class.php');
     $waf =& UUWAF::get_instance($config['waf']);
   
    // first check if the web_service can find some info
    
    $student_info = Web_Service::get_student_details($reg_number);
    
    // second set up some default values based on the parameters above
    
    $student_info['user_id'] = $user_id;
    $student_info['reg_number'] = $reg_number;
    
    // now fill in important blanks
  
    if (empty($student_info['first_name'])) $student_info['first_name'] = $waf->user['firstname'];
    if (empty($student_info['last_name'])) $student_info['last_name'] = $waf->user['lastname'];
    if (empty($student_info['email_uni'])) $student_info['email_uni'] = $waf->user['email'];
    
    
    log_to_debug("initialising student details for: ".$student_info['first_name']." ".$student_info['last_name']);

    // this is no longer necessary as it is sorted above
        
//     if (strlen($student_info['email_uni']) == 0) 
//     {
//       $student_user = User::load_by_id($user_id);
//       $student_info['email_uni'] = $student_user->email;
//     }
    
    Student_Detail::insert($student_info);
   
  }

  function initialise_academic($reg_number, $user_id) // initialises a student acount for an academic
  {
		require_once('model/Web_Service.class.php');
     $waf =& UUWAF::get_instance($config['waf']);

    $student_info = Web_Service::get_academic_student_details($reg_number);

    $student_info['user_id'] = $user_id;
    $student_info['reg_number'] = $reg_number;
    if (empty($student_info['email_uni'])) $student_info['email_uni'] = $waf->user['email'];

//     if (strlen($student_info['email_uni']) == 0) 
//     {
//       $student_user = User::load_by_id($user_id);
//       $student_info['email_uni'] = $student_user->email;
//     }
    
    Student_Detail::insert($student_info);

  }

  function insert($fields) 
  {
    $student_detail = new Student_Detail;
    $fields['dob'] = date_reverse_h2mysql($fields['dob']);
    $student_detail->_insert($fields);
  }
  
  // fixme - need to check if the DOB is being reset by this process now.
  function update($fields) 
  {
    $student_detail = Student_Detail::load_by_id($fields['id']);
    if (strlen($fields['dob']) > 0) $fields['dob'] = date_reverse_h2mysql($fields['dob']);
    $student_detail->_update($fields);
  }
  
  function update_programme_instance_code($reg_number)
  {
    require_once('model/Web_Service.class.php');
     $waf =& UUWAF::get_instance($config['waf']);

    $student_info = Web_Service::get_student_details($reg_number);
    
    $student_detail = Student_Detail::load_by_reg_number($reg_number);
    
    if ($student_detail->programme_instance_code != $student_info['programme_instance_code'])
    {
      $details_array = array('id'=>$student_detail->id, 'programme_instance_code'=>$student_info['programme_instance_code']);
      log_to_debug("updating programme_instance_code: student_detail.id:".$student_detail->id." programme_instance_code: ".$student_info['programme_instance_code']);
      Student_Detail::update($details_array);
    }
  }
  
  function exists($id) 
  {
    $student_detail = new Student_Detail;
    $student_detail->id = $id;
    return $student_detail->_exists();
  }
  
  function count($where_clause = "") 
  {
    $student_detail = new Student_Detail;
    return $student_detail->_count($where_clause);
  }

  function get_all($where_clause="", $order_by="ORDER BY id", $page = 0, $parse = False)
  {
    $student_detail = new Student_Detail;
    
    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $student_details = $student_detail->_get_all($where_clause, $order_by, $start, $limit, $parse);
    } else {
      $student_details = $student_detail->_get_all($where_clause, $order_by, 0, 1000, $parse);
    }
    return $student_details;
  }

  function get_id_and_field($fieldname) 
  {
    $student_detail = new Student_Detail;
    return  $student_detail->_get_id_and_field($fieldname);
  }


  function remove($id=0) 
  {  
    $student_detail = new Student_Detail;
    $student_detail->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {  
    $student_detail = new Student_Detail;
    return  $student_detail->_get_fieldnames($include_id); 
  }
  function request_field_values($include_id = false) 
  {
    $fieldnames = Student_Detail::get_fields($include_id);
    $nvp_array = array();
 
    foreach ($fieldnames as $fn) {
 
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
 
    }

    $day = WA::request('Day');
    $month = WA::request('Month');
    $year = WA::request('Year');
    
    $nvp_array['dob'] = $day."-".$month."-".$year;
    
    return $nvp_array;

  }

  
}  

?>
