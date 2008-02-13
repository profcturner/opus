<?php

/**
* Handles the various things associated with channels
* @package OPUS
*/
require_once("dto/DTO_ChannelAssociation.class.php");
/**
* Handles the various things associated with channels
*
* In particular, programmes, schools, assessmentgroups or activitytypes
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/

class ChannelAssociation extends DTO_ChannelAssociation 
{
  var $permission = "";  // Either enable or disable
  var $type = "";        // Type of association
  var $object_id = "";   // Object id
  var $priority = 0;     // Priority
  var $channel_id;       // The channel to associate with


  static $_field_defs = array(
    'permission'=>array('type'=>'list', 'list'=>array('enable', 'disable'), 'header'=>true),
    'type'=>array('type'=>'list', 'list'=>array('course','school','assessmentgroup','activity'), 'header'=>'true')
  );

  function __construct() 
  {
    parent::__construct('default');
  }

  /**
  * returns the statically defined field definitions
  */
  function get_field_defs()
  {
    return(self::$_field_defs);
  }

  function load_by_id($id) 
  {
    $channelassociation = new ChannelAssociation;
    $channelassociation->id = $id;
    $channelassociation->_load_by_id();
    return $channelassociation;
  }

  function insert($fields) 
  {
    $channelassociation = new ChannelAssociation;
    $channelassociation->_insert($fields);
  }

  function update($fields) 
  {
    $channelassociation = ChannelAssociation::load_by_id($fields[id]);
    $channelassociation->_update($fields);
  }

  /**
  * Wasteful
  */
  function exists($id) 
  {
    $channelassociation = new ChannelAssociation;
    $channelassociation->id = $id;
    return $channelassociation->_exists();
  }

  /**
  * Wasteful
  */
  function count($where_clause="") 
  {
    $channelassociation = new ChannelAssociation;
    return $channelassociation->_count($where_clause);
  }

  function get_all($where_clause="", $order_by="ORDER BY priority", $page=0)
  {
    global $config;
    $channelassociation = new ChannelAssociation;

    if ($page <> 0) {
      $start = ($page-1)*$config['opus']['rows_per_page'];
      $limit = $config['opus']['rows_per_page'];
      $channelassociations = $channelassociation->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $channelassociations = $channelassociation->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $channelassociations;
  }

  function get_id_and_field($fieldname, $where_clause="") 
  {
    $channelassociation = new ChannelAssociation;
    $channelassociation_array = $channelassociation->_get_id_and_field($fieldname, $where_clause);
    $channelassociation_array[0] = 'Global';
    return $channelassociation_array;
  }

  function remove($id=0) 
  {
    $channelassociation = new ChannelAssociation;
    $channelassociation->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {
    $channelassociation = new ChannelAssociation;
    return  $channelassociation->_get_fieldnames($include_id); 
  }

  function request_field_values($include_id = false) 
  {
    $fieldnames = ChannelAssociation::get_fields($include_id);
    $nvp_array = array();

    foreach ($fieldnames as $fn)
    {
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
    }

    return $nvp_array;
  }

  function get_all_extended($channel_id)
  {
    $channelassociation = new ChannelAssociation;
    return($channelassociation->_get_all_extended($channel_id));
  }

  function move_up($channel_id, $id)
  {
    $channelassociation = ChannelAssociation::load_by_id($id);
    $priority = $channelassociation->priority;
    $channelassociation->_move_up($channel_id, $priority);
  }

  function move_down($channel_id, $id)
  {
    $channelassociation = ChannelAssociation::load_by_id($id);
    $priority = $channelassociation->priority;
    $channelassociation->_move_down($channel_id, $priority);
  }

  function user_in_channel_association($user_id)
  {
    $in_channel = false;
    $object_id = $this->object_id;
    switch($this->type)
    {
      case 'programme':
        return(ChannelAssociation::user_in_channel_association_programme($object_id, $user_id));
        break;
      case 'school':
        return(ChannelAssociation::user_in_channel_association_school($object_id, $user_id));
        break;
      case 'assessmentgroup':
        return(ChannelAssociation::user_in_channel_association_assessmentgroup($object_id, $user_id));
        break;
      case 'activity':
        return(ChannelAssociation::user_in_channel_association_activity($object_id, $user_id));
        break;
    }
  }

  function user_in_channel_association_programme($programme_id, $user_id)
  {
    if(User::is_student($user_id))
    {
      require_once("model/Student.class.php");
      return(Student::get_programme_id($user_id) == $programme_id);
    }
    if(User::is_admin($user_id))
    {
      require_once("model/Policy.class.php");
      return(Policy::is_auth_for_programme($programme_id, "channel", "read"));
    }
    if(User::is_supervisor($user_id))
    {
      require_once("model/Supervisor.class.php");
      require_once("model/Student.class.php");
      return(Student::get_programme_id(Supervisor::get_supervisee_id($user_id)) == $programme_id);
    }
    // No staff code yet, nothing seems appropriate here...
    return false;
  }

  function user_in_channel_association_school($school_id, $user_id)
  {
    if(User::is_student($user_id))
    {
      require_once("model/Student.class.php");
      require_once("model/Programme.class.php");
      return(Programme::get_school_id(Student::get_programme_id($user_id)) == $school_id);
    }
    if(User::is_admin($user_id))
    {
      require_once("model/Policy.class.php");
      return(Policy::is_auth_for_school($school_id, "channel", "read"));
    }
    if(User::is_staff($user_id))
    {
      require_once("model/Staff.class.php");
      return(Staff::get_school_id($user_id) == $school_id);
    }
    if(User::is_supervisor($user_id))
    {
      require_once("model/Programme.class.php");
      require_once("model/Supervisor.class.php");
      require_once("model/Student.class.php");
      return(Programme::get_school_id(Student::get_programme_id(Supervisor::get_supervisee_id($user_id))) == $school_id);
    }
    return false;
  }

  function user_in_channel_association_assessmentgroup($group_id, $user_id)
  {
    $group_id = (int) $group_id;

    // For students, ask what the assessmentgroup is
    if(User::is_student($user_id))
    {
      return(Student::get_assessment_group_id($user_id) == $group_id);
    }
    // For others, take each programme and look at it...
    require_once("model/AssessmentGroupProgramme.class.php");
    $links = AssessmentGroupProgramme::get_all("where group_id=$group_id");
    foreach($links as $link)
    {
      if(ChannelAssociation::user_in_channel_association_programme($link->programme_id, $user_id)) return true;
    }
    return false;
  }

  function user_in_channel_association_activity($activity_id, $user_id)
  {
    $user_id = (int) $user_id;

    // This is only for HR contacts...
    if(!User::is_company($user_id)) return false;
    $in_activity = false;

    // What companies does this user act for?
    require_once("model/CompanyContact.class.php");
    require_once("model/CompanyActivity.class.php");
    $links = CompanyContact::get_all("where contact_id=$user_id");
    foreach($links as $link)
    {
      $company_id = $link->company_id;
      if(CompanyActivity::count("where company_id=$company_id and activity=$activity_id")) return true;
    }
    return false;
  }
}
?>