<?php

/**
* The database user authentication object for OPUS
* @package OPUS
*/

/**
* The database user authentication object for OPUS
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/

class dbregnumberauth
{
  function waf_authenticate_user($username, $password)
  {
    if(empty($password)) return false; //empty password denotes no login
    require_once("model/User.class.php");

    // Try original credentials
    $user_object = new User;
    $returned_username = $user_object->_reg_number_password($username, $password);

    if($returned_username == false)
    {
      // Backwards compatibility, slice off any initial s
      if(preg_match("/^s[0-9]+$/", $username))
      {
        $username = substr($username, 1);
      }
      $user_object = new User;
      $returned_username = $user_object->_reg_number_password($username, $password);
    }

    if($returned_username == false)
    {
      return false;
    }
    else
    {
      $user = array();
      $user['valid'] = True;
      $user['username'] = $returned_username;

      return $user;
    }
  }
}

?>