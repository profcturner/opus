<?php
/**
* Encapulates interfaces to several CV sources
* @package OPUS
*/

/**
* Encapulates interfaces to several CV sources
*
* OPUS can obtain CVs from
* <ul>
*  <li>its internal CV store for students, derived from PDSystem code;</li>
*  <li>PDSystem stored CVs, identical to that above, but remote;</li>
*  <li>PDSystem template based CVs, that are almost miny e-Portfolios.</li>
* </ul>
* This class is designed to provide more seamless access to these.
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see CV.class.php
* @see PDSystem.class.php
* @package OPUS
*
*/

class CVCombined
{

  /**
  * fetches a list of all CVs from several sources for a given student
  *
  * CVs might come from the internal CV store, the PDSystem template driven
  * ones, as well as PDSystem archive (hashed) ones.
  *
  * @param int $student_id the user of the id for which CVs must be fetched
  * @param boolean $filter whether the list should be filtered for permission to use
  */
  function fetch_cvs_for_student($student_id, $filter = false)
  {
    $final_cvs = array();

    $student_id = (int) $student_id; // security

    // Get internal CVs
    require_once("model/CV.class.php");
    $internal_cvs = CV::get_all("where user_id=$student_id");

    foreach($internal_cvs)
    {
      $new_cv = "internal:hash:"; // need's the hash
      if($filter && !CVCombined::check_cv_permission($student_id, $new_cv)) continue; // skip this
      array_push($final_cvs, $new_cv);
    }

    // Get PDS Template CVs
    require_once("model/PDSystem.class.php");
    $valid_templates = PDSystem::get_valid_templates($student_id);
    foreach($valid_templates as $template)
    {
      $new_cv = "pdsystem:template:" . $template['id'];
      if($filter && !CVCombined::check_cv_permission($student_id, $new_cv)) continue; // skip this
      array_push($final_cvs, $new_cv);
    }

    // Finally CVs from PDSystem CV store
    $pdsystem_archived = PDSystem::get_archived($student_id);
    foreach($pdsystem_archived as $archived)
    {
      $new_cv = "pdsystem:hash:";
      if($filter && !CVCombined::check_cv_permission($student_id, $new_cv)) continue; // skip this
      array_push($final_cvs, $new_cv);
    }
    return $final_cvs;
  }

  /**
  * establishes if a student has permission to use a given CV to apply for placement
  *
  * @param int $student_id the user_id of the student
  * @param string $cv_ident the identifier user for the CV
  * @return true if permission exist, false otherwise
  * @todo badly needs caching of a lot of this information, return to that SOON
  */
  function check_cv_permission($student_id, $cv_ident)
  {
    $cv_group_id = Student::get_cv_group_id($student_id);
    require_once("model/CVGroup.class.php");

    $allowAllTemplates = CVGroup::check_permission($group_id, "allowAllTemplates");
    $allowCustom = CVGroup::check_permission($group_id, "allowCustom");
    $template_permissions = CVGroupTemplate::get_template_permissions_by_group($group_id);

    $cv_ident_parts = explode(":", $cv_ident);
    switch($cv_ident_parts[0])
    {
      case "internal":
        return $allowCustom; // Crude for now
        break;

      case "pdsystem":
        switch($cv_ident_parts[1])
        {
          case "hash":
            return $allowCustom;
            break;

          case "template":
            if($allowAllTemplates) return true;
            if(!in_array("allow", $template_permissions[$cv_ident_parts[2]])) return false;
            if(in_array("requiresApproval", $template_permissions[$cv_ident_parts[2]])) return CVApproval::get_approval($student_id, $cv_ident);
            return true;
            break;
        }
        break;

      default:
        return false; // Unknown type
        break;
    }
  }

  /**
  * email a CV from a student to a given recipient
  *
  */
  function email_cv($student_id, $recipient_id, $vacancy_id = 0)
  {
    $student_id = (int) $student_id;
    $vacancy_id = (int) $vacancy_id;

    // First find what CV was used
    require_once("model/Application.class.php");
    $application = Application::load_where("student_id = $student_id and vacancy_id = $vacancy_id");

    // Now fetch the raw data
    $cv_blob = CVCombined::get_cv_blob($application->cv_ident);

    // Now the email address
    require_once("model/User.class.php");
    $recipient = User::load_by_id($recipient_id);

    // Finally package it up
    require_once("model/OPUSMail.class.php");
    $mail = new OPUSMail($recipient->email, $recipi

  }

};

?>