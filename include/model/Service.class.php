<?php

/**
* Controls the whole application status
* @package OPUS
*/
require_once('dto/DTO_Service.class.php');
/**
* Controls the whole application status
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @author Gordon Crawford <g.crawford@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/

class Service extends DTO_Service
{
  /**
  * @var string whether OPUS is running or not, takes the values "started" or "stopped". When stopped, only
  * super admins can login.
  */
  var $status = "stopped";

  /**
  * @var string the version of the database schema
  */
  var $schema_version = "";

  static $_field_defs = array
  (
    'status'=>array('type'=>'list', 'list'=>array('started'=>'started','stopped'=>'stopped'), 'header'=>true),
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
    $id = 1;
    $service = new Service;
    $service->id = $id;
    $service->_load_by_id();
    return $service;
  }

  function update($fields) 
  {
    $service = Service::load_by_id(1);
    unset($fields['schema_version']);
    $service->_update($fields);
  }

  function exists($id) 
  {
    $service = new Service;
    $service->id = $id;
    return $service->_exists();
  }

  function get_fields($include_id = false) 
  {
    $service = new Service;
    return  $service->_get_fieldnames($include_id); 
  }

  function request_field_values($include_id = false) 
  {
    $fieldnames = Service::get_fields($include_id);
    $nvp_array = array();

    foreach ($fieldnames as $fn)
    {
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
    }
    return $nvp_array;
  }

  /**
  * check for the validity of the application for running
  *
  * @return true on success, but an error string on failure
  */
  function checks()
  {
    global $config;

    $service = Service::load_by_id(1);
    if($service->status != "started")
    {
      return("error:opus:closed");
    }
    if(version_compare($service->schema_version, $config['opus']['required_schema_version'], "<"))
    {
      return("error:opus:old_schema");
    }
    return "opus:ok";
  }

  function is_started()
  {
    $service = Service::load_by_id(1);

    if ($service->status == "started")
    {
      return true;
    }
    else
    {
      return false;
    }
  }

}

?>