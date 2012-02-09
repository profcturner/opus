<?php

/**
* Handling for the System Status page
* @package OPUS
*/

/**
* This class encapsulates the required handling for the system status page
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/
class SystemStatus
{
  function get_root_users()
  {
    require_once("model/Admin.class.php");
    return(Admin::get_all("where user_type='root'", "order by last_time desc"));
  }

  function get_root_headings()
  {
    return array(
      'username'=>array('type'=>'text','size'=>30, 'header'=>true),
      'real_name'=>array('type'=>'text','size'=>30, 'header'=>true, 'title'=>'Name'),
      'position'=>array('type'=>'text','size'=>30, 'header'=>true),
      'online'=>array('type'=>'list','values'=>array('no','yes'), 'header'=>true),
      'last_time'=>array('type'=>'text','size'=>30, 'header'=>true, 'title'=>'Last Access')
    );
  }

  function get_admin_users($max_users)
  {
    require_once("model/Admin.class.php");
    return(Admin::get_all("where user_type='admin'", "order by last_time desc", 0, $max_users));
  }

  function get_admin_headings()
  {
    return array(
      'username'=>array('type'=>'text','size'=>30, 'header'=>true),
      'real_name'=>array('type'=>'text','size'=>30, 'header'=>true, 'title'=>'Name'),
      'policy_id'=>array('type'=>'lookup', 'object'=>'policy', 'value'=>'name', 'title'=>'Policy', 'var'=>'policies', 'header'=>true),
      'position'=>array('type'=>'text','size'=>30, 'header'=>true),
      'online'=>array('type'=>'list','values'=>array('no','yes'), 'header'=>true),
      'last_time'=>array('type'=>'text','size'=>30, 'header'=>true, 'title'=>'Last Access')
    );
  }

  function get_admin_actions()
  {
    return array(
      array('edit', 'edit_admin', 'directories', 'no')
    );
  }

  function get_student_users($max_users)
  {
    require_once("model/Student.class.php");
    require_once("model/Programme.class.php");

    $student = new Student;
    $students = $student->_get_all("", "order by last_time desc", 0, $max_users);
    for($loop = 0; $loop < count($students); $loop++)
    {
      $students[$loop]->_programme_id = Programme::get_name($students[$loop]->programme_id);
    }
    return($students);
  }

  function get_student_headings()
  {
    return array(
      'username'=>array('type'=>'text','size'=>30, 'header'=>true),
      'real_name'=>array('type'=>'text','size'=>30, 'header'=>true, 'title'=>'Name'),
      'programme_id'=>array('type'=>'lookup', 'object'=>'programme', 'value'=>'name', 'title'=>'Programme', 'header'=>true),
      'online'=>array('type'=>'list','values'=>array('no','yes'), 'header'=>true),
      'last_time'=>array('type'=>'text','size'=>30, 'header'=>true, 'title'=>'Last Access')
    );
  }

  function get_student_actions()
  {
    return array(
      array('edit', 'edit_student', 'directories', 'no')
    );
  }

  function get_contact_users($max_users)
  {
    require_once("model/Contact.class.php");

    $contact = new Contact;
    $contacts = $contact->_get_all("", "order by last_time desc", 0, $max_users);
    for($loop = 0; $loop < count($contacts); $loop++)
    {
      $companies = Contact::get_companies_for_contact($contacts[$loop]->user_id);
      $companies_field = "";
      foreach($companies as $company_id => $company_name)
      {
        $companies_field .= "<a href=\"?section=directories&function=edit_company&id=$company_id\">$company_name</a>\n";
      }
      // trim off last character (superfluous \n)
      $companies_field = substr($companies_field, 0, -1);
      $contacts[$loop]->companies = $companies_field;
    }
    return($contacts);
  }

  function get_contact_headings()
  {
    return array(
      'username'=>array('type'=>'text','size'=>30, 'header'=>true),
      'real_name'=>array('type'=>'text','size'=>30, 'header'=>true, 'title'=>'Name'),
      'companies'=>array('type'=>'text','size'=>30, 'header'=>true, 'title'=>'Companies'),
      'online'=>array('type'=>'list','values'=>array('no','yes'), 'header'=>true),
      'last_time'=>array('type'=>'text','size'=>30, 'header'=>true, 'title'=>'Last Access')
    );
  }

  function get_contact_actions()
  {
    return array(
      array('edit', 'edit_contact', 'directories', 'no')
    );
  }

  function get_staff_users($max_users)
  {
    require_once("model/Staff.class.php");

    $staff = new Staff;
    $staff_members = $staff->_get_all("", "order by last_time desc", 0, $max_users);
    return($staff_members);
  }

  function get_staff_headings()
  {
    return array(
      'username'=>array('type'=>'text','size'=>30, 'header'=>true),
      'real_name'=>array('type'=>'text','size'=>30, 'header'=>true, 'title'=>'Name'),
      'school_id'=>array('type'=>'lookup', 'object'=>'school', 'value'=>'name', 'title'=>'School', 'size'=>20, 'var'=>'schools', 'header'=>true),
      'online'=>array('type'=>'list','values'=>array('no','yes'), 'header'=>true),
      'last_time'=>array('type'=>'text','size'=>30, 'header'=>true, 'title'=>'Last Access')
    );
  }

  function get_staff_actions()
  {
    return array(
      array('edit', 'edit_staff', 'directories', 'no')
    );
  }

  function get_supervisor_users($max_users)
  {
    require_once("model/Supervisor.class.php");

    $supervisor = new Supervisor;
    $supervisors = $supervisor->_get_all("", "order by last_time desc", 0, $max_users);
    return($supervisors);
  }

  function get_supervisor_headings()
  {
    return array(
      'username'=>array('type'=>'text','size'=>30, 'header'=>true),
      'real_name'=>array('type'=>'text','size'=>30, 'header'=>true, 'title'=>'Name'),
      'company_id'=>array('type'=>'lookup','size'=>30, 'header'=>true, 'title'=>'Company', 'link'=>array('url'=>'?section=directories&function=edit_company')),
      'vacancy_id'=>array('type'=>'lookup','size'=>30, 'header'=>true, 'title'=>'Vacancy', 'link'=>array('url'=>'?section=directories&function=edit_vacancy')),
      'student_id'=>array('type'=>'lookup','size'=>30, 'header'=>true, 'title'=>'Student', 'link'=>array('url'=>'?section=directories&function=edit_student')),
      'online'=>array('type'=>'list','values'=>array('no','yes'), 'header'=>true),
      'last_time'=>array('type'=>'text','size'=>30, 'header'=>true, 'title'=>'Last Access')
    );
  }

  function get_supervisor_actions()
  {
    return array(
      array('edit', 'edit_supervisor', 'directories', 'no')
    );
  }


}

?>
