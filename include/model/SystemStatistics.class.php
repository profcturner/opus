<?php

/**
* Handling for the System Statistics page
* @package OPUS
*/

/**
* This class encapsulates the required handling for the system statistics page
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/
class SystemStatistics
{
	function get_first_year()
	{
		require_once("model/Vacancy.class.php");
		return(Vacancy::get_first_year());
	}
	
	function get_last_year()
	{
		require_once("model/Vacancy.class.php");
		return(Vacancy::get_last_year());
	}
	
	function get_resource_statistics()
	{
	}
	
	function get_statistics_by_year($start_year, $end_year)
	{
		$vacancy_statistics = SystemStatistics::get_vacancy_statistics($start_year, $end_year);
		$application_statistics = SystemStatistics::get_application_statistics($start_year, $end_year);
		$placement_statistics = SystemStatistics::get_placement_statistics($start_year, $end_year);
		$assessment_statistics = SystemStatistics::get_assessment_statistics($start_year, $end_year);

		$combined = array();
		for($loop = $start_year; $loop <= $end_year; $loop++)
		{
			$element = array();
			$element['vacancy'] = $vacancy_statistics[$loop];
			$element['application'] = $application_statistics[$loop];
			$element['placement'] = $placement_statistics[$loop];
			$element['assessment'] = $asessment_statistics[$loop];
			
			$vacancy_total += $element['vacancy'];
			$application_total += $element['application'];
			$placement_total += $element['placement'];
			$assessment_total += $element['assessment'];
			
			$combined["$loop"] = $element;
		}
		
		$element = array();
		$element['vacancy'] = $vacancy_total;
		$element['application'] = $application_total;
		$element['placement'] = $placement_total;
		$element['assessment'] = $assessment_total;
		
		$combined['Total'] = $element;
		
		return($combined);
	}
	
	function get_vacancy_statistics($start_year, $end_year)
	{
		require_once("model/Vacancy.class.php");
		
		$results = array();
		for($loop = $start_year; $loop <= $end_year; $loop++)
		{
			$results[$loop] = Vacancy::count("where year(jobstart) = '$loop'");
		}
		return($results);
	}
	
	function get_application_statistics($start_year, $end_year)
	{
		require_once("model/Application.class.php");
		
		$results = array();
		for($loop = $start_year; $loop <= $end_year; $loop++)
		{
			$results[$loop] = Application::count("where year(created) = '$loop'");
		}
		return($results);
	}
	
	function get_placement_statistics($start_year, $end_year)
	{
		require_once("model/Placement.class.php");
		
		$results = array();
		for($loop = $start_year; $loop <= $end_year; $loop++)
		{
			$results[$loop] = Placement::count("where year(jobstart) = '$loop'");
		}
		return($results);
	}
	
	function get_assessment_statistics($start_year, $end_year)
	{
		require_once("model/AssessmentTotal.class.php");
		
		$results = array();
		for($loop = $start_year; $loop <= $end_year; $loop++)
		{
			$results[$loop] = AssessmentTotal::count("where year(created) = '$loop'");
		}
		return($results);
		
	}

}

?>