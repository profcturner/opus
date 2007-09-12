<?php

/**
* The model object for Schools
* @package OPUS
*/
require_once("dto/DTO_Channelassociation.class.php");

/**
* The Channelassociation model class
*/
class Channelassociation extends DTO_Channelassociation 
{
  var $permission = "";  // Either enable or disable
  var $type = "";        // Type of association
  var $object_id = "";   // Object id
  var $priority = 0;     // Priority
  var $channel_id;       // The channel to associate with


  static $_field_defs = array(
    'permission'=>array('type'=>'list', 'list'=>array('enable', 'disable'), 'header'=>true),
    'type'=>array('type'=>'list', 'list'=>array('course','school','assessmentgroup','activity'), 'header'=>'true'))
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
    $channelassociation = new Channelassociation;
    $channelassociation->id = $id;
    $channelassociation->_load_by_id();
    return $channelassociation;
  }

  function insert($fields) 
  {
    $channelassociation = new Channelassociation;
    $channelassociation->_insert($fields);
  }
  
  function update($fields) 
  {
    $channelassociation = Channelassociation::load_by_id($fields[id]);
    $channelassociation->_update($fields);
  }
  
  /**
  * Wasteful
  */
  function exists($id) 
  {
    $channelassociation = new Channelassociation;
    $channelassociation->id = $id;
    return $channelassociation->_exists();
  }
  
  /**
  * Wasteful
  */
  function count() 
  {
    $channelassociation = new Channelassociation;
    return $channelassociation->_count();
  }

  function get_all($where_clause="", $order_by="ORDER BY priority", $page=0)
  {
    $channelassociation = new Channelassociation;
    
    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $channelassociations = $channelassociation->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $channelassociations = $channelassociation->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $channelassociations;
  }

  function get_id_and_field($fieldname) 
  {
    $channelassociation = new Channelassociation;
    $channelassociation_array = $channelassociation->_get_id_and_field($fieldname);
    $channelassociation_array[0] = 'Global';
    return $channelassociation_array;
  }


  function remove($id=0) 
  {  
    $channelassociation = new Channelassociation;
    $channelassociation->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {  
    $channelassociation = new Channelassociation;
    return  $channelassociation->_get_fieldnames($include_id); 
  }
  function request_field_values($include_id = false) 
  {
    $fieldnames = Channelassociation::get_fields($include_id);
    $nvp_array = array();
 
    foreach ($fieldnames as $fn) {
 
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
 
    }

    return $nvp_array;

  }

  function user_in_channel_association($user_id)
  {
    $in_channel = false;

    switch($this->type)
    {
      // Called course for historial reasons in the schema
      case 'course':
        return($this->user_in_channel_association_programme($user_id));
        break;

      case 'school':
        return($this->user_in_channel_association_school($user_id));
        break;

      case 'assessmentgroup':
        return($this->user_in_channel_association_assessmentgroup($user_id));
        break;

      case 'activity':
        return($this->user_in_channel_association_activity($user_id));
        break;
    }
  }

  function user_in_channel_association_programme($user_id)
  {
    if($user_id == 0) $real_user_id = User::get_id();
    else $real_user_id = $user_id;

    if(User::is_student($user_id))
    {
      require_once("model/Student.class.php");
      return(Student::get_programme($real_user_id)->id == $this->object_id);
    }
    if(User::is_admin($user_id))
    {
      return(Policy::is_auth_for_programme($this->object_id, "channel", "read"));
    }
    if(User::is_supervisor($user_id))
    {
      require_once("model/Student.class.php");
      require_once("model/Supervisor.class.php");
      return(Student::get_programme(Supervisor::get_student_id($real_user_id)) == $this->object_id);
    }
    // No staff code yet, nothing seems appropriate here...
    return false;
  }


  function user_in_channel_association_school($user_id)
  {
    if($user_id == 0) $real_user_id = User::get_id();
    else $real_user_id = $user_id;

    if(User::is_student($user_id))
    {
      require_once("model/Student.class.php");
      return(Student::get_school($real_user_id)->id) == $this->object_id);
    }
    if(User::is_admin($user_id))
    {
      return(Policy::is_auth_for_school($this->object_id, "channel", "read"));
    }
    if(User::is_staff($user_id))
    {
      require_once("model/Staff.class.php");
      return(Staff::get_school($real_user_id)->id == $this->object_id);
    }
    if(User::is_supervisor($user_id))
    {
      require_once("model/Student.class.php");
      require_once("model/Supervisor.class.php");
      return(Student::get_school(Supervisor::get_student_id($real_user_id)) == $this->object_id);
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