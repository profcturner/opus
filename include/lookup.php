<?php

/**
**	lookup.php
**
** This file provides much of the code that allows human
** readable names of various resources to be found.
**
** Initial coding : Colin Turner
**
*/


function get_supervisee_id($supervisor_id)
{
  // Work out which placement this person cares for
  $login_name = get_login_name($supervisor_id);
  $placement_id = str_replace("supervisor_", "", $login_name);

  return(backend_lookup("placement", "student_id", "placement_id", $placement_id));

}

function get_language_name($lang_id)
{
  $query = sprintf("SELECT language FROM languages WHERE language_id=%s",
                   $lang_id);
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch language name", $query);

  $row = mysql_fetch_row($result);
  mysql_free_result($result);

  return($row[0]);
}

/**
* gets the channel name if it exists
* @param integer $channel_id the unique channel id
* @return the name of the channel, or a "None" string if it does not exist
*/
function get_channel_name($channel_id)
{
  if(empty($channel_id)) return("None");
  return(backend_lookup("channels", "name", "channel_id", $channel_id));
}


function get_placement_year($student_id)
{
  if(empty($student_id)) return(0);
  return(backend_lookup("students", "year", "user_id", $student_id));
}

function get_vacancy_description($vacancy_id)
{
  if(empty($vacancy_id)) return("");
  return(backend_lookup("vacancies", "description", "vacancy_id", $vacancy_id));

}


function get_policy_name($policy_id)
{
  if(empty($policy_id)) return("No policy defined");
  return(backend_lookup("policy", "descript", "policy_id", $policy_id));
}

function get_academic_tutor($student_id)
{
  if(empty($student_id)) return("No student id given");
  return(backend_lookup("staffstudent", "staff_id", "student_id", $student_id));
}

function get_assessment_description($assessment_id)
{
  if(empty($assessment_id)) return("No assessment description");
  return(backend_lookup("assessment", "description", "assessment_id", $assessment_id));
}

function get_school_name($id_match)
{
  if(empty($id_match)) return("No school selected");
  return(backend_lookup("schools", "school_name", "school_id", $id_match));
}


function get_course_name($id_match)
{
  if(empty($id_match)) return("No course selected");
  return(backend_lookup("courses", "course_name", "course_id", $id_match));
}

function get_course_code($id_match)
{
  if(empty($id_match)) return("No course selected");
  return(backend_lookup("courses", "course_code", "course_id", $id_match));
}

function get_course_id($id_match)
{
  if(empty($id_match)) return("No student selected");
  return(backend_lookup("cv_pdetails", "course", "id", $id_match));
}

function get_school_id($id_match)
{
  if(empty($id_match)) return("No course selected");
  return(backend_lookup("courses", "school_id", "course_id", $id_match));
}


function get_user_name($id_match)
{
  return(backend_lookup("id", "real_name", "id_number", $id_match));
}

function get_login_name($id_match)
{
  return(backend_lookup("id", "username", "id_number", $id_match));
}


function get_student_for_link($id_match)
{
  return(backend_lookup("cv_edetails", "id", "link_no", $id_match));
}


function get_company_name($id_match)
{
  return(backend_lookup("companies", "name", "company_id", $id_match));
}

function get_activity_name($id_match)
{
  return(get_vacancy_name($id_match));
}

// deprecated
function get_vacancy_name($id_match)
{
  return(backend_lookup("vacancytype", "name", "vacancy_id", $id_match));
}


function get_contact_id($id_match)
{
  return(backend_lookup("contacts", "contact_id", "user_id", $id_match));
}

function get_default_cvtemplate($student_id)
{
  $cvgroup_id = get_student_cvgroup($student_id);
  $template_id = backend_lookup("cvgroups", "default_template", "group_id", $cvgroup_id);

  return($template_id);

}

function get_user_details($user_id)
{
  $type = backend_lookup("id", "user", "id_number", $user_id);
  switch($type)
  {
  case "":
    return FALSE;
  case "student":
    $table = "cv_pdetails";
    $key_field = "id";
    break;
  case "admin":
  case "root":
    $table = "admins";
    $key_field = "user_id";
    break;
  case "staff":
    $table = "staff";
    $key_field = "user_id";
    break;
  case "company":
    $table = "contacts";
    $key_field = "user_id";
    break;
  }
  $sql = "select title, firstname, surname, email from $table where $key_field=$user_id";
  $result = mysql_query($sql)
    or print_mysql_error2("Unable to extract full name", $sql);
  $details = mysql_fetch_array($result);
  mysql_free_result($result);
  return($details);
}


function get_student_cvgroup($id_match)
{
  $course = get_student_course($id_match);
  if(empty($course)) return 0;
  $group  = backend_lookup("cvgroupcourse", "group_id", "course_id", $course);
  if(empty($group)) $group=1;
  return($group);
}


/**
* fetches the correct assessment group for a student
*
* This depends upon the student's course, placement year
* and the membership of that course to one or more assessment groups
* (which since v3 can change over time)
*
* @param integer $student_id the OPUS user id of the student
* @return the assessment group on success, or 1 on failure or if there is no assessment group 
*/
function get_student_assessmentgroup($student_id)
{
  $default_id = 1; // The default assessment regime
  
  // We need the student's course
  $course_id = get_student_course($student_id);
  if(empty($course_id)) return $default_id; // No course, no idea!
  // But also (since v3) their placement year
  $year = get_placement_year($student_id);

  // First look for an explict bounded match
  $sql = "select group_id from assessmentgroupcourse where " .
    "course_id = $course_id and $year >= startyear and $year <= endyear";
  $result = mysql_query($sql)
    or print_mysql_error2("Failed to get bounded assessmentgroup", $sql);
  $row = mysql_fetch_array($result);
  mysql_free_result($result);
  
  if($row['group_id']) return($row['group_id']); // success
  
  // Ok, look for a match with an endpoint only
  $sql = "select group_id from assessmentgroupcourse where " .
    "course_id = $course_id and startyear IS NULL and $year <= endyear";
  $result = mysql_query($sql)
    or print_mysql_error2("Failed to get upper bounded assessmentgroup", $sql);
  $row = mysql_fetch_array($result);
  mysql_free_result($result);
  
  if($row['group_id']) return($row['group_id']); // success
  
  // Ok, look for a match with an endpoint only
  $sql = "select group_id from assessmentgroupcourse where " .
    "course_id = $course_id and startyear <= $year and endyear IS NULL";
  $result = mysql_query($sql)
    or print_mysql_error2("Failed to get upper bounded assessmentgroup", $sql);
  $row = mysql_fetch_array($result);
  mysql_free_result($result);
  
  if($row['group_id']) return($row['group_id']); // success
  
  // Ok, look for a match with no endpoints
  $sql = "select group_id from assessmentgroupcourse where " .
    "course_id = $course_id and startyear IS NULL and endyear IS NULL";
  $result = mysql_query($sql)
    or print_mysql_error2("Failed to get unbounded assessmentgroup", $sql);
  $row = mysql_fetch_array($result);
  mysql_free_result($result);
  
  if($row['group_id']) return($row['group_id']); // success
  
  
  // Bail with the default
  return($default_id);
}


function get_cassessment_description($cassessment_id)
{
  return(backend_lookup("assessmentregime", "student_description", "cassessment_id", $cassessment_id));
}

function get_student_course($id_match)
{
  return(backend_lookup("cv_pdetails", "course", "id", $id_match));
}

function get_student_status($id_match)
{
  return(backend_lookup("students", "status", "user_id", $id_match));
}

function get_cv_name($id_match)
{
  if($id_match == 0) return("Classic CV system");
  return(backend_lookup("ocvtemplatedescription", "templatename", "templateid", $id_match));
}

function get_mime_type($id_match)
{
  return(backend_lookup("mime_types", "type", "mime_id", $id_match));
}

function get_category_name($id_match)
{
  return("To do...");
}

?>