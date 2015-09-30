<?php

/**
* Controller for the Student User, Home Section
* @package OPUS
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
*/

/**
* Controller for the Student User, Home/Dashboard Section
*/

function home($waf) 
{
  $waf->display("main.tpl", "student:home:home:home", 'student/home/home.tpl');
  //goto_section("placement", "placement_home");
  //$pds->display("main.tpl", "student:home:home:home");
}

?>
