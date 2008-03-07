<?php

/**
* CSV mapping to change a University's own CSV to a generic version
* @package OPUS
*/
require_once("dto/DTO_CSVMapping.class.php");
/**
* CSV mapping to change a University's own CSV to a generic version
*
* Even within the University of Ulster, there are two slightly different variants
* of CSV files in use. This code allows an institution to supply regular expressions
* to convert their CSV to a generic format on the fly.
*
* The data inbound must be line based, but it could be more sophisticated than CSV.
* The output should be CSV of the form
* student #, title, firstname, surname, course code, year of study, disability flags
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/

class CSVMapping extends DTO_CSVMapping 
{
  var $name = "";         // Name of this format
  var $pattern = "";      // Pattern for preg_replace, use parentheses for parts to catch
  var $replacement = "";  // Replacement
  var $exclude = "";      // Exclude lines matching this pattern

  static $_field_defs = array(
    'name'=>array('type'=>'text', 'size'=>30, 'maxsize'=>100, 'title'=>'Name of Mapping', 'header'=>true, 'mandatory'=>true),
    'pattern'=>array('type'=>'text', 'size'=>40, 'maxsize'=>200, 'title'=>'Pattern', 'mandatory'=>true),
    'replacement'=>array('type'=>'text', 'size'=>40, 'maxsize'=>200, 'title'=>'Replacement', 'mandatory'=>true),
    'exclude'=>array('type'=>'text', 'size'=>40, 'maxsize'=>200),
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
    $csvmapping = new CSVMapping;
    $csvmapping->id = $id;
    $csvmapping->_load_by_id();
    return $csvmapping;
  }

  function insert($fields) 
  {
    $csvmapping = new CSVMapping;
    return($csvmapping->_insert($fields));
  }
  
  function update($fields) 
  {
    $csvmapping = CSVMapping::load_by_id($fields[id]);
    $csvmapping->_update($fields);
  }
  
  /**
  * Wasteful
  */
  function exists($id) 
  {
    $csvmapping = new CSVMapping;
    $csvmapping->id = $id;
    return $csvmapping->_exists();
  }
  
  /**
  * Wasteful
  */
  function count($where_clause="") 
  {
    $csvmapping = new CSVMapping;
    return $csvmapping->_count($where_clause);
  }

  function get_all($where_clause="", $order_by="ORDER BY name", $page=0)
  {
    global $config;
    $csvmapping = new CSVMapping;

    if ($page <> 0) {
      $start = ($page-1)*$config['opus']['rows_per_page'];
      $limit = $config['opus']['rows_per_page'];
      $csvmappings = $csvmapping->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $csvmappings = $csvmapping->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $csvmappings;
  }

  function get_id_and_field($fieldname, $where_clause="") 
  {
    $csvmapping = new CSVMapping;
    $csvmapping_array = $csvmapping->_get_id_and_field($fieldname, $where_clause);
    $csvmapping_array[0] = "Automatic";
    if($fieldname != 'name') unset($csvmapping_array[0]);
    return $csvmapping_array;
  }


  function remove($id=0) 
  {  
    $csvmapping = new CSVMapping;
    $csvmapping->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {  
    $csvmapping = new CSVMapping;
    return  $csvmapping->_get_fieldnames($include_id); 
  }
  function request_field_values($include_id = false) 
  {
    $fieldnames = CSVMapping::get_fields($include_id);
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

    $data = CSVMapping::get_id_and_field("name","where id='$id'");
    return($data[$id]);
  }

}
?>