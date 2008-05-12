<?php
/**
 * @package OPUS
 *
 * The system wide configuration file for OPUS
 * Don't change this file, it'll be clobbered on upgrade, make any changes in local.conf.php
 */

// PHP settings

// This next set of paths is used by cron jobs only - you will still have to
// deal with the apache config
$config['php']['include_dir'] = "/usr/share/php:/usr/share/php/smarty/libs:/usr/share/uuwaf/include:/usr/share/opus/include:.";

// WAF settings

$config['waf']['title']                 = 'OPUS';
$config['waf']['log_level']             = 7; // From 0 - emergency, to 7 - debug
$config['waf']['compile_check']         = True;
$config['waf']['caching']               = False;
$config['waf']['debugging']             = False;
$config['waf']['debug_only_on_IP']      = array("127.0.0.1");
$config['waf']['language']              = "en";
$config['waf']['waf_debug']             = False;
$config['waf']['validation_image_ok']   = "images/ok-small.png";
$config['waf']['validation_image_fail'] = "images/fail-small.png";
$config['waf']['auth_dir']              = '/usr/share/opus/include/auth.d';
$config['waf']['app_error_function']    = 'error';

// OPUS related settings

$config['opus']['institution']          = 'University of Ulster';
$config['opus']['official_url']         = 'http://opus.ulster.ac.uk';
$config['opus']['url']                  = 'http://opus.ulster.ac.uk/opus';
$config['opus']['cleanurls']            = False;
$config['opus']['title']                = 'OPUS';
$config['opus']['title_short']          = 'OPUS';
$config['opus']['tagline']              = 'Online Placement University System';
$config['opus']['version']              = 4;
$config['opus']['minor_version']        = 0;
$config['opus']['patch_version']        = 3;
$config['opus']['benchmarking']         = true;
$config['opus']['logo']                 = "logo.png";
$config['opus']['rows_per_page']        = 20;

$config['opus']['paths']['resources']   = '/var/lib/opus/resources/';
$config['opus']['paths']['photos']      = '/var/lib/opus/photos/';

// names of objects held in session
$config['opus']['session']['navigation'] = 'OPUS_Navigation';


// Don't change this if you don't know what you are doing
$config['opus']['pref_ident'] = 'preferences';

$config['opus']['tinymce_url'] = '/opus/javascript/tinymce/tiny_mce.js';

// now include the local configuration file
// create this file locally and add any variation to the above configuration
include "../include/local.conf.php"; 

?>