<?php

function home(&$pds) 
{
  goto("placement", "placement_home");
  $pds->display("main.tpl", "student:home:home:home");
}
function list_messages(&$pds) 
{
  $pds->display("main.tpl", "student:home:messages:list_messages");
}
function view_calendar(&$pds) 
{
  $pds->display("main.tpl", "student:home:calendar:view_calendar");
}
function list_contacts(&$pds) 
{
  $pds->display("main.tpl", "student:home:contacts:list_contacts");
}
function open_email(&$pds) 
{
  $pds->display("main.tpl", "student:home:email:open_email");
}
function list_artefacts(&$pds) 
{
  $pds->display("main.tpl", "student:home:artefacts:list_artefacts");
}

?>