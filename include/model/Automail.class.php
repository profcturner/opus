<?php

/**
* The model object for automail templates
* @package OPUS
*/
require_once("dto/DTO_Automail.class.php");

/**
* The Resource model class
*/
class Automail extends DTO_Automail 
{
  var $lookup = "";      // A text lookup field for the resource
  var $language_id = 0;  // Language resource belongs to
  var $fromh = "";       // The From header
  var $toh = "";         // The To header
  var $cch = "";         // The Carbon Copy Header
  var $bcch = "";        // The Blind Carbon Copy Header
  var $subject = "";     // The subject of the message
  var $description = ""; // Brief description of description
  var $contents = "";    // The message body

  static $_field_defs = array(
    'lookup'=>array('type'=>'text', 'size'=>30, 'maxsize'=>100, 'title'=>'Lookup', 'header'=>true, 'mandatory'=>true),
    'language_id'=>array('type'=>'lookup', 'object'=>'language', 'value'=>'name', 'title'=>'language', 'var'=>'languages', 'header'=>'true'),
    'description'=>array('type'=>'text', 'size'=>80, 'maxsize'=>250, 'title'=>'Description', 'header'=>true, 'listclass'=>'resource_description', 'mandatory'=>true),
    'fromh'=>array('type'=>'text', 'size'=>60, 'maxsize'=>250, 'title'=>'From Header'),
    'toh'=>array('type'=>'text', 'size'=>60, 'maxsize'=>250, 'title'=>'To Header'),
    'cch'=>array('type'=>'text', 'size'=>60, 'maxsize'=>250, 'title'=>'CC Header'),
    'bcch'=>array('type'=>'text', 'size'=>60, 'maxsize'=>250, 'title'=>'BCC Header'),
    'subject'=>array('type'=>'text', 'size'=>60, 'maxsize'=>250, 'title'=>'Subject'),
    'contents'=>array('type'=>'textarea', 'rowsize'=>10, 'colsize'=>40, 'maxsize'=>32000)

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
    $automail = new Automail;
    $automail->id = $id;
    $automail->_load_by_id();
    return $automail;
  }

  function load_by_lookup($lookup, $language_id = 1)
  {
    $automail = new Automail;
    return($automail->_load_by_lookup($lookup, $language_id));
  }

  function insert($fields) 
  {
    $automail = new Automail;
    $automail->_insert($fields);
  }
  
  function update($fields) 
  {
    $automail = Automail::load_by_id($fields[id]);
    $automail->_update($fields);
  }
  
  /**
  * Wasteful
  */
  function exists($id) 
  {
    $automail = new Automail;
    $automail->id = $id;
    return $automail->_exists();
  }
  
  /**
  * Wasteful
  */
  function count($where_clause="") 
  {
    $automail = new Automail;
    return $automail->_count($where_clause);
  }

  function get_all($where_clause="", $order_by="ORDER BY lookup", $page=0)
  {
    $automail = new Automail;

    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $automails = $automail->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $automails = $automail->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $automails;
  }

  function get_id_and_field($fieldname, $where_clause="") 
  {
    $automail = new Automail;
    return  $automail->_get_id_and_field($fieldname, $where_clause);
  }

  function remove($id=0) 
  {
    $automail = new Automail;
    $automail->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {
    $automail = new Automail;
    return  $automail->_get_fieldnames($include_id); 
  }

  function request_field_values($include_id = false) 
  {
    $fieldnames = Automail::get_fields($include_id);
    $nvp_array = array();

    foreach ($fieldnames as $fn)
    {
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
    }
    return $nvp_array;
  }

  /**
  * substitutes template values in an email and sends it appropriately
  *
  * @param string $lookup the unique lookup for the template in a language
  * @param string $mailfields associative array of fields to substitute
  */
  function sendmail($lookup, $mailfields, $language_id=1)
  {
    global $config;
    global $waf;

    $automail = Automail::load_by_lookup($lookup, $language_id);
    if($automail == false) return; // lookup failed

    // Defaults from config
    $mailfields["conf_institution"]  =  $config['opus']['institution'];
    $mailfields["conf_website"]      =  $config['opus']['url'];
    $mailfields["conf_appname"]      =  $config['opus']['title'];
    $mailfields["conf_version"]      =  $config['opus']['version'] . "." . $config['opus']['minor_version'] . "." . $config['opus']['patch_version'];

    require_once("model/User.class.php");
    // Substitute the currently logged in admin details if possible
    if(User::is_admin())
    {
      $mailfields['atitle']     = $waf->user['opus']['salutation'];
      $mailfields['afirstname'] = $waf->user['opus']['firstname'];
      $mailfields['asurname']   = $waf->user['opus']['lastname'];
      $mailfields['aposition']  = $waf->user['opus']['position'];
      $mailfields['aemail']     = $waf->user['opus']['email'];
    }

    // If all else fails
    if(!isset($mailfields["asurname"]))
    {
      // We need a primary admin, get the first root user in the database
      require_once("model/User.class.php");
      $admins = User::get_all("where user_type='root'", "order by id");

      $mailfields["atitle"]     = $admins[0]->salutation;
      $mailfields["afirstname"] = $admins[0]->firstname;
      $mailfields["asurname"]  = $admins[0]->lastname;
      $mailfields["aposition"]  = $admins[0]->position;
      $mailfields["aemail"]     = $admins[0]->email;
    }

    // Process substitutions
    $automail = Automail::process_automail_subs($automail, $mailfields);

    // Form necessary variables
    $extra="";
    if(!empty($automail->fromh)) $extra .= "From: " . $automail->fromh . "\r\n";
    if(!empty($automail->cch))   $extra .= "Cc: " . $automail->cch . "\r\n";
    if(!empty($automail->bcch))  $extra .= "Bcc: " . $automail->bcch . "\r\n";

    // Add OPUS information to allow easy automatic handling
    $extra .= "X-OPUS-Automail-Lookup: $lookup\r\n";

    require_once("model/OPUSMail.class.php");

    // Send email
    $mail_object = new OPUSMail($automail->toh, $automail->subject, $automail->contents, $extra);
    $mail_object->send();

    $waf->log("Auto email $lookup sent from " . $automail->fromh . 
                            " to " . $automail->toh, PEAR_LOG_NOTICE);
  }


  /**
  * perfoms the substitution of fields in all parts of a message
  *
  * @param string $row the element to substitute, could be to, from, message body etc.
  * @param array $mailfields an associative array of key value substitutions
  * @return the processed input in $row is returned
  */
  private function process_automail_subs($automail, $mailfields)
  {
    // A list of database fields to substitute
    $subfields = array("toh", "fromh", "subject", "cch", "bcch", "contents");

    // Look through each element of the email - from, to, body etc
    foreach($subfields as $subfield)
    {
      // Look at the list to substitute
      foreach($mailfields as $key => $value)
      {
        $automail->$subfield = preg_replace("/%$key%/", $value, $automail->$subfield);
      }
    }
    return($automail);
  }

  function get_name($id)
  {
    $id = (int) $id; // Security

    $data = Automail::get_id_and_field("lookup","where id='$id'");
    return($data[$id]);
  }
}
?>