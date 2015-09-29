<?php

/**
* Controller for the Student User, Welcome Section
*/

function home($waf) 
{
  $welcome_page = 'yes';
  $waf->assign("welcome_page", $welcome_page);
  $waf->assign('tag_line', ' ');

  $waf->display("main.tpl", "student:welcome:home:home", 'student/welcome/welcome.tpl');
  //goto_section("placement", "placement_home");
  //$pds->display("main.tpl", "student:home:home:home");
}

?>
