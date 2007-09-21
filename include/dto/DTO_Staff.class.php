<?php
/**
 * @package OPUS
 *
 *
 */
require_once("dto/DTO.class.php");

class DTO_Staff extends DTO {

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
      $sql = $con->prepare("SELECT staff.id FROM `staff` left join user on staff.user_id = user.id $where_clause $order_by LIMIT $start, $limit;");
      $sql->execute();

      while ($results_row = $sql->fetch(PDO::FETCH_ASSOC))
      {
        $id = $results_row["id"];
        $object_array[] = $this->load_by_id($id, $parse);
      }
    }
    catch (PDOException $e)
    {
      $this->_log_sql_error($e, "staff", "_get_all()");
    }
    return $object_array; 
  }

}

?>