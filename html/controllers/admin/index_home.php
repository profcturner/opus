<?php

function home(&$pds) 
{
  $pds->assign("subsection", "home");
  $pds->assign("page_title", "Admin Home Page");
  $pds->display("main.tpl");
}
function list_messages(&$pds) 
{
  $pds->assign("subsection", "messages");
  $pds->assign("page_title", "Messages");
  $pds->display("main.tpl");
}
function view_demo_requests(&$pds) 
{
  $pds->assign("subsection", "demo_requests");
  $pds->assign("page_title", "Demo Requests");
  $pds->display("main.tpl");
}
?>