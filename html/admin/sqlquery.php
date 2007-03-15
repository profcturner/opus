<?php

/*
** This debugging, temporary script, mainly for Colin's
** use allows mysql queries to be launched when logged
** in with MySQL credentials.
**
** Users with "root" access this script.
*/

// Include common functions
include ("common.php");
include ("authenticate.php");

// Attempt to open database using credentials
db_connect ()
  or die("Unable to connect to database server");

// Authenticate the user for this script
auth_user("root");

// The default action is to display the form
if(empty ($mode)){
  $mode = "FormDisplay";
}

// Call the right function for the right mode  
switch($mode)
{
  case "FormDisplay":
    display_sqlform();
    break;
  case "ResultsDisplay":
    if($type == "HTML")
      page_header("General SQL Query");
    display_sqlresults();
    if($type == "HTML")
      page_footer();
    break;
}



/*
**	display_sqlform
**
** Displays the request for the query on screen.
*/
function display_sqlform()
{
  global $PHP_SELF;
  global $log;

  page_header("General SQL Query");
  print_menu("");
  print("<H2 ALIGN=\"CENTER\">Enter Query</H2>\n");

  printf("<FORM METHOD=\"post\" ACTION=\"%s?mode=%s\">\n",
    $PHP_SELF, "ResultsDisplay");
    
  print("<TABLE ALIGN=\"CENTER\">");  
 
  print("<TR><TD>");
  print("<TEXTAREA NAME=\"query\" ROWS=10 COLS=40>");
  print("</TEXTAREA>");
  print("</TD></TR>");
  echo "<TR><TD ALIGN=\"CENTER\">" .
       "Output Format : <BR>" .
       "<INPUT TYPE=\"RADIO\" NAME=\"type\" VALUE=\"HTML\" CHECKED> HTML " .
       "<INPUT TYPE=\"RADIO\" NAME=\"type\" VALUE=\"TSV\"> Tab separated text " .
       "<INPUT TYPE=\"RADIO\" NAME=\"type\" VALUE=\"CSV\"> Comma separated text " .
       "</TD></TR>\n";
    printf("<TR><TD><INPUT TYPE=\"submit\" NAME=\"button\" VALUE=\"Submit\">");
  printf(" <INPUT TYPE=\"reset\" VALUE=\"Reset\"></TD></TR>\n");
  printf("</TABLE>\n");
  printf("</FORM>\n"); 
  $log['admin']->LogPrint("SQL Query form displayed");
  page_footer();
}


/*
**	display_sqlresults
**
**
*/
function display_sqlresults()
{
  global $query;
  global $type;
  global $log;
  
  $log['admin']->LogPrint("SQL Query launched");                 

  // Tell the browser what type to expect
  switch($type)
  {
    case "TSV":
      header("Content-type: text/tab-separated-values");
      header("Content-Disposition: attachment; filename=\"SQLResults.tsv\"");
      break;
    case "CSV":
      header("Content-type: text/comma-separated-values");
      header("Content-Disposition: attachment; filename=\"SQLResults.csv\"");
      break;
    case "HTML":
      echo "<H2 ALIGN=\"CENTER\">Query Results</H2>\n" .
           "<TABLE ALIGN=\"CENTER\" BORDER=\"1\">\n";
      break;
    default:
      page_header("Error");
      die_gracefully("Unknown type");
  }

  // Attempt to pass the query to MySQL  
  $result = mysql_query(stripslashes($query))
     or print_mysql_error("Unable to execute query");

  $cols = mysql_num_fields($result);
  // Results
  while($row = mysql_fetch_row($result)){
    if($type == "HTML") echo "<TR>\n";
    for($loop = 0; $loop < $cols; $loop++){
      if($type == "HTML"){
        echo "  <TD>";
        if(empty($row[$loop])) echo "NULL";
        else echo htmlspecialchars($row[$loop]);
      }
      else echo $row[$loop];

      switch($type)
      {
        case "TSV":
          echo "\t";
          break;
        case "CSV":
          echo ",";
          break;
        case "HTML":
          echo "</TD>\n";
          break;
      }
    }
    if($mode == "HTML") echo "</TR>";
    echo "\n";
  }
  if($mode == "HTML"){
    echo "</TABLE>\n" .
         "<P ALIGN=\"CENTER\">%s rows affected</P>\n" . mysql_affected_rows();
  }
}

  
?>
