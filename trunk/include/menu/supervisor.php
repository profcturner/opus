<?php

$navigation = new Navigation;

$navigation->add_menu('Home', 'home',
  $conf['scripts']['supervisor']['index'], 'mn0100');

// Information menu and its sub items
$navigation->add_menu('Information', 'information', 
  $conf['scripts']['user']['resources'], 'mn0300');
$navigation->add_menu_item('Resources', 'information', 
  $conf['scripts']['user']['resources']);

?>