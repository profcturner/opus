<?php

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
  
    $waf->display("main.tpl", "admin:advanced:view_phpinfo:view_phpinfo", "admin/advanced/view_phpinfo.tpl");
  }

?>
