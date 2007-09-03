<?php

function home(&$waf) 
{
  $waf->assign("subsection", "home");
  $waf->assign("page_title", "Admin Home Page");

  $waf->display("main.tpl");
}

function company_activity(&$waf)
{
  $days = (int) WA::request("days");

  if($days)
  {
    // Look for activity in the last few days
    $waf->assign("days", $days);
    $unixtime = time();
    $unixtime -= ($days * 24 * 60 * 60);
    $since = date("YmdHis", $unixtime);
  }
  else
  {
    // since the last login
    $since = $waf->user['opus']['last_login'];
    if(!$last_login) $since = 0;
  } 

  require_once("model/Vacancy.class.php");
  require_once("model/Company.class.php");

  $vacancies_created = Vacancy::get_all("where created > " . $since);
  $vacancies_modified = Vacancy::get_all("where modified > " . $since);
  $companies_created = Company::get_all("where created > " . $since);
  $companies_modified = Company::get_all("where modified > " . $since);

  $waf->assign("vacancies_created", $vacancies_created);
  $waf->assign("vacancies_modified", $vacancies_modified);
  $waf->assign("companies_created", $companies_created);
  $waf->assign("companies_modified", $companies_modified);
  $waf->assign("since", $since);

  $waf->display("main.tpl", "admin:home:company_activity:company_activity", "admin/home/company_activity.tpl");
}

?>