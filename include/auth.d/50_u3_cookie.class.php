<?php

/**
* Cookie authentication for moving between u3 applications
* @package OPUS
*/

/**
* Cookie authentication for moving between u3 applications
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
* @see Cookie.class.php
*
*/

class u3_cookie
{
  function waf_authenticate_user($username, $password)
  {
    global $waf;
    require_once("WA.Cookie.class.php");

    // Look for, verify, and decode any PDS cookie
    $u3cookie = Cookie::read('u3ticket');
    if($u3cookie)
    {
      // if the cookie exists, extract the reg_number which is the
      // best candidate for a username
      $auth_user['valid'] = True;
      $auth_user['reg_number'] = $u3cookie['reg_number'];
      $auth_user['username'] = $u3cookie['username'];

      require_once("model/User.class.php");
      $user = User::load_by_reg_number($auth_user['reg_number']);
      if($user->id)
      {
        // We know this person
        switch($user->user_type)
        {
          // Only two categories for now
          case student:
          case staff:
          $auth_user['username'] = $user->username;
          $waf->log("cookie authenticated user is " . $user->username);
          break;
        }
      }
      return $auth_user;
    }
    // Otherwise, fail
    return false;
  }
}

?>