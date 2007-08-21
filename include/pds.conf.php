<?php
/**
 * @package Configuration
 *
 * The system wide configuration file for the PDSystem
 */

// waf settings

$config['waf']['title']           = 'OPUS';
$config['waf']['log_dir']         = '/var/log/opus/';
$config['waf']['log_level']       = 7;

// log_level settings

// 0 emergency
// 1 alert
// 2 critical
// 3 error
// 4 warning
// 5 notice
// 6 info
// 7 debug

$config['waf']['auth_dir']        = '/usr/share/opus/include/auth.d';
$config['waf']['compile_check']   = True; 
$config['waf']['caching']         = False; 
$config['waf']['debugging']       = False;    
$config['waf']['language']        = "en";
$config['waf']['user']            = "PDS_USER";

// pds related settings

$config['pds']['smartyfiles']   = '/usr/share/smarty_files';
$config['pds']['docroot']       = '/usr/share/opus/html';
$config['pds']['lib']           = '/usr/share/opus/include';
$config['pds']['url']           = 'http://localhost/opus';
$config['pds']['debug']         = False;
$config['pds']['cleanurls']     = False;
$config['pds']['title']         = 'PDSystem';
$config['pds']['title_short']   = 'PDS';
$config['pds']['tagline']       = 'Personal Development System';
$config['pds']['version']       = 4;
$config['pds']['minor_version'] = 0;
$config['pds']['development_mode'] = true;
$config['pds']['validation_image_ok'] = "images/ok-small.png";
$config['pds']['validation_image_fail'] = "images/fail-small.png";

// names of objects held in session

$config['pds']['session']['navigation'] = 'PDS_Navigation';

// web service configuration settings

$config['pds']['ws']['url'] = "http://localhost/ws";
$config['pds']['ws']['username'] = "ws";
$config['pds']['ws']['password'] = "password";

// now include the local configuration file
// create this file locally and add any variation to the above cinfiguration

include "../include/local.conf.php"; 

$config['pds']['smarty']['template_dir'] = $config['pds']['smartyfiles']."/pds/templates/"; 
$config['pds']['smarty']['compile_dir'] = $config['pds']['smartyfiles']."/templates_c/"; 
$config['pds']['smarty']['config_dir'] = $config['pds']['smartyfiles']."/opus/configs/"; 
$config['pds']['smarty']['cache_dir'] = $config['pds']['smartyfiles']."/cache/"; 


$config['waf']['templates_dir']   = $config['pds']['smartyfiles']."/opus/templates/"; 
$config['waf']['templates_c_dir'] = $config['pds']['smartyfiles']."/templates_c/";
$config['waf']['config_dir']      = $config['pds']['smartyfiles']."/opus/configs/";
$config['waf']['cache_dir']       = $config['pds']['smartyfiles']."/cache/";
?>
