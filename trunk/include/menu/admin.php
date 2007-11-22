<?php

//require("class.Navigation.php");

$navigation = new Navigation;

$navigation->add_menu('Home', 'home',
  $conf['scripts']['admin']['index'], 'mn0100');

// Directory menu and its sub items
$navigation->add_menu('Directories', 'directories', 
  $conf['scripts']['admin']['studentdir'], 'mn0200');
$navigation->add_menu_item('Students', 'directories',
  $conf['scripts']['admin']['studentdir']);
$navigation->add_menu_item('Companies & Vacancies', 'directories',
  $conf['scripts']['company']['edit']);
$navigation->add_menu_item('Academic Staff', 'directories', 
  $conf['scripts']['staff']['directory']);
$navigation->add_menu_item('Contacts', 'directories', 
  $conf['scripts']['company']['contacts']);

// Information menu and its sub items
$navigation->add_menu('Information', 'information', 
  $conf['scripts']['user']['resources'], 'mn0300');
$navigation->add_menu_item('Resources', 'information', 
  $conf['scripts']['user']['resources']);
$navigation->add_menu_item('System Status', 'information', 
  $conf['scripts']['admin']['status']);
$navigation->add_menu_item('View Logs', 'information', 
  $conf['scripts']['admin']['viewlog']);

// Configuration menu and its sub items
$navigation->add_menu('Configuration', 'configuration', 
  $conf['scripts']['admin']['admindir'], 'mn0400');
$navigation->add_menu_item('Admin Details', 'configuration', 
  $conf['scripts']['admin']['admindir']);
$navigation->add_menu_item('Resources', 'configuration', 
  $conf['scripts']['admin']['resourcedir']);
$navigation->add_menu_item('Courses & Groups', 'configuration', 
  $conf['scripts']['admin']['courses']);
$navigation->add_menu_item('Help Prompts', 'configuration', 
  $conf['scripts']['admin']['edithelp']);
$navigation->add_menu_item('Mail Templates', 'configuration', 
  $conf['scripts']['admin']['automail']);
$navigation->add_menu_item('Assessments', 'configuration', 
  $conf['scripts']['admin']['assessments']);
$navigation->add_menu_item('Import Data', 'configuration', 
  $conf['scripts']['admin']['import']);


// Configuration menu and its sub items
$navigation->add_menu('Last items', 'lastitems', '', 'mn0500');
// Now loop through the last item queue in the session
foreach($_SESSION['lastitems']->queue as $last_item)
{
  $human = $last_item->human;
  if(strlen($human)>23) $human = substr($human, 0, 20) . "...";
  $navigation->add_menu_item($human, 'lastitems',
    $last_item->url);
}

?>