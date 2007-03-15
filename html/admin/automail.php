<?php

/**
**	automail.php
**
** This admin script allows help prompts for the 
** system to be edited.
**
** Initial coding : Colin Turner
**
*/

// The include files
include('common.php');
include('lookup.php');
include('authenticate.php');

// Connect to the database on the server
db_connect()
  or die("Unable to connect to server");

auth_user("admin");

// The Page Header file
$page = new HTMLOPUS("Auto Mail Administration", "configuration");

// The default mode for the global variable
if(empty ($mode)) $mode = ShowList;

// Getting into the right mode for the right job
switch($mode)
{

  case ShowList:
    list_mail();
    break;

  case EditMail:
    edit_mail();
    break;

  case UpdateMail:
    update_mail();
    break;

  case DeleteMail:
    delete_mail();
    break;

  case AddMail:
    insert_mail();
    break;
}

  
// Print the footer and finish the page
page_footer();


/**
**	list_mail
**
** Shows the currently available mail entries.
**
*/
function list_mail()
{
  global $PHP_SELF; // A reference back to the script
  global $log;      // Access to the logging system

  printf("<H2 ALIGN=\"CENTER\">Mail Templates Available</H2>\n");

  if(!check_default_policy("automail", "list"))
    die_gracefully("You are not permitted to list templates");

  $query = "SELECT * FROM automail ORDER BY language, lookup";
  $result = mysql_query($query)
    or print_mysql_error("Unable to fetch mail templates.");

  if(!mysql_num_rows($result))
  {
    printf("<P ALIGN=\"CENTER\">No templates currently available.</P>\n");
  }
  else{
    printf("<TABLE ALIGN=\"CENTER\" BORDER=\"1\">\n");
    printf("<TR><th>Options</th><th>Language</th><th>Key</th><th>Description</th>\n");
    while($row = mysql_fetch_array($result))
    {
      printf("<TR>");
      printf("<TD><A HREF=\"%s?mode=%s&lang=%s&lookup=%s\">Edit</A> ",
             $PHP_SELF, EditMail, $row['language'], $row['lookup']);
      printf("<A HREF=\"%s?mode=%s&lang=%s&lookup=%s\">Delete</A></TD>",
             $PHP_SELF, DeleteMail, $row['language'], $row['lookup']);
      
      printf("<TD>%s</TD>", get_language_name($row['language']));
      printf("<TD>%s</TD>", $row['lookup']);
      printf("<TD>%s</TD>", $row['description']);
      printf("</TR>\n");
    }
    printf("</TABLE>\n");
  }

  mysql_free_result($result);
  // Form to add a new item
  echo "<HR>\n" .
       "<P>To add a new template use the following form.</P>" .
       "<FORM METHOD=\"POST\" ACTION=\"$PHP_SELF?mode=AddMail\">\n" .
       "<P>Language \n<SELECT NAME=\"lang\">\n";

  // List of languages
  $query = "SELECT * FROM languages";
  $result = mysql_query($query)
    or print_mysql_error("Unable to query language table.", $query);
  while($row = mysql_fetch_array($result)){
    echo "<OPTION VALUE=\"" . $row['language_id'] . "\">" .
         htmlspecialchars($row['language']) . "</OPTION>\n";
  }
  echo "</SELECT>\n";
  echo " Lookup <INPUT TYPE=\"TEXT\" NAME=\"lookup\" SIZE=\"30\">\n" .
       "<INPUT TYPE=\"submit\" NAME=\"button\" VALUE=\"Add Template\"></P>" .
       "\n</FORM>\n";
}


/*
**	insert_mail
**
** Adds a new mail template to the system. This might not exist in any
** production version.
*/
function insert_mail()
{
  global $lang;
  global $lookup;
  global $PHP_SELF;

  if(!check_default_policy("automail", "create"))
    die_gracefully("You are not permitted to list mail templates");

  // Check for validity...
  $query = "SELECT * FROM automail WHERE language=$lang AND lookup=" . make_null($lookup);
  $result = mysql_query($query)
    or print_mysql_error("Failure checking uniqueness", $query);
  if(mysql_num_rows($result))
    die_gracefully("This lookup already exists in this language.");
  mysql_free_result($result);

  // Ok, now create an entry
  $query = "INSERT INTO automail VALUES(" .
           $lang . ", " . make_null($lookup) .
           ", NULL, NULL, NULL, NULL, NULL, NULL, NULL)";

  mysql_query($query)
    or print_mysql_error2("Unable to add mail template.", $query);

  edit_mail();
}


/*
**	delete_mail
**
** Deletes a mail template from the system. This might not exist is any
** production version.
*/
function delete_mail()
{
  global $lang;
  global $lookup;
  global $confirm;
  global $PHP_SELF;

  if(!check_default_policy("automail", "delete"))
    die_gracefully("You are not permitted to list help prompts");

  if($confirm!=1){
    echo "<H2 ALIGN=\"CENTER\">Are you sure?</H2>\n";
    echo "<P ALIGN=\"CENTER\">You have selected to delete a mail template for " .
         htmlspecialchars($lookup) . " for language " .
         get_language_name($lang) . " from the system. Normally this " .
         "should never be done. Are you absolutely sure?</P>";

    echo "<P ALIGN=\"CENTER\"><A HREF=\"$PHP_SELF?mode=DeleteMail&" .
         "lang=$lang&lookup=$lookup&confirm=1\">Click here to delete template</A></P>";
    page_footer();
    exit(0);
  }

  $query = "DELETE FROM help WHERE language=$lang AND lookup=" . make_null($lookup);
  mysql_query($query)
    or print_mysql_error("Unable to delete mail template.", $query);

  list_mail();
}  

/*
**	edit_mail
**
** Provides a form suitable for updating mail information.
*/
function edit_mail()
{
  global $lookup;
  global $lang;
  global $log;

  display_mail_form($lang, $lookup);

  $log['admin']->LogPrint("Help prompt information viewed for editing ($lookup in $lang).");
}


/*
**	display_mail_form
*/
function display_mail_form($language, $lookup)
{
  global $PHP_SELF;

  if(empty($language))
    die_gracefully("This page should not be accessed without a language id.");
 
  if(!empty($lookup))
  {
    $query = sprintf("SELECT * FROM automail WHERE language=%s AND lookup=%s",
                     $language, make_null($lookup));
    $result = mysql_query($query)
      or print_mysql_error("Unable to fetch specific mail template");
    $values = mysql_fetch_array($result);

    printf("<H2 ALIGN=\"CENTER\">Edit Mail Template<BR>%s</H2>\n",
      htmlspecialchars($values['lookup']));
  }
  else{
    printf("<H2 ALIGN=\"CENTER\">Add Mail Template</H2>\n");
  }
  
  printf("<P ALIGN=\"CENTER\">Language is %s</P>\n",
    htmlspecialchars(get_language_name($language)));
 
  if(!empty($lookup)){
    printf("<FORM METHOD=\"post\" ACTION=\"%s?mode=%s&lang=%s&lookup=%s\">\n",
            $PHP_SELF, UpdateMail, $language, $lookup);
  }
  else{
    printf("<FORM METHOD=\"post\" ACTION=\"%s?mode=%s&lang=%s\">\n",
            $PHP_SELF, AddMail, $language);
  }
    
  printf("<TABLE ALIGN=\"CENTER\">\n");

  //if(!empty($lookup))
  
  printf("<TR><th>Lookup</th>\n<TD>");
  printf("<INPUT TYPE=\"TEXT\" SIZE=\"50\" NAME=\"newlookup\" VALUE=\"%s\"></TD></TR>\n", $values['lookup']);
  printf("<TR>\n<th>Description</th>\n<TD>\n");
  printf("<INPUT TYPE\"TEXT\" SIZE=\"50\" NAME=\"description\" VALUE=\"%s\">\n</TD>\n</TR>", $values['description']);
  printf("<TR>\n<th>From</th>\n<TD>\n");
  printf("<INPUT TYPE\"TEXT\" SIZE=\"50\" NAME=\"fromh\" VALUE=\"%s\">\n</TD>\n</TR>", $values['fromh']);
  printf("<TR>\n<th>To</th>\n<TD>\n");
  printf("<INPUT TYPE\"TEXT\" SIZE=\"50\" NAME=\"toh\" VALUE=\"%s\">\n</TD>\n</TR>", $values['toh']);
  printf("<TR>\n<th>CC</th>\n<TD>\n");
  printf("<INPUT TYPE\"TEXT\" SIZE=\"50\" NAME=\"cch\" VALUE=\"%s\">\n</TD>\n</TR>", $values['cch']);
  printf("<TR>\n<th>BCC</th>\n<TD>\n");
  printf("<INPUT TYPE\"TEXT\" SIZE=\"50\" NAME=\"bcch\" VALUE=\"%s\">\n</TD>\n</TR>", $values['bcch']);
  printf("<TR>\n<th>Subject</th>\n<TD>\n");
  printf("<INPUT TYPE\"TEXT\" SIZE=\"50\" NAME=\"subject\" VALUE=\"%s\">\n</TD>\n</TR>", $values['subject']);

  printf("<TR><th ALIGN=\"CENTER\">Contents</th>\n");
  printf("<TD ALIGN=\"CENTER\" ><TEXTAREA ROWS=\"30\" COLS=\"50\"  NAME=\"contents\">");

  printf("%s", htmlspecialchars($values['contents']));
  printf("</TEXTAREA></TD></TR>\n");

  printf("<TR><TD ALIGN=\"CENTER\" COLSPAN=\"2\"><INPUT TYPE=\"submit\" NAME=\"button\"
            VALUE=\"Submit\">");
  printf("<INPUT TYPE=\"reset\" VALUE=\"Reset\">");
  printf("</TD></TR>\n");
  printf("</TABLE>\n");
  printf("</FORM>\n");
}


function update_mail()
{
  global $lang;
  global $lookup;
  global $description;
  global $contents;
  global $newlookup;
  global $log;
  global $fromh, $toh, $cch, $bcch, $subject;


  if(!check_default_policy("automail", "edit"))
    die_gracefully("You are not permitted to edit mail templates");

  $query = "UPDATE automail SET" .
           "  lookup = " . make_null($newlookup) .
           ", description = " . make_null($description) .
           ", contents = " . make_null($contents) .
           ", fromh = " . make_null($fromh) .
           ", toh = " . make_null($toh) .
           ", cch = " . make_null($cch) .
           ", bcch = " . make_null($bcch) .
           ", subject = " . make_null($subject) .
           " WHERE lookup=" . make_null($lookup) .
           " AND language=" . $lang;

  mysql_query($query)
    or print_mysql_error2("Unable to update mail template", $query);
  $log['admin']->LogPrint("Help prompt updated ($lookup in $lang)");
  list_mail();

}

?>