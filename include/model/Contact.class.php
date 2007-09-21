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
  var $position;          // position in company
  var $voice;             // Phone number
  var $fax;               // Fax number
  var $user_id;           // Matches id from user table

  // Several of these fields actually reside in the User table
  static $_field_defs = array
  (
    'salutation'=>array('type'=>'text', 'size'=>20, 'header'=>true, 'title'=>'Title'),
    'firstname'=>array('type'=>'text','size'=>30, 'header'=>true),
    'lastname'=>array('type'=>'text','size'=>30, 'header'=>true, 'source'=>'_lastname'),
    'position'=>array('type'=>'text','size'=>50,'header'=>true),
    'email'=>array('type'=>'email','size'=>40, 'header'=>true),
    'voice'=>array('type'=>'text','size'=>40, 'header'=>true),
    'fax'=>array('type'=>'text','size'=>40, 'header'=>true)
  );

  // This defines which ones
  static $_extended_fields = array
  (
    'salutation','firstname','lastname','email'
  );

  function __construct() 
  {
    parent::__construct();
  }

  function get_field_defs()
  {
    return self::$_field_defs;
  }

  function get_extended_fields()
  {
    return self::$_extended_fields;
  }

  function load_by_id($id) 
  {
     $contact = new Contact;
     $contact->id = $id;
     $contact->_load_by_id();
     return $contact;
  }

  function load_by_user_id($user_id) 
  {
     $contact = new Contact;
     $contact->user_id = $user_id;
     $contact->_load_by_user_id($user_id);
     return $contact;
  }

  /**
  * inserts data about a new contact to the User and Contact tables
  *
  * this is more sophisticated that usual because there are two tables.
  */
  function insert($fields) 
  {
    require_once("model/User.class.php");

    $contact = new Contact;
    $company_id = WA::request("company_id");
    $extended_fields = Contact::get_extended_fields();
    $user_fields = array();

    foreach($fields as $key => $value)
    {
      if(in_array($key, $extended_fields))
      {
        // Set these in the other array
        $user_fields[$key] = $value;
        unset($fields[$key]);
      }
    }
    // Insert user data first, adding anything else we need
    $user_fields['user_type'] = 'company';
    $user_id = User::insert($user_fields);

    // Now we know the user_id, to populate the other tables
    $fields['user_id'] = $user_id;
    if($company_id)
    {
      // populate the company contact table
      require_once("model/CompanyContact.class.php");
      $company_contact = array();
      $company_contact['company_id'] = $company_id;
      $company_contact['contact_id'] = $user_id;
      CompanyContact::insert($company_contact);
    }

    // We want to email them, if possible
    if($user_fields['email'])
    {
      require_once("model/Automail.class.php");

    }

    return $contact->_insert($fields);
  }

  function user_notify_password($fields)
  {
    require_once("model/Automail.class.php");

    $mailfields = array();
    $mailfields["rtitle"]     = $fields['salutation'];
    $mailfields["rfirstname"] = $fields['firstname'];
    $mailfields["rsurname"]   = $fields['surname'];
    $mailfields["username"]   = $fields['username'];
    $mailfields["password"]   = $fields['password'];
    $mailfields["remail"]     = $fields['email'];

    automail($template, $mailfields);
  }


  function update($fields) 
  {
    $extended_fields = Contact::get_extended_fields();
    $user_fields = array();

    foreach($fields as $key => $value)
    {
      if(in_array($key, $extended_fields))
      {
        // Set these in the other array
        $user_fields[$key] = $value;
        unset($fields[$key]);
      }
    }
    // Insert user data first, adding anything else we need
    $user_fields['id'] = $fields['user_id'];
    User::update($user_fields);

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

  function get_all_by_company($company_id)
  {
    $contact = new Contact;
    return $contact->_get_all_by_company($company_id);
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
    return  $contact->_get_fieldnames($include_id);
  }

  function request_field_values($include_id = false) 
  {
    $fieldnames = Contact::get_fields($include_id);
    $fieldnames = array_merge($fieldnames, Contact::get_extended_fields());

    //echo "fieldnames: "; print_r($fieldnames);
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