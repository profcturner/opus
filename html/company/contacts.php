<?php

/**
**  contacts.php
**
** Allows company contacts
**
** Initial coding : Colin Turner
**
*/

// The include files 
include('common.php');		
include('authenticate.php');
include('lookup.php');	
include('wizard.php');
include('users.php');

// Connect to the database on the server
db_connect()
  or die("Unable to connect to server");
  
// Authenticate user so that the right people see the right thing
auth_user("company");

$page = new HTMLOPUS("Contact Editor", "directories");	// Calls the function for the header

// Ordinary users can only view themselves
if(is_company()){
  $query = "SELECT contact_id FROM contacts WHERE user_id=" . get_id();
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch contact id.", $query);
  $row = mysql_fetch_row($result);
  mysql_free_result($result);

  $contact_id = $row[0];

  if(empty($mode)) $mode = CONTACT_BASICEDIT;
}

if(is_admin()){
  if(empty($mode) && !empty($contact_id)) $mode=CONTACT_BASICEDIT;
  if(empty($mode)) $mode = CONTACT_STARTSEARCH;
}
           

// Getting into the right mode for the right job
switch($mode)
{

  case CONTACT_STARTSEARCH:
    contact_startsearch();
    break;

  case CONTACTS_SEARCH_RESULTS:
    contacts_search_results();
    break;

  case CONTACT_BASICEDIT:
    contact_basicedit();
    break;

  case CONTACT_BASICUPDATE:
    contact_basicupdate();
    break;

  case CONTACT_DISPLAYCOMPANIES:
    contact_displaycompanies();
    break;

  case CONTACT_STARTADD:
    contact_startadd();
    break;

  case CONTACT_ADD:
    contact_add();
    break;

  case CONTACT_DELETE:
    contact_delete();
    break;

  case CONTACT_NEWPASSWORD:
    contact_newpassword();
    break;

  case SUPERVISOR_NEWPASSWORD:
    supervisor_newpassword();
    break;

  default:
    die_gracefully("invalid mode");
    break;

}



// Print out the help column on rigth hand side
//right_column("pdetails");

// Print the footer and finish the page
page_footer();			


function contact_startsearch()
{
  global $smarty;

  $form = array();
  $form['name'] = "ContactSearch";
  $form['action'] = $_SERVER['PHP_SELF'];
  $form['method'] = "POST";

  $form['hidden'] = array();
  $form['hidden']['mode'] = "CONTACTS_SEARCH_RESULTS";

  $smarty->assign("form", $form);
  $smarty->display("companies/contacts_search_form.tpl");
}


function contacts_search_results()
{
  global $smarty;
  global $log;

  $search = $_REQUEST['search']; // Search String
  $search_hr = !empty($_REQUEST['SearchContacts']); // Search HR contacts (checkbox)
  $search_is = !empty($_REQUEST['SearchSupervisors']); // Search Industrial Supervisors (checkbox)

  if(!$search_hr && !$search_is)
    die_gracefully("You must search either supervisors or HR contacts");

  if(!is_admin() || !check_default_policy("contact", "list"))
    die_gracefully("You do not have permission to access this page.");
 
  echo "<H3 ALIGN=\"CENTER\">Search Result</H3>\n";

  // Form Search criteria string
  if(!empty($search)){
    $hr_searchc .= " surname LIKE '%" . $search . "%'" .
                " OR firstname  LIKE '%" . $search . "%'";
    $is_searchc .= " supervisor_surname LIKE '%" . $search . "%'" .
                " OR supervisor_firstname  LIKE '%" . $search . "%'";

  }

  $hr_query = "SELECT * FROM contacts";
  $is_query = "SELECT * FROM placement";

  // Search Criteria
  if(!empty($search))
  {
    $hr_query .= " WHERE" . $hr_searchc;
    $is_query .= " WHERE" . $is_searchc;
  }

  // Get the HR Results
  $result = mysql_query($hr_query)
    or print_mysql_error2("Unable to fetch contact list", $hr_query);
  
  $hr_results = array();
  while($row = mysql_fetch_array($result))
  {
    $row["company_name"] = "Fill in Company";
    array_push($hr_results, $row);
  }
  mysql_free_result($result);

  // Get the IS Results
  $result = mysql_query($is_query)
    or print_mysql_error2("Unable to fetch contact list", $is_query);
  
  $is_results = array();
  while($row = mysql_fetch_array($result))
  {
    $row['company_name'] = get_company_name($row['company_id']);
    array_push($is_results, $row);
  }
  mysql_free_result($result);

  $smarty->assign("hr_results", $hr_results);
  $smarty->assign("is_results", $is_results);
  $smarty->display("companies/contacts_search_results.tpl");
  /*


  // If there are no entries then say so...  
  if(!mysql_num_rows($result)){
    printf("<H2 ALIGN=\"CENTER\">No Matches Found</H2>");
    
    printf("<P ALIGN=\"CENTER\">No contacts could be found to match ");
    printf("the search criteria.</P>");
  
    page_footer();
    exit(0);
  }
  echo "<TABLE ALIGN=\"CENTER\" BORDER=\"0\">\n";
  while($row = mysql_fetch_array($result)){
    echo "<TR><TD><A HREF=\"" . $PHP_SELF .
         "?mode=CONTACT_BASICEDIT&contact_id=" .
         $row["contact_id"] . "\">" . htmlspecialchars($row["title"]) .
         " " . htmlspecialchars($row["firstname"]) .
         " " . htmlspecialchars($row["surname"]) .
         "</A></TD></TR>\n";
  }
  printf("</TABLE>\n");

  printf("<HR>\n");

  printf("<P>%u contacts met your search criterion.</P>\n", mysql_num_rows($result));
 */
  $log['access']->LogPrint("contact search performed.");

}


function contact_basicedit()
{
  global $PHP_SELF;
  global $contact_id;
  global $log;

  $query = "SELECT * FROM contacts WHERE contact_id=" . $contact_id;
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch contact data.", $query);

  $row = mysql_fetch_array($result);

  echo "<H2 ALIGN=\"CENTER\">" . 
       htmlspecialchars($row["title"] . " " . $row["firstname"] . " " . $row["surname"]) .
       "</H2>\n";
  echo "<H3 ALIGN=\"CENTER\">Basic Details</H3>\n";
  print_wizard("Basics");

  if(is_admin() && !check_default_policy("contact", "list"))
    die_gracefully("You do not have permission to view this data");

  contact_basicform($row);

  $log['access']->LogPrint("Basic details for " . 
                           $row["title"] . " " .
                           $row["firstname"] . " " .
                           $row["surname"] . "viewed.");

  if(is_admin()){
    echo "<P ALIGN=\"CENTER\"><A HREF=\"$PHP_SELF?mode=CONTACT_DELETE&contact_id=$contact_id\">" .
         "Click here to delete this contact.</A><BR>\n";
    echo "<A HREF=\"$PHP_SELF?mode=CONTACT_NEWPASSWORD&contact_id=$contact_id\">" .
         "Click here to send a new password to this contact.</A></P>\n";
  }
}


function contact_basicform($row)
{
  global $PHP_SELF;
  global $contact_id;
  global $company_id;

  echo "<FORM METHOD=\"POST\" ";
  echo "ACTION=\"" . $PHP_SELF;

  if(!empty($contact_id))
  {
    echo "?mode=CONTACT_BASICUPDATE&contact_id=$contact_id";
    if(!empty($company_id)) echo "&company_id=$company_id";
  }
  else
    echo "?mode=CONTACT_ADD&company_id=" . $company_id;

  echo "\">\n";
  

  echo "<TABLE ALIGN=\"CENTER\">\n";

  echo "<TR><TD>Title</TD><TD>" .
       "<INPUT TYPE=\"TEXT\" NAME=\"title\" SIZE=\"10\" VALUE=\"" .
       htmlspecialchars($row["title"]) . "\"></TD></TR>\n";

  echo "<TR><TD>First name</TD><TD>" .
       "<INPUT TYPE=\"TEXT\" NAME=\"firstname\" SIZE=\"30\" VALUE=\"" .
       htmlspecialchars($row["firstname"]) . "\"></TD></TR>\n";

  echo "<TR><TD>Surname</TD><TD>" .
       "<INPUT TYPE=\"TEXT\" NAME=\"surname\" SIZE=\"30\" VALUE=\"" .
       htmlspecialchars($row["surname"]) . "\"></TD></TR>\n";

  echo "<TR><TD>Position</TD><TD>" .
       "<INPUT TYPE=\"TEXT\" NAME=\"position\" SIZE=\"30\" VALUE=\"" .
       htmlspecialchars($row["position"]) . "\"></TD></TR>\n";

  echo "<TR><TD>Phone</TD><TD>" .
       "<INPUT TYPE=\"TEXT\" NAME=\"voice\" SIZE=\"30\" VALUE=\"" .
       htmlspecialchars($row["voice"]) . "\"></TD></TR>\n";

  echo "<TR><TD>Fax</TD><TD>" .
       "<INPUT TYPE=\"TEXT\" NAME=\"fax\" SIZE=\"30\" VALUE=\"" .
       htmlspecialchars($row["fax"]) . "\"></TD></TR>\n";

  echo "<TR><TD>Email</TD><TD>" .
       "<INPUT TYPE=\"TEXT\" NAME=\"email\" SIZE=\"50\" VALUE=\"" .
       htmlspecialchars($row["email"]) . "\"></TD></TR>\n";

  if(is_admin())
  {
    if(empty($contact_id)){
      $status = $row["status"];
    }
    else{
      if(!empty($company_id)){
        // fetch existing status
        $status_query = "SELECT status FROM companycontact WHERE " .
                        "company_id=$company_id AND contact_id=$contact_id";
        $status_result = mysql_query($status_query)
          or print_mysql_error2("Unable to fetch contact status", $status_query);
        $status_row = mysql_fetch_row($status_result);
        $status = $status_row[0];
      }
    }
    if(empty($status)) $status='normal';
    echo "<TR><TD>Status</TD><TD>" .
         "<SELECT NAME=\"status\">\n";
    
    echo "<OPTION VALUE=\"primary\"";
    if($status=="primary") echo " SELECTED";
    echo ">primary</OPTION>\n";

    echo "<OPTION VALUE=\"normal\"";
    if($status=="normal") echo " SELECTED";
    echo ">normal</OPTION>\n";

    echo "<OPTION VALUE=\"restricted\"";
    if($status=="restricted") echo " SELECTED";
    echo ">restricted</OPTION>\n";
    echo "</SELECT>\n";
  }

  if(empty($contact_id))
  {
    // user related fields
    echo "<TR><TD>Username</TD><TD>" .
         "<INPUT TYPE=\"TEXT\" NAME=\"username\" SIZE=\"10\" VALUE=\"auto\"></TD></TR>\n";

    echo "<TR><TD>Real Name</TD><TD>" .
         "<INPUT TYPE=\"TEXT\" NAME=\"realname\" SIZE=\"30\" VALUE=\"auto\"></TD></TR>\n";


    echo "<INPUT TYPE=\"HIDDEN\" NAME=\"password\" SIZE=\"10\" VALUE=\"auto\"></TD></TR>\n";
    echo "<INPUT TYPE=\"HIDDEN\" NAME=\"cpassword\" SIZE=\"10\" VALUE=\"auto\"></TD></TR>\n";
  }

  echo "<TR><TD COLSPAN=\"2\" ALIGN=\"CENTER\">";
  echo "<INPUT TYPE=\"submit\" NAME=\"button\" VALUE=\"Submit\">";
  echo "<INPUT TYPE=\"reset\" VALUE=\"Reset\"></TD></TR>\n";


  echo "</TABLE>\n";
  echo "</FORM>\n";

  if(is_admin() && !empty($company_id))
  {
    echo "<P>Note that the contact status refers only to this contact's " .
         "association with " . htmlspecialchars(get_company_name($company_id)) . "</P>\n";
  }

}


function contact_basicupdate()
{
  global $title, $firstname, $surname;
  global $position, $voice, $fax, $email;
  global $contact_id;
  global $company_id, $status;

  if(empty($contact_id))
    die_gracefully("You cannot access this page without a contact id.");

  if(is_admin() && !check_default_policy("contact", "edit"))
    die_gracefully("You do not have permission to edit this contact");

  // Form the query
  $query = "UPDATE contacts SET" .
             "  title="     . make_null($title) .
             ", firstname=" . make_null($firstname) .
             ", surname="   . make_null($surname) .
             ", position="  . make_null($position) .
             ", voice="     . make_null($voice) .
             ", fax="       . make_null($fax) .
             ", email="     . make_null($email) .
             " WHERE contact_id=" . $contact_id;

  // Now try it
  mysql_query($query)
    or print_mysql_error2("Unable to update contact record.", $query);

  if(is_admin() && !empty($company_id) && !empty($status))
  {
    $query = "UPDATE companycontact SET status=" . make_null($status) .
             " WHERE company_id=$company_id AND contact_id=$contact_id";
    mysql_query($query)
      or print_mysql_error2("Unable to update status.", $query);

  }

  echo "<H2 ALIGN=\"CENTER\">Contact record updated</H2>\n";

  contact_basicedit();
}


function contact_displaycompanies()
{
  global $contact_id;
  global $conf;
  global $log;

  if(empty($contact_id))
    die_gracefully("This page cannot be accessed without a contact id.");

  $query = "SELECT * FROM contacts WHERE contact_id=" . $contact_id;
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch contact information.", $query);
  $contact = mysql_fetch_array($result);
  mysql_free_result($result);


  echo "<H2 ALIGN=\"CENTER\">" .
       htmlspecialchars($contact['title'] . " " . $contact['firstname'] . " " . $contact['surname']) .
       "</H2>\n";

  echo "<H3 ALIGN=\"CENTER\">Company List</H3>\n";

  print_wizard("Companies");

  if(is_admin() && !check_default_policy("contact", "list"))
    die_gracefully("You do not have permission to view this data");

  $query = "SELECT companies.* FROM companies, companycontact " .
           "WHERE companies.company_id = companycontact.company_id AND " .
           "companycontact.contact_id =" . $contact_id;

  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch current company list.", $query);

  if(mysql_num_rows($result)){
    echo "<TABLE ALIGN=\"CENTER\">\n";
    while($row = mysql_fetch_array($result))
    {
       echo "<TR><TD><A HREF=\"" . $conf['scripts']['company']['edit'] .
            "?company_id=" . $row["company_id"] . "\">" .
            htmlspecialchars($row["name"]) . " (" . htmlspecialchars($row["locality"]) .
            ")</A></TD></TR>\n";
       }
    echo "</TABLE>\n";
  }
  else{
    echo "<P>No companies are attached to this contact as yet.</P>\n";
  }

  $log['access']->LogPrint("company list for " . 
                           $contact['title'] . " " . $contact['firstname'] . " " .
                           $contact['surname'] . " viewed.");
}


function contact_startadd()
{
  global $company_id;
  global $log;

  
  if(!is_admin() || !check_default_policy("contact", "create"))
    die_gracefully("You do not have permission to create contacts");

  echo "<H2 ALIGN=\"CENTER\">Adding a new contact for<BR>" . 
       htmlspecialchars(get_company_name($company_id)) .
       "</H2>\n";
  echo "<H3 ALIGN=\"CENTER\">Basic Details</H3>\n";
  contact_basicform("");

  $log['access']->LogPrint("starting to create contact for " . get_company_name($company_id));

}


function contact_add()
{
  global $PHP_SELF;
  global $company_id;
  global $title, $firstname, $surname, $position;
  global $voice, $fax, $email, $status;
  global $username, $realname, $password;
  global $conf;

  if(!is_admin() || !check_default_policy("contact", "create"))
    die_gracefully("You do not have permission to create contacts");

  if(empty($company_id))
    die_gracefully("You cannot access this page without a valid company id.");

  if(empty($surname))
    die_gracefully("The surname field cannot be empty.");

  // We need to make the user entry
  if($username=="auto"){
    $username = user_make_username($title, $firstname, $surname);
    if($username==FALSE){
      die_gracefully("Automatic username allocation failed, please use the back button and manually try a username.");
    }
  }
  else{
    $query = "SELECT * FROM id WHERE username='$username'";
    $result = mysql_query($query)
      or print_mysql_error2("Unable to query id table.", $query);
    if(mysql_num_rows($result)) die_gracefully("The username $username is already in use, please select another.");
    mysql_free_result($result);
  }
  if($password=="auto"){
    $password = user_make_password();
  }
  if($realname=="auto"){
    $realname = $title . " " . $firstname . " " . $surname;
  }
  $query = "INSERT INTO id VALUES(" .
           make_null($realname) . ", " .
           make_null($username) . ", " .
           make_null(MD5($password)) . ", 'company', NULL, NULL, 0)";

  mysql_query($query)
    or print_mysql_error2("Unable to create new user entry.", $query);

  // Fetch the user id just allocated.
  $user_id = mysql_insert_id();
           
  // Form contacts table query
  $query = "INSERT INTO contacts VALUES(" .
           make_null($title) . ", " .
           make_null($firstname) . ", " .
           make_null($surname) . ", " .
           make_null($position) . ", " .
           make_null($voice) . ", " .
           make_null($fax) . ", " .
           make_null($email) . ", 0, " .
           $user_id . ")";

  mysql_query($query)
    or print_mysql_error2("Unable to make new contacts table entry.", $query);

  // Find the contact id given.
  $contact_id = mysql_insert_id();

  // Make an appropriate entry in the company contact table
  $query = "INSERT INTO companycontact VALUES(" .
           "$company_id, $contact_id, '$status')";

  mysql_query($query)
    or print_mysql_error2("Unable to update company contacts.", $query);

  if(!empty($email))
  {
    user_notify_password($email, $title, $firstname, $surname, $username, $password, $user_id, "NewPassword_Contact");
    echo "<P ALIGN=\"CENTER\">The user has been emailed a username and password.</P>\n";
  }
  else{
    echo "<P ALIGN=\"CENTER\">No email address is listed for this user " .
         "and so it is impossible to send them the new credentials.<BR>" .
         "They have been allocated as follows.<BR>" .
         "<TABLE>\n<TR><TD>Username</TD><TD>" . $username . "</TD></TR>\n" .
         "<TR><TD>Password</TD><TD>" . $password . "</TD></TR>\n</TABLE>\n";
  }
    echo "<P ALIGN=\"CENTER\"><A HREF=\"" .
         $conf['scripts']['company']['edit'] .
         "?company_id=$company_id\">" .
         "Return to company details for " .
         htmlspecialchars(get_company_name($company_id)) .
         "</A></P>\n";      
  
}


/*
**	contact_newpassword
**
** Automatically generates a new password for the contact and emails
** it if possible. Otherwise it displays it on screen.
*/
function contact_newpassword()
{
  global $PHP_SELF;
  global $log;
  global $contact_id;

  if(!is_admin() || !check_default_policy("contact", "create"))
    die_gracefully("You do not have permission to update passwords");

  // Fetch contact information
  $query = "SELECT * FROM contacts WHERE contact_id=$contact_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to obtain contact data.", $query);
  $row = mysql_fetch_array($result);

  // Fetch matching user information
  $user_query = "SELECT * FROM id WHERE id_number=" . $row["user_id"];
  $user_result = mysql_query($user_query)
    or print_mysql_error2("Unable to obtain user data.", $user_query);
  $user_row = mysql_fetch_array($user_result);

  // Generate a new password
  $password = user_make_password();

  // Put the new password in the database
  $new_query = "UPDATE id SET password=MD5('$password') WHERE id_number=" . $row["user_id"];
  mysql_query($new_query) or print_mysql_error2("Unable to update password.", $new_query);

  if(!empty($row["email"]))
  {
    user_notify_password($row["email"], $row["title"], $row["firstname"], $row["surname"],
                         $user_row["username"], $password, $row["user_id"], "NewPassword_Contact");
    echo "<P ALIGN=\"CENTER\">The user has been emailed a username and password.</P>\n";
  }
  else{
    echo "<P ALIGN=\"CENTER\">No email address is listed for this user " .
         "and so it is impossible to send them the new credentials.<BR>" .
         "They have been allocated as follows.<BR>" .
         "<TABLE>\n<TR><TD>Username</TD><TD>" . $user_row["username"] . "</TD></TR>\n" .
         "<TR><TD>Password</TD><TD>" . $password . "</TD></TR>\n</TABLE>\n";
  }

  contact_basicedit();
}


/*
**	supervisor_newpassword
**
** Automatically generates a new password for the contact and emails
** it if possible. Otherwise it displays it on screen.
*/
function supervisor_newpassword()
{
  global $PHP_SELF;
  global $log;
  global $placement_id;

  if(!is_admin() || !check_default_policy("contact", "create"))
    die_gracefully("You do not have permission to update passwords");
  
  if(!is_numeric($placement_id))
    die_gracefully("Placement ids can only be numbers");

  $supervisor_username="supervisor_$placement_id";
  $placement_query = "select * from placement where placement_id=$placement_id";
  $placement_result = mysql_query($placement_query)
    or print_mysql_error2("Unable to obtain placement data.", $placement_query);
  $placement_row = mysql_fetch_array($placement_result);


  // Fetch matching user information
  $user_query = "SELECT * FROM id WHERE username='$supervisor_username'";
  $user_result = mysql_query($user_query)
    or print_mysql_error2("Unable to obtain user data.", $user_query);
  $user_row = mysql_fetch_array($user_result);

  // Generate a new password
  $password = user_make_password();

  // Put the new password in the database
  $new_query = "UPDATE id SET password=MD5('$password') WHERE id_number=" . $user_row["id_number"];
  mysql_query($new_query) or print_mysql_error2("Unable to update password.", $new_query);

  if(!empty($placement_row["supervisor_email"]))
  {
    user_notify_password($placement_row["supervisor_email"],
      $placement_row["supervisor_title"],
      $placement_row["supervisor_firstname"],
      $placement_row["supervisor_surname"],
      $user_row["username"], $password, $user_row["id_number"], "NewPassword_Supervisor");
    echo "<P ALIGN=\"CENTER\">The user has been emailed a username and password.</P>\n";
  }
  else{
    echo "<P ALIGN=\"CENTER\">No email address is listed for this user " .
         "and so it is impossible to send them the new credentials.<BR>" .
         "They have been allocated as follows.<BR>" .
         "<TABLE>\n<TR><TD>Username</TD><TD>" . $user_row["username"] . "</TD></TR>\n" .
         "<TR><TD>Password</TD><TD>" . $password . "</TD></TR>\n</TABLE>\n";
  }

  contact_startsearch();
}



function contact_delete()
{
  global $PHP_SELF;
  global $log;
  global $contact_id;
  global $company_id;
  global $confirmed;
  global $detach;

  if(!is_admin() || !check_default_policy('contact', 'delete'))
  {
    die_gracefully("You do not have permission to delete contacts.");
  }

  $query = "SELECT user_id FROM contacts WHERE contact_id=$contact_id";
  $result = mysql_query($query)
    or print_mysql_error2("Can't fetch contact information.", $query);
  if(!mysql_num_rows($result))
    die_gracefully("No such contact exists.");
  $row = mysql_fetch_row($result);
  mysql_free_result($result);
  $user_id = $row[0];
  $user_name = get_user_name($user_id);

  if($confirmed==1){

    if($detach==1){
      // Not deleting, only detaching from one company
      $query = "DELETE FROM companycontact WHERE " .
               "company_id=$company_id AND contact_id=$contact_id";
      mysql_query($query)
        or print_mysql_error2("Unable to detatch contact.");

      echo "<P ALIGN=\"CENTER\">Contact " .
           htmlspecialchars($user_name) . " was detached " .
           "from " . htmlspecialchars(get_company_name($company_id)) . "</P>\n";
      $log['admin']-LogPrint("contact " . $user_name . " was detached from company " .
                             get_company_name($company_id));
      page_footer();
      exit(0);
    }
    else{
      // Root and branch delete
      // Delete all links to companies
      $query = "DELETE FROM companycontact WHERE contact_id=$contact_id";
      mysql_query($query)
        or print_mysql_error2("Failed to delete all links for contact.", $query);

      $query = "DELETE FROM id WHERE id_number=$user_id";
      mysql_query($query)
        or print_mysql_error2("Failed to delete user entry for contact.", $query);

      $query = "DELETE FROM contacts WHERE contact_id=$contact_id";

      $log['admin']->LogPrint("contact " . $user_name . " was totally removed " .
                              "from the system.");
      echo "<P ALIGN=\"CENTER\">Contact " . htmlspecialchars($user_name) .
           " totally removed from system.</P>";
    }
  }
  else{

    echo "<H2 ALIGN=\"CENTER\">Are you sure?</H2>\n";
    echo "<P ALIGN=\"CENTER\">You have started the process to delete a contact " .
         htmlspecialchars($user_name) . ".</P>";

    $query = "SELECT companies.* FROM companies, companycontact " .
             "WHERE companies.company_id = companycontact.company_id AND " .
             "companycontact.contact_id =" . $contact_id;

    $result = mysql_query($query)
      or print_mysql_error2("Unable to fetch current company list.", $query);

    if(mysql_num_rows($result)<2){
      // We assume, this is not a detach
      echo "<P ALIGN=\"CENTER\"><A HREF=\"$PHP_SELF?mode=CONTACT_DELETE&contact_id=$contact_id" .
           "&confirmed=1\">Click here to confirm delete</A></P>\n";
    }


    if(mysql_num_rows($result)>1){
      echo "<P ALIGN=\"CENTER\">This contact represents more than one " .
           "company. It is still possible to completely delete the " .
           "record, or it can be detached from connection to a specific " .
           "company instead.</P>\n";

      echo "<TABLE ALIGN=\"CENTER\">\n";
      while($row = mysql_fetch_array($result))
      {
         echo "<TR><TD>" .
              htmlspecialchars($row["name"]) . " (" . htmlspecialchars($row["locality"]) .
              ")</TD><TD><A HREF=\"$PHP_SELF?contact_id=$contact_id" .
              "&confirmed=1&detach=1&company_id=" . $row["company_id"] . "\">Detach" .
              "</A><TD></TR>\n";
         }
      echo "</TABLE>\n";

      echo "<P ALIGN=\"CENTER\"><A HREF=\"$PHP_SELF?mode=CONTACT_DELETE&contact_id=$contact_id" .
           "&confirmed=1\">Click here to confirm delete</A></P>\n";
 
    }
  }
}


function print_wizard($item)
{
  global $conf;
  global $contact_id;
  global $smarty;

  $wizard = new TabbedContainer($smarty, "tabs");
  $wizard->addTab('Basics', $_SERVER['PHP_SELF'] . "?mode=CONTACT_BASICEDIT&contact_id=$contact_id");
  $wizard->addTab('Companies', $_SERVER['PHP_SELF'] . "?mode=CONTACT_DISPLAYCOMPANIES&contact_id=$contact_id");

  // Transitionary code
  echo "<div name=\"tabbedContainer\" align=\"center\">\n";
  $wizard->displayTab($item);
  echo "</div>\n";
}

?>


