<?php
/**
* Encapulates reports, with a plug-in sort of mechanism
* @package OPUS
*/
require_once("dto/DTO.class.php");
/**
* Encapulates reports, with a plug-in sort of mechanism
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/

class Report
{
  /** @var array array of all the variables needed to determine data set for analysis */
  var $variables;
  /** @var int a number of stages of input expected to obtain all variables */
  var $input_stages;
  /** @var int the current stage we are at */
  var $current_stage;
  /** @var array associative array of the available output formats for the report */
  var $available_formats;
  /** @var string the format of the output, for example, csv, tsv or html */
  var $output_format;
  /** @var array the final data produced */
  var $data;
  /** @var string the name of the report for computers (unique) */
  var $unique_name;
  /** @var string the name of the report (human readable) */
  var $human_name;
  /** @var string the description of the report (human readable) */
  var $version;
  /** @var string the version number of the report */

  static $output_types = array(
    'html'=>"Show within OPUS",
    'csv'=>"Comma Separated Variable (CSV) file",
    'tsv'=>"Tab Separated Variable (TSV) file"
  );

  static $mime_types = array(
    'html'=>'text/html',
    'csv'=>'text/csv',
    'tsv'=>'text/tsv'
  );

  /**
  * constructor
  */
  function __construct()
  {
    $this->unique_name = "Unknown";
    $this->human_name = "Unknown";
    $this->description = "Unknown";
    $this->version = "Unknown";

    $this->input_stages = 0;
    $this->current_stage = 0;
    $this->available_formats = array("html");
    $this->output_format = "html";
  }

  /**
  * get an array of the available reports
  *
  * these are plugin classes found in the include/model/reports directory
  */
  function get_reports()
  {
    global $waf;
    $report_list = array();

    // Declare a test expression for valid files
    $test_expr = "/^([A-Za-z0-9_-]+)\.class\.php$/";

    $objects_added = 0;

    $waf->log("loading list of reports", PEAR_LOG_DEBUG, "debug");

    $report_classes = array();
    try
    {
      $directory = $waf->base_dir . "include/model/reports";
      $dir = new DirectoryIterator($directory);
      foreach($dir as $file)
      {
        // Only interested in files
        if(!$file->isfile()) continue;
        $filename = $file->getFilename();
        $matches = array();
        if(!preg_match($test_expr, $filename, $matches)) continue; // invalid filename
        // Remember the filename
        $matches['filename'] = $filename;

        $report_classes[$matches[0]] = $matches;
      }

      // Now step through them
      foreach($report_classes as $report_class)
      {
        $classname = $report_class[1];
        $filename = $report_class['filename'];

        $waf->log("Loading $filename", PEAR_LOG_DEBUG, "debug");
        require_once($directory . "/" . $filename);

        // Get the object details
        $object = new $classname;
        array_push($report_list, $object->get_description());
        $objects_added++;
      }
    }
    catch (RuntimeException $e)
    {
      $this->log("Error while loading the from the report", PEAR_LOG_DEBUG, "debug");
    }
    return($report_list);

  }

  /**
  * calls a given input stage (data acquisition)
  * @param int $input_stage the number of the stage of data acquisition
  * @todo need to check against available_formats
  */
  function input($input_stage)
  {
    global $waf;

    // Load what already exists
    require_once("model/Preference.class.php");
    $report_options = Preference::get_preference("report:" . $this->unique_name);

    $waf->assign("input_stages", $this->input_stages);
    $waf->assign("input_stage", $input_stage);
    $waf->assign("report_options", $report_options);

    // Need to check against available_formats
    $waf->assign("formats", self::$output_types);

    if(method_exists($this, "input_stage_$input_stage"))
    {
      call_user_func(array($this, "input_stage_$input_stage"));
    }
    else
    {
      $waf->halt("error:report:bad_stage");
    }
  }

  /**
  * calls a given input stage processing
  * @param int $input_stage the number of the stage of data acquisition
  */
  function input_do($input_stage)
  {
    global $waf;

    if(method_exists($this, "input_stage_do_$input_stage"))
    {
      call_user_func(array($this, "input_stage_do_$input_stage"));
    }
    else
    {
      $waf->halt("error:report:bad_stage");
    }
  }

  /**
  * assigns the object to smarty and calls the output template
  */
  function output_data()
  {
    global $waf;

    // Ensure this is available
    $waf->assign("report", $this);
    $waf->assign("header", $this->get_header());
    $waf->assign("body", $this->get_body());
    $waf->assign("tab", "\t");

    $format = $this->output_format;
    $template = "reports/output_data_$format.tpl";

    if($format != "html")
    {
      // We will need a content header here...
      $waf->debugging = false;
      header("Content-type: " . self::$mime_types[$format]);
      header("Content-Disposition: attachment; filename=\"opus_report.$format\"");
      $waf->display($template);
    }
    else
    {
      $waf->display("main.tpl", "admin:information:list_reports:report_output", $template);
    }
  }

  /**
  * used to obtain a list of available reports
  */
  function get_description()
  {
    return array('unique_name' => $this->unique_name, 'human_name' => $this->human_name, 'description' => $this->description, 'version' => $this->version);
  }

  /**
  * instantiates an object based on the unique name
  * @param string $unique_name the unique name of the report class
  * @see $unique_name
  */
  function make_object($unique_name)
  {
    global $waf;
    // Declare a test expression for valid files
    $test_expr = "/^([A-Za-z0-9_-]+)$/";

    $name_parts = explode(":", $unique_name);
    $classname = $name_parts[count($name_parts)-1];

    if(!preg_match($test_expr, $classname)) $waf->halt("error:invalid_report");

    require_once($waf->base_dir . "include/model/reports/" . $classname . ".class.php");
    $report = new $classname;

    return($report);
  }
}

?>