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

  var $_human_link_name = ""; // Not in DB, a human representation of a name

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
    $notelink->fill_human_link_name();
    return $notelink;
  }

  function fill_human_link_name()
  {
    switch($this->link_type)
    {
      case 'Company':
        require_once("model/Company.class.php");
        $this->_human_link_name = Company::get_name($this->link_id);
        break;
      case 'Vacancy':
        require_once("model/Vacancy.class.php");
        $this->_human_link_name = Vacancy::get_name($this->link_id);
        break;
      case 'Student':
      case 'Staff':
      case 'Admin':
      case 'Contact':
        require_once("model/User.class.php");
        $this->_human_link_name = User::get_name($this->link_id);
        break;
      default:
        $this->_human_link_name = "Unknown";
        break;
    }
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
  function count($where_clause="") 
  {
    $notelink = new Notelink;
    return $notelink->_count($where_clause);
  }

  function get_all($where_clause="", $order_by="ORDER BY id", $page=0)
  {
    global $config;
    $notelink = new Notelink;

    if ($page <> 0) {
      $start = ($page-1)*$config['opus']['rows_per_page'];
      $limit = $config['opus']['rows_per_page'];
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

  private function get_possible_links_student($student_id)
  {
    $student_id = (int) $student_id;
    $result_array = array();
    require_once("model/Student.class.php");

    require_once("model/Application.class.php");
    require_once("model/Company.class.php");
    require_once("model/Vacancy.class.php");
    $applications = Application::get_all("where student_id=$student_id");
    foreach($applications as $application)
    {
      $result_array['Company_' . $application->company_id] = "Company: " . Company::get_name($application->company_id);
      $result_array['Vacancy_' . $application->vacancy_id] = "Vacancy: " . Vacancy::get_name($application->vacancy_id);
    }
    return($result_array);
  }
}
?>