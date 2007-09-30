#!/usr/bin/php -q
<?php

// php scripts, very irritatingly, use the directory they were called from,
// not the one the script is in. Try to rectify this. We use __FILE__ since
// (at least in my testing), it seems to follow symlinks and still show the
// "real" path.
if(preg_match("%^(.*)/%", __FILE__, $matches)) $directory = $matches[1]; // Unix
// Windows regexp requires four slashes, since PHP requires two for each
// backslash required for the regexp!
if(preg_match("/^(.*)\\\\/", __FILE__, $matches)) $directory = $matches[1]; // Windows

if(!chdir($directory)) die("OPUS: could not change to script directory");

// This include is relative
require_once("../include/opus.conf.php");
// Now we use config to set the others
set_include_path($config['php']['include_dir']);

main();

/**
 * This is the main function that controls OPUS
 *
 * @uses WA.class.php
 * @uses $config
 * @uses WA::request()
 *
 */

function main() 
{
  global $config;
  global $config_sensitive;

  require_once("opus.conf.php");
  require_once("WA.class.php");

  // Running in batch mode
  $config['waf']['unattended'] = true;

  if($config['opus']['benchmarking'])
  {
    require_once("model/Benchmark.class.php");
    $benchmark = new Benchmark;
  }

  // Initialise the Web Application Framework
  global $waf;
  $waf = new WA($config['waf']);
  $waf->log("cron job started");

  $waf->assign_by_ref("benchmark", $benchmark);
  $waf->assign_by_ref("config", $config);


  $waf->register_data_connection('default', $config_sensitive['opus']['database']['dsn'], $config_sensitive['opus']['database']['username'], $config_sensitive['opus']['database']['password']);

  $waf->create_log_file("cron");
  $waf->set_default_log("cron");
  $waf->set_log_ident("cron");

  $function = $_SERVER['argv'][1];

  if(function_exists($function))
  {
    $waf->log("invoking $function", PEAR_LOG_DEBUG);
    $function($waf);
  }
  else
  {
    help($waf);
  }
  echo "\n";
}

function help(&$waf)
{
  $waf->display("cron.tpl", "cron:user_count", "cron/help.tpl");
}

function print_argv(&$waf)
{
  print_r($_SERVER['argv']);
}

function user_count(&$waf)
{
  require_once("model/User.class.php");
  $roots       = User::count("where user_type='root'");
  $admins      = User::count("where user_type='admin'");
  $contacts    = User::count("where user_type='company'");
  $staff       = User::count("where user_type='staff'");
  $supervisors = User::count("where user_type='supervisors'");
  $students    = User::count("where user_type='student'");

  $waf->assign("roots", $roots);
  $waf->assign("admins", $admins);
  $waf->assign("contacts", $contacts);
  $waf->assign("staff", $staff);
  $waf->assign("supervisors", $supervisors);
  $waf->assign("students", $students);

  $waf->display("cron.tpl", "cron:user_count", "cron/user_count.tpl");
}

function company_count(&$waf)
{
  require_once("model/Company.class.php");
  $companies = Company::count();

  $waf->assign("companies", $companies);

  $waf->display("cron.tpl", "cron:user_count", "cron/company_count.tpl");
}

function vacancy_count(&$waf)
{
  require_once("model/Vacancy.class.php");
  $vacancies = Vacancy::count();

  $waf->assign("vacancies", $vacancies);

  $waf->display("cron.tpl", "cron:user_count", "cron/vacancy_count.tpl");
}

function expire_vacancies(&$waf)
{
  $now = date("YmdHis");
  require_once("model/Vacancy.class.php");

  $vacancies = Vacancy::get_ids("where closedate > $now");
  foreach($vacancies as $vacancy)
  {
    Vacancy::expire($vacancy);
  }
}
?>