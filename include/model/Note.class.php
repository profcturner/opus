<?php

/**
* Handles notes within OPUS, the write once documentation system
* @package OPUS
*/
require_once("dto/DTO_Note.class.php");
/**
* Handles notes within OPUS, the write once documentation system
*
* Notes are deliberately intended to never be deleted or updated. This is so that
* they might provide some legal defence if needed. You will note that the usual
* update and remove functions are totally absent from this class.
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see Notelink.class.php
* @package OPUS
*
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
    'summary'=>array('type'=>'text', 'size'=>60, 'maxsize'=>250, 'title'=>'Summary', 'header'=>true, 'mandatory'=>true),
    'notelinks'=>array('type'=>'lookup', 'object'=>'notelink', 'value'=>'name', 'title'=>'Possible Links', 'var'=>'links', 'lookup_function'=>'get_possible_links', 'multiple'=>true),
    'comments'=>array('type'=>'textarea', 'rowsize'=>20, 'colsize'=>70, 'maxsize'=>32000, 'markup'=>'xhtml')
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
  function count($where_clause="") 
  {
    $note = new Note;
    return $note->_count($where_clause);
  }

  function get_all_by_links($object_name, $object_id)
  {
    $note = new Note;
    return($note->_get_all_by_links($object_name, $object_id));
  }

  function get_all($where_clause="", $order_by="ORDER BY lookup", $page=0)
  {
    global $config;
    $note = new Note;

    if ($page <> 0) {
      $start = ($page-1)*$config['opus']['rows_per_page'];
      $limit = $config['opus']['rows_per_page'];
      $notes = $note->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $notes = $note->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $notes;
  }

  function get_id_and_field($fieldname, $where_clause="") 
  {
    $note = new Note;
    return  $note->_get_id_and_field($fieldname, $where_clause);
  }

  function get_fields($include_id = false) 
  {
    $note = new Note;
    return  $note->_get_fieldnames($include_id); 
  }


  function request_field_values($include_id = false) 
  {
    $fieldnames = Note::get_fields($include_id);
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