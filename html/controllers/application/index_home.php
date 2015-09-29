<?php

/**
* Home Menu for Applications
*
* @package OPUS
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
*/

function home($waf, $user_id) 
{
  $waf->display("main.tpl", "application:home:home:home");
}

?>
