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
  $waf->display("main.tpl", "application:user:form_get_user_status:form_get_user_status", 'application/user/form_get_user_status.tpl');
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
  $user['last_time'] = $object->last_time;

  $waf->assign("user", $user);

  switch($format)
  {
    case 'html':
      $decoded_user = var_export($user, true);
      $waf->assign("decoded_data", $decoded_user);
      $waf->display("main.tpl", "application:user:form_get_user_status:get_user_status", 'application/user/decoded_data.tpl');
      break;
    case 'php':
    default:
      echo serialize($user);
      break;
  }
}

function form_kill_session(&$waf)
{
  $session_killed = WA::request("session_killed");
  if($session_killed)
  {
    $waf->assign("session_killed", $session_killed);
  }
  $waf->display("main.tpl", "application:user:form_kill_session:form_kill_session", 'application/user/form_kill_session.tpl');
}

/**
* Used by other applications to logout an OPUS user with a given session_id
*/
function kill_session(&$waf)
{
  $session_id = WA::request("session_id");
  $interactive = WA::request("interactive");
  // Assume this session and destroy it
  session_id($session_id);
  unset($_SESSION['user']);
  @session_destroy();

  if($interactive)
  {
    // API user logged in interactively
    goto_section("user", "form_kill_session&session_killed=$session_id");
  }
}

?>