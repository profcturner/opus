<?php

/**
* Encapsulates page management
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/

// Smarty is 3rd party
require_once 'Smarty.class.php';

// And now our stuff
require_once 'Navigation.class.php';

$smarty = new Smarty;

$smarty->template_dir=$conf['paths']['templates'] . 'templates';
$smarty->compile_dir=$conf['paths']['templates'] . 'templates_c';
$smarty->config_dir=$conf['paths']['templates'] . 'configs';
$smarty->cache_dir=$conf['paths']['templates'] . 'templates_cache';

if($conf["debug"])
{
  $smarty->compile_check = true;
  $smarty->debugging = true;
}


$smarty->assign_by_ref("opus_version", OPUS::get_version());
$smarty->assign_by_ref("conf", $conf);
$smarty->assign_by_ref("page", $page);
$smarty->assign_by_ref("session", $_SESSION);
// This next assignment will be removed eventually
$smarty->assign_by_ref("legacy_page", $legacy_page);

// The class that follows should be declared in a single instance, into
// $page, if required.
class HTMLOPUS
{
  var $title;
  var $section;
  var $subsection;
  var $starttime;
  var $endtime;

  function __construct($title, $section="", $subsection="")
  {
    global $conf;
    global $smarty;
    global $student_id; // @todo handle in the session in the future.

    // Sadly these are not available in the $page reference
    // when the display() is called.
    $this->title = $title;
    $this->section = $section;
    $this->subsection = $subsection;

    $smarty->assign("page_title", $title);
    // This requires PHP 5 to work fully
    $this->starttime = microtime(true);
    $smarty->display('header.tpl');

    if(is_staff())
    {
      include("menu/staff.php");
      $smarty->assign("navigation", $navigation);
    }

    if(is_supervisor())
    {
      include("menu/supervisor.php");
      $smarty->assign("navigation", $navigation);
    }

    if(is_company())
    {
      include("menu/company.php");
      $smarty->assign("navigation", $navigation);
    }

    if(is_student())
    {
      include("menu/student.php");
      $smarty->assign("navigation", $navigation);
    }

    if(is_admin())
    {
      include("menu/admin.php");
      $smarty->assign("navigation", $navigation);
      if(!empty($student_id))
      {
        include("menu/student.php");
        $smarty->assign("student_navigation", $navigation);
      }
    }
    // Next two lines are for crude testing
    $smarty->assign("section", $this->section);
    $smarty->assign("subsection", $this->subsection);

    $smarty->display("nav.tpl");
  }

  function end()
  {
    global $conf;
    global $smarty;

    $this->endtime = microtime(true);
    $smarty->display('footer.tpl');
  }
}
?>