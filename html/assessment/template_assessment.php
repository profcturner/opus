<?php

// The include files
include('common.php');
include('authenticate.php');
include('assessment.php');
include('assessment2.php');
include('lookup.php');

// Connect to the database on the server
db_connect()
  or die("Unable to connect to server");

// Authenticate user so that the right people see the right thing
auth_user("user");

// Check for a cassessment_id
if(empty($cassessment_id))
{
  die_gracefully("You cannot access this page without a valid cassessment_id.");
}

// Check for a assessed_id
if(empty($assessed_id))
{
  die_gracefully("You cannot access this page without a valid assessed_id.");
}

// The following should be the student version of the assessment title
$page = new HTMLOPUS("Assessment");

// Further security tests
$assessor_id = get_id();

$sql = "select * FROM assessmentregime where cassessment_id=$cassessment_id";
$result = mysql_query($sql)
  or print_mysql_error2("Unable to fetch assessment information", $sql);
$assessment_info = mysql_fetch_array($result);
mysql_free_result($result);

$canView = FALSE;
$canEdit = FALSE;

if(is_student())
{
  // Students can only look at themselves
  $assessed_id = get_id();

  // Students can always view...
  $canView = TRUE;
  // And edit, only if self assessment
  if($assessment_info['assessor']=='student') $canEdit = TRUE;
}
// For now, only students are assessed, and I want the student menu to trigger...
$student_id = $assessed_id;

if(is_staff())
{
  // No, they are a normal staff member, so they can only look if
  // They are the academic tutor, or a designated other assessor
  // for this assessment...
  $assessor_id = get_id();
  if(get_academic_tutor($assessed_id) == $assessor_id)
  {
    $canView = TRUE;
    if($assessment_info['assessor']=='academic') $canEdit = TRUE;
  }
  // there was an else here, but actually that causes a blip when the person is
  // an academic tutor, so leaving an implicit or by removing these seems better...
  {
    $sql = "select * from assessorother where cassessment_id=$cassessment_id " .
      "and assessor_id=$assessor_id and assessed_id=$assessed_id";
    $result = mysql_query($sql);
    if(mysql_num_rows($result))
    {
      $canView = TRUE;
      $canEdit = TRUE;
    }
    mysql_free_result($result);
  }
}

if(is_admin())
{
  if(is_auth_for_student($student_id, "student", "viewAssessment"))
  {
    $canView = TRUE;
  }
  if(is_auth_for_student($student_id, "student", "editAssessment"))
  {
    $canEdit = TRUE;
  }
}

// Supervisors can only see what they are responsible for...
if(is_supervisor())
{
  if($assessment_info['assessor']=='industrial')
  {
    $canView = TRUE;
    $canEdit = TRUE;
  } 
}

if(is_company())
{
  die_gracefully("not supported yet");
}

// Allow the option to suppress the menu
//if(!($_REQUEST['printer_friendly'])) print_menu("");

// Load the data
$assessment_object = new PMSAssessment($cassessment_id, $assessed_id, $assessor_id);
$assessment_object->obtainVariables();
$error = $assessment_object->checkVariables();

$mode = $_REQUEST["mode"];

$smarty->assign("mode", $mode);
$smarty->assign("canView", $canView);
$smarty->assign("canEdit", $canEdit);
$smarty->assign_by_ref("assessment", $assessment_object);
$smarty->assign("printer_friendly", $_REQUEST['printer_friendly']);

switch($mode)
{
  case "AssessmentDisplayForm":

    if(!$canView)
    {
      die_gracefully("Sorry, you are not permitted to see this assessment");
    }
    $assessment_object->displayTemplate();
    $page->end();

    break;


  case "AssessmentSubmitResults":

    if(!$canEdit)
    {
      die_gracefully("Sorry, you are not permitted to make / alter this assessment");
    }
    $assessment_object->saveResults();
    $assessment_object->displayTemplate();
    $page->end();

    break;

 default:
   die_gracefully("Unknown mode");
}


?>