<?php
/**
* Encapulates many classes that handle assessments into one unit
* @package OPUS
*/

/**
* Encapulates many classes that handle assessments into one unit
*
* In order to allow easy creation of assessment templates, a single assessment object
* is made available to extract information about the assessment. This is that object.
* It gathers together totals, results, weighting and more.
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see Assessment.class.php
* @see AssessmentRegime.class.php
* @see AssessmentStructure.class.php
* @see AssessmentTotal.class.php
* @see AssessmentResult.class.php
* @package OPUS
*
*/

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
  var $can_view;
  var $can_edit;
  var $save;
  var $early;
  var $late;
  var $time_left;

  function __construct($regime_id, $assessed_id, $assessor_id, $save = false)
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
    $this->early = "";
    $this->late = "";

    $this->regime = AssessmentRegime::load_by_id($regime_id);
    $this->assessment_id = $this->regime->assessment_id;
    $this->assessment = Assessment::load_by_id($this->assessment_id);

    $this->load_structure($this->assessment_id);
    $this->load_totals();
    $this->obtain_variables();
    $this->get_permissions();
    $this->time_left = $this->get_time_left();
    $this->save = $save;
    if($save)
    {
      if($this->can_edit)
      {
        // Validate, try to save and reload
        $this->check_variables();
        $this->save_results();
        $this->load_totals();
      }
      else
      {
        $this->error = array("You do not have permission to edit this assessment.");
      }
    }
    if(empty($this->assessment_results))
    {
      // No data yet, check punctuality
      $punctuality = $this->regime->get_punctuality($this->assessed_id);
      if($punctuality == 'early') $this->early = true;
      if($punctuality == 'late') $this->late = true;
    }
  }

  private function get_permissions()
  {
    // Failsafe
    $this->can_view = false;
    $this->can_edit = false;

    if(User::is_admin())
    {
      AssessmentCombined::get_admin_permissions();
    }
    if(User::is_student())
    {
      AssessmentCombined::get_student_permissions();
    }
    if(User::is_supervisor())
    {
      AssessmentCombined::get_supervisor_permissions();
    }
    if(User::is_staff())
    {
      AssessmentCombined::get_staff_permissions();
    }
  }

  private function get_admin_permissions()
  {
    $this->can_view = Policy::is_auth_for_student($this->assessed_id, "student", "viewAssessment");
    $this->can_edit = Policy::is_auth_for_student($this->assessed_id, "student", "editAssessment");
  }

  private function get_student_permissions()
  {
    // Usually they can view
    $this->can_view = true;
    if($this->assessed_id != User::get_id()) $this->can_view = false; // should never happen, but in case

    // Usually they cannot edit
    $this->can_edit = false;
    if($this->regime->assessor == 'student') $this->can_edit = true;
  }

  private function get_staff_permissions()
  {
    $academic_tutor = (Student::get_academic_user_id($this->assessed_id) == User::get_id());

    if($academic_tutor)
    {
      $this->can_view = true; // Academic tutors can see
      if($this->regime->assessor == 'academic') $this->can_edit = true;
    }
    else
    {
      if($this->regime->assessor != 'other') return; // Can't have rights...
      // Ok, is this staff member the designated "other"?
      require_once("model/AssessorOther.class.php");
      $assessorother = AssessorOther::load_where("where assessor=" . User::get_id() . " and assessed_id=" $this->assessed_id . " and regime_id=" . $this->regime->id;
      if($assessorother->id)
      {
        // They are
        $this->can_view = true;
        $this->can_edit = false;
      }
    }
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

    $assessed_name = $this->assessed_name;
    $assessor_name = $this->assessor_name;
    $assessment_name = $this->regime->student_description;
    if(empty($this->assessment_results))
    {
      $fields = array();
      $fields['regime_id']   = $this->regime_id;
      $fields['assessed_id'] = $this->assessed_id;
      $fields['assessor_id'] = $this->assessor_id;
      $fields['mark'] = $total;
      $fields['outof'] = $max;
      $fields['percentage'] = $percentage;
      $fields['assessed'] = $this->assessed_date;
      $fields['created'] = $now;
      $fields['modified'] = NULL;
      AssessmentTotal::insert($fields);
      $waf->log("assessment $assessment_name filed by $assessor_name for $assessed_name ($percentage%)");
    }
    else
    {
      $assessmenttotal = AssessmentTotal::load_where("where assessed_id=" . $this->assessed_id . " and regime_id= " . $this->regime_id);
      $assessmenttotal->assessor_id = $this->assessor_id;
      $assessmenttotal->mark = $total;
      $assessmenttotal->outof = $max;
      $assessmenttotal->percentage = $percentage;
      $assessmenttotal->modified = $now;
      $assessmenttotal->_update();
      $waf->log("assessment $assessment_name updated by $assessor_name for $assessed_name ($percentage%)");
    }
  }

  function get_time_left()
  {
    $now = date("YmdHis");
    $unixnow = time();
    $seconds = 0;

    if($this->assessment_results['created_unix'])
    {
      // We allow a 24 hour grace period for assessments to be altered,
      // after that, no luck... except for admins...
      $seconds = 60*60*24 - ($unixnow - $this->assessment_results['created_unix']);
      if($seconds < 0) $seconds = 0;
    }
    return($seconds);
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
    require_once("model/AssessmentResult.class.php");
    AssessmentResult::remove_where("where regime_id = " . $this->regime_id . " and assessed_id = " . $this->assessed_id);

    // Assessement date defaults to now if we don't get something more
    // appropriate
    $this->assessed_date = $now;
    foreach($this->structure as $item)
    {
      if($item->type == 'assesseddate')
      {
        // assessed dates are special, and stored in totals, not results
        $this->assessed_date = AssessmentCombined::make_datetime(AssessmentStructure::parse_date($this->variables[$item->name]));
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
    $this->save_totals();
  }

  function make_datetime($date)
  {
    $datetime = sprintf("%04s%02s%02s120000", $date['year'], $date['month'], $date['day']);
    return($datetime);
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
          $this->error = array_merge($this->error, $item->validate_variable($value));
        }
        else
        {
          // A missing variable is serious, possibly even a security breach
          $waf->security_log("missing variable" . $item->name .  " in script");
        }
      }
      else
      {
        $this->error = array_merge($this->error, $item->validate_variable($value));
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
    global $config;

    if(!$this->save) return ""; // Not trying to save yet.

    foreach($this->structure as $item)
    {
      if($item->name == $name)
      {
        if(count($item->validate_variable($this->variables[$name])))
        {
          if(isset($config['waf']['validation_image_fail']))
            return ("<img src=\"" . $config['waf']['validation_image_fail'] . "\" alt=\"error\" />&nbsp;");
          else return "<span class=\"assessment_error\">**</span>";
        }
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