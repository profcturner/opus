<?php

/**
* Web Services Support
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @version 4.0
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
  * runs a webservice and obtains the results
  *
  * @param string $service_name the service provided by the WS layer
  * @param string $input a standard URL style string encoding variable to pass in
  * @param string $format is the format return that is expected
  *
  * @return a SimpleXML object containing all the templates
  */
  function get_data($service_name, $input, $format = 'PHP')
  {
    global $config_sensitive;
    global $waf;

    // Perform a preg security check on $service_name
    if(!preg_match("/^[A-Za-x_]+$/", $service_name))
    {
      $waf->security_log("attempt to use invalid web service [$service_name]");
      $waf->log("invalid web service [$service_name]");
    }

    $empty_result = array();
    if($format == 'XML') $empty_result = simplexml_load_string($empty_result);

    $waf->log("requesting service $service_name from web services", PEAR_LOG_DEBUG, 'debug');
    if(empty($config_sensitive['ws']['url']))
    {
      $waf->log("web services are not enabled, check your configuration", PEAR_LOG_DEBUG, 'debug');
      return($empty_result);
    }

    $input .= "&username=" . $config_sensitive['ws']['username'] .
      "&password=" . $config_sensitive['ws']['password'];

    $url = $config_sensitive['ws']['url'] . "/$service_name.php?$input";

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