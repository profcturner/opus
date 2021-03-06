<?php

/**
* DTO handling for Admin
* @package OPUS
*/
require_once("dto/DTO.class.php");
/**
* DTO handling for Admin
*
* Mainly, this class provides logical joining of data from both the
* user and admin tables.
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see Admin.class.php
* @see User.class.php
* @package OPUS
*
*/

class DTO_Admin extends DTO
{

  function __construct($handle='default') 
  {
    parent::__construct($handle);
  }

  function _load_by_id($id=0)
  {
    require_once("model/User.class.php");
    parent::_load_by_id($id);

    $user = User::load_by_id($this->user_id);
    $fields = $user->_get_fieldnames(false);

    foreach($fields as $field)
    {
      $this->$field = $user->$field;
    }

    require_once("model/Policy.class.php");
    $this->_policy_id = Policy::get_name($this->policy_id);
  }

  function _load_by_user_id($user_id=0)
  {
    require_once("model/User.class.php");
    $this->user_id = $user_id;
    $this->_load_by_field("user_id");

    $user = User::load_by_id($user_id);
    $fields = $user->_get_fieldnames(false);

    foreach($fields as $field)
    {
      $this->$field = $user->$field;
    }

    require_once("model/Policy.class.php");
    $this->_policy_id = Policy::get_name($this->policy_id);
  }

  function _count($where_clause="")
  {
    $waf =& UUWAF::get_instance();

    $con = $waf->connections[$this->_handle]->con;

    try
    {
      $sql = $con->prepare("SELECT COUNT(*) FROM `admin` left join user on admin.user_id = user.id $where_clause;");
      $sql->execute();

      $results_row = $sql->fetch(PDO::FETCH_NUM);
    }
    catch (PDOException $e)
    {
      $this->_log_sql_error($e, "Admin", "_count($where_clause)");
    }
    return $results_row[0];
  }

  function _get_all($where_clause="", $order_by="order by user.lastname", $start=0, $limit=MAX_ROWS_RETURNED, $parse = False) 
  {
    $waf =& UUWAF::get_instance();
    $con = $waf->connections[$this->_handle]->con;

    if($waf->waf_debug)
    {
      $waf->log("$class::_get_all() called [$where_clause:$order_by:$start:$limit]", PEAR_LOG_DEBUG, "waf_debug");
    }

    $object_array = array();
    if (!($start >= 0)) $start = 0; 

    try
    {
      $sql = $con->prepare("SELECT admin.id FROM `admin` left join user on admin.user_id = user.id $where_clause $order_by LIMIT $start, $limit;");
      $sql->execute();

      while ($results_row = $sql->fetch(PDO::FETCH_ASSOC))
      {
        $id = $results_row["id"];
        $object_array[] = $this->load_by_id($id, $parse);
      }
    }
    catch (PDOException $e)
    {
      $this->_log_sql_error($e, "admin", "_get_all()");
    }
    return $object_array; 
  }

  function _get_all_by_faculty($faculty_id, $help_directory = false)
  {
    return($this->_get_all_by_level("faculty", $faculty_id, $help_directory));
  }

  function _get_all_by_school($school_id, $help_directory = false)
  {
    return($this->_get_all_by_level("school", $school_id, $help_directory));
  }

  function _get_all_by_programme($programme_id, $help_directory = false)
  {
    return($this->_get_all_by_level("programme", $programme_id, $help_directory));
  }

  function _get_all_by_level($level, $level_id = 0, $help_directory = false)
  {
    $waf =& UUWAF::get_instance();

    require_once("model/Policy.class.php");

    $con = $waf->connections[$this->_handle]->con;

    $object_array = array();
    $tablename = $level . "admin";
    try
    {
      $query = "select admin_id, $tablename.policy_id as level_policy_id, $tablename.id as level_id from admin left join $tablename on admin.user_id = $tablename.admin_id left join user on admin.user_id = user.id left join policy on policy.id = $tablename.policy_id where $level" . "_id=?";
      if($help_directory) $query .= " and admin.help_directory = 'yes'";
      $query .= " order by policy.priority, user.lastname"; // needs to be improved
      $sql = $con->prepare($query);
      $sql->execute(array($level_id));

      while ($results_row = $sql->fetch(PDO::FETCH_ASSOC))
      {
        $admin_id = $results_row["admin_id"];
        $user = $this->load_by_user_id($admin_id);
        if($results_row["level_policy_id"])
        {
          // Is there an override policy for this level?
          $user->_level_policy_name = Policy::get_name($results_row["level_policy_id"]);
        }
        else
        {
          $user->_level_policy_name = $user->_policy_id;
        }
        $user->_level_id = $results_row["level_id"];
        $object_array[] = $user;
      }
    }
    catch (PDOException $e)
    {
      $this->_log_sql_error($e, "Admin", "_get_all_by_level($level, $level_id)");
    }
    return $object_array; 
  }

  /**
  * retrieves the id from the link table for a given admin and level
  * 
  * for example, when the faculty admins are listed, the ids are those
  * of the admin users. This is normally good, but when trying to
  * remove the link in the table facultyadmin, the id from that table
  * needs to be retrieved.
  * 
  * @param $level is the name of the link type, e.g. faculty, school
  * @param $level_id is the id for that type, e.g. the faculty id
  * @parem $admin_id is the id from the admin table
  * @return the id from the link table
  */ 
  function _get_link_id_from_admin_and_level($level, $level_id = 0, $admin_id)
  {
    $waf =& UUWAF::get_instance();

    $con = $waf->connections[$this->_handle]->con;

    $tablename = $level . "admin";
    try
    {
      $query = "select $tablename.id from $tablename left join admin on admin.user_id = $tablename.admin_id where admin.id=? and $tablename.$level" . "_id=?";

      $sql = $con->prepare($query);
      $sql->execute(array($admin_id, $level_id));

      $results_row = $sql->fetch(PDO::FETCH_ASSOC);
      return($results_row['id']);
    }
    catch (PDOException $e)
    {
      $this->_log_sql_error($e, "Admin", "_get_link_id_from_admin_and_level($level, $level_id, $admin_id)");
    }
  }

  function _get_user_id_and_name($where_clause, $order_by="order by user.lastname")
  {
    $waf =& UUWAF::get_instance();
    $con = $waf->connections[$this->_handle]->con;

    $final_array = array();
    try
    {
      $sql = $con->prepare("select user.* from admin left join user on admin.user_id = user.id $where_clause $order_by");
      $sql->execute();

      while ($results_row = $sql->fetch(PDO::FETCH_ASSOC))
      {
        $final_array[$results_row['id']] = $results_row['lastname'] . ", " . $results_row['salutation'] . " " . $results_row['firstname'];
      }
    }
    catch (PDOException $e)
    {
      $this->_log_sql_error($e, "Admin", "_get_user_id_and_name()");
    }
    return $final_array;
  }
}

?>