<?php

/**
* Substitutes fields in the automail templates and sends the message
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/

require_once "Mail.class.php";

/**
* substitutes template values in an email and sends it appropriately
*
* @param string $lookup the unique lookup for the template in a language
* @param string $mailfields associative array of fields to substitute
*/
function automail($lookup, $mailfields)
{
  global $conf;
  global $log;
  global $user;

  // Fetch template
  $query = "SELECT * FROM automail WHERE language=1 " .
           "AND lookup='$lookup'";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch mail template", $query);
  if(!mysql_num_rows($result)) return FALSE;
  $row = mysql_fetch_array($result);
  mysql_free_result($result);

  // Defaults from config
  $mailfields["conf_institution"]  =  $conf['instname'];
  $mailfields["conf_website"]      =  $conf['webhost'];
  $mailfields["conf_appname"]      =  $conf['appname'];
  $mailfields["atitle"]     =  $conf['proot']['title'];
  $mailfields["afirstname"] =  $conf['proot']['firstname'];
  $mailfields["asurname"]   =  $conf['proot']['surname'];
  $mailfields["aposition"]  =  $conf['proot']['position'];
  $mailfields["aemail"]     =  $conf['proot']['email'];

  // Substitute the currently logged in admin details if possible
  if(is_admin())
  {
    $squery = "SELECT * FROM admins WHERE user_id=" . get_id();
    $sresult = mysql_query($squery)
      or print_mysql_error2("Unable to fetch admin information.", $squery);
    if(mysql_num_rows($sresult))
    {
      $srow = mysql_fetch_array($sresult);
      $mailfields["atitle"]     = $srow["title"];
      $mailfields["afirstname"] = $srow["firstname"];
      $mailfields["asurname"]   = $srow["surname"];
      $mailfields["aposition"]  = $srow["position"];
      $mailfields["aemail"]     = $srow["email"];
      mysql_free_result($sresult);
    }
  }

  // Process substitutions
  $row = process_automail_subs($row, $mailfields);

  // Form necessary variables
  $extra="";
  if(!empty($row["fromh"])) $extra .= "From: " . $row["fromh"] . "\r\n";
  if(!empty($row["cch"]))   $extra .= "Cc: " . $row["cch"] . "\r\n";
  if(!empty($row["bcch"]))  $extra .= "Bcc: " . $row["bcch"] . "\r\n";

  // Add OPUS information to allow easy automatic handling
  $extra .= "X-OPUS-Automail-Lookup: $lookup\r\n";

  // Send email
  $mail_object = new OPUSMail($row["toh"], $row["subject"], $row["contents"], $extra);
  $mail_object->send();

  $log['admin']->LogPrint("Auto email $lookup sent from " . $row["fromh"] . 
                          " to " . $row["toh"]);
}

/**
* perfoms the substitution of fields in all parts of a message
*
* @param string $row the element to substitute, could be to, from, message body etc.
* @param array $mailfields an associative array of key value substitutions
* @return the processed input in $row is returned
*/
function process_automail_subs($row, $mailfields)
{
  global $log;

  // A list of database fields to substitute
  $subfields = array("toh", "fromh", "subject", "cch", "bcch", "contents");
  
  // Look through each element of the email - from, to, body etc
  foreach($subfields as $subfield)
  {
    // Look at the list to substitute
    foreach($mailfields as $key => $value)
    {
      $row[$subfield] = preg_replace("/%$key%/", $value, $row[$subfield]);
    }
  }
  return($row);
}

?>