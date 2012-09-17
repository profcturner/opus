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

    // Get details about the student
    require_once("model/Student.class.php");
    $student = Student::load_by_user_id($student_user_id);
    require_once("model/Programme.class.php");
    $student->_programme_id = Programme::get_name($student->programme_id);
    $student_headings = array
    (
      'real_name'=>array('type'=>'text', 'size'=>50, 'title'=>'Name'),
      'reg_number'=>array('type'=>'text', 'size'=>15, 'readonly'=>true, 'mandatory'=>true),
      'programme_id'=>array('type'=>'lookup', 'object'=>'programme', 'value'=>'name', 'title'=>'Programme', 'var'=>'programmes', 'lookup_function'=>'get_id_and_description')
    );
    $assessment_group_id = Student::get_assessment_group_id($student->user_id);
    $regime_items = Student::get_assessment_regime($student->user_id, $aggregate_total, $weighting_total);

    // Get details about the placement
    if(!(preg_match("/^supervisor_([0-9]+)$/", $waf->user['username'], $matches)))
    {
      $waf->halt("error:supervisor:invalid_username");
    }
    $placement_id = $matches[1];
    require_once("model/Placement.class.php");
    $placement = Placement::load_by_id($placement_id);
    $placement_headings = Placement::get_field_defs();

    // And finally any academic tutor
    $academic_id = Student::get_academic_user_id($student_user_id);
    require_once("model/Staff.class.php");
    $academic = Staff::load_by_user_id($academic_id);
    $academic_headings = array
    (
      'real_name'=>array('type'=>'text', 'size'=>50, 'title'=>'Name'),
      'school_id'=>array('type'=>'lookup', 'object'=>'school', 'value'=>'name', 'title'=>'School', 'size'=>20, 'var'=>'schools'),
      'position'=>array('type'=>'text','size'=>50,'header'=>true),
      'email'=>array('type'=>'email','size'=>40, 'header'=>true, 'mandatory'=>true),
      'voice'=>array('type'=>'text','size'=>40),
      'room'=>array('type'=>'text', 'size'=>10, 'header'=>true),
      'address'=>array('type'=>'textarea', 'rowsize'=>6, 'colsize'=>40),
      'postcode'=>array('type'=>'text', 'size'=>10),
    );

    $waf->assign("student", $student);
    $waf->assign("student_headings", $student_headings);
    $waf->assign("placement", $placement);
    $waf->assign("placement_headings", $placement_headings);
    $waf->assign("placement_action", array("confirm", "home", "edit_placement_do"));
    $waf->assign("academic", $academic);
    $waf->assign("academic_headings", $academic_headings);
    $waf->assign("assessment_group_id", $assessment_group_id);
    $waf->assign("regime_items", $regime_items);
    $waf->assign("assessed_id", $student->user_id);
    $waf->assign("aggregate_total", $aggregate_total);
    $waf->assign("weighting_total", $weighting_total);
    $waf->assign("assessment_section", "home");

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
    goto_section("home", "home");
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
    $username = WA::request("username");
    $fullsize = WA::request("fullsize");
    require_once("model/Photo.class.php");

    Photo::display_photo($username, $fullsize);
  }

  // Assessments

  /**
  * show an assessment for viewing or editing
  */
  function edit_assessment(&$waf, &$user)
  {
    // Note security is handled internally by the AssessmentCombined object

    // Get the unique identifer for the assessment instance
    $regime_id = (int) WA::request("id");
    // and for whom
    $assessed_id = (int) WA::request("assessed_id");
    require_once("model/AssessmentCombined.class.php");
    $assessment = new AssessmentCombined($regime_id, $assessed_id, User::get_id());
    $waf->assign("assessment", $assessment);
    $waf->display("main.tpl", "admin:directories:edit_assessment:edit_assessment", "general/assessment/edit_assessment.tpl");
  }

  /**
  * process inbound assessment information
  */
  function edit_assessment_do(&$waf, &$user)
  {
    // Get the unique identifer for the assessment instance
    $regime_id = (int) WA::request("regime_id");
    // and for whom
    $assessed_id = (int) WA::request("assessed_id");
    require_once("model/AssessmentCombined.class.php");
    $assessment = new AssessmentCombined($regime_id, $assessed_id, User::get_id(), true); // try to save
    $waf->assign("assessment", $assessment);
    $waf->display("main.tpl", "admin:directories:edit_assessment:edit_assessment", "general/assessment/edit_assessment.tpl");
  }

  function edit_placement_do(&$waf, &$user) 
  {
    require_once("model/Supervisor.class.php");

    if(!(preg_match("/^supervisor_([0-9]+)$/", $waf->user['username'], $matches)))
    {
      $waf->halt("error:supervisor:invalid_username");
    }
    $placement_id = $matches[1];
    $id = (int) WA::request("id");
    if($id != $placement_id) $waf->halt("error:supervisor:bad_placement_id");

    $student_id = (int) WA::request("student_id");
    if(Supervisor::get_supervisee_id(User::get_id()) != $student_id)
    {
      $waf->halt("error:supervisor:bad_student_id");
    }

    edit_object_do($waf, $user, "Placement", "section=home&function=home", "home");
  }





?>
