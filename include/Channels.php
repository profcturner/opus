<?php

class Channels
{

  function get_indexed_array($user_id = 0)
  {
    $query = "select * from channels order by name";
    $result = mysql_query($query)
      or print_mysql_error2("Unable to get channels", $query);
  
    $channels = array();
    $channels[0] = "No channel (global)";
    while($channel = mysql_fetch_array($result))
    {
      // Folks shouldn't see channels they are not in...
      if(!Channels::user_in_channel($channel["channel_id"], $user_id)) continue;
      $channels[$channel["channel_id"]] = $channel["name"];
    }
    mysql_free_result($result);
    return($channels);
  }
  
  /**
  * Checks to see if a user is "in" a channel
  *
  * @param integer $channel_id the channel to check against
  * @param integer $user_id optionally a user_id to check, otherwise the logged in user is checked
  * @return boolean answer
  */
  function user_in_channel($channel_id, $user_id = 0)
  {
    // Assume no...
    $in_channel = false;
    // Use the logged in user if no other is satisfied
    if(!$user_id) $user_id = get_id();
    //else die_gracefully("Debug: user_in_channel() does not support user_ids yet");
    
    $query = "select * from channelassociations where channel_id=$channel_id order by priority";
    $result = mysql_query($query)
      or print_mysql_error2("Unable to get channel associations", $query);
      
    while($association = mysql_fetch_array($result))
    {
      // Does this association include this person
      if(Channels::user_in_channel_association($association, $user_id))
      {
        if($association['permission'] == 'enable') $in_channel = true;
        else
        {
          // Admin users almost certainly don't want to be removed for this...
          if(!is_admin()) $in_channel = false;
        }
      }
    }
    return($in_channel);
  }
  
  function user_in_channel_association($association, $user_id)
  {
    $in_channel = false;
    $object_id = $association['object_id'];
    switch($association['type'])
    {
      case 'course':
        return(Channels::user_in_channel_association_course($object_id, $user_id));
        break;
        
      case 'school':
        return(Channels::user_in_channel_association_school($object_id, $user_id));
        break;
        
      case 'assessmentgroup':
        return(Channels::user_in_channel_association_assessmentgroup($object_id, $user_id));
        break;

      case 'activity':
        return(Channels::user_in_channel_association_activity($object_id, $user_id));
        break;
    }
  }
  
  function user_in_channel_association_course($course_id, $user_id)
  {
    if(is_student($user_id))
    {
      return(get_student_course($user_id) == $course_id);
    }
    if(is_admin($user_id))
    {
      return(is_auth_for_course($course_id, "channel", "read"));
    }
    if(is_supervisor($user_id))
    {
      return(get_student_course(get_supervisee_id($user_id)) == $course_id);
    }
    // No staff code yet, nothing seems appropriate here...
    return false;
  }
  
  function user_in_channel_association_school($school_id, $user_id)
  {
    if(is_student($user_id))
    {
      return(get_school_id(get_student_course($user_id)) == $school_id);
    }
    if(is_admin($user_id))
    {
      return(is_auth_for_school($school_id, "channel", "read"));
    }
    if(is_staff($user_id))
    {
      return(backend_lookup("staff", "school_id", "user_id", $user_id) == $school_id);
    }
    if(is_supervisor($user_id))
    {
      return(get_school_id(get_student_course(get_supervisee_id($user_id))) == $school_id);
    }
    return false;
  }

  function user_in_channel_association_assessmentgroup($group_id, $user_id)
  {
    // For students, ask what the assessmentgroup is
    if(is_student($user_id))
    {
      return(get_student_assessmentgroup($user_id) == $group_id);
    }
    // For others, take each course and look at it...
    $in_group = false;
    $query = "select distinct course_id from assessmentgroupcourse where group_id=$group_id";
    $result = mysql_query($query)
      or print_mysql_error2("Couldn't get course_id", $query);
    while(!$in_group && $row = mysql_fetch_row($result))
    {
      $course_id = $row[0];
      $in_group = Channels::user_in_channel_association_course($course_id, $user_id);
    }
    mysql_free_result($result);
    return($in_group);
  }

  function user_in_channel_association_activity($activity_id, $user_id)
  {
    // This is only for HR contacts...
    if(!is_company($user_id)) return false;
    $in_activity = false;

    // What companies does this user act for?
    $contact_id = get_contact_id($user_id);
    $query = "select company_id from companycontact where contact_id=$contact_id";
    $result = mysql_query($query)
      or print_mysql_error2("Unable to fetch companies", $query);
    while(!$inactivity && $row = mysql_fetch_row($result))
    {
      $company_id = $row[0];
      //echo "Debug: Checking $company_id" . get_company_name($company_id) . "<br />";
      $in_activity = Channels::company_in_activity($company_id, $activity_id);
    }
    mysql_free_result($result);
    return($in_activity);    
  }

  function company_in_activity($company_id, $activity_id)
  {
    //echo "Debug: checking company $company_id for $activity_id<br />";
    $query = "select * from companyvacancy where company_id=$company_id and vacancy_id=$activity_id";
    //echo $query . "<br />";
    $result = mysql_query($query)
      or print_mysql_error2("Unable to check company activities", $query);
    $in_activity = mysql_num_rows($result);
    //echo "Debug : $in_activity rows<br />";
    mysql_free_result($result);
    return($in_activity);
  }

}

?>