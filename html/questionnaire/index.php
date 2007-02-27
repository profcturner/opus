<?php

include "common.php";
include "class.questionnaire.php";
include "class.Cookie.php";

//  $smarty->debugging = true;




// Connect to the database on the server
db_connect()
  or die("Unable to connect to server");
  
// Validate questions
if(!preg_match('/^[A-Za-z0-9_]+$/', $_REQUEST['questions']))
{
  die_gracefully("Sorry, questions is empty or invalid");
}


// Load the questionnaire data from the control file
$questionnaire = new Questionnaire($_REQUEST['questions'] . ".ctl");

// This script currently isn't in conf (and may never be)
// so give a variable for smarty to use
$smarty->assign("script_path", $_SERVER['PHP_SELF']);

// See if they are logged into PDS
$PDScookie = Cookie::read('PDSTicket');
if($PDScookie)
{
  //if the cookie exists, extract the username(ie student number)
    $username = $PDScookie['reg_num'];
}
if(!$username)
{
//  die_gracefully("This script is only accessible from the PDSystem");
}


$mode = $_REQUEST["mode"];

switch($mode)
{
  case "QuestionnaireSave":
    QuestionnaireSave();
    break;
 
  case "QuestionnaireData":
    QuestionnaireData();
    break;    

  case "QuestionnaireDisplay":
  default:
    QuestionnaireDisplay();
    break;

}


function QuestionnaireData()
{
  global $username;
  global $smarty;
  global $questionnaire;

  $format = $_REQUEST['format'];
  if(empty($format)) $format="HTML";

  $questions = $_REQUEST['questions'];
  // This is already a strongly validated variable...

  $patterns = array("/\r\n/", "/\n\r/", "/\r/", "/\n/");
  switch($format)
  {
    case "HTML":
      $smarty->assign("seperator", "</td><td>");
      $smarty->assign("row_start", "<tr><td>");
      $smarty->assign("row_end", "</td></tr>");
      $replacements = array("<br />", "<br />", "<br />", "<br />");
      break;
    case "TSV":
      $smarty->assign("row_start", "");
      $smarty->assign("seperator", "\t");
      $smarty->assign("row_end", "\n");
      $replacements = array("", "", "", "");
      header("Content-type: text/tab-separated-values");
      header("Content-Disposition: attachment; filename=\"$questions.tsv\"");
      break;
  }

  // Setup information for how to present this...
  $smarty->assign("format", $format);
  $smarty->assign("questions", $questionnaire->questions);

  // Fetch the data from the database - nasty way at the moment, row by row...
  // based on created - a little dangerous. Look at this again...
  $timestamps = array();
  $sql = "select distinct created from questionnaire_results where questions='$questions' order by created";
  $result = mysql_query($sql)
    or die("Query failed.");

  while($row = mysql_fetch_array($result))
  {
    array_push($timestamps, $row['created']);
  }
  mysql_free_result($result);

  $array_of_answers = array();
  // Now loop round these
  foreach($timestamps as $timestamp)
  {
    $answers = array($timestamp);
    // And get each variable
    foreach($questionnaire->questions as $question)
    {
      $sql = "select * from questionnaire_results where created='$timestamp' " .
        "and questions='$questions' and name='" . $question->name . "'";
      $result = mysql_query($sql)
        or die("Unable to fetch individual answer");
      $row = mysql_fetch_array($result);
      if(count($answers) == 1) array_push($answers, $row['username']);
      // Process contents to handle newlines...
      $row['contents'] = preg_replace($patterns, $replacements, $row['contents']);
      array_push($answers, $row['contents']);
      mysql_free_result($result);
    }
    array_push($array_of_answers, $answers);
  }
  $smarty->assign("timestamps", $timestamps);
  $smarty->assign("answers", $array_of_answers);

  $smarty->display("questionnaire/download.tpl");
 
}

function QuestionnaireSave()
{
  global $username;
  global $smarty;
  global $questionnaire;
  
  $errors = FALSE;
  $smarty->assign_by_ref("errors", $errors);
  
  // Check for any validation errors
  foreach($questionnaire->questions as $question)
  {
    $errors |= (!$question->Validate());
  }
  if($errors)
  {
    QuestionnaireDisplay();
    exit;
  }
  
  $now = date("YmdHis");
  foreach($questionnaire->questions as $question)
  {
    $sql = "insert into questionnaire_results " .
      "(questions, username, name, contents, created) " .
      "VALUES(" .
      make_null($_REQUEST['questions']) . ", " .
      make_null($username) . ", " .
      make_null($question->name) . ", " .
      make_null($_REQUEST[$question->name]) . ", $now)";
      
      mysql_query($sql)
        or print_mysql_error2("Unable to save data", $sql);
    //echo $sql . "<br />";
    
  }
  $smarty->display("questionnaire/success.tpl");
  
  /*
  echo "<table>";
  foreach($questionnaire->questions as $question)
  {
    echo "<tr>";
    echo "<th>Name</th>";
    echo "<td>" . htmlspecialchars($question->name) . "</td>";
    echo "<th>Value </th>";
    echo "<td>" . htmlspecialchars($_REQUEST[$question->name]) . "</td>";
    echo "<th>Validity</th>";
    echo "<td>";
    if($question->Validate()) echo "TRUE";
    else echo "FALSE";
    echo "</td>";
    echo "</tr>";
    
  
  }
  echo "</table>";
  */

}

function QuestionnaireDisplay()
{
  global $smarty;
  
  $smarty->display("questionnaire/" . $_REQUEST['questions'] . ".tpl");
}


?>