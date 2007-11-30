<?php

/**
* DTO handling for Supervisors
* @package OPUS
*/
require_once("dto/DTO_NoData.class.php");
/**
* DTO handling for Supervisors
*
* Requires careful handling since there is no supervisor table
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see Supervisor.class.php
* @package OPUS
*
*/

class DTO_Supervisor extends DTO_NoData
{

  function __construct($handle) 
  {
    parent::__construct($handle);
  }

  function _load_by_placement_id($id=0)
  {
    global $waf;

    $id = (int) $id;
    // Load the placement record
    require_once("model/Placement.class.php");
    $placement = Placement::load_by_id($id);

    $this->placement_id    = $placement->id;
    $this->company_id      = $placement->company_id;
    $this->vacancy_id      = $placement->vacancy_id;
    $this->student_user_id = $placement->student_id;

    require_once("model/Company.class.php");
    $this->_company_id = Company::get_name($this->company_id);
    require_once("model/Vacancy.class.php");
    $this->_vacancy_id = Vacancy::get_name($this->vacancy_id);
    require_once("model/Student.class.php");
    $this->student_id = Student::get_id_from_user_id($this->student_user_id);
    $this->_student_id = Student::get_name($this->student_id);

    require_once("model/User.class.php");
    $username = "supervisor_$id";
    $user = new User;
    $success = $user->_load_where("where username='$username' and user_type='supervisor'");
    if(!$success) $waf->halt("error:invalid:supervisor");
    $fields = $user->_get_fieldnames(false);

    foreach($fields as $field)
    {
      $this->$field = $user->$field;
    }
    $this->user_id = $user->id;
  }

  function _load_by_user_id($user_id=0)
  {
    require_once("model/User.class.php");
    $username = User::get_username($user_id);
    $this->_load_by_username($username);
  }

  function _load_by_username($username="")
  {
    $matches = array();
    preg_match("/^supervisor_([0-9]+)$/", $username, &$matches);
    $this->_load_by_placement_id($matches[1]);
  }

  function _get_all($where_clause="", $order_clause="order by lastname", $start=0, $limit=10000)
  {
    global $waf;

    $con = $waf->connections[$this->_handle]->con;

    if(empty($where_clause)) $where_clause="where user_type='supervisor'";
    else $where_clause .= " and user_type='supervisor'";

    $full_query = "SELECT id, username FROM user $where_clause $order_clause limit $start, $limit";

    $object_array = array();
    try
    {
      $sql = $con->prepare($full_query);
      $sql->execute();

      while ($results_row = $sql->fetch(PDO::FETCH_ASSOC))
      {
        $supervisor = Supervisor::load_by_username($results_row['username']);
        array_push($object_array, $supervisor);
      }
    }
    catch (PDOException $e)
    {
      $this->_log_sql_error($e, "Supervisor", "_get_all($where_clause, $order_clause, $start, $limit)");
    }
    return $object_array;
  }

  function _get_all_by_initial($initial)
  {
    global $waf;
    $con = $waf->connections[$this->_handle]->con;

    if(!preg_match('/^[A-Za-z]$/', $initial)) return array();
    return($this->_get_all("lastname like '$initial%'"));
  }
}

?>