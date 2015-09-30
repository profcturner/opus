<?php

  /**
  * Recent Items Menu for Administrators
  *
  * @package OPUS
  * @author Colin Turner <c.turner@ulster.ac.uk>
  * @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
  */

  function last_items($waf, &$user)
  {
    $waf->assign("recent_items", $_SESSION['lastitems']->get_nav());

    $waf->display("main.tpl", "admin:recent:last_items:last_items", "admin/recent/last_items.tpl");
  }

?>
