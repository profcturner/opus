<?php

//require("class.Navigation.php");

$navigation = new Navigation;

$navigation->add_menu('Home', 'home',
  $conf['scripts']['company']['index'], 'mn0100');
$navigation->add_menu_item('Edit Companies', 'home',
   $conf['scripts']['company']['index'] . "?mode=CompanySmallMenu");
$navigation->add_menu_item('Contact Details', 'home',
   $conf['scripts']['company']['contacts']);
$navigation->add_menu_item('Change Password', 'home',
   $conf['scripts']['admin']['password']);


// Directory menu and its sub items
$navigation->add_menu('Directories', 'directories', 
  $conf['scripts']['company']['directory'], 'mn0200');
$navigation->add_menu_item('Companies & Vacancies', 'directories',
  $conf['scripts']['company']['directory']);

?>