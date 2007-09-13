<?php
/**
 * @package OPUS
 *
 *
 */
require_once("dto/DTO.class.php");

class DTO_Vacancy extends DTO {

  function __construct($handle) 
  {
    parent::__construct($handle);
  }

  function _get_all_extended($search, $year, $activities)
  {
    global $waf;
    $con = $waf->connections[$this->_handle]->con;

    // Form Search criteria string
    if(!empty($search))
    {
      $searchc .= " (description LIKE ? OR vacancy.locality LIKE ? OR vacancy.town LIKE ? OR vacancy.brief LIKE ? OR company.name LIKE ? OR company.brief LIKE ?)";
      $parameters = array("%$search%","%$search%","%$search%","%$search%","%$search%","%$search%");
    }
    else
    {
      $searchc = "";
      $parameters = array();
    }

    if(!empty($year))
    {
      if(!empty($searchc)) $searchc .= " AND";
      $searchc .= " YEAR(jobstart) = ?";
      array_push($parameters, $year);
    }
    if(!$showclosed)
    {
      if(!empty($searchc)) $searchc .= " AND";
      $searchc .= " vacancy.status != 'closed'";
    }

    $full_query = "SELECT vacancy.*, company.name as company_name FROM vacancy LEFT JOIN company ON vacancy.company_id = company.id";
    if(!empty($searchc)) $full_query .= " WHERE $searchc";

    //echo $full_query;
    //print_r($parameters);
    $object_array = array();

    try
    {
      $sql = $con->prepare($full_query);
      $sql->execute($parameters);

      while ($results_row = $sql->fetch(PDO::FETCH_ASSOC))
      {
        array_push($object_array, $results_row);
      }
    }
    catch (PDOException $e)
    {
      $this->_log_sql_error($e, $class, "_get_all()");
    }
    return $object_array;
  }
}

?>