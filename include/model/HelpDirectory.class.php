<?php

/**
* Encapsulates functionality to produce the dynamic help directory
* @package OPUS
*/

/**
* Encapsulates functionality to produce the dynamic help directory
*
* This establishes which administrator users are most helpful by context.
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see Admin.class.php
* @package OPUS
*
*/

class HelpDirectory
{
  /**
  * fetch all root admins that are available in the help directory
  *
  * @return an array of Admin objects
  */
  function get_root_admins()
  {
    require_once("model/Admin.class.php");

    $admins = Admin::get_all("where user_type='root' and help_directory='yes'");

    return($admins);
  }

  function get_institutional_admins()
  {
    require_once("model/Admin.class.php");
    return(Admin::get_all_by_institution(true));
  }

  function get_student_admins($student_user_id)
  {
    require_once("model/Admin.class.php");
    require_once("model/Student.class.php");
    $programme_id = Student::get_programme_id($student_user_id);
    $programme_admins = Admin::get_all_by_programme($programme_id, true);

    require_once("model/Programme.class.php");
    $school_id = Programme::get_school_id($programme_id);
    $school_admins = Admin::get_all_by_school($school_id, true);

    require_once("model/School.class.php");
    $faculty_id = School::get_faculty_id($school_id);
    $faculty_admins = Admin::get_all_by_faculty($faculty_id, true);

    return(HelpDirectory::merge_admins(array($programme_admins, $school_admins, $faculty_admins)));
  }

  /**
  * consolidates multiple lists of admins removing duplicates
  *
  * @param array $array_of_arrays an array of array of admins
  * @return a flat array of non duplicated admins
  */
  private function merge_admins($array_of_arrays)
  {
    $final_list = array();
    $seen_so_far = array();

    foreach($array_of_arrays as $array)
    {
      foreach($array as $admin)
      {
        if($seen_so_far[$admin->id]) continue; // already in list
        array_push($final_list, $admin);
        $seen_so_far[$admin->id] = true; // record as seen
      }
    }
    return($final_list);
  }

  function get_by_activity($activity_id)
  {
    require_once("model/Admin.class.php");

    $activity_admins = array();

    $sql = "select admins.* FROM adminactivity, admins, policy where " .
      "adminactivity.admin_id = admins.user_id and " .
      "adminactivity.activity_id = $activity_id and " .
      "admins.policy_id = policy.policy_id ORDER BY policy.priority, admins.surname";
  
    $result = mysql_query($sql)
      or print_mysql_error2("Unable to fetch course admin list.", $sql);
  
    while($row = mysql_fetch_array($result))
    {
      if(substr($row['status'], 'help')) array_push($activity_admins, $row);
    }
    mysql_free_result($result);
  
    $smarty->assign("activity_name", get_activity_name($activity_id));
    $smarty->assign("activity_admins", $activity_admins);
    $smarty->display("help/help_directory/activity_admins.tpl");
  }
};

?>