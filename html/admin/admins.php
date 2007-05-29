<?php

/**
* Allows admin users and security policies to be edited
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
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
auth_user("admin");

//$page2 = new HTMLOPUS("Administrator Editor", "configuration", "admins");    
$page = new HTMLOPUS("Administrator Editor", "directories");	// Calls the function for the header

// Non root users may only edit themselves
if(!is_root())
{
  $admin_id = $_SESSION['user']['id'];
  if(empty($mode))  $mode = BasicEdit;
}
else
{
  if(empty($mode))  $mode = ListAdmins;
}

// Getting into the right mode for the right job
switch($mode)
{
  // Policy modes
  case ListPolicy:
    policy_list();
    break;

  case AddPolicy:
    policy_add();
    break;

  case DeletePolicy:
    policy_delete();
    break;

  case EditPolicy:
    policy_edit();
    break;

  case UpdatePolicy:
    policy_update();
    // Can't get logging to behave in the function because $log exists as variable
    $log['admin']->LogPrint("policy \"" . get_policy_name($policy_id) . "\" updated");
    break;

  // Admin modes
  case ListAdmins:
    admins_list();
    break;

  case BasicEdit:
    admin_edit();
    break;

  case AdminUpdate:
    admin_update();
    break;

  case AdminAdd:
    admin_add();
    break;
  
  case AdminDelete:
    admin_delete();
    break;

  case AdminNewPassword:
    admin_newpassword();
    break;

  case AdminPolicyUpdate:
    admin_policyupdate();
    break;

  case AdminStatusUpdate:
   admin_statusupdate();
   break;

  case AdminActivityAdd:
   admin_addactivity();
   break;

  case AdminActivityDelete:
   admin_deleteactivity();
   break;

  default:
    die_gracefully("Invalid mode");

}

// Print the footer and finish the page
$page->end();

/**
**	@function get_policy_companies
**	returns an array of categories used in the policy system
**	@return array of categories in the policy system
*/
function get_policy_categories()
{
  $policy_category = array('help', 'channel', 'cvgroup', 'assessmentgroup',
                           'automail', 'resources', 'import',
                           'status', 'log', 'school', 'course', 'company',
                           'vacancy', 'contact', 'staff', 'student');

  return($policy_category);
}


/**
**	@function get_policy_help
**	Defines permissions used in each category and gives brief help
**	@returns an array of [category][permission] and help
*/
function get_policy_help()
{
  $policy_help = array();

  $policy_help['cvgroup']['list']        = "Can list cv groups";
  $policy_help['cvgroup']['create']      = "Can create new cv groups";
  $policy_help['cvgroup']['edit']        = "Can edit cv groups";
  $policy_help['cvgroup']['delete']      = "Can delete cv groups";

  $policy_help['assessmentgroup']['list']        = "Can list assessment groups";
  $policy_help['assessmentgroup']['create']      = "Can create new assessment groups";
  $policy_help['assessmentgroup']['edit']        = "Can edit assessment groups";
  $policy_help['assessmentgroup']['delete']      = "Can delete assessment groups";

  $policy_help['channel']['list']        = "Can list channels";
  $policy_help['channel']['create']      = "Can create new channels";
  $policy_help['channel']['edit']        = "Can edit channels";
  $policy_help['channel']['delete']      = "Can delete channels";
  $policy_help['channel']['read']        = "Can read from a channel";
  $policy_help['channel']['write']      = "Can write to a channel";

  

  $policy_help['help']['list']        = "Can list help prompts";
  $policy_help['help']['create']      = "Can create new help prompts";
  $policy_help['help']['edit']        = "Can edit help prompts";
  $policy_help['help']['delete']      = "Can delete help prompts";

  $policy_help['automail']['list']    = "Can list mail templates";
  $policy_help['automail']['create']  = "Can create mail templates";
  $policy_help['automail']['edit']    = "Can edit mail templates";
  $policy_help['automail']['delete']  = "Can delete mail templates";

  $policy_help['resources']['list']   = "Can list resources";
  $policy_help['resources']['create'] = "Can create resources";
  $policy_help['resources']['edit']   = "Can edit resources";
  $policy_help['resources']['delete'] = "Can delete resources";
  
  $policy_help['import']['students']  = "Can import students";
  $policy_help['import']['photos']    = "Can upload photos on mass (not supported)";

  $policy_help['status']['user']      = "Can view user status";

  $policy_help['log']['access']       = "Can view general access log";
  $policy_help['log']['admin']        = "Can view administration log";
  $policy_help['log']['security']     = "Can view security log";
  $policy_help['log']['debug']        = "Can view debugging log";

  $policy_help['school']['list']      = "Can list schools";  
  $policy_help['school']['create']    = "Can create new schools";  
  $policy_help['school']['edit']      = "Can edit schools";  
  $policy_help['school']['archive']   = "Can archive schools";  

  $policy_help['course']['list']      = "Can list courses";  
  $policy_help['course']['create']    = "Can create new courses";  
  $policy_help['course']['edit']      = "Can edit course";  
  $policy_help['course']['archive']   = "Can archive courses";  

  $policy_help['company']['create']   = "Can create new companies";  
  $policy_help['company']['edit']     = "Can edit companies";  
  //$policy_help['company']['note']   = "Can edit schools";

  $policy_help['vacancy']['create']   = "Can create new vacancies";  
  $policy_help['vacancy']['edit']     = "Can edit vacancies";  
  $policy_help['vacancy']['delete']   = "Can delete vacancies";


  $policy_help['contact']['list']     = "Can list company contacts";
  $policy_help['contact']['create']   = "Can create company contacts";
  $policy_help['contact']['edit']     = "Can edit company contacts";
  $policy_help['contact']['archive']  = "Can archive company contacts";
  $policy_help['contact']['delete']   = "Can delete company contacts";

  $policy_help['staff']['list']       = "Can list academic staff";
  $policy_help['staff']['create']     = "Can create academic staff";
  $policy_help['staff']['edit']       = "Can edit academic staff";
  $policy_help['staff']['archive']    = "Can archive academic staff";

  $policy_help['student']['list']          = "Can list students";
  $policy_help['student']['create']        = "Can create new students";
  $policy_help['student']['viewCV']        = "Can view student CVs";
  $policy_help['student']['editCV']        = "Can edit student CVs";
  $policy_help['student']['viewStatus']    = "Can view student status";
  $policy_help['student']['editStatus']    = "Can edit student status";
  $policy_help['student']['viewCompanies'] = "Can view student company choices";
  $policy_help['student']['editCompanies'] = "Can edit student company choices";
  $policy_help['student']['viewAssessments'] = "Can view student assessment breakdowns";
  $policy_help['student']['editAssessments'] = "Can edit student assessment breakdowns";
 
  return($policy_help);
}


/**
**	@function low_check_policy
**	Checks a policy for a permission in a category
**	This is similar to the check_policy function defined
**	elsewhere, but does not check on the current users
**	behalf and so root users do not override.
**	@see check_policy
*/
function low_check_policy($policy, $category, $permission)
{
  return(strstr($policy[$category], $permission));
}


/**
**	@function policy_list
**	Lists all the security policies available on the system and provides a form for adding.
*/
function policy_list()
{
  global $PHP_SELF;

  $query = "SELECT descript, policy_id FROM policy ORDER BY descript";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to obtain policy listing", $query);
  
  echo "<H2 ALIGN=\"CENTER\">Policy Listing</H2>\n";
  print_wizard("Policies");

  echo "<TABLE ALIGN=\"CENTER\" BORDER=\"1\">\n";
  echo "<TR><TH>Policy</TH><TH>Options</TH></TR>\n";
  while($row = mysql_fetch_array($result))
  {
    echo "<TR><TD>" . htmlspecialchars($row["descript"]) . "</TD><TD>\n";
    echo "<A HREF=\"$PHP_SELF?mode=EditPolicy&policy_id=" .
         $row["policy_id"] . "\">[ Edit ]";
    if(is_root())
      echo "<A HREF=\"$PHP_SELF?mode=DeletePolicy&policy_id=" .
           $row["policy_id"] . "\">[ Delete ]";
    echo "</TD></TR>\n";
  }
  echo "</TABLE>";
  mysql_free_result($result);

  if(is_root())
  {
    // Form to add new policy
    echo "<H3 ALIGN=\"CENTER\">Add new policy</H3>\n";
    echo "<P ALIGN=\"CENTER\">To add a new policy use the form below<BR>\n";
    echo "<FORM METHOD=\"POST\" ACTION=\"$PHP_SELF\">\n";
    echo "<INPUT TYPE=\"HIDDEN\" NAME=\"mode\" VALUE=\"AddPolicy\">\n";
    echo "<INPUT TYPE=\"TEXT\" NAME=\"descript\" SIZE=\"30\">\n";
    echo "<INPUT TYPE=\"SUBMIT\" NAME=\"SUBMIT\" VALUE=\"Add\">\n";
    echo "</FORM>\n";
    echo "</P>\n";
  }
}


/**
**	@function policy_add
**	Adds a new policy with no permissions to the system.
**	@param $descript (CGI) The name of the new policy
**	@return (implicit) sets the global variable $policy_id to the created policy id
*/
function policy_add()
{
  global $descript;
  global $policy_id;

  if(!is_root()) die_gracefully("Only root users may add policies");
  if(empty($descript)) die_gracefully("You must define a policy name");

  // Check this name is not in use
  $query = "SELECT * FROM policy WHERE descript=" . make_null($descript);
  $result = mysql_query($query)
    or print_mysql_error2("Unable to query policy table", $query);
  if(mysql_num_rows($result))
    die_gracefully("That policy name is already in use.");
  mysql_free_result($result);

  // Ok, now add it...
  $query = "INSERT INTO policy (descript) VALUES(" . make_null($descript) . ")";
  mysql_query($query)
    or print_mysql_error2("Unable to add policy", $query);
  $policy_id = mysql_insert_id();
  policy_edit();
}


/**
**	@function policy_delete
**	Checks for confirmation and then deletes a policy
*/
function policy_delete()
{
  global $policy_id;
  global $confirm;

  if(!is_root()) die_gracefully("Only root users may delete policies");

  if($confirm != 1)
  {
    echo "<H2 ALIGN=\"CENTER\">Policy Editor</H2>\n";
    echo "<H3 ALIGN=\"CENTER\">" .
         htmlspecialchars(get_policy_name($policy_id)) . "</H3>\n";
    print_wizard("Policies");
    echo "<H3 ALIGN=\"CENTER\">Are you sure?</H3>\n";

    output_help("AdminAdminPolicyDelete");

    echo "<P ALIGN=\"CENTER\"><A HREF=\"$PHP_SELF?mode=DeletePolicy&policy_id=$policy_id&confirm=1\">" .
         "Really Delete Policy</A></P>\n";
    echo "<P ALIGN=\"CENTER\"><A HREF=\"$PHP_SELF?mode=EditPolicy&policy_id=$policy_id\">" .
         "Back to policy editor</A></P>\n";
  }
  else
  {
    $query = "DELETE FROM policy WHERE policy_id=$policy_id";
    mysql_query($query)
      or print_mysql_error2("Unable to delete policy", $query);
    // Back to listing policies
    policy_list();
  }
}


/**
**	@function policy_edit
**	Produces a form to view or edit existing policies
**	@param (CGI) $policy_id the policy id to edit
*/
function policy_edit()
{
  global $policy_id;

  if(empty($policy_id))
    die_gracefully("You must specify a policy id");

  echo "<H2 ALIGN=\"CENTER\">Policy Editor</H2>\n";
  // Fetch the policy under discussion
  $policy = load_policy($policy_id);
  if(!$policy) die_gracefully("Invalid policy");

  echo "<H3 ALIGN=\"CENTER\">" .
       htmlspecialchars(get_policy_name($policy_id)) . "</H3>\n";
  print_wizard("Policies");

  // Fetch the categories currently used
  $policy_category = get_policy_categories();
  $policy_help = get_policy_help();

  echo "<FORM METHOD=\"POST\" ACTION=\"$PHP_SELF\">\n";
  echo "<INPUT TYPE=\"HIDDEN\" NAME=\"mode\" VALUE=\"UpdatePolicy\">\n";
  echo "<INPUT TYPE=\"HIDDEN\" NAME=\"policy_id\" VALUE=\"$policy_id\">\n";
  echo "<TABLE ALIGN=\"CENTER\" BORDER=\"1\">\n";
  // Put the name at the top
  echo "<TR><TH COLSPAN=\"3\" ALIGN=\"CENTER\">Name</TH></TR>\n";
  echo "<TR><TD COLSPAN=\"3\" ALIGN=\"CENTER\"><INPUT TYPE=\"TEXT\" SIZE=\"30\" NAME=\"descript\"" .
       " VALUE=\"" . $policy["descript"] . "\"></TD></TR>\n";

  echo "<TR><TH COLSPAN=\"3\" ALIGN=\"CENTER\">Priority (for Help Directory)</TH></TR>\n";
  echo "<TR><TD COLSPAN=\"3\" ALIGN=\"CENTER\"><INPUT TYPE=\"TEXT\" SIZE=\"30\" NAME=\"priority\"" .
       " VALUE=\"" . $policy["priority"] . "\"></TD></TR>\n";

  foreach($policy_category as $category)
  {
    echo "<TR><TH COLSPAN=\"3\" ALIGN=\"CENTER\">" .
         htmlspecialchars($category) . "</TH></TR>\n";

    // Now look at all the items we have help for in that category
    foreach($policy_help[$category] as $permission => $help)
    {
      echo "<TR><TD>" . htmlspecialchars($permission) . "</TD>";
      // Encode checkboxes to make it easier in policy_update
      echo "<TD><INPUT TYPE=\"CHECKBOX\" NAME=\"cp" . $category . "_" . "$permission\" VALUE=\"1\"";
      if(low_check_policy($policy, $category, $permission)) echo " CHECKED";
      echo "></TD>";
      echo "<TD>" . htmlspecialchars($help) . "</TD></TR>\n";
    }
  }
  if(is_root())
  {
    echo "<TR><TD COLSPAN=\"3\" ALIGN=\"CENTER\">" .
         "<INPUT TYPE=\"Submit\" VALUE=\"Submit Changes\">\n" .
         " <INPUT TYPE=\"Reset\" VALUE=\"Reset Form\">\n" .
         "</TD></TR>\n";
  }
  echo "</TABLE>\n";
  echo "</FORM>\n";

  output_help("AdminAdminPolicyEdit");

}


/**
**	@function policy_update
**	Updates a policy from form data
**	Decodes the checkbox data and forms a query using the currently
**	known categories. Unknown categories will NOT be affected.
**      Unknown permissions in known categories will be switched off.
**	Permissions not supplied in a checkbox will be switched off.
**	@param $policy_id (CGI) the policy to update
**	@param $descript (CGI) the new name of the policy
*/
function policy_update()
{
  global $policy_id;
  global $descript;
  global $priority;

  if(!is_root()) die_gracefully("Only root users can update policies");
  if(empty($policy_id)) die_gracefully("There must be a valid policy id");
  if(empty($descript)) die_gracefully("You cannot have an empty policy name");

  // $query has to be formed in stages, let's begin at the beginning as they say...
  $query = "UPDATE policy SET descript=" . make_null($descript) . 
    ", priority=$priority ";

  // Permissions are encoded as "cp<category>_<permission>", decode them now...
  foreach($_POST as $key => $value)
  {
    // Starts with cp?
    if(substr($key, 0, 2) == 'cp')
    {
      // Split it up into category and permission
      if(preg_match("/cp(.*)_(.*)/", $key, $matches))
      {
        // Form variable for category
        if(!empty($$matches[1])) $$matches[1] .= ",";
        $$matches[1] .= $matches[2];
      }
    }
  }

  // Get the known category list, so we can check each in turn as constructed above
  $policy_category = get_policy_categories();
  foreach($policy_category as $category)
  {
    $query .= ", $category='" . $$category . "' ";
    // Debug : echo "$category will be " . $$category . "<BR>\n";
  }
  $query .= "WHERE policy_id=$policy_id";
  // Debug : echo $query;

  // Finally let's try to run this query
  mysql_query($query)
    or print_mysql_error2("Unable to update policy", $query);

  // NOTE!! Logging performed outside this function because $log used as category :-(
  echo "<P ALIGN=\"CENTER\">Policy Updated</P>";
  policy_edit();  
}


/**
**	@function get_admin_type()
**	Determines the category of admin user (admin or root)
**	@param $admin_id the id of the admin to check
**	@return the admin type as a string "admin" or "root"
*/
function get_admin_type($admin_id)
{
  $query = "SELECT user FROM id WHERE id_number=$admin_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to obtain admin type", $query);
  $row = mysql_fetch_row($result);
  mysql_free_result($result);

  return($row[0]);
}


/**
**	@function admins_list
**	Lists all root and admin users on the system
*/
function admins_list()
{
  global $PHP_SELF;

  echo "<H2 ALIGN=\"CENTER\">Administrator Directory</H2>\n";
  print_wizard("Admins");

  echo "<H3 ALIGN=\"CENTER\">Root Users</H3>\n";
  $query = "SELECT admins.*, id.user, id.last_time FROM admins " .
           "LEFT JOIN id ON id.id_number = admins.user_id " .
           "WHERE id.user='root' ORDER BY surname";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to obtain root user list.", $query);

  output_help("AdminAdminRootList");  
  echo "<TABLE ALIGN=\"CENTER\" BORDER=\"1\">\n";
  echo "<TR><TH>Name</TH><TH>Position</TH><TH>Last Access</TH>" .
       "<TH>Options</TH></TR>\n";
  while($row = mysql_fetch_array($result))
  {
    echo "<TR><TD>" .
         htmlspecialchars($row["title"] . " " . $row["firstname"] .
                          " " . $row["surname"]) .
         "</TD><TD>" . htmlspecialchars($row["position"]) . "</TD>" .
         "</TD><TD>" . $row["last_time"] . "</TD>";

    echo "<TD><A HREF=\"$PHP_SELF?mode=BasicEdit&admin_id=" . $row["user_id"] .
         "\">Edit</A></TD>\n";
    echo "</TR>\n";
  }
  echo "</TABLE>\n";
  mysql_free_result($result);

  echo "<H3 ALIGN=\"CENTER\">Admin Users</H3>\n";
  $query = "SELECT admins.*, id.user, id.last_time FROM admins " .
           "LEFT JOIN id ON id.id_number = admins.user_id " .
           "WHERE id.user='admin' ORDER BY surname";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to obtain admin user list.", $query);
  
  output_help("AdminAdminAdminList");
  echo "<TABLE ALIGN=\"CENTER\" BORDER=\"1\">\n";
  echo "<TR><TH>Name</TH><TH>Position</TH><TH>Default Policy</TH>" .
       "<TH>Last Access</TH>" .
       "<TH>Options</TH></TR>\n";
  while($row = mysql_fetch_array($result))
  {
    echo "<TR><TD>" .
         htmlspecialchars($row["title"] . " " . $row["firstname"] .
                          " " . $row["surname"]) .
         "</TD><TD>" . htmlspecialchars($row["position"]) . "</TD>";
    echo "<TD>";

    if(!empty($row["policy_id"]))
    {
      echo "<A HREF=\"$PHP_SELF?mode=EditPolicy&policy_id=" . $row["policy_id"] . "\">" .
           htmlspecialchars(get_policy_name($row["policy_id"])) . "</A></TD>" .
           "</TD>";
    }
    else echo "No policy defined\n";

    echo "<TD>" . $row["last_time"] . "</TD>";

    echo "<TD><A HREF=\"$PHP_SELF?mode=BasicEdit&admin_id=" . $row["user_id"] .
         "\">Edit</A></TD>\n";
    echo "</TR>\n";
  }
  echo "</TABLE>\n";
  mysql_free_result($result);

  if(is_root())
  {
    echo "<H3 ALIGN=\"CENTER\">Make new Admin user</H3>\n";
    admin_basicform(NULL);
  }
}


/**
**	@function admin_edit
**	Allows an existing admin user to be viewed or edited.
**	@param (CGI) $admin_id The id of the admin to edit
*/
function admin_edit()
{
  global $PHP_SELF;
  global $admin_id;
  global $log;

  echo "<H2 ALIGN=\"CENTER\">Admin Editor</H2>\n";

  $query = "SELECT * FROM admins WHERE user_id=$admin_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch admin information", $query);
  $row = mysql_fetch_array($result);
  mysql_free_result($result);
  $fullname = $row["title"] . " " . $row["firstname"] . " " . $row["surname"];
  echo "<H3 ALIGN=\"CENTER\">" .
       htmlspecialchars($fullname) .
       "</H3>\n";
  print_wizard("Basics");

  // Form for basic details
  admin_basicform($row);

  echo "<HR>\n<H3 ALIGN=\"CENTER\">Activities</H3>\n";
  admin_activitiesform();

  if(is_root())
  {
    echo "<HR>\n";
    $admin_type = get_admin_type($admin_id);
    
    if($admin_type != 'root')
    {
      // Allow policy to be edited
      admin_policyform();

      // Allow promotion
      echo "<P ALIGN=\"CENTER\"><A HREF=\"$PHP_SELF?mode=AdminStatusUpdate&status=root&admin_id=$admin_id\">" .
           "Click here to promote this user to a super-admin (root) user</A></P>\n";
    }
    else
    {
      // Allow demotion
      echo "<P ALIGN=\"CENTER\"><A HREF=\"$PHP_SELF?mode=AdminStatusUpdate&status=admin&admin_id=$admin_id\">" .
           "Click here to demote this user to a normal admin user</A></P>\n";
    }

    // Link for new password
    echo "<P ALIGN=\"CENTER\"><A HREF=\"$PHP_SELF?mode=AdminNewPassword&admin_id=$admin_id\">" .
         "Click here to send this user a new password</A></P>\n";
  }

  $log['admin']->LogPrint("Details for admin user $fullname viewed for editing");
}


/**
**	@function admin_basicform
**	Provides the form for adding new admins or editing existing ones.
**	@param $row will either be empty for a new admin, or contain data for existing one
*/
function admin_basicform($row = NULL)
{
  global $PHP_SELF;
  global $admin_id;

  // Form beginning of FORM tag
  echo "<FORM METHOD=\"POST\" ACTION=\"$PHP_SELF";

  if(!empty($admin_id))
  {
    echo "?mode=AdminUpdate&admin_id=$admin_id";
  }
  else
  {
    echo "?mode=AdminAdd";
  }

  echo "\">\n";

  // Some defaults for new users - a sensible signature style for example...
  if(empty($row))
  {
    $row = array();
    $row["signature"] = "%atitle% %afirstname% %asurname%\n%aposition%\n%aaddress%\n";
  }
  
  // And now the table
  echo "<TABLE ALIGN=\"CENTER\">\n";

  echo "<TR><TH>Title</TH><TD>" .
       "<INPUT TYPE=\"TEXT\" NAME=\"title\" SIZE=\"10\" VALUE=\"" .
       htmlspecialchars($row["title"]) . "\"></TD></TR>\n";

  echo "<TR><TH>First name</TH><TD>" .
       "<INPUT TYPE=\"TEXT\" NAME=\"firstname\" SIZE=\"30\" VALUE=\"" .
       htmlspecialchars($row["firstname"]) . "\"></TD></TR>\n";

  echo "<TR><TH>Surname</TH><TD>" .
       "<INPUT TYPE=\"TEXT\" NAME=\"surname\" SIZE=\"30\" VALUE=\"" .
       htmlspecialchars($row["surname"]) . "\"></TD></TR>\n";

  echo "<TR><TH>Position</TH><TD>" .
       "<INPUT TYPE=\"TEXT\" NAME=\"position\" SIZE=\"30\" VALUE=\"" .
       htmlspecialchars($row["position"]) . "\"></TD></TR>\n";

  echo "<TR><TH>Phone</TH><TD>" .
       "<INPUT TYPE=\"TEXT\" NAME=\"voice\" SIZE=\"30\" VALUE=\"" .
       htmlspecialchars($row["voice"]) . "\"></TD></TR>\n";

  echo "<TR><TH>Fax</TH><TD>" .
       "<INPUT TYPE=\"TEXT\" NAME=\"fax\" SIZE=\"30\" VALUE=\"" .
       htmlspecialchars($row["fax"]) . "\"></TD></TR>\n";

  echo "<TR><TH>Address</TH><TD>" .
       "<TEXTAREA NAME=\"address\" ROWS=\"6\" COLS=\"50\">" . $row["address"] . "</TEXTAREA></TD></TR>\n";

  echo "<TR><TH>Email</TH><TD>" .
       "<INPUT TYPE=\"TEXT\" NAME=\"email\" SIZE=\"50\" VALUE=\"" .
       htmlspecialchars($row["email"]) . "\"></TD></TR>\n";

  echo "<TR><TH>Signature</TH><TD>" .
       "<TEXTAREA NAME=\"signature\" ROWS=\"6\" COLS=\"50\">" . $row["signature"] . "</TEXTAREA></TD></TR>\n";

  echo "<TR><TH>Staff No.</TH><TD>";
  if(is_root()) echo "<INPUT NAME=\"staffno\" VALUE=\"" . $row["staffno"] . "\" SIZE=\"10\">";
  else echo htmlspecialchars($row["staffno"]);
  echo "</TD></TR>\n";

  echo "<TR><TH>Options</TH><TD>";
  echo "<INPUT TYPE=\"CHECKBOX\" NAME=\"help\"";
  if(strstr($row["status"], 'help')) echo " CHECKED";
  echo"> Show in the help directory? </TD></TR>\n";
  if(empty($admin_id))
  {
    // user related fields
    echo "<TR><TH>Username</TH><TD>" .
         "<INPUT TYPE=\"TEXT\" NAME=\"username\" SIZE=\"10\" VALUE=\"auto\"></TD></TR>\n";

    echo "<TR><TH>Real Name</TH><TD>" .
         "<INPUT TYPE=\"TEXT\" NAME=\"realname\" SIZE=\"30\" VALUE=\"auto\"></TD></TR>\n";

    echo "<INPUT TYPE=\"HIDDEN\" NAME=\"password\" SIZE=\"10\" VALUE=\"auto\"></TD></TR>\n";
    echo "<INPUT TYPE=\"HIDDEN\" NAME=\"cpassword\" SIZE=\"10\" VALUE=\"auto\"></TD></TR>\n";
  }

  echo "<TR><TD COLSPAN=\"2\" ALIGN=\"CENTER\">";
  echo "<INPUT TYPE=\"submit\" NAME=\"button\" VALUE=\"Submit\">";
  echo "<INPUT TYPE=\"reset\" VALUE=\"Reset\"></TD></TR>\n";

  echo "</TABLE>\n";
  echo "</FORM>\n";
}


/**
**	@function admin_update
**	Updates information about an admin user using form data
*/
function admin_update()
{
  global $title, $firstname, $surname;
  global $position, $voice, $fax, $email;
  global $signature, $address, $staffno;
  global $admin_id, $help;
  global $status;

  if(empty($admin_id))
    die_gracefully("You cannot access this page without a admin id.");

  // Checkboxes
  if($help) $checkboxes = ", status='help'";
  else $checkboxes = ", status=''";

  // Form the query
  $query = "UPDATE admins SET" .
             "  title="     . make_null($title) .
             ", firstname=" . make_null($firstname) .
             ", surname="   . make_null($surname) .
             ", position="  . make_null($position) .
             ", voice="     . make_null($voice) .
             ", fax="       . make_null($fax) .
             ", email="     . make_null($email) .
             ", signature=" . make_null($signature) .
             ", address="   . make_null($address) . $checkboxes;

  // Only root users can edit the Staff number, this
  // is exceptionally important as it makes the link
  // to WebCT _without further authentication_ possible
  // and so could be a way to spoof identity
  if(is_root())
    $query .= ", staffno=" . make_null($staffno);
       
  $query .= " WHERE user_id=" . $admin_id;

  // Now try it
  mysql_query($query)
    or print_mysql_error2("Unable to update admin record.", $query);

  // Was there a status change request?
  if(!empty($status))
  {
    if(is_root())
    {
      if(($status != 'root') && ($status != 'admin'))
        die_gracefully("Invalid status setting");
      $query = "UPDATE id SET user='$status' WHERE id_number=$admin_id";
      mysql_query($query)
        or print_mysql_error2("Unable to update status.", $query);
      $log['admin']->LogPrint("admin account for $title $firstname $surname changed to $status");
      $log['security']->LogPrint("admin account for $title $firstname $surname changed to $status");
    }
    else
    {
      $log['security']->LogPrint("attempt to alter root level status by admin user");
      die_gracefully("You do not have permission to do this");
    }
  }

  echo "<P ALIGN=\"CENTER\">Admin record updated</P>\n";

  admin_edit();
}


/**
**	@function admin_add
**	Adds a new admin user to the system
*/
function admin_add()
{
  global $PHP_SELF;
  global $company_id;
  global $title, $firstname, $surname, $position;
  global $voice, $fax, $email;
  global $signature, $address, $staffno;
  global $username, $realname, $password;
  global $conf;

  if(!is_root()) die_gracefully("Only root users can add admin users");

  if(empty($surname) || empty($firstname) || empty($title))
    die_gracefully("The name fields must all be filled.");

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
           make_null(MD5($password)) . ", 'admin', NULL, NULL, 0)";

  mysql_query($query)
    or print_mysql_error2("Unable to create new user entry.", $query);

  // Fetch the user id just allocated.
  $user_id = mysql_insert_id();
           
  // Form contacts table query
  $query = "INSERT INTO admins (title, firstname, surname, position, voice, fax, " .
           "email, signature, staffno, address, user_id) VALUES(" .
           make_null($title) . ", " .
           make_null($firstname) . ", " .
           make_null($surname) . ", " .
           make_null($position) . ", " .
           make_null($voice) . ", " .
           make_null($fax) . ", " .
           make_null($email) . ", " .
           make_null($signature) . ", " .
           make_null($staffno) . ", " .
           make_null($address) . ", " .
           $user_id . ")";

  mysql_query($query)
    or print_mysql_error2("Unable to make new admins table entry.", $query);

  if(!empty($email))
  {
    user_notify_password($email, $title, $firstname, $surname, $username, $password, $user_id);
    echo "<P ALIGN=\"CENTER\">The user has been emailed a username and password.</P>\n";
  }
  else{
    echo "<P ALIGN=\"CENTER\">No email address is listed for this user " .
         "and so it is impossible to send them the new credentials.<BR>" .
         "They have been allocated as follows.<BR>" .
         "<TABLE>\n<TR><TD>Username</TD><TD>" . $username . "</TD></TR>\n" .
         "<TR><TD>Password</TD><TD>" . $password . "</TD></TR>\n</TABLE>\n";
  }
  admins_list();
}


/*
**	admin_newpassword
**
** Automatically generates a new password for the admin and emails
** it if possible. Otherwise it displays it on screen.
*/
function admin_newpassword()
{
  global $PHP_SELF;
  global $log;
  global $admin_id;

  if(!is_root())
     die_gracefully("You do not have permission to update passwords");

  // Fetch admins information
  $query = "SELECT * FROM admins WHERE user_id=$admin_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch admin information", $query);
  $row = mysql_fetch_array($result);
  mysql_free_result;

  // Fetch matching user information
  $user_query = "SELECT * FROM id WHERE (user='admin' OR user='root') AND id_number=$admin_id";
  $user_result = mysql_query($user_query)
    or print_mysql_error2("Unable to obtain user data.", $user_query);
  $user_row = mysql_fetch_array($user_result);

  // Generate a new password
  $password = user_make_password();

  // Put the new password in the database
  $new_query = "UPDATE id SET password=MD5('$password') WHERE id_number=$admin_id";
  mysql_query($new_query) or print_mysql_error2("Unable to update password.", $new_query);

  if(!empty($row["email"]))
  {
    user_notify_password($row["email"], $row["title"], $row["firstname"], $row["surname"],
                         $user_row["username"], $password, $row["user_id"]);
    echo "<P ALIGN=\"CENTER\">The user has been emailed a username and password.</P>\n";
  }
  else{
    echo "<P ALIGN=\"CENTER\">No email address is listed for this user " .
         "and so it is impossible to send them the new credentials.<BR>" .
         "They have been allocated as follows.<BR>" .
         "<TABLE>\n<TR><TD>Username</TD><TD>" . $user_row["username"] . "</TD></TR>\n" .
         "<TR><TD>Password</TD><TD>" . $password . "</TD></TR>\n</TABLE>\n";
  }
  admin_edit();
}


/**
**	@function admin_policyform
**	Provides a form for changing an admin's default policy
**	@param $admin_id (CGI) the admin to examine
*/
function admin_policyform()
{
  global $PHP_SELF;
  global $admin_id;

  if(!is_root()) die_gracefully("Only root users may change policies");
  if(empty($admin_id)) die_gracefully("There must be a valid admin id");

  $query = "SELECT * FROM admins WHERE user_id=$admin_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch admin information", $query);
  $row = mysql_fetch_array($result);
  mysql_free_result($result);

  $fullname = $row["title"] . " " . $row["firstname"] . " " . $row["surname"];
  // Now the form
  echo "<H3 ALIGN=\"CENTER\">Alter Policy For<BR>" .
       htmlspecialchars($fullname) . "</H3>\n";

  echo "<FORM METHOD=\"POST\" ACTION=\"$PHP_SELF\">\n";
  echo "<INPUT TYPE=\"HIDDEN\" NAME=\"mode\" VALUE=\"AdminPolicyUpdate\">\n";
  echo "<INPUT TYPE=\"HIDDEN\" NAME=\"admin_id\" VALUE=\"$admin_id\">\n";
  echo "<INPUT TYPE=\"HIDDEN\" NAME=\"opolicy_id\" VALUE=\"" . $row["policy_id"] . "\">\n";
  echo "<TABLE ALIGN=\"CENTER\" BORDER=\"0\">\n";
  echo "<TR><TH>Current Policy</TH><TD>";
  if(empty($row["policy_id"])) echo "No policy defined";
  else echo htmlspecialchars(get_policy_name($row["policy_id"]));
  echo "</TD></TR>\n";
  echo "<TR><TH>New Policy</TH><TD>" .
       "<SELECT NAME=\"policy_id\">\n";
  
  $query = "SELECT descript, policy_id FROM policy ORDER BY descript";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to check policy names", $query);
  echo "<OPTION VALUE=\"0\"";
  if(empty($row["policy_id"])) echo " SELECTED";
  echo ">No Policy</OPTION>\n";
  while($prow = mysql_fetch_array($result))
  {
    echo "<OPTION VALUE=\"" . $prow["policy_id"] . "\"";
    if($row["policy_id"]==$prow["policy_id"]) echo " SELECTED";
    echo ">" . htmlspecialchars($prow["descript"]) ."</OPTION>\n";
  }
  echo "<TR><TD ALIGN=\"CENTER\" COLSPAN=\"2\">" .
       "<INPUT TYPE=\"SUBMIT\" VALUE=\"Change Policy\"></TD></TR>\n";
  echo "</TABLE>\n";
  echo "</FORM>\n";

}


/**
**	@function admin_policyupdate
**	Used to update the default policy (really for admin users)
**	@param $admin_id (CGI) the id of the admin user to change
**	@param $opolicy_id (CGI) the old policy of the admin user
**	@param $policy_id (CGI) the new policy of the admin user
**	@param $confirm (CGI) required to be one to really cause the change
*/
function admin_policyupdate()
{
  global $PHP_SELF;
  global $admin_id;
  global $opolicy_id;
  global $policy_id;
  global $confirm;
  global $log;
 
  if(!is_root()) die_gracefully("You do not have permission to update policies");

  if($confirm==1)
  {
    if(empty($policy_id))
    {
      $query = "UPDATE admins SET policy_id=NULL WHERE user_id=$admin_id";
    }
    else
    {   
      $query = "UPDATE admins SET policy_id=$policy_id WHERE user_id=$admin_id";
    }
    mysql_query($query)
      or print_mysql_error2("Unable to change admin policy", $query);

    $log['admin']->LogPrint("policy changed for " . get_user_name($admin_id) . 
                            " to " . get_policy_name($policy_id));

    $log['security']->LogPrint("policy changed for " . get_user_name($admin_id) . 
                            " to " . get_policy_name($policy_id));
    admin_edit();
  }
  else
  {
    echo "<H2 ALIGN=\"CENTER\">Are you sure?</H2>\n";
    echo "<P ALIGN=\"CENTER\">You have elected to change the policy for " .
         get_user_name($admin_id) . " from " . get_policy_name($opolicy_id) . 
         " to " . get_policy_name($policy_id) . ". You should be sure you " .
         "understand the consequences of this action.</P>";

    echo "<P ALIGN=\"CENTER\"><A HREF=\"$PHP_SELF?mode=AdminPolicyUpdate" .
         "&admin_id=$admin_id&opolicy_id=$opolicy_id&policy_id=$policy_id&confirm=1\">" .
         "Click here to confirm the changes</A></P>";
    echo "<P ALIGN=\"CENTER\"><A HREF=\"$PHP_SELF?mode=BasicEdit" .
         "&admin_id=$admin_id\">" .
         "Click here to return to the admin editor</A></P>";
  }
}


/**
**	@function admin_activitiesform
**
*/
function admin_activitiesform()
{
  global $PHP_SELF;
  global $admin_id;

  if(empty($admin_id)) die_gracefully("An admin id must be defined");
  
  $query = "SELECT vacancytype.* FROM adminactivity " .
           "LEFT JOIN vacancytype ON adminactivity.activity_id=" .
           "vacancytype.vacancy_id WHERE admin_id=$admin_id ORDER BY name";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to get activities for admin", $query);
  
  if(!mysql_num_rows($result))
  {
    echo "<P ALIGN=\"CENTER\">None defined yet</P>\n";
  }
  else
  {
    echo "<TABLE ALIGN=\"CENTER\" BORDER=\"0\">\n";
    while($row = mysql_fetch_array($result))
    {
      echo "<TR><TD>" . htmlspecialchars($row["name"]) . "</TD>";
      if(is_root())
      {
        echo "<TD><A HREF=\"$PHP_SELF?mode=AdminActivityDelete" .
             "&admin_id=$admin_id&activity_id=" . $row["vacancy_id"] .
             "\">Delete</A></TD>";
      }
      echo "</TR>\n";
    }
    echo "</TABLE>\n";
  }
  mysql_free_result($result);

  if(is_root())
  {
    // Offer the chance to do more
    $query = "SELECT * FROM vacancytype ORDER by name";
    $result = mysql_query($query)
      or print_mysql_error2("Unable to obtain activity list", $query);
    echo "<P ALIGN=\"CENTER\">\n";
    echo "<FORM METHOD=\"POST\" ACTION=\"$PHP_SELF\">\n";
    echo "<INPUT TYPE=\"HIDDEN\" NAME=\"mode\" VALUE=\"AdminActivityAdd\">\n";
    echo "<INPUT TYPE=\"HIDDEN\" NAME=\"admin_id\" VALUE=\"$admin_id\">\n";
    echo "<SELECT NAME=\"activity_id\">";
    while($row = mysql_fetch_array($result))
    {
      echo "<OPTION VALUE=\"" . $row["vacancy_id"] . "\">" .
           htmlspecialchars($row["name"]) . "</OPTION>\n";
    }
    echo "</SELECT> <INPUT TYPE=\"SUBMIT\" VALUE=\"Add Activity\"></FORM></P>\n";
  }
}


function admin_addactivity()
{
  global $admin_id;
  global $activity_id;

  if(!is_root()) die_gracefully("You do not have permission to alter activities");

  // Check's it's not there
  $query = "SELECT * FROM adminactivity WHERE admin_id=$admin_id " .
           "AND activity_id=$activity_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to check current activity", $query);

  if(!mysql_num_rows($result))
  {
    // So let's add it
    mysql_free_result($result);
    $query = "INSERT INTO adminactivity VALUES($admin_id, $activity_id)";
    mysql_query($query)
      or print_mysql_error2("Unable to add activity", $query);
  }
  admin_edit();
}


function admin_deleteactivity()
{
  global $admin_id;
  global $activity_id;

  if(!is_root()) die_gracefully("You do not have permission to alter activities");

  $query = "DELETE FROM adminactivity WHERE admin_id=$admin_id " .
           "AND activity_id=$activity_id";
  mysql_query($query)
    or print_mysql_error2("Unable to delete activity", $query);

  admin_edit();
}


/**
**	@function admin_statusupdate
**	Used to change a user from an admin to a root or vice versa
**	@param $admin_id (CGI) the id of the admin user to change
**	@param $status (CGI) the new status for the user
**	@param $confirm (CGI) required to be one to really cause the change
*/
function admin_statusupdate()
{
  global $PHP_SELF;
  global $admin_id;
  global $status;
  global $confirm;
  global $log;
 
  if(!is_root()) die_gracefully("You do not have permission to update policies");
  if($status != 'admin' && $status != 'root') die_gracefully("Invalid status");

  if($confirm==1)
  {
    $query = "UPDATE id SET user='$status' WHERE id_number=$admin_id";
    mysql_query($query)
      or print_mysql_error2("Unable to change admin status", $query);

    $log['admin']->LogPrint(get_user_name($admin_id) . " altered to $status");
    $log['security']->LogPrint(get_user_name($admin_id) . " altered to $status");
    admin_edit();
  }
  else
  {
    echo "<H2 ALIGN=\"CENTER\">Are you sure?</H2>\n";
    echo "<P ALIGN=\"CENTER\">You have elected to change the status for " .
         get_user_name($admin_id) . " to $status. You should be sure you " .
         "understand the consequences of this action.</P>";

    echo "<P ALIGN=\"CENTER\"><A HREF=\"$PHP_SELF?mode=AdminStatusUpdate" .
         "&admin_id=$admin_id&status=$status&confirm=1\">" .
         "Click here to confirm the changes</A></P>";
    echo "<P ALIGN=\"CENTER\"><A HREF=\"$PHP_SELF?mode=BasicEdit" .
         "&admin_id=$admin_id\">" .
         "Click here to return to the admin editor</A></P>";
  }
}


/**
**	@function admin_delete
**	Deletes an admin user and associated lines in the database
**	@param $admin_id the user to delete
**	@param $confirm must be 1 to ensure the deletion
*/
function admin_delete()
{
  global $PHP_SELF;
  global $log;
  global $confirm;
  global $admin_id;

  if(!is_root()) die_gracefully("You do not have permission to access this page.");

  // The admin user name
  $username = get_user_name($admin_id);

  if($confirm==1){
      // Root and branch delete
      // Delete all links to companies
      $query = "DELETE FROM id WHERE id_number=$admin_id";
      mysql_query($query)
        or print_mysql_error2("Unable to delete id record", $query);

      $query = "DELETE FROM admins WHERE user_id=$admin_id";
      mysql_query($query)
        or print_mysql_error2("Unable to delete admin record", $query);

      $query = "DELETE FROM adminschool WHERE admin_id=$admin_id";
      mysql_query($query)
        or print_mysql_error2("Unable to delete admin school links", $query);

      $query = "DELETE FROM admincourse WHERE admin_id=$admin_id";
      mysql_query($query)
        or print_mysql_error2("Unable to delete admin course links", $query);

      $log['admin']->LogPrint("admin " . $username . " was totally removed " .
                              "from the system.");
      echo "<P ALIGN=\"CENTER\">Admin " . htmlspecialchars($username) .
           " totally removed from system.</P>";
      list_admins();
 
  }
  else
  {

    echo "<H2 ALIGN=\"CENTER\">Are you sure?</H2>\n";
    echo "<P ALIGN=\"CENTER\">You have started the process to delete an admin " .
         htmlspecialchars($username). ".</P>";

    echo "<P ALIGN=\"CENTER\"><A HREF=\"$PHP_SELF?mode=AdminDelete&admin_id=$admin_id" .
         "&confirm=1\">Click here to confirm delete</A></P>\n";

    echo "<P ALIGN=\"CENTER\"><A HREF=\"$PHP_SELF?mode=ListAdmins" .
         "\">Click here to go to admin listing</A></P>\n";
  }
}


function print_wizard($item)
{
  global $conf;
  global $policy_id;
  global $admin_id;
  global $smarty;

  $wizard = new TabbedContainer($smarty, "tabs");

  $wizard->addTab("Admins", $_SERVER['PHP_SELF'] . "?mode=ListAdmins");
  if(!empty($policy_id))
  {
    $wizard->addTab('Policies', $SERVER['PHP_SELF'] . "?mode=EditPolicy&policy_id=$policy_id");
  }
  else
  {
    $wizard->addTab('Policies', $SERVER['PHP_SELF'] . "?mode=ListPolicy");
  }

  if(!empty($admin_id))
  {
    $wizard->addTab('Basics', $SERVER['PHP_SELF'] . "?mode=BasicEdit&admin_id=$admin_id");
  }

  // Transitionary code
  echo "<div name=\"tabbedContainer\" align=\"center\">\n";
  $wizard->displayTab($item);
  echo "</div>\n";
}

?>



