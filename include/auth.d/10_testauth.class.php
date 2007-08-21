<?php

class testauth
{
  function waf_authenticate_user($username, $password)
  {
    switch ($username) 
    {
      case "admin" : 
        $stub_user['valid'] = True;
        $stub_user['user'] = array('firstname'=>'Colin', 'lastname'=>'Turner', 'email'=>'c.turner@ulster.ac.uk');
        $stub_user['groups'] = array('admin');
        break;
      case "academic" :
        $stub_user['valid'] = True;
        $stub_user['user'] = array('firstname'=>'Colin', 'lastname'=>'Turner', 'email'=>'c.turner@ulster.ac.uk');
        $stub_user['groups'] = array('academic');
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
    }
    return $stub_user;
  }
}

?>