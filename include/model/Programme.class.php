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
    'srs_ident'=>array('type'=>'text', 'size'=>15, 'maxsize'=>30, 'header'=>true, 'title'=>'Code'),
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
    
    if(!$id) return 0; // No programme, no school

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

  function load_where($where_clause)
  {
    $programme = new Programme;
    $programme->_load_where($where_clause);
    return $programme;
  }
  
  function insert($fields) 
  {
    $programme = new Programme;
    return($programme->_insert($fields));
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

  function get_id_and_field($fieldname, $where_clause="", $order_by="order by name") 
  {
    $programme = new Programme;
    $programme_array = $programme->_get_id_and_field($fieldname, $where_clause, $order_by);
    unset($programme_array[0]);
    return $programme_array;
  }

  function remove($id=0) 
  {
    $programme = new Programme;
    $programme->_remove_where("WHERE id=$id");
  }

  function get_cv_group_id($programme_id)
  {
    $programme_id = (int) $programme_id;

    $programme = new Programme;
    return $programme->_get_fields("cvgroup_id", "where id=$programme_id");

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
  
  /**
  * Attempts to create a programme, school and faculty if needed
  * 
  * Particularly on auto creation of student accounts, we often need
  * to create a programme entry, and perhaps all the details above.
  * 
  * @param $programme_details an associative array of details to use
  * @return a valid programme->id if success, or it exists, false otherwise
  */ 
  function auto_create($programme_details)
  {
    $waf = UUWAF::get_instance();
    
    $programme_code = $programme_details['programme_code'];
    $programme = Programme::load_where("where srs_ident='$programme_code'");
    if($programme->id)
    {
      return $programme->id;
    }
    
    // Ok, we don't already have it. Do we have the faculty?
    require_once("model/Faculty.class.php");
    $faculty = Faculty::load_where("where srs_ident='" . $programme_details['faculty_code'] . "'");
    if(!$faculty->id)
    {
      // Does not yet exist
      $fields = array();
      $fields['name'] = $programme_details['faculty_name'];
      $fields['srs_ident'] = $programme_details['faculty_code'];
      $fields['status'] = 'active';
      $waf->log("Automatically creating faculty (" . $fields['srs_ident'] . ") " . $fields['name']);
      $faculty_id = Faculty::insert($fields);
    }
    else $faculty_id = $faculty->id;
    
    // Do we have the school?
    require_once("model/School.class.php");
    $school = School::load_where("where srs_ident='" . $programme_details['department'] . "'");
    if(!$school->id)
    {
      // Does not yet exist
      $fields = array();
      $fields['name'] = $programme_details['department_name'];
      $fields['srs_ident'] = $programme_details['department'];
      $fields['faculty_id'] = $faculty_id;
      $fields['status'] = 'active';
      $waf->log("Automatically creating school (" . $fields['srs_ident'] . ") " . $fields['name']);      
      $school_id = School::insert($fields);
    }
    else $school_id = $school->id;
    
    // Now create the programme
    $fields = array();
    $fields['name'] = $programme_details['programme_title'];
    $fields['srs_ident'] = $programme_details['programme_code'];
    $fields['school_id'] = $school_id;
    $fields['cvgroup_id'] = 1;
    $fields['status'] = 'active';
    $waf->log("Automatically creating programme (" . $fields['srs_ident'] . ") " . $fields['name']);
    $programme_id = Programme::insert($fields);
    
    return($programme_id);
  }
}
?>