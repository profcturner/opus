<?php
/*
**	disclaimer.php
**
** Ensures compliance with disclaimers as required.
**
** Colin Turner
**
*/

function student_disclaimer()
{
  global $PHP_SELF;
  global $system_sdisclaim;
  global $page; // We might need to create a page

  if(empty($system_sdisclaim)){
    $page = new HTMLOPUS("Disclaimer");
    include("studentdisclaimer.php");
  
    echo "<FORM ACTION=\"" . $PHP_SELF .
         "\" METHOD=\"POST\">\n" ;
    echo "<TABLE>\n<TR><TD>\n" .
         "<INPUT TYPE=\"CHECKBOX\" NAME=\"system_sdisclaim\"" .
         " VALUE=\"1\"></TD>\n" .
         "<TD>I agree to these terms.</TD></TR>\n" .
         "<TR><TD COLSPAN=\"2\"><INPUT TYPE=\"submit\"" .
         " NAME=\"button\" VALUE=\"Submit\">" .
         "<INPUT TYPE=\"reset\" VALUE=\"Reset\"></TD></TR>\n";
    echo "</TABLE>\n</FORM>\n";
    page_footer("");
    exit;
  }
  else{
    $query = "UPDATE students SET progress='disclaimer' " .
             "WHERE user_id=" . get_id();
    mysql_query($query)
      or print_mysql_error2("Unable to note disclaimer acceptance.", $query);
  }
}

?>