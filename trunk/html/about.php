<?php

/**
* about.php
*
* Gives information about OPUS.
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/

// The include files
include('common.php');
include('authenticate.php');
include('lookup.php');

$smarty->display("about.tpl");

?>