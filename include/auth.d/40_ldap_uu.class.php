<?php

class ldap_uu
{
  function waf_authenticate_user($username, $password)
  {
    global $waf;
    global $config_sensitive;

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

        if (strcmp(substr($username,0,1),"s") == 0) {
          $grp_array=array("student");
          $reg_number = substr($username,1);
        }
        elseif ((ereg('[^0-9]', substr($username,0,1)))) {
          $grp_array = array('academic');
          $reg_number = $username;
        }
        else {
          $reg_number = $username;
          $username = 's'.$username;
          $grp_array=array("student");
        }

        if (strlen($password) == 0) $password = "you have not entered a password so I am not letting you in!";
        $r = @ldap_bind($ds, "uid=$username,ou=People,dc=ulst,dc=ac,dc=uk","$password");

        $sr = @ldap_search($ds, "ou=People,dc=ulst,dc=ac,dc=uk", "uid=$username");
        $entry = @ldap_first_entry($ds, $sr);
        $attrs = @ldap_get_attributes($ds, $entry);

      if ($r === TRUE) 
      {
        if ($reg_number != "") 
        {
            $firstname = $attrs["givenName"][0];
            $lastname = $attrs["sn"][0];
            $email = $attrs["mail"][0];
        }
      }
      else
      {
        $valid = False;
      }
    }
    else
    {
      $valid = False;
    }

    $auth_user['valid'] = True;
    $auth_user['firstname'] = $firstname;
    $auth_user['lastname'] = $lastname;
    $auth_user['groups'] = $grp_array;
    $auth_user['email'] = $email;
    $auth_user['reg_number'] = $reg_number;
    $auth_user['username'] = $username;

    return $auth_user;
  }


}

?>