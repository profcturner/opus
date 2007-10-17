<?php

  function last_items(&$waf, &$user)
  {
    $waf->assign("recent_items", $_SESSION['lastitems']->get_nav());

    $waf->display("main.tpl", "admin:recent:last_items:last_items", "admin/recent/last_items.tpl");
  }

?>