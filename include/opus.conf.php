<?php
/**
 * @package Configuration
 *
 * The system wide configuration file for OPUS
 */

// WAF settings

$config['waf']['title']                 = 'OPUS';
$config['waf']['log_level']             = 7; // From 0 - emergency, to 7 - debug
$config['waf']['compile_check']         = True;
$config['waf']['caching']               = False;
$config['waf']['debugging']             = True;
$config['waf']['debug_only_on_IP']      = array("127.0.0.1", "193.61.142.92");
$config['waf']['language']              = "en";
$config['waf']['waf_debug']             = True;
$config['waf']['validation_image_ok']   = "images/ok-small.png";
$config['waf']['validation_image_fail'] = "images/fail-small.png";
$config['waf']['auth_dir']              = '/usr/share/opus/include/auth.d';
$config['waf']['log_dir']               = '/var/log/opus/';
$config['waf']['templates_dir']         = "/usr/share/opus/templates/"; 
$config['waf']['templates_c_dir']       = "/usr/share/opus/templates_c/";
$config['waf']['config_dir']            = "/usr/share/opus/configs/";
$config['waf']['cache_dir']             = "/usr/share/opus/templates_cache/";
$config['waf']['session_dir']           = "";

// OPUS related settings

$config['opus']['url']                  = 'http://localhost/opus';
$config['opus']['cleanurls']            = False;
$config['opus']['title']                = 'OPUS';
$config['opus']['title_short']          = 'OPUS';
$config['opus']['tagline']              = 'Online Placement University System';
$config['opus']['version']              = 4;
$config['opus']['minor_version']        = 0;
$config['opus']['patch_version']        = 0;
$config['opus']['benchmarking']         = true;
$config['opus']['logo']                 = "logo.png";


$config['opus']['paths']['resources']   = '/usr/share/opus/resources/';
// names of objects held in session

$config['opus']['session']['navigation'] = 'OPUS_Navigation';

$config_sensitive['opus']['database']['dsn'] = "mysql:host=localhost;dbname=opus4";
$config_sensitive['opus']['database']['username'] = "root";
$config_sensitive['opus']['database']['password'] = "test";

// PDSystem configuration options, use this to define the linked PDSystem (if any)
// Make the URL blank if the PDSystem is not installed
$config['opus']['pds']['url'] = "http://localhost/ws";
$config['opus']['pds']['username'] = "opus";
$config['opus']['pds']['password'] = "password";

// web service configuration settings, again, make the URL if nothing is defined
$config['opus']['ws']['url'] = "http://localhost/ws";
$config['opus']['ws']['username'] = "opus";
$config['opus']['ws']['password'] = "password";

// now include the local configuration file
// create this file locally and add any variation to the above configuration
include "../include/local.conf.php"; 

?>