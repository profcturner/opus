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
  /** @var string the format of the output, for example, csv, tsv or html */
  var $output_format;
  /** @var array the final data produced */
  var $data;

  var $current_stage;

  /**
  * get an array of the available reports
  */
  function get_reports()
  {
  }

  function input()
  {
    if($this->current_stage < $this->input_stages)
    {
      $stage = ++$this->current_stage;
      if(method_exists($this, "input_stage_$stage"))
      {
        call_user_func(array($this, "input_stage_$stage"));
      }
    }
  }

  function output_data()
  {
    global $waf;

    // Ensure this is available
    $waf->assign("report", $this);
    $waf->display("reports/output_data_$output_format.tpl");
  }


}





?>