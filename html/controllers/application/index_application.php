<?php

/**
* Directory Menu for Applications
*
* @package OPUS
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
*/

function nav_application() 
{
  return array
  (
   "Home"=>array
    (
      array("home", "home", "home", "home")
		),
    "User"=>array
    (
      array("get_user_status", "user", "form_get_user_status", "form_get_user_status"),
      array("kill_session", "user", "form_kill_session", "form_kill_session"),
    )
  );
}

?>