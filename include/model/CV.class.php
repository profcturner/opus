<?php

/**
* Handles stored internal CVs, i.e. file based ones
* @package OPUS
*/
require_once("dto/DTO_CV.class.php");
/**
* Handles stored internal CVs, i.e. file based ones
* This was lifted from the PDSystem source code, at some stage a common copy will
* probably be maintained.
*
* @author Gordon Crawford <g.crawford@ulster.ac.uk>
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/

class CV extends DTO_CV
{
  var $user_id = 0;
  var $title = '';
  var $description = '';
  var $artefact_id = 0;
  var $modified;
  var $created;

  var $_file_name;
  var $_hash;

  static $_field_defs = array(
    'title'=>array('type'=>'text', 'size'=>50, 'title'=>'Title', 'header'=>true),
    'description'=>array('type'=>'textarea', 'rowsize'=>4, 'colsize'=>50, 'maxsize'=>300, 'title'=>'Description', 'header'=>true),
    'artefact_id'=>array('type'=>'file', 'title'=>'CV File'),
    'modified'=>array('type'=>'timestamp'),
    'created'=>array('type'=>'createtimestamp')
    );

  function __construct()
  {
    parent::__construct();
  }

  function get_field_defs()
  {
    return self::$_field_defs;
  }

  function load_by_id($id, $parse = False) 
  {
    require_once('model/Artefact.class.php');
    $cv = new CV;
    $cv->id = $id;
    $cv->_load_by_id($parse);

    $art = Artefact::load_by_id($cv->artefact_id);
    $cv->_hash = $art->hash;
    $cv->_file_name = $art->file_name;

    return $cv;
  }

  function insert($fields) 
  {
    // insert the artefact and set the artefact_id value
    require_once('model/Artefact.class.php');

    $artefact_fields = array('user_id'=>$fields['user_id'], 'type'=>'uploaded_cv', 'description'=>$fields['description'], 'group'=>'CV Files', 'file'=>$fields['file']);

    $artefact_id = Artefact::insert($artefact_fields);
    if (is_numeric($artefact_id))
    {
      array_pop($fields);
      $fields['artefact_id'] = $artefact_id;

      $cv = new CV;   // insert the actual CV object
      return $cv->_insert($fields);
    }
    else
    {
      return $artefact_id; // this is an error code string
    }
  }

  function update($fields) 
  {
    array_pop($fields); // remove the file element from this array
    $cv = CV::load_by_id($fields[id]);
    $cv->_update($fields);
  }

  function exists($id) 
  {
    $cv = new CV;
    $cv->id = $id;
    return $cv->_exists();
  }

  function count() 
  {
    $cv = new CV;
    return $cv->_count();
  }

  function get_all($where_clause="", $order_by="ORDER BY id", $page = 0, $parse = False)
  {
    $cv = new CV;

    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $cvs = $cv->_get_all($where_clause, $order_by, $start, $limit, $parse);
    } else {
      $cvs = $cv->_get_all($where_clause, $order_by, 0, 1000, $parse);
    }
    return $cvs;
  }

  function get_all_by_user_id($user_id)
  {
    return CV::get_all("WHERE user_id=$user_id", "ORDER BY created", 0, true);
  }

  function get_id_and_field($fieldname) 
  {
    $cv = new CV;
    return  $cv->_get_id_and_field($fieldname);
  }

  function remove($id=0) 
  {
    $cv = new CV;
    $cv->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {
    $cv = new CV;
    return  $cv->_get_fieldnames($include_id);
  }

  function request_field_values($include_id = false)
  {
    $fieldnames = CV::get_fields($include_id);
    $nvp_array = array();

    foreach ($fieldnames as $fn)
    {
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
    }

    $nvp_array['file'] = $_FILES['artefact_id'];
    return $nvp_array;
  }
}
?>