<?php

/**
* Handles support for webservices, the loosely coupled layer for talking with the outside world
* @package OPUS
*/

/**
* Handles support for webservices, the loosely coupled layer for talking with the outside world
*
* Both the PDSystem and OPUS use an abstracted layer to communicate with any
* Student Records System (SRS).
*
* This small class handles authentication for that task, and returning data
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
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
  * @return a PHP array, or SimpleXML object depending on format
  */
  function get_data($service_name, $input, $format = 'php')
  {
    global $config_sensitive;
    $waf =& UUWAF::get_instance();

    $waf->log("requesting service $service_name from web services", PEAR_LOG_DEBUG, 'debug');
    if(empty($config_sensitive['ws']['url']))
    {
      $waf->log("web services are not enabled, check your configuration", PEAR_LOG_DEBUG, 'debug');
      return($empty_result);
    }

    // Security check on format
    $format = strtolower($format);
    if(!in_array($format, array('php', 'xml'))) $waf->halt("error:webservices:unknown_format");

    // Perform a preg security check on $service_name
    if(!preg_match("/^[A-Za-z_]+$/", $service_name))
    {
      $waf->security_log("attempt to use invalid web service [$service_name]");
      $waf->log("invalid web service [$service_name]");
    }

    $input .= "&section=functions&mode=$format&username=" . $config_sensitive['ws']['username'] .
      "&password=" . $config_sensitive['ws']['password'];

    $url = $config_sensitive['ws']['url'] . "?function=$service_name&$input";
    $waf->log($url, PEAR_LOG_DEBUG, 'debug');

    $data = @file_get_contents($url);
    switch($format)
    {
      case "php":
        return(unserialize($data));
        break;

      case "xml":
        if(substr($data, 0, 5) == "<xmp>")
        {
          // Seems to be invalid XML, strip the xmp containers
          $data = substr($data, 5);
          // and the end
          $data =substr($data, 0, strlen($data)-6);
        }
        return(simplexml_load_string($data));
        break;

      default:
        $waf->halt("error:webservices:unknown_format");
        break;
    }
  }

  /**
  * fetches information on a staff user
  *
  * @param string $reg_number the registration number for the member of staff
  * @return an associative PHP array
  */
  function get_staff($staff_number)
  {
    $staff_members = WebServices::get_data("find_staff", "reg_num=$staff_number");
    return($staff_members[0]);
  }

  /**
  * fetches information on a student user
  *
  * @param string $reg_number the registration number for the student
  * @return an associative PHP array
  *
  * @todo should student number not take preceding s in Ulster?
  */
  function get_student($student_number)
  {
    return(WebServices::get_data("get_student_details", "banner_id=$student_number&full=0"));
  }

  /**
  * fetches a list of students on a given programme of study in a given year
  *
  * @param string $course_code the srs_ident of the programme / course
  * @param string $calendar_occurence essentially the academic year
  * @param int $course_year the year for which students should be extracted
  *
  * @return a PHP array of students
  */
  function get_students_by_course($course_code, $calendar_occurence, $course_year)
  {
    $waf =& UUWAF::get_instance();
    $waf->log("webservice get_course called for programme $course_code, calendar_occurence $calendar_occurence, programme year $course_year", PEAR_LOG_DEBUG, 'debug');

    return(WebServices::get_data("get_students_by_ppio_by_year", "ppio_code=$course_code&calendar_occurrence=$calendar_occurence&year=$course_year"));
  }
}
?>
