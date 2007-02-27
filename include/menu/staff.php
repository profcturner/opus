<?php

$navigation = new Navigation;

if(!empty($user_id)) $extra = "&user_id=$user_id";

$navigation->add_menu('Home', 'home',
  $conf['scripts']['staff']['index'], 'mn0100');
$navigation->add_menu_item('Contact Details', 'home',
  $conf['scripts']['staff']['directory'] .
  "?mode=BasicEdit" . $extra);
$navigation->add_menu_item('Student Details', 'home',
  $conf['scripts']['staff']['directory'] .
  "?mode=DisplayStudents" . $extra);

// Directory menu and its sub items
$navigation->add_menu('Directories', 'directories', '', 'mn0200');
if(is_course_director() && check_default_policy("student", "list"))
{
  $navigation->add_menu_item('Students', 'directories',
    $conf['scripts']['admin']['studentdir']);
}
$navigation->add_menu_item('Companies & Vacancies', 'directories',
  $conf['scripts']['company']['directory']);

// Information menu and its sub items
$navigation->add_menu('Information', 'information', '', 'mn0300');
$navigation->add_menu_item('Resources', 'information', 
  $conf['scripts']['user']['resources']);
?>