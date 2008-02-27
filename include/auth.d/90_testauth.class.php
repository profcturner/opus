<?php

/**
* Test authentication
* @package OPUS
*/

/**
* Test authentication
*
* @author Gordon Crawford <g.crawford@ulster.ac.uk>
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/

class testauth
{
  function waf_authenticate_user($username, $password)
  {
    switch ($username) 
    {
      case "admin" : 
        $stub_user['valid'] = True;
        $stub_user['username'] = 'admin';
        $stub_user['user'] = array('firstname'=>'Colin', 'lastname'=>'Turner', 'email'=>'c.turner@ulster.ac.uk');
        $stub_user['groups'] = array('admin');
        break;
      case "academic" :
        $stub_user['valid'] = True;
        $stub_user['user'] = array('firstname'=>'Colin', 'lastname'=>'Turner', 'email'=>'c.turner@ulster.ac.uk');
        $stub_user['groups'] = array('academic');
        break;
      case "company" :
        $stub_user['valid'] = True;
        $stub_user['user'] = array('firstname'=>'Colin', 'lastname'=>'Turner', 'email'=>'c.turner@ulster.ac.uk');
        $stub_user['groups'] = array('company');
        break;
      case "workplace" :
        $stub_user['valid'] = True;
        $stub_user['user'] = array('firstname'=>'Colin', 'lastname'=>'Turner', 'email'=>'c.turner@ulster.ac.uk');
        $stub_user['groups'] = array('workplace');
        break;
      case "student" :
        $stub_user['valid'] = True;
        $stub_user['user'] = array('firstname'=>'Colin', 'lastname'=>'Turner', 'email'=>'c.turner@ulster.ac.uk');
        $stub_user['groups'] = array('student');
        $stub_user['reg_number'] = "X1000000";
        $stub_user['user_id'] = "-1";
        break;
      case "super" :
        $stub_user['valid'] = True;
        $stub_user['user'] = array('firstname'=>'Colin', 'lastname'=>'Turner', 'email'=>'c.turner@ulster.ac.uk');
        $stub_user['groups'] = array('super');
        break;
      case "guest" :
        $stub_user['valid'] = True;
        $stub_user['user'] = array('firstname'=>'Colin', 'lastname'=>'Turner', 'email'=>'c.turner@ulster.ac.uk');
        $stub_user['groups'] = array('guest');
        break;
      case "multi" :
        $stub_user['valid'] = True;
        $stub_user['user'] = array('firstname'=>'Colin', 'lastname'=>'Turner', 'email'=>'c.turner@ulster.ac.uk');
        $stub_user['groups'] = array('super', 'academic');
        break;
      default:
        return false;
    }
    return $stub_user;
  }
}

?>