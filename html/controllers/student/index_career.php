<?php

  /**
  * Career Menu for Students
  *
  * @package OPUS
  * @author Gordon Crawford <g.crawford@ulster.ac.uk>
  * @author Colin Turner <c.turner@ulster.ac.uk>
  * @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
  */

  function manage_cvs(&$waf, $user)
  {
    $page = WA::request('page', true);
    manage_objects($waf, User::get_id(), "CV", array(array("add","section=career&function=add_cv")), array(array('edit', 'edit_cv'), array('remove','remove_cv')), "get_all", array("WHERE `user_id`=\"".User::get_id()."\"", "", $page, True), "student:career:cv_store:manage_cvs");
  }

  function add_cv(&$waf, $user) 
  {
    add_object($waf, User::get_id(), "CV", array("add", "career", "add_cv_do"), array(array("cancel","section=career&function=manage_cvs")), array(array("user_id",User::get_id())), "student:career:cv_store:add_cv");
  }

  function add_cv_do(&$waf, $user) 
  {
    add_object_do($waf, User::get_id(), "CV", "section=career&function=manage_cvs", "add_cv");
  }

  function edit_cv(&$waf, $user) 
  {
    edit_object($waf, User::get_id(), "CV", array("confirm", "career", "edit_cv_do"), array(array("cancel","section=career&function=manage_cvs")), array(array("user_id",User::get_id())), "student:career:cv_store:edit_cv");
  }

  function edit_cv_do(&$waf, $user) 
  {
    edit_object_do($waf, User::get_id(), "CV", "section=career&function=manage_cvs", "edit_cv");
  }

  function remove_cv(&$waf, $user) 
  {
    remove_object($waf, User::get_id(), "CV", array("remove", "career", "remove_cv_do"), array(array("cancel","section=career&function=manage_cvs")), "", "student:career:cv_store:remove_cv");
  }

  function remove_cv_do(&$waf, $user) 
  {
    remove_object_do($waf, User::get_id(), "CV", "section=career&function=manage_cvs");
  }

?>