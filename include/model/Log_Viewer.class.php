<?php 

/**
* @todo policy code needs reimplemented
*/
class Log_Viewer
{
  var $available_logs;

  function __construct(&$waf, $logname, $search, $lines)
  {
    $this->available_logs = array('general', 'admin', 'debug', 'security', 'panic', 'waf_debug');
  }

  /**
  * display search form showing available logs
  *
  * @todo need to filter allowable list by using policy
  */
  function show_form()
  {
    global $waf;

    $waf->assign("available_logs", $this->available_logs);
    $waf->display();
  }

  function get_log_content($logname, $search, $lines)
  {
  }
}


if(empty($lines)) $lines = 100;

// Show the form
log_view_form();

// Check security policy for logged in user
if(!empty($logname))
{
  if(!check_default_policy('log', $logname))
    die_gracefully("Sorry, you do not have permission to view this log");
  log_view($logname, $search, $lines);
  log_view_form();
}
else output_help("AdminLogViewer");


/**
**	@function log_view_form
**
**	Provides a simple form to allow log files to be searched
**
*/
function log_view_form()
{
  global $logname;
  global $search;
  global $lines;
  global $PHP_SELF;

  echo "<FORM METHOD=\"POST\" ACTION=\"$PHP_SELF\">\n";
  echo "Log File <SELECT NAME=\"logname\">";
  echo "<OPTION ";
  if($logname == "access") echo "SELECTED ";
  echo "VALUE=\"access\">General access</OPTION>\n";
  echo "<OPTION ";
  if($logname == "admin") echo "SELECTED ";
  echo "VALUE=\"admin\">Administration access</OPTION>\n";
  echo "<OPTION ";
  if($logname == "security") echo "SELECTED ";
  echo "VALUE=\"security\">Possible security problems</OPTION>\n";
  echo "<OPTION ";
  if($logname == "system") echo "SELECTED ";
  echo "VALUE=\"system\">Cron jobs</OPTION>\n";
  echo "<OPTION ";
  if($logname == "debug") echo "SELECTED ";
  echo "VALUE=\"debug\">Debugging</OPTION>\n";
  echo "</SELECT>\n ";
  echo "  Search <INPUT NAME=\"search\" VALUE=\"$search\" SIZE=\"10\">\n";
  echo "  Lines <INPUT NAME=\"lines\" VALUE=\"$lines\" SIZE=\"4\">\n";
  echo "  <INPUT TYPE=\"SUBMIT\" VALUE=\"Submit\">\n";
  echo "</FORM>\n";
}


/**
**	@function log_view
**
**	Performs a tail of logfiles and shows the result on screen.
**
**	@param $logname is the "name" of the logfile
**	@param $search is an optional search criterion (regexp)
**	@param $lines is the last number of lines to show
*/
function log_view($logname, $search, $lines)
{
  global $conf;	// The global configuration

  // Ascertain the actual filename for the log file
  $logfile = "";
  switch($logname)
  {
    case "access" :
      $logfile = $conf['logs']['access']['file'];
      break;
    case "admin" :
      $logfile = $conf['logs']['admin']['file'];
      break;
    case "security" :
      $logfile = $conf['logs']['security']['file'];
      break;
    case "system" :
      $logfile = $conf['logs']['system']['file'];
      break;
    case "debug" :
      $logfile = $conf['logs']['debug']['file'];
      break;
  }

  if(empty($logfile))
    die_gracefully("Invalid log file");

  echo "<H3>" . htmlspecialchars($logname) . "</H3>";

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

  // For Solaris
  if(!empty($lines)) $command .= " | tail -\"$lines\"";

  // For Linux
  //if(!empty($lines)) $command .= " | tail -\"$lines\"";
  

  // Command now contains the full unix command necessary
  // Run it and get the output as a read only file.
  //echo $command;
  $handle = popen($command, "r");
  if(!$handle){
    echo "<H3>Could not open log file</H3>";
  }
  else{
    echo "<P>";
    echo "<TABLE>";
    $odd = TRUE;
    // Keep reading lines while we have stuff to read
    while(!feof($handle)){
      // Alternate colours for clarity
      echo "<TR";
      if(!$odd) echo " class=\"list_row_dark\"";
      echo "><TD";
      echo "><TT>";
      // Get a whole line and print it.
      echo htmlspecialchars(fgets($handle)) . "<BR>\n";
      echo "</TT></TD></TR>\n";
      if($odd) $odd=FALSE; else $odd=TRUE;
    }
    echo "</TABLE>";
    echo "</P>";
  }
  pclose($handle);
}

$page->end();

?>


