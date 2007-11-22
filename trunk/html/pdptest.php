<?php

include 'common.php';
include 'authenticate.php';
include 'lookup.php';
include 'pdp.php';

page_header("test");


echo microtime_float();
echo "<br />";
$templates =  get_PDP_cv_templates();
echo microtime_float();
echo "<br />";

$valid = get_PDP_valid_templates(2445, 1);
echo microtime_float();
echo "<br />";

$test = get_PDP_cv_status(2445, 1);
echo microtime_float();
echo "<br />";

//echo $test;

$smarty->assign_by_ref("valid", $valid);
$smarty->assign_by_ref("templates", $templates);
$smarty->assign_by_ref("test", $test);


$page->end();


?>