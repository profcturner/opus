<?php

  /**
  * Directory Menu for Company Contacts
  *
  * @package OPUS
  * @author Colin Turner <c.turner@ulster.ac.uk>
  * @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
  */

  function mass_email(&$waf)
  {
    $users = WA::request('users');
    $message = WA::request('message');
    $cc_me = WA::request('CC');
    $subject = WA::request('subject');
    $redirect_url = WA::request("redirect_url");

    $valid_recipients = array();
    $invalid_recipients = array();

    if(!empty($message) && count($users))
    {
      require_once("model/User.class.php");
      $sender_details = User::load_by_id(User::get_id());

      $sender_email =
        $sender_details->real_name . " <" . $sender_details->email . ">";

      $to_email =
        "Undisclosed Recipients <" . $sender_details->email . ">";

      $extra = "From: $sender_email\r\n";
      if($cc_me) $extra .= "Cc: $sender_email\r\n";

      $bcc = "bcc: ";

      foreach($users as $user_id)
      {
        $user = User::load_by_id($user_id);

        $user_email = $user->real_name . " <" . $user->email . ">";

        // Do we in fact, have an email address to send to?
        if(strlen($user->email))
        {
          $bcc .= $user_email . ", ";
          array_push($valid_recipients, $user);
        }
        else
        {
          array_push($invalid_recipients, $user);
        }
      }

      // Trim extra comma off
      $bcc = substr($bcc, 0, -2) . "\r\n";
      $extra .= $bcc;

      require_once("model/OPUSMail.class.php");

      $new_mail = new OPUSMail($to_email, $subject, $message, $extra);
      $new_mail->send();
    }
    else
    {
      $waf->assign("invalid_email", true);
      // No message, or no users selected
    }
    $waf->assign("action_links", array(array('done', $redirect_url)));
    $waf->assign("valid_recipients", $valid_recipients);
    $waf->assign("invalid_recipients", $invalid_recipients);
    $waf->display("main.tpl", "admin:directories:mass_email:mass_email", "admin/directories/mass_email.tpl");
  }


  // CVs

  // Photos

  function display_photo(&$waf, &$user)
  {
    $username = WA::request("username");
    $fullsize = WA::request("fullsize");
    require_once("model/Photo.class.php");

    Photo::display_photo($username, $fullsize);
  }

  // Company

  function edit_company(&$waf, &$user)
  {
    require_once("model/Contact.class.php");

    // Put company in session to "pick it up"
    $id = $_SESSION['company_id'] = WA::request("id");
    if(!Contact::is_auth_for_company($id)) $waf->die("error:contact:not_your_company");

    require_once("model/Company.class.php");
    $company_name = Company::get_name($id);
    $_SESSION['lastitems']->add_here("c:$company_name", "c:$id", "Company: $company_name");

    goto("directories", "edit_company_real&id=$id");
  }

  function edit_company_real(&$waf, &$user) 
  {
    $id = WA::request("id");

    require_once("model/Contact.class.php");
    if(!Contact::is_auth_for_company($id)) $waf->die("error:contact:not_your_company");

    edit_object($waf, $user, "Company", array("confirm", "directories", "edit_company_do"), array(array("cancel","section=directories&function=company_directory"), array("contacts", "section=directories&function=manage_contacts&company_id=$id"), array("vacancies", "section=directories&function=manage_vacancies&company_id=$id&page=1"), array("notes", "section=directories&function=list_notes&object_type=Company&object_id=$id")), array(array("user_id",$user["user_id"])), "company:my_company:edit_company:edit_company");
  }

  function edit_company_do(&$waf, &$user) 
  {
    require_once("model/Contact.class.php");
    if(!Contact::is_auth_for_company(WA::request('id'))) $waf->die("error:contact:not_your_company");

    edit_object_do($waf, $user, "Company", "section=home&function=home", "edit_company");
  }

  function view_company(&$waf, &$user)
  {
    $company_id = (int) WA::request("company_id");

    require_once("model/Contact.class.php");
    if(!Contact::is_auth_for_company($company_id)) $waf->die("error:contact:not_your_company");

    if($_SESSION['company_id'] != $company_id)
    {
      // If this company isn't the active one, make it so
      $company_id = (int) WA::request("company_id", true);
      goto("directories", "view_company&company_id=$company_id");
    }

    $action_links = array(array("edit", "section=directories&function=edit_company&id=$company_id"));

    require_once("model/Company.class.php");
    $company = Company::load_by_id($company_id);

    // Make "recent" menu entry
    $company_name = $company->name;
    $_SESSION['lastitems']->add_here("c:$company_name", "c:$company_id", "Company: $company_name");

    // Some lookups
    require_once("model/Activitytype.class.php");
    $company_activity_names = array();
    foreach($company->activity_types as $activity_type)
    {
      array_push($company_activity_names, Activitytype::get_name($activity_type));
    }

    require_once("model/Resource.class.php");
    $resources = Resource::get_all("where company_id=$company_id");
    $resource_headings = Resource::get_field_defs("company");
    $resource_actions = array(array("view", "view_company_resource", "directories"));

    $waf->assign("resources", $resources);
    $waf->assign("resource_headings", $resource_headings);
    $waf->assign("resource_actions", $resource_actions);
    $waf->assign("action_links", $action_links);
    $waf->assign("company", $company);
    $waf->assign("company_activity_names", $company_activity_names);

    $waf->display("main.tpl", "admin:directories:vacancy_directory:view_company", "admin/directories/view_company.tpl");
  }

  /**
  * manages vacancies for a specific company
  */
  function manage_vacancies(&$waf, $user, $title)
  {
    $page = (int) WA::request("page", true);

    $company_id = (int) WA::request("company_id");
    if($company_id && ($_SESSION['company_id'] != $company_id))
    {
      // If this company isn't the active one, make it so
      $company_id = (int) WA::request("company_id", true);
      goto("directories", "manage_vacancies&company_id=$company_id&page=$page");
    }
    require_once("model/Contact.class.php");
    if(!Contact::is_auth_for_company($company_id)) $waf->die("error:contact:not_your_company");

    require_once("model/Vacancy.class.php");
    $objects = Vacancy::get_all("where company_id=$company_id", "order by year(jobstart) DESC, status, description", $page);
    require_once("model/Application.class.php");
    $object_num = Vacancy::count("where company_id=$company_id");
    for($loop = 0; $loop < count($objects); $loop++)
    {
      $objects[$loop]->startyear = substr($objects[$loop]->jobstart, 0, 4);
      $objects[$loop]->applicants = Application::count("where vacancy_id=" . $objects[$loop]->id);
    }

    $headings = array(
      'description'=>array('type'=>'text', 'size'=>30, 'maxsize'=>100, 'title'=>'Job Description','header'=>true),
      'closedate'=>array('type'=>'text', 'header'=>true),
      'startyear'=>array('type'=>'text', 'header'=>true),
      'applicants'=>array('type'=>'text', 'header'=>true),
      'status'=>array('type'=>'list', 'list'=>array("open", "closed", "special"), 'header'=>true)
    );

    $actions = array(array('edit', 'edit_vacancy'), array('applicants', 'manage_applicants'), array('clone', 'clone_vacancy'), array('remove','remove_vacancy'));

    $waf->assign("headings", $headings);
    $waf->assign("objects", $objects);
    $waf->assign("object_num", $object_num);
    $waf->assign("actions", $actions);
    $waf->assign("action_links", array(array("add","section=directories&function=add_vacancy&company_id=$company_id"), array("edit company", "section=directories&function=edit_company&id=$company_id")));

    $waf->display("main.tpl", "admin:directories:vacancies:manage_vacancies", "list.tpl");
  }

  function add_vacancy(&$waf, &$user) 
  {
    $company_id = (int) WA::request("company_id", true);
    require_once("model/Contact.class.php");
    if(!Contact::is_auth_for_company($company_id)) $waf->die("error:contact:not_your_company");

    require_once("model/Company.class.php");
    $company = new Company;
    $company = $company->load_by_id($company_id);

    $existing_nvp_array = $waf->get_template_vars("nvp_array");
    if(!strlen($existing_nvp_array['locality']))
    {
      foreach(array("address1", "address2", "address3", "postcode", "locality", "town", "country") as $field)
      {
        $nvp_array[$field] = $company->$field;
      }
      $waf->assign("nvp_array", $nvp_array);
    }

    add_object($waf, $user, "Vacancy", array("add", "directories", "add_vacancy_do"), array(array("cancel","section=directories&function=manage_vacancies")), array(array("company_id", $company_id), array("user_id",$user["user_id"])), "admin:directories:vacancies:add_vacancy");
  }

  /**
  * @todo activities don't copy across, manage.tpl needs changed.
  */
  function clone_vacancy(&$waf, &$user) 
  {
    $company_id = (int) WA::request("company_id", true);
    $id = (int) WA::request("id");
    require_once("model/Contact.class.php");
    if(!Contact::is_auth_for_company($company_id)) $waf->die("error:contact:not_your_company");

    require_once("model/Vacancy.class.php");
    $vacancy = new Vacancy;
    $vacancy = $vacancy->load_by_id($id);

    $copy_fields = array_merge(Vacancy::get_fields(), Vacancy::get_extended_fields());
    foreach($copy_fields as $field)
    {
      $nvp_array[$field] = $vacancy->$field;
    }
    $waf->assign("nvp_array", $nvp_array);

    add_object($waf, $user, "Vacancy", array("add", "directories", "add_vacancy_do"), array(array("cancel","section=directories&function=manage_vacancies")), array(array("company_id", $company_id), array("user_id",$user["user_id"])), "admin:directories:vacancies:clone_vacancy");
  }

  function add_vacancy_do(&$waf, &$user) 
  {
    $company_id = (int) WA::request("company_id", true);
    require_once("model/Contact.class.php");
    if(!Contact::is_auth_for_company($company_id)) $waf->die("error:contact:not_your_company");

    add_object_do($waf, $user, "Vacancy", "section=directories&function=manage_vacancies&company_id=$company_id", "add_vacancy");
  }

  function edit_vacancy(&$waf, &$user) 
  {
    $id = (int) WA::request("id");
    require_once("model/Contact.class.php");
    if(!Contact::is_auth_for_vacancy($id)) $waf->die("error:contact:not_your_company");

    // Make a "recent" menu item
    require_once("model/Vacancy.class.php");
    $vacancy_desc = Vacancy::get_name($id);
    $_SESSION['lastitems']->add_here("v:$vacancy_desc", "v:$id", "Vacancy: $vacancy_desc");

    $company_id = (int) WA::request("company_id", true);
    $waf->assign("xinha_editor", true);

    edit_object($waf, $user, "Vacancy", array("confirm", "directories", "edit_vacancy_do"), array(array("cancel","section=directories&function=manage_vacancies"), array("view","section=directories&function=view_vacancy&id=$id")), array(array("company_id", $company_id), array("user_id",$user["user_id"])), "admin:directories:vacancy_directory:edit_vacancy", "admin/directories/edit_vacancy.tpl");
  }

  function edit_vacancy_do(&$waf, &$user) 
  {
    $company_id = (int) WA::request("company_id", true);
    require_once("model/Contact.class.php");
    if(!Contact::is_auth_for_company($company_id)) $waf->die("error:contact:not_your_company");

    edit_object_do($waf, $user, "Vacancy", "section=directories&function=manage_vacancies&company_id=$company_id", "edit_vacancy");
  }


  function view_vacancy(&$waf, &$user)
  {
    $id = (int) WA::request("id");
    require_once("model/Contact.class.php");
    if(!Contact::is_auth_for_vacancy($id)) $waf->die("error:contact:not_your_company");

    $student_id = $_SESSION["student_id"];

    require_once("model/Vacancy.class.php");
    $vacancy = Vacancy::load_by_id($id);

    // Make a "recent" menu item
    $vacancy_desc = $vacancy->description;
    $_SESSION['lastitems']->add_here("v:$vacancy_desc", "v:$id", "Vacancy: $vacancy_desc");

    // Some lookups
    require_once("model/Activitytype.class.php");
    $vacancy_activity_names = array();
    foreach($vacancy->activity_types as $activity_type)
    {
      array_push($vacancy_activity_names, Activitytype::get_name($activity_type));
    }

    require_once("model/Company.class.php");
    $company = new Company;
    $company = Company::load_by_id($vacancy->company_id);

    $company_activity_names = array();
    foreach($company->activity_types as $activity_type)
    {
      array_push($company_activity_names, Activitytype::get_name($activity_type));
    }

    $company_id = $vacancy->company_id;
    $action_links = array(array("edit", "section=directories&function=edit_vacancy&id=$id"), array("edit company", "section=directories&function=edit_company&id=$company_id"));
    if($student_id)
    {
      array_push($action_links, array("apply with student", "section=directories&function=add_application&id=$id"));
    }

    require_once("model/Resource.class.php");
    $resources = Resource::get_all("where company_id=" . $vacancy->company_id);
    $resource_headings = Resource::get_field_defs("company");
    $resource_actions = array(array("view", "view_company_resource", "directories"));

    $waf->assign("action_links", $action_links);
    $waf->assign("vacancy", $vacancy);
    $waf->assign("company", $company);
    $waf->assign("resources", $resources);
    $waf->assign("resource_headings", $resource_headings);
    $waf->assign("resource_actions", $resource_actions);
    $waf->assign("vacancy_activity_names", $vacancy_activity_names);
    $waf->assign("company_activity_names", $company_activity_names);
    $waf->assign("show_heading", true);

    $waf->display("main.tpl", "admin:directories:vacancy_directory:view_vacancy", "admin/directories/view_vacancy.tpl");
  }

  // Manage Applicants

  function manage_applicants(&$waf, $user, $title)
  {
    $vacancy_id = (int) WA::request("id");
    require_once("model/Vacancy.class.php");

    require_once("model/Contact.class.php");
    if(!Contact::is_auth_for_vacancy($vacancy_id)) $waf->die("error:contact:not_your_company");

    $vacancy = Vacancy::load_by_id($vacancy_id);
    $waf->log("applicants for vacancy " . $vacancy->description . "(" . $vacancy->_company_id . ") viewed");

    require_once("model/Application.class.php");
    $possible_status = array('unseen','seen','invited to interview','missed interview','offered','unsuccessful');
    $all_applications = Application::get_all_triaged($vacancy_id);
    $waf->assign("placed", $all_applications[0]);
    $waf->assign("available", $all_applications[1]);
    $waf->assign("unavailable", $all_applications[2]);
    $waf->assign("status_values", $possible_status);
    $waf->assign("vacancy_id", $vacancy_id);
    $waf->display("main.tpl", "admin:directories:vacancy_directory:manage_applicants", "admin/directories/manage_applicants.tpl");
  }

  function manage_applicants_do(&$waf)
  {
    $status = WA::request("status");                // New status array
    $old_status = WA::request("old_status");        // Original status array
    $send = WA::request("send");                    // Array of "send" checkboxes
    $vacancy_id = (int) WA::request("vacancy_id");
    require_once("model/Vacancy.class.php");
    $vacancy = Vacancy::load_by_id($vacancy_id);
    $waf->assign("vacancy", $vacancy);

    $action_links = array(array("back", "section=directories&function=manage_applicants&id=$vacancy_id"));

    // Array of student ids for which status is changed
    $status_changes = array();
    foreach($old_status as $key => $value)
    {
      if($status[$key] != $value)
      {
        require_once("model/Application.class.php");
        $application = Application::load_where("where vacancy_id = $vacancy_id and student_id=" . (int) $key);
        $fields = array();
        $fields['id'] = (int) $application->id;
        $fields['status'] = $status[$key];
        Application::update($fields);
        array_push($status_changes, $key);
        $student_names[$key] = User::get_name($key);
      }
    }

    // Check if CVs were requested
    if(!empty($send))
    {
      require_once("model/CVCombined.class.php");
      // Send CVs via email
      foreach($send as $student_id)
      {
        // Send me the combined CV
        require_once("model/Application.class.php");
        $application = Application::load_where("where vacancy_id = $vacancy_id and student_id=" . (int) $student_id);
        CVCombined::email_cv(User::get_id(), $application->id);
      }
    }

    // Check if changes were made to status, if so offer up a dialog
    if(count($status_changes))
    {
      $waf->log("status changes were made to applicants on vacancy " . Vacancy::get_name($vacancy_id));
      $waf->assign("action_links", $action_links);
      $waf->assign("old_status", $old_status);
      $waf->assign("student_name", $student_names);
      $waf->assign("status", $status);
      $waf->assign("status_changes", $status_changes);
      $waf->assign("vacancy_id", $vacancy_id);
      $waf->display("main.tpl", "admin:directories:vacancy_directory:message_applicants", "admin/directories/message_applicants.tpl");
    }
    else
    {
      // No changes, back to same screen
      goto("directories", "manage_applicants&id=$vacancy_id");
    }
  }

  function view_cv_by_application(&$waf)
  {
    $application_id = WA::request("application_id");

    // security is handled in the model layer
    require_once("model/CVCombined.class.php");
    CVCombined::view_cv_for_application($application_id);
  }

  function view_cover_by_application(&$waf)
  {
    require_once("model/CVCombined.class.php");
    require_once("model/Application.class.php");

    $application_id = WA::request("application_id");
    $application = Application::load_by_id($application_id);

    $action_links = array(array("fetch cv", "section=directories&function=view_cv_by_application&application_id=$application_id"), array("back to applications", "section=directories&function=manage_applicants&id=" .$application->vacancy_id));

    // Security is same as for CV
    if(!CVCombined::is_auth_to_view_cv($application->cv_ident, $application->student_id))
    {
      $waf->halt("error:cv:not_authorised");
    }
    $waf->assign("action_links", $action_links);
    $waf->assign("letter", $application->cover);

    $waf->display("main.tpl", "admin:directories:vacancy_directory:manage_applicants", "admin/directories/view_cover_letter.tpl");
  }




























  // Placements


  // Contacts


  function manage_contacts(&$waf, $user, $title)
  {
    $company_id = (int) WA::request("company_id", true);
    require_once("model/Contact.class.php");
    if(!Contact::is_auth_for_company($company_id)) $waf->die("error:contact:not_your_company");

    if($company_id && ($_SESSION['company_id'] != $company_id))
    {
      // If this company isn't the active one, make it so
      $company_id = (int) WA::request("company_id", true);
      goto("directories", "manage_contacts&company_id=$company_id");
    }
    require_once("model/Contact.class.php");

    if($company_id)
    {
      $objects = Contact::get_all_by_company($company_id);

      $headings = array(
        'real_name'=>array('type'=>'text','size'=>30, 'header'=>true, title=>'Name'),
        'position'=>array('type'=>'list','size'=>30, 'header'=>true, title=>'Position'),
        'email'=>array('type'=>'email','size'=>40, 'header'=>true),
        'voice'=>array('type'=>'text','size'=>40, 'header'=>true, title=>'Phone'),
      );
      $actions = array(array('edit', 'edit_contact'));

      $waf->assign("headings", $headings);
      $waf->assign("objects", $objects);
      $waf->assign("actions", $actions);
    }
    $waf->display("main.tpl", "admin:directories:contact_directory:company_contacts", "list.tpl");
  }

  function edit_contact(&$waf, &$user) 
  {

    require_once("model/Contact.class.php");
    $id = WA::request("id");
    $contact = Contact::load_by_id($id);

    if($contact->user_id != User::get_id()) $waf->halt("error:policy:not_your_account");
    $changes = WA::request("changes");
    $waf->assign("changes", $changes);

    edit_object($waf, $user, "Contact", array("confirm", "directories", "edit_contact_do"), array(array("cancel","section=directories&function=manage_contacts")), array(array("user_id", $contact->user_id)), "admin:directories:contact_directory:edit_contact", "admin/directories/edit_contact.tpl");
  }

  function edit_contact_do(&$waf, &$user) 
  {
    require_once("model/Contact.class.php");
    $id = WA::request("id");
    $contact = Contact::load_by_id($id);

    if($contact->user_id != User::get_id()) $waf->halt("error:policy:not_your_account");

    edit_object_do($waf, $user, "Contact", "section=directories&function=manage_contacts", "edit_contact");
  }



  // Notes

  /**
  * lists all notes associated with a given item
  */
  function list_notes(&$waf, &$user)
  {
    $object_type = WA::request("object_type");
    $object_id = (int) WA::request("object_id");

    $action_links = array(array("add", "section=directories&function=add_note&object_type=$object_type&object_id=$object_id"));
    require_once("model/Note.class.php");
    $notes = Note::get_all_by_links($object_type, $object_id);
    $waf->assign("notes", $notes);
    $waf->assign("action_links", $action_links);

    $waf->display("main.tpl", "admin:directories:list_notes:list_notes", "admin/directories/search_notes.tpl");
  }

  /**
  * views a specific note
  * @todo show other linked items
  * @todo modify referer code to allow cleanurls
  */
  function view_note(&$waf, &$user)
  {
    $note_id = (int) WA::request("id");

    // Because notes are accessed from all over the place, we don't know where
    // to go back to. So, try and get the referring URL
    if(preg_match("/^.*?(section=.*)$/", $_SERVER['HTTP_REFERER'], $matches))
    {
      $action_links = array(array("back", $matches[1]));
      $waf->assign("action_links", $action_links);
    }
    require_once("model/Note.class.php");
    require_once("model/Notelink.class.php");

    $note = Note::load_by_id($note_id);
    $note_links = Notelink::get_all("where note_id=$note_id");

    $waf->assign("note", $note);
    $waf->assign("note_links", $note_links);

    $waf->display("main.tpl", "admin:directories:list_notes:view_note", "admin/directories/view_note.tpl");
  }

  function add_note(&$waf, &$user) 
  {
    $object_type = WA::request("object_type");
    $object_id = WA::request("object_id");

    $mainlink = $object_type . "_" . $object_id;

    // Get any inbound variables (validation fail), and make the default auth all
    $nvp_array = $waf->get_template_vars("nvp_array");
    if(!strlen($nvp_array['all']))
    {
      $nvp_array['auth'] = 'all';
      $waf->assign("nvp_array", $nvp_array);
    }

    add_object($waf, $user, "Note", array("add", "directories", "add_note_do"), array(array("cancel","section=directories&function=list_notes&object_type=$object_type&object_id=$object_id")), array(array("mainlink", $mainlink)), "admin:directories:list_notes:add_note", "admin/directories/add_note.tpl");
  }

  function add_note_do(&$waf, &$user) 
  {
    $mainlink = WA::request("mainlink");
    $parts = explode("_", $mainlink);
    $object_type = $parts[0];
    $object_id = $parts[1];

    add_object_do($waf, $user, "Note", "section=directories&function=list_notes&object_type=$object_type&object_id=$object_id", "add_note");
  }

  // Company / Vacancy resources

  function manage_company_resources(&$waf)
  {
    if(empty($_SESSION['company_id']))
    {
      // If this company isn't already the active one, make it so
      $company_id = (int) WA::request("company_id", true);
      goto("directories", "manage_company_resources&company_id=$company_id");
    }
    $company_id = (int) WA::request("company_id", true);
    require_once("model/Contact.class.php");
    if(!Contact::is_auth_for_company($company_id)) $waf->die("error:contact:not_your_company");

    // Ignore pagination for complex reasons
    $waf->assign("nopage", true);

    manage_objects($waf, $user, "Resource", array(array("add","section=directories&function=add_company_resource&company_id=$company_id")), array(array('view', 'view_company_resource'), array('edit', 'edit_company_resource'), array('remove','remove_company_resource')), "get_all", array("where company_id=$company_id", "", $page), "contact:directories:manage_company_resources:manage_company_resources", "list.tpl", "company");
  }

  function view_company_resource(&$waf, &$user)
  {
    $id = (int) $_REQUEST["id"];
    require_once("model/Resource.class.php");

    Resource::view($id); 
  }

  function add_company_resource(&$waf, &$user) 
  {
    $company_id = (int) WA::request("company_id", true);
    require_once("model/Contact.class.php");
    if(!Contact::is_auth_for_company($company_id)) $waf->die("error:contact:not_your_company");

    add_object($waf, $user, "Resource", array("add", "directories", "add_company_resource_do"), array(array("cancel","section=directories&function=manage_company_resources")), array(array("company_id", $company_id), array("lookup", "PRIVATE"), array("auth", "all"), array("channel_id", 0)), "admin:configuration:resources:add_resource", "manage.tpl", "", "company");
  }

  function add_company_resource_do(&$waf, &$user) 
  {
    $company_id = (int) WA::request("company_id", true);
    require_once("model/Contact.class.php");
    if(!Contact::is_auth_for_company($company_id)) $waf->die("error:contact:not_your_company");

    add_object_do($waf, $user, "Resource", "section=directories&function=manage_company_resources&company_id=$company_id", "add_company_resource");
  }

  function edit_company_resource(&$waf, &$user) 
  {
    $company_id = (int) WA::request("company_id", true);
    require_once("model/Contact.class.php");
    if(!Contact::is_auth_for_company($company_id)) $waf->die("error:contact:not_your_company");

    edit_object($waf, $user, "Resource", array("confirm", "directories", "edit_company_resource_do"), array(array("cancel","section=directories&function=manage_company_resources")), array(array("company_id", $company_id), array("lookup", "PRIVATE"), array("auth", "all"), array("channel_id", 0)), "admin:configuration:resources:edit_resource", "manage.tpl", "", "company");
  }

  function edit_company_resource_do(&$waf, &$user) 
  {
    $company_id = (int) WA::request("company_id", true);
    require_once("model/Contact.class.php");
    if(!Contact::is_auth_for_company($company_id)) $waf->die("error:contact:not_your_company");

    edit_object_do($waf, $user, "Resource", "section=directories&function=manage_company_resources", "edit_company_resource");
  }

  function remove_company_resource(&$waf, &$user) 
  {
    $company_id = (int) WA::request("company_id", true);
    require_once("model/Contact.class.php");
    if(!Contact::is_auth_for_company($company_id)) $waf->die("error:contact:not_your_company");

    remove_object($waf, $user, "Resource", array("remove", "directories", "remove_company_resource_do"), array(array("cancel","section=directories&function=manage_company_resources&company_id=$company_id")), "", "admin:configuration:resources:remove_resource");
  }

  function remove_company_resource_do(&$waf, &$user) 
  {
    $company_id = (int) WA::request("company_id", true);
    require_once("model/Contact.class.php");
    if(!Contact::is_auth_for_company($company_id)) $waf->die("error:contact:not_your_company");

    remove_object_do($waf, $user, "Resource", "section=directories&function=manage_company_resources&company_id=$company_id");
  }



  function reset_password(&$waf)
  {
    $user_id = (int) WA::request("user_id");
    $error_function = WA::request("error_function");
    $done_function = WA::request("done_function");

    require_once("model/User.class.php");
    $success = User::reset_password($user_id);

    if($success || empty($error_function))
    {
      if(!empty($done_function)) goto("directories", "$done_function");
      else header("location:" . $_SERVER['HTTP_REFERER'] . "&changes=true");
    }
    else
    {
      goto("directories", "$error_function");
    }
  }

?>