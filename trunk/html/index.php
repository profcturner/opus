<?php 

/**
**	index.php
**
** The main index page of the website. The program calls the a 
** header and footer file to print the header and footer for 
** the page.
**
** Initial coding : Andrew Hunter
**
** Modified for new backend by Colin Turner
*/

// Include some common functions 
include('common.php');	
include('authenticate.php');

// Connect to the database on the server
db_connect()
  or die("Unable to connect to server");

$mode = $_REQUEST['mode'];


switch($mode)
{
 case 'Logout':
   $log['access']->SetUserName($_SESSION['user']['username']);
   $log['access']->LogPrint('logout');
   clear_all_cookies();
   unset($_SESSION['user']);
   unset($_SESSION['preferences']);
   session_destroy();
   break;
 case 'KillSession':
   // Allows other apps to kill a PMS session and log us out...
   if(!$session_id) exit;
   session_id($session_id);
   unset($_SESSION['user']);
   @session_destroy();
   exit;
   break;    
 case 'Login':
   $username = $_REQUEST['username'];
   $password = $_REQUEST['password'];

   if(!empty($username))
   {
     $log['access']->SetUserName($username);
     $log['security']->SetUserName($username);
     // Try to log in the user
     $login_test = login_user($username, $password);
     if($login_test)
     {
       $log['access']->LogPrint('login');
       // It worked, redirect them if needed, or send them to
       // their home page...
       if(isset($_SESSION['redirect']))
       {
	 $redirect = $_SESSION['redirect'];
	 unset($_SESSION['redirect']);
	 header("Location: $redirect");
       }
       else
       {
	 header("Location: " . $conf['scripts']['user']['login']);
       }    
     }
     else
     {
       $log['access']->LogPrint('login failed');
       $log['security']->LogPrint('login failed');
       $smarty->assign("login_error", !($login_test));
     }
   }
   break;
 default:
   login_external_user();
   break;
}

$page = new HTMLOPUS('Welcome');

$smarty->display('entry_page.tpl');

echo "<table align=\"center\" width=\"800\"><tr><td>";
output_help("OpeningScreen");
echo "</td></tr></table>";

$page->end();


?>
