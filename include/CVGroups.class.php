<?php

/**
* SQL backend functions for CV Group handling
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @version 3.0
* @package OPUS
*/
class CVGroups
{
  function get_core_settings($group_id)
  {
    $sql = "select * from cvgroups where group_id=$group_id";
    $result = mysql_query($sql)
      or print_mysql_error2("Unable to fetch core cvgroup settings", $sql);
    $settings = mysql_fetch_array($result);
    mysql_free_result($result);
    return($settings);
  }

  function get_templates_for_group($group_id)
  {
    $sql = "select * from cvgrouptemplate where group_id=$group_id";
    $result = mysql_query($sql)
      or print_mysql_error2("Unable to get cv group templates", $sql);
    $groups = array();
    
    while($row = mysql_fetch_array($result))
    {
      $group = array();
      $options = explode(',', $row['settings']);
      
      $group['allow'] = in_array('allow', $options);
      $group['requiresApproval'] = in_array('requiresApproval', $options);
      
      $groups[$row['template_id']] = $group;
    }
    mysql_free_result($result);
    return($groups);
  }
}

?>