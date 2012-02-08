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
* @author Paul Vitty <p.vitty@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/

class ad_staff_uu
{
  function waf_authenticate_user($username, $password)
  {
    $waf =& UUWAF::get_instance();
    global $config_sensitive;

    if(!strlen($username) || !strlen($password)) return false;

    $ldap_host = "ldap.ad.ulster.ac.uk";
    $ldap_suffix = "@ad.ulster.ac.uk";
    $ldap_base = "OU=Ulster,DC=ad,DC=ulster,DC=ac,DC=uk";
    $ldap_search = "(sAMAccount=$username)";

    if(!strlen($ldap_host)) return false;

    $ds = ldap_connect($ldap_host, 389);
    if($ds == false)
    {
      $waf->log("cannot connect to AD host $ldap_host", PEAR_LOG_CRIT, 'panic');
      return false;
    }

    if ($ds)
    {
      ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);

      $r = @ldap_bind($ds, $username.$ldap_suffix, "$password");
      $sr = @ldap_search($ds, $ldap_base, $ldap_search);
      $entry = @ldap_first_entry($ds, $sr);
      $attrs = @ldap_get_attributes($ds, $entry);

      if ($r === TRUE)
      {
        require_once("model/User.class.php");
        $candidate_user = User::load_by_reg_number($username);
        if($candidate_user->id)
	{
          // There's a matching user
          $auth_user['valid'] = true;
          $auth_user['username'] = $candidate_user->username;

          return($auth_user);
	}
      }
    return false;
    }
  }
}

?>