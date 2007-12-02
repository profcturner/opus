<?php

class AssessmentCombined
{
  var $structure; // Assessment structure and validation information
  var $variables; // The variables submitted by the user
  var $error;     // Error information
  var $regime_id;
  var $regime;
  var $assessment_id;
  var $assessed_id;
  var $assessed_name;
  var $assessor_id;
  var $assessor_name;
  var $assessment_results;
  var $assessed_date;
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
    $this->assessor_name = User::get_name($this->assessor_id);

    // Make sure the error value is empty
    $this->error = "";

    $this->regime = AssessmentRegime::load_by_id($regime_id);
    $this->assessment_id = $this->regime->assessment_id;
    $this->assessment = Assessment::load_by_id($this->assessment_id);

    $this->load_structure($this->assessment_id);
    $this->load_totals();
    $this->obtain_variables();

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

    $this->assessment_results = AssessmentTotal::get_totals_with_stamps($this->assessed_id, $this->regime_id);
    if(!empty($this->assessment_results))
    {
      $this->assessor_id = $this->assessment_results['assessor_id'];
      $this->assessor_name = User::get_name($this->assessor_id);
    }
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

    // Put it in the database
    require_once("model/User.class.php");
    require_once("model/AssessmentTotal.class.php");
    $now = date("YmdHis");
    $present = FALSE;
    $fields = array();
    $fields['regime_id']   = $this->regime_id;
    $fields['assessed_id'] = $this->assessed_id;
    $fields['assessor_id'] = $this->assessor_id;
    $fields['mark'] = $total;
    $fields['outof'] = $max;
    $fields['percentage'] = $percentage;
    $fields['assessed'] = $this->assessed_date;

    $assessed_name = $this->assessed_name;
    $assessor_name = $this->assessor_name;
    $assessment_name = $this->regime->student_description;
    if(empty($this->assessment_results))
    {
      $fields['created'] = $now;
      $fields['modified'] = NULL;
      AssessmentTotal::insert($fields);
      $waf->log("assessment $assessment_name filed by $assessor_name for $assessed_name ($percentage%)");
    }
    else
    {
      $fields['modified'] = $now;
      AssessmentTotal::update($fields);
      $waf->log("assessment $assessment_name updated by $assessor_name for $assessed_name ($percentage%)");
    }
  }


  function save_results()
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
      if(!User::is_admin() && ($seconds > (60*60*24)))
      {
        $this->error = "Assessments can only be modified for up to 24 hours after their initial creation. This assessment is now locked, and cannot be edited.";
        return;
      }
    }

    // Delete any existing results
    AssessmentResult::remove("where regime_id = " . $this->regime_id . " and assessed_id = " . $this->assessed_id);

    // Assessement date defaults to now if we don't get something more
    // appropriate
    $assesseddate = $now;
    foreach($this->structure as $item)
    {
      if($item->type == 'assesseddate')
      {
        // assessed dates are special, and stored in totals, not results
        $assesseddate = make_datetime(AssessmentStructure::parse_date($this->variables[$item->name]));
      }
      else
      {
        // Now store each result
        $fields = array();
        $fields['regime_id'] = $this->regime_id;
        $fields['assessed_id'] = $this->assessed_id;
        $fields['name'] = $item->name;
        $fields['contents'] = $this->variables[$item->name];

        AssessmentResult::insert($fields);
      }
    }
    $this->assesseddate = $assesseddate;
    $this->save_totals();
  }

  function get_error()
  {
    return $this->error;
  }

  function get_regime_id()
  {
    return $this->regime_id;
  }

  function get_value($key)
  {
    return $this->variables[$key];
  }

  function obtain_variables()
  {
    $user_input = FALSE;
    // First try to obtain user specified data in the $_REQUEST
    // superglobal, this should override saved data
    // Run through the structure to see what we need
    foreach($this->structure as $item)
    {
      $this->variables[$item->name] = $_REQUEST[$item->name];
      if(!empty($_REQUEST[$item->name])) $user_input = TRUE;
    }

    // No user input, so we get from the database. There's one exception...
    // we don't show data on industrial reports to students
    if(!$user_input && !(User::is_student() && $this->assessment_regime_data->assessor=='industrial'))
    {
      require_once("model/AssessmentResult.class.php");

      $results = AssessmentResult::get_all("where regime_id=" . $this->regime_id . " and assessed_id = " . $this->assessed_id);

      foreach($results as $result)
      {
        $this->variables[$result->name] = $result->contents;
      }

      // Annoyingly, assessed date is stored elsewhere
      foreach($this->structure as $item)
      {
        if($item->type == 'assesseddate')
        {
          // Needs work!
          if($this->assessment_results['assessed_unix'])
          {
            $this->variables[$item->name] = 
              date("d/m/Y", $this->assessment_results['assessed_unix']);
          }
        }
      }
    }
    $this->load_totals();
  }

  /**
  * Performs validation of all variables based on an assessment structure.
  * @return An error string that will be empty if there are no errors.
  */
  function check_variables()
  {
    global $waf;
    $this->error = array();

    foreach($this->structure as $item)
    {
      $name  = $item->name;
      $human = $item->section;
      $value = $this->variables[$name];
      if(!isset($this->variables[$name]))
      {
        if($item->type == 'checkbox')
        {
          // We still need to validate it, it might be compulsory
          array_merge($this->error, $item->validate_variable($value));
        }
        else
        {
          // A missing variable is serious, possibly even a security breach
          $waf->security_log("missing variable" . $item->name .  " in script");
        }
      }
      else
      {
        // The variable is inbound, we must validate it
        array_merge($this->error, $item->validate_variable($value));
      }
    }
    return($this->error);
  }


  /**
  * indicates an error in a given variable
  *
  * @param string $name the variable to check
  * @return an error indicator if appropriate, otherwise an empty string
  */
  function flag_error($name)
  {
    foreach($this->structure as $item)
    {
      if($item->name == $name)
      {
        if(count($item->validate_variable($this->variables[$name]))) return "<span class=\"error\">**</span>";
        else return "";
      }
    }
    return "";
  }


  function displayTemplate()
  { 
    global $smarty;

    $smarty->display($this->assessment_table_data['template_filename']);
  }
};

?>