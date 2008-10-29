<?php

/**
* LDAP authentication for University of Ulster
* @package OPUS
*/

/**
* LDAP authentication for University of Ulster
*
* @author Gordon Crawford <g.crawford@ulster.ac.uk>
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/

class ldap_uu
{
  function waf_authenticate_user($username, $password)
  {
    $waf =& UUWAF::get_instance();
    global $config_sensitive;

    if(!strlen($username) || !strlen($password)) return false;
    $ldap_host = $config_sensitive['opus']['auth']['ldap'];
    if(!strlen($ldap_host)) return false;

    $ds = ldap_connect($ldap_host, 389);
    if($ds == false)
    {
      $waf->log("cannot connect to LDAP host $ldap_host", PEAR_LOG_CRIT, 'panic');
      return false; 
    }

    if ($ds)
    {
      $username = strtolower($username);

      // Backwards compatibility for no prefix
      if(preg_match("/^[0-9]+$/", $username)) $username = "s" . $username;
      $r = @ldap_bind($ds, "uid=$username,ou=People,dc=ulster,dc=ac,dc=uk","$password");
      $sr = @ldap_search($ds, "ou=People,dc=ulster,dc=ac,dc=uk", "uid=$username");
      $entry = @ldap_first_entry($ds, $sr);
      $attrs = @ldap_get_attributes($ds, $entry);

      if ($r === TRUE) 
      {
        $auth_user['valid'] = true;
        // Remove s for OPUS db
        if(preg_match("/^s[0-9]+$/", $username))
        {
          $auth_user['username'] = substr($username, 1);
        }
        else
        {
          $auth_user['username'] = $username;
        }
        return($auth_user);
      }
    return false;
    }
  }
}

?>