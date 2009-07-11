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
	function get_years_of_use()
	{
		require_once("model/Vacancy.class.php");
		return(Vacancy::get_years_of_use());
	}
	
	function get_resource_statistics()
	{
	}
	
	function get_statistics_by_year($years)
	{
		$vacancy_statistics = SystemStatistics::get_vacancy_statistics($years);
		$application_statistics = SystemStatistics::get_application_statistics($years);
		$placement_statistics = SystemStatistics::get_placement_statistics($years);
		$assessment_statistics = SystemStatistics::get_assessment_statistics($years);

		$combined = array();
		foreach($years as $year)
		{
			$element = array();
			$element['vacancy'] = $vacancy_statistics[$year];
			$element['application'] = $application_statistics[$year];
			$element['placement'] = $placement_statistics[$year];
			$element['assessment'] = $asessment_statistics[$year];
			
			$vacancy_total += $element['vacancy'];
			$application_total += $element['application'];
			$placement_total += $element['placement'];
			$assessment_total += $element['assessment'];
			
			$combined["$year"] = $element;
		}
		
		$element = array();
		$element['vacancy'] = $vacancy_total;
		$element['application'] = $application_total;
		$element['placement'] = $placement_total;
		$element['assessment'] = $assessment_total;
		
		$combined['Total'] = $element;
		
		return($combined);
	}
	
	function get_vacancy_statistics($years)
	{
		require_once("model/Vacancy.class.php");
		
		$results = array();
		foreach($years as $year)
		{
			$results[$year] = Vacancy::count("where year(jobstart) = '$year'");
		}
		return($results);
	}
	
	function get_application_statistics($years)
	{
		require_once("model/Application.class.php");
		
		$results = array();
		foreach($years as $year)
		{
			$results[$year] = Application::count("where year(created) = '$year'");
		}
		return($results);
	}
	
	function get_placement_statistics($years)
	{
		require_once("model/Placement.class.php");
		
		$results = array();
		foreach($years as $year)
		{
			$results[$year] = Placement::count("where year(jobstart) = '$year'");
		}
		return($results);
	}
	
	function get_assessment_statistics($years)
	{
		require_once("model/AssessmentTotal.class.php");
		
		$results = array();
		foreach($years as $year)
		{
			$results[$year] = AssessmentTotal::count("where year(created) = '$year'");
		}
		return($results);
		
	}
}

?>