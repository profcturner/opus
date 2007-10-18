<?php

/**
* The model object for note templates
* @package OPUS
*/
require_once("dto/DTO_Note.class.php");

/**
* The Note model class
*/
class Note extends DTO_Note 
{
  var $date = "";        // Datestamp for note
  var $auth = "";        // An authorisation string
  var $summary = "";     // A one line description of the note
  var $comments = "";    // The main note body
  var $author_id = "";   // The id, from the user table, of the author of the note

  static $_field_defs = array(
    'auth'=>array('type'=>'text', 'size'=>60, 'maxsize'=>250, 'title'=>'Authorization'),
    'summary'=>array('type'=>'text', 'size'=>60, 'maxsize'=>250, 'title'=>'Summary', 'header'=>true),
    'notelinks'=>array('type'=>'lookup', 'object'=>'notelink', 'value'=>'name', 'title'=>'Possible Links', 'var'=>'links', 'lookup_function'=>'get_possible_links', 'multiple'=>true),
    'comments'=>array('type'=>'textarea', 'rowsize'=>10, 'colsize'=>40, 'maxsize'=>32000, 'markup'=>'xhtml')
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

  // This defines which variables are stored elsewhere
  static $_extended_fields = array
  (
    'notelinks'
  );

  function load_by_id($id) 
  {
    $note = new Note;
    $note->id = $id;
    $note->_load_by_id();
    return $note;
  }

  function load_by_lookup($lookup, $language_id = 1)
  {
    $note = new Note;
    return($note->_load_by_lookup($lookup, $language_id));
  }

  function insert($fields) 
  {
    $note = new Note;
    // Creation time is NOW.
    $fields['date'] = date("YmdHis");
    $notelinks = $fields['notelinks'];
    unset($fields['notelinks']);

    $note->_insert($fields);
  }

  /*  
  function update($fields) 
  {
    $note = Note::load_by_id($fields[id]);
    $note->_update($fields);
  }
  */

  /**
  * Wasteful
  */
  function exists($id) 
  {
    $note = new Note;
    $note->id = $id;
    return $note->_exists();
  }
  
  /**
  * Wasteful
  */
  function count() 
  {
    $note = new Note;
    return $note->_count();
  }

  function get_all_by_links($object_name, $object_id)
  {
    $note = new Note;
    return($note->_get_all_by_links($object_name, $object_id));
  }

  function get_all($where_clause="", $order_by="ORDER BY lookup", $page=0)
  {
    $note = new Note;

    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $notes = $note->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $notes = $note->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $notes;
  }

  function get_id_and_field($fieldname) 
  {
    $note = new Note;
    return  $note->_get_id_and_field($fieldname);
  }

  /*
  function remove($id=0) 
  {
    $note = new Note;
    $note->_remove_where("WHERE id=$id");
  }
  */
  function get_fields($include_id = false) 
  {
    $note = new Note;
    return  $note->_get_fieldnames($include_id); 
  }


  function request_field_values($include_id = false) 
  {
    $fieldnames = Note::get_fields($include_id);
    $fieldnames = array_merge($fieldnames, Note::get_extended_fields());
    $nvp_array = array();

    foreach ($fieldnames as $fn)
    {
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
    }
    return $nvp_array;
  }

  function display($show_error = false, $user_id = 0)
  {
    require_once("model/XMLdisplay.class.php");
    $xml_parser = new XMLdisplay($this->comments);
    if($show_error)
    {
      echo $xml_parser->xml_error;
    }
    else
    {
      echo $xml_parser->xml_output;
    }
  }

  function get_name($id)
  {
    $id = (int) $id; // Security

    $data = Note::get_id_and_field("summary","where id='$id'");
    return($data[$id]);
  }
}
?>