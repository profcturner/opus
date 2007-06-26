<?php

/**
* Code called from all over OPUS
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
* @todo most of this needs tidied and/or encapsulated for 4.0.0
*
*/

// Various other files should be included

include('config.php');
include('html.class.php');
include('log.php');
include('xmldisplay.php');
include('xmldisplay2.php');

require_once('Channels.class.php');

$log = array();

$log['system']   = new Log($conf['logs']['system']['file'], NULL);
$log['debug']    = new Log($conf['logs']['debug']['file'], $PHP_AUTH_USER);
$log['admin']    = new Log($conf['logs']['admin']['file'], $PHP_AUTH_USER);

class OPUS
{
  function get_version()
  {
    return ("3.3.0");
  }
}


/**
**	db_connect()
**
** This function connects to the database where possible
*/
function db_connect()
{
  global $conf; // We need access to the configuration

  $link = @mysql_pconnect(
          $conf['database']['host'],
          $conf['database']['username'],
          $conf['database']['password']);          

  if ($link && mysql_select_db($conf['database']['database']))
    return ($link);
  return (false);
}


/**
**	die_gracefully()
**
** An alternative to die() which attempts to end the page
** to produce a renderable result.
**
*/
function die_gracefully($die_error)
{
  printf("<P>An error has occured : <BR>%s</P>", $die_error);

  // finish page
  global $page;
  $page->end();
  //page_footer();

  // quit the script
  exit;
}


/**
**	die_gracefully_help()
**
** As above, but it attempts to output a help prompt in the
** dying moments.
**
*/
function die_gracefully_help($die_error)
{
  output_help($die_error);

  // finish page
  global $page;
  $page->end();
  //page_footer();

  // quit the script
  exit;
}



/**
**      print_mysql_error()  DEPRECATED
**
** A function for printing mysql errors for examination.
**
*/
function print_mysql_error($die_error)
{
  global $conf;   // We need access to the configuration

  printf("<P>A MySQL error occured<BR>(%u) : %s</P>",
    mysql_errno(), mysql_error());

  printf("<P>If this error persists, please report it to ");
  printf("<A HREF=\"mailto:%s\">%s</A></P>\n",
    $conf['email']['webmaster'],
    htmlspecialchars($conf['name']['webmaster']));

  die_gracefully($die_error);
}


/**
**      print_mysql_error2()
**
** A function for printing mysql errors for examination.
**
*/
function print_mysql_error2($die_error, $query)
{
  global $conf;   // We need access to the configuration
  global $log;    // and logging

  printf("<P>A MySQL error occured<BR>(%u) : %s</P>",
    mysql_errno(), mysql_error());

  printf("<P>If this error persists, please report it to ");
  printf("<A HREF=\"mailto:%s\">%s</A></P>\n",
    $conf['email']['webmaster'],
    htmlspecialchars($conf['name']['webmaster']));
  
  if(isset($log["debug"])){
    $log_line = "MYSQLERROR : (" . mysql_errno() .
                ") " . mysql_error() . "USERERROR : [$die_error] QUERY : [$query]";
                
    $log["debug"]->LogPrint($log_line);
  }
   
  die_gracefully($die_error);
}


/**
**	make_null
**
** This function checks the input string and if it is
** empty returns an unquoted NULL, but otherwise returns
** the string encapsulated in quotes. Ideal for
** pre-processing strings for inclusion into a database.
**
*/
function make_null($input)
{
  
  if(empty($input)) return("NULL");
  if($input == "NULL") return("NULL");
  return("'$input'");

}


function get_academic_year()
{
  global $conf;

  if(empty($year)){
    if(date("md") < $conf["prefs"]["yearstart"]) $year = date("Y") - 1;
    else $year = date("Y");
  }
  return($year);
}


function output_help($lookup, $user_id=0)
{
  global $conf;
  
  // Are we acting on behalf of another user?
  if(!$user_id) $user_id = get_id();

  //$_SESSION['display_prefs']['edit_channels']=true;
  if(empty($_SESSION['user']['language'])) $language = 1;
  else $language = $_SESSION['user']['language'];

  $query = "SELECT * FROM help WHERE language=" . make_null($language) .
           " AND lookup=" . make_null($lookup) . " order by channel_id";
  $result = mysql_query($query)
    or print_mysql_error("Unable to fetch help or prompt information.");
  while($prompt = mysql_fetch_array($result))
  {
    //echo "Debug: Help found for channel [" . get_channel_name($prompt['channel_id']) . "]";
    // Skip prompts for channels we are not in
    if($prompt['channel_id'] && !Channels::user_in_channel($prompt['channel_id'], $user_id))
    {
      //echo "... invalid channel for this user<br />";
      continue;
    }
    //echo " valid... help follows<br />";
    echo "<div class=\"channel_help\">\n";
    if($_SESSION['display_prefs']['edit_channels'] == true)
    {
      echo "<p class=\"channel_help_title\">" .
        "<a class=\"channel_help_title\" href=\"" . $conf['scripts']['admin']['edithelp'] . "?mode=Help_Edit&id=" .
        $prompt['id'] . "\">" .
        htmlspecialchars($prompt['lookup'] . ":" . get_channel_name($prompt['channel_id'])) . 
        "</a></p>\n";
    }
    output_xml_field($prompt['contents']);
    echo "</div>\n";
  }
  mysql_free_result($result);
}


function check_for_help($lookup)
{
  global $user;
  
  if(empty($_SESSION['user']['language'])) $language = 1;
  else $language = $_SESSION['user']['language'];
  
  $query = "SELECT * FROM help WHERE language=" . make_null($language) .
           " AND lookup=" . make_null($lookup);
  $result = mysql_query($query)
    or print_mysql_error("Unable to fetch help or prompt information.");

  if(mysql_num_rows($result)){
    $help = TRUE;
  }
  else $help = FALSE;
  mysql_free_result($result);
  return($help);
}


/**
 **@function parse_and_check_date()
 **
 **Takes an input date and parses it according to the user date format.
 **
 **@var $input_date the date given by the user
 **@return an associative array with several key components, 'sql', which
 **is the sql format date, 'dd', 'mm' and 'yyyy' which should be self explanatory
 **and 'valid' which is the boolean output of the main PHP function checkdate.
 */
function parse_and_check_date($input_date)
{
  //echo "Input : $input_date <br />";
  // Split off anything after a space
  $input_date = trim($input_date);
  $chunks = explode(" ", $input_date);
  $input_date = $chunks[0];
  
  
  $split_on      = "[/.-]";
  $user_format   = split($split_on, $_SESSION['user']['dateformat']);
  $date_supplied = split($split_on, $input_date);

  $date=array();

  for($index=0; $index < count($user_format); $index++)
  {
    if($user_format[$index] == 'dd')
    {
      $date["dd"]  = $date_supplied[$index];
    }
    if($user_format[$index] == 'mm')
    {
      $date["mm"]  = $date_supplied[$index];
    }
    if($user_format[$index] == 'yyyy')
    {
      $date["yyyy"]  = $date_supplied[$index];
    }
  }
  if(empty($input_date))
  {
    $date["sql"] = "NULL";
    $date["valid"] = FALSE;
  }
  else
  {
    $date["sql"] = $date["yyyy"] . $date["mm"] . $date["dd"];
    $date["valid"] = checkdate($date["mm"], $date["dd"], $date["yyyy"]);
  }
  
  //print_r($date);
  //echo "<br/>";


  return($date);
}

function output_date($input_date)
{
  global $user;

  $patterns = array("dd", "mm", "yyyy");
  $replace  = array("d", "m", "Y");

  $php_format = preg_replace($patterns, $replace, $_SESSION['user']['dateformat']);
  date($php_format, $input_date);
}

/**
 ** A PHP4 version of microtime(TRUE)
 **
 */
function microtime_float()
{
   list($usec, $sec) = explode(" ", microtime());
   return ((float)$usec + (float)$sec);
}

function backend_lookup($table, $human, $id, $id_match)
{
  $query = sprintf("SELECT %s FROM %s WHERE %s=%s",
                   $human, $table, $id, $id_match);

  $result = mysql_query($query)
    or print_mysql_error2("Backend lookup failed. ($table.$human)", $query);
  $row = mysql_fetch_row($result); 
  mysql_free_result($result);

  return($row[0]);
}


?>
