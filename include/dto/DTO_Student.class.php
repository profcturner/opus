<?php

/**
* DTO handling for Student
* @package OPUS
*/
require_once("dto/DTO.class.php");
/**
* DTO handling for Student
*
* Performs logical joining of the student and user tables where required.
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see Student.class.php
* @package OPUS
*
*/

class DTO_Student extends DTO
{

  function __construct($handle) 
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

  function _get_all_extended($search, $year, $programmes, $sort, $other_options)
  {
    $waf =& UUWAF::get_instance();
    $con = $waf->connections[$this->_handle]->con;

    if(empty($programmes)) $programmes = array();
    if(empty($other_options)) $other_options = array();

    if(!in_array($sort, array('lastname', 'reg_number', 'placement_status', 'last_time')))
      $sort = 'lastname';

    // Form Search criteria string
    if(!empty($search))
    {
      $searchc .= " (lastname LIKE ? OR firstname LIKE ? OR reg_number LIKE ? OR quick_note LIKE ?)";
      $parameters = array("%$search%","%$search%","%$search%", "%$search%");
    }
    else
    {
      $searchc = "";
      $parameters = array();
    }

    if(!empty($year))
    {
      if(!empty($searchc)) $searchc .= " AND";
      $searchc .= " placement_year = ?";
      array_push($parameters, $year);
    }

    $full_query = "SELECT user.real_name as real_name, user.email, user.salutation, user.firstname, user.lastname, user.reg_number, user.last_time, student.* FROM student LEFT JOIN user ON student.user_id = user.id";
    if(!empty($searchc)) $full_query .= " WHERE $searchc";
    $full_query .= " ORDER BY `$sort`";
    if($sort != 'lastname') $full_query .= ", `lastname`";

    $object_array = array();

    try
    {
      $sql = $con->prepare($full_query);
      $sql->execute($parameters);

      while ($results_row = $sql->fetch(PDO::FETCH_ASSOC))
      {
        if(in_array($results_row["programme_id"], $programmes))
          array_push($object_array, $results_row);
      }
    }
    catch (PDOException $e)
    {
      $this->_log_sql_error($e, "Student", "_get_all_extended($search, $year)");
    }
    return $object_array;
  }

  function _get_all_by_initial($initial)
  {
    $waf =& UUWAF::get_instance();
    $con = $waf->connections[$this->_handle]->con;

    require_once("model/Policy.class.php");
    if(!preg_match('/^[A-Za-z]$/', $initial)) return array();

    $full_query = "SELECT user.real_name as real_name, user.email, user.salutation, user.firstname, user.lastname, user.reg_number, user.last_time, student.* FROM student LEFT JOIN user ON student.user_id = user.id where lastname like ? order by lastname";

    $object_array = array();

    try
    {
      $sql = $con->prepare($full_query);
      $sql->execute(array("$initial%"));

      while ($results_row = $sql->fetch(PDO::FETCH_ASSOC))
      {
        if(Policy::is_auth_for_student($results_row['user_id'], "student", "list")) array_push($object_array, $results_row);
      }
    }
    catch (PDOException $e)
    {
      $this->_log_sql_error($e, "Student", "_get_all_by_initial($initial)");
    }
    return $object_array;
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
      $sql = $con->prepare("SELECT student.id FROM `student` left join user on student.user_id = user.id $where_clause $order_by LIMIT $start, $limit;");
      $sql->execute();

      while ($results_row = $sql->fetch(PDO::FETCH_ASSOC))
      {
        $id = $results_row["id"];
        $object_array[] = $this->load_by_id($id, $parse);
      }
    }
    catch (PDOException $e)
    {
      $this->_log_sql_error($e, "student", "_get_all()");
    }
    return $object_array; 
  }
}

?>