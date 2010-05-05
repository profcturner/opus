<?php

  /**
  * Directory Menu for Administrators
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

  // Timelines

  function display_timeline(&$waf, &$user)
  {
    $student_id = (int) WA::request("student_id");
    require_once("model/Timeline.class.php");

    if(!Policy::is_auth_for_student($student_id, "student", "viewStatus")) $waf->halt("error:policy:permissions");

    Timeline::display_timeline($student_id);
  }

  // Photos

  function display_photo(&$waf, &$user)
  {
    $username = WA::request("username");
    $fullsize = WA::request("fullsize");
    require_once("model/Photo.class.php");

    Photo::display_photo($username, $fullsize);
  }

  // Vacancies

  function vacancy_directory(&$waf, $user, $title)
  {
    require_once("model/Activitytype.class.php");
    require_once("model/Vacancytype.class.php");
    $activity_types = Activitytype::get_id_and_field("name");
    $vacancy_types = Vacancytype::get_id_and_field("name");
    $sort_types = array("name" => 'Name', "locality" => 'Locality', "closedate" => 'Closing date');
    $other_options = array("ShowClosed" => "Show Closed");
    //, "ShowCompanies" => "Show Companies", "ShowVacancies" => "Show Vacancies");

    require_once("model/Preference.class.php");
    $form_options = Preference::get_preference("vacancy_directory_form");

    $waf->assign("sort_types", $sort_types);
    $waf->assign("activity_types", $activity_types);
    $waf->assign("vacancy_types", $vacancy_types);
    $waf->assign("other_options", $other_options);
    $waf->assign("form_options", $form_options);

    $waf->display("main.tpl", "staff:directories:vacancy_directory:vacancy_directory", "admin/directories/vacancy_directory.tpl");
  }

  function search_vacancies(&$waf, $user, $title)
  {
    $search = WA::request("search");
    $year = WA::request("year");
    $activities = WA::request("activities");
    $vacancy_types = WA::request("vacancy_types");
    $sort = WA::request("sort");
    $other_options = WA::request("other_options");

    $form_options['search'] = $search;
    $form_options['year'] = $year;
    $form_options['activities'] = $activities;
    $form_options['vacancy_types'] = $vacancy_types;
    $form_options['sort'] = $sort;
    $form_options['other_options'] = $other_options;

    require_once("model/Preference.class.php");
    Preference::set_preference("vacancy_directory_form", $form_options);

    require_once("model/Vacancy.class.php");
    $waf->assign("vacancies", Vacancy::get_all_extended($search, $year, $activities, $vacancy_types, $sort, $other_options));
    $waf->assign("activities", $activities);
    $waf->assign("vacancy_types", $vacancy_types);

    $waf->display("main.tpl", "admin:directories:vacancy_directory:search_vacancies", "staff/directories/search_vacancies.tpl");
  }

  function company_directory(&$waf, $user, $title)
  {
    require_once("model/Activitytype.class.php");
    $activity_types = Activitytype::get_id_and_field("name");
    $sort_types = array("name", "locality");

    require_once("model/Preference.class.php");
    $form_options = Preference::get_preference("company_directory_form");

    $waf->assign("sort_types", $sort_types);
    $waf->assign("activity_types", $activity_types);
    $waf->assign("form_options", $form_options);

    $waf->display("main.tpl", "staff:directories:company_directory:company_directory", "admin/directories/company_directory.tpl");
  }

  function search_companies(&$waf, $user, $title)
  {
    $search = WA::request("search");
    $activities = WA::request("activities");
    $sort = WA::request("sort");

    $form_options['search'] = $search;
    $form_options['activities'] = $activities;
    $form_options['sort'] = $sort;

    require_once("model/Preference.class.php");
    Preference::set_preference("company_directory_form", $form_options);

    require_once("model/Company.class.php");
    // A simplification, doesn't honour switches yet...
    $waf->assign("companies", Company::get_all_extended($search, $activities, $sort));
    $waf->display("main.tpl", "admin:directories:company_directory:search_companies", "staff/directories/search_companies.tpl");
  }

  function view_company(&$waf, &$user)
  {
    $id = (int) WA::request("company_id");

    $action_links = array(array("edit", "section=directories&function=edit_company&id=$id"));

    require_once("model/Company.class.php");
    $company = Company::load_by_id($id);

    // Make "recent" menu entry
    $company_name = $company->name;
    $_SESSION['lastitems']->add_here("c:$company_name", "c:$id", "Company: $company_name");

    // Some lookups
    require_once("model/Activitytype.class.php");
    $company_activity_names = array();
    foreach($company->activity_types as $activity_type)
    {
      array_push($company_activity_names, Activitytype::get_name($activity_type));
    }

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
    $company_id = (int) WA::request("company_id", true);

    require_once("model/Vacancy.class.php");
    $objects = Vacancy::get_all("where company_id=$company_id", "order by year(jobstart), status");
    require_once("model/Application.class.php");
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
    $waf->assign("actions", $actions);
    $waf->assign("action_links", array(array("add","section=directories&function=add_vacancy&company_id=$company_id"), array("edit company", "section=directories&function=edit_company&id=$company_id")));

    $waf->display("main.tpl", "admin:directories:vacancies:manage_vacancies", "list.tpl");
  }

  function view_vacancy(&$waf, &$user)
  {
    $id = (int) WA::request("id");
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
    $action_links = array(array("view company", "section=directories&function=view_company&id=$company_id"));

    $waf->assign("action_links", $action_links);
    $waf->assign("vacancy", $vacancy);
    $waf->assign("company", $company);
    $waf->assign("vacancy_activity_names", $vacancy_activity_names);
    $waf->assign("company_activity_names", $company_activity_names);
    $waf->assign("show_heading", true);

    $waf->display("main.tpl", "admin:directories:vacancy_directory:view_vacancy", "admin/directories/view_vacancy.tpl");
  }

  // Admin

  function manage_admins(&$waf, $user, $title)
  {
    require_once("model/Admin.class.php");

    $admin_objects = Admin::get_all("where user_type = 'admin'");
    $root_objects  = Admin::get_all("where user_type = 'root'");

    $admin_headings = Admin::get_admin_list_headings();
    $root_headings = Admin::get_root_list_headings();

    $waf->assign("root_headings", $root_headings);
    $waf->assign("admin_headings", $admin_headings);
    $waf->assign("admin_objects", $admin_objects);
    $waf->assign("root_objects", $root_objects);
    $waf->assign("actions", $actions);

    $waf->display("main.tpl", "staff:directories:admin_directory:admin_directory", "admin/directories/list_admins.tpl");
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

  function reset_password(&$waf)
  {
    $user_id = (int) WA::request("user_id");
    $error_function = WA::request("error_function");

    require_once("model/User.class.php");
    $success = User::reset_password($user_id);

    if($success || empty($error_function))
    {
      header("location:" . $_SERVER['HTTP_REFERER'] . "&changes=true");
    }
    else
    {
      goto_section("directories", "$error_function");
    }
  }

?>