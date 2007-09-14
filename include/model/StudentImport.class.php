<?php

class StudentImport
{

  function import_via_SRS()
  {
    global $config_sensitive;
    global $waf;

    echo "hello";
    $password     = $_REQUEST['password'];
    $programme_id = (int) $_REQUEST['programme_id'];
    $year         = (int) $_REQUEST['year'];
    $status       = $_REQUEST['status'];
    $test         = $_REQUEST['test'];
    $onlyyear     = (int) $_REQUEST['onlyyear'];

    require_once("model/Programme.class.php");
    $programme = Programme::load_by_id($programme_id);

    //if(empty($course_id)) die_gracefully("Unknown course");
    //if(empty($onlyyear)) die_gracefully("You must specify the import year in this method");

    if($config_sensitive['ws']['url'])
    {
      $waf->assign("ws_enabled", true);
    }
    else
    {
      $waf->assign("ws_enabled", false);
    }

    require_once("model/WebServices.php");
    // Oddly, for 06/07 the webservice uses 07, not 06!
    $course_xml = WebServices::get_course($programme->srs_ident, substr(get_academic_year()+1, 2), $onlyyear);

    print_r($course_xml);
    exit;
    $students = array();
    foreach($course_xml->students->student as $student)
    {
      $student_array = Student_Import_SRS($student->reg_number);
      // Are they already present?
      if(backend_lookup("id", "id_number", "username", make_null($student->reg_number)))
      {
        // Already exists
        $student_array['result'] = "Exists";
      }
      else
      {
        if(!$test) Student_Insert($student_array, $course_id, $status, $year);
        $student_array['result'] = "Added";
      }
      
      
      array_push($students, $student_array);
    }
    $smarty->assign("test", $test);
    $smarty->assign("course_name", $course_name);
    $smarty->assign("students", $students);
    $smarty->display("admin/import/students_import_srs.tpl");
  }
}

?>