<?php

/**
* The model object for linking AssessmentGroups with Programmes
* @package OPUS
*/
require_once("dto/DTO_AssessmentGroupProgramme.class.php");

/**
* The AssessmentGroupProgramme model class
*/
class AssessmentGroupProgramme extends DTO_AssessmentGroupProgramme 
{
  var $group_id = 0;     // The id from the assessmentgroup table
  var $startyear = "";   // Year the programme commenced on this group
  var $endyear = "";     // Year the programme finished on this group
  var $programme_id = 0;  // The id for the programme

  function __construct() 
  {
    parent::__construct('default');
  }

  static $_field_defs = array
  (
    'group_id'=>array('type'=>'lookup', 'object'=>'AssessmentGroup', 'value'=>'name', 'title'=>'Assessment Group', 'size'=>20, 'var'=>'assessment_groups', 'header'=>true),
    'startyear'=>array('type'=>'text', 'size'=>8, 'title'=>'Start Year', 'header'=>true),
    'endyear'=>array('type'=>'text', 'size'=>8, 'title'=>'End Year', 'header'=>true)
  );


  /**
  * returns the statically defined field definitions
  */
  function get_field_defs()
  {
    return(self::$_field_defs);
  }

  function load_by_id($id) 
  {
    $assessmentgroupprogramme = new AssessmentGroupProgramme;
    $assessmentgroupprogramme->id = $id;
    $assessmentgroupprogramme->_load_by_id();
    return $assessmentgroupprogramme;
  }

  function insert($fields) 
  {
    // Null some fields if empty
    $fields = AssessmentGroupProgramme::set_empty_to_null($fields);

    $assessmentgroupprogramme = new AssessmentGroupProgramme;
    $assessmentgroupprogramme->_insert($fields);
  }

  function update($fields) 
  {
    // Null some fields if empty
    $fields = AssessmentGroupProgramme::set_empty_to_null($fields);

    $assessmentgroupprogramme = AssessmentGroupProgramme::load_by_id($fields[id]);
    $assessmentgroupprogramme->_update($fields);
  }

  /**
  * Goes through certain fields and sets them to null if they are "empty"
  */
  function set_empty_to_null($fields)
  {
    $set_to_null = array("startyear", "endyear");
    foreach($set_to_null as $field)
    {
      if(isset($fields[$field]) && !strlen($fields[$field])) $fields[$field] = null;
    }
    return($fields);
  }

  /**
  * Wasteful
  */
  function exists($id) 
  {
    $assessmentgroupprogramme = new AssessmentGroupProgramme;
    $assessmentgroupprogramme->id = $id;
    return $assessmentgroupprogramme->_exists();
  }
  
  /**
  * Wasteful
  */
  function count($where_clause="") 
  {
    $assessmentgroupprogramme = new AssessmentGroupProgramme;
    return $assessmentgroupprogramme->_count($where_clause);
  }

  function get_all($where_clause="", $order_by="ORDER BY programme_id, startyear, endyear", $page=0)
  {
    global $config;
    $assessmentgroupprogramme = new AssessmentGroupProgramme;

    if ($page <> 0) {
      $start = ($page-1)*$config['opus']['rows_per_page'];
      $limit = $config['opus']['rows_per_page'];
      $assessmentgroupprogrammes = $assessmentgroupprogramme->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $assessmentgroupprogrammes = $assessmentgroupprogramme->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $assessmentgroupprogrammes;
  }

  function get_id_and_field($fieldname, $where_clause="") 
  {
    $assessmentgroupprogramme = new AssessmentGroupProgramme;
    $assessmentgroupprogramme_array = $assessmentgroupprogramme->_get_id_and_field($fieldname, $where_clause);
    return $assessmentgroupprogramme_array;
  }

  function get_all_programmes($group_id, $year)
  {
    $assessmentgroupprogramme = new AssessmentGroupProgramme;
    return($assessmentgroupprogramme->_get_all_programmes($group_id, $year));
  }

  function remove_by_group($group_id=0) 
  {
    $assessmentgroupprogramme = new AssessmentGroupProgramme;
    $assessmentgroupprogramme->_remove_where("WHERE group_id=$group_id");
  }

  function remove($id=0) 
  {
    $assessmentgroupprogramme = new AssessmentGroupProgramme;
    $assessmentgroupprogramme->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {
    $assessmentgroupprogramme = new AssessmentGroupProgramme;
    return  $assessmentgroupprogramme->_get_fieldnames($include_id); 
  }

  function load_where($where_clause)
  {
    $assessmentgroupprogramme = new AssessmentGroupProgramme;
    $assessmentgroupprogramme->_load_where($where_clause);
    return($assessmentgroupprogramme);
  }
  
  /**
  * Changes the assessment group for an individual course from a given year
  * 
  * @param $programme_id the id of the programme
  * @param $new_group_id the id of the new assessment group
  * @param $from_year the first year of the new assessment group
  * @return boolean success indicator
  */
  function change_assessment_group($programme_id, $new_group_id, $from_year)
  {
		if(!is_numeric($from_year)) return false;
		// Get any matching groups
		$groups = AssessmentGroupProgramme::get_all("where programme_id=" . (int) $programme_id);
		
		// If there is a group with an open endpoint, but defined start, we need to close it
		foreach($groups as $group)
		{
			if(!empty($group->startyear) && empty($group->endyear))
			{
				$fields = array();
				$fields["id"] = $group->id;
				$fields["endyear"] = $from_year - 1;
				$group->update($fields);
			}
		}
		
		// Now write the new entry
		$fields = array();
		$fields["startyear"] = $from_year;
		$fields["group_id"] = $new_group_id;
		$fields["programme_id"] = $programme_id;
		
		AssessmentGroupProgramme::insert($fields);
		return true;
	}
  
  /**
  * Changes the assessment group for many courses at once
  * 
  * @param $programme_ids the id of the programme
  * @param $new_group_id the id of the new assessment group
  * @param $from_year the first year of the new assessment group
  * @return boolean success indicator
  */
  function bulk_change_assessment_group($programme_ids, $new_group_id, $from_year)
  {
		foreach($programme_ids as $programme_id)
		{
			AssessmentGroupProgramme::change_assessment_group($programme_id, $new_group_id, $from_year);
		}
	}


  function request_field_values($include_id = false) 
  {
    $fieldnames = AssessmentGroupProgramme::get_fields($include_id);
    $nvp_array = array();

    foreach ($fieldnames as $fn)
    {
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
    }
    return $nvp_array;
  }
}
?>