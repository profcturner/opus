<?php

  /**
  * Home Menu for Administrators
  *
  * @package OPUS
  * @author Colin Turner <c.turner@ulster.ac.uk>
  * @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
  */


  /**
  * the main home page for admin users
  *
  * if the user is a super-admin, and the phonehome questions have not been answered
  * they will be directed (once only per session) to that screen instead
  *
  * @param uuwaf the web application framework object
  */
  function home(&$waf)
  {
    $waf->assign("user", $waf->user['opus']);
    require_once("model/User.class.php");

    // Check, is it a superuser?
    if(User::is_root())
    {
      // Do we need to ask about phone home?
      if(!isset($_SESSION['phonehome_asked']))
      {
        require_once("model/PhoneHome.class.php");
        if(PhoneHome::ask_later())
        {
          goto("superuser", "edit_phonehome");
        }
      }
    }
    $waf->display("main.tpl", "admin:home:home:home", "admin/home/home.tpl");
  }

  /**
  * displays the dialog to allow for a password change for the logged in user
  *
  * @param uuwaf the web application framework object
  */
  function change_password(&$waf)
  {
    $waf->display("main.tpl", "admin:home:change_password:change_password", "admin/home/change_password.tpl");
  }

  /**
  * attempts to process a change of password for the currently logged in user
  *
  * @param uuwaf the web application framework object
  */
  function change_password_do(&$waf)
  {
    $old_password      = WA::request("old_password");
    $new_password      = WA::request("new_password");
    $new_password_copy = WA::request("new_password_copy");

    require_once("model/User.class.php");
    $user = User::load_by_id(User::get_id());

    if(md5($old_password) != $user->password)
    {
      $error = true;
      $waf->assign("failed_old", true);
    }
    if($new_password != $new_password_copy)
    {
      $error = true;
      $waf->assign("failed_new_equal", true);
    }
    if(!test_password_strength($new_password))
    {
      $error = true;
      $waf->assign("failed_new_simple", true);
    }
    if($error)
    {
      change_password($waf);
      exit;
    }

    // Must be ok...
    $user->password = md5($new_password);
    $user->_update();
    goto("home", "home");
  }

  /**
  * a very simple test for password strength
  *
  * at the moment, is merely puts a limit on length, and requires a mixture of cases
  *
  * @param string $password
  * @return boolean true on a good password, false otherwise
  */
  function test_password_strength($password)
  {
    if(strlen($password) < 8) return false;
    if(preg_match("/^[a-z]$/", $password)) return false;
    if(preg_match("/^[A-Z]$/", $password)) return false;

    return true;
  }

  /**
  * shows recent company and vacancy activity
  *
  * by default the activity since the last login is shown, but an arbitrary number
  * of days can be requested.
  *
  * @param uuwaf the web application framework object
  */
  function company_activity(&$waf)
  {
    $days = (int) WA::request("days");

    if($days)
    {
      // Look for activity in the last few days
      $waf->assign("days", $days);
      $unixtime = time();
      $unixtime -= ($days * 24 * 60 * 60);
      $since = date("YmdHis", $unixtime);
    }
    else
    {
      // since the last login
      $last_login = $waf->user['opus']['last_login'];
      $pd = date_parse($last_login);
      $since = sprintf("%04u%02u%02u%02u%02u%02u", $pd['year'], $pd['month'], $pd['day'], $pd['hour'], $pd['minute'], $pd['second']);
    }
    $waf->assign("since", $since);

    require_once("model/Vacancy.class.php");
    require_once("model/Company.class.php");

    $vacancy = new Vacancy;
    $company = new Company;
    $vacancies_created = $vacancy->_get_all("where created > " . $since);
    $vacancies_modified = $vacancy->_get_all("where modified > " . $since);
    $companies_created = $company->_get_all("where created > " . $since);
    $companies_modified = $company->_get_all("where modified > " . $since);

    $vacancy_headings = array(
      'description'=>array('type'=>'text','size'=>30, 'header'=>true, 'listclass'=>'vacancy_description'),
      'company_id'=>array('type'=>'lookup', 'size'=>30, 'header'=>true, 'title'=>'Company Name'),
      'locality'=>array('type'=>'list','size'=>30, 'header'=>true, 'listclass'=>'vacancy_locality'),
      'status'=>array('type'=>'text','size'=>30, 'header'=>true, 'listclass'=>'vacancy_status')
    );

    $company_headings = array(
      'name'=>array('type'=>'text','size'=>30, 'header'=>true),
      'locality'=>array('type'=>'list','size'=>30, 'header'=>true)
    );

    $vacancy_actions = array(array('edit', 'edit_vacancy', 'directories'));
    $company_actions = array(array('edit', 'edit_company', 'directories'));

    $waf->assign("vacancies_created", $vacancies_created);
    $waf->assign("vacancies_modified", $vacancies_modified);
    $waf->assign("companies_created", $companies_created);
    $waf->assign("companies_modified", $companies_modified);
    $waf->assign("vacancy_headings", $vacancy_headings);
    $waf->assign("company_headings", $company_headings);
    $waf->assign("vacancy_actions", $vacancy_actions);
    $waf->assign("company_actions", $company_actions);
    $waf->assign("since", $since);

    $waf->display("main.tpl", "admin:home:company_activity:company_activity", "admin/home/company_activity.tpl");
  }

?>