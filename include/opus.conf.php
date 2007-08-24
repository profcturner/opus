<?php
/**
 * @package Configuration
 *
 * The system wide configuration file for the OPUS
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
$config['waf']['debugging']       = True;    
$config['waf']['language']        = "en";
$config['waf']['waf_debug']       = True;

// opus related settings

$config['opus']['smartyfiles']   = '/usr/share/smarty_files';
$config['opus']['docroot']       = '/usr/share/opus/html';
$config['opus']['lib']           = '/usr/share/opus/include';
$config['opus']['url']           = 'http://localhost/opus';
$config['opus']['debug']         = False;
$config['opus']['cleanurls']     = False;
$config['opus']['title']         = 'OPUS';
$config['opus']['title_short']   = 'OPUS';
$config['opus']['tagline']       = 'Online Placement University System';
$config['opus']['version']       = 4;
$config['opus']['minor_version'] = 0;
$config['opus']['development_mode'] = true;
$config['opus']['validation_image_ok'] = "images/ok-small.png";
$config['opus']['validation_image_fail'] = "images/fail-small.png";
$config['opus']['logo'] = "logo.png";

// names of objects held in session

$config['opus']['session']['navigation'] = 'PDS_Navigation';

// web service configuration settings

$config['opus']['ws']['url'] = "http://localhost/ws";
$config['opus']['ws']['username'] = "ws";
$config['opus']['ws']['password'] = "password";

// now include the local configuration file
// create this file locally and add any variation to the above cinfiguration

include "../include/local.conf.php"; 

$config['opus']['smarty']['template_dir'] = $config['opus']['smartyfiles']."/opus/templates/"; 
$config['opus']['smarty']['compile_dir'] = $config['opus']['smartyfiles']."/templates_c/"; 
$config['opus']['smarty']['config_dir'] = $config['opus']['smartyfiles']."/opus/configs/"; 
$config['opus']['smarty']['cache_dir'] = $config['opus']['smartyfiles']."/cache/"; 


$config['waf']['templates_dir']   = "/usr/share/opus/templates/"; 
$config['waf']['templates_c_dir'] = "/usr/share/opus/templates_c/";
$config['waf']['config_dir']      = "/usr/share/opus/configs/";
$config['waf']['cache_dir']       = "/usr/share/opus/templates_cache/";
?>
