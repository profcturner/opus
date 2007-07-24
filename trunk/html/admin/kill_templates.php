<?php

include('authenticate.php');

$templates_compile = $conf['paths']['templates'] . "templates_c";

auth_user("admin");

system("rm -rf $templates_compile");

?>