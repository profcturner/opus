<?php

/**
**	resources.php
**
** This script allows viewing of resource files.
**
** Initial coding : Colin Turner
**
*/

// The include files
require_once('common.php');
require_once('authenticate.php');
require_once('resources.php');
require_once('lookup.php');

// Connect to the database on the server
db_connect()
  or die("Unable to connect to server");

// All users can access this script, further security is implemented in
// the resources code itself.
auth_user("user");

// Just who is this person? Primitive for now, this will be beefed up...
if(is_student()) $auth_string = "student";
if(is_company()) $auth_string = "contact";
if(is_staff())   $auth_string = "staff";

// Do we have a resource id?
if(!empty($resource_id)) $mode=DownloadResource;

if(empty($mode)) $mode=ResourceShowList;

// Show HTML only if appropriate!
if($mode != DownloadResource){
  if(is_student())
  {
    $page = new HTMLOPUS('Resources', 'mycareer', 'resources');
  }
  else
  {
    $page = new HTMLOPUS('Resources', 'information', 'resources');
  }
}


switch($mode)
{
  case ResourceShowList:
    resource_show_list();
    break;
  case DownloadResource:
    download_resource($resource_id, $auth_string);
    break;
}

// Print the footer and finish the page
if($mode != DownloadResource){
  $page->end();
}


/**
**	resource_show_list
**
** Shows the currently available resource entries.
** These are entries the current user can download!
**
*/
function resource_show_list()
{
  global $smarty;
  global $PHP_SELF; // A reference back to the script
  global $log;      // Access to the logging system
  global $conf;     // Access to the configuration
  global $auth_string;

  $query = "SELECT * FROM resources ORDER BY language_id, channel_id DESC, description";
  $result = mysql_query($query)
    or print_mysql_error("Unable to fetch resources.");

  $resources = array();
  while($resource = mysql_fetch_array($result))
  {
    // Don't show private resources
    if(strstr($resource['status'], "private")) continue;
    
    // Don't show resources where we fail basic authentication
    if(!authenticate_resource($resource['resource_id'], $auth_string)) continue;
    
    // Don't show resource if there is a channel, and we are not in it!
    if($resource['channel_id'] && !Channels::user_in_channel($resource['channel_id'])) continue;
    
    // Ok, then we can show it
    $resource['channel_name'] = get_channel_name($resource['channel_id']);
    if($resource['channel_id'])
    {
      $resource['channel_description'] = backend_lookup("channels", "description", "channel_id", $resource['channel_id']);
    }
    $resource['language_name'] = get_language_name($resource['language_id']);
    array_push($resources, $resource);
  }
  mysql_free_result($result);
  
  $smarty->assign("resources", $resources);
  $smarty->assign("count", count($resources));
  
  $smarty->display("all_users/list_resources.tpl");
  
  $log['access']->LogPrint("Resources listed, $count available");
}


/*
**	resource_insert
**
** Adds a new resource to the system.
*/
function resource_insert()
{
  global $lang;
  global $lookup;
  global $PHP_SELF;

  // Check for validity...
  $query = "SELECT * FROM help WHERE language=$lang AND lookup=" . make_null($lookup);
  $result = mysql_query($query)
    or print_mysql_error("Failure checking uniqueness", $query);
  if(mysql_num_rows($result))
    die_gracefully("This lookup already exists in this language.");
  mysql_free_result($result);

  upload_resource();

  resource_show_list();

}


/*
**	delete_help
**
** Deletes a help prompt from the system. This might not exist is any
** production version.
*/
function delete_help()
{
  global $lang;
  global $lookup;
  global $confirm;
  global $PHP_SELF;

  if($confirm!=1){
    echo "<H2 ALIGN=\"CENTER\">Are you sure?</H2>\n";
    echo "<P ALIGN=\"CENTER\">You have selected to delete a prompt for " .
         htmlspecialchars($lookup) . " for language " .
         get_language_name($lang) . " from the system. Normally this " .
         "should never be done. Are you absolutely sure?</P>";

    echo "<P ALIGN=\"CENTER\"><A HREF=\"$PHP_SELF?mode=EditHelpDeleteHelp&" .
         "lang=$lang&lookup=$lookup&confirm=1\">Click here to delete prompt</A></P>";
    $page->end();
    exit(0);
  }

  $query = "DELETE FROM help WHERE language=$lang AND lookup=" . make_null($lookup);
  mysql_query($query)
    or print_mysql_error("Unable to delete help prompt.", $query);

  list_help();
}  

/*
**	edit_help
**
** Provides a form suitable for updating help information.
*/
function edit_help()
{
  global $lookup;
  global $lang;
  global $log;

  display_help_form($lang, $lookup);

  $log['admin']->LogPrint("Help prompt information viewed for editing ($lookup in $lang).");
}


/*
**	preview_help
**
** Shows the help when properly parsed in XML.
*/
function preview_help()
{
  global $lookup;
  global $lang;
  global $PHP_SELF;

  $query = "SELECT * FROM help WHERE language=" . make_null($lang) .
           " AND lookup=" . make_null($lookup);
  $result = mysql_query($query)
    or print_mysql_error("Unable to fetch help or prompt information.");
  $values = mysql_fetch_array($result);  

  printf("<H2 ALIGN=\"CENTER\">Previewing lookup [%s]</H2>\n",
    htmlspecialchars($lookup));

  printf("<P ALIGN=\"CENTER\">Language is %s</P>\n",
    htmlspecialchars(get_language_name($lang)));

  output_xml_field($values['contents']);

  echo "<HR>\n";
  echo "<P ALIGN=\"CENTER\"><A HREF=\"$PHP_SELF?mode=EditHelpEditHelp" .
       "&lang=$lang&lookup=$lookup\">Edit this entry</A></P>";
  echo "<P ALIGN=\"CENTER\"><A HREF=\"$PHP_SELF\">Back to Main Help page</A></P>\n";
}


/*
**	display_help_form
*/
function display_help_form($language, $lookup)
{
  global $PHP_SELF;

  if(empty($language))
    die_gracefully("This page should not be accessed without a language id.");
 
  if(!empty($lookup))
  {
    $query = sprintf("SELECT * FROM help WHERE language=%s AND lookup=%s",
                     $language, make_null($lookup));
    $result = mysql_query($query)
      or print_mysql_error("Unable to fetch specific help prompt");
    $values = mysql_fetch_array($result);

    printf("<H2 ALIGN=\"CENTER\">Edit Help Prompt<BR>%s</H2>\n",
      htmlspecialchars($values['lookup']));
  }
  else{
    printf("<H2 ALIGN=\"CENTER\">Add Help Prompt</H2>\n");
  }
  
  printf("<P ALIGN=\"CENTER\">Language is %s</P>\n",
    htmlspecialchars(get_language_name($language)));
 
  if(!empty($lookup)){
    printf("<FORM METHOD=\"post\" ACTION=\"%s?mode=%s&lang=%s&lookup=%s\">\n",
            $PHP_SELF, EditHelpUpdateHelp, $language, $lookup);
  }
  else{
    printf("<FORM METHOD=\"post\" ACTION=\"%s?mode=%s&lang=%s\">\n",
            $PHP_SELF, EditHelpAddHelp, $language);
  }
    
  printf("<TABLE ALIGN=\"CENTER\">\n");

  //if(!empty($lookup))
  
  printf("<TR><th>Lookup</th>\n<TD>");
  printf("<INPUT TYPE=\"TEXT\" SIZE=\"50\" NAME=\"newlookup\" VALUE=\"%s\"></TD></TR>\n", $values['lookup']);
  printf("<TR>\n<th>Description</th>\n<TD>\n");
  printf("<INPUT TYPE\"TEXT\" SIZE=\"50\" NAME=\"description\" VALUE=\"%s\">\n</TD>\n</TR>", $values['description']);
  printf("<TR>\n<th>Authorization</th>\n<TD>\n");
  printf("<INPUT TYPE\"TEXT\" SIZE=\"50\" NAME=\"auth\" VALUE=\"%s\">\n</TD>\n</TR>", $values['auth']);

  printf("<TR><th ALIGN=\"CENTER\" COLSPAN=\"2\">Contents</th></TR>\n");
  printf("<TR><TD ALIGN=\"CENTER\" COLSPAN=\"2\"><TEXTAREA ROWS=\"30\" COLS=\"50\"  NAME=\"contents\">");

  printf("%s", htmlspecialchars($values['contents']));
  printf("</TEXTAREA></TD></TR>\n");

  printf("<TR><TD ALIGN=\"CENTER\" COLSPAN=\"2\"><INPUT TYPE=\"submit\" NAME=\"button\"
            VALUE=\"Submit\">");
  printf("<INPUT TYPE=\"reset\" VALUE=\"Reset\">");
  printf("</TD></TR>\n");
  printf("</TABLE>\n");
  printf("</FORM>\n");
}


function update_help()
{
  global $lang;
  global $lookup;
  global $description;
  global $contents;
  global $newlookup;
  global $auth;
  global $log;

  $query = "UPDATE help SET" .
           "  lookup = " . make_null($newlookup) .
           ", description = " . make_null($description) .
           ", auth = " . make_null($auth) .
           ", contents = " . make_null($contents) .
           " WHERE lookup=" . make_null($lookup) .
           " AND language=" . $lang;

  mysql_query($query)
    or print_mysql_error2("Unable to update help prompt", $query);
  $log['admin']->LogPrint("Help prompt updated ($lookup in $lang)");
  preview_help();

}

?>