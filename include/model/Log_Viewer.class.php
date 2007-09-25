<?php 

/*
// Check security policy for logged in user
if(!empty($logname))
{
  if(!check_default_policy('log', $logname))
    die_gracefully("Sorry, you do not have permission to view this log");
  log_view($logname, $search, $lines);
  log_view_form();
}
else output_help("AdminLogViewer");
*/


/**
* @todo policy code needs reimplemented
*/
class Log_Viewer
{
  var $available_logs;

  function __construct($logname="general", $search="", $lines=100)
  {
    global $waf;

    $this->available_logs = array('general', 'admin', 'debug', 'security', 'panic', 'cron', 'waf_debug');

    if($lines == 0) $lines = 100;
    if($logname == "") $logname = 'general';

    $waf->assign("selected_log", $logname);
    $waf->assign("search", $search);
    $waf->assign("lines", $lines);
    $waf->assign("available_logs", $this->available_logs);
    $waf->assign("log_lines", $this->get_log_content($logname, $search, $lines));
  }

//    $waf->display("admin/information/log_view.tpl");

  function get_log_content($logname, $search, $lines)
  {
    global $waf;
    global $config;

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
    else $command = "grep \"$search\" ";

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


