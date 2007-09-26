<?php
/**
 * @package OPUS
 *
 *
 */
require_once("dto/DTO.class.php");

class DTO_Admin extends DTO {

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

  function _get_all($where_clause="", $order_by="order by user.lastname", $start=0, $limit=MAX_ROWS_RETURNED, $parse = False) 
  {
    global $waf;
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

  function _get_all_by_faculty($faculty_id)
  {
    return($this->_get_all_by_level("faculty", $faculty_id));
  }

  function _get_all_by_school($school_id)
  {
    return($this->_get_all_by_level("school", $school_id));
  }

  function _get_all_by_programme($programme_id)
  {
    return($this->_get_all_by_level("programme", $programme_id));
  }

  function _get_all_by_level($level, $level_id = 0)
  {
    global $waf;

    require_once("model/Policy.class.php");

    $con = $waf->connections[$this->_handle]->con;

    $tablename = $level . "admin";
    try
    {
      $sql = $con->prepare("select admin_id, $tablename.policy_id as level_policy_id from admin left join $tablename on admin.user_id = $tablename.admin_id where $level" . "_id=?");
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
        $object_array[] = $user;
      }
    }
    catch (PDOException $e)
    {
      $this->_log_sql_error($e, "Admin", "_get_all_by_level($level, $level_id)");
    }
    return $object_array; 
  }


  function _get_user_id_and_name($where_clause)
  {
    global $waf;
    $con = $waf->connections[$this->_handle]->con;

    $final_array = array();
    try
    {
      $sql = $con->prepare("select user.* from admin left join user on admin.user_id = user.id $where_clause");
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