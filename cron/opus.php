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
  require_once("UUWAF.class.php");

  // Running in batch mode
  $config['waf']['unattended'] = true;

  if($config['opus']['benchmarking'])
  {
    require_once("model/Benchmark.class.php");
    $benchmark = new Benchmark;
  }

  // Initialise the Web Application Framework
  $waf =& UUWAF::get_instance($config['waf']);
  $waf->log("cron job started");

  $waf->assign_by_ref("benchmark", $benchmark);
  $waf->assign_by_ref("config", $config);


  $waf->register_data_connection('default', $config_sensitive['opus']['database']['dsn'], $config_sensitive['opus']['database']['username'], $config_sensitive['opus']['database']['password']);

  $waf->create_log_file("cron");
  $waf->set_default_log("cron");
  $waf->set_log_ident("cron");

  $function = $_SERVER['argv'][1];

  // other parameters passed in regular var=value&var2=value2 format
  $parameters = $_SERVER['argv'][2];
  $parameters = explode("&", $parameters);
  $parameter_count = 0;
  $key = array();
  $value = array();
  foreach($parameters as $keyvalue)
  {
    $parts = explode("=", $keyvalue);
    $key[$parameter_count] = $parts[0];
    $value[$parameter_count] = $parts[1];
    $parameter_count++;
  }
  $decoded_parameters = array_combine($key, $value);
  $waf->assign("parameters", $decoded_parameters);

  if(function_exists($function))
  {
    $waf->log("invoking $function", PEAR_LOG_DEBUG);
    $function($waf, $decoded_parameters);
  }
  else
  {
    help($waf, $parameters);
  }
  echo "\n";
}

function help(&$waf, $parameters)
{
  $waf->display("cron.tpl", "cron:user_count", "cron/help.tpl");
}

function dev_help(&$waf, $parameters)
{
  $waf->display("cron.tpl", "cron:user_count", "cron/dev_help.tpl");
}

function check_online_users(&$waf, $parameters)
{
  require_once("model/User.class.php");
  User::check_online_users();
}

function user_count(&$waf, $parameters)
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

function company_count(&$waf, $parameters)
{
  require_once("model/Company.class.php");
  $companies = Company::count();

  $waf->assign("companies", $companies);

  $waf->display("cron.tpl", "cron:user_count", "cron/company_count.tpl");
}

function vacancy_count(&$waf, $parameters)
{
  require_once("model/Vacancy.class.php");
  $vacancies = Vacancy::count();

  $waf->assign("vacancies", $vacancies);

  $waf->display("cron.tpl", "cron:user_count", "cron/vacancy_count.tpl");
}

function phone_home_install(&$waf, $parameters)
{
  require_once("model/PhoneHome.class.php");

  PhoneHome::send_install();
}

function phone_home_periodic(&$waf, $parameters)
{
  require_once("model/PhoneHome.class.php");

  PhoneHome::send_periodic();
}

/*
function create_admin(&$waf, $parameters)
{
  require_once("model/Admin.class.php");
  $admin = new Admin;

  $fields['username'] = $parameters['username'];
  $fields['password'] = md5($parameters['password']);
  $fields['salutation'] = "Dr";
  $fields['firstname'] = "Demo";
  $fields['lastname'] = "User";
  print_r($fields);
  Admin::insert($fields);
}
*/

function start()
{
  require_once("model/Service.class.php");

  Service::start();
}

function stop()
{
  require_once("model/Service.class.php");

  Service::stop();
}

function update_timelines($waf, $parameters)
{
  // Better ensure the configuration is up-to-date
  update_perl_config();

  require_once("model/Timeline.class.php");
  Timeline::update_all_years();
}

function update_timelines_for_year($waf, $parameters)
{
  // Better ensure the configuration is up-to-date
  update_perl_config();

  require_once("model/Timeline.class.php");
  // Do a very comprehensive check
  Timeline::update_year($parameters['year'], true);
}

function expire_vacancies($waf, $parameters)
{
  require_once("model/Vacancy.class.php");
  Vacancy::close_expired_vacancies();
}

/**
* write the database access credentials to a suitable perl file
*/
function update_perl_config()
{
  $waf =& UUWAF::get_instance();
  global $config_sensitive;

  $filename = "config.pl";
  $fp = @fopen($filename, "w");
  if($fp == false)
  {
    $message = "unable to write perl configuration file";
    echo $message;
    $waf->log($message);
    return;
  }
  fwrite($fp, "# automatically generated file, make changes to the php configuration\n");
  fwrite($fp, "# and run 'opus update_perl_config'\n\n");
  fwrite($fp, "package opus;\n\n");
  fwrite($fp, "\$db_dsn='dbi:" . $config_sensitive['opus']['database']['dsn'] . "';\n");
  fwrite($fp, "\$db_username='" . $config_sensitive['opus']['database']['username'] . "';\n");
  fwrite($fp, "\$db_password='" . $config_sensitive['opus']['database']['password'] . "';\n");
  fwrite($fp, "\nreturn 1;\n# end of file\n");
  fclose($fp);
}

function get_academic_year()
{
  global $config;
  $yearstart = $config['opus']['yearstart'];
  if(empty($yearstart)) $yearstart="0930";

  if(empty($year)){
    if(date("md") < $yearstart) $year = date("Y") - 1;
    else $year = date("Y");
  }
  return($year);
}

function change_password($waf, $parameters)
{
  if(empty($parameters['username'])) help($waf);
  if(empty($parameters['password'])) help($waf);
  
  echo "Changing password for " . $parameters['username'] . " ... ";
  
  $waf->log("change password for user " . $parameters['username']);
  require_once("model/User.class.php");
  $user = User::load_by_username($parameters['username']);
  if($user->id)
  {
    // Successfully loaded a real user
    $user->password = md5($parameters['password']);
    $user->_update();
    echo "success\n";
  }
  else
  {
    $waf->log("password change failed, user could not be loaded.");
    echo "failed\n";
  }
}


function check_missing_error_prompts()
{
  $halt_command = "grep -Ihor \"halt(\\\"error:.*.*\\\")\" ../* | sed -e \"s/halt(\\\"//\" -e \"s/\\\")//\" | sort | uniq";
  // Look for the error codes called on halt, from the top of the source tree
  $fp = popen($halt_command, 'r');
  while(!feof($fp))
  {
    $line = trim(fgets($fp));
    if(empty($line)) continue; // Last line is blank
    // grep for this now!
    

    $missing_halt_command = "grep -IL \"" . $line . "\" ../configs/lang_*";
    $missing_files = array();
    $fp_missing = popen($missing_halt_command, "r");
    while(!feof($fp_missing))
    {
      $missing_files_line = trim(fgets($fp_missing));
      if(!empty($missing_files_line)) array_push($missing_files, $missing_files_line);
    }
    pclose($fp_missing);
    if(count($missing_files))
    {
      echo "Prompt $line... missing for :\n";
      foreach($missing_files as $missing_files_line)
      {
        echo "  $missing_files_line\n";
      }
    }
    
  }
  pclose($fp);
}

function check_missing_lang_prompts()
{
  $lang_command = "grep -Ihor \\\"[a-z_]*:[a-z_]*:[a-z_]*:[a-z_]*\\\" ../* | sort | uniq";
  // Look for the error codes called on halt, from the top of the source tree
  $fp = popen($lang_command, 'r');
  while(!feof($fp))
  {
    $line = trim(fgets($fp));
    if(empty($line)) continue; // Last line is blank
    // grep for this now!
    

    $missing_lang_command = "grep -IL \"" . $line . "\" ../configs/lang_*";
    $missing_files = array();
    $fp_missing = popen($missing_lang_command, "r");
    while(!feof($fp_missing))
    {
      $missing_files_line = trim(fgets($fp_missing));
      if(!empty($missing_files_line)) array_push($missing_files, $missing_files_line);
    }
    pclose($fp_missing);
    if(count($missing_files))
    {
      echo "Prompt $line... missing for :\n";
      foreach($missing_files as $missing_files_line)
      {
        echo "  $missing_files_line\n";
      }
    }
    
  }
  pclose($fp);
}

?>