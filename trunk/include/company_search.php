<?php

/**
**	@function company_lastlogin
**      This obtains the last time that a given company was logged in by any appropriate contact.
**	@param $company_id The selected company
**	@return The date time string from the MySQL database
*/
function company_lastlogin($company_id)
{
  $query = "SELECT MAX(last_time) FROM id, companycontact, contacts WHERE " .
           "companycontact.contact_id = contacts.contact_id AND " .
           "contacts.user_id = id.id_number AND " .
           "companycontact.company_id = $company_id";
  $result = mysql_query($query)
    or print_mysql_error2("Can't get last access time", $query);

  if(!mysql_num_rows($result)) $last_time = NULL;
  else{
    $row = mysql_fetch_row($result);
    $last_time = $row[0];
  }
  mysql_free_result($result);
  
  return($last_time);
}


/**
**	@function company_displayform()
**	The form to initiate a search on the company database
*/
function company_displayform($edit)
{
  global $PHP_SELF;
  global $student_id;

  if($edit)
  {
    echo "<H2 ALIGN=\"CENTER\">Company / Vacancy Directory</H2>\n";
  }
  else
  {
    echo "<H2 ALIGN=\"CENTER\">Company / Vacancy Browser</H2>\n";
  }

  echo "<FORM METHOD=\"POST\" ACTION=\"" . $PHP_SELF . "\">\n";

  echo "<TABLE ALIGN=\"CENTER\">\n";

  echo "<TR><th ALIGN=\"CENTER\" COLSPAN=2><B>Search criteria</B></th></TR>\n";
  echo "<TR><th>Keyword (if any)</th>\n";
  echo "<TD><INPUT TYPE=\"TEXT\" NAME=\"search\" SIZE=\"20\"></TD></TR>\n";
  echo "<TR><th>For placement in</th>\n";
  echo "<TD><INPUT TYPE=\"TEXT\" NAME=\"year\" VALUE=\"" .
       (get_academic_year() + 1) . "\" SIZE=\"4\">";
  echo "</TD><TR>\n";


  // Vacancy types
  $query  = "SELECT * FROM vacancytype ORDER BY name";
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch vacancytypes.", $query);

  echo "<TR><TD><INPUT TYPE=\"CHECKBOX\" NAME=\"searchcompanies\" CHECKED> Search Companies</TD>" .
    "<TD><INPUT TYPE=\"CHECKBOX\" NAME=\"searchvacancies\" CHECKED> Search Vacancies</TD></TR>\n";
  echo "<tr><td colspan=\"2\"><INPUT TYPE=\"CHECKBOX\" NAME=\"showclosed\"";
  if(!is_student()) echo " CHECKED";
  echo "> Show \"closed\" vacancies</td></tr>";

  echo "<TR><TH COLSPAN=\"2\" ALIGN=\"CENTER\">Activity Types</TH></TR>\n";
  $newrow = TRUE;
  while($row = mysql_fetch_array($result)){
    if($newrow) echo "<TR>\n";
    echo "<TD>";
    echo "<INPUT TYPE=\"CHECKBOX\" NAME=\"vt" . $row[vacancy_id];
    echo "\" VALUE=\"1\" CHECKED> " . htmlspecialchars($row[name]);
    echo "</TD>\n";
    if($newrow){
      $newrow = FALSE;
    } else
    {
      echo "</TR>\n";
      $newrow = TRUE;
    }
  }
  if(!$newrow) echo "</TR>\n";

  echo "<TR><th ALIGN=\"CENTER\" COLSPAN=2><B>Sort criteria</B></th></TR>\n";
  echo "<TR><TD COLSPAN=2>";
  echo "<INPUT TYPE=\"RADIO\" NAME=\"sort\" VALUE=\"name\" CHECKED> Name";
  echo "<INPUT TYPE=\"RADIO\" NAME=\"sort\" VALUE=\"locality\"> Locality";
  echo "</TD></TR>\n";

  echo "<TR><TD ALIGN=\"CENTER\" COLSPAN=2>";
  if(!empty($student_id))
    echo "<INPUT TYPE=\"HIDDEN\" NAME=\"student_id\" VALUE=\"$student_id\">\n";
  echo "<INPUT TYPE=\"HIDDEN\" NAME=\"mode\" VALUE=\"COMPANY_DISPLAYLIST\">\n";
  echo "<INPUT TYPE=\"submit\" NAME=\"button\" VALUE=\"Search\">\n";
  echo "<INPUT TYPE=\"reset\" VALUE=\"Reset\">\n";
  echo "</TD></TR>\n";

  echo "</TABLE>\n";

  if($edit && is_admin())
  {
    echo "<P ALIGN=\"CENTER\"><A HREF=\"$PHP_SELF?mode=COMPANY_STARTADD\">" .
         "Add a new company</A></P>\n";
  }
}


function company_displaylist()
{
  global $PHP_SELF;
  global $search;       // Search field
  global $sort;         // Sort field
  global $log;
  global $year;
  global $smarty;
 
  $companies = array(); // To hold all matches.

  // Form Search criteria string
  if(!empty($search)){
    $searchc .= " name LIKE '%" . $search . "%'" .
                "OR locality LIKE '%" . $search . "%'" .
                "OR town     LIKE '%" . $search . "%'" .
                "OR brief    LIKE '%" . $search . "%'";
  }

  // Form Sort criteria string
  if($sort == 'locality') $sortc = " ORDER BY locality";
  else $sortc = " ORDER BY name";

  $query = "SELECT * FROM companies";

  // Search Criteria
  if(!empty($searchc)) $query .= " WHERE" . $searchc;

  // Sort Criteria
  $query .= $sortc; 
  $result = mysql_query($query)
    or print_mysql_error2("Unable to fetch company list", $query);

  while($row = mysql_fetch_array($result))
  {
    // Need to check activity types are valid
    $valid = FALSE;
    $vac_query = "SELECT vacancy_id FROM companyvacancy WHERE " .
                 "company_id=" . $row["company_id"];
    $vac_result = mysql_query($vac_query)
       or print_mysql_error2("Unable to get vacancy info.", $vac_query);
  
    while(!$valid && ($vac_row=mysql_fetch_array($vac_result)))
    {
      $vt = "vt" . $vac_row["vacancy_id"];
      if(!empty($_POST[$vt])) $valid=TRUE;
    }
    mysql_free_result($vac_result);
     
    if($valid)
    { 
      $row["last_access"] = company_lastlogin($row["company_id"]);
      array_push($companies, $row);
    }
  }


  $smarty->assign_by_ref("companies", $companies);
  $smarty->display("companies/company_search.tpl");

 
  $log['access']->LogPrint("company search performed.");

}


function vacancy_search()
{
  global $smarty;
  global $log;

  $search = $_REQUEST["search"];
  $year   = $_REQUEST["year"];
  $sort   = $_REQUEST["sort"];
  $showclosed = $_REQUEST["showclosed"];

  // Form Sort criteria string
  if($sort == 'locality') $sortc = " ORDER BY vacancies.status, vacancies.locality";
  else $sortc = " ORDER BY vacancies.status, description";


  // Array to hold matching vacancies
  $vacancies = array();

  // Form Search criteria string
  if(!empty($search)){
    $searchc .= " (description LIKE '%" . $search . "%' " .
      "OR vacancies.locality  LIKE '%" . $search . "%' " .
      "OR vacancies.town      LIKE '%" . $search . "%' " .
      "OR vacancies.brief     LIKE '%" . $search . "%' " .
      "OR companies.name LIKE '%$search%' " .
      "OR companies.brief LIKE '%$search%')";
  }

  if(!empty($year))
  {
    if(!empty($searchc)) $searchc .= " AND";
    $searchc .= " YEAR(jobstart) = '$year'";
  }

  if(!$showclosed)
  {
    if(!empty($searchc)) $searchc .= " AND";
    $searchc .= " vacancies.status != 'closed'";
  }    


  // Add search criteria for placement year
  $sql = "SELECT vacancies.* FROM vacancies " .
    "LEFT JOIN companies ON vacancies.company_id = companies.company_id ";
  if(!empty($searchc)) $sql .= "WHERE $searchc";
  $sql .= $sortc;

  // Run query
  $result = mysql_query($sql)
    or print_mysql_error2("Unable to search vacancy lists.", $sql);
  while($vacancy = mysql_fetch_array($result))
  {
    // Check validity before going further
 
    $valid = FALSE;
    $sub_sql = "SELECT activity_id FROM vacancyactivity WHERE vacancy_id=" . $vacancy["vacancy_id"];
    $sub_result = mysql_query($sub_sql)
      or print_mysql_error2("Unable to get activity info for vacancy", $sub_sql);

    if(!mysql_num_rows($sub_result)) continue;
    else{
      while(!$valid && ($activity=mysql_fetch_array($sub_result))){
	$vt = "vt" . $activity["activity_id"];
	if(!empty($_REQUEST[$vt])) $valid=TRUE;
      }
    }
    mysql_free_result($sub_result);

    if($valid){
      $vacancy["company_name"] = get_company_name($vacancy["company_id"]);
      $vacancy["company_access"] = company_lastlogin($vacancy["company_id"]);
      array_push($vacancies, $vacancy);
    }
  }
  mysql_free_result($result);

  $smarty->assign_by_ref("vacancies", $vacancies);
  $smarty->display("companies/vacancy_search.tpl");
}


?>