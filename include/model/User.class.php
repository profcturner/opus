<?php

/**
* Handling for base user details
* @package OPUS
*/
require_once("dto/DTO_User.class.php");
/**
* Handling for base user details
*
* This class is usually augmented by another which details special handling
* for a given class of users, like admins or staff.
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see Admin.class.php
* @see Contact.class.php
* @see Staff.class.php
* @see Student.class.php
* @package OPUS
*
*/

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
  var $session_hash = '';
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

  function get_field_defs($field_def_param = "null")
  {
    $field_defs = self::$_field_defs;
    if($field_def_param == 'application')
    {
      return array(
        'real_name'=>array('type'=>'text','size'=>30, 'title'=>'Name', 'header'=>true),
        'username'=>array('type'=>'text','size'=>15, 'header'=>true),
        'last_time'=>array('type'=>'datetime','size'=>30, 'header'=>true)
      );
    }
    if($field_def_param == 'application_add')
    {
      return array(
        'firstname'=>array('type'=>'text','size'=>30, 'title'=>'Name', 'mandatory'=>true),
        'username'=>array('type'=>'text','size'=>15, 'mandatory'=>true),
        'password'=>array('type'=>'password','size'=>30, 'mandatory'=>true)
      );
    }
    return $field_defs;
  }

  function get_user_list_headings()
  {
    return array(
      'real_name'=>array('type'=>'text','size'=>30, 'header'=>true, title=>'Name'),
      'user_type'=>array('type'=>'text','size'=>30, 'header'=>true, title=>'Type'),
      'username'=>array('type'=>'text','size'=>30, 'header'=>true),
      'reg_number'=>array('type'=>'text', 'header'=>true),
      'last_time'=>array('type'=>'text', 'header'=>true, 'title'=>'Last Access'),
      'email'=>array('type'=>'email','size'=>40, 'header'=>true)
    );
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
  
  function get_type($user_id = 0)
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
      if($user->user_type) return $user->user_type;
      else return false;
    }    
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
      if($user->user_type == $type) return true;
      else return false;
    }
  }

  function get_id()
  {
    $waf =& UUWAF::get_instance();
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

    // We send emails to all users but only to students on creation if so configured...
    if(!($fields['user_type'] == 'student' && $config['opus']['email_students_on_creation']))
    {
      User::email_password($fields, $password);
    }
    return($user_id);
  }

  function update($fields) 
  {
    $user = User::load_by_id($fields[id]);
    if(strlen($fields['firstname']) && strlen($fields['lastname']))
    {
      $fields['real_name'] = $user->form_real_name($fields);
    }
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

  function reset_password($id = 0, $allow_other_user = false)
  {
    if($id == 0) return(false);
    $waf =& UUWAF::get_instance();
    // Non admins can only do this for themselves
    if(!User::is_admin() && !allow_other_user)
    {
      $id = User::get_id();
    }
    $user = User::load_by_id($id);
    if(!strlen($user->email))
    {
      $waf->log("no email for " . $user->real_name . " so password cannot be sent");
      return(false);
    }
    $password = User::make_password();
    $fields = array();
    $fields['id']         = $user->id;
    $fields['password']   = $user->password = md5($password);

    // Write changes to user
    $user->update($fields);

    // Other information required for email
    $fields['salutation'] = $user->salutation;
    $fields['firstname']  = $user->firstname;
    $fields['lastname']   = $user->lastname;
    $fields['username']   = $user->username;
    $fields['email']      = $user->email;
    $fields['user_type']  = $user->user_type;

    User::email_password($fields, $password);
    $waf->log($user->real_name . " has been sent a new password to " . $user->email);
    return(true);
  }
  
  function email_password($fields, $password)
  {
    global $config;
    
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
        // Need to get information about the supervisee
        require_once("model/Supervisor.class.php");
        $student_user_id = Supervisor::get_supervisee_id($fields['id']);
        require_once("model/Student.class.php");
        $student = Student::load_by_user_id($student_user_id);
        $mailfields["student_title"] = $student->salutation;
        $mailfields["student_firstname"] = $student->firstname;
        $mailfields["student_surname"] = $student->lastname;

        Automail::sendmail("NewPassword_Supervisor", $mailfields);
        break;
      case "student" :
        Automail::sendmail("NewPassword_Student", $mailfields);
        break;
      default:
        Automail::sendmail("NewPassword", $mailfields);
    }
  }


  /**
  * Initiates the process to recover a password via email
  * 
  * @param $recovery_email the email address to check
  * @return number of valid accounts found 
  */
  function send_recovery_email($recovery_email)
  {
    global $config;
    
    // We cache the random numbers for a day
    require_once("model/Cache_Object.class.php");
    $cache = new Cache_Object('default', 60*60*24);

    $user = new User;
    $users = $user->_lookup_password_recovery_accounts($recovery_email);
    if(!count($users))
    {
      return 0;
    }

    $mailfields = array();
    $mailfields["rtitle"]     = $users[0]['salutation'];
    $mailfields["rfirstname"] = $users[0]['firstname'];
    $mailfields["rsurname"]   = $users[0]['lastname'];
    $mailfields["remail"]     = $users[0]['email'];

    $block = "";
    $count = 0;
    foreach($users as $user)
    {
      // Create a random number
      $user['random'] = mt_rand();
      $key = "user:reset_password:" . $user['id'];
      $cache->update_cache($key, $user['random']);

      $count++;
      $block .= "$count. ";
      $block .= $user['salutation'];
      $block .= (" " . $user['firstname']);
      $block .= (" " . $user['lastname']);
      $block .= (" [" . $user['user_type'] . "] ");
      if($user['user_type'] == 'supervisor')
      {
        require_once("model/Supervisor.class.php");
        $student_user_id = Supervisor::get_supervisee_id($user['id']);
        require_once("model/Student.class.php");
        $student = Student::load_by_user_id($student_user_id);
        $block .= " (" . $student->salutation . " " . $student->firstname . " " .$student->lastname . ") ";
      }
      $block . "\r\n";
      
      $block .= $config['opus']['url'] . "?function=recover_password_do&user_id=" . $user['id'] . "&hash=" . $user['random'];
      $block .= "\r\n\r\n";
    }
    
    require_once("model/Automail.class.php");
    $mailfields["block"] = $block;
    Automail::sendmail("StartPasswordRecovery", $mailfields);
    return(count($users));
  }


  function get_all($where_clause="", $order_by="ORDER BY lastname", $page=0, $end=0) 
  {
    global $config;
    $user = new User;

    if($end != 0) return($user->_get_all($where_clause, $order_by, $page, $end));
    if ($page <> 0) 
    {
        $start = ($page-1)*$config['opus']['rows_per_page'];
        $limit = $config['opus']['rows_per_page'];
        $users = $user->_get_all($where_clause, $order_by, $start, $limit);
    }
    else 
    {
        $users = $user->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $users;
  }

  function get_id_and_field($fieldname, $where_clause="") 
  {
    $users = new User;
    return  $users->_get_id_and_field($fieldname, $where_clause);
  }

  function get_fields($include_id = false) 
  {
    $user = new User;
    return  $user->_get_fieldnames($include_id);
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
    // Return false is no user was found
    if(!strlen($user->user_type)) return false;
    return $user;
  }

  function load_by_reg_number($reg_number)
  {
    $user = new User;
    $user->reg_number = $reg_number;
    $user->_load_by_field('reg_number');
    // Return false is no user was found
    if(!strlen($user->user_type)) return false;
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

  function check_online_users()
  {
    $waf =& UUWAF::get_instance();
    // Who is supposedly online?
    $marked_online = User::get_id_and_field('session_hash', "where online = 'online'");

    // Hashes for active sessions
    $session_hashes = User::get_active_session_hashes();

    foreach($marked_online as $id => $session_hash)
    {
      if(!in_array($session_hash, $session_hashes))
      {
        $waf->log("User " . User::get_name($id) . "($id) has no active session, marking as offline");
        $fields = array();
        $fields['id'] = $id;
        $fields['online'] = 'offline';
        User::update($fields);
      }
      else
      {
        $waf->log("User " . User::get_name($id) . "($id) has active session");
      }
    }
  }

  /**
  * goes through the session directory making an array of hashes of the filenames
  */
  function get_active_session_hashes()
  {
    $waf =& UUWAF::get_instance();

    $waf->log("building hashes of active sessions", PEAR_LOG_DEBUG);

    $session_hashes = array();
    try
    {
      $dir = new DirectoryIterator(session_save_path());
      foreach($dir as $file)
      {
        // Only interested in files
        if(!$file->isfile()) continue;
        $filename = $file->getFilename();
        array_push($session_hashes, md5($filename));
      }
      $waf->log(count($session_hashes) . " active sessions counted", PEAR_LOG_DEBUG);
    }
    catch (RuntimeException $e)
    {
      $waf->log("Error while reading session directory", PEAR_LOG_DEBUG);
    }
    return($session_hashes);
  }

  function get_name($id)
  {
    $id = (int) $id; // Security

    $data = User::get_id_and_field("real_name","where id='$id'");
    return($data[$id]);
  }

  function get_email($id)
  {
    $id = (int) $id; // Security

    $data = User::get_id_and_field("email","where id='$id'");
    return($data[$id]);
  }

  function get_username($id)
  {
    $id = (int) $id; // Security

    $data = User::get_id_and_field("username","where id='$id'");
    return($data[$id]);
  }

  function get_reg_number($id)
  {
    $id = (int) $id; // Security

    $data = User::get_id_and_field("reg_number","where id='$id'");
    return($data[$id]);
  }

  function get_user_type($id)
  {
    $id = (int) $id; // Security

    $data = User::get_id_and_field("user_type","where id='$id'");
    return($data[$id]);
  }

  /**
  * check a standard OPUS authentication line against a user
  *
  * in a number of places, OPUS uses a text line to define users permitted
  * to access an item. The line has the form
  * [all] [type] ![type]
  *
  * all means access is unrestricted, otherwise the line takes the form of
  * a list of allowed user types, optionally with excluded types.
  *
  * e.g. "all !student" means all users other than students
  *
  * @param string $auth_line the authentication line to check
  * @param int $user_id the user to check, zero means the logged in user
  * @return boolean true if permitted, false otherwise
  */
  function check_auth($auth_line, $user_id = 0)
  {
    $auth_line = strtolower($auth_line);
    if($user_id == 0)
    {
      $current_user_type = $_SESSION['waf']['user']['opus']['user_type'];
    }
    else
    {
      $current_user_type = User::get_user_type($user_id);
    }

    $allowed = FALSE;
    // Ok, authenticate the user against this;
    $auth_parts = explode(" ", $auth_line);
    if(User::is_admin($user_id))
      $allowed = TRUE;
    else
    {
      foreach($auth_parts as $auth_part)
      {
        if($auth_part == $current_user_type) $allowed = TRUE;
        if($auth_part == 'all') $allowed = TRUE;
      }
    }
    // Check for the effects of exclusions now
    if($allowed == TRUE)
    {
      foreach($auth_parts as $auth_part)
        if($auth_part == ("!" . $current_user_type)) $allowed = FALSE;
    }
    return($allowed);
  }

  function upload_path($user_id)
  {
    global $config;

    $upload_dir = $config['opus']['paths']['resources'];
    $top_path = $user_id % 1000;

    if (!file_exists($upload_dir.$top_path)) mkdir($upload_dir.$top_path);
    if (!file_exists($upload_dir.$top_path."/".$user_id)) mkdir($upload_dir.$top_path."/".$user_id);

    $path = $upload_dir.$top_path."/".$user_id."/";

    return $path;
  }

  function not_over_quota($user_id)
  {
    return true;
  }
  
  function online_user_count()
  {
    $user = new User;
    return($user->_online_user_count());
  }
  
  function drop_online_user_count_file()
  {
    $waf = UUWAF::get_instance();
    global $config;
    
    if($config['opus']['drop_online_user_count'])
    {
      $filename = $waf->var_dir . "online_users";
      $fp = @fopen($filename, "w");
      if($fp)
      {
        $user_counts = User::online_user_count();
        foreach((array) $user_counts as $key => $value)
        {
          fwrite($fp, "$key: $value\n");
        }
        fclose($fp);
      }
    }
  }

}

?>
