<?php

/**
* Controller for the Student User, Home Section
* @package OPUS
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
*/

/**
* Controller for the Student User, Home Section
*/

function home(&$pds) 
{
  goto("placement", "placement_home");
  $pds->display("main.tpl", "student:home:home:home");
}

?>