<?php

/**
**
**
*/

/**
**	@function get_assessment_from_cassessment
**  Look up the specific assessment information for a regime item.
*/
function get_assessment_from_cassessment($cassessment_id)
{
  $query = "SELECT assessment_id FROM assessmentregime " .
           "WHERE cassessment_id=$cassessment_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to obtain assessment id.", $query);
  $row = mysql_fetch_row($result);
  mysql_free_result($result);
  return($row[0]);
}

/**
**	@function obtain_required_variables
**	Fetches the list of variables needed for an assessment.
**	@param $assessment_id The id of the assessment in the database
**			      which we are fetching information for.
**	@return An associative array from assessmentstructure. The
**              key item is the variable name.
*/
function obtain_required_variables($assessment_id)
{
  $query = "SELECT * FROM assessmentstructure WHERE " .
           "assessment_id = $assessment_id ORDER BY varorder";

  $result = mysql_query($query)
    or print_mysql_error2("Could not obtain assessment structure.", $query);

  while($row = mysql_fetch_array($result))
  {
    $structure[$row['name']] = $row;
  }
  mysql_free_result($result);
  return($structure);
}


/**
**	@function check_required_variables
**	Performs validation of all variables based on an assessment structure.
**	@param $structure The assessment structure returned by obtain_required_variables
**	@return An error string that will be empty if there are no errors.
**
*/
function check_required_variables($structure)
{
  global $log;	// Access to logging
  $error = "";

  foreach($structure as $item)
  {
    $name  = $item['name'];
    $human = $item['section'];
    $value = $_POST[$name];
    if(!isset($_POST[$name]))
    {
      if($item['type'] == 'checkbox') continue;
      // A missing variable is serious, possibly even a security breach
      $error .= "! Missing variable " . htmlspecialchars($item['name']) .
                ", report to Webmaster<BR>\n";
      $log['security']->LogPrint("missing variable in script");
    }
    else
    {
      // The variable is inbound, we must validate it
      $error .= validate_variable($item, $value);
    }
  }
  return($error); 
}


function flag_for_attention($structure, $name)
{

  foreach($structure as $item)
  {
    if($item['name'] == $name)
    {
       if(validate_variable($item, $_POST[$name])) echo "<span class=\"error\">**</span>";
    }
  }
}
  

/**
**	@function validate_variable
**	Checks an individual assessment variable for validity.
**	@param $item is a row from the assessmentstructure table for this data
**	@param $value is the inbound value for this item
**	@return An error string that will be empty if there are no errors.
*/
function validate_variable($item, $value)
{
  $error = "";
  if($item['type']=='assesseddate')
  {
    $date = parse_date($value);
    if(!checkdate($date['month'], $date['day'], $date['year']))
    {
      $error .= htmlspecialchars($item['human']) . " is invalid.<BR>\n";
    }
  }
  $error .= validate_variable_minimum($item, $value);
  $error .= validate_variable_maximum($item, $value);
  $error .= validate_variable_options($item, $value);

  return($error);
}


/**
**	checks an inbound assessment variable against options.
**	@param item is a row from assessmentstructure
**	@param value is the value entered by the user
**	@returns any error that occurred, or an empty variable
*/
function validate_variable_options($item, $value)
{
  $error = "";
  if(strstr($item['options'], "compulsory"))
  {
    if($value != "0")
    {
      if(empty($value) || $value="")
      {
        $error = htmlspecialchars($item['human']) .
                 " cannot by empty<BR>\n";
      }
    }
  }
  return($error);
}


/**
**	checks an inbound assessment variable against a minimum.
**	@param item is a row from assessmentstructure
**	@param value is the value entered by the user
**	@returns any error that occurred, or an empty variable
*/
function validate_variable_minimum($item, $value)
{
  $error = "";
  if(!empty($item['min']))
  {
    if($item['type'] == 'textual')
    {
      if(strlen($value) < $item['min'])
      {
        $error = htmlspecialchars($item['human']) .
                 " must have a length greater than " . $item['min'] . "<BR>\n";
      }
    }
    if($item['type'] == 'numeric')
    {
      if($value < $item['min'])
      {
        $error = htmlspecialchars($item['human']) .
                 " cannot have a value less than " . $item['min'] . "<BR>\n";
      }
    }
  }
  return($error);
}


/**
**	checks an inbound assessment variable against a maximum.
**	@param item is a row from assessmentstructure
**	@param value is the value entered by the user
**	@returns any error that occurred, or an empty variable
*/
function validate_variable_maximum($item, $value)
{
  $error = "";
  if(!empty($item['max']))
  {
    if($item['type'] == 'textual')
    {
      if(strlen($value) > $item['max'])
      {
        $error = htmlspecialchars($item['human']) .
                 " must have a length less than " . $item['max'] . "<BR>\n";
      }
    }
    if($item['type'] == 'numeric')
    {
      if($value > $item['max'])
      {
        $error = htmlspecialchars($item['human']) .
                 " cannot have a value more than " . $item['max'] . "<BR>\n";
      }
    }
  }
  return($error);
}


/**
**	@function insert_assessment
**	Takes all inbound CGI data for an assessment and attempts to parse and insert it.
**	This function will perform the validation also, and will call display_assessment_form()
**	again if there are problems. This function shouls be independently written for the
**	specific assessment.
**	@param $cassessment_id is the id for the assessmentregime table
**	@param $assessed_id is the id of the user being assessed
**	@param $assessor_id is the id of the user performing the assessment
*/
function insert_assessment($cassessment_id, $assessed_id, $assessor_id)
{
  global $user;
  global $log;

  // Get the specific assessment_id
  $assessment_id = get_assessment_from_cassessment($cassessment_id);

  // Keep a track of errors 
  $error = "";

  // Obtain the assessment structure
  $structure = obtain_required_variables($assessment_id);

  // Now compare with the inputs from the user
  $error = check_required_variables($structure);

  if(strlen($error))
  {
    // This call is to a function external to this file
    // which will vary from assessment to assessment
   
    echo "<DIV CLASS=\"error\"><H3>Errors Occurred</H3>\n";
    echo "<P>See the comments at the bottom of the form for more details.</P></DIV>\n";
    display_assessment_form();

       
    echo "<DIV CLASS=\"error\"><H3>Errors Occurred</H3>\n";
    echo "<P>Your submission could not be accepted, due to one " .
         "or more errors. Please review the list below and try once " .
         "again.<BR>" . $error . "</P></DIV>\n";
    die_gracefully("Correct the form as directed above.");
  }

  $assesseddate = date("YmdHis");
  // We're still here? Start to insert variables
  foreach($structure as $item)
  {
    if($item['type']=='assesseddate')
    {
      // assessed dates are special, and stored in totals, not results
      $assesseddate = make_datetime(parse_date($_POST[$item['name']]));
    }
    else
    {
      $query = "INSERT INTO assessmentresults VALUES(" .
               $cassessment_id . ", " .
               $assessed_id . ", " .
               make_null($item['name']) . ", " .
               ($_POST[$item['name']] == "0" ? "0" : make_null($_POST[$item['name']])) . ")";
      mysql_query($query)
        or print_mysql_error2("Unable to insert result.", $query);
    }
  }

  //$log['admin']->LogPrint("
  // We should also update the totals table
  update_totals($cassessment_id, $assessed_id, $assessor_id, $structure, $assesseddate);             
}

function parse_date($textdate)
{
  $parts = explode("/", $textdate);
  $date['day'] = $parts[0];
  $date['month'] = $parts[1];
  $date['year'] = $parts[2];

  return($date);
}

function make_datetime($date)
{
  $datetime = sprintf("%04s%02s%02s120000", $date['year'], $date['month'], $date['day']);
  return($datetime);
}

function update_assessment()
{
}

function update_totals($cassessment_id, $assessed_id, $assessor_id, $structure, $assesseddate)
{
  global $log;

  // Form total mark
  $total = 0;
  $max   = 0;

  foreach($structure as $item)
  {
    if($item['weighting'] != 0)
    {
      if($_POST[$item['name']] != "")
      {
        $total += ($_POST[$item['name']] * $item['weighting']);
        $max   += ($item['max'] * $item['weighting']);
      }
    }
  }
  if($max)
  {
    $percentage = (($total * 100.0) / $max);
  }
  else $percentage = 'NULL';

  $present = check_for_assessment($cassessment_id, $assessed_id, $assessor_id);
  $now = date("YmdHis");

  if(!$present)
  {
    $query = "INSERT INTO assessmenttotals VALUES(" .
             "$cassessment_id, $assessed_id, $assessor_id, '', " .
             "$total, $max, $percentage, $now, NULL, $assesseddate)";
    mysql_query($query)
      or print_mysql_error2("Unable to insert new assessment total.");
    $log['admin']->LogPrint("Assessment $cassessment_id, assessed $assessed_id, assessor $assessor_id, percentage $percentage");
  }
  else
  {
    die_gracefully("Updates not currently supported");
  }
}


function get_assessment_percentage($cassessment_id, $assessed_id)
{
  $query = "SELECT percentage FROM assessmenttotals WHERE " .
           "cassessment_id=$cassessment_id AND " .
           "assessed_id=$assessed_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to check for current assessment total.", $query);
 
  $row = mysql_fetch_row($result);
  mysql_free_result($result);
 
  return($row[0]);
} 

function check_for_assessment($cassessment_id, $assessed_id)
{
 $query = "SELECT * FROM assessmenttotals WHERE " .
           "cassessment_id=$cassessment_id AND " .
           "assessed_id=$assessed_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to check for current assessment total.", $query);
 
 
  $present = mysql_num_rows($result);
  mysql_free_result($result);
  //echo "<H2>$present</H2>";
  return($present);
}

function obtain_submitted_variables($result)
{
  $submitted = array();
  while($row = mysql_fetch_array($result))
  {
    $submitted[$row['name']] = $row['contents'];
  }
  return($submitted);
}

function assessment_breakdown($cassessment_id, $assessed_id)
{
  $query = "SELECT student_description FROM assessmentregime " .
           "WHERE cassessment_id=$cassessment_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to obtain assessment name.", $query);
  $row = mysql_fetch_row($result);
  $assessment_title = $row[0];
  mysql_free_result($result);

  // Fetch the totals
  $query = "SELECT * FROM assessmenttotals WHERE " .
           "assessed_id=$assessed_id AND cassessment_id=$cassessment_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to obtain assessment total.", $query);
  if(!mysql_num_rows($result))
  {
    echo "<H3 ALIGN=\"CENTER\">No Results</H3>";
    echo "<P>Sorry, there are no results as yet.</P>";
    return;
  }
  $total_row = mysql_fetch_array($result);
  mysql_free_result($result);

  // And now the breakdown
  $query = "SELECT * FROM assessmentresults WHERE " .
           "assessed_id=$assessed_id AND cassessment_id=$cassessment_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to obtain assessment breakdown.", $query);
  $submitted = obtain_submitted_variables($result);
  
  // Get the assessment structure
  $assessment_id = get_assessment_from_cassessment($cassessment_id);
  $structure = obtain_required_variables($assessment_id);

  echo "<H2 ALIGN=\"CENTER\">Assessment Breakdown<BR>" .
       htmlspecialchars($assessment_title) . "</H2>\n";
  echo "<H3 ALIGN=\"CENTER\">For " .
       htmlspecialchars(get_user_name($assessed_id)) . "</H3>\n";
  echo "<H3 ALIGN=\"CENTER\">Assessed by " .
       htmlspecialchars(get_user_name($total_row["assessor_id"])) . "</H3>\n";

  echo "<TABLE BORDER=\"1\">";
  echo "<TR><TH>Category</TH><TH>Details</TH><TH>Mark / Max</TH><TH>Multiplier</TH></TR>";
  foreach($structure as $item)
  {
    $contents = $submitted[$item["name"]];
    echo "<TR>";
    echo "<TD>" . htmlspecialchars($item["human"]) . "</TD>\n";

    echo "<TD>";
    switch($item["type"])
    {
      case "numeric" :
        echo "N/A";
        break;
      case "checkbox" :
        if($contents=="on") echo "Yes";
        else echo "No";
        break;
      default :
        echo htmlspecialchars($contents);
        break;
    }
    echo "</TD>";

    // Non zero weighting
    if(($item["type"]=="numeric") && $item["weighting"])
    {
      echo "<TD>" . ($contents == NULL ? "--" : $contents) . " / " .$item["max"] . "</TD>" .
           "<TD>" . $item["weighting"] . "</TD>\n";
    }
    else
    {
      echo "<TD>--</TD><TD>--</TD>\n";
    }
    echo "</TR>\n";
  }
  echo "</TABLE>\n";

  echo "<P><B>Summary</B> Mark is " . $total_row["mark"] . " out of " .
       $total_row["outof"] . " (" . $total_row["percentage"] . "%)";
  mysql_free_result($result);
}


function assessment_show_other_assessors($student_id)
{
  global $conf;
  global $showaliens;

  $course_id = get_course_id($student_id);
  $school_id = get_school_id($course_id);
  // Determine the students assessmentgroup
  $assessmentgroup = get_student_assessmentgroup($student_id);

  $query = "SELECT * FROM assessmentregime WHERE " .
           "group_id=$assessmentgroup ORDER BY start, end";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch course assessment regime", $query);
  if(!mysql_num_rows($result)) return;

  echo "<H3 ALIGN=\"CENTER\">Other assessors</H3>\n";
  echo "<TABLE ALIGN=\"CENTER\">\n";
  while($row = mysql_fetch_array($result))
  {
    if($row['assessor'] == "other")
		{
      echo "<FORM METHOD=\"POST\" ACTION=\"" .
	$conf['scripts']['admin']['studentdir'] . 
        "?mode=StudentSetAssessor&assessed_id=$student_id" .
        "&cassessment_id=" . $row["cassessment_id"] . "\">\n";

      echo "<TR>\n";
      echo "<TD>" . htmlspecialchars(get_cassessment_description($row["cassessment_id"])) . "</TD><TD>";
      // get any existing assessor
      $assessor_id = assessment_get_other_assessor($student_id, $row["cassessment_id"]);

      // Provide a list
      $sub_query = "SELECT surname, firstname, title, user_id FROM staff ";
      if(!$showaliens) $sub_query .= " WHERE school_id=$school_id ";
      $sub_query .= "ORDER BY surname, firstname";
      $sub_result = mysql_query($sub_query) or
        print_mysql_error2("Unable to fetch staff details.", $tutor_query);
     
      echo "<SELECT NAME=\"assessor_id$cassessment_id\">\n";
      echo "<OPTION VALUE=\"0\">No assessor selected</OPTION>\n";
      while($sub_row=mysql_fetch_array($sub_result)){
        echo "<OPTION";
        if($sub_row["user_id"] == $assessor_id) echo " SELECTED";
        echo " VALUE=\"" . $sub_row["user_id"] . "\">" .
            htmlspecialchars($sub_row["surname"] . ", " . $sub_row["firstname"] . ", " . $sub_row["title"]) .
            "</OPTION>\n";
     }
     mysql_free_result($sub_result);
     echo "</SELECT>\n</TD>";
     echo "<TD><INPUT TYPE=\"submit\" NAME=\"button\" VALUE=\"Submit Changes\"></TD>";

     echo "</TR></FORM>";
		}
  }  
  echo "</TABLE>\n\n";
}


function assessment_get_other_assessor($assessed_id, $cassessment_id)
{
  $query = "SELECT assessor_id FROM assessorother WHERE " .
           "assessed_id=$assessed_id AND cassessment_id=$cassessment_id";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch assessor information", $query);

  if(!mysql_num_rows($result)) return FALSE;
  $row = mysql_fetch_row($result);
  mysql_free_result($result);
  return($row[0]);
}

function assessment_date_compare($regime_item1, $regime_item2)
{
  // -1 is item 1 earlier, 1 otherwise
  global $conf;
  $year1 = $regime_item1['year'];
  $year2 = $regime_item2['year'];

  $end1 = $regime_item1['end'];
  $end2 = $regime_item2['end'];

  // Calculate how far this is from the start of the academic
  // year (using a crude estimate in days).
  if($end1 == 0) return -1;
  if($end2 == 0) return 1;

  // Dates are MMDD
  // e.g. 1001 first visit, 0131 final visit

  if($end1 < $conf['prefs']['yearstart']) $year1++;
  if($end2 < $conf['prefs']['yearstart']) $year2++;

  //  echo "debug: year1, $year1, year2, $year2";
  if($year2 > $year1) return -1;
  if($year1 < $year2) return 1;
  return($end1 - $end2);
}

function assessment_regime($student_id)
{
  global $smarty;
  global $conf;

  // This will store the items
  $regime_items = array();

  // Determine the students assessmentgroup
  $assessmentgroup = get_student_assessmentgroup($student_id);

  $query = "SELECT * FROM assessmentregime WHERE " .
    "group_id=$assessmentgroup ORDER BY start, end";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch course assessment regime", $query);

  $weighting_total = 0;
  $aggregate_total = 0;

  while($row = mysql_fetch_array($result))
  {
    if(is_supervisor() && $row["assessor"]!="industrial") continue;
    if(strstr($row['options'], "archive")) continue;
    if(is_student() && strstr($row['options'], "hidden")) continue;

    // Collect the weight as we go...
    $weighting_total += $row["weighting"];

    // Try to determine the assessor
    $assessor = "Unknown";
    if($row['assessor']=="student") $assessor = "Self Assessment";
    if($row['assessor']=="academic") $assessor = "Academic Tutor";
    if($row['assessor']=="industrial") $assessor = "Industrial Supervisor";
      
    if($assessor == "Unknown")
    {
      $otherassessor = assessment_get_other_assessor($student_id, $row["cassessment_id"]);
      if($otherassessor != FALSE) $assessor = get_user_name($otherassessor);
    }

    $row['assessor'] = $assessor;

    $sub_query = "SELECT * FROM assessment WHERE assessment_id=" .
      get_assessment_from_cassessment($row["cassessment_id"]);
    $sub_result = mysql_query($sub_query)
      or print_mysql_error2("Unable to fetch assessment URL", $sub_query);
    $sub_row = mysql_fetch_array($sub_result);
    mysql_free_result($sub_result);

    $row['template_filename'] = $sub_row['template_filename'];
/*    if($sub_row['t'])
    {
      $row['submission_url'] = $conf['paths']['assessment'] . $sub_row['submission_url']
	. '?cassessment_id=' . $row['cassessment_id'] . "&assessed_id=$student_id";
    }
*/
    // Get the results if possible
    $percentage = get_assessment_percentage($row["cassessment_id"], $student_id);
    if(empty($percentage)){
      if(check_for_assessment($row["cassessment_id"], $student_id))
      {
	$percentage = "0%";
	$aggregate  = "0%";
      }
      else
      {
	$percentage = "--";
	$aggregate = "--";
      }
    }
    else
    {
      $percentage = sprintf("%.02f", $percentage);
      $aggregate = sprintf("%.02f", $percentage * $row['weighting']);
      $aggregate_total += $aggregate;
      $percentage .= "%";
      $aggregate .= "%";
    }
    // Add them to the array
    $row['percentage'] = $percentage;
    $row['aggregate'] = $aggregate;

    // Add the whole array to the array of results
    array_push($regime_items, $row);
  }

  // Custom sort
  usort($regime_items, "assessment_date_compare");

  $smarty->assign("student_id", $student_id);
  $smarty->assign("regime_items", $regime_items);
  $smarty->assign("aggregate_total", $aggregate_total);
  $smarty->assign("weighting_total", $weighting_total);
  $smarty->display("assessment/assessment_results.tpl");
}

?>