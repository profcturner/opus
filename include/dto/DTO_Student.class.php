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
    global $waf;
    $con = $waf->connections[$this->_handle]->con;

    if(empty($programmes)) $programmes = array();
    if(empty($other_options)) $other_options = array();

    if(!in_array($sort, array('lastname', 'reg_number', 'placement_status', 'last_time')))
      $sort = 'lastname';

    // Form Search criteria string
    if(!empty($search))
    {
      $searchc .= " (lastname LIKE ? OR firstname LIKE ? OR reg_number LIKE ?)";
      $parameters = array("%$search%","%$search%","%$search%");
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
    global $waf;
    $con = $waf->connections[$this->_handle]->con;

    if(!preg_match('/^[A-Za-z]$/', $initial)) return array();


    $full_query = "SELECT user.real_name as real_name, user.email, user.salutation, user.firstname, user.lastname, user.reg_number, user.last_time, student.* FROM student LEFT JOIN user ON student.user_id = user.id where lastname like ? order by lastname";

    $object_array = array();

    try
    {
      $sql = $con->prepare($full_query);
      $sql->execute(array("$initial%"));

      while ($results_row = $sql->fetch(PDO::FETCH_ASSOC))
      {
          array_push($object_array, $results_row);
      }
    }
    catch (PDOException $e)
    {
      $this->_log_sql_error($e, "Student", "_get_all_by_initial($initial)");
    }
    return $object_array;
  }
}

?>