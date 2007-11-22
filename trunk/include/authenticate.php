<?php

include_once("class.Cookie.php");
include_once("Lastitems.class.php");
//require('common.php');
include('policy.php');
include('database.php');


// Global Variables
$user['id']       = 0;      // The ID of the authenticated user
$user['type']     = 'user'; // Assume minimal access level (non existant)
$user['lasttime'] = NULL;   // The time of the last user login.

$log['access'] = new Log($conf['logs']['access']['file'], $PHP_AUTH_USER);
$log['security'] = new Log($conf['logs']['security']['file'], $PHP_AUTH_USER);

header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: post-check=0, pre-check=0",false);
session_cache_limiter("must-revalidate");

session_set_cookie_params(0);
session_save_path($conf['paths']['session']);
ini_set('session.gc_maxlifetime', '86400'); 
session_start();
$smarty->assign_by_ref("session", $_SESSION);



/**
**	auth_user()
**
** Attempts to authenticate the logged in user to a specified
** level.
**
** The possible levels are:
**
** user    - All authenticated users
** student - Students, but not users nor companies
** company - Companies, but not users nor students
** staff   - Academic staff, but not users nor students
** admin   - Admin staff only, which may have limited administrative power
** root    - Super admins with automatic priviledge over the whole site
**
** Upon authentication the last login time in the
** id table is updated.
**
** @param $level the level of authentication required (see above),
**
** Returns a boolean indicated success of authentication.
*/
function auth_user($level)
{
  global $conf;
  // Need access to the log files to fill in the username
  global $log;

  // Check if we are closed
  if($conf['closed'])
  {
    die_gracefully("The system is closed. Please try again later.");
  }

  // If there is no user information in the session
  if(empty($_SESSION['user']['id']))
  {
    // Check for external verification
    if(!login_external_user())
    {
      // Cache the URI that was to be accessed
      $_SESSION['redirect'] = $_SERVER['REQUEST_URI'];
      // Redirect to the login page
      header("Location: " . $conf['scripts']['user']['index']);
    }
  }

  // Update log names if required
  $log['access']->SetUserName($_SESSION['user']['username']);
  $log['security']->SetUserName($_SESSION['user']['username']);
  $log['admin']->SetUserName($_SESSION['user']['username']);
  $log['system']->SetUserName($_SESSION['user']['username']);
  $log['debug']->SetUserName($_SESSION['user']['username']);
  // Check if all disclaimers are filled?
  check_disclaimers();
  
  // Write Cookie..., only for students at present
  if(is_student())
  {
    $expiry = time() + 1800;
    $cookie_value="reg_num=" . $_SESSION['user']['username'] .
      "&session_id=" . session_id();
    
    Cookie::write("PMSTicket",  $cookie_value);
  }

  // Check whether access should be granted...
  $access = FALSE;
  switch($level)
  {
  case 'root':
    if(is_root()) $access = TRUE;
    break;
  case 'admin':
    // Following call true for root users too
    if(is_admin()) $access = TRUE;
    break;
  case 'student':
    if(is_admin()) $access = TRUE;
    if(is_student()) $access = TRUE;
    break;
  case 'company':
    if(is_admin()) $access = TRUE;
    if(is_company()) $access = TRUE;
    break;
  case 'staff':
    if(is_admin()) $access = TRUE;
    if(is_staff()) $access = TRUE;
    break;
  case 'supervisor':
    if(is_admin()) $access = TRUE;
    if(is_supervisor()) $access = TRUE;
    break;
  case 'user':
    if(is_user()) $access = TRUE;
    break;
  }
  if(!$access)
  {
    print_auth_failure("ACCESS");
    exit;
  }
  

  touch_last_login_time();
}


/**
 ** @function login_user()
 **
 ** Attempts to take a username and password, fill a user
 ** array, and add it to the session. Note that the lasttime
 ** value populated into the user data is that of the last
 ** access during the last session. This is quite deliberate.
 **
 ** @param $username the username passed in by the user
 ** @param $password the password passed in by the user
 ** @return TRUE if authentication is valid, FALSE otherwise
 */
function login_user($username, $password)
{
  // Need access to the database
  global $db;
  global $log;
/*
  if(login_uu_user($username, $password))
  {
    return TRUE;
  }
*/
  $user = get_user($username);

  // Now check password
  $sql = "SELECT user FROM id WHERE username=? AND password=?";

  $res = $db->query($sql, array($username, MD5($password)));
  if (DB::isError($res))
  {
    die($res->getMessage());
  }
  if(!$res->numRows())
  {
    // Password is invalid
    $res->free();

    // Try UU users...
    if(login_uu_user($username, $password))
    {
      return true;
    }
    
    // Ok, nothing there either
    unset($_SESSION['user']);
    session_destroy();
    return false;
  }
  $res->free();

  // Bingo... a valid username and password...
  // Add it to the session and let's go!
  $_SESSION['user'] = $user;
  post_login($username);
  return(TRUE);
}


function get_user($username)
{
  global $db;

  // Perform a query to obtain data for this user
  $sql = "SELECT user, username, id_number, last_time, real_name, " .
    "DATE_FORMAT(last_index, '%Y%m%d%H%i%s') AS last_index_iso " .
    "FROM id WHERE username=?";

  $res = $db->query($sql, array($username));
  if (DB::isError($res))
  {
    die($res->getMessage());
  }
  if(!$res->numRows())
  {
    // No Match!
    $res->free();
    return(FALSE);
  }
  $user_data =& $res->fetchRow();
  $res->free();

  global $smarty;
  $smarty->assign('user_data', $user_data);
  // Ok, a login did occur, let's populate the session
  $user = array();
  $user['dateformat'] = "yyyy-mm-dd";
  $user['id'] = $user_data['id_number'];
  $user['type'] = $user_data['user'];
  $user['real_name'] = $user_data['real_name'];
  $user['username'] = $user_data['username'];
  $user['lasttime'] = $user_data['last_time'];
  $user['lastindex'] = $user_data['last_index_iso'];
  // Make sure lastindex isn't NULL
  if(empty($user['lastindex'])) $user['lastindex'] = date('YmdHis');
  $_SESSION['user'] = $user;
  // Load the default security policy for this user.
  if(is_admin() || is_staff()){
    $user['policy'] = load_default_policy();
  }
  $_SESSION['user'] = $user;

  // Get a lastitem thing going...
  $lastitems = new LastitemQueue(6);
  $_SESSION['lastitems'] = $lastitems;
  return($user);
}


/**
 ** @function login_external_user()
 **
 ** This function checks for external authentication, via
 ** a cookie for example, and logins a user on that basis.
 **
 ** @return TRUE on success, FALSE otherwise
 */
function login_external_user()
{
  // Look for users with a WebCTTicket cookie
  if(login_webct_user()) return TRUE;

  // Or with a PMSTicket cookie
  if(login_pmsticket_user()) return TRUE;

  // Or with a PMSTicket cookie
  if(login_pdsticket_user()) return TRUE;

  return FALSE;
}

function login_uu_user($username, $password)
{
  global $db;
  global $log;
  $debug = TRUE;

  if(empty($username) || empty($password)) return FALSE;

  if($debug) $log['debug']->LogPrint("CLAM: ($username) Checking UU users");
  $url = "http://pds.ulster.ac.uk/clam/http_clam.php?function=validate&username=" .
  htmlspecialchars($username) .
  "&password=" .
  htmlspecialchars($password) .
  "&validation_type=0000001&application=PMS";

  //$log['debug']->LogPrint($url);
  $clam_output = simplexml_load_string(@file_get_contents($url));

  //$log['debug']->LogPrint("CLAM: reg_num " . $clam_output->reg_num . " length " . strlen($clam_output->reg_num));
  if(strlen($clam_output->reg_num))
  {
    if($debug) $log['debug']->LogPrint("CLAM: ($username) this is a valid UU user");
    
    $opus_username = get_opus_username($username);
    if($opus_username)
    {
      if($debug) $log['debug']->LogPrint("CLAM: ($username) OPUS username is $opus_username");
      // Try to log them in...
      $user = get_user($opus_username);
      if($user)
      {
        post_login($username);
        return(TRUE);
      }
      else return(FALSE);      
    }
    else
    {
      if($debug) $log['debug']->LogPrint("CLAM: ($username) no matching OPUS user");
      return(FALSE);
    }
  }
  // No CLAM authentication
  return(FALSE);
}

function login_webct_user()
{
  global $log;
  global $db;
  global $smarty;

  $smarty->assign('cookie', $_COOKIE);

//  $debug = TRUE;

  if($debug) $log['debug']->LogPrint('Looking for WebCT cookie');
 
  // Check for the presence of a WebCT cookie...
  if(isset($_COOKIE['WebCTTicket']))
  {
    //if the cookie exists, extract the username(ie student number)
    //and select the student from the database
    $libticket=$_COOKIE['WebCTTicket'];
    $tmp=explode("&",$libticket);
    $useridarray=explode("=",$tmp[0]);
    $webctuser=$useridarray[1];

    if($debug) $log['debug']->LogPrint("Found WebCT cookie, user $webctuser");
    // This is either a student number of a staff number.
    // Check for a student first
    if($user = get_user($webctuser))
    {
      if($debug) $log['debug']->LogPrint('WebCT: It is a student');
      // Yes, it's a student
      $log['access']->SetUserName($webctuser);
      $log['access']->LogPrint('Login via WebCT');
      post_login($data['username']);
      return TRUE;
    }
    
    // If it's the latter we need a PMS login instead...
    // Time for a very nasty hack to see if this matches an
    // admin member of staff :-(. Don't allow empty strings,
    // they might match an admin with no defined staff number :-(.
    if(!empty($webctuser) && $webctuser != 0)
    {
      if($debug) $log['debug']->LogPrint('WebCT: Are they staff?');
      $sql = "SELECT user_id FROM admins WHERE staffno=?";
      $res = $db->query($sql, array($webctuser));
      if (DB::isError($res))
      {
	die($res->getMessage());
      }
      if($res->numRows())
      {
	$data = $res->FetchRow();
	// We have a user_id, now get the username
	$sql = "SELECT username FROM id WHERE id_number=?";
	$res2 = $db->query($sql, array($data['user_id']));
	if (DB::isError($res2))
	{
	  die($res2->getMessage());
	}
	$data = $res2->FetchRow();
	if($debug) $log['debug']->LogPrint('WebCT: Staff username ' . $data['username']);
	if($user = get_user($data['username']))
	{
	  $res2->free();
	  $res->free();
	  $log['access']->SetUserName($data['username']);
	  $log['access']->LogPrint('Login via WebCT');
    post_login($data['username']); 
	  return TRUE;
	}
	$res2->free();
      }
      $res->free();
    }
  }
  return FALSE;
}


function login_pmsticket_user()
{
  global $log;
  global $db;
  global $smarty;

  $smarty->assign('cookie', $_COOKIE);

  $debug = TRUE;

  if($debug) $log['debug']->LogPrint('Looking for PMSTicket cookie');
 
  // Check for the presence of a WebCT cookie...
  if(isset($_COOKIE['PMSTicket']))
  {
    //if the cookie exists, extract the username(ie student number)
    //and select the student from the database
    $libticket=$_COOKIE['PMSTicket'];
    $tmp=explode("&",$libticket);
    $useridarray=explode("=",$tmp[0]);
    $webctuser=$useridarray[1];

    if($debug) $log['debug']->LogPrint("Found PMSTicket cookie, user $webctuser");
    // This is either a student number of a staff number.
    // Check for a student first
    if($user = get_user($webctuser))
    {
      if($debug) $log['debug']->LogPrint('PMSTicket: It is a student');
      // Yes, it's a student
      $log['access']->SetUserName($webctuser);
      $log['access']->LogPrint('Login via PMSTicket');
      post_login($webctuser);
      return TRUE;
    }
    
    // If it's the latter we need a PMS login instead...
    // Time for a very nasty hack to see if this matches an
    // admin member of staff :-(. Don't allow empty strings,
    // they might match an admin with no defined staff number :-(.
    if(!empty($webctuser) && $webctuser != 0)
    {
      if($debug) $log['debug']->LogPrint('PMSTicket: Are they staff?');
      $sql = "SELECT user_id FROM admins WHERE staffno=?";
      $res = $db->query($sql, array($webctuser));
      if (DB::isError($res))
      {
	die($res->getMessage());
      }
      if($res->numRows())
      {
	$data = $res->FetchRow();
	// We have a user_id, now get the username
	$sql = "SELECT username FROM id WHERE id_number=?";
	$res2 = $db->query($sql, array($data['user_id']));
	if (DB::isError($res2))
	{
	  die($res2->getMessage());
	}
	$data = $res2->FetchRow();
	if($debug) $log['debug']->LogPrint('PMSTicket: Staff username ' . $data['username']);
	if($user = get_user($data['username']))
	{
	  $res2->free();
	  $res->free();
	  $log['access']->SetUserName($data['username']);
	  $log['access']->LogPrint('Login via PMSTicket');
    post_login($data['username']); 
	  return TRUE;
	}
	$res2->free();
      }
      $res->free();
    }
  }
  return FALSE;
}


function login_pdsticket_user()
{
  global $log;
  global $db;
  global $smarty;

  $smarty->assign('cookie', $_COOKIE);

  $debug = TRUE;

  if($debug) $log['debug']->LogPrint('Looking for PDSTicket cookie');
  $PDScookie = Cookie::read('PDSTicket');
  if($PDScookie)
  {
    //if the cookie exists, extract the username(ie student number)
    //and select the student from the database
    $username = $PDScookie['reg_num'];
    
    if($debug) $log['debug']->LogPrint("Found PDSTicket cookie, user $username");
    // This is either a student number of a staff number.
    // Check for a student first
    if($user = get_user($username))
    {
      if($debug) $log['debug']->LogPrint('PDSTicket: It is a student');
      // Yes, it's a student
      $log['access']->SetUserName($username);
      $log['access']->LogPrint('Login via PDSTicket');      
      post_login($username);
      return TRUE;
    }
    
    // If it's the latter we need a PMS login instead...
    // Time for a very nasty hack to see if this matches an
    // admin member of staff :-(. Don't allow empty strings,
    // they might match an admin with no defined staff number :-(.
    if(!empty($username) && $username != 0)
    {
      if($debug) $log['debug']->LogPrint('PDSTicket: Are they staff?');
      $sql = "SELECT user_id FROM admins WHERE staffno=?";
      $res = $db->query($sql, array($username));
      if (DB::isError($res))
      {
	die($res->getMessage());
      }
      if($res->numRows())
      {
	$data = $res->FetchRow();
	// We have a user_id, now get the username
	$sql = "SELECT username FROM id WHERE id_number=?";
	$res2 = $db->query($sql, array($data['user_id']));
	if (DB::isError($res2))
	{
	  die($res2->getMessage());
	}
	$data = $res2->FetchRow();
	if($debug) $log['debug']->LogPrint('PDSTicket: Staff username ' . $data['username']);
	if($user = get_user($data['username']))
	{
	  $res2->free();
	  $res->free();
	  $log['access']->SetUserName($data['username']);
	  $log['access']->LogPrint('Login via PDSTicket');
   post_login($data['username']); 
	  return TRUE;
	}
	$res2->free();
      }
      $res->free();
    }
    // Cookie, but no valid username
    $_SESSION['LoginFromPDSFail'] = TRUE;
  }
  return FALSE;
}






Function check_disclaimers()
{
  // Only disclaimers for students so far...
  if(!is_student()) return;

  $squery  = "SELECT * FROM students WHERE user_id=" . $_SESSION['user']['id'];
  $sresult = mysql_query($squery)
    or print_mysql_error2("Unable to fetch student information.", $squery);
  if(!mysql_num_rows($sresult)){
    $squery = "INSERT INTO students VALUES(" . $_SESSION['user']['id'] .
      ", " . (get_academic_year() + 1) . ", 'Required', NULL)";

    mysql_query($squery)
      or print_mysql_error2("Unable to create new student record.", $squery);
  }
  $srow = mysql_fetch_array($sresult);
  mysql_free_result($sresult);

  if(!strstr($srow['progress'], 'disclaimer')){
    include("disclaimer.php");
    student_disclaimer();
  }
}

function get_user_type($user_id)
{
  if(!$user_id) return "";
  
  $sql = "select user from id where id_number=$user_id";
  $result = mysql_query($sql)
    or print_mysql_error2("Couldn't fetch user type", $sql);
  $data = mysql_fetch_row($result);
  mysql_free_result($result);
  
  return($data[0]);
}


/**
**	is_root()
**
** Indicates whether or not the authenticated user has
** root (super user admin) access.
**
*/
function is_root($user_id = 0)
{
  if(!$user_id)
  {
    if($_SESSION['user']['type'] == "root") return TRUE;
    else return FALSE;
  }
  else
  {
    return(get_user_type($user_id) == "root");
  }
}


/**
**	is_admin()
**
** Indicates whether or not the authenticated user has
** admin access.
**
*/
function is_admin($user_id = 0)
{
  if(!$user_id)
  {
    if($_SESSION['user']['type'] == "root") return TRUE;
    if($_SESSION['user']['type'] == "admin") return TRUE;
    else return FALSE;
  }
  else
  {
    $user_type = get_user_type($user_id);
    if($user_type == "root") return TRUE;
    if($user_type == "admin") return TRUE;
    return FALSE;
  }
}


/**
**	is_student()
**
** Indicates whether or not the authenticated user has
** "student" status.
**
*/
function is_student($user_id = 0)
{
  if(!$user_id)
  {
    if($_SESSION['user']['type'] == "student") return TRUE;
    else return FALSE;
  }
  else
  {
    return(get_user_type($user_id) == "student");
  }
}  


/**
**	is_company()
**
** Indicates whether or not the authenticated user has
** "company" status.
**
*/
function is_company($user_id = 0)
{
  if(!$user_id)
  {
    if($_SESSION['user']['type'] == "company") return TRUE;
    else return FALSE;
  }
  else
  {
    return(get_user_type($user_id) == "company");
  }

}  

function is_supervisor($user_id = 0)
{
  if(!$user_id)
  {
    if($_SESSION['user']['type'] == "supervisor") return TRUE;
    else return FALSE;
  }
  else
  {
    return(get_user_type($user_id) == "supervisor");
  }
}

function is_staff()
{
  if(!$user_id)
  {
    if($_SESSION['user']['type'] == "staff") return TRUE;
    else return FALSE;
  }
  else
  {
    return(get_user_type($user_id) == "staff");
  }
}

function is_user()
{
  if(isset($_SESSION['user'])) return TRUE;
  else return FALSE;
}


function is_course_director()
{
  if(!is_staff()) return(FALSE);

  $query = "SELECT * FROM coursedirectors WHERE staff_id=" . $_SESSION['user']['id'];
  $result = mysql_query($query)
    or print_mysql_error2("Unable to check course director table.", $query);
  $decision = mysql_num_rows($result);
  mysql_free_result($result);

  return($decision);
}


/**
**	print_auth_failure()
**
** Prints information to the end user concerning an
** authorization failure, either due to missing or wrong
** credentials, or lack of access.
**
*/
function print_auth_failure($known)
{
  global $conf; // We need access to the configuration
  global $log;  // And the security log
  global $smarty; 

  // Display a message
  $smarty->display("auth_failed.tpl");

  // Make a note in the security log...
  $log['security']->LogPrint("Authorisation failed, attempting to access URL [" .
			     $_SERVER['REQUEST_URI'] . "] from IP address [" .
			     $_SERVER['REMOTE_ADDR'] . "]");
  exit;

}


/**
**	get_id()
**
** Returns the id number (from the id table) for the
** currently authenticated user.
**
*/
function get_id()
{
  return($_SESSION['user']['id']);
}


/**
**	get_name()
**
** Returns the name of the currently authenticated user
** which comes from the id table.
**
*/
function get_name()
{
  $query = sprintf("SELECT real_name FROM id WHERE id_number=%s", $_SESSION['user']['id']); 
  $result = mysql_query($query);
  $row = mysql_fetch_row($result);
  mysql_free_result($result);
  return($row[0]);
}

function touch_last_login_time()
{
  $query = "UPDATE id SET last_time= " . date("YmdHis") .
           " WHERE id_number=" . $_SESSION['user']['id'];

  mysql_query($query)
    or print_mysql_error2("Unable to update last time timestamp.", $query);
}



/**
**	touch_last_index()
**
** This function touches the field in the id table to
** indicate that the index page has been viewed.
**
*/
function touch_last_index()
{
  $query = "UPDATE id SET last_index= " . date("YmdHis") .
           " WHERE id_number=" . $_SESSION['user']['id'];

  mysql_query($query)
    or print_mysql_error2("Unable to update last index timestamp.", $query);
}




function get_last_index()
{
  $query = "SELECT DATE_FORMAT(last_index, '%Y%m%d%H%i%s') FROM id WHERE id_number=" .
           $_SESSION['user']['id'];

  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch last index time.", $query);

  $row = mysql_fetch_row($result);

  mysql_free_result($result);

  if(empty($row[0])) $row[0]="00000000000000";
  return($row[0]);
}


function clear_all_cookies()
{
  // Clear PMS, PDS if they exist...
  if(is_student())
  {
    // Write blank expired cookie over PMSTicket
    Cookie::write('PMSTicket', 'blank', 0);
  }
  if($PDScookie = Cookie::Read('PDSTicket'))
  {
    // Get session id so we can clear it...
    // Note session path may need editing ... this is a bugbear, Gordon and I should really use our own...
    $sessionpath = session_save_path();
    if($PDScookie["session_id"])
    {
      @unlink($sessionpath . "/sess_" . $PDScookie["session_id"]);
    }
    Cookie::write('PDSTicket', 'blank', 0);    
  }
}


function pdp_get_preferences($username)
{
  global $conf;
  global $log;

  $url = $conf['pdp']['host'] . "/pdp/controller.php?" .
    "function=get_style&" .
    "&reg_number=$username&" .
    "username=" . $conf['pdp']['user'] . "&password=" . $conf['pdp']['pass'];

  $log['debug']->LogPrint("Fetching PDP preferences for $user_id");
  $file = @file_get_contents($url);

  if($file == FALSE)
  {
    $log['debug']->LogPrint("Fetching for $user_id failed");
    return FALSE;
  }

  return $file;
}


function post_login($username)
{
  global $log;
  
  // If it's a student, try to get their PDP config.
  if(is_student())
  {
    $preferences = array();
    $preferences['style'] = pdp_get_preferences($username);
    
//    echo $username;
//    echo $preferences['style'];
    switch($preferences['style'])
    {
      // Allow the following styles...
      case "red.css":
      case "green.css":
      case "blue.css":
      case "text.css":
      break;
  
      default:
      // Otherwise, make a default choice...
      $preferences['style'] = "blue.css";
      break;
  
    }
    
  }
  else
  {
//    echo "not student";
    $preferences['style'] = "blue.css";
  }
  $_SESSION['preferences'] = $preferences;
  
  // Set up log names
  $log['access']->SetUserName($username);
  $log['admin']->SetUserName($username);
}

/**
* attempts to find a valid username (if possible) from a UU reg number
*
* @param integer $reg_number the UU registration "number"
* @return any valid OPUS username that matches
*/
function get_opus_username($reg_number)
{
  // Student registrations
  if($reg_number[0] == 's')
  {
    $username = substr($reg_number, 1);
  }
  if($reg_number[0] == 'e')
  {
    // Look in staff, then admin
    $user_id = backend_lookup('staff', 'user_id', 'staffno', substr($reg_number, 1));
    if(!$user_id)
    {
      // No staff match, check admin table
      $user_id = backend_lookup('admins', 'user_id', 'staffno', substr($reg_number, 1));
    }
    
    if($user_id)
    {
      // Ok, we found one or the other
      return(backend_lookup('id', 'username', 'id_number', $user_id));
    }
  }
  // Student numbers used to have no s prefix
  if(is_numeric($reg_number)) return($reg_number);
  
  // No joy...
  return("");

}

?>