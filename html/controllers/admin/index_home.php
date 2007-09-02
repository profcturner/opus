<?php

function home(&$waf) 
{
  $waf->assign("subsection", "home");
  $waf->assign("page_title", "Admin Home Page");

  $waf->display("main.tpl");
}

function company_activity(&$waf)
{
  $waf->assign("subsection", "company_activity");
  $waf->assign("page_title", "Company Activity");

  $last_login = $waf->user['opus']['last_login'];
  if(!$last_login) $last_login = 0; 

  require_once("model/Company.class.php");
  // Get content for now
  $companies_created = Company::get_all("where created > " . $last_login);
  $companies_modifed = Company::get_all("where modified > " . $last_login);

  $waf->assign("companies_created", $companies_created);
  $waf->assign("companies_modifed", $companies_modifed);

  $waf->display("main.tpl");
}

?>