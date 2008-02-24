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

    return(array("none:none:none"=>"None Available")); // needed until PDS testing is done

    $student_id = (int) $student_id; // security

    // Get internal CVs
    require_once("model/CV.class.php");
    $internal_cvs = CV::get_all("where user_id=$student_id");

    foreach($internal_cvs as $cv)
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