<?php

  /**
  * Student Menu for Staff
  *
  * @package OPUS
  * @author Colin Turner <c.turner@ulster.ac.uk>
  * @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
  * @todo this controller uses student_id for the student table, not from the user like others
  */

  require_once("model/Student.class.php");
  // Ensure, before anything else happens, that we have the rights to this student
  if(Student::get_academic_user_id(Student::get_user_id($_SESSION['student_id'])) != User::get_id())
  {
    drop_student(&$waf);
  }

  /**
  * link back to the edit student page
  */
  function edit_student(&$waf)
  {
    $id = $_SESSION['student_id'];

    // Get the student
    $student = Student::load_by_id($id);
    
    if(!$student->id)
		{
			//$waf->halt("error:staff:invalid_student");
			$waf->display("popup.tpl", "error:staff:invalid_student", "error.tpl");
		}
	else
	{
		// And the staff member
		require_once("model/Staff.class.php");
		$staff = Staff::load_by_user_id(User::get_id());

		//$assessment_group_id = Student::get_assessment_group_id($student->user_id);
		$regime_items = Student::get_assessment_regime($student->user_id, &$aggregate_total, &$weighting_total);
		require_once("model/Placement.class.php");

		$placements = Placement::get_all("where student_id=" . $student->user_id, "order by jobstart desc");
		$placement_fields = array(
		   'position'=>array('type'=>'text', 'size'=>30, 'maxsize'=>100, 'title'=>'Job Description','header'=>true),
		   'company_id'=>array('type'=>'lookup', 'size'=>30, 'maxsize'=>100, 'title'=>'Company','header'=>true),
		   'jobstart'=>array('type'=>'text', 'size'=>20, 'title'=>'Start','header'=>true),
		   'jobend'=>array('type'=>'text', 'size'=>20, 'title'=>'End','header'=>true)
		);
		$placement_options = array();

		// Some more information about the most recent placement...
		if(count($placements)) // Should *always* be true!*
		{
		  // Get the associated company and vacancy records
		  require_once("model/Company.class.php");
		  $company = Company::load_by_id($placements[0]->company_id);
		  require_once("model/Vacancy.class.php");
		  $vacancy = Vacancy::load_by_id($placements[0]->vacancy_id);

		  // Get a contact, for preference, get the one for the vacancy
		  require_once("model/Contact.class.php");
		  if($vacancy->contact_id) $contact = Contact::load_by_user_id($vacancy->contact_id);
		  else
		  {
			$contacts = Contact::get_all_by_company($placements[0]->company_id);
			$contact = $contacts[0]; // Will be a primary if one exists
		  }

		  $waf->assign("company", $company);
		  $waf->assign("vacancy", $vacancy);
		  $waf->assign("contact", $contact);
		}

		$waf->assign("student", $student);
		$waf->assign("staff", $staff);
		$waf->assign("assessment_section", "student");
		$waf->assign("mode", "view");
		$waf->assign("assessment_group_id", $assessment_group_id);
		$waf->assign("regime_items", $regime_items);
		$waf->assign("assessed_id", $student->user_id);
		$waf->assign("aggregate_total", $aggregate_total);
		$waf->assign("weighting_total", $weighting_total);
		$waf->assign("placements", $placements);
		$waf->assign("placement_fields", $placement_fields);
		$waf->assign("placement_options", $placement_options);

		$waf->display("main.tpl", "staff:student:edit_student:edit_student", "staff/student/edit_student.tpl");
	}
  }

  function list_assessments(&$waf)
  {
    $id = $_SESSION['student_id'];

    require_once("model/Student.class.php");
    $regime_items = Student::get_assessment_regime(Student::get_user_id($id), &$aggregate_total, &$percentage_total);
    $waf->assign("regime_items", $regime_items);
    $waf->assign("assessed_id", Student::get_user_id($id));
    $waf->assign("aggregate_total", $aggregate_total);
    $waf->assign("percentage_total", $percentage_total);
    $waf->assign("assessment_section", "student");

    $waf->display("main.tpl", "student:placement:list_assessments:list_assessments", "general/assessment/assessment_results.tpl");
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
    $assessed_id = (int) Student::get_user_id($_SESSION["student_id"]);

    require_once("model/AssessmentCombined.class.php");
    $assessment = new AssessmentCombined($regime_id, $assessed_id, User::get_id());
    $waf->assign("assessment", $assessment);
    
    $waf->display("main.tpl", "admin:directories:list_assessments:edit_assessment", "general/assessment/edit_assessment.tpl");
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
    $waf->display("main.tpl", "admin:directories:list_assessments:edit_assessment", "general/assessment/edit_assessment.tpl");
  }

  /**
  * removes the student from the session
  */
  function drop_student(&$waf)
  {
    unset($_SESSION['student_id']);
    goto_section("home", "home");
  }

  function list_notes(&$waf)
  {
    require_once("model/Student.class.php");
    $object_type = "Student";
    $object_id = Student::get_user_id($_SESSION['student_id']);

    $action_links = array(array("add note", "section=home&function=add_note&object_type=$object_type&object_id=$object_id","thickbox"));
    require_once("model/Note.class.php");
    $notes = Note::get_all_by_links($object_type, $object_id);
    $waf->assign("notes", $notes);
    $waf->assign("action_links", $action_links);

    $waf->display("main.tpl", "admin:directories:list_notes:list_notes", "admin/directories/search_notes.tpl");
    /*goto_section("home", "list_notes&object_type=Student&object_id=" . Student::get_user_id($_SESSION['student_id']));*/
  }

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

?>
