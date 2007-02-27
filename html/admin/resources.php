<?php

/**
**	resources.php
**
** This admin script allows the collection of
** downloadable resources to be managed.
**
** Initial coding : Colin Turner
**
*/

// The include files
include('common.php');
include('authenticate.php');
include('resources.php');
include('lookup.php');

// Version 3 includes

require_once('Channels.php');
require_once('Languages.php');

// Connect to the database on the server
db_connect()
  or die("Unable to connect to server");

auth_user("user");

if(!(is_company() || is_admin()))
{
  die_gracefully("Sorry, you do not have permission to access this page");
}

// The Page Header file
$page = new HTMLOPUS("Resource Administration", "configuration");

// The default mode for the global variable
if(empty ($mode)) $mode = ResourceShowList;

// Getting into the right mode for the right job
switch($mode)
{

  case ResourceShowList:
    resource_list_edit();
    break;

  case ResourceEdit:
    resource_edit();
    break;

  case ResourceUpdate:
    resource_update();
    break;

  case ResourceDelete:
    resource_delete();
    break;

  case ResourceInsert:
    resource_insert();
    break;

}

  
// Print the footer and finish the page
page_footer();


/*
**	resource_insert
**
** Adds a new resource to the system.
**
*/
function resource_insert()
{
  global $lang;
  global $lookup;
  global $company_id;
  global $PHP_SELF;


  if(!is_company() && !check_default_policy('resources', 'create'))
    die_gracefully("You do not have permission to add resources");

  if(empty($company_id))
  { 
    // Check for validity...
    $query = "SELECT * FROM resources WHERE language_id=$lang AND lookup=" . make_null($lookup);
    $result = mysql_query($query)
      or print_mysql_error2("Failure checking uniqueness", $query);
    if(mysql_num_rows($result))
      die_gracefully("This lookup already exists in this language.");
    mysql_free_result($result);
  }

  upload_resource();

  resource_list_edit();

}


/*
**	resource_delete()
**
** Deletes a resource from the system.
**
*/
function resource_delete()
{
  global $resource_id;
  global $lang;
  global $lookup;
  global $confirm;
  global $PHP_SELF;
  global $conf;        // Configuration data
  global $log;         // Access to logging
  global $company_id;


  if(!check_default_policy('resources', 'delete'))
    die_gracefully("You do not have permission to delete resources");

  if($confirm!=1){
    echo "<H2 ALIGN=\"CENTER\">Are you sure?</H2>\n";
    echo "<P ALIGN=\"CENTER\">You have selected to delete a resource for " .
         htmlspecialchars($lookup) . " for language " .
         get_language_name($lang) . " from the system." .
         " Are you absolutely sure?</P>";

    echo "<P ALIGN=\"CENTER\"><A HREF=\"$PHP_SELF?mode=ResourceDelete&" .
         "lang=$lang&company_id=$company_id&lookup=$lookup&resource_id=$resource_id&confirm=1\">" .
         "Click here to delete prompt</A></P>";
    page_footer();
    exit(0);
  }

  $query = "DELETE FROM resources WHERE resource_id=$resource_id";
  mysql_query($query)
    or print_mysql_error("Unable to delete resource.", $query);

  $query = "DELETE FROM resourcelink WHERE resource_id=$resource_id";
  mysql_query($query)
    or print_mysql_error("Unable to delete resource.", $query);


  // Delete the actual file
  unlink($conf['paths']['resources'] . ($resource_id));

  $log['admin']->LogPrint("resource $lookup deleted for language " . 
                          get_language_name($lang));

  resource_list_edit();
}  

/*
**	resource_edit
**
** Provides a form suitable for updating resource information.
*/
function resource_edit()
{
  global $smarty;
  global $resource_id;
  global $lookup;
  global $lang;
  global $log;
  global $conf;

  if(empty($resource_id))
    die_gracefully("You must specify a resource id.");

  $query = "SELECT * FROM resources WHERE resource_id=$resource_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to obtain resource data.", $query);

  $resource_info = mysql_fetch_array($result);
  mysql_free_result($result);

  $resource_info['language_name'] = get_language_name($resource_info['language_id']);
  $resource_info['channel_name'] = get_channel_name($resource_info['channel_id']);
  $resource_info['mime_type'] = get_mime_type($resource_info['mime']);
  $resource_info['file_size'] = resource_size($resource_info['resource_id']);
  $resource_info['uploader_name'] = get_user_name($resource_info['uploader']);

  $languages = Languages::get_indexed_array();
  $channels = Channels::get_indexed_array();

  $smarty->assign("resource_info", $resource_info);
  $smarty->assign("languages", $languages);
  $smarty->assign("channels", $channels);

  $smarty->display("admin/resource_directory/edit_resource.tpl");

  $log['admin']->LogPrint("Resource information viewed for editing ($lookup in $lang).");
}


/**
**	resource_update()
**
** This function attempts to update resource information
** given the results of the above form.
**
*/
function resource_update()
{
  global $lang;
  global $lookup;
  global $description;
  global $author;
  global $copyright;
  global $auth;
  global $filename;
  global $resource_id;
  global $log;
  global $userfile;
  
  $channel_id = $_REQUEST['channel_id'];
  if(!$channel_id) $channel_id="NULL";
  $language_id = $_REQUEST['language_id'];

  if(!check_default_policy('resources', 'edit'))
    die_gracefully("You do not have permission to edit resources");

  if(empty($resource_id))
    die_gracefully("You cannot access this page without a resource id.");

  if(empty($lookup))
    die_gracefully("You must specify a lookup expression");

  $query = "SELECT mime FROM resources WHERE resource_id=$resource_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to access resource information.", $query);
  $row = mysql_fetch_array($result);
  mysql_free_result($result);

  if(!validate_extension($filename, $row['mime']))
  {
    $log['security']->LogPrint("an invalid extension was used for a mime type ($filename, " .
                               get_mime_type($row['mime']) . ")");
    die_gracefully("The extension on the filename was invalid for the mime type of the file. " .
                   "This is a possible security problem and therefore you must rectify this.");
  }

  $query = "UPDATE resources SET" .
    "  filename = " . make_null($filename) .
    ", language_id = " . $language_id .
    ", channel_id = " . $channel_id . 
    ", lookup = " . make_null($lookup) .
    ", description = " . make_null($description) .
    ", auth = " . make_null($auth) .
    ", author = " . make_null($author) .
    ", copyright = " . make_null($copyright) .
    " WHERE resource_id=$resource_id";

  mysql_query($query)
    or print_mysql_error2("Unable to update resource", $query);
  $log['admin']->LogPrint("Resource updated ($lookup in $lang)");

  global $downloadname;
  $downloadname = $filename;
  if(($userfile != 'none') && !empty($userfile)) upload_resource();

  resource_list_edit();
}


?>