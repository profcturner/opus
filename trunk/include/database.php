<?php
/**
 ** database.php
 **
 ** This file is to be loaded by new scripts in the PMS
 ** to move towards a more abstracted data layer.
 **
 ** Initial Coding: Colin Turner 2005-02-16
 */

// Depends on PEAR
require_once 'DB.php';

// Depends on config.php
require_once 'config.php';

// Database credentials
$dsn = array(
    'phptype'  => 'mysql',
    'username' => $conf['database']['username'],
    'password' => $conf['database']['password'],
    'hostspec' => $conf['database']['host'],
    'database' => $conf['database']['database'],
);

/*
$options = array(
    'ssl' => false,
);
*/

$db =& DB::connect($dsn, $options);
if (DB::isError($db)) {
    die($db->getMessage());
}

$db->setFetchMode(DB_FETCHMODE_ASSOC);

?>