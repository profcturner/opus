<?php

/**
* Defines and handles Faculties
* @package OPUS
*/
require_once("dto/DTO_Faculty.class.php");
/**
* Defines and handles Faculties
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/

class Faculty extends DTO_Faculty 
{
  var $name = "";        // Name of faculty
  var $www = "";         // Web Address of Faculty
  var $srs_ident = "";   // SRS Identifier
  var $status = "";      // Status flags

  static $_field_defs = array(
    'name'=>array('type'=>'text', 'size'=>40, 'maxsize'=>200, 'title'=>'Name', 'header'=>true, 'listclass'=>'faculty_name', 'mandatory'=>true),
    'www'=>array('type'=>'url', 'size'=>60, 'maxsize'=>200, 'title'=>'Web Address'),
    'srs_ident'=>array('type'=>'text', 'size'=>40, 'maxsize'=>60, 'title'=>'SRS Code'),
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

  function load_by_id($id) 
  {
    $faculty = new Faculty;
    $faculty->id = $id;
    $faculty->_load_by_id();
    return $faculty;
  }

  function insert($fields) 
  {
    $faculty = new Faculty;
    $faculty->_insert($fields);
  }
  
  function update($fields) 
  {
    $faculty = Faculty::load_by_id($fields[id]);
    $faculty->_update($fields);
  }
  
  /**
  * Wasteful
  */
  function exists($id) 
  {
    $faculty = new Faculty;
    $faculty->id = $id;
    return $faculty->_exists();
  }
  
  /**
  * Wasteful
  */
  function count($where_clause="") 
  {
    $faculty = new Faculty;
    return $faculty->_count($where_clause);
  }

  function get_all($where_clause="", $order_by="ORDER BY name", $page=0)
  {
    global $config;
    $faculty = new Faculty;

    if ($page <> 0) {
      $start = ($page-1)*$config['opus']['rows_per_page'];
      $limit = $config['opus']['rows_per_page'];
      $facultys = $faculty->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $facultys = $faculty->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $facultys;
  }

  function get_id_and_field($fieldname, $where_clause="", $order_by="order by name") 
  {
    $faculty = new Faculty;
    $faculty_array = $faculty->_get_id_and_field($fieldname, $where_clause, $order_by);
    unset($faculty_array[0]);
    return $faculty_array;
  }


  function remove($id=0) 
  {  
    $faculty = new Faculty;
    $faculty->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {  
    $faculty = new Faculty;
    return  $faculty->_get_fieldnames($include_id); 
  }
  function request_field_values($include_id = false) 
  {
    $fieldnames = Faculty::get_fields($include_id);
    $nvp_array = array();

    foreach ($fieldnames as $fn)
    {
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
    }
    return $nvp_array;
  }

  function get_name($id)
  {
    $id = (int) $id; // Security

    $data = Faculty::get_id_and_field("name","where id='$id'");
    return($data[$id]);
  }
}
?>