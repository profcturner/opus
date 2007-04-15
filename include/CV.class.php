<?php

/**
* CV
*
* A class to encapsulate CV handling within OPUS
*
* Right now this class really handles associated PDSystem templates, but in time
* we will reinstate internal CV handling here too.
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
* @todo need to add internal, (and other?) CV mechanisms, perhaps for 4.0.0
*
*/
require_once "CVGroups.class.php"

class CV
{
  /**
  * places information about CVs for a student in smarty assignments
  *
  * Several functions require detailed information about CVs the student
  * has completed from the PDSystem, which is then merged with information
  * from OPUS configuration to evaluate just what CVs are allowable for
  * applications.
  *
  * Arrays and variables are:
  * $cvs, which contains all allowed cvs, with approval information inserted
  * which contains fields $template_id, $name, $approved, $template_allowed, $problem
  * $group_name, the CV group name
  * $count_completed_cvs, the number of completed CVs (not necessarily valid)
  * $count_archived_cvs, the number of archived (custom CVs)
  * $student_id, the student_id
  * $invalid_cvs, an array of invalid CVs
  * $archived_cvs, an array of archived CVs
  *
  * @param int $student_id the OPUS student_id of the student
  * @return the $cvs array discussed above
  */
  function populate_smarty_arrays($student_id);
  {
    global $smarty;

    $completed_cvs = PDSystem::get_valid_templates($student_id);
    $archived_cvs = PDSystem::get_archived_cvs($student_id);
    $archived_cvs = $archived_cvs->xpath('//cv');
    $group_id = get_student_cvgroup($student_id);
    $group_name = backend_lookup("cvgroups", "name", "group_id", $group_id);
    $template_info = CVGroups::get_templates_for_group($group_id);

    $cvs = array();
    $invalid_cvs = array();
    foreach($completed_cvs as $completed_cv)
    {
      $template_id = (int) $completed_cv->id;
      $template_name = (string) $completed_cv->name;
      $cv['template_id'] = $template_id;
      $cv['name'] = $template_name;
      // Ok, it's complete, but if it's not in the group, we don't show it...
      $allowed = TRUE;
      $approved = CV::cv_approved($student_id, $template_id);
      $cv['approved'] = $approved;
      $cv['template_allowed'] = TRUE;

      // Is the template allowed?
      if(!$template_info[$template_id]['allow'])
      {
        $allowed = FALSE;
        $cv['problem'] = 'Template Not Allowed';
        $cv['template_allowed'] = FALSE;
      }
      // Is approval required?
      if($allowed && $template_info[$template_id]['requiresApproval'])
      {
        if(!$approved)
        {
          // Oops, change our mind
          $allowed = FALSE;
          $cv['problem'] = 'CV Not Approved';
        }
      }
      if($allowed) array_push($cvs, $cv);
      else array_push($invalid_cvs, $cv);
    }
    $smarty->assign("cvs", $cvs);
    $smarty->assign("group_name", $group_name);
    $smarty->assign("count_completed_cvs", count($completed_cvs));
    $smarty->assign("count_archived_cvs", count($archived_cvs));
    $smarty->assign("count_allowed_cvs", count($cvs));
    $smarty->assign("student_id", $student_id);
    $smarty->assign("invalid_cvs", $invalid_cvs);
    //$smarty->assign("completed_cvs", $completed_cvs);
    $smarty->assign("archived_cvs", $archived_cvs);
    //$smarty->assign("template_info", $template_info);

    return($cvs);
  }

  /**
  * checks if a given template is approved for use
  *
  * Note, that only some CV groups implement approval, so this call is
  * likely to return FALSE for such CVs, since no approval is required
  * and will have been filed.
  *
  * @param int $student_id the OPUS student_id of the student to check
  * @param int $template_id the PDSystem template to check approval for
  * @return TRUE if approval is filed, FALSE otherwise (see notes)
  */
  function cv_approved($student_id, $template_id)
  {
    $sql = "select * from cv_approval where student_id=$student_id and template_id=$template_id";
    $result = mysql_query($sql)
      or print_mysql_error2("Could not check CV approval", $sql);
    $success = mysql_num_rows($result);
    mysql_free_result($result);
    return($success);
  }

  /**
  * record that the currently logged in user approves a CV template
  *
  * @param int $student_id the student_id (OPUS) of the student
  * @param int $template_id the template_id (PDSystem) to approve
  */
  function approve_cv($student_id, $template_id)
  {
    $datestamp = date("YmdHis");
    $approver_id = get_id();

    $sql = "insert into cv_approval (student_id, template_id, approver_id, datestamp) " .
      "values($student_id, $template_id, $approver_id, '$datestamp')";
    mysql_query($sql)
      or print_mysql_error2("Unable to approve CV", $sql);
  }

  /**
  * record that the currently logged in user revokes a CV template
  *
  * @param int $student_id the student_id (OPUS) of the student
  * @param int $template_id the template_id (PDSystem) to approve
  */
  function revoke_cv($student_id, $template_id)
  {
    $sql = "delete from cv_approval where student_id=$student_id and template_id=$template_id";
    mysql_query($sql)
      or print_mysql_error2("Unable to revoke approval of CV", $sql);
      
    student_CV();
  }
}

?>
