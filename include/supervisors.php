<?php

/**
* Functions for handling supervisor accounts
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @version 3.0
* @package OPUS
* @todo this needs to become Object Oriented
*/

require_once("users.php");

/**
* creates a supervisor account in the id table
*
* @param integer $placement_id is the unique identifier for the placement record
*/
function create_supervisor($placement_id)
{
  global $log;

  if(empty($placement_id))
    die_gracefully("Invalid placement id");

  $sql = "select * from placement WHERE placement_id=$placement_id";
  $result = mysql_query($sql)
    or print_mysql_error2("Unable to get placement info", $sql);
  $placement_info = mysql_fetch_array($result);
  mysql_free_result($result);

  if(empty($placement_info['supervisor_email']))
  {
    die_gracefully("Supervisor has no email address, will not create user");
  }

  // Check it doesn't already exist...
  $sql = "select * from id where username='supervisor_$placement_id'";
  $result = mysql_query($sql);
  if(mysql_num_rows($result))
  {
    mysql_free_result($result);
    die_gracefully("User already exists in database");
  }
  mysql_free_result($result);

  $password = user_make_password();
  $supervisor_real_name = 
    $placement_info['supervisor_title'] . " " .
    $placement_info['supervisor_firstname'] . " " .
    $placement_info['supervisor_surname'];


  $sql = "insert into id (username, password, user, real_name) " .
    "values('supervisor_$placement_id', '" . md5($password) . 
    "', 'supervisor', " . make_null(addslashes($supervisor_real_name)) . ")";
  mysql_query($sql)
    or print_mysql_error2("Unable to add supervisor", $sql);
  $user_id = mysql_insert_id();

  user_notify_password($placement_info['supervisor_email'], 
		       $placement_info['supervisor_title'], 
		       $placement_info['supervisor_firstname'],
		       $placement_info['supervisor_surname'],
		       "supervisor_$placement_id",
		       $password, $user_id, "NewPassword_Supervisor");

  $log['access']->LogPrint("Supervisor $supervisor_real_name created, with email " .
			   $placement_info['supervisor_email'] . " for student " .
			   get_user_name($placement_info['student_id']));
}

?>