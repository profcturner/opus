<?php

  /**
  * Superuser Menu for Administrators
  *
  * @package OPUS
  * @author Colin Turner <c.turner@ulster.ac.uk>
  * @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
  */

  if(!User::is_root()) $GLOBALS['waf']->halt("error:admin:no_access");

  /**
  * @author Gordon Crawford <g.crawford@ulster.ac.uk>
  */
  function view_phpinfo(&$waf) 
  {
    ob_start();
    phpinfo();   
    $php_info = ob_get_contents();
    ob_end_clean(); 
    $php_info = preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $php_info);
    $waf->assign("php_info", $php_info);
  
    $waf->display("main.tpl", "admin:superuser:view_phpinfo:view_phpinfo", "super/home/view_phpinfo.tpl");
  }

  function edit_phonehome(&$waf, &$user) 
  {
    // Indicate we should not ask again this session
    $_SESSION['phonehome_asked'] = true;

    // This table only has one row...
    $id = 1;

    edit_object($waf, $user, "PhoneHome", array("confirm", "superuser", "edit_phonehome_do"), array(array("cancel","section=home&function=home")), array(array("admin_id",User::get_id())), "admin:superuser:edit_phonehome:edit_phonehome");
  }

  function edit_phonehome_do(&$waf, &$user) 
  {

    edit_object_do($waf, $user, "PhoneHome", "section=home&function=home", "edit_phonehome");
  }

?>