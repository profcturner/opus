<?php
/**
 * @package PDSystem
 *
 * reg_number -> username
 * user_id -> id
 * last few fields have gone
 */

require_once("dto/DTO_Contact.class.php");

class Contact extends DTO_Contact 
{
/*
  var $contactname = ''; 
  var $password="";
  var $salutation="";
  var $firstname = '';
  var $lastname = '';
  var $reg_number = "";
  var $login_time = '';
  var $last_time = "";
  var $last_index = "";
  var $online = 'offline';
  var $email = '';
  var $contact_type = "";
*/
  var $position;          // position in company
  var $voice;             // Phone number
  var $fax;               // Fax number
  var $contact_id;           // Matches id from contact table

  static $_field_defs = array
  (
    'salutation'=>array('type'=>'text', 'size'=>20, 'header'=>true, 'title'=>'title'),
    'firstname'=>array('type'=>'text','size'=>30, 'header'=>true),
    'lastname'=>array('type'=>'text','size'=>30, 'header'=>true),
    'position'=>array('type'=>'text','size'=>50,'header'=>true),
    'email'=>array('type'=>'email','size'=>40, 'header'=>true),
    'voice'=>array('type'=>'text','size'=>40, 'header'=>true),
    'fax'=>array('type'=>'text','size'=>40, 'header'=>true)
  );

  function __construct() 
  {
    parent::__construct();
  }

  function get_field_defs()
  {
    return self::$_field_defs;
  }

  function load_by_id($id) 
  {
     $contact = new Contact;
     $contact->id = $id;
     $contact->_load_by_id();
     return $contact;
  }

  function insert($fields) 
  {
    $contact = new Contact;
    return $contact->_insert($fields);
  }

  function update($fields) 
  {
    $contact = Contact::load_by_id($fields[id]);
    $contact->_update($fields);
  }

  function exists($id) 
  {
    $contact = new Contact;
    $contact->id = $id;
    return $contact->_exists();
  }

  function count($where="") 
  {
    $contact = new Contact;
    return $contact->_count($where);
  }

  function get_all($where_clause="", $order_by="", $page=0, $end=0) 
  {
    $contact = new Contact;

    if($end != 0) return($contact->_get_all($where_clause, $order_by, $page, $end));
    if ($page <> 0) 
    {
        $start = ($page-1)*ROWS_PER_PAGE;
        $limit = ROWS_PER_PAGE;
        $contacts = $contact->_get_all($where_clause, $order_by, $start, $limit);
    }
    else 
    {
        $contacts = $contact->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $contacts;
  }

  function get_id_and_field($fieldname) 
  {
    $contacts = new Contact;
    return  $contacts->_get_id_and_field($fieldname);
  }

  function get_fields($include_id = false) 
  {
    $contact = new Contact;
    return  $contact->get_fieldnames($include_id);
  }

  function request_field_values($include_id = false) 
  {
    $fieldnames = Contact::get_fields($include_id);
    $nvp_array = array();
    foreach ($fieldnames as $fn) 
    {
        $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
    }
    return $nvp_array;
  }

  function remove($id=0) 
  {
    require_once("model/User.class.php");

    $contact = new Contact;
    $contact->load_by_id($id);
    // Remove the user object also
    User::remove($contact->user_id);
    $contact->_remove_where("WHERE id=$id");
  }

}

?>