<?php
/**
 * @package PDSystem
 *
 * reg_number -> username
 * user_id -> id
 * last few fields have gone
 */

require_once("pds/dto/DTO_User.class.php");

Class User extends DTO_User 
{
	var $username = ''; 
	var $password="";
	var $firstname = '';
	var $lastname = '';
	var $last_login_time = '';
	var $login_time = '';
	var $online = 'no';
	var $email = '';
	var $session = '';
	var $type = "";

  var $_field_defs = array
  (
    'username'=>array('type'=>'text','size'=>15, 'header'=>true),
    'password'=>array('type'=>'password','size'=>20, 'header'=>false),
    'firstname'=>array('type'=>'text','size'=>30, 'header'=>true),
    'lastname'=>array('type'=>'text','size'=>30, 'header'=>true),
    'last_login_time'=>array('type'=>'datetime','size'=>30, 'header'=>true), 
    'login_time'=>array('type'=>'datetime','size'=>30, 'header'=>true),
    'online'=>array('type'=>'list','values'=>array('no','yes'), 'header'=>true),
    'email'=>array('type'=>'email','size'=>40, 'header'=>true),
    'session'=>array('type'=>'text','size'=>100, 'header'=>false),
    'type'=>array('type'=>'list','values'=>array('student','staff','guest'), 'header'=>true)
  );

  function __construct($handle = 'default') 
  {
    parent::__construct($handle);

    global $logger;
    $logger->log("User construct called");
    $logger->log($this);
  }

   static function load_by_id($id) 
   {
      $user = new User;
      $user->id = $id;
      $user->_load_by_id();
      return $user;
   }
   
/**
 * @package PDSystem
 * @class User
 * @param array $fields
 */

   function insert($fields) 
   {
      $user = new User;     
      return $user->_insert($fields);
   }
   
   function update($fields) 
   {
      $user = User::load_by_id($fields[id]);
      $user->_update($fields);
   }
   
   function exists($id) 
   {
      $user = new User;
      $user->id = $id;
      return $user->_exists();
   }
   
   function count() 
   {
      $user = new User;
      return $user->_count();
   }

   function get_all($where_clause="", $order_by="ORDER BY lastname", $page=0) 
   {
      $user = new User;
      
      if ($page <> 0) 
      {
         $start = ($page-1)*ROWS_PER_PAGE;
         $limit = ROWS_PER_PAGE;
         $users = $user->_get_all($where_clause, $order_by, $start, $limit);
      } 
      else 
      {
         $users = $user->_get_all($where_clause, $order_by, 0, 1000);
      }
      return $users;
   }

   function get_id_and_field($fieldname) 
   {
      $users = new User;
      return  $users->_get_id_and_field($fieldname);
   }

   function get_fields($include_id = false) 
   {   
      $user = new User;
      return  $user->get_fieldnames($include_id);
   }

   function request_field_values($include_id = false) 
   {
      $fieldnames = User::get_fields($include_id);
      $nvp_array = array();
      foreach ($fieldnames as $fn) 
      {
         $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
      }
      return $nvp_array;
   }

   function remove($id=0) 
   {   
      $user = new User;
      $user->_remove_where("WHERE id=$id");
   }

  function load_by_reg_num($reg_num) 
  {
    	
  }

  function load_by_email($email) 
  {
    $email = trim($email);	
  }

	function get_all_students($order_by="ORDER BY lastname", $page=0) 
   {
      return User::get_all("type='student'", $order_by, $page);
	}
	
	function count_types($date="2000-01-01") {
	
		$total = User::count();
		$user_array = User::get_all("login_time > '$date'");
		
		$students = 0;
		$academics = 0;
		$demo_students = 0;
		$guests = 0;
		$others = 0;
		$total = 0;
		
		foreach($user_array as $user) 
      {
			if ($user->type == "guest") $guests++;
			elseif ($user->type == "academic") $academics++;
			elseif ($user->type == "demo_student") $demo_students++;
			elseif ($user->type == "student") $students++;
			else $others++;
			$total++;
		}
		
		return array($students,$academics,$demo_students,$guests,$others,$total);
	
	}
	
	function count_online() {
	
		DTO_User::update_status();
      $total = User::count();
      $user_array = User::get_all("online = 'yes'");
      
      $students = 0;
      $academics = 0;
      $demo_students = 0;
      $guests = 0;
      $others = 0;
      $total = 0;
      $total_online = 0;
      
      foreach($user_array as $user) 
      {
         if ($user->type == "guest") $guests++;
         elseif ($user->type == "academic") $academics++;
         elseif ($user->type == "demo_student") $demo_students++;
         elseif ($user->type == "student") $students++;
         else $others++;
         $total_online++;
      }
      
		return array($students,$academics,$demo_students,$guests,$others,$total_online, $total);
	}
	
	function set_online($reg_num, $status) {
	
		$con = new DB_Connection_PDP();
		$sql = "UPDATE user SET online='".$status."', session='".session_id()."' WHERE reg_number='".$reg_num."';";
		$con->query($sql);
		unset($con);
	
	}
	
	function set_student_programme($user_id, $programme, $school, $faculty, $location, $programme_year) {

		// also set type to student
		$con = new DB_Connection_PDP();
		$sql = "UPDATE user SET programme='".addslashes($programme)."', school='".addslashes($school)."', faculty='".addslashes($faculty)."', location='".addslashes($location)."', course_year='$programme_year', type='student' WHERE user_id='$user_id';";
		$con->query($sql);
		unset($con);

	}

	function set_academic_school($user_id, $school, $faculty, $location) {

		// also set type to academic

	}

	function update_status() {
	
		$users = DTO_User::get_all();
		$user = new DTO_User;
		
		for ($i=0; $i<count($users); $i++) {
			
			$user = $users[$i];
			
			if (strlen($user->session) > 0 and !file_exists(session_save_path()."/sess_".$user->session)) {
				
				$user->session = "";
				$user->online = "no";
				$user->update();
			}
		}
	}
	
	function ques_check($user_id) {
	
					$con = new DB_Connection_PDP();
								
		$sql = "SELECT user_id FROM questionnaire_student WHERE user_id='".$user_id."';";
		$sql2 = "SELECT user_id FROM questionnaire_staff WHERE user_id='".$user_id."';";
								
		$con->query($sql);
		if ($con->num_rows() > 0) {
			return false;
		}else{
			$con->query($sql2);
			if ($con->num_rows() > 0) {
				return false;
			}else{
				return true;
			}
		}
	}
	
	function get_photo_url($staff_code) {
				
		$params = array('staff_code'=>$staff_code);
	
		$serverpath = WEB_SERVICE_URL.'/controller_soap.php?username='.WS_USERNAME.'&password='.WS_PASSWORD.'&Debug=1';
	
		$client = new soapclient($serverpath);
	
		$get_photo_url = $client->call('get_photo',$params);
		
		return $get_photo_url;
	}	
	
	function get_staff_details($staff_code) {
				
		$params = array('staff_code'=>$staff_code);
	
		$serverpath = WEB_SERVICE_URL.'/controller_soap.php?username='.WS_USERNAME.'&password='.WS_PASSWORD.'&Debug=1';
	
		$client = new soapclient($serverpath);
	
		$get_staff_details = $client->call('get_staff_details',$params);
		
		return $get_staff_details;
	}
	
	function find_academic($phrase) {
		
		$con = new DB_Connection_PDP();
		$user_array = array();
		$sql = "SELECT * FROM user WHERE (reg_number LIKE '%$phrase%' OR firstname LIKE '%$phrase%' OR lastname LIKE '%$phrase%') AND reg_number LIKE 'e%' ORDER BY reg_number ASC LIMIT 50;";
		$con->query($sql);
		while ($user = $con->fetch_array()) {
			
			$usr = new DTO_User;
			$usr->load_by_id($user['user_id']);
			array_push($user_array, $usr);
		}

		unset($con);
	
		return $user_array;
		}

	
	function find_guest($phrase) {

		$con = new DB_Connection_PDP();
		$user_array = array();
		$sql = "SELECT * FROM user WHERE reg_number LIKE '%$phrase%' AND firstname='Guest' ORDER BY reg_number ASC LIMIT 50;";
		$con->query($sql);
		while ($user = $con->fetch_array()) {
			
			$usr = new DTO_User;
			$usr->load_by_id($user['user_id']);
			array_push($user_array, $usr);
		}

		unset($con);
	
		return $user_array;
		}

	function add_email($user_id, $email) {

		$con = new DB_Connection_PDP();
		$user_array = array();
		$sql = "UPDATE user SET email=\"$email\" WHERE user_id=\"$user_id\";";
		$con->query($sql);
		unset($con);

	}

	function all_accessed_since($date, $year_group="all", $more_than_one_login=false) {

		if ($more_than_one_login) 
			$login_count_clause = " AND last_login_time >= $date ";
		else
			$login_count_clause = "";
			
		if ($year_group == "all") {
			$sql = "SELECT count(user_id) FROM user WHERE login_time >= $date $login_count_clause AND (course_year=1 OR course_year=2 OR course_year=3 OR course_year=4);";
		} else {
			$sql = "SELECT count(user_id) FROM user WHERE login_time >= $date $login_count_clause AND course_year=$year_group;";
		}
		
		$con = new DB_Connection_PDP();
		$con->query($sql);
		$total = $con->fetch_array();
		return $total[0];
		
	}

	function faculty_accessed_since($faculty, $date, $year_group="all", $more_than_one_login=false) {

		if ($more_than_one_login) 
			$login_count_clause = " AND last_login_time >= $date ";
		else
			$login_count_clause = "";
		
		if ($year_group == "all") {
			$sql = "SELECT count(user_id) FROM user WHERE login_time >= $date $login_count_clause AND faculty=\"$faculty\" AND (course_year=1 OR course_year=2 OR course_year=3 OR course_year=4) ORDER BY faculty;";
		} else {
			$sql = "SELECT count(user_id) FROM user WHERE login_time >= $date $login_count_clause AND faculty=\"$faculty\" AND course_year=$year_group ORDER BY faculty;";
		}
		$con = new DB_Connection_PDP();
		$con->query($sql);
		$total = $con->fetch_array();
		return $total[0];
		
	}

	function school_accessed_since($school, $date, $year_group="all", $more_than_one_login=false) {
		
		if ($more_than_one_login) 
			$login_count_clause = " AND last_login_time >= $date ";
		else
			$login_count_clause = "";
		
		if ($year_group == "all") {
			$sql = "SELECT count(user_id) FROM user WHERE login_time >= $date $login_count_clause AND school=\"$school\" AND (course_year=1 OR course_year=2 OR course_year=3 OR course_year=4) ORDER BY school;";
		} else {
			$sql = "SELECT count(user_id) FROM user WHERE login_time >= $date $login_count_clause AND school=\"$school\" AND course_year=$year_group ORDER BY school;";
		}
		
		$con = new DB_Connection_PDP();
		$con->query($sql);
		$total = $con->fetch_array();

		return $total[0];

	}

	function programme_accessed_since($programme, $date, $year_group="all", $more_than_one_login=false) {
		
		if ($more_than_one_login) 
			$login_count_clause = " AND last_login_time >= $date ";
		else
			$login_count_clause = "";
		
		if ($year_group == "all") {
			$sql = "SELECT count(user_id) FROM user WHERE login_time >= $date $login_count_clause AND programme=\"$programme\" AND (course_year=1 OR course_year=2 OR course_year=3 OR course_year=4) ORDER BY programme;";
		} else {
			$sql = "SELECT count(user_id) FROM user WHERE login_time >= $date $login_count_clause AND programme=\"$programme\" AND course_year=$year_group ORDER BY programme;";
		}
		$con = new DB_Connection_PDP();
		$con->query($sql);
		$total = $con->fetch_array();

		return $total[0];


	}
	
	function get_unique_schools($fac) {
		
		$sql = "SELECT DISTINCT school FROM user WHERE faculty = \"$fac\" AND (course_year=1 OR course_year=2 OR course_year=3 OR course_year=4) ORDER BY school;";
		$con = new DB_Connection_PDP();
		$con->query($sql);
		$schools = array();
		
		while ($school = $con->fetch_array()) {
			
			array_push($schools, $school[0]);
		}

		unset($con);
	
		return $schools;
	}

	function get_unique_programmes($school) {
		
		$sql = "SELECT DISTINCT programme FROM user WHERE school = \"$school\" AND (course_year=1 OR course_year=2 OR course_year=3 OR course_year=4) ORDER BY programme;";
		$con = new DB_Connection_PDP();
		$con->query($sql);
		$programmes = array();
		
		while ($prog = $con->fetch_array()) {
			
			array_push($programmes, $prog[0]);
		}

		unset($con);
	
		return $programmes;
	}

	function add_password($user_id, $password) {

		$sql = "UPDATE user SET `password`=\"".md5($password.SECRET_PHRASE)."\" WHERE user_id=$user_id;";
		$con = new DB_Connection_PDP();
		$con->query($sql);
		unset($con);

	}

	function migrate_objects($object, $migrate_user_id, $user_id) {

		$con = new DB_Connection_PDP();
		$con1 = new DB_Connection_PDP();

		echo "<p>Migrating all $object Objects...</p>";
		$sql1 = "SELECT * FROM `$object` WHERE `user_id`=$migrate_user_id;";

		$con->query($sql1);
		$obj = array();

		while ($obj = $con->fetch_array()) {
			
			$sql2 = "INSERT INTO migration_information SET `object_type`=\"$object\", `object_id`=$obj[0], `old_user_id`=$migrate_user_id, `new_user_id`=$user_id;";
		
			$con1->query($sql2);
		}


		$sql3 = "UPDATE $object SET `user_id`=$user_id WHERE `user_id`=$migrate_user_id;";

		$con->query($sql3);
		unset($con);

	}

   function disk_usage($user_id) {

      $con = new DB_Connection_PDP();
      $sql = "SELECT sum( `file_size` ) FROM `artifact` WHERE `user_id` =$user_id AND `type` <> 'actifact_deleted'";
      $con->query($sql);
      
      $du = $con->fetch_array();

      if (strlen($du[0]) == 0) $du[0] = 0;

      return $du[0];
      
   }

   function update_cohort_counter($user_id, $cohort_counter) {

      $con = new DB_Connection_PDP();
      $sql = "UPDATE user SET `cohort_counter`=$cohort_counter WHERE `user_id` =$user_id";
      $con->query($sql);
      unset($con);

   }

   function get_cohort_counter($user_id) {

      $con = new DB_Connection_PDP();
      $sql = "SELECT cohort_counter FROM `user` WHERE `user_id`=$user_id";
      $con->query($sql);
      
      $counter = $con->fetch_array();

      return $counter[0];

   }
}

?>