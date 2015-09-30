<?php

/**
* Configuration Menu for Administrators
*
* @package OPUS
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
*/


  // Resources

  function manage_resources($waf, $user, $title)
  {
    if(!Policy::check_default_policy("resource", "list")) $waf->halt("error:policy:permissions");
    $waf->log("resources listed", PEAR_LOG_NOTICE, 'general');

    $waf->assign("nopage", true);

    manage_objects($waf, $user, "Resource", array(array("add resource","section=configuration&function=add_resource","thickbox")), array(array('edit', 'edit_resource'), array('remove','remove_resource')), "get_all", array("where lookup NOT LIKE 'PRIVATE%'"), "admin:configuration:resources:manage_resources");
  }

  function add_resource($waf, $user) 
  {
    if(!Policy::check_default_policy("resource", "create")) //$waf->halt("error:policy:permissions");
	{
		$waf->display("popup.tpl", "error:policy:permissions", "error.tpl");
	}
	else
	{
		add_object($waf, $user, "Resource", array("add", "configuration", "add_resource_do"), array(array("cancel","section=configuration&function=manage_resources")), array(array("user_id",$user["user_id"])), "admin:configuration:resources:add_resource");
	}
  }

  function add_resource_do($waf, $user) 
  {
    if(!Policy::check_default_policy("resource", "create")) $waf->halt("error:policy:permissions");
    $waf->log("adding new resource");

    add_object_do($waf, $user, "Resource", "section=configuration&function=manage_resources", "add_resource");
  }

  function edit_resource($waf, $user) 
  {
    if(!Policy::check_default_policy("resource", "list")) //$waf->halt("error:policy:permissions");
    {
		$waf->display("popup.tpl", "error:policy:permissions", "error.tpl");
	}
	else
	{
		$waf->log("editing a resource");

		edit_object($waf, $user, "Resource", array("confirm", "configuration", "edit_resource_do"), array(array("cancel","section=configuration&function=manage_resources")), array(array("user_id",$user["user_id"])), "admin:configuration:resources:edit_resource");
	}
  }

  function edit_resource_do($waf, $user) 
  {
    if(!Policy::check_default_policy("resource", "edit")) $waf->halt("error:policy:permissions");
    $waf->log("editing a resource");

    edit_object_do($waf, $user, "Resource", "section=configuration&function=manage_resources", "edit_resource");
  }

  function remove_resource($waf, $user) 
  {
    if(!Policy::check_default_policy("resource", "delete")) //$waf->halt("error:policy:permissions");
    {
		$waf->display("popup.tpl", "error:policy:permissions", "error.tpl");
	}
	else
	{
		$waf->log("deleting a resource");

		remove_object($waf, $user, "Resource", array("remove", "configuration", "remove_resource_do"), array(array("cancel","section=configuration&function=manage_resources")), "", "admin:configuration:resources:remove_resource");
	}
  }

  function remove_resource_do($waf, $user) 
  {
    if(!Policy::check_default_policy("resource", "delete")) $waf->halt("error:policy:permissions");
    $waf->log("deleting a resource");

    remove_object_do($waf, $user, "Resource", "section=configuration&function=manage_resources");
  }


  // Organisation

  function organisation_details($waf, $user, $title) 
  {
    manage_faculties($waf, $user, $title);
  }

  // Faculties

  function manage_faculties($waf, $user, $title)
  {
    if(!Policy::check_default_policy("faculty", "list")) $waf->halt("error:policy:permissions");
    set_navigation_history($waf, "Faculties");

    $page = WA::request("page", true);

    manage_objects($waf, $user, "Faculty", array(array("add faculty","section=configuration&function=add_faculty","thickbox")), array(array('admins', 'manage_facultyadmins','no'), array('schools', 'manage_schools','no'), array('edit', 'edit_faculty'), array('remove','remove_faculty')), "get_all", array("", "order by name", $page), "admin:configuration:organisation_details:manage_faculties");
  }

  function add_faculty($waf, $user) 
  {
    if(!Policy::check_default_policy("faculty", "create")) //$waf->halt("error:policy:permissions");
	{
		$waf->display("popup.tpl", "error:policy:permissions", "error.tpl");
	}
	else
	{
		add_object($waf, $user, "Faculty", array("add", "configuration", "add_faculty_do"), array(array("cancel","section=configuration&function=manage_faculties")), array(array("user_id",$user["user_id"])), "admin:configuration:organisation_details:add_faculty");
	}
  }

  function add_faculty_do($waf, $user) 
  {
    if(!Policy::check_default_policy("faculty", "create")) $waf->halt("error:policy:permissions");

    add_object_do($waf, $user, "Faculty", "section=configuration&function=manage_faculties", "add_faculty");
  }

  function edit_faculty($waf, $user) 
  {
    if(!Policy::check_default_policy("faculty", "edit")) //$waf->halt("error:policy:permissions");
	{
		$waf->display("popup.tpl", "error:policy:permissions", "error.tpl");
	}
	else
	{
		edit_object($waf, $user, "Faculty", array("confirm", "configuration", "edit_faculty_do"), array(array("cancel","section=configuration&function=manage_faculties")), array(array("user_id",$user["user_id"])), "admin:configuration:organisation_details:edit_faculty");
	}
  }

  function edit_faculty_do($waf, $user) 
  {
    if(!Policy::check_default_policy("faculty", "edit")) $waf->halt("error:policy:permissions");

    edit_object_do($waf, $user, "Faculty", "section=configuration&function=manage_faculties", "edit_faculty");
  }

  function remove_faculty($waf, $user) 
  {
    if(!User::is_root()) //$waf->halt("error:policy:permissions");
	{
		$waf->display("popup.tpl", "error:policy:permissions", "error.tpl");
	}
	else
	{
		remove_object($waf, $user, "Faculty", array("remove", "configuration", "remove_faculty_do"), array(array("cancel","section=configuration&function=manage_faculties")), "", "admin:configuration:organisation_details:remove_faculty");
	}
  }

  function remove_faculty_do($waf, $user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    remove_object_do($waf, $user, "Faculty", "section=configuration&function=manage_faculties");
  }

  // Schools

  function manage_schools($waf, $user, $title)
  {
    if(!Policy::check_default_policy("school", "list")) $waf->halt("error:policy:permissions");

    $page = WA::request("page", true);

    $faculty_id = (int) WA::request("id", true);
    $_SESSION['faculty_id'] = $faculty_id;

    require_once("model/Faculty.class.php");
    $faculty = Faculty::load_by_id($faculty_id);

    add_navigation_history($waf, $faculty->name);

    manage_objects($waf, $user, "School", array(array("add school","section=configuration&function=add_school","thickbox")), array(array('admins', 'manage_schooladmins','no'), array('programmes', 'manage_programmes','no'), array('edit', 'edit_school'), array('remove','remove_school')), "get_all", array("where faculty_id=$faculty_id", "order by name", $page), "admin:configuration:organisation_details:manage_schools");
  }

  function add_school($waf, $user) 
  {
    if(!Policy::check_default_policy("school", "create")) //$waf->halt("error:policy:permissions");
	{
		$waf->display("popup.tpl", "error:policy:permissions", "error.tpl");
	}
	else
	{
		$faculty_id = (int) WA::request("id", true);

		// Make sure the school is set correctly
		$nvp_array["faculty_id"] = $faculty_id;
		$waf->assign("nvp_array", $nvp_array);

		add_object($waf, $user, "School", array("add", "configuration", "add_school_do"), array(array("cancel","section=configuration&function=manage_schools")), array(array("user_id",$user["user_id"])), "admin:configuration:organisation_details:add_school");
	}
  }

  function add_school_do($waf, $user) 
  {
    if(!Policy::check_default_policy("school", "create")) $waf->halt("error:policy:permissions");

    $faculty_id = (int) $_SESSION['faculty_id'];

    add_object_do($waf, $user, "School", "section=configuration&function=manage_schools&id=$faculty_id", "add_school");
  }

  function edit_school($waf, $user) 
  {
    if(!Policy::check_default_policy("school", "edit")) //$waf->halt("error:policy:permissions");
	{
		$waf->display("popup.tpl", "error:policy:permissions", "error.tpl");
	}
	else
	{
		$faculty_id = (int) WA::request("id", true);

		edit_object($waf, $user, "School", array("confirm", "configuration", "edit_school_do"), array(array("cancel","section=configuration&function=manage_schools")), array(array("user_id",$user["user_id"])), "admin:configuration:organisation_details:edit_school");
	}
  }

  function edit_school_do($waf, $user) 
  {
    if(!Policy::check_default_policy("school", "edit")) $waf->halt("error:policy:permissions");

    $faculty_id = (int) $_SESSION['faculty_id'];

    edit_object_do($waf, $user, "School", "section=configuration&function=manage_schools&id=$faculty_id", "edit_school");
  }

  function remove_school($waf, $user) 
  {
    if(!User::is_root()) //$waf->halt("error:policy:permissions");
	{
		$waf->display("popup.tpl", "error:policy:permissions", "error.tpl");
	}
	else
	{
		remove_object($waf, $user, "School", array("remove", "configuration", "remove_school_do"), array(array("cancel","section=configuration&function=manage_schools")), "", "admin:configuration:organisation_details:remove_school");
	}
  }

  function remove_school_do($waf, $user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    $faculty_id = (int) $_SESSION['faculty_id'];

    remove_object_do($waf, $user, "School", "section=configuration&function=manage_schools&id=$faculty_id");
  }

  // Faculty Admins

  function manage_facultyadmins($waf, $user, $title)
  {
    if(!Policy::check_default_policy("faculty", "list")) $waf->halt("error:policy:permissions");

    $faculty_id = (int) WA::request("id", true);

    require_once("model/Admin.class.php");
    $objects = Admin::get_all_by_faculty($faculty_id);

    $headings = array(
      'real_name'=>array('type'=>'text','size'=>30, 'header'=>true, title=>'Name'),
      '_level_policy_name'=>array('type'=>'text','size'=>30, 'header'=>true, 'title'=>'Policy'),
      'email'=>array('type'=>'email','size'=>40, 'header'=>true),
      'voice'=>array('type'=>'text','size'=>40, 'header'=>true, title=>'Phone')
    );
    $action_links = array(array('add faculty admin', "section=configuration&function=add_facultyadmin&faculty_id=$faculty_id","thickbox"));
    $actions = array(array('remove', "remove_facultyadmin&faculty_id=$faculty_id"));

    $waf->assign("actions", $actions);
    $waf->assign("action_links", $action_links);
    $waf->assign("headings", $headings);
    $waf->assign("objects", $objects);

    //add_navigation_history($waf, $faculty->name);
    $waf->display("main.tpl", "admin:configuration:organisation_details:manage_facultyadmins", "list.tpl");
  }

  function add_facultyadmin($waf)
  {
    if(!User::is_root()) //$waf->halt("error:policy:permissions");
	{
		$waf->display("popup.tpl", "error:policy:permissions", "error.tpl");
	}
	else
	{
		$faculty_id = (int) WA::request("faculty_id", true);

		require_once("model/Admin.class.php");
		require_once("model/Policy.class.php");

		// Don't pick up root users, they are irrelevant here.
		$admins = Admin::get_user_id_and_name("where user_type = 'admin'");
		$policies = Policy::get_id_and_field("name");
		$policies[0] = "Default for this Administrator";
		$function = "add_facultyadmin_do";

		$waf->assign("type", "faculty_id");
		$waf->assign("object_id", $faculty_id);
		$waf->assign("function", $function);
		$waf->assign("admins", $admins);
		$waf->assign("policies", $policies);

		$waf->display("popup.tpl", "admin:configuration:organisation_details:add_facultyadmin", "admin/configuration/add_level_admin.tpl");
	}
  }

  function add_facultyadmin_do($waf, $user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    add_object_do($waf, $user, "FacultyAdmin", "section=configuration&function=manage_facultyadmins", "add_facultyadmin");
  }

  function remove_facultyadmin($waf, $user) 
  {
    if(!User::is_root()) //$waf->halt("error:policy:permissions");
    {
		$waf->display("popup.tpl", "error:policy:permissions", "error.tpl");
	}
	else
	{
		// The inbound id is actually the admin id, not the id from the facultyadmin table
		require_once("model/Admin.class.php");
		$id = (int) WA::request("id");
		$faculty_id = (int) WA::request("faculty_id", true);
		$new_id = Admin::get_link_id_from_admin_and_level("faculty", $faculty_id, $id);

		// Ok, so now we inject the correct id back into place.
		$_REQUEST['id'] = $new_id;
		remove_object($waf, $user, "FacultyAdmin", array("remove", "configuration", "remove_facultyadmin_do"), array(array("cancel","section=configuration&function=manage_facultyadmins")), "", "admin:configuration:organisation_details:remove_facultyadmin");
	}
  }

  function remove_facultyadmin_do($waf, $user) 
  {
    $faculty_id = (int) WA::request("faculty_id", true);
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    remove_object_do($waf, $user, "FacultyAdmin", "section=configuration&function=manage_facultyadmins&faculty_id=$faculty_id");
  }

  // School Admins

  function manage_schooladmins($waf, $user, $title)
  {
    if(!Policy::check_default_policy("school", "list")) $waf->halt("error:policy:permissions");

    $school_id = (int) WA::request("id", true);

    require_once("model/Admin.class.php");
    $objects = Admin::get_all_by_school($school_id);

    $headings = array(
      'real_name'=>array('type'=>'text','size'=>30, 'header'=>true, title=>'Name'),
      '_level_policy_name'=>array('type'=>'text','size'=>30, 'header'=>true, 'title'=>'Policy'),
      'email'=>array('type'=>'email','size'=>40, 'header'=>true),
      'voice'=>array('type'=>'text','size'=>40, 'header'=>true, title=>'Phone')
    );
    $action_links = array(array('add school admin', "section=configuration&function=add_schooladmin&school_id=$school_id","thickbox"));
    $actions = array(array('remove', "remove_schooladmin&school_id=$school_id"));

    $waf->assign("actions", $actions);
    $waf->assign("action_links", $action_links);
    $waf->assign("headings", $headings);
    $waf->assign("objects", $objects);

    //add_navigation_history($waf, $faculty->name);
    $waf->display("main.tpl", "admin:configuration:organisation_details:manage_schooladmins", "list.tpl");
  }

  function add_schooladmin($waf)
  {
    if(!User::is_root()) //$waf->halt("error:policy:permissions");
	{
		$waf->display("popup.tpl", "error:policy:permissions", "error.tpl");
	}
	else
	{
		$school_id = (int) WA::request("school_id", true);

		require_once("model/Admin.class.php");
		require_once("model/Policy.class.php");

		// Don't pick up root users, they are irrelevant here.
		$admins = Admin::get_user_id_and_name("where user_type = 'admin'");
		$policies = Policy::get_id_and_field("name");
		$policies[0] = "Default for this Administrator";
		$function = "add_schooladmin_do";

		$waf->assign("type", "school_id");
		$waf->assign("object_id", $school_id);
		$waf->assign("function", $function);
		$waf->assign("admins", $admins);
		$waf->assign("policies", $policies);

		$waf->display("popup.tpl", "admin:configuration:organisation_details:add_schooladmin", "admin/configuration/add_level_admin.tpl");
	}
  }

  function add_schooladmin_do($waf, $user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    add_object_do($waf, $user, "SchoolAdmin", "section=configuration&function=manage_schooladmins", "add_schooladmin");
  }

  function remove_schooladmin($waf, $user) 
  {
    if(!User::is_root()) //$waf->halt("error:policy:permissions");
	{
		$waf->display("popup.tpl", "error:policy:permissions", "error.tpl");
	}
	else
	{
		// The inbound id is actually the admin id, not the id from the facultyadmin table
		require_once("model/Admin.class.php");
		$id = (int) WA::request("id");
		$school_id = (int) WA::request("school_id", true);
		$new_id = Admin::get_link_id_from_admin_and_level("school", $school_id, $id);

		// Ok, so now we inject the correct id back into place.
		$_REQUEST['id'] = $new_id;
		remove_object($waf, $user, "SchoolAdmin", array("remove", "configuration", "remove_schooladmin_do"), array(array("cancel","section=configuration&function=manage_schooladmins")), "", "admin:configuration:organisation_details:remove_schooladmin");
	}
  }

  function remove_schooladmin_do($waf, $user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");
    $school_id = (int) WA::request("school_id", true);

    remove_object_do($waf, $user, "SchoolAdmin", "section=configuration&function=manage_schooladmins&school_id=$school_id");
  }

  // Programme Admins

  function manage_programmeadmins($waf, $user, $title)
  {
    if(!Policy::check_default_policy("programme", "list")) $waf->halt("error:policy:permissions");

    $programme_id = (int) WA::request("id", true);

    require_once("model/Admin.class.php");
    $objects = Admin::get_all_by_programme($programme_id);

    $headings = array(
      'real_name'=>array('type'=>'text','size'=>30, 'header'=>true, title=>'Name'),
      '_level_policy_name'=>array('type'=>'text','size'=>30, 'header'=>true, 'title'=>'Policy'),
      'email'=>array('type'=>'email','size'=>40, 'header'=>true),
      'voice'=>array('type'=>'text','size'=>40, 'header'=>true, title=>'Phone')
    );
    $action_links = array(array('add programme admin', "section=configuration&function=add_programmeadmin&programme_id=$programme_id", "thickbox"));
    $actions = array(array('remove', "remove_programmeadmin&programme_id=$programme_id", "thickbox"));

    $waf->assign("actions", $actions);
    $waf->assign("action_links", $action_links);
    $waf->assign("headings", $headings);
    $waf->assign("objects", $objects);

    //add_navigation_history($waf, $faculty->name);
    $waf->display("main.tpl", "admin:configuration:organisation_details:manage_programmeadmins", "list.tpl");
  }

  function add_programmeadmin($waf)
  {
    if(!User::is_root()) //$waf->halt("error:policy:permissions");
	{
		$waf->display("popup.tpl", "error:policy:permissions", "error.tpl");
	}
	else
	{
		$programme_id = (int) WA::request("programme_id", true);

		require_once("model/Admin.class.php");
		require_once("model/Policy.class.php");

		// Don't pick up root users, they are irrelevant here.
		$admins = Admin::get_user_id_and_name("where user_type = 'admin'");
		$policies = Policy::get_id_and_field("name");
		$policies[0] = "Default for this Administrator";
		$function = "add_programmeadmin_do";

		$waf->assign("type", "programme_id");
		$waf->assign("object_id", $programme_id);
		$waf->assign("function", $function);
		$waf->assign("admins", $admins);
		$waf->assign("policies", $policies);

		$waf->display("popup.tpl", "admin:configuration:organisation_details:add_programmeadmin", "admin/configuration/add_level_admin.tpl");
	}
  }

  function add_programmeadmin_do($waf, $user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    add_object_do($waf, $user, "ProgrammeAdmin", "section=configuration&function=manage_programmeadmins", "add_programmeadmin");
  }

  function remove_programmeadmin($waf, $user) 
  {
    if(!User::is_root()) //$waf->halt("error:policy:permissions");
	{
		$waf->display("popup.tpl", "error:policy:permissions", "error.tpl");
	}
	else
	{
		// The inbound id is actually the admin id, not the id from the facultyadmin table
		require_once("model/Admin.class.php");
		$id = (int) WA::request("id");
		$programme_id = (int) WA::request("programme_id", true);
		$new_id = Admin::get_link_id_from_admin_and_level("programme", $programme_id, $id);

		// Ok, so now we inject the correct id back into place.
		$_REQUEST['id'] = $new_id;
		remove_object($waf, $user, "ProgrammeAdmin", array("remove", "configuration", "remove_programmeadmin_do"), array(array("cancel","section=configuration&function=manage_programmeadmins")), "", "admin:configuration:organisation_details:remove_programmeadmin");
	}
  }

  function remove_programmeadmin_do($waf, $user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");
    $programme_id = (int) WA::request("programme_id", true);

    remove_object_do($waf, $user, "ProgrammeAdmin", "section=configuration&function=manage_programmeadmins&programme_id=$programme_id");
  }

  // Programmes

  function manage_programmes($waf, $user, $title)
  {
    if(!Policy::check_default_policy("programme", "list")) $waf->halt("error:policy:permissions");

    add_navigation_history($waf, "Programmes");

    $page = WA::request("page", true);

    $school_id = (int) WA::request("id", true);
    $_SESSION['school_id'] = $school_id;

    require_once("model/School.class.php");
    $school = School::load_by_id($school_id);

    add_navigation_history($waf, $school->name);
    
    $other_actions = array(
    	array("add programme","section=configuration&function=add_programme","thickbox"),
    	array("bulk change assessment","section=configuration&function=bulk_change_assessment_group","thickbox")
		);


    manage_objects($waf, $user, "Programme", $other_actions, array(array('admins', 'manage_programmeadmins', 'no'), array('assessment', 'manage_assessmentgroupprogrammes', 'no'), array('edit', 'edit_programme'), array('remove','remove_programme')), "get_all", array("where school_id=$school_id", "order by name", $page), "admin:configuration:organisation_details:manage_programmes");
  }
  
  function bulk_change_assessment_group($waf, $user, $title)
  {
		if(!Policy::check_default_policy("assessmentgroup", "create")) //$waf->halt("error:policy:permissions");
		{
			$waf->display("popup.tpl", "error:policy:permissions", "error.tpl");
		}
		else
		{
			$school_id = (int) WA::request("school_id", true);
			require_once("model/Programme.class.php");
			require_once("model/AssessmentGroup.class.php");
			
			$assessmentgroups = AssessmentGroup::get_id_and_field("name");		
			$programmes = Programme::get_id_and_description("where school_id=" . $school_id);
			
			$waf->assign("assessmentgroups", $assessmentgroups);
			$waf->assign("programmes", $programmes);
			
			$waf->display("popup.tpl", "admin:configuration:organisation_details:bulk_change_assessment_group", "admin/configuration/bulk_change_assessment_group.tpl");		
		}
	}

  function bulk_change_assessment_group_do($waf, $user, $title)
  {
		if(!Policy::check_default_policy("assessmentgroup", "create")) $waf->halt("error:policy:permissions");
		
		$school_id = (int) WA::request("school_id", true);
		require_once("model/Programme.class.php");
		
		$new_group_id      = WA::request("new_group_id");
		$from_year         = WA::request("from_year");
		$programme_ids_raw = WA::request("programme_ids");
		$programme_ids = array();
		
		foreach($programme_ids_raw as $item)
		{
			foreach($item as $key => $value)
			{
				array_push($programme_ids, $value);
			}
		}
		
		require_once("model/AssessmentGroupProgramme.class.php");
		AssessmentGroupProgramme::bulk_change_assessment_group($programme_ids, $new_group_id, $from_year);
		goto_section("configuration", "manage_programmes&id=$school_id");

	}

  function add_programme($waf, $user) 
  {
    if(!Policy::check_default_policy("programme", "create")) //$waf->halt("error:policy:permissions");
	{
		$waf->display("popup.tpl", "error:policy:permissions", "error.tpl");
	}
	else
	{
		$school_id = (int) WA::request("id", true);

		add_navigation_history($waf, "Add Programme");

		// Make sure the school is set correctly
		$nvp_array["school_id"] = $school_id;
		$waf->assign("nvp_array", $nvp_array);

		add_object($waf, $user, "Programme", array("add", "configuration", "add_programme_do"), array(array("cancel","section=configuration&function=manage_programmes")), array(array("user_id",$user["user_id"])), "admin:configuration:organisation_details:add_programme");
	}
  }

  function add_programme_do($waf, $user) 
  {
    if(!Policy::check_default_policy("programme", "create")) $waf->halt("error:policy:permissions");

    $school_id = (int) $_SESSION['school_id'];

    add_object_do($waf, $user, "Programme", "section=configuration&function=manage_programmes&id=$school_id", "add_programme");
  }

  function edit_programme($waf, $user) 
  {
    if(!Policy::check_default_policy("programme", "edit")) //$waf->halt("error:policy:permissions");
	{
		$waf->display("popup.tpl", "error:policy:permissions", "error.tpl");
	}
	else
	{
		$school_id = (int) WA::request("id", true);
		$programme_id = (int) WA::request("id", true);
		require_once("model/Programme.class.php");
		
		add_navigation_history($waf, Programme::get_name($programme_id));

		edit_object($waf, $user, "Programme", array("confirm", "configuration", "edit_programme_do"), array(array("cancel","section=configuration&function=manage_programmes")), array(array("user_id",$user["user_id"])), "admin:configuration:organisation_details:edit_programme");
	}
  }

  function edit_programme_do($waf, $user) 
  {
    if(!Policy::check_default_policy("programme", "edit")) $waf->halt("error:policy:permissions");

    $school_id = (int) $_SESSION['school_id'];

    edit_object_do($waf, $user, "Programme", "section=configuration&function=manage_programmes&id=$school_id", "edit_programme");
  }

  function remove_programme($waf, $user) 
  {
    if(!User::is_root()) //$waf->halt("error:policy:permissions");
	{
		$waf->display("popup.tpl", "error:policy:permissions", "error.tpl");
	}
	else
	{
		remove_object($waf, $user, "Programme", array("remove", "configuration", "remove_programme_do"), array(array("cancel","section=configuration&function=manage_programmes")), "", "admin:configuration:organisation_details:remove_programme");
	}
  }

  function remove_programme_do($waf, $user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");

    $school_id = (int) $_SESSION['school_id'];

    remove_object_do($waf, $user, "Programme", "section=configuration&function=manage_programmes&id=$school_id");
  }

  // Assessmentgroups

  function manage_assessmentgroups($waf, $user, $title)
  {
    if(!Policy::check_default_policy("assessmentgroup", "list")) $waf->halt("error:policy:permissions");

    manage_objects($waf, $user, "AssessmentGroup", array(array("add assessment group","section=configuration&function=add_assessmentgroup","thickbox")), array(array('regime', 'manage_assessmentregimes','no'), array('edit', 'edit_assessmentgroup'), array('remove','remove_assessmentgroup')), "get_all", "", "admin:configuration:manage_assessmentgroups:manage_assessmentgroups");
  }

  function add_assessmentgroup($waf, $user) 
  {
    if(!Policy::check_default_policy("assessmentgroup", "create")) //$waf->halt("error:policy:permissions");
	{
		$waf->display("popup.tpl", "error:policy:permissions", "error.tpl");
	}
	else
	{
		add_object($waf, $user, "AssessmentGroup", array("add", "configuration", "add_assessmentgroup_do"), array(array("cancel","section=configuration&function=manage_assessmentgroups")), array(array("user_id",$user["user_id"])), "admin:configuration:manage_assessmentgroups:add_assessmentgroup");
	}
  }

  function add_assessmentgroup_do($waf, $user) 
  {
    if(!Policy::check_default_policy("assessmentgroup", "create")) $waf->halt("error:policy:permissions");

    add_object_do($waf, $user, "AssessmentGroup", "section=configuration&function=manage_assessmentgroups", "add_assessmentgroup");
  }

  function edit_assessmentgroup($waf, $user) 
  {
    if(!Policy::check_default_policy("assessmentgroup", "edit")) //$waf->halt("error:policy:permissions");
	{
		$waf->display("popup.tpl", "error:policy:permissions", "error.tpl");
	}
	else
	{
		edit_object($waf, $user, "AssessmentGroup", array("confirm", "configuration", "edit_assessmentgroup_do"), array(array("cancel","section=configuration&function=manage_assessmentgroups")), array(array("user_id",$user["user_id"])), "admin:configuration:manage_assessmentgroups:edit_assessmentgroup");
	}
  }

  function edit_assessmentgroup_do($waf, $user) 
  {
    if(!Policy::check_default_policy("assessmentgroup", "edit")) $waf->halt("error:policy:permissions");

    edit_object_do($waf, $user, "AssessmentGroup", "section=configuration&function=manage_assessmentgroups", "edit_assessmentgroup");
  }

  function remove_assessmentgroup($waf, $user) 
  {
    if(!Policy::check_default_policy("assessmentgroup", "delete")) //$waf->halt("error:policy:permissions");
	{
		$waf->display("popup.tpl", "error:policy:permissions", "error.tpl");
	}
	else
	{
		remove_object($waf, $user, "AssessmentGroup", array("remove", "configuration", "remove_assessmentgroup_do"), array(array("cancel","section=configuration&function=manage_assessmentgroups")), "", "admin:configuration:manage_assessmentgroups:remove_assessmentgroup");
	}
  }

  function remove_assessmentgroup_do($waf, $user) 
  {
    if(!Policy::check_default_policy("assessmentgroup", "delete")) $waf->halt("error:policy:permissions");

    remove_object_do($waf, $user, "AssessmentGroup", "section=configuration&function=manage_assessmentgroups");
  }

  // AssessmentRegimes

  function manage_assessmentregimes($waf, $user, $title)
  {
    if(!Policy::check_default_policy("assessmentgroup", "list")) $waf->halt("error:policy:permissions");

    $page = (int) WA::request("page", true);
    $group_id = (int) WA::request('group_id');
    if(empty($group_id)) $group_id = (int) WA::request('id');
    $_SESSION['group_id'] = $group_id;

    manage_objects($waf, $user, "AssessmentRegime", array(array("add assessment regime","section=configuration&function=add_assessmentregime","thickbox")), array(array('edit', 'edit_assessmentregime'), array('remove','remove_assessmentregime')), "get_all", array("where group_id=$group_id", "ORDER BY student_description", $page), "admin:configuration:manage_assessmentgroups:manage_assessmentregimes");
  }

  function add_assessmentregime($waf, $user) 
  {
    if(!User::is_root()) //$waf->halt("error:policy:permissions");
	{
		$waf->display("popup.tpl", "error:policy:permissions", "error.tpl");
	}
	else
	{
		$group_id = (int) WA::request('group_id', true);

		add_object($waf, $user, "AssessmentRegime", array("add", "configuration", "add_assessmentregime_do"), array(array("cancel","section=configuration&function=manage_assessmentregimes")), array(array("user_id",$user["user_id"]), array("group_id", $group_id)), "admin:configuration:manage_assessmentgroups:add_assessmentregime");
	}
  }

  function add_assessmentregime_do($waf, $user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");
    $group_id = (int) WA::request('group_id', true);

    add_object_do($waf, $user, "AssessmentRegime", "section=configuration&function=manage_assessmentregimes&id={$group_id}", "add_assessmentregime");
  }

  function edit_assessmentregime($waf, $user) 
  {
    if(!User::is_root()) //$waf->halt("error:policy:permissions");
	{
		$waf->display("popup.tpl", "error:policy:permissions", "error.tpl");
	}
	else
	{
		$group_id = (int) WA::request('group_id', true);

		edit_object($waf, $user, "AssessmentRegime", array("confirm", "configuration", "edit_assessmentregime_do"), array(array("cancel","section=configuration&function=manage_assessmentregimes")), array(array("user_id",$user["user_id"]), array("group_id", $group_id)), "admin:configuration:manage_assessmentgroups:edit_assessmentregime");
	}
  }

  function edit_assessmentregime_do($waf, $user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");
    $group_id = (int) WA::request('group_id', true);

    edit_object_do($waf, $user, "AssessmentRegime", "section=configuration&function=manage_assessmentregimes&id={$group_id}", "edit_assessmentregime");
  }

  function remove_assessmentregime($waf, $user) 
  {
    $group_id = (int) WA::request('group_id', true);

    remove_object($waf, $user, "AssessmentRegime", array("remove", "configuration", "remove_assessmentregime_do"), array(array("cancel","section=configuration&function=manage_assessmentregimes&id={$group_id}")), "", "admin:configuration:manage_assessmentgroups:remove_assessmentregime");
  }

  function remove_assessmentregime_do($waf, $user) 
  {
    if(!User::is_root()) $waf->halt("error:policy:permissions");
    
    $group_id = (int) WA::request('group_id', true);

    remove_object_do($waf, $user, "AssessmentRegime", "section=configuration&function=manage_assessmentregimes&id={$group_id}");
  }

  // AssessmentGroupProgrammes

  function manage_assessmentgroupprogrammes($waf, $user, $title)
  {
    if(!Policy::check_default_policy("assessmentgroup", "list")) $waf->halt("error:policy:permissions");

    $programme_id = (int) WA::request("id", true);
    require_once("model/Programme.class.php");

    add_navigation_history($waf, Programme::get_name($programme_id));

    manage_objects($waf, $user, "AssessmentGroupProgramme", array(array("add programme","section=configuration&function=add_assessmentgroupprogramme", "thickbox")), array(array('edit', 'edit_assessmentgroupprogramme'), array('remove','remove_assessmentgroupprogramme')), "get_all", array("where programme_id=$programme_id", "", $page), "admin:configuration:organisation_details:manage_assessmentgroupprogrammes");
  }

  function add_assessmentgroupprogramme($waf, $user) 
  {
    if(!Policy::check_default_policy("assessmentgroup", "create")) //$waf->halt("error:policy:permissions");
	{
		$waf->display("popup.tpl", "error:policy:permissions", "error.tpl");
	}
	else
	{
		$programme_id = (int) WA::request("id", true);

		add_object($waf, $user, "AssessmentGroupProgramme", array("add", "configuration", "add_assessmentgroupprogramme_do"), array(array("cancel","section=configuration&function=manage_assessmentgroupprogrammes")), array(array("user_id",$user["user_id"]), array("programme_id", $programme_id)), "admin:configuration:organisation_details:add_assessmentgroupprogramme");
	}
  }

  function add_assessmentgroupprogramme_do($waf, $user) 
  {
    if(!Policy::check_default_policy("assessmentgroup", "create")) $waf->halt("error:policy:permissions");

    add_object_do($waf, $user, "AssessmentGroupProgramme", "section=configuration&function=manage_assessmentgroupprogrammes", "add_assessmentgroupprogramme");
  }

  function edit_assessmentgroupprogramme($waf, $user) 
  {
    if(!Policy::check_default_policy("assessmentgroup", "create")) //$waf->halt("error:policy:permissions");
	{
		$waf->display("popup.tpl", "error:policy:permissions", "error.tpl");
	}
	else
	{
		$programme_id = (int) WA::request("id", true);

		edit_object($waf, $user, "AssessmentGroupProgramme", array("confirm", "configuration", "edit_assessmentgroupprogramme_do"), array(array("cancel","section=configuration&function=manage_assessmentgroupprogrammes")), array(array("user_id",$user["user_id"]), array("programme_id", $programme_id)), "admin:configuration:organisation_details:edit_assessmentgroupprogramme");
	}
  }

  function edit_assessmentgroupprogramme_do($waf, $user) 
  {
    if(!Policy::check_default_policy("assessmentgroup", "create")) $waf->halt("error:policy:permissions");

    edit_object_do($waf, $user, "AssessmentGroupProgramme", "section=configuration&function=manage_assessmentgroupprogrammes", "edit_assessmentgroupprogramme");
  }

  function remove_assessmentgroupprogramme($waf, $user) 
  {
    if(!Policy::check_default_policy("assessmentgroup", "create")) //$waf->halt("error:policy:permissions");
	{
		$waf->display("popup.tpl", "error:policy:permissions", "error.tpl");
	}
	else
	{
		remove_object($waf, $user, "AssessmentGroupProgramme", array("remove", "configuration", "remove_assessmentgroupprogramme_do"), array(array("cancel","section=configuration&function=manage_assessmentgroupprogrammes")), "", "admin:configuration:organisation_details:remove_assessmentgroupprogramme");
	}
  }

  function remove_assessmentgroupprogramme_do($waf, $user) 
  {
    if(!Policy::check_default_policy("assessmentgroup", "create")) $waf->halt("error:policy:permissions");

    remove_object_do($waf, $user, "AssessmentGroupProgramme", "section=configuration&function=manage_assessmentgroupprogrammes");
  }


  // CVGroups

  function manage_cvgroups($waf, $user, $title)
  {
    if(!Policy::check_default_policy("cvgroup", "list")) $waf->halt("error:policy:permissions");

    require_once("model/PDSystem.class.php");

    $actions = array(array('edit', 'edit_cvgroup'), array('remove','remove_cvgroup'));
    if(PDSystem::exists())
    {
      array_push($actions, array('templates', 'manage_cvgroup_templates'));
    }

    manage_objects($waf, $user, "CVGroup", array(array("add CV group","section=configuration&function=add_cvgroup","thickbox")), $actions , "get_all", "", "admin:configuration:manage_cvgroups:manage_cvgroups");
  }

  function add_cvgroup($waf, $user) 
  {
    if(!Policy::check_default_policy("cvgroup", "create")) //$waf->halt("error:policy:permissions");
	{
		$waf->display("popup.tpl", "error:policy:permissions", "error.tpl");
	}
	else
	{
		add_object($waf, $user, "CVGroup", array("add", "configuration", "add_cvgroup_do"), array(array("cancel","section=configuration&function=manage_cvgroups")), array(array("user_id",$user["user_id"])), "admin:configuration:manage_cvgroups:add_cvgroup");
	}
  }

  function add_cvgroup_do($waf, $user) 
  {
    if(!Policy::check_default_policy("cvgroup", "create")) $waf->halt("error:policy:permissions");

    add_object_do($waf, $user, "CVGroup", "section=configuration&function=manage_cvgroups", "add_cvgroup");
  }

  function edit_cvgroup($waf, $user) 
  {
    if(!Policy::check_default_policy("cvgroup", "edit")) //$waf->halt("error:policy:permissions");
	{
		$waf->display("popup.tpl", "error:policy:permissions", "error.tpl");
	}
	else
	{
		edit_object($waf, $user, "CVGroup", array("confirm", "configuration", "edit_cvgroup_do"), array(array("cancel","section=configuration&function=manage_cvgroups")), array(array("user_id",$user["user_id"])), "admin:configuration:manage_cvgroups:edit_cvgroup");
	}
  }

  function edit_cvgroup_do($waf, $user) 
  {
    if(!Policy::check_default_policy("cvgroup", "edit")) $waf->halt("error:policy:permissions");

    edit_object_do($waf, $user, "CVGroup", "section=configuration&function=manage_cvgroups", "edit_cvgroup");
  }

  function remove_cvgroup($waf, $user) 
  {
    if(!Policy::check_default_policy("cvgroup", "delete")) //$waf->halt("error:policy:permissions");
	{
		$waf->display("popup.tpl", "error:policy:permissions", "error.tpl");
	}
	else
	{
		remove_object($waf, $user, "CVGroup", array("remove", "configuration", "remove_cvgroup_do"), array(array("cancel","section=configuration&function=manage_cvgroups")), "", "admin:configuration:manage_cvgroups:remove_cvgroup");
	}
  }

  function remove_cvgroup_do($waf, $user) 
  {
    if(!Policy::check_default_policy("cvgroup", "delete")) $waf->halt("error:policy:permissions");

    remove_object_do($waf, $user, "CVGroup", "section=configuration&function=manage_cvgroups");
  }

  function manage_cvgroup_templates($waf, $user, $title)
  {
    if(!Policy::check_default_policy("cvgroup", "edit")) $waf->halt("error:policy:permissions");

    $group_id = (int) WA::request("id");

    require_once("model/CVGroup.class.php");
    $group_info = CVGroup::load_by_id($group_id);

    require_once("model/PDSystem.class.php");

    // Get possible PDSystem templates
    $pdp_templates = PDSystem::get_cv_templates();

    // Get current permissions for this group
    require_once("model/CVGroupTemplate.class.php");
    $cvgrouptemplates = CVGroupTemplate::get_all("where group_id=$group_id");

    // Assemble permissions from these
    $opus_permissions = array();
    foreach($cvgrouptemplates as $template)
    {
      $opus_permission = array();

      // Check if it is allowed
      if(strpos($template->settings, "allow") === false)
      {
        $opus_permission['allow'] = false;
      }
      else
      {
        $opus_permission['allow'] = true;
      }

      // Check if approval is required
      if(strpos($template->settings, "requiresApproval") === false)
      {
        $opus_permission['requiresApproval'] = false;
      }
      else
      {
        $opus_permission['requiresApproval'] = true;
      }
      $opus_permissions[$template->template_id] = $opus_permission;
    }
    $waf->assign("action_links", array(array("cancel","section=configuration&function=manage_cvgroups")));
    $waf->assign("group_info", $group_info);
    $waf->assign("pdp_templates", $pdp_templates);
    $waf->assign("opus_permissions", $opus_permissions);

    $waf->display("main.tpl", "admin:configuration:manage_cvgroups:manage_cvgroup_templates", "admin/configuration/manage_cvgroup_templates.tpl");
  }

  function manage_cvgroup_templates_do($waf, $user, $title)
  {
    if(!Policy::check_default_policy("cvgroup", "edit")) $waf->halt("error:policy:permissions");

    $group_id = (int) WA::request("group_id");
    $allowed = WA::request("allowed");
    $approval = WA::request("approval");
    $default_template = WA::request("default_template");

    // If nothing is selected, arrays don't exist
    if(empty($allowed)) $allowed = array();
    if(empty($approval)) $approval = array();

    // Nuke current permissions
    require_once("model/CVGroupTemplate.class.php");
    CVGroupTemplate::remove_by_group($group_id);

    // Make new adjustments
    foreach($allowed as $template_id)
    {
      $fields = array();
      $fields['group_id'] = $group_id;
      $fields['template_id'] = $template_id;
      if(in_array($template_id, $approval))
      {
        $fields['settings']='allow,requiresApproval';
      }
      else
      {
        $fields['settings']='allow';
      }
      CVGroupTemplate::insert($fields);
    }

    require_once("model/CVGroup.class.php");
    $cvgroup = CVGroup::load_by_id($group_id);
    $cvgroup->default_template = $default_template;
    $cvgroup->_update();
    goto_section("configuration", "manage_cvgroups");
  }



  // Help

  function manage_help($waf, $user, $title)
  {
    $page = WA::request("page", true);

    if(!Policy::check_default_policy("help", "list")) $waf->halt("error:policy:permissions");

    manage_objects($waf, $user, "Help", array(array("add help","section=configuration&function=add_help","thickbox")), array(array('edit', 'edit_help', 'no'), array('remove','remove_help')), "get_all", array("", "order by lookup, channel_id", $page), "admin:configuration:manage_help:manage_help");
  }

  function add_help($waf, $user) 
  {
    if(!Policy::check_default_policy("help", "create")) //$waf->halt("error:policy:permissions");
	{
		$waf->display("popup.tpl", "error:policy:permissions", "error.tpl");
	}
	else
	{
		$waf->assign("xinha_editor", true);
		add_object($waf, $user, "Help", array("add", "configuration", "add_help_do"), array(array("cancel","section=configuration&function=manage_help")), array(array("user_id",$user["user_id"])), "admin:configuration:manage_help:add_help");
	}
  }

  function add_help_do($waf, $user) 
  {
    if(!Policy::check_default_policy("help", "create")) $waf->halt("error:policy:permissions");

    add_object_do($waf, $user, "Help", "section=configuration&function=manage_help", "add_help");
  }

  function edit_help($waf, $user) 
  {
    if(!Policy::check_default_policy("help", "edit")) //$waf->halt("error:policy:permissions");
	{
		$waf->display("popup.tpl", "error:policy:permissions", "error.tpl");
	}
	else
	{
		$waf->assign("xinha_editor", true);
		edit_object($waf, $user, "Help", array("confirm", "configuration", "edit_help_do"), array(array("cancel","section=configuration&function=manage_help")), array(array("user_id",$user["user_id"])), "admin:configuration:manage_help:edit_help", "admin/configuration/edit_help.tpl");
	}
  }

  function edit_help_do($waf, $user) 
  {
    if(!Policy::check_default_policy("help", "edit")) $waf->halt("error:policy:permissions");

    edit_object_do($waf, $user, "Help", "section=configuration&function=manage_help", "edit_help");
  }

  function remove_help($waf, $user) 
  {
    if(!Policy::check_default_policy("help", "delete")) //$waf->halt("error:policy:permissions");
	{
		$waf->display("popup.tpl", "error:policy:permissions", "error.tpl");
	}
	else
	{
		remove_object($waf, $user, "Help", array("remove", "configuration", "remove_help_do"), array(array("cancel","section=configuration&function=manage_help")), "", "admin:configuration:manage_help:remove_help");
	}
  }

  function remove_help_do($waf, $user) 
  {
    if(!Policy::check_default_policy("help", "delete")) $waf->halt("error:policy:permissions");

    remove_object_do($waf, $user, "Help", "section=configuration&function=manage_help");
  }

  function import_data($waf, $user)
  {
    import_students($waf, $user);
  }

  function import_students($waf)
  {
    if(!Policy::check_default_policy("student", "create")) $waf->halt("error:policy:permissions");

    global $config_sensitive;

    if(!empty($config_sensitive['ws']['url'])) $waf->assign("ws_enabled", true);
    else $waf->assign("ws_enabled", false);

    // Normally, we are doing this for students on placement next year
    $year = get_academic_year()+1;

    require_once("model/CSVMapping.class.php");
    $csvmappings = CSVMapping::get_id_and_field("name");

    require_once("model/Programme.class.php");
    $programmes = Programme::get_id_and_description();

    $waf->assign("year", $year);
    $waf->assign("programmes", $programmes);
    $waf->assign("csvmappings", $csvmappings);

    $waf->display("main.tpl", "admin:configuration:import_data:import_students", "admin/configuration/import_students_form.tpl");
  }

  function import_students_do($waf)
  {
    if(!Policy::check_default_policy("student", "create")) $waf->halt("error:policy:permissions");

    $password       = $_REQUEST['password'];
    $programme_id   = (int) $_REQUEST['programme_id'];
    $year           = (int) $_REQUEST['year'];
    $status         = $_REQUEST['status'];
    $test           = $_REQUEST['test'];
    $onlyyear       = (int) $_REQUEST['onlyyear'];
    $csvmapping_id  = (int) $_REQUEST['csvmapping_id'];

    require_once("model/StudentImport.class.php");
    if(strlen($_FILES['userfile']['tmp_name']))
    {
      StudentImport::import_csv($_FILES['userfile']['tmp_name'], $programme_id, $year, $status, $onlyyear, $password, $test, $csvmapping_id);
    }
    else
    {
      StudentImport::import_programme_via_SRS($programme_id, $year, $status, $onlyyear, $password, $test);
    }

    $waf->display("main.tpl", "admin:configuration:import_data:import_students_srs", "admin/configuration/import_students_srs.tpl");
  }

?>
