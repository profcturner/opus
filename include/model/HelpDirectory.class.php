<?php

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