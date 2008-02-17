<?php

  /**
  * Home Menu for Workplace Supervisors
  *
  * @package OPUS
  * @author Colin Turner <c.turner@ulster.ac.uk>
  * @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
  */

  function home(&$waf)
  {
    require_once("model/Supervisor.class.php");
    $student_user_id = Supervisor::get_supervisee_id(User::get_id());

    require_once("model/Student.class.php");
    $student = Student::load_by_user_id($student_user_id);
    $assessment_group_id = Student::get_assessment_group_id($student->user_id);
    $regime_items = Student::get_assessment_regime($student->user_id, &$aggregate_total, &$weighting_total);

    $waf->assign("student", $student);
    $waf->assign("assessment_group_id", $assessment_group_id);
    $waf->assign("regime_items", $regime_items);
    $waf->assign("assessed_id", $student->user_id);
    $waf->assign("aggregate_total", $aggregate_total);
    $waf->assign("weighting_total", $weighting_total);

    $waf->display("main.tpl", "supervisor:home:home:home", "supervisor/home/home.tpl");
  }

  /**
  * displays the dialog to allow for a password change for the logged in user
  *
  * @param uuwaf the web application framework object
  */
  function change_password(&$waf)
  {
    $waf->display("main.tpl", "admin:home:change_password:change_password", "admin/home/change_password.tpl");
  }

  /**
  * attempts to process a change of password for the currently logged in user
  *
  * @param uuwaf the web application framework object
  */
  function change_password_do(&$waf)
  {
    $old_password      = WA::request("old_password");
    $new_password      = WA::request("new_password");
    $new_password_copy = WA::request("new_password_copy");

    require_once("model/User.class.php");
    $user = User::load_by_id(User::get_id());

    if(md5($old_password) != $user->password)
    {
      $error = true;
      $waf->assign("failed_old", true);
    }
    if($new_password != $new_password_copy)
    {
      $error = true;
      $waf->assign("failed_new_equal", true);
    }
    if(!test_password_strength($new_password))
    {
      $error = true;
      $waf->assign("failed_new_simple", true);
    }
    if($error)
    {
      change_password($waf);
      exit;
    }

    // Must be ok...
    $user->password = md5($new_password);
    $user->_update();
    goto("home", "home");
  }

  /**
  * a very simple test for password strength
  *
  * at the moment, is merely puts a limit on length, and requires a mixture of cases
  *
  * @param string $password
  * @return boolean true on a good password, false otherwise
  */
  function test_password_strength($password)
  {
    if(strlen($password) < 8) return false;
    if(preg_match("/^[a-z]$/", $password)) return false;
    if(preg_match("/^[A-Z]$/", $password)) return false;

    return true;
  }

  // Photos

  function display_photo(&$waf, &$user)
  {
    $user_id = (int) WA::request("user_id");
    $fullsize = WA::request("fullsize");
    require_once("model/Photo.class.php");

    Photo::display_photo($user_id, $fullsize);
  }




?>