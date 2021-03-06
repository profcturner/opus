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
*/
class dbusernameauth
{
  function waf_authenticate_user($username, $password)
  {
    if(empty($password)) return false; // sometimes passwords are blank which disables login
    require_once("model/User.class.php");

    $user_object = new User;
    $username = $user_object->_username_password($username, $password);

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