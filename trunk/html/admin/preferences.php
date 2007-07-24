<?php 

/**
**	status.php
**
** This script displays useful status information
** for admin users.
**
** Initial coding : Colin Turner
**
*/

// The include files
include('common.php');
include('authenticate.php');
include('lookup.php');

// Connect to the database on the server
db_connect()
  or die("Unable to connect to server");

// Authenticate user so that the right people see the right thing
auth_user("admin");

if(!check_default_policy('status', 'user'))
  print_auth_failure("ACCESS");

$page = new HTMLOPUS('Preferences', 'Configuration', 'system status');

switch($mode)
{
  case "EditChannels_On":
    EditChannels_On();
    break;

  case "EditChannels_Off":
    EditChannels_Off();
    break;

  default:
    die_gracefully("Invalid mode");
}

function EditChannels_On()
{
  $_SESSION['display_prefs']['edit_channels']=true;
  echo "Channel editing on...";
}


function EditChannels_Off()
{
  $_SESSION['display_prefs']['edit_channels']=false;
  echo "Channel editing off...";
}


$page->end();			// Calls the function for the footer

?>


