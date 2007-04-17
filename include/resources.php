<?php

/**
* resources.php
*
* Common resources functions for viewing and editing
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
* @todo encapsulate in a class
*
*/

require_once('Channels.class.php');
require_once('Languages.class.php');

/**
* Shows the currently available resource entries. With appropriate links to
* edit them.
* @todo probably want to revisit this and get channel permissions worked in
*/
function resource_list_edit()
{
  global $smarty;
  global $log;      // Access to the logging system
  global $conf;     // Access to the configuration

  $company_id = (int) $_REQUEST['company_id'];
  $vacancy_id = (int) $_REQUEST['vacancy_id'];

  if(!check_default_policy('resources', 'list'))
    die_gracefully("You do not have permission to list resources");

  // If we are looking at a company - make that clear
  if(!empty($company_id))
  {
     $company_name = get_company_name($company_id);
     $query = "SELECT resources.* FROM resources, resourcelink WHERE " .
             "resourcelink.resource_id = resources.resource_id AND " .
             "resourcelink.company_id=$company_id ORDER BY language_id, lookup";
    $log['admin']->LogPrint("Resources for $company_name viewed for editing");
  }
  else
  {
    $query = "SELECT * FROM resources WHERE (NOT FIND_IN_SET('private', status) <=> NULL) ORDER BY language_id, channel_id DESC, lookup";
    $log['admin']->LogPrint("Resources viewed for editing");
  }
  $result = mysql_query($query)
    or print_mysql_error("Unable to fetch resources.");

  $resources = array();
  while($resource = mysql_fetch_array($result))
  {
    // Augment the array a little
    $resource['language_name'] = get_language_name($resource['language_id']);
    $resource['channel_name'] = get_channel_name($resource['channel_id']);
    array_push($resources, $resource);
  }
  mysql_free_result($result);

  $smarty->assign("company_id", $company_id);
  $smarty->assign("company_name", $company_name);
  $smarty->assign("resources", $resources);

  $smarty->display("admin/resource_directory/list_resources.tpl");

  resource_add_form();
}


function resource_add_form()
{
  global $smarty;
  global $log;      // Access to the logging system
  global $conf;     // Access to the configuration

  $company_id = (int) $_REQUEST['company_id'];
  $vacancy_id = (int) $_REQUEST['vacancy_id'];
  
  $channels = Channels::get_indexed_array();
  $languages = Languages::get_indexed_array();

  $smarty->assign("company_id", $company_id);
  $smarty->assign("vacancy_id", $vacancy_id);
  $smarty->assign("channels", $channels);
  $smarty->assign("languages", $languages);

  $smarty->display("admin/resource_directory/add_resource.tpl");

  output_help("AdminResourceAdd");
}



/**
**	upload_resource
**
** This function processes the result of a resource upload
** ensuring that the file is intact, and meets necessary criteria.
** It creates an entry in the resources table.
**
*/
function upload_resource()
{
  global $conf; // The configuration
  global $log;  // Logging support
  global $user; // User details
  global $lookup, $lang, $description, $downloadname, $author, $copyright, $auth; // cgi data
  global $resource_id; // If this is set, this is a file replacement!

  // New fields to add
  global $category_id;
  $category_id = 'NULL';
  global $status;
  global $company_id;
  
  $channel_id = (int) $_REQUEST['channel_id'];
  if(!$channel_id) $channel_id="NULL";

  // Company resources are created as "private"
  if(is_company() || !empty($company_id)){
    $status='private';
  }
  else
  {
    if(empty($lookup))
    {
      die_gracefully("A lookup must be specified");
      unlink($_FILES['userfile']['tmp_name']);

    }
  }

  // Check for various failures...
  switch($_FILES['userfile']['error'])
  {
    case UPLOAD_ERR_INI_SIZE:
    case UPLOAD_ERR_FORM_SIZE:
      die_gracefully("Sorry, your file is above permitted maximum size.");
      break;
    case UPLOAD_ERR_PARTIAL:
      // We need to delete the partial upload (security)
      unlink($_FILES['userfile']['tmp_name']);   
      die_gracefully("Sorry, but your upload failed part way through");
      break;
    case UPLOAD_ERR_NO_FILE:
      die_gracefully("Sorry, but no file was received.");
      break;
  }

  // Check for the mime-type, and ensure it is permitted
  $query = "SELECT * FROM mime_types WHERE type=" . make_null($_FILES['userfile']['type']);
  $result = mysql_query($query)
    or print_mysql_error2("Unable to access mime type information.", $query);
  if(!mysql_num_rows($result))
  {
    unlink($_FILES['userfile']['tmp_name']);
    die_gracefully("Sorry, this file is not of an allowed type for upload.");
  }
  $data = mysql_fetch_array($result);
  if(!strstr($data['flags'], "uploadable"))
  {
    unlink($_FILES['userfile']['tmp_name']);
    die_gracefully("Sorry, this file is not of an allowed type for upload.");
  }

  if(!validate_extension($downloadname, $data['mime_id']))
  {
    $log['security']->LogPrint("attempt made to upload file with wrong extension (" .
                               $downloadname . ", " . get_mime_type($data['mime_id']) . ")");
    die_gracefully("The download filename you have specified is not permitted for " .
                   "this file type. This is a potential security risk and so the file " .
                   "has been rejected.");
    unlink($_FILES['userfile']['tmp_name']);
  }

  if(!empty($company_id))
  {
    $space_allowed = get_allocation($company_id);
    $space_used    = used_allocation($company_id);
    // Check there is enough space remaining
    if(filesize($_FILES['userfile']['tmp_name']) > 
        (get_allocation($company_id) - used_allocation($company_id)))
    {
      $log['access']->LogPrint("Insufficient remaining allocation for upload");
      die_gracefully("The uploaded file is larger than your remaining allocation for " .
                     "uploaded files. Either delete other resources to make more space " .
                     "or appeal to the administrator for extra space.<BR>" .
                     "Space allocation :$space_allowed<BR>" .
                     "Space used       :$space_used<BR>");
      unlink($_FILES['userfile']['tmp_name']);
    }
  }

  if(empty($resource_id)){
    $user_id = get_id();
    // Ok, all is well, so let's make a new entry in the resources table
    $query = "insert into resources (lookup, " .
      "description, author, copyright, auth, filename, status, created, " .
      "uploader, mime, language_id, channel_id, category_id) values(" .
      make_null($lookup) . ", " .
      make_null($description) . ", " .
      make_null($author) . ", " .
      make_null($copyright) . ", " .
      make_null($auth) . ", " .
      make_null($downloadname) . ", " .
      make_null($status) . ", " .
      date("YmdHis") . ", " .
      "$user_id, " . $data['mime_id'] . ", " .
      "$lang, $channel_id, $category_id)";
    
    mysql_query($query)
      or print_mysql_error2("Unable to update resources table", $query);

    // Fetch the resource id allocated.
    $resource_id = mysql_insert_id();

    if(!empty($company_id))
    {
      $query = "INSERT INTO resourcelink (resource_id, company_id) " .
	"VALUES($resource_id, $company_id)";

      mysql_query($query)
	or print_mysql_error2("Unable to update resources link table", $query);

      $query = "UPDATE resources SET lookup='PRIVATE:$resource_id' WHERE resource_id=$resource_id";
      mysql_query($query)
	or print_mysql_error2("Unable to update resources link table", $query);
      
    }
  }
  if(move_uploaded_file($_FILES['userfile']['tmp_name'], $conf['paths']['resources'] . $resource_id)){
    $log['access']->logprint("Upload of resource successful");
  }
  else $log['security']->logprint("Possible attempt to breach upload security on file " . $_FILES['userfile']['tmp_name']);

}

function get_allocation($company_id)
{
  global $conf;

  $query = "SELECT allocation FROM companies WHERE company_id=$company_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to obtain allocation information.", $query);
  $row = mysql_fetch_array($result);

  if(empty($row["allocation"]))
  {
    $allocation = $conf['prefs']['allocation'];
  }
  else
  {
    $allocation = $row["allocation"];
  }
  $allocation *= 1024;
  mysql_free_result($result);
  return($allocation);
}


/**
**	used_allocation
**
** Calculates the used space allocation for a company.
**
** @var	$company_id;	
**
*/
function used_allocation($company_id)
{
  $allocation = 0;

  $query = "SELECT * FROM resourcelink WHERE company_id=$company_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to obtain allocation information.", $query);
  while($row = mysql_fetch_array($result))
  {
    $allocation += resource_size($row["resource_id"]);
  }
  mysql_free_result($result);

  return($allocation);
}


function resource_size($resource_id)
{
  global $conf;

  return(filesize($conf["paths"]["resources"] . $resource_id));
}


/**
**	authenticate_resource
**
** This function checks if we are authenticated for the resource.
**
** Now $auth passed into the function is essentially the authentication
** level of the viewer.
*/
function authenticate_resource($resource_id, $auth)
{
  $query = "SELECT auth FROM resources WHERE resource_id=$resource_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to obtain resource authentication information.", $query);
  $row = mysql_fetch_array($result);
  mysql_free_result($result);

  $allowed = FALSE;
  // Ok, authenticate the user against this;
  $auth_parts = explode(" ", $row['auth']);
  if(is_admin())
    $allowed = TRUE;
  else{
    foreach($auth_parts as $auth_part){
      if($auth_part == $auth) $allowed = TRUE;
      if($auth_part == 'all') $allowed = TRUE;
    }
  }
  // Check for the effects of exclusions now
  if($allowed == TRUE){
    foreach($auth_parts as $auth_part)
      if($auth_part == ("!" . $auth)) $allowed = FALSE;
  }
  return($allowed);
}
  

/**
**	download_resource
**
** This function supports the download of items that are specified in the
** resource table, provided authenetication is given. It also updates the
** download counters and times.
**
**	resource_id	The id as specified in resources
**	auth		The authentication level of the user
**
*/
function download_resource($resource_id, $auth)
{
  // We need the configuration data
  global $conf;

  // Fetch resource data
  $query = "SELECT * FROM resources WHERE resource_id=$resource_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch resource $resource_id");

  // Is there such a resource?
  if(!mysql_num_rows($result)) die_gracefully("No such resource is found.");

  $data = mysql_fetch_array($result);
  mysql_free_result($result);

  if(!authenticate_resource($resource_id, $auth))
    die_gracefully("Sorry, you do not have permission to access this file.");

  // Download is permitted, check file system data
  $absolute_name = $conf['paths']['resources'] . $resource_id;

  if(!file_exists($absolute_name))
    die_gracefully("Resource file is missing.");

  if(!is_readable($absolute_name))
    die_gracefully("Unable to access resource file.");

  $filesize = filesize($absolute_name);
  $mime_type = get_mime_type($data['mime']);

  header("Content-type: " . $mime_type);
  header("Content-Length: " . $filesize);
  header("Content-Disposition: inline; filename=" . $data['filename']);

  if(!$file = fopen($absolute_name, "rb"))
    die_gracefully("Unable to open resource file.");
  else
    fpassthru($file);

  // Update relevant counters etc...
  $query = "UPDATE resources SET dcounter=" . ($data['dcounter']+1) .
           ", downloaded=" . date("YmdHis") . " WHERE resource_id=$resource_id";

  mysql_query($query) or
    print_mysql_error2("Unable to update download data.", $query);
}

/**
**      validate_extension()
**
** This function ensures that the extension of a
** filename is one of the permitted extensions listed
** for the appropriate mime type.
**
**      $filename       The complete filename;
**      $mime_id        The mime id to lookup.
*/
function validate_extension($filename, $mime_id)
{
  $query = "SELECT * FROM mime_types WHERE mime_id=$mime_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to obtain mime type information.", $query);

  $row = mysql_fetch_array($result);

  $extensions = explode(" ", $row['extensions']);
  foreach($extensions as $extension)
  {
    //echo "Debug...\n $filename\n";
    //echo "<P>Cmp " . substr($filename, -(strlen($extension))) .
    //     " : " . $extension . "</P>\n";
    if(!strcasecmp(substr($filename, -(strlen($extension))), $extension))
      return(TRUE);
  }
  return(FALSE);
}



?>
