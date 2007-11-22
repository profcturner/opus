<?php

/**
* Web Services Support
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @version 3.0
* @package OPUS
*/

/**
* Web Service Support Calls
*
* Both the PDSystem and OPUS use an abstracted layer to communicate with any
* Student Records System (SRS).
*
* This small class handles authentication for that task, and returning data
*
* @package OPUS
*/
class WebServices
{

  /**
  ** Runs a webservice and obtains the
  **
  ** @return a SimpleXML object containing all the templates
  */
  function get_data($service_name, $input)
  {
    global $conf;
    global $log;
    
    if(empty($conf['webservices']['url'])) return(simplexml_load_string(""));
    
    // Perform a preg security check on $service_name
    if(!preg_match("/^[A-Za-x_]+$/", $service_name))
      die_gracefully("$service_name is an invalid web service");

    $input .= "&username=" . $conf['webservices']['username'] .
      "&password=" . $conf['webservices']['password'];
      
    $url = $conf['webservices']['url'] . "/$service_name.php?$input";
    
    $log['debug']->LogPrint($url);
    $data = @file_get_contents($url);
    if(substr($data, 0, 5) == "<xmp>")
    {
      // Seems to be invalid XML, strip the xmp containers
      $data = substr($data, 5);
      // and the end
      $data =substr($data, 0, strlen($data)-6);
    }
    //echo htmlspecialchars($data);
    return(simplexml_load_string($data));
  }
  
  function get_staff($staff_number)
  {
    return(WebServices::get_data("soap_staff_details", "reg_num=$staff_number"));
  }
  
  function get_student($student_number)
  {
    return(WebServices::get_data("soap_person_ss", "reg_num=$student_number"));  
  }
  
  function get_course($course_code, $calendar_occurence, $course_year)
  {
    return(WebServices::get_data("soap_course", "course_code=$course_code&calendar_occurrence=$calendar_occurence&course_year=$course_year"));  
  }
  
  

}
?>