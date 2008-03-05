<?php

/**
* Handles vacancies or job adverts
* @package OPUS
*/
require_once("dto/DTO_Vacancy.class.php");
/**
* Handles vacancies or job adverts
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/

class Vacancy extends DTO_Vacancy 
{
  var $company_id = "";   // The company offering the vacancy
  var $description = "";  // Job Description
  var $vacancy_type = ""; // Type of Job
  var $created = "";      // Item creation date
  var $modified = "";     // Item modification date
  var $closedate = "";    // Closing date for applications (if any)
  var $jobstart = "";     // The approximate start date
  var $jobend = "";       // The approximate end date
  var $address1 = "";     // Address field 1
  var $address2 = "";     // Address field 2
  var $address3 = "";     // Address field 3
  var $town = "";         // Town
  var $locality = "";     // Locality
  var $country = "";      // Country
  var $postcode = "";     // Postcode
  var $www = "";          // Web address
  var $salary = "";       // Salary details
  var $brief = "";        // Brief details
  var $status = "";       // Open, Closed etc?
  var $contact_id = "";   // Who's looking after this in a company?


  static $_field_defs = array(
    'description'=>array('type'=>'text', 'size'=>30, 'maxsize'=>100, 'title'=>'Job Description','header'=>true, 'mandatory'=>true),
    'vacancy_type'=>array('type'=>'lookup', 'object'=>'vacancytype', 'value'=>'name', 'title'=>'Type', 'var'=>'vacancytypes'),
    'activity_types'=>array('type'=>'lookup', 'object'=>'activitytype', 'value'=>'name', 'title'=>'Activities', 'var'=>'activitytypes', 'multiple'=>true, 'mandatory'=>true),
    'jobstart'=>array('type'=>'isodate', 'inputstyle'=>'popup', 'required'=>'true', 'title'=>'Job Start Date', 'mandatory'=>true),
    'jobend'=>array('type'=>'isodate', 'inputstyle'=>'popup', 'title'=>'Job Finish Date'),
    'salary'=>array('type'=>'text', 'size'=>10, 'maxsize'=>20),
    'address1'=>array('type'=>'text', 'size'=>40, 'maxsize'=>100, 'title'=>'Address 1'),
    'address2'=>array('type'=>'text', 'size'=>40, 'maxsize'=>100, 'title'=>'Address 2'),
    'address3'=>array('type'=>'text', 'size'=>40, 'maxsize'=>100, 'title'=>'Address 3'),
    'town'=>array('type'=>'text', 'size'=>40, 'maxsize'=>100, 'title'=>'Town'),
    'locality'=>array('type'=>'text', 'size'=>40, 'maxsize'=>100, 'title'=>'Locality', 'header'=>true, 'mandatory'=>true),
    'country'=>array('type'=>'text', 'size'=>30, 'maxsize'=>100, 'title'=>'Country'),
    'postcode'=>array('type'=>'text', 'size'=>10, 'maxsize'=>20, 'title'=>'Postcode'),
    'www'=>array('type'=>'url', 'size'=>40, 'maxsize'=>80, 'title'=>'Web Address'),
    'status'=>array('type'=>'list', 'list'=>array("open"=>"open", "closed"=>"closed", "special"=>"special")),
    'closedate'=>array('type'=>'isodatetime', 'prefix'=>'closedate', 'timestamp'=>'20000101170000', 'inputstyle'=>'popup', 'title'=>'Application Deadline'),
    'contact_id'=>array('type'=>'lookup', 'object'=>'contact', 'value'=>'dud', 'title'=>'Contact', 'var'=>'contacts', 'lookup_function'=>'lookup_contacts_for_company'),
    'brief'=>array('type'=>'textarea', 'rowsize'=>20, 'colsize'=>80, 'maxsize'=>60000,  'title'=>'Brief', 'markup'=>'xhtml', 'mandatory'=>true)
     );

  // This defines which variables are stored elsewhere
  static $_extended_fields = array
  (
    'activity_types'
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

  function get_extended_fields()
  {
    return self::$_extended_fields;
  }

  function load_by_id($id) 
  {
    $vacancy = new Vacancy;
    $vacancy->id = $id;
    $vacancy->_load_by_id();

    require_once("model/VacancyActivity.class.php");
    $vacancy->activity_types = VacancyActivity::get_activity_ids_for_vacancy($vacancy->id);
    $vacancy->www = Vacancy::complete_url($vacancy->www);

    return $vacancy;
  }

  function insert($fields) 
  {
    $vacancy = new Vacancy;
    $fields = Vacancy::set_empty_to_null($fields);

    // Some fields reside elsewhere, grab and unset them
    $activities = $fields['activity_types'];
    unset($fields['activity_types']);

    // Make sure there is an array... even an empty one
    if(empty($activities)) $activities = array();

    $fields['created'] = date("YmdHis");
    $vacancy_id =  $vacancy->_insert($fields);

    require_once("model/VacancyActivity.class.php");
    VacancyActivity::remove_by_vacancy($vacancy_id);
    foreach($activities as $activity)
    {
      $fields = array();
      $fields['vacancy_id'] = $vacancy_id;
      $fields['activity_id'] = $activity;

      VacancyActivity::insert($fields);
    }
  }

  function update($fields) 
  {
    // Null some fields if empty
    $fields = Vacancy::set_empty_to_null($fields);

    // Some fields reside elsewhere, grab and unset them
    $activities = $fields['activity_types'];
    unset($fields['activity_types']);

    $vacancy = Vacancy::load_by_id($fields[id]);
    $fields['modified'] = date("YmdHis");
    $vacancy->_update($fields);

    $vacancy_id = $vacancy->id;
    require_once("model/VacancyActivity.class.php");
    VacancyActivity::remove_by_vacancy($vacancy_id);
    foreach($activities as $activity)
    {
      $fields = array();
      $fields['vacancy_id'] = $vacancy_id;
      $fields['activity_id'] = $activity;

      VacancyActivity::insert($fields);
    }
  }

  /**
  * Goes through certain fields and sets them to null if they are "empty"
  */
  function set_empty_to_null($fields)
  {
    $set_to_null = array("closedate", "jobstart", "jobend");
    foreach($set_to_null as $field)
    {
      if(!strlen($fields[$field])) $fields[$field] = null;
    }
    return($fields);
  }

  function expire($id)
  {
    $vacancy = Vacancy::load_by_id($id);
    $vacancy->status = 'closed';
    // @todo Need automail code in here...
    $vacancy->_update();
  }

  function get_ids($where_clause="", $order_clause="")
  {
    $vacancy = new Vacancy;
    return($vacancy->_get_ids($where_clause, $order_clause));
  }

  /**
  * Wasteful
  */
  function exists($id) 
  {
    $vacancy = new Vacancy;
    $vacancy->id = $id;
    return $vacancy->_exists();
  }

  /**
  * Wasteful
  */
  function count($where_clause="") 
  {
    $vacancy = new Vacancy;
    return $vacancy->_count($where_clause);
  }

  function get_all($where_clause="", $order_by="ORDER BY description, locality", $page=0)
  {
    global $config;
    $vacancy = new Vacancy;

    if ($page <> 0) {
      $start = ($page-1)*$config['opus']['rows_per_page'];
      $limit = $config['opus']['rows_per_page'];
      $vacancys = $vacancy->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $vacancys = $vacancy->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $vacancys;
  }

  function get_id_and_field($fieldname, $where_clause="") 
  {
    $vacancy = new Vacancy;
    $vacancy_array = $vacancy->_get_id_and_field($fieldname, $where_clause);
    unset($vacancy_array[0]);
    return $vacancy_array;
  }

  function remove($id=0) 
  {
    $vacancy = new Vacancy;
    $vacancy->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {
    $vacancy = new Vacancy;
    return  $vacancy->_get_fieldnames($include_id); 
  }

  function request_field_values($include_id = false) 
  {
    $fieldnames = Vacancy::get_fields($include_id);
    $fieldnames = array_merge($fieldnames, Vacancy::get_extended_fields());

    $nvp_array = array();

    foreach ($fieldnames as $fn) {
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
    }
    return $nvp_array;
  }

  function get_all_extended($search, $year, $activities, $vacancy_types, $sort, $other_options)
  {
    $vacancy = new Vacancy;
    return($vacancy->_get_all_extended($search, $year, $activities, $vacancy_types, $sort, $other_options));
  }

  function display($show_error = false, $user_id = 0)
  {
    require_once("model/XMLdisplay.class.php");
    $xml_parser = new XMLdisplay($this->brief);
    if($show_error)
    {
      echo $xml_parser->xml_error;
    }
    else
    {
      echo $xml_parser->xml_output;
    }
  }

  function get_company_id($id)
  {
    $id = (int) $id; // Security

    $data = Vacancy::get_id_and_field("company_id","where id='$id'");
    return($data[$id]);
  }

  function get_name($id)
  {
    $id = (int) $id; // Security

    $data = Vacancy::get_id_and_field("description","where id='$id'");
    return($data[$id]);
  }

  /**
  * checks for vacancies that are open and past their close date, and closes them
  */
  function close_expired_vacancies()
  {
    $now = date("YmdHis");
    $vacancies = Vacancy::get_ids("where status != 'closed' and $now > closedate");

    foreach($vacancies as $vacancy_id)
    {
      Vacancy::close_vacancy($vacancy_id);
    }
  }

  /**
  * closes a given vacancy, and notifies the company contact
  */
  function close_vacancy($vacancy_id)
  {
    global $waf;

    $vacancy = Vacancy::load_by_id($vacancy_id);
    // Perform the close
    $vacancy->status = 'closed';
    $vacancy->_update();
    $waf->log("vacancy " . $vacancy->description . " (" . $vacancy->_company_id . ") automatically closed");

    // Let the contact know
    if($vacancy->contact_id)
    {
      require_once("model/Contact.class.php");
      $contact = Contact::load_by_user_id($vacancy->contact_id);
      if(!strlen($contact->email)) return; // Can't email them!

      // Any more information
      require_once("model/Application.class.php");
      $application_count = Application::count("where vacancy_id=" . $vacancy->id);

      // Start to populate the mail fields
      $mailfields = array();
      $mailfields['custom_vacancydesc'] = $vacancy->description;
      $mailfields['custom_companyname'] = $vacancy->_company_id;
      $mailfields['custom_editurl'] = "?section=directories&function=edit_vacancy&id=" . $vacancy->id;
      $mailfields['custom_manageurl'] = "?section=directories&function=manage_applicants&id=" . $vacancy->id;
      $mailfields['custom_applicationcount'] = $application_count;
      $mailfields['rtitle']     = $contact->salutation;
      $mailfields['rfirstname'] = $contact->firstname;
      $mailfields['rsurname']   = $contact->lastname;
      $mailfields['remail']     = $contact->email;
      $mailfields['rposition']  = $contact->position;

      require_once("model/Automail.class.php");
      Automail::sendmail("CompanyOnClosed", $mailfields);
    }
  }

  function complete_url($url)
  {
    if(empty($url)) return $url;

    if(preg_match("/^((http)|(https)|(ftp)).*$/", $url))
    {
      return $url; // already fine
    }
    else
    {
      return("http://$url");
    }
  }
}
?>