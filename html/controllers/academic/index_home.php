<?php
function home(&$pds) 
{
  $pds->assign("subsection", "home");
  $pds->assign("page_title", "Academic Home Page");
  $pds->display("main.tpl");
}
function list_messages(&$pds) 
{
  $pds->assign("subsection", "messages");
  $pds->assign("page_title", "Messages");
  $pds->display("main.tpl");
}
function view_calendar(&$pds) 
{
  $pds->assign("subsection", "calendar");
  $pds->assign("page_title", "Calendar");
  $pds->display("main.tpl");
}
function list_contacts(&$pds) 
{
  $pds->assign("subsection", "contacts");
  $pds->assign("page_title", "Contacts");
  $pds->display("main.tpl");
}
?>