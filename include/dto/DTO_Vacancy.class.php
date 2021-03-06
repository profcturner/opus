<?php

/**
* DTO handling for Vacancy
* @package OPUS
*/
require_once("dto/DTO.class.php");
/**
* DTO handling for Vacancy
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see Vacancy.class.php
* @package OPUS
*
*/

class DTO_Vacancy extends DTO
{

  function __construct($handle) 
  {
    parent::__construct($handle);
  }

  function _load_by_id($id=0)
  {
    parent::_load_by_id($id);

    require_once("model/Company.class.php");
    $this->_company_id = Company::get_name($this->company_id);
  }

  function _get_all_extended($search, $year, $activities, $vacancy_types, $sort, $other_options)
  {
    $waf =& UUWAF::get_instance();
    $con = $waf->connections[$this->_handle]->con;

    $sort_types = array("name", "locality", "closedate");
    if(!in_array($sort, $sort_types)) $sort="name";

    if(empty($other_options)) $other_options = array();
    if(empty($vacancy_types)) $vacancy_types = array();
    if(empty($activities)) $activities = array();

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
    if(!in_array("ShowClosed", $other_options))
    {
      if(!empty($searchc)) $searchc .= " AND";
      $searchc .= " vacancy.status != 'closed'";
    }

    $full_query = "SELECT vacancy.*, company.name as company_name FROM vacancy LEFT JOIN company ON vacancy.company_id = company.id";
    if(!empty($searchc)) $full_query .= " WHERE $searchc";
    if(!empty($sort)) $full_query .= " ORDER BY `$sort`";

    $object_array = array();
    try
    {
      $sql = $con->prepare($full_query);
      $sql->execute($parameters);

      while ($results_row = $sql->fetch(PDO::FETCH_ASSOC))
      {
        $these_activities = DTO_Vacancy::get_vacancy_activities($results_row['id']);
        if(!DTO_Vacancy::if_any_in_array($activities, $these_activities)) continue;

        // Check vacancy types
        if(!in_array($results_row['vacancy_type'], $vacancy_types)) continue;
        array_push($object_array, $results_row);
      }
    }
    catch (PDOException $e)
    {
      $this->_log_sql_error($e, $class, "_get_all()");
    }
    return $object_array;
  }

  function get_vacancy_activities($vacancy_id)
  {
    require_once("model/VacancyActivity.class.php");
    $object_array = Vacancyactivity::get_all("where vacancy_id=$vacancy_id");

    $activities = array();
    foreach($object_array as $object)
    {
      array_push($activities, $object->activity_id);
    }
    return $activities;
  }
	
	function _get_years_of_use()
	{
	  $waf =& UUWAF::get_instance();
    $con = $waf->connections[$this->_handle]->con;

		$years = array();
    try
    {
      $sql = $con->prepare('select distinct(year(jobstart)) from vacancy order by year(jobstart)');
      $sql->execute();

      while($result_row = $sql->fetch(PDO::FETCH_NUM))
			{
				array_push($years, $result_row[0]);
			}
    }
    catch (PDOException $e)
    {
      $this->_log_sql_error($e, $class, "_get_years_of_use()");
    }
    return $years;
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