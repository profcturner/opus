<?php

/**
**	notes.php
**
** This file provides support for adding notes to various
** resources.
**
*/  


/**
**      note_authenticate
**
** This function checks if we are authenticated for the note.
**
** Admin users are always authenticated. For other users one can
** view the note if
**
** 1) The note specifically links you (or your company);
** 2) The authorisation field mentions your category of user as
**    permitted to view the note.
**
*/
function note_authenticate($note_id)
{
  global $user;

  // Admin's are always allowed...
  if(is_admin()) return(TRUE);

  // Unless you are linked on the note, you do not have permission
  $linked = FALSE;
  $query = "SELECT * FROM notelink WHERE note_id=$note_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to obtain note link information.", $query);
  while($row = mysql_fetch_array($result))
  {
    if(is_staff())
    {
      if($row['link_type']=='Staff' && $row['link_id']==get_id()) $linked = TRUE;
    }
    if(is_student())
    {
      if($row['link_type']=='Student' && $row['link_id']==get_id()) $linked = TRUE;
    }
    if(is_company())
    {
      // A complicated one, this is a company contact, check they act for this company
      if($row['link_type']=='Company')
      {
        $sub_query = "SELECT * FROM companycontact WHERE company_id=" . $row['link_id'] .
                     " AND contact_id=" . get_contact_id($_SESSION['user']['id']);
        $sub_result = mysql_query($sub_query)
          or print_mysql_error2("Unable to verify contact information", $sub_query);
        if(mysql_num_rows($sub_result)) $linked = TRUE;
        mysql_free_result($sub_result);
      }
    }
    // More to add ... companies and stuff...
  }
  mysql_free_result($result);
  if(!$linked) return(FALSE);

  // Ok so far, but the authorisation string must now be examined.
  $query = "SELECT auth FROM notes WHERE note_id=$note_id";
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
      if($auth_part == $_SESSION['user']['type']) $allowed = TRUE;
      if(($_SESSION['user']['type'] == 'company') && ($auth_part == 'contact')) $allowed = TRUE;
      if($auth_part == 'all') $allowed = TRUE;
    }
  }
  // Check for the effects of exclusions now
  if($allowed == TRUE){
    foreach($auth_parts as $auth_part)
      if($auth_part == ("!" . $_SESSION['user']['type'])) $allowed = FALSE;
  }

  return($allowed);
}

/**
**	notes_display_list
**
** Searches for all notes for the current item.
** Notes must pass logic in startsearch_notes to be
** visible.
**
** @param $link_type The type of the link, Staff, Student etc...
** @param $link_id   The unique identifier for the link
** @param $extra     Information to be carried in URL to preserve state
*/
function notes_display_list($link_type, $link_id, $extra)
{
  $query = "SELECT notes.* FROM notes, notelink WHERE " .
           " notes.note_id = notelink.note_id" .
           " AND notelink.link_type = " . make_null($link_type) .
           " AND notelink.link_id = " . make_null($link_id) .
           " ORDER BY date DESC";

  notes_startsearch($query, $link_type, $link_id, $extra);
}


/**
**	notes_startsearch
*/
function notes_startsearch($query, $link_type, $link_id, $extra)
{
  global $PHP_SELF;

  echo "<FORM METHOD=\"POST\" ACTION=\"" . $PHP_SELF . "?$extra&mode=Notes_Search\">\n";
  echo "<TABLE ALIGN=\"CENTER\">\n";
  echo "<TR><TD ALIGN=\"CENTER\" COLSPAN=2><B>Search criteria</B></TD></TR>\n";
  echo "<TR><TD>Note fragment</TD>\n";
  echo "<TD><INPUT TYPE=\"TEXT\" NAME=\"search\" SIZE=\"20\"></TD></TR>\n";
  echo "<TR><TD ALIGN=\"CENTER\" COLSPAN=2>";
  echo "<INPUT TYPE=\"submit\" NAME=\"searchbutton\" VALUE=\"Search\">\n";
  echo "</TD></TR>\n";
  echo "</TABLE>\n";
  echo "</FORM>\n";
 
  notes_process_query($query, $link_type, $link_id, $extra);
}


function notes_links_company($company_id)
{
  echo "<TABLE ALIGN=\"CENTER\" BORDER=\"0\">\n";

  // Contacts
  $query = "SELECT user_id FROM companycontact, contacts " .
           "WHERE contacts.contact_id = companycontact.contact_id AND company_id=$company_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to list contacts.", $query);

  echo "<TR><TD><H3>Contacts</H3></TD></TR>\n";
  while($row=mysql_fetch_row($result))
  {
    echo "<TR><TD>";
    notes_allow_link("Contact", $row[0], get_user_name($row[0]));
    echo "</TD></TR>\n";
  }
  mysql_free_result($result);

  // Students placed with the company for the current academic year...

  // Students applying for company for the current academic year...
  if(empty($year)) $year = get_academic_year() + 1;


  $query = "SELECT companystudent.student_id FROM cv_pdetails, companystudent " .
           "LEFT JOIN students ON students.user_id=cv_pdetails.id " .
           "WHERE cv_pdetails.id = companystudent.student_id AND " .
           "students.year=$year AND " .
           "companystudent.company_id =" . $company_id . " ORDER BY cv_pdetails.surname";

  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch current student list.", $query);

  if(mysql_num_rows($result)){
    echo "<TR><TD><H3>Students applying for placement in ($year - " . ($year + 1) . ")</H3></TD></TR>\n";
    while($row=mysql_fetch_row($result))
    {
      echo "<TR><TD>";
      notes_allow_link("Student", $row[0], get_user_name($row[0]));
      echo "</TD></TR>\n";
    }
  }


  

  echo "</TABLE>\n";
}



/**
**
** Produces checkboxes for appropriate cross links for student notes.
*/
function notes_links_student($student_id)
{
  echo "<TABLE ALIGN=\"CENTER\" BORDER=\"0\">\n";
  
  // Academic tutor
  $query = "SELECT staff_id FROM staffstudent " .
           "WHERE student_id=$student_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch academic tutor.", $query);
  if(mysql_num_rows($result))
  {
    echo "<TR><TD><H3>Academic Tutor</H3></TD></TR>\n";
    $row = mysql_fetch_row($result);
    echo "<TR><TD>";
    notes_allow_link("Staff", $row[0], get_user_name($row[0]));
    echo "</TD></TR>\n";
  }
  mysql_free_result($result);

  // Companies placed
  $query = "SELECT company_id FROM placement " .
           "WHERE student_id=$student_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch placement company.", $query);
  if(mysql_num_rows($result))
  {
    echo "<TR><TD><H3>Placement Companies</H3></TD></TR>\n";
    while($row = mysql_fetch_row($result))
    {
      echo "<TR><TD>";
      notes_allow_link("Company", $row[0], get_company_name($row[0]));
      echo "</TD></TR>\n";
    }
  }
  mysql_free_result($result);

  // Companies applied for
  $query = "SELECT company_id FROM companystudent " .
           "WHERE student_id=$student_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch placement company.", $query);
  if(mysql_num_rows($result))
  {
    echo "<TR><TD><H3>Companies Applied for</H3></TD></TR>\n";
    while($row = mysql_fetch_row($result))
    {
      echo "<TR><TD>";
      notes_allow_link("Company", $row[0], get_company_name($row[0]));
      echo "</TD></TR>\n";
    }
  }
  mysql_free_result($result);
  echo "</TABLE>\n";
}


function notes_allow_link($type, $link, $text)
{
  echo "<INPUT TYPE=\"CHECKBOX\" NAME=\"nl" . $type . $link .
       "\"> " . htmlspecialchars($text) . "\n";
}





/********************************************************************
**    
**   note_process_query($query, $link_type, $link_id, $extra)
**
*********************************************************************/

function notes_process_query($query, $link_type, $link_id, $extra)
{
  global $PHP_SELF;
  global $odd;
  $odd = TRUE;

  //With the Query sent to the server, $query goes in and $results comes out...

  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch note list.", $query);

   if(mysql_num_rows($result) == 0)
   {
     echo "<P>No entries meet the search criteria or there are currently no notes.</P>\n";

     notes_form($link_type, $link_id, $extra);
   }
   else
   {
     // Once there is any record of notes, we need to
     // get each row in turn, and fetch into an associative array...
     echo "<TABLE ALIGN=\"CENTER\" BORDER=\"0\">\n";
     echo "<TR><TH>Author</TH><TH>Summary</TH><TH>Date</TH></TR>\n";

     while($row = mysql_fetch_array($result))
     {
       if(!note_authenticate($row['note_id'])) continue;
       // This is a new row...
       if(!$odd){
       echo "<TR  BGCOLOR = \"#C4C4FF\">";
       }
       else{
       echo "<TR>";
       }
       echo "<TD>" . htmlspecialchars(get_user_name($row["author_id"])) . "</TD>\n";
       // Now display the data found in, $row["summary"], and $row["date"]
        echo "<TD><A HREF=\"" . $PHP_SELF .
                  "?$extra&mode=Display_Single_Note&note_id=" .
                   $row['note_id'] . "\">" .
                   htmlspecialchars($row["summary"]) .
                   "</A></TD>";
       echo "<TD>" . $row["date"] . "</TD>";
       echo "</TR>\n";
       if($odd) $odd = FALSE;
       else $odd = TRUE;
     }
     echo "</TABLE>\n";

   // echo "<P><A HREF=\"" . $PHP_SELF . "?$extra&mode=NoteForm"\">" .
    echo "<P ALIGN=\"CENTER\"><A HREF=\"$PHP_SELF?$extra&mode=NoteForm\">" .
         "Add a new note</A></P>\n";

    // note_form($link_type, $link_id, $extra);

   }
}

/**********************************************************************/





/****************************************************************
**      note_form()
**
** Displays a form so that the user can create  notes.
****************************************************************/

function notes_form($link_type, $link_id, $extra)
{
  global $user;
  global $PHP_SELF;
  echo "<H3 ALIGN=\"CENTER\">Add a new note</H3>\n";
  
  echo "<FORM METHOD=\"POST\" ACTION=\"" . $PHP_SELF . "?$extra&mode=Insert_Note\">\n";
  echo "<INPUT TYPE=\"HIDDEN\" NAME=\"link_type\" VALUE=\"$link_type\">";
  echo "<INPUT TYPE=\"HIDDEN\" NAME=\"link_id\" VALUE=\"$link_id\">";
  echo "<TABLE ALIGN=\"CENTER\">\n";
  echo "<TR><TD>Summary of Note</TD><TD><INPUT TYPE=\"TEXT\" SIZE=\"45\" NAME=\"summary\"></TD></TR>\n";
  echo "<TR><TD>Note Contents</TD><TD><TEXTAREA ROWS=\"7\" COLS=\"38\" NAME=\"comments\" WRAP=\"PHYSICAL\">" . htmlspecialchars($row["comments"]) . "</TEXTAREA></TD></TR>\n";
  echo "<TR><TD>Authorisation</TD><TD><INPUT TYPE=\"TEXT\" SIZE=\"45\" ";
  if(!is_admin()) echo "VALUE=\"" . $_SESSION['user']['type'] . "\" ";
  echo "NAME=\"auth\"></TD></TR>\n";
  echo "<TR><TD ALIGN=\"CENTER\" COLSPAN=15>";
  echo "</TD></TR>\n";
  echo "</TABLE>\n";
  echo "<H3 ALIGN=\"CENTER\">Link note to other resources</H3>\n";
  if($link_type=="Student") notes_links_student($link_id); 
  if($link_type=="Company") notes_links_company($link_id);


  echo "<P ALIGN=\"CENTER\">\n";
  echo "<INPUT TYPE=\"submit\" NAME=\"button\" VALUE=\"Submit\">\n";
  echo "<INPUT TYPE=\"reset\" VALUE=\"Reset\">\n";
  echo "</P></FORM>\n";

  output_help("NoteAddGuidance");
}

/*****************************************************************/





/*****************************************************************
**  
**  function search_note_list($link_type, $link_id, $extra)
** 
*****************************************************************/

function notes_search_list($link_type, $link_id, $extra)
{
  global $search;

  $query = "SELECT notes.* FROM notes, notelink WHERE " .
           " notes.note_id = notelink.note_id" .
           " AND notelink.link_type = " . make_null($link_type) .
           " AND notelink.link_id = " . make_null($link_id) .
           " AND (notes.summary LIKE '%" . $search . "%'" .
           " OR notes.comments LIKE '%" . $search . "%')" .
           " ORDER BY date DESC";

  notes_startsearch($query, $link_type, $link_id, $extra);
}

/****************************************************************/





/**
**	notes_display
**
** Displays note information if user is authenticated.
**
** @param $note_id (CGI) the id of the note to display
*/
function notes_display()
{
  global $conf;
  global $note_id;

  // Should this user see this note?
  if(!note_authenticate($note_id))
    die_gracefully("You do not have permission to view this note.");
 
 
  $query = "SELECT * FROM notes WHERE note_id=$note_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch note details.", $query);
  
  $row = mysql_fetch_array($result);
  mysql_free_result($result);

  echo "<H3 ALIGN=\"CENTER\">Note by " .
        htmlspecialchars(get_user_name($row["author_id"])) . "</H3>\n";
  echo "<H3 ALIGN=\"CENTER\">Dated " . htmlspecialchars($row["date"]) . "</H3>\n";

  // View main information
  if(!empty($row["summary"]))
    echo "<H3 ALIGN=\"CENTER\">Summary:  " . htmlspecialchars($row["summary"]) . "</H3>\n";
  echo "<P><B>Note Contents:</B></P>";
  echo "<P>" . $row["comments"] . "</P>";

  // View note cross links
  echo "<H3 ALIGN=\"CENTER\">Note linked to</H3>";

  $query = "SELECT * FROM notelink WHERE note_id=$note_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch note links", $query);

  echo "<UL>";
  while($row = mysql_fetch_array($result))
  {
    echo "<LI>";
    switch($row["link_type"])
    {
      case "Student":
        if(is_admin())
        {
          echo "<A HREF=\"" . $conf['scripts']['admin']['studentdir'] . 
               "?mode=STUDENT_DISPLAYSTATUS&student_id=" . $row["link_id"] . "\">";
        }
        echo htmlspecialchars(get_user_name($row["link_id"]));
        if(is_admin())
        {
          echo "</A>\n";
        }
        echo " (Student)";
        break;
      case "Staff":
        if(is_admin())
        {
          echo "<A HREF=\"" . $conf['scripts']['staff']['directory'] . 
               "?mode=BasicEdit&user_id=" . $row["link_id"] . "\">";
        }
        echo htmlspecialchars(get_user_name($row["link_id"]));
        if(is_admin())
        {
          echo "</A>\n";
        }
        echo " (Staff)";
        break;
      case "Company":
        if(is_admin())
        {
          echo "<A HREF=\"" . $conf['scripts']['company']['edit'] . 
               "?company_id=" . $row["link_id"] . "\">";
        }
        echo htmlspecialchars(get_company_name($row["link_id"]));
        if(is_admin())
        {
          echo "</A>\n";
        }
        echo " (Company)";
        break;
      case "Contact":
        if(is_admin())
        {
          echo "<A HREF=\"" . $conf['scripts']['company']['contacts'] .
               "?mode=CONTACT_BASICEDIT&contact_id=" . get_contact_id($row["link_id"]) . "\">";
        }
        echo htmlspecialchars(get_user_name($row["link_id"]));
        if(is_admin())
        {
          echo "</A>\n";
        }
        echo " (Company Contact)";
        break;
      case "Admin":
        echo htmlspecialchars(get_user_name($row["link_id"]));
        echo " (Admin)";
        break;
      default:
        echo "Unknown link type - inform webmaster.";
        break;
    }
    echo "</LI>";
  }
  echo "</UL>\n";
  mysql_free_result($result);
}


function notes_insert()
{
  global $comments, $summary, $user, $auth;
  global $link_type, $link_id, $note_id; 

  if(!is_admin())
  {
    if(empty($auth))
      die_gracefully("If the authorisation field is left empty, you will not have access to read this note in future!");
  }

  $query = "INSERT INTO notes VALUES(" . date("YmdHis") . ",  ".
            make_null($comments) . ", " .
            make_null($summary) . ", " .
            make_null($auth) . ", " . $_SESSION['user']['id'] . ", 0)";
 


  if(empty($summary))
  {
    die_gracefully("The summary field must not be empty");
  }
  else

  mysql_query($query)
    or print_mysql_error2("Unable to insert note", $query);
  
  // Always add a default link
  $note_id = mysql_insert_id();
  notes_create_link($note_id, $link_type, $link_id);

  // Any additional links
  foreach($_POST as $var => $value)
  {
    if(substr($var, 0, 2)=='nl')
    {
      notes_decodecreate_link($note_id, $var);
    }
  }
}

function notes_decodecreate_link($note_id, $var)
{
  $var = substr($var, 2);

  if(substr($var, 0, 7)=="Student")
  {
    $link_id=substr($var, 7);
    notes_create_link($note_id, "Student", $link_id);
  }
  if(substr($var, 0, 5)=="Staff")
  {
    $link_id=substr($var, 5);
    notes_create_link($note_id, "Staff", $link_id);
  }
  if(substr($var, 0, 5)=="Admin")
  {
    $link_id=substr($var, 5);
    notes_create_link($note_id, "Admin", $link_id);
  }
  if(substr($var, 0, 7)=="Company")
  {
    $link_id=substr($var, 7);
    notes_create_link($note_id, "Company", $link_id);
  }
  if(substr($var, 0, 7)=="Contact")
  {
    $link_id=substr($var, 7);
    notes_create_link($note_id, "Contact", $link_id);
  }
}

function notes_create_link($note_id, $link_type, $link_id)
{
  $query = "INSERT INTO notelink VALUES(" .
           make_null($link_type) . ", $link_id, $note_id)";
  mysql_query($query)
    or print_mysql_error2("Unable to insert note link", $query);
}

/****************************************************************************/

?>