<?php
/**
 * @package PDSystem
 *
 * reg_number -> username
 * user_id -> id
 * last few fields have gone
 */

require_once("dto/DTO_User.class.php");

Class User extends DTO_User 
{
  var $real_name = "";
  var $username = ""; 
  var $password="";
  var $salutation="";
  var $firstname = '';
  var $lastname = '';
  var $reg_number = "";
  var $login_time = '';
  var $last_time = "";
  var $last_index = "";
  var $online = 'offline';
  var $email = '';
  var $user_type = "";

  static $_field_defs = array
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

  function __construct() 
  {
    parent::__construct();
  }

  function get_field_defs()
  {
    return self::$_field_defs;
  }

  function load_by_id($id) 
  {
     $user = new User;
     $user->id = $id;
     $user->_load_by_id();
     return $user;
  }

  function is_root($user_id = 0)
  {
    return(User::is_type("root", $user_id));
  }

  function is_admin($user_id = 0)
  {
    // root users also qualify as admins
    if(User::is_root($user_id)) return true;
    return(User::is_type("admin", $user_id));
  }

  function is_company($user_id = 0)
  {
    return(User::is_type("company", $user_id));
  }

  function is_supervisor($user_id = 0)
  {
    return(User::is_type("supervisor", $user_id));
  }

  function is_student($user_id = 0)
  {
    return(User::is_type("student", $user_id));
  }

  function is_staff($user_id = 0)
  {
    return(User::is_type("staff", $user_id));
  }

  function is_type($type, $user_id = 0)
  {
    if($user_id == 0)
    {
      // Currently logged in user
      if($_SESSION['waf']['user']['opus']['user_type'] == $type) return true;
      else return false;
    }
    else
    {
      // Another user
      $user = User::load_by_id($user_id);
      if($user->usertype == $type) return true;
      else return false;
    }
  }

  function get_id()
  {
    global $waf;
    return($waf->user['opus']['user_id']);
  }

  function form_real_name($fields)
  {
    $real_name = $fields['salutation'] . " " . $fields['firstname'] . " " . $fields['lastname'];
    return(trim($real_name));
  }

  function insert($fields)
  {
    $user = new User;
    if(empty($fields['username']))
    {
      // No username? Generate one...
      $fields['username'] = User::make_username($fields);
    }
    if(empty($fields['password']))
    {
      $password = User::make_password();
      $fields['password'] = md5($password);
    }
    $fields['real_name'] = $user->form_real_name($fields);
    $user_id = $user->_insert($fields);

    User::email_password($fields, $password);
    return($user_id);
  }



  function update($fields) 
  {
    $user = User::load_by_id($fields[id]);
    $fields['real_name'] = $user->form_real_name($fields);
    $user->_update($fields);
  }

  function exists($id) 
  {
    $user = new User;
    $user->id = $id;
    return $user->_exists();
  }

  function count($where="") 
  {
    $user = new User;
    return $user->_count($where);
  }

  function email_password($fields, $password)
  {
    require_once("model/Automail.class.php");

    $mailfields = array();
    $mailfields["rtitle"]     = $fields['salutation'];
    $mailfields["rfirstname"] = $fields['firstname'];
    $mailfields["rsurname"]   = $fields['lastname'];
    $mailfields["username"]   = $fields['username'];
    $mailfields["password"]   = $password;
    $mailfields["remail"]     = $fields['email'];

    switch($fields['user_type'])
    {
      case "company" :
        Automail::sendmail("NewPassword_Contact", $mailfields);
        break;
      case "staff" :
        Automail::sendmail("NewPassword_Staff", $mailfields);
        break;
      case "supervisor" :
        Automail::sendmail("NewPassword_Supervisor", $mailfields);
        break;
      case "student" :
        // Nothing, for now...
        break;
      default:
        Automail::sendmail("NewPassword", $mailfields);
    }
  }


  function get_all($where_clause="", $order_by="ORDER BY lastname", $page=0, $end=0) 
  {
    $user = new User;

    if($end != 0) return($user->_get_all($where_clause, $order_by, $page, $end));
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

  function load_by_username($username) 
  {
    $user = new User;
    $user->username = $username;
    $user->_load_by_field('username');
    return $user;
  }

  function make_username($fields)
  {
    // $title is not used - at least for now...
    // Strip all characters that aren't alphabetical
    // and make everything lower case.
    $firstname = strtolower(ereg_replace("[^[:alpha:]]", "", $fields['firstname']));
    $lastname   = strtolower(ereg_replace("[^[:alpha:]]", "", $fields['lastname']));

    // Make an initial guess...
    $attempt = substr($firstname, 0, 1) . substr($lastname, 0, 20);

    for($loop = 0; $loop < 99; $loop++)
    {
      // Add a number if required;
      if($loop == 0) $full_attempt = $attempt;
      else $full_attempt = $attempt . $loop;

      if(User::count("where username='$full_attempt'") == 0)
      {
        return($full_attempt);
      }
    }
    // No guess worked, we can improve this later, but it's improbable
    // we will end up here.
    return(FALSE);
  }

  function make_password()
  {
    // Removed l and 1 to prevent font confusion.
    // Removed O and 0 to prevent font confusion
    // Create an array of valid password characters. 
    $the_char = array( 
      "a","A","b","B","c","C","d","D","e","E","f","F","g","G","h","H","i","I","j","J",
      "k","K","L","m","M","n","N","o","p","P","q","Q","r","R","s","S","t","T",
      "u","U","v","V","w","W","x","X","y","Y","z","Z","2","3","4","5","6","7","8",
      "9"
    );

    // Set var to number of elements in the array minus 1, since arrays begin at 0 
    // and the count() function returns beginning the count at 1. 
    $max_elements = count($the_char) - 1; 

    // Now we set our random vars using the rand() function with 0 and the  
    // array count number as our arguments. Thus returning $the_char[randnum]. 
    srand((double)microtime()*1000000);
    for($loop = 0; $loop < 8; $loop++)
    {
      $password[$loop] = $the_char[rand(0,$max_elements)];
    }

    return(implode("", $password));
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
	
	function set_online($reg_number, $status) {
	
		$con = new DB_Connection_PDP();
		$sql = "UPDATE user SET online='".$status."', session='".session_id()."' WHERE reg_number='".$reg_number."';";
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
	
		$serverpath = WEB_SERVICE_URL.'/controller_soap.php?reg_number='.WS_USERNAME.'&password='.WS_PASSWORD.'&Debug=1';
	
		$client = new soapclient($serverpath);
	
		$get_photo_url = $client->call('get_photo',$params);
		
		return $get_photo_url;
	}	
	
	function get_staff_details($staff_code) {
				
		$params = array('staff_code'=>$staff_code);
	
		$serverpath = WEB_SERVICE_URL.'/controller_soap.php?reg_number='.WS_USERNAME.'&password='.WS_PASSWORD.'&Debug=1';
	
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