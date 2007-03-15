<?php

/**
**	assessments.php
**
** This admin script allows the creation of new
** assessments - specifically their structure.
** Initial coding : Colin Turner
**
*/

// The include files
include('common.php');
include('authenticate.php');
include('lookup.php');

// Connect to the database on the server
db_connect()
  or die("Unable to connect to server");

auth_user("root");

// The Page Header file
$page = new HTMLOPUS("Assessment Administration", "configuration");

print_menu("admin");  

// The default mode for the global variable
if(empty ($mode)) $mode = "AssessmentShowList";

// Getting into the right mode for the right job
switch($mode)
{

  case "AssessmentShowList":
    assessment_show_list();
    break;

  case "AssessmentInsert":
    assessment_insert();
    break;

  case "AssessmentEdit":
    assessment_edit();
    break;

  case "AssessmentDelete":
    assessment_delete();
    break;

  case "AssessmentStructureUpdate":
    assessmentstructure_update();
    break;

  case "AssessmentStructureInsert":
    assessmentstructure_insert();
    break;

  case "AssessmentStructureItemUp":
    assessmentstructure_moveup();
    break;    

  case "AssessmentStructureItemDown":
    assessmentstructure_movedown();
    break;    

  case "AssessmentStructureDelete":
    assessmentstructure_delete();
    break;

  default:
    die_gracefully("Invalid mode");
    break;

}

  
// Print the footer and finish the page
page_footer();


/**
**	@function assessmentstructure_sanitycheck
**
** Checks the variable ordering in an assessment for faults.
** If the reordering of an assessment structure goes wrong
** it could cause severe problems for further editing. This
** function checks for possible faults.
**
** @param $assessment_id is the unique id for the assessment
** @return TRUE is the assessment is sound, FALSE otherwise
*/
function assessmentstructure_sanitycheck($assessment_id)
{
  $query = "SELECT MIN(varorder), MAX(varorder), " .
           "COUNT(varorder), COUNT(DISTINCT varorder) " .
           "FROM assessmentstructure WHERE assessment_id=$assessment_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to obtain assessment structure.", $query);

  $row = mysql_fetch_row($result);

  // No variable order should be zero, that's for temporary use only!
  if($row[0] == '0') return(FALSE);

  // No two variable orders should be the same
  if($row[2] != $row[3]) return(FALSE);

  return(TRUE);
}


/**
**	@function assessment_show_list
**
** Shows the currently available assessment entries.
** This harvests data from the assessment table.
**
*/
function assessment_show_list()
{
  global $PHP_SELF; // A reference back to the script
  global $log;      // Access to the logging system
  global $conf;     // Access to the configuration

  printf("<H2 ALIGN=\"CENTER\">Assessments Available</H2>\n");

  $query = "SELECT * FROM assessment ORDER BY description";
  $result = mysql_query($query)
    or print_mysql_error("Unable to fetch assignments.");

  if(!mysql_num_rows($result))
  {
    printf("<P ALIGN=\"CENTER\">No assessments currently avilable.</P>\n");
  }
  else{
    echo "<TABLE ALIGN=\"CENTER\" BORDER=\"1\">\n" .
         "<TR><TH>Options</TH><TH>Description</TH><TH>Student Description</TH></TR>\n";
    while($row = mysql_fetch_array($result))
    {

      echo "<TR><TD>";
/*        show_button("edit", "$PHP_SELF?mode=AssessmentEdit&assessment_id=" . $row["assessment_id"]);
        show_button("delete", "$PHP_SELF?mode=AssessmentEdit&assessment_id=" . $row["assessment_id"]);
     */ echo "</TD>";
      echo "<TR><TD><A HREF=\"$PHP_SELF?mode=AssessmentEdit&assessment_id=" .
           $row["assessment_id"] . "\">[ Edit ]</A>" .
           " <A HREF=\"$PHP_SELF?mode=AssessmentDelete&assessment_id=" .
           $row["assessment_id"] . "\">[ Delete ]</A></TD>\n";
    
      echo "<TD>" . htmlspecialchars($row["description"]) . "</TD>\n";
      echo "<TD>" . htmlspecialchars($row["student_description"]) . "</TD></TR>\n";
    }
    echo "</TABLE>\n";
  }

  mysql_free_result($result);

 // Form to add a new item
  echo "<HR>\n" .
       "<P>To begin adding a new assessment use the following form.</P>";

  echo "<FORM ACTION=\"" .
       $PHP_SELF . "?mode=AssessmentInsert\" METHOD=\"POST\">\n";

  echo "<TABLE>\n" .
       "<TR><TH>Unique Description</TH><TD><INPUT NAME=\"description\" SIZE=\"40\" TYPE=\"TEXT\"></TD></TR>\n";
  echo "</TABLE>\n";
  echo "<P ALIGN=\"CENTER\"><INPUT TYPE=\"submit\" NAME=\"button\" VALUE=\"Add Assessment\"></P>" .
       "\n</FORM>\n";
  
  $log['admin']->LogPrint("assessment list viewed");
  output_help("AdminAssigmentAdd");
}


/**
**	@function assessment_insert
**
** Creates a new entry in the assessment table.
** This only adds a description and produces the automatically
** allocated value to use for further editing.
** Sets the global $assessment_id.
**
** @param $description (CGI) A description to use (unique)
** @see assessment_edit
*/
function assessment_insert()
{
  global $description;
  global $assessment_id;
  global $log;

  if(empty($description))
    die_gracefully("You must specify a description, please go back and try again.");

  $query = "SELECT assessment_id FROM assessment WHERE description=" . make_null($description);
  $result = mysql_query($query)
    or print_mysql_error2("Unable to check descriptions for assessments", $query);
  $rows = mysql_num_rows($result);
  mysql_free_result($result);

  if($rows) die_gracefully("You must specify a unique description, this one exists already.");

  $query = "INSERT INTO assessment (description) VALUES(" .
           make_null($description) . ")";
  mysql_query($query)
    or print_mysql_error2("Unable to insert new assessment.", $query);

  $assessment_id = mysql_insert_id();

  $log['admin']->LogPrint("new assessment created ($description)");
  assessment_edit();
}


/*
**	@function assessment_delete()
**
** Deletes an assessment from the system.
**
*/
function assessment_delete()
{
  global $assessment_id;
  global $confirm;
  global $PHP_SELF;
  global $conf;        // Configuration data
  global $log;         // Access to logging

  if(empty($assessment_id))
    die_gracefully("You cannot access this page without an assessment id.");

  $query = "SELECT * FROM assessment WHERE assessment_id=$assessment_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to obtain assessment data.", $query);
  $row = mysql_fetch_array($result);

  if($confirm!=1){
    echo "<H2 ALIGN=\"CENTER\">Are you sure?</H2>\n";
    echo "<P ALIGN=\"CENTER\">You have selected to delete an assessment (" .
         htmlspecialchars($row["description"]) . ") from the system." .
         "<BR><B>This should normally never be done</B>";
         " Are you absolutely sure?</P>";

    echo "<P ALIGN=\"CENTER\"><A HREF=\"$PHP_SELF?mode=AssessmentDelete&" .
         "assessment_id=$assessment_id&confirm=1\">" .
         "Click here to delete prompt</A></P>";
    echo "<P><A HREF=\"$PHP_SELF\">Click to return to assessment list.</A></P>";
    page_footer();
    exit(0);
  }

  $query = "DELETE FROM assessment WHERE assessment_id=$assessment_id";
  mysql_query($query)
    or print_mysql_error("Unable to delete assessment.", $query);

  $log['admin']->LogPrint("assessment deleted :" . $row["description"]);

  assessment_show_list();
}  


/**
**	@function assessmentstructure_delete
**
** Deletes a single structural item from an assessment.
**
** @param $assessment_id (CGI) The assessment to delete from
** @param $varorder (CGI) The specific variable to delete
** @param $confirm (CGI) Must be set to 1 to finalise delete
*/
function assessmentstructure_delete()
{
  global $PHP_SELF;
  global $assessment_id;
  global $varorder;
  global $confirm;
  global $log;

  $desc = get_assessment_name($assessment_id);
  $query = "SELECT name, human FROM assessmentstructure WHERE " .
           "assessment_id=$assessment_id AND varorder=$varorder";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch structure data", $query);
  $row = mysql_fetch_array($result);
  mysql_free_result($result);

  if($confirm!=1)
  {
    echo "<H3 ALIGN=\"CENTER\">Are you sure?</H3>";
    echo "<P ALIGN=\"CENTER\">You have elected to delete the structure item \"" .
         htmlspecialchars($row['human']) . "\" (" .
         htmlspecialchars($row['name']) . ") from the assessment \"" .
         htmlspecialchars($desc) . "\"</P>\n<P ALIGN=\"CENTER\">";
    echo "<A HREF=\"$PHP_SELF?mode=AssessmentStructureDelete" .
         "&assessment_id=$assessment_id&varorder=$varorder&confirm=1\">" .
         "delete the item</A></P>";
    echo "<P><A HREF=\"$PHP_SELF?mode=AssessmentEdit" .
         "&assessment_id=$assessment_id\">return to structure view</A></P>";
  }
  else
  {
    $query = "DELETE FROM assessmentstructure WHERE " .
             "assessment_id=$assessment_id AND varorder=$varorder";
    mysql_query($query)
      or print_mysql_error2("Unable to delete item.", $query);
    
    $log['admin']->LogPrint("deleted item " . $row['name'] . " from assessment " . $desc);
    assessment_edit();
  }
}

/*
**	@function assessment_edit
**
** Provides a form suitable for updating assessment information.
*/
function assessment_edit()
{
  global $PHP_SELF;
  global $assessment_id;
  global $log;
  global $conf;

  if(empty($assessment_id)) die_gracefully("Missing assessment_id.");

  if(!assessmentstructure_sanitycheck($assessment_id))
    die_gracefully("The structure of this assessment is corrupt and it is not safe to edit" .
                   ", please inform the maintainer of the system immediately.");

  $query = "SELECT * FROM assessment WHERE assessment_id=$assessment_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to obtain assignment information.", $query);
  $assessment_basic = mysql_fetch_array($result);
  mysql_free_result($result);

  $query = "SELECT * FROM assessmentstructure WHERE assessment_id=$assessment_id" .
           " ORDER BY varorder";
  $result = mysql_query($query)
    or print_mysql_error("Unable to fetch assignment structure.", $query);

  echo "<H3 ALIGN=\"CENTER\">Editing assessment " . 
       $assessment_basic['description'] . "</H3>\n";


  echo "<HR>\n";
  echo "<H3 ALIGN=\"CENTER\">Assessment Structure</H3>";
  
  $var_count = mysql_num_rows($result);
  $var_current = 0;
  if(!$var_count) echo "<P>No structure exists at this time.</P>\n";
  else
  {
    while($row = mysql_fetch_array($result))
    {
      $var_current++;
      echo "<P><A NAME=\"var$var_current\">$var_current.</A></P>\n";
      echo "<FORM METHOD=\"POST\" ACTION=\"$PHP_SELF?mode=AssessmentStructureUpdate" .
           "&assessment_id=$assessment_id&varorder=" . $row["varorder"] . 
           "#var$var_current\">";
      assessmentstructure_itemform($row);
      // Update and movement
      echo "<TR><TD COLSPAN=\"2\"> " .
           "<INPUT TYPE=\"SUBMIT\" VALUE=\"Update\"> ";
      echo "<A HREF=\"$PHP_SELF?mode=AssessmentStructureDelete" .
           "&assessment_id=$assessment_id&varorder=" . $row["varorder"] . "\">[ Delete ]</A>\n";
      if($var_current != 1)
      {
        echo " <A HREF=\"$PHP_SELF?mode=AssessmentStructureItemUp&assessment_id=$assessment_id" .
             "&varorder=" . $row["varorder"] . "#var" . ($var_current-1) . "\">[ Move Up ]</A>\n";
      }
      if($var_current != $var_count)
      {
        echo " <A HREF=\"$PHP_SELF?mode=AssessmentStructureItemDown&assessment_id=$assessment_id" .
             "&varorder=" . $row["varorder"] . "#var" . ($var_current+1) . "\">[ Move Down ]</A>\n";
      }
      echo "</TD></TR>\n";
      echo "</TABLE>";
      echo "</FORM>\n\n";
    }
  }
 
  echo "<HR><H3 ALIGN=\"CENTER\">Add a new assessment structure item</H3>\n";
  echo "<FORM METHOD=\"POST\" ACTION=\"$PHP_SELF?mode=AssessmentStructureInsert" .
       "&assessment_id=$assessment_id\">";
  assessmentstructure_itemform(NULL);
  echo "<TR><TD COLSPAN=\"2\"> " .
       "<INPUT TYPE=\"SUBMIT\" VALUE=\"Add\"> ";
  echo "</TD></TR>\n";
  echo "</TABLE>";
  echo "</FORM><BR>\n\n";

  $log['admin']->LogPrint("assessment " . $assessment_basic['description'] . "viewed for editing");  
  output_help("AdminAssessmentStructureEdit");
}


/**
**	@function assessmentstructure_itemform
**
** Displays the form of an assessment structure item.
** This is used for both editing existing items and
** creating new ones.
**
** @param $row is the row from the database for editing
**        an item, but should be NULL for a new item
*/
function assessmentstructure_itemform($row)
{
  echo "<TABLE WIDTH=\"500\" ALIGN=\"CENTER\" BORDER=\"0\">\n";

  // Variable Name
  echo "<TR><TH>Name</TH><TD><INPUT NAME=\"name\" SIZE=\"20\" VALUE=\"" .
       $row['name'] . "\"></TD></TR>\n";
  // Variable Type
  echo "<TR><TH>Type</TH><TD><SELECT NAME=\"type\">\n" .
       "<OPTION" . ($row['type']=='textual' ? " SELECTED" : "") . ">textual</OPTION>\n" .
       "<OPTION" . ($row['type']=='numeric' ? " SELECTED" : "") . ">numeric</OPTION>\n" .
       "<OPTION" . ($row['type']=='date' ? " SELECTED" : "") . ">date</OPTION>\n" .
       "<OPTION>checkbox</OPTION>\n" .
       "<OPTION>assesseddate</OPTION>\n</SELECT></TD></TR>";
  // Variable Min / Max
  echo "<TR><TH>Min/Max</TH><TD>Minimum value / length " .
       "<INPUT NAME=\"min\" SIZE=\"3\" VALUE=\"" .
       $row['min'] . "\"> Maximum value / length " .
       "<INPUT NAME=\"max\" SIZE=\"3\" VALUE=\"" .
       $row['max'] . "\"></TD></TR>\n";
  // Options
  echo "<TR><TH>Options</TH><TD>Compulsory <INPUT TYPE=\"CHECKBOX\" NAME=\"compulsory\" " .
       (strstr($row['options'], "compulsory") ? " CHECKED" : "") . "></TD></TR>\n";
  echo "<TR><TH>Weighting</TH><TD><INPUT TYPE=\"TEXT\" SIZE=\"5\" NAME=\"weighting\" " .
       "VALUE=\"" . $row['weighting'] . "\"></TD></TR>\n";
  echo "<TR><TH>Description</TH><TD><TEXTAREA NAME=\"human\" ROWS=\"3\" COLS=\"50\">" .
       $row['human'] . "</TEXTAREA></TD></TR>\n";
}


/**
**	@function assessmentstructure_insert
**
** Adds a new assessment structure item.
** Table locking is required to preserve the uniqueness of
** varorder for specific assessment_id values.
**
** @param $assessment_id (CGI) the assessment to add this to
** @see assessmentstructure_update for more variables.
*/
function assessmentstructure_insert()
{
  global $assessment_id;
  global $name;
  global $min, $max, $weighting, $type;
  global $compulsory, $human;
  global $log;

  if(empty($assessment_id)) die_gracefully("You cannot access this page without an assessment id.");
  if(empty($name)) die_gracefully("You must specify a name.");
  if(empty($min)) $min = "NULL";
  if(empty($max)) $max = "NULL";
  if(empty($weighting)) $weighting = 0;

  // The varorder cannot get corrupted while we do this...
  $query = "LOCK TABLES assessmentstructure WRITE";
  mysql_query($query)
    or print_mysql_error2("Unable to lock assessmentstructure table.", $query);

  $query = "SELECT MAX(varorder) FROM assessmentstructure " .
           "WHERE assessment_id=$assessment_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch maximum variable order.", $query);
  $row = mysql_fetch_row($result);
  $varorder = $row[0] + 1;
  mysql_free_result($result);

  // Options only has one field for now...
  $options = "";
  if($compulsory) $options="compulsory";

  $query = "INSERT INTO assessmentstructure " .
           "(name, min, max, weighting, type, options, human, varorder, assessment_id) " .
           "VALUES(" . make_null($name) . ", ".
           "$min, $max, $weighting, " . make_null($type) .
           ", " . make_null($options) . ", " . make_null($human) .
           ", $varorder, $assessment_id)";
  mysql_query($query)
    or print_mysql_error2("Unable to insert assessment structure item.", $query);
  echo "<P>Changes were accepted</P>.";

  $query = "UNLOCK TABLES";
  mysql_query($query)
    or print_mysql_error2("Unable to unlock table.", $query);

  $log['admin']->LogPrint("new item $name added to assessment structure for assessment $assessment_id");
  assessment_edit();
}


/**
**	@function assessmentstructure_update
**
** Updates an individual structure item for an assessment.
**
** @param $assessment_id (CGI) The assessment to update
** @param $varorder (CGI) The specific variable to update
** @param $name (CGI) The variable name
** @param $min (CGI) The minimum value
** @param $max (CGI) The maximum value
** @param $weighting (CGI) The weighting for the item
** @param $type (CGI) One of textual, numeric, etc.
** @param $compulsory (CGI) Used to form options
** @param $human (CGI) Human readable description
*/
function assessmentstructure_update()
{
  global $assessment_id;
  global $varorder;
  global $name;
  global $min, $max, $weighting, $type;
  global $compulsory, $human;

  if(empty($name)) die_gracefully("You must specify a name.");
  if(empty($min)) $min = "NULL";
  if(empty($max)) $max = "NULL";
  if(empty($weighting)) $weighting = 0;


  // Options only has one field for now...
  $options = "";
  if($compulsory) $options="compulsory";

  $query = "UPDATE assessmentstructure SET " .
           "name=" . make_null($name) . ", ".
           "min=$min, max=$max, weighting=$weighting, type=" . make_null($type) .
           ", human=" . make_null($human) .
           ", options=" . make_null($options) .
           " WHERE assessment_id=$assessment_id AND varorder=$varorder";
  mysql_query($query)
    or print_mysql_error2("Unable to update assessment structure item.", $query);
  echo "<P>Changes were accepted</P>.";
  assessment_edit();
}


/**	@function assessmentstructure_moveup
**
** Moves a variable upwards in the priority list for an assessment.
** This function provides full table locking for safety.
** 
** @param $assessment_id (CGI) The assessment to modify
** @param $varorder (CGI) The "row" to move upwards, an internal variable
*/
function assessmentstructure_moveup()
{
  global $assessment_id;
  global $varorder;

  // We have to lock tables before we do this kind of thing...
  $query = "LOCK TABLES assessmentstructure WRITE";
  mysql_query($query)
    or print_mysql_error2("Unable to lock table.", $query);

  $query = "SELECT varorder FROM assessmentstructure WHERE " .
           "assessment_id=$assessment_id ORDER BY varorder";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch variable orders.", $query);

  // Look for our chosen entry, and the one above...
  $previous = 0;
  $found = FALSE;
  while(!$found && ($row = mysql_fetch_array($result)))
  {
    //echo "<P>Comparing : " . $row[0] . ", $varorder</P>";
    if($row[0] == $varorder) $found = TRUE;
    else $previous = $row[0];
  }

  if($found && $previous)
  {
    // safe to proceed
    assessmentstructure_swaprows($assessment_id, $varorder, $previous);
  }
  else
  {
    echo "<P>Warning, unable to move row</P>"; 
  }
  // Must unlock the table too!
  $query = "UNLOCK TABLES";
  mysql_query($query)
    or print_mysql_error2("Unable to unlock table.", $query);

  mysql_free_result($result);
  assessment_edit();
}


/**	@function assessmentstructure_movedown
**
** Moves a variable downwards in the priority list for an assessment.
** This function provides full table locking for safety.
** 
** @param $assessment_id (CGI) The assessment to modify
** @param $varorder (CGI) The "row" to move downwards, an internal variable
*/
function assessmentstructure_movedown()
{
  global $assessment_id;
  global $varorder;

  // We have to lock tables before we do this kind of thing...
  $query = "LOCK TABLES assessmentstructure WRITE";
  mysql_query($query)
    or print_mysql_error2("Unable to lock table.", $query);

  $query = "SELECT varorder FROM assessmentstructure WHERE " .
           "assessment_id=$assessment_id ORDER BY varorder";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch variable orders.", $query);

  // Look for our chosen entry, and the one above...
  $next = 0;
  $found = FALSE;
  while(!$found && ($row = mysql_fetch_array($result)))
  {
    if($row[0] == $varorder){
      $found = TRUE;
      $row = mysql_fetch_array($result);
      $next = $row[0];
    }
 
  }
  if($found && $next)
  {
    // safe to proceed
    assessmentstructure_swaprows($assessment_id, $varorder, $next);
  }
  else
  {
    echo "<P>Warning, unable to move row</P>"; 
  }
  // Must unlock the table too!
  $query = "UNLOCK TABLES";
  mysql_query($query)
    or print_mysql_error2("Unable to unlock table.", $query);

  mysql_free_result($result);
  assessment_edit();
}


/**
**	@function assessmentstructure_swaprows
**
** Swaps the variable orders for rows in assessmentstructure.
** This function should ONLY be called after appropriate locks have
** been placed on the assessmentstructure to group these statements,
** and any statements that determined the row numbers together.
** This assumes that no row has a varorder of 0, this is used as a
** transitional variable only.
**
** @param $assessment_id The unique assessment id for the assessment
** @param $varorder1 The first variable to be swapped
** @param $varorder2 The second variable to be swapped
*/
function assessmentstructure_swaprows($assessment_id, $varorder1, $varorder2)
{
  $query = "UPDATE assessmentstructure SET varorder=0 " .
           "WHERE varorder=$varorder1 AND assessment_id=$assessment_id";
  mysql_query($query)
    or print_mysql_error2("Unable to update assessment structure position.", $query);

  $query = "UPDATE assessmentstructure SET varorder=$varorder1 " .
           "WHERE varorder=$varorder2 AND assessment_id=$assessment_id";
  mysql_query($query)
    or print_mysql_error2("Unable to update assessment structure position.", $query);

  $query = "UPDATE assessmentstructure SET varorder=$varorder2 " .
           "WHERE varorder=0 AND assessment_id=$assessment_id";
  mysql_query($query)
    or print_mysql_error2("Unable to update assessment structure position.", $query);
}
 

?>