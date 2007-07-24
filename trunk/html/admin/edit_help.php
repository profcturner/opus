<?php

/**
* Allows help prompts to be edited
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/

// The include files
include('common.php');
include('lookup.php');
include('authenticate.php');

// Version 3 includes

require_once('Channels.class.php');
require_once('Languages.class.php');

// Connect to the database on the server
db_connect()
  or die("Unable to connect to server");

auth_user("admin");

// The Page Header file
$page = new HTMLOPUS("Help Prompt Administration", "configuration");

// The default mode for the global variable
if(empty ($mode)) $mode = "Help_List";

// Getting into the right mode for the right job
switch($mode)
{

  case "Help_List":
    Help_List();
    break;

  case "Help_Edit":
    Help_Edit();
    break;

  case "Help_Update":
    Help_Update();
    break;

  case "Help_Delete":
    Help_Delete();
    break;

  case "Help_Insert":
    Help_Insert();
    break;

}

  
// Print the footer and finish the page
$page->end();


/**
*
* Shows the currently available help and prompt entries and provides a form for a new one
*
*/
function Help_List()
{
  global $smarty;
  global $log;      // Access to the logging system

  if(!check_default_policy("help", "list"))
    die_gracefully("You are not permitted to list help prompts");

  $query = "SELECT * FROM help ORDER BY language, lookup, channel_id DESC";
  $result = mysql_query($query)
    or print_mysql_error("Unable to fetch help prompts.");

  $prompts = array();
  while($prompt = mysql_fetch_array($result))
  {
    // Don't show help not in "our" channel
    if($prompt['channel_id'] && !Channels::user_in_channel($prompt['channel_id'])) continue;

    // Augment data
    $prompt['language_name'] = get_language_name($prompt['language']);
    $prompt['channel_name'] = get_channel_name($prompt['channel_id']);

    array_push($prompts, $prompt);
  }
  mysql_free_result($result);

  $languages = Languages::get_indexed_array();
  $channels = Channels::get_indexed_array();

  $smarty->assign("prompts", $prompts);
  $smarty->assign("languages", $languages);
  $smarty->assign("channels", $channels);

  $smarty->display("admin/help_directory/list_help.tpl");

  $log['admin']->LogPrint("Help prompts listed");
}


/**
* Adds a new help prompt to the system. This might not exist in any production version.
*
* WIth the addition of channels, it almost certainly will, but it might not take this form
*/
function Help_Insert()
{
  global $lang;
  global $lookup;
  global $PHP_SELF;

  $language = (int) $_REQUEST['language'];
  $channel_id = (int) $_REQUEST['channel_id'];
  $lookup = $_REQUEST['lookup'];

  if(!$channel_id) $channel_id="NULL";

  if(!check_default_policy("help", "create"))
    die_gracefully("You are not permitted to list help prompts");
  
  $lookup = trim($lookup);
  if(empty($lookup) || strpos($lookup, " "))
    die_gracefully("Your lookup must not be empty, or contain spaces");

  // Check for validity...
  $query = "SELECT * FROM help WHERE language=$language AND channel_id <=> $channel_id AND lookup=" . make_null($lookup);
  $result = mysql_query($query)
    or print_mysql_error("Failure checking uniqueness", $query);
  if(mysql_num_rows($result))
    die_gracefully("This lookup already exists in this language and channel.");
  mysql_free_result($result);

  // Ok, now create an entry
  $query = "insert into help (language, channel_id, lookup) " .
    "values($language, $channel_id, " . make_null($lookup) . ")";

  mysql_query($query)
    or print_mysql_error2("Unable to add help prompt.", $query);

  // Get the id that was allocated
  $prompt_id = mysql_insert_id();

  Help_Edit($prompt_id);
}


/**
*
* Deletes a help prompt from the system. This might not exist is any
* production version.
*/
function Help_Delete()
{  
  global $confirm;
  global $PHP_SELF;

  $confirm = (int) $_REQUEST['confirm'];
  $id = (int) $_REQUEST['id'];

  if(!check_default_policy("help", "delete"))
    die_gracefully("You are not permitted to list help prompts");

  if($confirm!=1){
    $query = "select * from prompts where id=$id";
    $result = mysql_query($query)
      or print_mysql_error2("Unable to fetch prompt data", $query);
    $prompt = mysql_fetch_array($result);
    mysql_free_result($result);

    echo "<H2 ALIGN=\"CENTER\">Are you sure?</H2>\n";
    echo "<P ALIGN=\"CENTER\">You have selected to delete a prompt for " .
         htmlspecialchars($prompt['lookup']) . " for language " .
         get_language_name($prompt['language']);

    if($prompt['channel_id'])
      echo " in channel " . get_channel_name($prompt['channel_id']);

    echo "  from the system. Normally this " .
         "should never be done. Are you absolutely sure?</P>";

    echo "<P ALIGN=\"CENTER\"><A HREF=\"$PHP_SELF?mode=Help_Delete&" .
         "id=$id&confirm=1\">Click here to delete prompt</A></P>";
    $page->end();
    exit(0);
  }

  $query = "DELETE FROM help WHERE id=$id";
  mysql_query($query)
    or print_mysql_error("Unable to delete help prompt.", $query);

  Help_List();
}  


/**
* Provides a form suitable for updating help information.
* @param integer $id is an optional id, it will otherwise be loaded from CGI variables
*/
function Help_Edit($id = 0)
{
  global $smarty;
  global $log;

  if(!$id) $id=$_REQUEST['id'];
  $query = "select * from help where id=$id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch help information.", $query);
  $prompt_info = mysql_fetch_array($result);
  mysql_free_result($result);

  $channel_name = get_channel_name($prompt_info['channel_id']);
  $language_name = get_language_name($prompt_info['language']);
  $lookup = $prompt_info['lookup'];

  $parsed_xml = parse_xml_field($prompt_info['contents']);

  $smarty->assign("prompt_info", $prompt_info);
  $smarty->assign("language_name", $language_name);
  $smarty->assign("channel_name", $channel_name);
  $smarty->assign("parsed_xml", $parsed_xml);
  $smarty->display("admin/help_directory/edit_help.tpl");

  $log['admin']->LogPrint("Help prompt information viewed for editing ($lookup in $language_name, channel $channel_name).");
}


function Help_Update()
{
  global $smarty;
  global $log;

  $id = (int) $_REQUEST['id'];
  $description = $_REQUEST['description'];
  $contents = $_REQUEST['contents'];
  $auth = $_REQUEST['auth']; // Currently unused

  if(!check_default_policy("help", "edit"))
    die_gracefully("You are not permitted to list help prompts");

  $query = "UPDATE help SET" .
           " description = " . make_null($description) .
           ", auth = " . make_null($auth) .
           ", contents = " . make_null($contents) .
           " WHERE id=$id";

  mysql_query($query)
    or print_mysql_error2("Unable to update help prompt", $query);

  // Now check it all again...
  $query = "select * from help where id=$id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch help information.", $query);
  $prompt_info = mysql_fetch_array($result);
  mysql_free_result($result);

  $channel_name = get_channel_name($prompt_info['channel_id']);
  $language_name = get_language_name($prompt_info['language']);
  $lookup = $prompt_info['lookup'];

  $parsed_xml = parse_xml_field($prompt_info['contents']);

  $smarty->assign("prompt_info", $prompt_info);
  $smarty->assign("language_name", $language_name);
  $smarty->assign("channel_name", $channel_name);
  $smarty->assign("parsed_xml", $parsed_xml);
  $smarty->display("admin/help_directory/preview_help.tpl");
  $log['admin']->LogPrint("Help prompt updated ($lookup in $language_name, channel $channel_name)."); 
}

?>