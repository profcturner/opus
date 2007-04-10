#!/usr/bin/php -q
<?php

// Experimental: In the past we've called cron jobs with massive arguments to specify include paths because
// 1. php.ini isn't correct, and was overridden in Apache, not appropriate here
// 2. we sometimes don't have access to php.ini
// Problem - we don't know the location of Smarty, or Pear
//set_include_path(../include)

include('common.php');
include('lookup.php');
include('automail.php');
include('authenticate.php');

// Connect to the database
db_connect()
  or die("Unable to open database");

$log['system']->LogPrint("Update cron job running");

vacancy_update_status();

/**
 ** vacancy_update_status()
 **
 */
function vacancy_update_status()
{
  global $log;
  $now = date("YmdHis");

  $log['system']->LogPrint("Checking for vacancies past their close date");
  // We could do this with one query, but it's better
  // to "know" which vacancies have closed
  $sql = "SELECT * FROM vacancies WHERE status != 'closed'" .
    " AND $now > closedate";
  //echo $sql;
  $result = mysql_query($sql)
    or cron_mysql_error("Unable to fetch vacancy information", $sql);

  while($row = mysql_fetch_array($result))
  {
    vacancy_close($row["vacancy_id"]);
  }
  mysql_free_result($result);
}

function vacancy_close($vacancy_id)
{
  global $conf;
  global $log;

  // Get all relevant information
  $sql = "SELECT * FROM vacancies WHERE vacancy_id=$vacancy_id";
  $result = mysql_query($sql)
    or cron_mysql_error("Unable to fetch vacancy $vacancy_id", $sql);

  $vacancy = mysql_fetch_array($result);
  mysql_free_result($result);

  $company_name = get_company_name($vacancy["company_id"]);

  $log["system"]->LogPrint("Closing vacancy " .
         $vacancy['description'] . " for $company_name"); 
  // Close the vacancy, probably best NOT to update modified
  // in this scenario
  $sql = "UPDATE vacancies SET status='closed' WHERE " .
    "vacancy_id = $vacancy_id";
  //echo "Test: $sql\n";
  $result = mysql_query($sql)
    or die("Unable to update vacancy\nQuery: $sql\n");

  // Now inform the primary contact, get his/her info first
  $contact = get_contact($vacancy['contact_id']);

  // Start to populate the mail fields
  $mailfields = array();
  $mailfields['custom_vacancydesc'] = $vacancy['description'];
  $mailfields['custom_companyname'] = $company_name;

  if(!empty($contact['email']))
  {
    $mailfields['rtitle']     = $contact['title'];
    $mailfields['rfirstname'] = $contact['firstname'];
    $mailfields['rsurname']   = $contact['surname'];
    $mailfields['remail']     = $contact['email'];
    $mailfields['rposition']  = $contact['position'];

    
    echo "  Contact: " . $contact['title'] . " " . $contact['surname'] . "\n";
    automail("CompanyOnClosed", $mailfields);

  }
  else
  {

  }

}

function get_contact($user_id)
{
  if(empty($user_id))
  {
    return NULL;
  }
  $sql = "SELECT * FROM contacts WHERE contact_id=$user_id";
  $result = mysql_query($sql)
    or cron_mysql_error("Unable to fetch contact $user_id", $sql);

  $contact = mysql_fetch_array($result);
  mysql_free_result($result);

  return($contact);
}

function cron_mysql_error($die_error, $query)
{
  global $conf;   // We need access to the configuration
  global $log;    // and logging

  echo "MySQL error " . mysql_errno() . ":" .
    mysql_error();

  if(isset($log["debug"])){
    $log_line = "CRON: MYSQLERROR : (" . mysql_errno() .
                ") " . mysql_error() . "USERERROR : [$die_error] QUERY : [$query]";
    $log["debug"]->LogPrint($log_line);
  }
  die($die_error);
}


?>