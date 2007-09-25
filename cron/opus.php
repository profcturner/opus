#!/usr/bin/php -q
<?php

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
  $waf->set_log_ident("cron");

  $waf->log("test", PEAR_LOG_DEBUG, "cron");

  $function = $_SERVER['argv'][1];

  if(function_exists($function))
  {
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
  echo "hello world\n";
}

function user_count(&$waf)
{
  require_once("model/Student.class.php");
  $students = Student::count();

  $waf->assign("students", $students);
  $waf->display("cron.tpl", "cron:user_count", "cron/user_count.tpl");
}
?>