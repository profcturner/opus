<?php

  /**
  * Student Menu for Administrators
  *
  * @package OPUS
  * @author Colin Turner <c.turner@ulster.ac.uk>
  * @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
  */

  /**
  * link back to the edit student page
  */
  function edit_student(&$waf)
  {
    $id = $_SESSION['student_id'];
    goto("directories", "edit_student&id=$id");
  }

  /**
  * show the student's placement page, to the best of our ability
  */
  function placement_home(&$waf)
  {
    $student_id = (int) WA::request("student_id", true);
    require_once("model/Student.class.php");

    $student = Student::load_by_id($student_id);

    $waf->assign("student", $student);
    $waf->display("main.tpl", "student:myplacement:home:home", "student/home/home.tpl");
  }

  function vacancy_directory(&$waf)
  {
    goto("directories", "vacancy_directory");
  }

  function manage_applications(&$waf, $user, $title)
  {
    $student_id = (int) WA::request("student_id", true);
    $page = (int) WA::request("page", true);

    manage_objects($waf, $user, "Application", array(), array(array('edit', 'edit_application')), "get_all", array("where student_id=$student_id", "order by created", $page), "student:myplacement:manage_applications:manage_applications");
  }

  function view_assessments(&$waf)
  {
    $id = $_SESSION['student_id'];

    $student_user_id = Student::get_user_id($id);
    require_once("model/Student.class.php");
    $regime_items = Student::get_assessment_regime($student_user_id,  &$aggregate_total, &$weighting_total);
    $waf->assign("regime_items", $regime_items);
    $waf->assign("assessed_id", $id);
    $waf->assign("aggregate_total", $aggregate_total);
    $waf->assign("weighting_total", $weighting_total);

    $waf->display("main.tpl", "student:myplacement:view_assessments:view_assessments", "general/assessment/assessment_results.tpl");
  }

  function list_notes(&$waf)
  {
    goto("directories", "list_notes&object_type=Student&object_id=" . $_SESSION['student_id']);
  }

  /**
  * removes the student from the session
  */
  function drop_student(&$waf)
  {
    unset($_SESSION['student_id']);
    goto("home", "home");
  }

?>