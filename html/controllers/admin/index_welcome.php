<?php

/**
* Controller for the Student User, Welcome Section
*/

function home(&$waf) 
{
  $welcome_page = 'yes';
  $waf->assign("welcome_page", $welcome_page);
  $waf->assign('tag_line', ' ');
  $waf->display("main.tpl", "admin:welcome:welcome:home", 'admin/welcome/welcome.tpl');
}

?>
