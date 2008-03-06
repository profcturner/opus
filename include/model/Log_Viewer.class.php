<?php 

/**
* Handles the display and searching of log files
* @package OPUS
*/

/**
* Handles the display and searching of log files
*
* This uses some unix like command line tools for elegance and speed, such as
* grep, cat and tail. I might reimplement this for Windows sometime, but I
* would suggest trying to obtain ports of these very simple, free utilities.
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/

class Log_Viewer
{
  var $available_logs;

  function __construct($logname="general", $search="", $lines=100)
  {
    global $waf;

    $this->available_logs = array('general', 'admin', 'debug', 'security', 'panic', 'cron', 'waf_debug', 'php_errors');

    if($lines == 0) $lines = 100;
    if($logname == "") $logname = 'general';

    require_once("model/Policy.class.php");
    if(!Policy::check_default_policy("log", $logname))
    {
      // The user will be trapped here, so we default back to something that should be safe
      require_once("model/Preference.class.php");
      $form_options = Preference::get_preference("log_viewer_form");
      $form_options['logfile'] = 'general';
      Preference::set_preference("log_viewer_form", $form_options);
      $waf->halt("error:policy:permission");
    }

    $waf->assign("selected_log", $logname);
    $waf->assign("search", $search);
    $waf->assign("lines", $lines);
    $waf->assign("available_logs", $this->available_logs);
    $waf->assign("log_lines", $this->get_log_content($logname, $search, $lines));
    $waf->assign("log_size", $this->get_log_size($logname));
  }

  function get_log_size($logname)
  {
    global $waf;
    global $config;

    if(!in_array($logname, $this->available_logs))
    {
      $waf->security_log("Illegal log name $logname attempted");
      $waf->halt("error:admin:illegal_log");
    }
    $logfile = $waf->log_dir . $logname . ".log";

    $filesize = @filesize($logfile);

    $kilobyte = 1024;
    $megabyte = 1024*1024;
    $gigabyte = 1024*1024*1024;

    if((int) ($filesize / $gigabyte))
    {
      $filesize_text = ($filesize / $gigabyte);
      return(sprintf("%.2f GB", $filesize_text));
    }
    if((int) ($filesize / $megabyte))
    {
      $filesize_text = ($filesize / $megabyte);
      return(sprintf("%.2f MB", $filesize_text));
    }
    if((int) ($filesize / $kilobyte))
    {
      $filesize_text = ($filesize / $kilobyte);
      return(sprintf("%.2f kB", $filesize_text));
    }
    return("$filesize bytes");
  }

  function get_log_content($logname, $search, $lines)
  {
    global $waf;
    global $config;

    $lines = (int) $lines; // security

    if(!in_array($logname, $this->available_logs))
    {
      $waf->security_log("Illegal log name $logname attempted");
      $waf->halt("error:admin:illegal_log");
    }
    $logfile = $waf->log_dir . $logname . ".log";
    // Start to form the command line
    // We will use cat to list the file if no search criterion
    // is given, other we use grep.
    // Encapsulate the search string in quotes to prevent hacking!
    if(empty($search)) $command = "cat ";
    else $command = "grep " . escapeshellarg($search) ." ";

    // Add the log filename to the end of the command so far.
    $command .= $logfile;

    // Provided a limit has been specified on the number of lines
    // to show, pipe the output from the above commant to tail.
    // Escapsulate the $lines variable in quotes for safety
    if(!empty($lines)) $command .= " | tail -\"$lines\"";

    // Command now contains the full unix command necessary
    // Run it and get the output as a read only file.
    //echo $command;
    //echo "debug: $command";
    $handle = popen($command, "r");
    if(!$handle){
      $waf->log("unable to open logfile", PEAR_LOG_ERR, 'admin');
      $waf->log("unable to open logfile $logfile", PEAR_LOG_ERR, 'debug');
      $waf->halt("error:log_view:no_access");
    }
    else{
      $log_lines = array();
      // Keep reading lines while we have stuff to read
      while(!feof($handle)){
        array_push($log_lines, str_replace(array('\r', '\n'), "", fgets($handle)));
      }
    }
    pclose($handle);
    // Remove the empty line if no entries where found
    unset($log_lines[count($log_lines)-1]);
    return($log_lines);
  }
}

?>


