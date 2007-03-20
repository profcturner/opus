<?php

/**
**	notes.php
**
** This file allows students to see their notes
**
** Initial coding : Colin Turner
**
*/

// The include files
include('common.php');
include('authenticate.php');
include('lookup.php');
include('notes.php');

// Connect to the database on the server
db_connect()
  or die("Unable to connect to server");

// Authenticate user so that the right people see the right thing
auth_user("student");

page_header("notes");

if(is_student())
{
  $student_id = get_id(); // Ensure students only see themselves
}

if(empty($student_id))
  dir_gracefully("This script requires a student_id");

if(is_admin())
{
  if(!is_auth_for_student($student_id, "student", "viewStatus"));
}

$mode = $_REQUEST['mode'];

switch($mode)
{
 case "":
 case "StudentDisplayNotes":
   student_display_notes();
   break;
 case "Display_Single_Note":
   student_display_note();
   break;
 case "NoteForm":
   student_note_form();
   break;
 case "Insert_Note":
   student_insert_note();
   break;
 case "Notes_Search":
   student_notes_search();
   break;
 default:
   echo "Invalid mode";
}

$page->end();

/**
**	@function student_display_notes()
**
*/
function student_display_notes()
{
  global $student_id;

  if(empty($student_id)){
    die_gracefully("This page should not be accessed without a student id.");
  }

  $query = sprintf("SELECT * FROM id WHERE id_number=%s", $student_id);
  $result = mysql_query($query)
    or print_mysql_error("Unable to access user information.");

  $row = mysql_fetch_array($result);
  mysql_free_result($result);

  printf("<H2 ALIGN=\"CENTER\">%s (%s)</H2>\n",
         htmlentities($row["real_name"]), htmlentities($row["username"]));

  printf("<H3 ALIGN=\"CENTER\">Notes</H3>\n");

  if($row["user"]!="student"){
    print("<P ALIGN=\"CENTER\">Error, this is not a student.</P>\n");
    return;
  }

 
  notes_display_list("Student", $student_id, "student_id=$student_id");
}


function student_notes_search()
{
  global $student_id;

  if(empty($student_id)){
    die_gracefully("This page should not be accessed without a student id.");
  }

  $query = sprintf("SELECT * FROM id WHERE id_number=%s", $student_id);
  $result = mysql_query($query)
    or print_mysql_error("Unable to access user information.");

  $row = mysql_fetch_array($result);
  mysql_free_result($result);

  printf("<H2 ALIGN=\"CENTER\">%s (%s)</H2>\n",
         htmlentities($row["real_name"]), htmlentities($row["username"]));

  printf("<H3 ALIGN=\"CENTER\">Notes</H3>\n");

  if($row["user"]!="student"){
    print("<P ALIGN=\"CENTER\">Error, this is not a student.</P>\n");
    return;
  }


  notes_search_list("Student", $student_id, "student_id=$student_id");

}

function student_insert_note()
{
  notes_insert();
  student_display_notes();
}

function student_note_form()
{
  global $student_id;
  global $PHP_SELF;

  if(empty($student_id)){
    die_gracefully("This page should not be accessed without a student id.");
  }

  $query = sprintf("SELECT * FROM id WHERE id_number=%s", $student_id);
  $result = mysql_query($query)
    or print_mysql_error("Unable to access user information.");

  $row = mysql_fetch_array($result);
  mysql_free_result($result);

  printf("<H2 ALIGN=\"CENTER\">%s (%s)</H2>\n",
         htmlentities($row["real_name"]), htmlentities($row["username"]));

  printf("<H3 ALIGN=\"CENTER\">Notes</H3>\n");

  if($row["user"]!="student"){
    print("<P ALIGN=\"CENTER\">Error, this is not a student.</P>\n");
    return;
  }

  notes_form("Student", $student_id, "student_id=$student_id");

}

function student_display_note()
{
  global $student_id;

  if(empty($student_id)){
    die_gracefully("This page should not be accessed without a student id.");
  }

  $query = sprintf("SELECT * FROM id WHERE id_number=%s", $student_id);
  $result = mysql_query($query)
    or print_mysql_error("Unable to access user information.");

  $row = mysql_fetch_array($result);
  mysql_free_result($result);

  printf("<H2 ALIGN=\"CENTER\">%s (%s)</H2>\n",
         htmlentities($row["real_name"]), htmlentities($row["username"]));

  printf("<H3 ALIGN=\"CENTER\">Notes</H3>\n");

  if($row["user"]!="student"){
    print("<P ALIGN=\"CENTER\">Error, this is not a student.</P>\n");
    return;
  }


  notes_display();
}


?>
