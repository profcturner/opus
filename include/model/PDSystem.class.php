<?php

/**
* PDSystem integration and support
* @package OPUS
*/

/**
* PDSystem integration and support
*
* This class handles all links to the PDSystem, including caching of data.
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/

class PDSystem
{
  /**
  * sends an API function to PDSystem and obtains the results
  *
  * @param string $section_name the section in which this service is found in the API
  * @param string $service_name the service provided by the API layer
  * @param string $input a standard URL style string encoding variable to pass in
  * @param string $format is the format return that is expected
  *
  * @return data encapulates as PHP arrays or XML dependant on mode called.
  */
  function get_data($section_name, $service_name, $input = "", $format = 'php')
  {
    global $config_sensitive;
    global $waf;

    $format = strtolower($format);
    if(!in_array($format, array('php', 'xml'))) $waf->halt("error:pdsystem_api:invalid_format");

    // Perform a preg security check on $service_name
    if(!preg_match("/^[A-Za-x_?&]+$/", $section_name))
    {
      $waf->security_log("attempt to use invalid PDS service [$section_name]");
      $waf->log("invalid PDS service [$section_name]");
      $waf->halt("error:pdsystem:invalid_section");
    }

    if(!preg_match("/^[A-Za-x_?&]+$/", $service_name))
    {
      $waf->security_log("attempt to use invalid PDS service [$service_name]");
      $waf->log("invalid PDS service [$service_name]");
      $waf->halt("error:pdsystem:invalid_service");
    }

    $empty_result = array();
    if($format == 'XML') $empty_result = "";

    $waf->log("requesting service $section_name:$service_name from PDSystem", PEAR_LOG_DEBUG, 'debug');
    if(empty($config_sensitive['pds']['url']))
    {
      $waf->log("links to the pdsystem are not enabled, check your configuration", PEAR_LOG_DEBUG, 'debug');
      return($empty_result);
    }

    $input .= "mode=$format&username=" . $config_sensitive['pds']['username'] .
      "&password=" . $config_sensitive['pds']['password'];

    $url = $config_sensitive['pds']['url'] . "?section=$section_name&function=$service_name&$input";

    $data = @file_get_contents($url);
    if($format == 'xml')
    {
      if(substr($data, 0, 5) == "<xmp>")
      {
        // Seems to be invalid XML, strip the xmp containers
        $data = substr($data, 5);
        // and the end
        $data = substr($data, 0, strlen($data)-6);
      }
      return(simplexml_load_string($data));
    }
    else
    {
      return(unserialize($data));
    }
  }

  function exists()
  {
    global $config_sensitive;

    if(empty($config_sensitive['pds']['url'])) return false;
    else return true;
  }

  /**
  * obtains all the templates offered by the PDSystem for CV creation
  *
  * Due to the issues of network latency, the data is cached for the default ttl
  * of the cache object.
  *
  * @return a SimpleXML object containing all the templates
  * @see Cache_Object.class.php
  */
  function get_cv_templates()
  {
    $key = "pdsystem:cv_templates";
    require_once("model/Cache_Object.class.php");

    $cv_templates_cache = new Cache_Object;
    $cached = $cv_templates_cache->load_from_cache($key);

    if($cached)
    {
      $cv_templates = $cv_templates_cache->cache;
    }
    else
    {
      // Stale or non existant
      $cv_templates = PDSystem::get_data("cv", "get_cv_template_list");
      $cv_templates_cache->update_cache($key, $cv_templates);
    }
    return($cv_templates);
  }

  /**
  * obtains the status of all the CVs for a given student.
  *
  * This includes completion information.
  *
  * @param integer $student_id the unique user_id for the student concerned (not the student number)
  * @return a SimpleXML object containing all the CV information
  */
  function get_cv_status($student_id)
  {
    // We'll be caching this
    $key = "pdsystem:cv_status:$student_id";
    require_once("model/Cache_Object.class.php");

    // The PDSystem uses student numbers more directly
    $student_reg = User::get_reg_number($student_id);

    // Quite tight time to live, since students are working between both systems
    $cv_status_cache = new Cache_Object('default', 30);
    $cached = $cv_status_cache->load_from_cache($key);

    if($cached)
    {
      $cv_status = $cv_status_cache->cache;
    }
    else
    {
      // Stale or non existant
      $cv_status = PDSystem::get_data("cv", "get_cv_status", "reg_number=$student_reg");
      $cv_status_cache->update_cache($key, $cv_status);
    }
    return($cv_status);
  }

  /**
  * obtains a list of all valid CV templates for a student from the PDSystem.
  *
  * Note that this simply explores which templates have been registered as "complete" by
  * the student within the PDSystem. It may be that some of those templates are disallowed
  * by the student's placement team. This logic is found elsewhere. There is no caching here,
  * that is all done by other underlying fetches.
  *
  * @param integer $student_id the unique user_id for the student concerned (not the student number)
  * @return a Simple XML object containing a list of only those templates that are complete.
  *
  */
  function get_valid_templates($student_id)
  {
    global $log;

    // The PDSystem uses student numbers more directly
    $student_reg = User::get_reg_number($student_id);

    // Fetch all possible templates
    $templates = PDSystem::get_cv_templates();

    // Fetch CV status for this student
    $cv_status = PDSystem::get_cv_status($student_id);

    // An array for the valid templates
    $valid = array();

    foreach($templates as $template)
    {
      foreach($cv_status as $single_cv_status)
      {
        if($single_cv_status->template_id == $template['id'])
        {
          if($single_cv_status['cv_submission_status'] == "COMPLETE")
            array_push($valid, $template);
        }
      }
    }
    return($valid);
  }

  /**
  * obtains a list of all custom (archived) CVs completed by a student within the PDSystem
  *
  * Note that these CVs may not have been allowed for use by the placement team. This logic
  * is found elsewhere.
  *
  * @param integer $student_id the unique user_id for the student concerned (not the student number)
  * @return a Simple XML object containing details of the CVs, including their mime types.
  */
  function get_archived_cvs($student_id)
  {
    // We'll be caching this
    $key = "pdsystem:archived_cvs:$student_id";
    require_once("model/Cache_Object.class.php");

    // The PDSystem uses student numbers more directly
    $student_reg = User::get_reg_number($student_id);

    // Quite tight time to live, since students are working between both systems
    $archived_cvs_cache = new Cache_Object('default', 30);
    $cached = $archived_cvs_cache->load_from_cache($key);

    if($cached)
    {
      $archived_cvs = $archived_cvs_cache->cache;
    }
    else
    {
      // Stale or non existant
      $archived_cvs = PDSystem::get_data("cv", "get_archived_cvs", "reg_number=$student_reg");
      $archived_cvs_cache->update_cache($key, $cv_templates);
    }
    return($archived_cvs);
  }

  /**
  * fetches a specific CV (by template) from the PDSystem
  *
  * If linkages to the PDSystem are broken it will complain loudly.
  *
  * @param integer $student_id the user id on OPUS for the student concerned
  * @param integer $template_id the template id to be used (from the PDSystem)
  * @return the actual PDF file as a string 
  */
  function fetch_cv($student_id, $template_id)
  {
    global $config_sensitive;
    global $waf;

    global $log;

    // The PDSystem uses student numbers more directly
    require_once("model/Student.class.php");
    $student = Student::load_by_user_id($student_id);
    $student_reg = $student->reg_number;

    $url = $config_sensitive['pds']['url'] . "/pds/?" .
      "section=cv&function=get_pdf_cv&template_id=$template_id" .
      "&reg_number=$student_reg&" .
      "username=" . $config_sensitive['pds']['username'] . "&password=" . $config_sensitive['pds']['password'];

    $file = @file_get_contents($url);

    if($file == FALSE)
    {
      $waf->log("Unable to acquire template CV from PDSystem");
      $waf->halt("error:pdsystem:no_cv");
    }

    if(substr($file, 0, 4) !=  "%PDF")
    {
      $waf->log("Unable to acquire template CV from PDSystem, something came but was invalid (not a PDF)");
      $waf->halt("error:pdsystem:invalid_cv");
    }
    return $file;
  }
}

?>