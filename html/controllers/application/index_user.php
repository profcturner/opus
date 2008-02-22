<?php

/**
* User Menu for Applications
*
* @package OPUS
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
*/


function form_get_user_status(&$waf)
{
  $waf->display("main.tpl", "application:user:get_user_status:form_get_user_status", 'application/user/form_get_user_status.tpl');
}


function get_user_status(&$waf)
{
  $reg_number = WA::request("reg_number");
  $format = WA::request("format");

  require_once("model/User.class.php");
  $object = User::load_by_reg_number($reg_number);

  $user['username'] = $object->username;
  $user['reg_number'] = $object->reg_number;
  $user['user_type'] = $object->user_type;
  $user['real_name'] = $object->real_name;

  $waf->assign("user", $user);

  switch($format)
  {
    case 'html':
      $waf->display("main.tpl", "application:user:form_get_user_status:get_user_status");
      break;
    case 'php':
    default:
      echo serialize($user);
      break;
  }
}

?>