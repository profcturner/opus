<?php
/**
 * @package OPUS
 *
 *
 */
require_once("dto/DTO.class.php");

class DTO_Student extends DTO {

  function __construct($handle) 
  {
    parent::__construct($handle);
  }

  function _get_all_extended($search, $year, $programmes, $sort, $other_options)
  {
    global $waf;
    $con = $waf->connections[$this->_handle]->con;

    if(empty($other_options)) $other_options = array();

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
      $this->_log_sql_error($e, "Student", "_get_all_extended()");
    }
    return $object_array;
  }
}

?>