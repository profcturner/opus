<?php

class AssessmentCombined
{
  var $structure; // Assessment structure and validation information
  var $variables; // The variables submitted by the user
  var $error;     // Error information
  var $cassessment_id;
  var $assessment_id;
  var $assessed_id;
  var $assessed_name;
  var $assessor_id;
  var $assessor_name;
  var $assessment_results;
  var $assessed_date;
  var $assessment_regime_data;
  var $assessment_table_data;

  function __construct($regime_id, $assessed_id, $assessor_id)
  {
    // A unique value for how an assessment is used
    $this->regime_id   = $regime_id;
    $this->assessed_id = $assessed_id;
    $this->assessor_id = $assessor_id;

    require_once("model/User.class.php");
    require_once("model/AssessmentRegime.class.php");
    require_once("model/Assessment.class.php");
    require_once("model/AssessmentStructure.class.php");
    $this->assessed_name = User::get_name($this->assessed_id);

    // Make sure the error value is empty
    $this->error = "";

    $this->regime = AssessmentRegime::load_by_id($regime_id);
    $this->assessment = Assessment::load_by_id($this->regime->id);

    $this->load_structure($this->regime->id);
    $this->load_totals();

  }

  function load_structure($assessment_id)
  {
    require_once("model/AssessmentStructure.class.php");

    $structure_array = AssessmentStructure::get_all("where assessment_id=$assessment_id");

    // Key this with the name
    foreach($structure_array as $item)
    {
      $this->structure[$item->name] = $item;
    }
  }

  function load_totals()
  {
    require_once("model/AssessmentTotal.class.php");

    
    $sql = "select *, UNIX_TIMESTAMP(created) as created_unix, " .
      "UNIX_TIMESTAMP(assessed) as assessed_unix from assessmenttotals where " .
      "assessed_id=" . $this->assessed_id . " and " .
      "cassessment_id=" . $this->cassessment_id;

    $result = mysql_query($sql)
      or print_mysql_error2("Unable to obtain assessment results", $sql);

    $this->assessment_results = mysql_fetch_array($result);
    if(!empty($this->assessment_results))
    {
      $this->assessment_results['assessor_name'] = 
	get_user_name($this->assessment_results['assessor_id']);
    }
    mysql_free_result($result);
  }

  function save_totals()
  {
    global $waf;

    // Form total mark
    $total = 0;
    $max   = 0;

    foreach($this->structure as $item)
    {
      if($item->weighting != 0)
      {
	if($this->variables[$item->name] != "")
	{
	  $total += ($this->variables[$item->name] * $item->weighting);
	  $max   += ($item->max * $item->weighting);
	}
      }
    }
    if($max)
    {
      $percentage = (($total * 100.0) / $max);
    }
    else $percentage = 'NULL';

    $now = date("YmdHis");
    $present = FALSE;
    if(empty($this->assessment_results))
    {
      $query = "INSERT INTO assessmenttotals VALUES(" .
	$this->cassessment_id . ", " .
	$this->assessed_id . ", " .
	$this->assessor_id . ", '', " .
	"$total, $max, $percentage, $now, NULL, " .
	$this->assesseddate. ")";
      mysql_query($query)
	or print_mysql_error2("Unable to insert new assessment total.", $query);
      $log['admin']->LogPrint("Assessment " .
			      $this->cassessment_id . " assessed " . 
			      $this->assessed_id . " assessor " .
			      $this->assessor_id . " percentage $percentage");
    }
    else
    {
      $query = "UPDATE assessmenttotals SET " .
	"assessor_id=" . $this->assessor_id . ", " .
	"mark=$total, outof=$max, percentage=$percentage, modified=$now, " .
	"assessed=" . $this->assesseddate .
	" where cassessment_id=" . $this->cassessment_id . " and " .
	"assessed_id=" . $this->assessed_id;
      mysql_query($query)
	or print_mysql_error2("Unable to update assessment total.", $query);

      $log['admin']->LogPrint("Assessment (update)" .
			      $this->cassessment_id . " assessed " . 
			      $this->assessed_id . " assessor " .
			      $this->assessor_id . " percentage $percentage");


    }
    $this->loadTotals();
  }


  function saveResults()
  {
    // Don't overwrite results if errors occured
    if(!empty($this->error)) return;

    // Get current time
    $now = date("YmdHis");
    $unixnow = time();

    if($this->assessment_results['created_unix'])
    {
      // We allow a 24 hour grace period for assessments to be altered,
      // after that, no luck... except for admins...
      $seconds = $unixnow - $this->assessment_results['created_unix'];
      if(!is_admin() && ($seconds > (60*60*24)))
      {
	$this->error = "Assessments can only be modified for up to 24 hours after their " .
	  "initial creation. This assessment is now locked, and cannot be edited.";
	return;
      }
    }

    // Delete any existing results
    $query = "DELETE FROM assessmentresults where ".
      "cassessment_id=" . $this->cassessment_id . " AND " .
      "assessed_id=" . $this->assessed_id;
    mysql_query($query)
      or print_mysql_error2("Unable to clear existing results.", $query);


    // Assessement date defaults to now if we don't get something more
    // approropriate
    $assesseddate = $now;
    foreach($this->structure as $item)
    {
      if($item['type']=='assesseddate')
      {
	// assessed dates are special, and stored in totals, not results
	$assesseddate = make_datetime(parse_date($this->variables[$item['name']]));
      }
      else
      {
	// Now store each result
	$query = "INSERT INTO assessmentresults VALUES(" .
	  $this->cassessment_id . ", " .
	  $this->assessed_id . ", " .
	  make_null($item['name']) . ", " .
	  ($this->variables[$item['name']] == "0" ? "0" : 
	   make_null($this->variables[$item['name']])) . ")";
	mysql_query($query)
	  or print_mysql_error2("Unable to insert result.", $query);
      }
    }
    $this->assesseddate = $assesseddate;
    $this->saveTotals();
  }


  

  function saveStructure()
  {
  }

  function getError()
    {
      return $this->error;
    }

  function getCassessment_id()
    {
      return $this->cassessment_id;
    }

  function getValue($key)
    {
      return $this->variables[$key];
    }

  function obtainVariables()
  {
    $user_input = FALSE;
    // First try to obtain user specified data in the $_REQUEST
    // superglobal, this should override saved data
    // Run through the structure to see what we need
    foreach($this->structure as $item)
    {
      $this->variables[$item['name']] = $_REQUEST[$item['name']];
      if(!empty($_REQUEST[$item['name']])) $user_input = TRUE;
    }

    // No user input, so we get from the database. There's one exception...
    // we don't show data on industrial reports to students
    if(!$user_input && !(is_student() && $this->assessment_regime_data['assessor']=='industrial'))
    {
      // Ok, we have to try and retrieve from the database
      $sql = "select * from assessmentresults WHERE " .
	"cassessment_id=" . $this->cassessment_id . " and " .
	"assessed_id=" . $this->assessed_id;

      $result = mysql_query($sql)
	or print_mysql_error2("Unable to obtain assessment results", $sql);

      while($row = mysql_fetch_array($result))
      {
	$this->variables[$row['name']] = $row['contents'];
      }
      mysql_free_result($result);

      // Annoyingly, assessed date is stored elsewhere
      foreach($this->structure as $item)
      {
	if($item['type']=='assesseddate')
	{
	  if($this->assessment_results['assessed_unix'])
	  {
	    $this->variables[$item['name']] = 
	      date("d/m/Y", $this->assessment_results['assessed_unix']);
	  }
	}
      }
    }
    $this->loadTotals();
  }

/**
 **	@function check_required_variables
 **	Performs validation of all variables based on an assessment structure.
 **	@param $structure The assessment structure returned by obtain_required_variables
 **	@return An error string that will be empty if there are no errors.
 **
 */
  function checkVariables()
  {
    global $log;	// Access to logging

    foreach($this->structure as $item)
    {
      $name  = $item['name'];
      $human = $item['section'];
      $value = $this->variables[$name];
      if(!isset($this->variables[$name]))
      {
	if($item['type'] == 'checkbox')
	{
	  // We still need to validate it, it might be compulsory
	  $this->error .= $this->validateVariable($item, $value);	
	}
	else
	{
	  // A missing variable is serious, possibly even a security breach
	  $this->error .= "! Missing variable " . htmlspecialchars($item['name']) .
	    ", report to Webmaster<BR>\n";
	  $log['security']->LogPrint("missing variable in script");
	}
      }
      else
      {
	// The variable is inbound, we must validate it
	$this->error .= $this->validateVariable($item, $value);
      }
    }
    return($error); 
  }

  /**
  **	@function validate_variable
  **	Checks an individual assessment variable for validity.
  **	@param $item is a row from the assessmentstructure table for this data
  **	@param $value is the inbound value for this item
  **	@return An error string that will be empty if there are no errors.
  */
  function validateVariable($item, $value)
    {
      $error = "";
      if($item['type']=='assesseddate' || $item['type']=='date') 
      {
	if(!(($item['type'] == 'date') && empty($value)))
	{
          if(empty($value))
          {
            // This needs recoded...
          }
          else
          {
	    $date = parse_date($value);
	    if(!checkdate($date['month'], $date['day'], $date['year']))
	    {
	      $error .= htmlspecialchars($item['human']) . " is invalid.<BR>\n";
	    }
          }
	}
      }
      $error .= $this->validateVariableMinimum($item, $value);
      $error .= $this->validateVariableMaximum($item, $value);
      $error .= $this->validateVariableOptions($item, $value);

      return($error);
    }


/**
**	checks an inbound assessment variable against options.
**	@param item is a row from assessmentstructure
**	@param value is the value entered by the user
**	@returns any error that occurred, or an empty variable
*/
function validateVariableOptions($item, $value)
{
  $error = "";
  if(strstr($item['options'], "compulsory"))
  {
    if($value != "0")
    {
      if(empty($value) || $value="")
      {
        $error = htmlspecialchars($item['human']) .
                 " cannot by empty<BR>\n";
      }
    }
  }
  return($error);
}


/**
**	checks an inbound assessment variable against a minimum.
**	@param item is a row from assessmentstructure
**	@param value is the value entered by the user
**	@returns any error that occurred, or an empty variable
*/
function validateVariableMinimum($item, $value)
{
  $error = "";
  if(!empty($item['min']))
  {
    if($item['type'] == 'textual')
    {
      if(strlen($value) < $item['min'])
      {
        $error = htmlspecialchars($item['human']) .
                 " must have a length greater than " . $item['min'] . "<br />\n";
      }
    }
    if($item['type'] == 'numeric')
    {
      if($value < $item['min'])
      {
        $error = htmlspecialchars($item['human']) .
                 " cannot have a value less than " . $item['min'] . "<br />\n";
      }
    }
  }
  return($error);
}


/**
**	checks an inbound assessment variable against a maximum.
**	@param item is a row from assessmentstructure
**	@param value is the value entered by the user
**	@returns any error that occurred, or an empty variable
*/
function validateVariableMaximum($item, $value)
{
  $error = "";
  if(!empty($item['max']))
  {
    if($item['type'] == 'textual')
    {
      if(strlen($value) > $item['max'])
      {
        $error = htmlspecialchars($item['human']) .
                 " must have a length less than " . $item['max'] . "<br />\n";
      }
    }
    if($item['type'] == 'numeric')
    {
      if($value > $item['max'])
      {
        $error = htmlspecialchars($item['human']) .
                 " cannot have a value more than " . $item['max'] . "<br />\n";
      }
    }
  }
  return($error);
}

function flagVariable($name)
{
  foreach($this->structure as $item)
  {
    if($item['name'] == $name)
    {
      if($this->validateVariable($item, $this->variables[$name])) 
	return "<span class=\"error\">**</span>";
    }
  }
//  return FALSE;
}


function displayTemplate()
  { 
    global $smarty;

    $smarty->display($this->assessment_table_data['template_filename']);
  }



};

?>