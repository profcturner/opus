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
* @todo needs consolidated a bit now
* @todo need internal CV work
*
*/

class CVCombined
{
  /** @var string describe the origin of the cv e.g. pdsystem:template:12 */
  var $cv_ident;
  /** @var int the user_id of the student owning it */
  var $student_user_id;
  /** @var string any associated mime type */
  var $mime_type;
  /** @var string an appropriate description */
  var $description;
  /** @var boolean whether the cv is approved, note many programmes don't require this so it might not matter */
  var $approval;
  /** @var boolean whether the cv can be used */
  var $valid;
  /** @var string desciprion of any problem */
  var $problem;

  /**
  * fetches a list of all CVs from several sources for a given student
  *
  * CVs might come from the internal CV store, the PDSystem template driven
  * ones, as well as PDSystem archive (hashed) ones.
  *
  * @param int $student_id the user of the id for which CVs must be fetched
  */
  function fetch_cvs_for_student($student_id)
  {
    $final_cvs = array();
    $student_id = (int) $student_id; // security
    require_once("model/CVApproval.class.php");

    // Get internal CVs
    require_once("model/CV.class.php");
    $internal_cvs = CV::get_all("where user_id=$student_id");

    foreach($internal_cvs as $cv)
    {
      $new_cv = new CVCombined;
      $new_cv->cv_ident = "internal:hash:"; // need's the hash
      $new_cv->student_user_id = $student_id;
      $new_cv->mime_type = "unknown"; // needs work
      $new_cv->description = $internal_cv->description;
      $new_cv->valid = CVCombined::check_cv_permission($student_id, $new_cv->cv_ident, &$problem);
      $new_cv->valid = $problem;
      $new_cv->approval = CVApproval::check_approval($student_id, $new_cv->cv_ident);
      array_push($final_cvs, $new_cv);
    }

    // Get PDS Template CVs
    require_once("model/PDSystem.class.php");
    $template_cvs = PDSystem::get_cv_status($student_id);
    foreach($template_cvs as $template)
    {
      $new_cv = new CVCombined;
      $new_cv->cv_ident = "pdsystem:template:" . $template['template_id'];
      $new_cv->student_user_id = $student_id;
      $new_cv->mime_type = "application/pdf";
      $new_cv->description = PDSystem::get_template_name($template['template_id']) . " (PDSystem Template)";
      if($template['cv_submission_status'] != 'COMPLETE')
      {
        $new_cv->valid = false;
        $new_cv->problem = "Not Complete";
      }
      else
      {
        $new_cv->valid = CVCombined::check_cv_permission($student_id, $new_cv->cv_ident, &$problem);
      }
      $new_cv->approval = CVApproval::check_approval($student_id, $new_cv->cv_ident);
      array_push($final_cvs, $new_cv);
    }

    // Finally CVs from PDSystem CV store
    $pdsystem_archived = PDSystem::get_archived_cvs($student_id);
    foreach($pdsystem_archived as $archived)
    {
      $new_cv = new CVCombined;
      $new_cv->cv_ident = "pdsystem:hash:" . $archived['_hash'];
      $new_cv->student_user_id = $student_id;
      $new_cv->mime_type = $archived['_file_type'];
      $new_cv->description = trim($archived['title'] . " " . $archived['description']) . " (PDSystem Store)";
      $new_cv->valid = CVCombined::check_cv_permission($student_id, $new_cv->cv_ident, &$problem);
      $new_cv->approval = CVApproval::check_approval($student_id, $new_cv->cv_ident);
      array_push($final_cvs, $new_cv);
    }
    return $final_cvs;
  }

  /**
  * filters the cv_list and provides valid CVs suitable for a pull down box
  *
  * @param array $cv_list is an array of CVCombined objects
  * @return array with keys as cv_idents and values as descriptions
  */
  function convert_cv_list_to_options($cv_list)
  {
    $result = array();
    foreach($cv_list as $cv)
    {
      if(!$cv->valid) continue;
      $result[$cv->cv_ident] = $cv->description;
    }
    if(!count($result)) array_push($result, array('none:none:none'=>'No available CVs'));
    return($result);
  }

  /**
  * establishes if a student has permission to use a given CV to apply for placement
  *
  * @param int $student_id the user_id of the student
  * @param string $cv_ident the identifier user for the CV
  * @return true if permission exist, false otherwise
  * @todo badly needs caching of a lot of this information, return to that SOON
  */
  function check_cv_permission($student_id, $cv_ident, &$problem)
  {
    require_once("model/Student.class.php");
    $cv_group_id = Student::get_cv_group_id($student_id);
    require_once("model/CVGroup.class.php");
    require_once("model/CVGroupTemplate.class.php");

    $allowAllTemplates = CVGroup::check_permission($cv_group_id, "allowAllTemplates");
    $allowCustom = CVGroup::check_permission($cv_group_id, "allowCustom");
    $template_permissions = CVGroupTemplate::get_template_permissions_by_group($cv_group_id);

    $cv_ident_parts = explode(":", $cv_ident);
    switch($cv_ident_parts[0])
    {
      case "internal":
        if(!$allowCustom) $problem = "Custom CVs not allowed";
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
            if(!in_array("allow", $template_permissions[$cv_ident_parts[2]]))
            {
              $problem = "Disallowed Template";
              return false;
            }
            if(in_array("requiresApproval", $template_permissions[$cv_ident_parts[2]]))
            {
              $approval = CVApproval::get_approval($student_id, $cv_ident);
              if(!$approval)
              {
                $problem = "Approval Required";
                return false;
              }
            }
            return true;
            break;
        }
        break;

      default:
        $problem = "Unknown Type";
        return false; // Unknown type
        break;
    }
  }

  /**
  * email a CV used for a certain vacancy from a student to a given recipient
  *
  * @param int $recipient_user_id the user_id of the recipient
  * @param int $application_id the application that was used
  * @todo would be nice to drive this from a template
  */
  function email_cv($recipient_user_id, $application_id = 0)
  {
    $application_id = (int) $application_id;

    require_once("model/Application.class.php");
    $application = Application::load_by_id($application_id);
    $vacancy_id = $application->vacancy_id;
    $student_user_id = $application->student_id;

    if(!$application->id) return false; // no such application linking the two

    require_once("model/Vacancy.class.php");
    require_once("model/Company.class.php");
    $student_name = User::get_name($student_user_id);
    $vacancy_name = Vacancy::get_name($vacancy_id);

    if(!CVCombined::is_auth_to_view_cv($application->cv_ident, $student_user_id))
    {
      $waf->log("failed attempt to view CV for application by $student_name for $vacancy_name");
      return;
    }
    $cv = CVCombined::get_cv_blob($application->cv_ident, $student_user_id);
    if($cv == false)
    {
      return;
    }
    $cv_mime_type="application/pdf";
    $cv_format="pdf";
    $cv_ident_parts = explode(":", $application->cv_ident);
    if($cv_ident_parts[1] == 'hash')
    {
      $cv_mime_type = $application->archive_mime_type;
      $cv_format = "";
    }
    $waf->log("emailing CV for application by $student_name for $vacancy_name");

    // Now the email address and other things
    require_once("model/User.class.php");
    $recipient = User::load_by_id($recipient_id);

    $body = "Company : $company_name\nVacancy : $vacancy_name\nStudent: $student_name";

    // Finally package it up
    require_once("model/OPUSMail.class.php");
    $mail = new OPUSMail($recipient->email, "CV: $student_name", $body, "", $recipient->email);
    $mail->add_direct_attachment($cv, $cv_mime_type);
    $mail->send();
    // Make sure this is tagged as seen if need be
    Application::ensure_seen($application_id);
  }

  /**
  * attempts to view the cv associated with a particular application
  *
  * this does check security
  *
  * @param int $application_id the id of the application
  * @todo finish file extension to be more elegant for artefact stuff
  */
  function view_cv_for_application($application_id)
  {
    global $waf;

    $application_id = (int) $application_id;

    require_once("model/Application.class.php");
    $application = Application::load_by_id($application_id);

    if(!$application->id) return false; // no such application

    $student_user_id = $application->student_id;
    $vacancy_id = $application->vacancy_id;

    require_once("model/Vacancy.class.php");
    $student_name = User::get_name($student_user_id);
    $vacancy_name = Vacancy::get_name($vacancy_id);

    if(!CVCombined::is_auth_to_view_cv($application->cv_ident, $student_user_id))
    {
      $waf->log("failed attempt to view CV for application by $student_name for $vacancy_name");
      $waf->halt("error:cv:not_authorised");
    }
    $cv = CVCombined::get_cv_blob($application->cv_ident, $student_user_id);
    if($cv == false)
    {
      $waf->halt("error:cv:retrieval_failure");
    }
    $cv_mime_type="application/pdf";
    $cv_format="pdf";
    $cv_ident_parts = explode(":", $application->cv_ident);
    if($cv_ident_parts[1] == 'hash')
    {
      $cv_mime_type = $application->archive_mime_type;
      $cv_format = "";
    }
    $waf->log("viewing CV for application by $student_name for $vacancy_name");
    header("Content-type: $cv_mime_type");
    header("Content-Disposition: attachment; filename=\"$student_name.$cv_format\"");
    echo($cv);
    // Make sure this is tagged as seen if need be
    Application::ensure_seen($application_id);
  }

  /**
  * view a given CV
  *
  * @param int $student_user_id the user id of the student
  * @param string $cv_ident a field of the form source:type:id e.g. pdsystem:template:2
  */
  function view_cv($student_user_id, $cv_ident)
  {
    global $waf;

    $student_name = User::get_name($student_user_id);

    if(!CVCombined::is_auth_to_view_cv($cv_ident, $student_user_id))
    {
      $waf->halt("error:cv:not_authorised");
    }
    $cv = CVCombined::get_cv_blob($cv_ident, $student_user_id);
    if($cv == false)
    {
      $waf->halt("error:cv:retrieval_failure");
    }
    $cv_mime_type="application/pdf";
    $cv_format="pdf";
    $cv_ident_parts = explode(":", $cv_ident);
    if($cv_ident_parts[1] == 'hash')
    {
      require_once("model/PDSystem.class.php"); // bug?
      $cv_mime_type = PDSystem::get_artefact_mime_type($student_user_id, $cv_ident_parts[2]); // needs modded for internal
      $cv_format = "";
    }
    $waf->log("viewing CV for $student_name");
    header("Content-type: $cv_mime_type");
    header("Content-Disposition: attachment; filename=\"$student_name.$cv_format\"");
    echo($cv);
    // Make sure this is tagged as seen if need be
    Application::ensure_seen($application_id);
  }

  /**
  * fetches the actual CV data
  *
  * Note that this function does not perform authentication, that is done elsewhere
  *
  * @param string $cv_ident a field of the form source:type:id e.g. pdsystem:template:2
  * @param int $student_user_id the user id of the student
  * @return the blob if possible, otherwise false
  */
  private function get_cv_blob($cv_ident, $student_user_id)
  {
    $cv_ident_parts = explode(":", $cv_ident);
    switch($cv_ident_parts[0])
    {
      case "internal":
        require_once("model/Artefact.class.php");
        return(Artefact::load_by_hash($cv_ident_parts[2]));
        break;
      case "pdsystem":
        require_once("model/PDSystem.class.php");
        switch($cv_ident_parts[1])
        {
          case "template":
            return(PDSystem::fetch_template_cv($student_user_id, $cv_ident_parts[2]));
            break;

          case "hash":
            return(PDSystem::fetch_artefact_hash($cv_ident_parts[2]));
            break;
        }
        break;
      default:
        return false;
        break;
    }
  }

  function is_auth_to_view_cv($cv_ident, $student_user_id)
  {
    if(User::is_admin())
    {
      require_once("model/Policy.class.php");
      return(Policy::is_auth_for_student($student_user_id, "student", "viewCV"));
    }
    if(User::is_student())
    {
      return($student_user_id == User::get_id());
    }
    if(User::is_company())
    {
      return(CVCombined::is_contact_auth_to_view_cv($cv_ident, $student_user_id));
    }
    return false; // Nobody else's business
  }

  private function is_contact_auth_to_view_cv($cv_ident, $student_user_id)
  {
    $contact_user_id = User::get_id();

    require_once("model/Student.class.php");
    $student = Student::load_by_user_id($student_user_id);
    $placement_status = $student->placement_status;

    require_once("model/Contact.class.php");
    $company_list = Contact::get_companies_for_contact($contact_user_id);
    $company_ids = array_keys($company_list);

    require_once("model/Application.class.php");
    $application_list = Application::get_all("where student_id=$student_user_id");

    foreach($application_list as $application)
    {
      // Ignore if not for one of our companies
      if(!in_array($application->company_id, $company_ids)) continue;
      // Or if the request is for a different ident
      if($application->cv_ident != $cv_ident) continue;

      // Ok, it is... Required students give permission
      if($placement_status == 'Required') return true;

      // Otherwise, permission is only granted if we are placed with this company
      require_once("model/Placement.class.php");
      if(Placement::count("where company_id=" . $application->company_id . " and student_id=$student_user_id")) return true;
    }
    // No, then hard luck
    return(false);
  }

};

?>