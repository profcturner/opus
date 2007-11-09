<?php

/**
* PDSystem integration and support
* @package OPUS
*/

/**
* PDSystem integration and support
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/

class PDSystem
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
    if(!preg_match("/^[A-Za-x_?&]+$/", $service_name))
    {
      $waf->security_log("attempt to use invalid PDS service [$service_name]");
      $waf->log("invalid PDS service [$service_name]");
    }

    $empty_result = array();
    if($format == 'XML') $empty_result = "";

    $waf->log("requesting service $service_name from PDSystem", PEAR_LOG_DEBUG, 'debug');
    if(empty($config_sensitive['pds']['url']))
    {
      $waf->log("links to the pdsystem are not enabled, check your configuration", PEAR_LOG_DEBUG, 'debug');
      return($empty_result);
    }

    $input .= "username=" . $config_sensitive['pds']['username'] .
      "&password=" . $config_sensitive['pds']['password'];

    $url = $config_sensitive['pds']['url'] . "/pdp/controller.php?function=$service_name&$input";

    $data = @file_get_contents($url);
    if($format == 'XML')
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
      return($data);
    }
  }

  function exists()
  {
    global $config_sensitive;

    if(empty($config_sensitive['pds']['url'])) return False;
    else return True;
  }

  /**
  * obtains all the templates offered by the PDSystem for CV creation
  *
  * @return a SimpleXML object containing all the templates
  */
  function get_cv_templates()
  {
    return(PDSystem::get_data("get_cv_template_list", "", "XML"));
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
    require_once("model/User.class.php");

    $reg_number = User::get_reg_number($student_id);

    return(PDSystem::get_data("get_cv_status", "reg_number=$reg_number&", "XML"));
  }
  
  /**
  ** obtains a list of all valid CV templates for a student from the PDSystem.
  **
  ** Note that this simply explores which templates have been registered as "complete" by
  ** the student within the PDSystem. It may be that some of those templates are disallowed
  ** by the student's placement team. This logic is found elsewhere.
  **
  ** @param integer $student_id the unique user_id for the student concerned (not the student number)
  ** @return a Simple XML object containing a list of only those templates that are complete.
  **
  ** @todo the latter half of this is broken and needs rewritten. 
  **/
  function get_valid_templates($student_id)
  {
    global $log;
  
    // The PDSystem uses student numbers more directly
    $student_reg = get_login_name($student_id);
  
    // Fetch all possible templates
    $templates = PDSystem::get_cv_templates();
  
    // Fetch CV status for this student
    $cv_status = PDSystem::get_cv_status($student_id);

    // An array for the valid templates
    $valid = array();  

    foreach($templates->template as $template)
    {
      foreach($cv_status->summary as $templatestatus)
      {
        if($templatestatus->template_id == $template->id)
        {
          if($templatestatus->cv_submission_status == "COMPLETE")
            array_push($valid, $template);
        }
      }
    }
    return($valid);
  }


  /**
  ** obtains a list of all custom (archived) CVs completed by a student within the PDSystem
  **
  ** Note that these CVs may not have been allowed for use by the placement team. This logic
  ** is found elsewhere.
  **
  ** @param integer $student_id the unique user_id for the student concerned (not the student number)
  ** @return a Simple XML object containing details of the CVs, including their mime types.
  */
  function get_archived_cvs($student_id)
  {
    global $conf;
    global $log;
    global $page; // We might create a page
  
    // The PDSystem uses student numbers more directly
    $student_reg = get_login_name($student_id);
  
    $url = $conf['pdp']['host'] . "/pdp/controller.php?" .
      "function=get_archived_cvs" .
      "&reg_number=$student_reg" .
      "&username=" . $conf['pdp']['user'] . "&password=" . $conf['pdp']['pass'];
  
    //$log['security']->LogPrint("Fetching file $url");
    $file = @file_get_contents($url);
  
    if($file == FALSE)
    {
      $page = new HTMLOPUS("Error");
      $log['debug']->LogPrint("Warning! PDP_get_archived_cvs failed to access the PDSystem");
      die_gracefully("The PMS was unable to acquire the CV archive list from the PDP system.");
    }
  
    $archived_cvs = simplexml_load_string($file);
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
    global $conf;
    global $log;

    // The PDSystem uses student numbers more directly
    $student_reg = get_login_name($student_id);
  
    $url = $conf['pdp']['host'] . "/pdp/controller.php?" .
      "function=get_pdf_cv&template_id=$template_id" .
      "&reg_number=$student_reg&" .
      "username=" . $conf['pdp']['user'] . "&password=" . $conf['pdp']['pass'];
  
    //$log['security']->LogPrint("Fetching file $url");
    $file = @file_get_contents($url);
  
    if($file == FALSE)
    {
      $log['debug']->LogPrint("Warning! Unable to acquire CV from PDSystem");
      print_menu("");
      die_gracefully("The PMS was unable to acquire the CV from the PDP system.");
    }

    if(substr($file, 0, 4) !=  "%PDF")
    {
      print_menu("");
      $log['debug']->LogPrint("Warning! Unable to acquire CV from PDSystem, something came but was invalid (not a PDF)");

      output_help("PDSCVFetchFailure");
      die_gracefully("The PMS was unable to acquire a valid CV from the PDP system.<BR>It may be that a central University System is offline.<h4>warning</h4>");
    }
    return $file;
  }
}

?>