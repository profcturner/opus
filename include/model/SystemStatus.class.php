<?php

/**
* Handling for the System Status page
* @package OPUS
*/

/**
* This class encapsulates the required handling for the system status page
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/
class SystemStatus
{
  function get_root_users()
  {
    require_once("model/Admin.class.php");
    return(Admin::get_all("where user_type='root'", "order by last_time desc"));
  }

  function get_admin_users($max_users)
  {
    require_once("model/Admin.class.php");
    return(Admin::get_all("where user_type='admin'", "order by last_time desc", 0, $max_users));
  }

  function get_admin_headings()
  {
    return array(
      'username'=>array('type'=>'text','size'=>30, 'header'=>true),
      'real_name'=>array('type'=>'text','size'=>30, 'header'=>true, 'title'=>'Name'),
      'position'=>array('type'=>'text','size'=>30, 'header'=>true),
      'online'=>array('type'=>'list','values'=>array('no','yes'), 'header'=>true),
      'last_time'=>array('type'=>'text','size'=>30, 'header'=>true, 'title'=>'Last Access')
    );
  }
}

?>