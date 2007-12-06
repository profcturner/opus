<?php

/**
* Defines and handles programmes of study
* @package OPUS
*/
require_once("dto/DTO_Programme.class.php");
/**
* Defines and handles programmes of study
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/

class Programme extends DTO_Programme 
{
  var $name = "";        // Name of programme
  var $www = "";         // Web Address of Programme
  var $srs_ident = "";   // SRS Identifier
  var $status = "";      // Status flags
  var $school_id = "";   // School Id that runs the course
  var $cvgroup_id = 1;   // The CV group the programme belongs to

  static $_field_defs = array(
    'name'=>array('type'=>'text', 'size'=>40, 'maxsize'=>200, 'title'=>'Name', 'header'=>true, 'listclass'=>'programme_name', 'mandatory'=>true),
    'srs_ident'=>array('type'=>'text', 'size'=>10, 'maxsize'=>10, 'header'=>true, 'title'=>'Code'),
    'www'=>array('type'=>'url', 'size'=>60, 'maxsize'=>200, 'title'=>'Web Address'),
    'school_id'=>array('type'=>'lookup', 'object'=>'school', 'value'=>'name', 'title'=>'School', 'var'=>'schools'),
    'cvgroup_id'=>array('type'=>'lookup', 'object'=>'CVGroup', 'value'=>'name', 'title'=>'CV Group', 'var'=>'cvgroup'),
    'status'=>array('type'=>'list', 'list'=>array('active', 'archive'))
  );

  function __construct() 
  {
    parent::__construct('default');
  }

  /**
  * returns the statically defined field definitions
  */
  function get_field_defs()
  {
    return(self::$_field_defs);
  }

  function get_name($id)
  {
    $id = (int) $id; // Security

    $programme = new Programme;
    $data = $programme->_get_id_and_description("where id='$id'");
    return($data[$id]);
  }

  function get_school_id($id)
  {
    $id = (int) $id; // Security

    $data = Programme::get_id_and_field("school_id","where id='$id'");
    return($data[$id]);
  }

  function load_by_id($id) 
  {
    $programme = new Programme;
    $programme->id = $id;
    $programme->_load_by_id();
    return $programme;
  }

  function insert($fields) 
  {
    $programme = new Programme;
    $programme->_insert($fields);
  }
  
  function update($fields) 
  {
    $programme = Programme::load_by_id($fields[id]);
    $programme->_update($fields);
  }
  
  /**
  * Wasteful
  */
  function exists($id) 
  {
    $programme = new Programme;
    $programme->id = $id;
    return $programme->_exists();
  }
  
  /**
  * Wasteful
  */
  function count($where_clause="") 
  {
    $programme = new Programme;
    return $programme->_count($where_clause);
  }

  function get_all($where_clause="", $order_by="ORDER BY id", $page=0)
  {
    global $config;
    $programme = new Programme;

    if ($page <> 0) {
      $start = ($page-1)*$config['opus']['rows_per_page'];
      $limit = $config['opus']['rows_per_page'];
      $programmes = $programme->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $programmes = $programme->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $programmes;
  }

  function get_id_and_description($where_clause="", $order_clause="order by srs_ident, name")
  {
    $programme = new Programme;
    $programme_array = $programme->_get_id_and_description($where_clause, $order_clause);
    return $programme_array;
  }

  function get_id_and_field($fieldname, $where_clause="") 
  {
    $programme = new Programme;
    $programme_array = $programme->_get_id_and_field($fieldname, $where_clause);
    unset($programme_array[0]);
    return $programme_array;
  }

  function remove($id=0) 
  {
    $programme = new Programme;
    $programme->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {
    $programme = new Programme;
    return  $programme->_get_fieldnames($include_id); 
  }

  function request_field_values($include_id = false) 
  {
    $fieldnames = Programme::get_fields($include_id);
    $nvp_array = array();

    foreach ($fieldnames as $fn)
    {
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
    }
    return $nvp_array;
  }

  function get_all_organisation($filter = true)
  {
    require_once("model/Faculty.class.php");
    require_once("model/School.class.php");

    // See if we should see everything
    if(!$filter) $auth_institution = true;
    else $auth_institution = Policy::is_auth_for_institution('student', 'list');

    $final_array = array();
    // First we need an array of faculties
    $faculties = Faculty::get_id_and_field("name");
    // Which we will augment with an array of schools
    foreach($faculties as $faculty_id => $faculty_name)
    {
      if($auth_institution) $auth_faculty = true;
      else $auth_faculty = Policy::is_auth_for_faculty($faculty_id, 'student', 'list');

      // Get the school information
      $schools = School::get_id_and_field("name", "where faculty_id=" . $faculty_id);
      $school_array = array();
      foreach($schools as $school_id => $school_name)
      {
        if($auth_faculty) $auth_school = true;
        else $auth_school = Policy::is_auth_for_school($school_id, 'student', 'list');

        // Augment information with programmes
        $programmes = Programme::get_id_and_description("where school_id=" . $school_id);
        $school['id'] = $school_id;
        $school['name'] = $school_name;
        if(!$auth_school)
        {
          foreach($programmes as $programme_id => $program_description)
          {
            if(!Policy::is_auth_for_programme($programme_id, 'student', 'list')) unset($programmes[$programme_id]);
          }
        }
        $school['programmes'] = $programmes;
        // Only add the school if some programmes are present
        if(count($school['programmes'])) array_push($school_array, $school);
      }
      $faculty['id'] = $faculty_id;
      $faculty['name'] = $faculty_name;
      $faculty['schools'] = $school_array;
      // Only add the faculty if there are schools present
      if(count($faculty['schools'])) array_push($final_array, $faculty);
    }
    return($final_array);
  }
}
?>