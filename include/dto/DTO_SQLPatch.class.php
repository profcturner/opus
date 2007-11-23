<?php

/**
* DTO handling for SQLPatch
* @package OPUS
*/
require_once("dto/DTO.class.php");
/**
* DTO handling for SQLPatch
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see SQLPatch.class.php
* @package OPUS
*
*/

class DTO_SQLPatch extends DTO
{

  function __construct($handle='default') 
  {
    parent::__construct($handle);
  }

  function upgrade_3_to_4()
  {
    global $waf;
    $con = $waf->connections[$this->_handle]->con;

    if($waf->unattended) echo "upgrading database schema from version 3 to 4\n";
    $waf->log("upgrading database schema from version 3 to 4");

    // Copy student data across
    try
    {
      $sql = $con->prepare("SELECT * FROM `cv_pdetails`");
      $sql->execute();

      while ($results_row = $sql->fetch(PDO::FETCH_ASSOC))
      {
        // Copy to relevant tables
        $sql2 = $con->prepare("update user set salutation=?, firstname=?, lastname=?, reg_number=username, email=? where id=?");
        $sql2->execute(array($results_row['title'], $results_row['firstname'], $results_row['surname'], $results_row['email'], $results_row['id']));
        $sql2 = $con->prepare("update student set programme_id=? where user_id=?");
        $sql2->execute(array($results_row['course'], $results_row['id']));
      }
    }
    catch (PDOException $e)
    {
      if($waf->unattended) echo " error upgrading student information\n";
      $this->_log_sql_error($e, $class, "upgrade_3_to_4(student)");
    }

    // Copy admin data across
    try
    {
      $sql = $con->prepare("SELECT * FROM `admins`");
      $sql->execute();

      while ($results_row = $sql->fetch(PDO::FETCH_ASSOC))
      {
        // Copy to relevant tables
        $sql2 = $con->prepare("update user set salutation=?, firstname=?, lastname=?, email=?, reg_number=? where id=?");
        $sql2->execute(array($results_row['title'], $results_row['firstname'], $results_row['surname'], $results_row['email'], "e" . $results_row['staffno'], $results_row['user_id']));
        $sql2 = $con->prepare("insert into admin (position, voice, fax, signature, policy_id, user_id) values(?, ?, ?, ?, ?, ?)");
        $sql2->execute(array($results_row['position'], $results_row['voice'], $results_row['fax'], $results_row['signature'], $results_row['policy_id'], $results_row['user_id']));
      }
    }
    catch (PDOException $e)
    {
      if($waf->unattended) echo " error upgrading admin information\n";
      $this->_log_sql_error($e, $class, "upgrade_3_to_4(admin)");
    }

    echo "staff";
    // Copy staff data across
    try
    {
      $sql = $con->prepare("SELECT * FROM `staff`");
      $sql->execute();

      while ($results_row = $sql->fetch(PDO::FETCH_ASSOC))
      {
        // Copy to relevant tables
        $sql2 = $con->prepare("update user set salutation=?, firstname=?, lastname=?, email=?, reg_number=? where id=?");
        $sql2->execute(array($results_row['title'], $results_row['firstname'], $results_row['surname'], $results_row['email'], "e" . $results_row['staffno'], $results_row['user_id']));
      }
    }
    catch (PDOException $e)
    {
      if($waf->unattended) echo " error upgrading staff information\n";
      $this->_log_sql_error($e, $class, "upgrade_3_to_4(staff)");
    }

    echo "contacts";
    // Contacts
    try
    {
      $sql = $con->prepare("SELECT * FROM `contacts`");
      $sql->execute();

      while ($results_row = $sql->fetch(PDO::FETCH_ASSOC))
      {
        // Copy to relevant tables
        $sql2 = $con->prepare("update user set salutation=?, firstname=?, lastname=?, email=? where id=?");
        $sql2->execute(array($results_row['title'], $results_row['firstname'], $results_row['surname'], $results_row['email'], $results_row['user_id']));
        $sql2 = $con->prepare("insert into contact (position, voice, fax, user_id) values(?, ?, ?, ?");
        $sql2->execute(array($results_row['position'], $results_row['voice'], $results_row['fax'], $results_row['user_id']));

      }
    }
    catch (PDOException $e)
    {
      if($waf->unattended) echo " error upgrading contact information\n";
      $this->_log_sql_error($e, $class, "upgrade_3_to_4(contact)");
    }


    // Copy cvgroups

    //

    if($waf->unattended) echo "upgrade complete\n";
  }
}

?>