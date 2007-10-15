<?php

  /**
  * link back to the edit student page
  */
  function edit_student(&$waf)
  {
    $id = $_SESSION['student_id'];
    goto("directories", "edit_student&id=$id");
  }

  function manage_applications(&$waf, $user, $title)
  {
    $student_id = (int) WA::request("student_id", true);

    manage_objects($waf, $user, "Application", array(), array(array('edit', 'edit_application')), "get_all", "where student_id=$student_id", "student:myplacement:manage_applications:manage_applications");
  }

  function view_assessments(&$waf)
  {
    $id = $_SESSION['student_id'];

    require_once("model/Student.class.php");
    $regime_items = Student::get_assessment_regime($id);
    $waf->assign("regime_items", $regime_items);

    $waf->display("main.tpl", "student:myplacement:view_assessments:view_assessments", "general/assessment/assessment_results.tpl");
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