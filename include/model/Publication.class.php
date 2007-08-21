<?php

/**
* The publication business model object is used to interact with the project data transition object
*
**/

require_once("pds/dto/DTO_Publication.class.php");

class Publication extends DTO_Publication 
{
  var $user_id = 0;
  var $title = "";
  var $abstract = "";
  var $journal = "";
  var $volume = "";
  var $date = "";
  var $pages = "";
  var $skills_developed = "";
  var $further_details = "";

  var $_field_defs = array(
    
    'title'=>array('type'=>'text', 'size'=>50, 'title'=>'Title', 'header'=>true),
    'abstract'=>array('type'=>'textarea', 'rowsize'=>6, 'colsize'=>50, 'title'=>'Abstract', 'header'=>true),
    'journal'=>array('type'=>'text', 'size'=>50, 'title'=>'Journal', 'header'=>true),
    'volume'=>array('type'=>'text', 'size'=>50, 'title'=>'Volume', 'header'=>true),
    'date'=>array('type'=>'date', 'inputstyle'=>'popup', 'size'=>15, 'title'=>'Date', 'header'=>true),
    'pages'=>array('type'=>'text', 'size'=>50, 'title'=>'Pages', 'header'=>true),
    'further_details'=>array('type'=>'textarea', 'rowsize'=>6, 'colsize'=>50, 'title'=>'Further Details', 'header'=>false),
    'skills_developed'=>array('type'=>'textarea', 'rowsize'=>6, 'colsize'=>50, 'title'=>'Skills Developed', 'header'=>true, 'listclass'=>'publication_skills_developed')

    
    );

  function __construct() 
  {
    parent::__construct();
    global $logger;
    $logger->log("Publication construct called");
    $logger->log($this);
  }

  function load_by_id($id) 
  {
    $publication = new Publication;
    $publication->id = $id;
    $publication->_load_by_id();
    return $publication;
  }

  function insert($fields) 
  {
    $publication = new Publication;
    $publication->_insert($fields);
  }
  
  function update($fields) 
  {
    $publication = Publication::load_by_id($fields[id]);
    $publication->_update($fields);
  }
  
  function exists($id) 
  {
    $publication = new Publication;
    $publication->id = $id;
    return $publication->_exists();
  }
  
  function count() 
  {
    $publication = new Publication;
    return $publication->_count();
  }

  function get_all($where_clause="", $order_by="ORDER BY id", $page=0)
  {
    $publication = new Publication;
    
    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $publications = $publication->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $publications = $publication->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $publications;
  }

  function get_id_and_field($fieldname) 
  {
    $publication = new Publication;
    return  $publication->_get_id_and_field($fieldname);
  }


  function remove($id=0) 
  {  
    $publication = new Publication;
    $publication->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {  
    $publication = new Publication;
    return  $publication->_get_fieldnames($include_id); 
  }
  function request_field_values($include_id = false) 
  {
    $fieldnames = Publication::get_fields($include_id);
    $nvp_array = array();
 
    foreach ($fieldnames as $fn) {
 
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
 
    }

    return $nvp_array;

  }
}
?>