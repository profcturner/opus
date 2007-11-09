<?php

/**
* Handles what entities a given note in OPUS is linked to
* @package OPUS
*/
require_once("dto/DTO_Notelink.class.php");
/**
* Handles what entities a given note in OPUS is linked to
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see Note.class.php
* @package OPUS
*
*/

class Notelink extends DTO_Notelink 
{
  var $link_type = "";      // The object linked to
  var $link_id = "";        // The id of the object (for user derived types, from User)
  var $note_id = "";        // The id of the note
  var $main = "";           // Is this the primary link?

  static $_field_defs = array(
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
    $notelink = new Notelink;
    $notelink->id = $id;
    $notelink->_load_by_id();
    return $notelink;
  }

  function insert($fields) 
  {
    $notelink = new Notelink;
    $notelink->_insert($fields);
  }

  /**
  * Wasteful
  */
  function exists($id) 
  {
    $notelink = new Notelink;
    $notelink->id = $id;
    return $notelink->_exists();
  }
  
  /**
  * Wasteful
  */
  function count() 
  {
    $notelink = new Notelink;
    return $notelink->_count();
  }

  function get_all($where_clause="", $order_by="ORDER BY id", $page=0)
  {
    $notelink = new Notelink;

    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $notelinks = $notelink->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $notelinks = $notelink->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $notelinks;
  }

  function get_id_and_field($fieldname, $where_clause="") 
  {
    $notelink = new Notelink;
    $notelink_array = $notelink->_get_id_and_field($fieldname, $where_clause);
    return $notelink_array;
  }

  function get_fields($include_id = false) 
  {
    $notelink = new Notelink;
    return  $notelink->_get_fieldnames($include_id); 
  }

  function request_field_values($include_id = false) 
  {
    $fieldnames = Notelink::get_fields($include_id);
    $nvp_array = array();

    foreach ($fieldnames as $fn)
    {
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
    }
    return $nvp_array;
  }

  /**
  * supplies possible links for a new note
  *
  * this function provides an array of possible links for a note, based
  * on the item being noted.
  *
  * @param $dud an unused variable, merely present to fool the framework
  * @return an associative array of (object_object_id => title) type
  */
  function get_possible_links($dud)
  {
    // Unfortunately, we need to use $_REQUEST to find out what is being added
    $object_type = $_REQUEST['object_type'];
    $object_id = (int) $_REQUEST['object_id'];

    switch($object_type)
    {
      case 'Company':
        return(Notelink::get_possible_links_company($object_id));
        break;
      case 'Vacancy':
        return(Notelink::get_possible_links_vacancy($object_id));
        break;
      case 'Staff':
        return(Notelink::get_possible_links_staff($object_id));
        break;
      case 'Admin':
        return(Notelink::get_possible_links_admin($object_id));
        break;
      case 'Student':
        return(Notelink::get_possible_links_student($object_id));
        break;
      case 'Contact':
        return(Notelink::get_possible_links_student($object_id));
        break;
      default:
        // Should never happen
        return(array());
    }
  }

  private function get_possible_links_company($object_id)
  {
    $result_array = array();
    // Contacts
    require_once("model/Contact.class.php");
    $contacts = Contact::get_all_by_company($object_id);
    foreach($contacts as $contact)
    {
      $result_array['Contact_' . $contact->user_id] = "Contact: " .$contact->real_name;
    }

    // Need to add placement students
    return($result_array);
  }
}
?>