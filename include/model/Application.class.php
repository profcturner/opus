<?php

/**
* The model object for applications for jobs
* @package OPUS
*/
require_once("dto/DTO_Application.class.php");

/**
* The Application model class
*/
class Application extends DTO_Application 
{
  var $company_id = 0;         // Company applied for
  var $vacancy_id = 0 ;        // Vacancy applied for
  var $student_id = 0;         // Student making applications
  var $created = "";           // Initial application timestamp
  var $modified = "";          // Last modification time for application
  var $cv_source = "";         // Where the CV is coming from (if any)
  var $cv_id = "";             // Template id for pds_template, or internal id for internal
  var $archive_hash = "";      // Hash for a pds_custom CV
  var $archive_mime_type = ""; // Mime type for a custom CV
  var $portfolio_source = "";  // Where the portfolio comes from (if any)
  var $portfolio_hash = "";    // Hash of the portfolio
  var $cover = "";             // Cover letter if any
  var $status = "";            // Status as set by company
  var $lastseen = "";          // When last seen by company
  var $status_modified = "";   // When the status was last set
  var $addedby = 0;            // Id of user who added this

  static $_field_defs = array(
    'company_id'=>array('type'=>'text', 'size'=>30, 'maxsize'=>100, 'title'=>'Company'),
    'vacancy_id'=>array('type'=>'text', 'size'=>30, 'maxsize'=>100, 'header'=>true, 'title'=>'Vacancy'),
    'status'=>array('type'=>'text', 'size'=>30, 'maxsize'=>100, 'title'=>'Status', 'header'=>true)
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
    $application = new Application;
    $application->id = $id;
    $application->_load_by_id();
    return $application;
  }

  function insert($fields) 
  {
    $application = new Application;
    $application->_insert($fields);
  }
  
  function update($fields) 
  {
    $application = Application::load_by_id($fields[id]);
    $application->_update($fields);
  }
  
  /**
  * Wasteful
  */
  function exists($id) 
  {
    $application = new Application;
    $application->id = $id;
    return $application->_exists();
  }
  
  /**
  * Wasteful
  */
  function count() 
  {
    $application = new Application;
    return $application->_count();
  }

  function get_all($where_clause="", $order_by="ORDER BY id", $page=0)
  {
    $application = new Application;
    
    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $applications = $application->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $applications = $application->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $applications;
  }

  function get_id_and_field($fieldname) 
  {
    $application = new Application;
    $application_array = $application->_get_id_and_field($fieldname);
    unset($application_array[0]);
    return $application_array;
  }


  function remove($id=0) 
  {  
    $application = new Application;
    $application->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {  
    $application = new Application;
    return  $application->_get_fieldnames($include_id); 
  }
  function request_field_values($include_id = false) 
  {
    $fieldnames = Application::get_fields($include_id);
    $nvp_array = array();
 
    foreach ($fieldnames as $fn) {
 
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
 
    }

    return $nvp_array;

  }
}
?>