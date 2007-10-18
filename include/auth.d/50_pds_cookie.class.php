<?php

class pds_cookie
{
  function waf_authenticate_user($username, $password)
  {
    require_once("WA.Cookie.class.php");

    // Look for, verify, and decode any PDS cookie
    $PDScookie = Cookie::read('PDSTicket');
    if($PDScookie)
    {
      // if the cookie exists, extract the reg_number which is the
      // best candidate for a username
      $auth_user['valid'] = True;
      $auth_user['reg_number'] = $PDScookie['reg_number'];
      $auth_user['username'] = $PDScookie['reg_number'];

      return $auth_user;
    }
    // Otherwise, fail
    return false;
  }
}

?>