<?php

/**
* DTO handling for Company
* @package OPUS
*/
require_once("dto/DTO.class.php");
/**
* DTO handling for Company
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see Company.class.php
* @package OPUS
*
*/

class DTO_Company extends DTO
{

  function __construct($handle) 
  {
    parent::__construct($handle);
  }

  function _get_all_extended($search, $activities, $sort)
  {
    global $waf;
    $con = $waf->connections[$this->_handle]->con;

    $sort_types = array("name", "locality");
    if(!in_array($sort, $sort_types)) $sort="name";

    if(empty($other_options)) $other_options = array();
    if(empty($activities)) $activities = array();

    // Form Search criteria string
    if(!empty($search))
    {
      $searchc .= " (name LIKE ? OR locality LIKE ? OR town LIKE ? OR brief LIKE ?)";
      $parameters = array("%$search%","%$search%","%$search%","%$search%");
    }
    else
    {
      $searchc = "";
      $parameters = array();
    }

    $full_query = "SELECT * FROM company";
    if(!empty($searchc)) $full_query .= " WHERE $searchc";
    if(!empty($sort)) $full_query .= " ORDER BY `$sort`";

    $object_array = array();
    try
    {
      $sql = $con->prepare($full_query);
      $sql->execute($parameters);

      while ($results_row = $sql->fetch(PDO::FETCH_ASSOC))
      {
        $these_activities = DTO_Company::get_company_activities($results_row['id']);
        if(!DTO_Company::if_any_in_array($activities, $these_activities)) continue;

        array_push($object_array, $results_row);
      }
    }
    catch (PDOException $e)
    {
      $this->_log_sql_error($e, "Company", "_get_all()");
    }
    return $object_array;
  }

  function get_company_activities($company_id)
  {
    require_once("model/CompanyActivity.class.php");
    $object_array = Companyactivity::get_all("where company_id=$company_id");

    $activities = array();
    foreach($object_array as $object)
    {
      array_push($activities, $object->activity_id);
    }
    return $activities;
  }

  function if_any_in_array($needles, $haystack)
  {
    foreach($needles as $needle)
    {
      if(in_array($needle, $haystack)) return true;
    }
    return false;
  }
}

?>