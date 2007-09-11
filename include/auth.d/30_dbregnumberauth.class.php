<?php

/**
* The database user authentication object for OPUS
* @package OPUS
*/

class dbregnumberauth
{
  function waf_authenticate_user($username, $password)
  {
    if(empty($password)) return false; //empty password denotes no login
    require_once("model/User.class.php");

    $user_object = new User;
    $username = $user_object->_reg_number_password($username, $password);

    if($username == false)
    {
      return false;
    }
    else
    {
      $user = array();
      $user['valid'] = True;
      $user['username'] = $username;

      return $user;
    }
  }
}

?>