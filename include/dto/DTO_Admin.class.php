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

  function _get_all_by_faculty($faculty_id = 0)
  {
    global $waf;

    //require_once("model/FacultyAdmin.class.php");

    $con = $waf->connections[$this->_handle]->con;

    try
    {
      $sql = $con->prepare("select admin_id from admin left join facultyadmin on admin.user_id = facultyadmin.admin_id where faculty_id=?");
      $sql->execute(array($faculty_id));

      while ($results_row = $sql->fetch(PDO::FETCH_ASSOC))
      {
        $admin_id = $results_row["admin_id"];
        $object_array[] = $this->load_by_user_id($admin_id);
      }
    }
    catch (PDOException $e)
    {
      $this->_log_sql_error($e, "Admin", "_get_all_by_faculty()");
    }
    return $object_array; 
  }

  function _get_all_by_school($school_id = 0)
  {
    global $waf;

    //require_once("model/FacultyAdmin.class.php");

    $con = $waf->connections[$this->_handle]->con;

    try
    {
      $sql = $con->prepare("select admin_id from admin left join schooladmin on admin.user_id = schooladmin.admin_id where school_id=?");
      $sql->execute(array($school_id));

      while ($results_row = $sql->fetch(PDO::FETCH_ASSOC))
      {
        $admin_id = $results_row["admin_id"];
        $object_array[] = $this->load_by_user_id($admin_id);
      }
    }
    catch (PDOException $e)
    {
      $this->_log_sql_error($e, "Admin", "_get_all_by_school()");
    }
    return $object_array; 
  }

  function _get_all_by_programme($programme_id = 0)
  {
    global $waf;

    //require_once("model/FacultyAdmin.class.php");

    $con = $waf->connections[$this->_handle]->con;

    try
    {
      $sql = $con->prepare("select admin_id from admin left join programmeadmin on admin.user_id = programmeadmin.admin_id where programme_id=?");
      $sql->execute(array($programme_id));

      while ($results_row = $sql->fetch(PDO::FETCH_ASSOC))
      {
        $admin_id = $results_row["admin_id"];
        $object_array[] = $this->load_by_user_id($admin_id);
      }
    }
    catch (PDOException $e)
    {
      $this->_log_sql_error($e, "Admin", "_get_all_by_programme()");
    }
    return $object_array; 
  }

}

?>