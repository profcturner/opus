<?php

/**
* Read Old OPUS 3.x configuration
*
* This script only loads old OPUS 3.x style configuration files to map the
* database details into what is expected by dbconfig-common. It is only
* used for automated conversion from old databases using Debian GNU/Linux.
*
* @package OPUS
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
*/

require_once("/usr/share/opus/include/config.php");

$dbuser   = $conf['database']['username'];
$dbpass   = $conf['database']['password'];
$dbname   = $conf['database']['database'];
$dbserver = $conf['database']['host'];
$dbtype   = 'mysql';

?>