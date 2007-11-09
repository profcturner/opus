<?php

/**
* DTO handling for Programme
* @package OPUS
*/
require_once("dto/DTO.class.php");
/**
* DTO handling for Programme
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see Programme.class.php
* @package OPUS
*
*/

class DTO_Programme extends DTO
{

  function __construct($handle) 
  {
    parent::__construct($handle);
  }

  /**
  * augments standard loading with programme name
  * @param int $id the id from the programme table
  */
  function _load_by_id($id)
  {
    parent::_load_by_id($id);

    require_once("model/School.class.php");

    $school = School::load_by_id($programme->school_id);
    $this->_school_id = $school->name;
  }

  /**
  * an augmented call to return a programme title consisting of its code and name
  *
  * @param string $where_clause an optional where clause to restrict selection
  * @param string $order_clause an optional clause to alter order of return
  * @return an array indexed by id with the compound description as values
  */
  function _get_id_and_description($where_clause="", $order_clause="")
  {
    $programme = new Programme;

    $programmes = $programme->_get_all($where_clause, $order_clause);

    $programme_array = array();
    foreach($programmes as $prog)
    {
      $programme_array[$prog->id] = $prog->srs_ident . " : " . $prog->name;
    }
    return($programme_array);
  }
}

?>