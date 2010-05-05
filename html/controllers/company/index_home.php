<?php

  /**
  * Home Menu for HR Contacts
  *
  * @package OPUS
  * @author Colin Turner <c.turner@ulster.ac.uk>
  * @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
  */

  function home(&$waf)
  {
    // Get the whole list of companies for this person
    require_once("model/Contact.class.php");
    $companies = Contact::get_companies_for_contact(User::get_id());

    $waf->assign("companies", $companies);
    $waf->display("main.tpl", "contact:home:home:home", "contact/home/home.tpl");
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

?>