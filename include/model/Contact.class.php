<?php

/**
* Defines the extra fields for company HR Contacts
* @package OPUS
*/
require_once("dto/DTO_Contact.class.php");
/**
* Defines the extra fields for company HR Contacts
*
* This melds data from the contact and user tables together
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see User.class.php
* @package OPUS
*
*/

class Contact extends DTO_Contact 
{
  var $position;          // position in company
  var $voice;             // Phone number
  var $fax;               // Fax number
  var $user_id;           // Matches id from user table

  // Several of these fields actually reside in the User table
  static $_field_defs = array
  (
    'salutation'=>array('type'=>'text', 'size'=>20, 'header'=>true, 'title'=>'Title', 'mandatory'=>true),
    'firstname'=>array('type'=>'text','size'=>30, 'header'=>true, 'mandatory'=>true),
    'lastname'=>array('type'=>'text','size'=>30, 'header'=>true, 'mandatory'=>true),
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
    $waf =& UUWAF::get_instance();
    // We have a potential security problem here, we should check id and user_id are really linked.
    $contact = Contact::load_by_id($fields['id']);
    if($contact->user_id != $fields['user_id'])
    {
      $waf->security_log("attempt to update contact with mismatching user_id fields");
      $waf->halt("error:contact:user_id_mismatch");
    }

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
    global $config;
    $contact = new Contact;

    if($end != 0) return($contact->_get_all($where_clause, $order_by, $page, $end));
    if ($page <> 0) 
    {
        $start = ($page-1)*$config['opus']['rows_per_page'];
        $limit = $config['opus']['rows_per_page'];
        $contacts = $contact->_get_all($where_clause, $order_by, $start, $limit);
    }
    else 
    {
        $contacts = $contact->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $contacts;
  }

  function get_id_and_field($fieldname, $where_clause="") 
  {
    $contacts = new Contact;
    return  $contacts->_get_id_and_field($fieldname, $where_clause);
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

  /**
  * provides information for the contact lookup field in vacancies
  */
  function lookup_contacts_for_company()
  {
    $company_id = WA::request("company_id");
    if(empty($company_id)) $company_id = $_SESSION['company_id'];
    $contacts = Contact::get_all_by_company($company_id);

    $lookups = array();
    foreach($contacts as $contact)
    {
      $lookups[$contact->user_id] = $contact->real_name;
    }
    return($lookups);

  }

  function get_companies_for_contact($contact_user_id)
  {
    $return_array = array();

    $contact_user_id = (int) $contact_user_id;

    require_once("model/CompanyContact.class.php");
    require_once("model/Company.class.php");
    $companycontacts = CompanyContact::get_all("where contact_id=$contact_user_id");

    foreach($companycontacts as $companycontact)
    {
      $return_array[$companycontact->company_id] = Company::get_name($companycontact->company_id);
    }
    return($return_array);
  }

  function is_auth_for_company($company_id, $contact_user_id = 0)
  {
    $company_id = (int) $company_id;

    if($contact_user_id == 0) $contact_user_id = User::get_id();
    require_once("model/CompanyContact.class.php");
    return(CompanyContact::count("where company_id=company_id and contact_id=$contact_user_id"));
  }

  function is_auth_for_vacancy($vacancy_id, $contact_user_id = 0)
  {
    require_once("model/Vacancy.class.php");
    $company_id = Vacancy::get_company_id($vacancy_id);
    return(Contact::is_auth_for_company($company_id, $contact_user_id));
  }

  function get_user_id($id)
  {
    $id = (int) $id;
    $contact = new Contact;
    return($contact->_get_fields("user_id", "where id=$id"));
  }

  function get_name($id)
  {
    return(User::get_name(Contact::get_user_id($id)));
  }
}

?>