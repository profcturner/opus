<?php

  /**
  * Home Menu for Academic Staff
  *
  * @package OPUS
  * @author Colin Turner <c.turner@ulster.ac.uk>
  * @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
  */


  /**
  * the main home page for academic staff
  *
  * @param uuwaf the web application framework object
  */
  function home(&$waf)
  {
    $page = (int) WA::request("page", true);

    $alt_headings = array
    (
      'real_name'=>array('type'=>'text', 'size'=>30, 'header'=>true, 'title'=>'Name'),
      'placement_year'=>array('type'=>'text','size'=>5, 'title'=>'Placement Year', 'header'=>true),
    );
    $waf->assign("alt_headings", $alt_headings);

    require_once("model/Staff.class.php");
    $staff = Staff::load_by_user_id(User::get_id());
    $waf->assign("staff", $staff);

    manage_objects($waf, $user, "Student", array(), array(array('edit', 'edit_student', 'no')), "get_all", array("where academic_user_id = " . User::get_id(), "order by placement_year desc, lastname", $page), "staff:home:home:home", "staff/home/home.tpl");
  }
  
  function other_assessees(&$waf)
  {
    require_once("model/AssessorOther.class.php");
    $other_assessments = AssessorOther::get_all_by_assessor(User::get_id());
    
    $waf->assign("other_assessments", $other_assessments);
    $waf->display("main.tpl", "staff:home:other_assessees:other_assessees", "staff/home/other_assessees.tpl");
  }
  
  /**
  * show an assessment (for an "other" student) for viewing or editing
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
  * process inbound assessment information for an assessment for an "other" student
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
  
  function edit_staff(&$waf, &$user) 
  {
    require_once("model/Staff.class.php");
    $staff = Staff::load_by_user_id(User::get_id());
    $_REQUEST['id'] = $staff->id; // Nasty kludge

    edit_object($waf, $user, "Staff", array("confirm", "home", "edit_staff_do"), array(array("cancel","section=home&function=home")), array(array("user_id", $staff->user_id)), "staff:home:edit_staff:edit_staff");
  }

  function edit_staff_do(&$waf, &$user) 
  {
    require_once("model/Staff.class.php");
    $staff = Staff::load_by_user_id(User::get_id());
    $_REQUEST['id'] = $staff->id; // Nasty kludge

    edit_object_do($waf, $user, "Staff", "section=home&function=home", "edit_staff");
  }


  function edit_student(&$waf)
  {
    // Pick up the student
    $id = (int) WA::request("id");
    $_SESSION['student_id'] = $id;

    // Jump to the context menu
    goto_section("student", "edit_student");
  }

  /**
  * displays the dialog to allow for a password change for the logged in user
  *
  * @param uuwaf the web application framework object
  */
  function change_password(&$waf)
  {
    $waf->display("main.tpl", "staff:home:change_password:change_password", "admin/home/change_password.tpl");
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

  /**
  * shows recent company and vacancy activity
  *
  * by default the activity since the last login is shown, but an arbitrary number
  * of days can be requested.
  *
  * @param uuwaf the web application framework object
  */
  function company_activity(&$waf)
  {
    $days = (int) WA::request("days");

    if($days)
    {
      // Look for activity in the last few days
      $waf->assign("days", $days);
      $unixtime = time();
      $unixtime -= ($days * 24 * 60 * 60);
      $since = date("YmdHis", $unixtime);
    }
    else
    {
      // since the last login
      $last_login = $waf->user['opus']['last_login'];
      $pd = date_parse($last_login);
      $since = sprintf("%04u%02u%02u%02u%02u%02u", $pd['year'], $pd['month'], $pd['day'], $pd['hour'], $pd['minute'], $pd['second']);
    }
    $waf->assign("since", $since);

    require_once("model/Vacancy.class.php");
    require_once("model/Company.class.php");

    $vacancy = new Vacancy;
    $company = new Company;
    $vacancies_created = $vacancy->_get_all("where created > " . $since);
    $vacancies_modified = $vacancy->_get_all("where modified > " . $since);
    $companies_created = $company->_get_all("where created > " . $since);
    $companies_modified = $company->_get_all("where modified > " . $since);

    $vacancy_headings = array(
      'description'=>array('type'=>'text','size'=>30, 'header'=>true, 'listclass'=>'vacancy_description'),
      'company_id'=>array('type'=>'lookup', 'size'=>30, 'header'=>true, 'title'=>'Company Name'),
      'locality'=>array('type'=>'list','size'=>30, 'header'=>true, 'listclass'=>'vacancy_locality'),
      'status'=>array('type'=>'text','size'=>30, 'header'=>true, 'listclass'=>'vacancy_status')
    );

    $company_headings = array(
      'name'=>array('type'=>'text','size'=>30, 'header'=>true),
      'locality'=>array('type'=>'list','size'=>30, 'header'=>true)
    );

    $vacancy_actions = array(array('edit', 'edit_vacancy', 'directories', 'no'));
    $company_actions = array(array('edit', 'edit_company', 'directories', 'no'));

    $waf->assign("vacancies_created", $vacancies_created);
    $waf->assign("vacancies_modified", $vacancies_modified);
    $waf->assign("companies_created", $companies_created);
    $waf->assign("companies_modified", $companies_modified);
    $waf->assign("vacancy_headings", $vacancy_headings);
    $waf->assign("company_headings", $company_headings);
    $waf->assign("vacancy_actions", $vacancy_actions);
    $waf->assign("company_actions", $company_actions);
    $waf->assign("since", $since);

    $waf->display("main.tpl", "staff:home:company_activity:company_activity", "staff/home/company_activity.tpl");
  }

  // Notes

  /**
  * lists all notes associated with a given item
  */
  function list_notes(&$waf, &$user)
  {
    $object_type = WA::request("object_type");
    $object_id = (int) WA::request("object_id");

    $action_links = array(array("add note", "section=directories&function=add_note&object_type=$object_type&object_id=$object_id", "thickbox"));
    require_once("model/Note.class.php");
    $notes = Note::get_all_by_links($object_type, $object_id);
    $waf->assign("notes", $notes);
    $waf->assign("action_links", $action_links);

    $waf->display("main.tpl", "admin:directories:list_notes:list_notes", "admin/directories/search_notes.tpl");
  }

  /**
  * views a specific note
  * @todo show other linked items
  * @todo modify referer code to allow cleanurls
  */
  function view_note(&$waf, &$user)
  {
    $note_id = (int) WA::request("id");

    // Because notes are accessed from all over the place, we don't know where
    // to go back to. So, try and get the referring URL
    if(preg_match("/^.*?(section=.*)$/", $_SERVER['HTTP_REFERER'], $matches))
    {
      $action_links = array(array("back", $matches[1]));
      $waf->assign("action_links", $action_links);
    }
    require_once("model/Note.class.php");
    require_once("model/Notelink.class.php");

    $note = Note::load_by_id($note_id);
    $note_links = Notelink::get_all("where note_id=$note_id");

    $waf->assign("note", $note);
    $waf->assign("note_links", $note_links);

    $waf->display("popup.tpl", "admin:directories:list_notes:view_note", "admin/directories/view_note.tpl");
  }

  function add_note(&$waf, &$user) 
  {
    $object_type = WA::request("object_type");
    $object_id = WA::request("object_id");

    $mainlink = $object_type . "_" . $object_id;

    // Get any inbound variables (validation fail), and make the default auth all
    $nvp_array = $waf->get_template_vars("nvp_array");
    if(!strlen($nvp_array['all']))
    {
      $nvp_array['auth'] = 'all';
      $waf->assign("nvp_array", $nvp_array);
    }

    add_object($waf, $user, "Note", array("add", "directories", "add_note_do"), array(array("cancel","section=directories&function=list_notes&object_type=$object_type&object_id=$object_id")), array(array("mainlink", $mainlink)), "admin:directories:list_notes:add_note", "admin/directories/add_note.tpl");
  }

  function add_note_do(&$waf, &$user) 
  {
    $mainlink = WA::request("mainlink");
    $parts = explode("_", $mainlink);
    $object_type = $parts[0];
    $object_id = $parts[1];

    add_object_do($waf, $user, "Note", "section=home&function=list_notes&object_type=$object_type&object_id=$object_id", "add_note");
  }

?>
