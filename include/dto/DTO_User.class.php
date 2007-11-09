<?php

/**
* DTO handling for User
* @package OPUS
*/
require_once("dto/DTO.class.php");
/**
* DTO handling for User
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see User.class.php
* @package OPUS
*
*/

class DTO_User extends DTO 
{
  function __construct($handle = 'default') 
  {
    parent::__construct($handle);
  }

  /**
  * look for an entry with the supplied username and password
  *
  * @param string username the username supplied
  * @param string password the password supplied
  * @return the valid username on success, or false on failure
  */
  function _username_password($username, $password)
  {
    global $waf;
    $con = $waf->connections[$this->_handle]->con;

    try
    {
      $sql = $con->prepare("SELECT username FROM `user` WHERE username = ? AND password = ?;");
      $sql->execute(array($username, md5($password)));
      $results_row = $sql->fetch(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e)
    {
      $this->_log_sql_error($e, $class, "_username_password()");
    }
    if ($results_row) return ($results_row['username']); else return false;


    if ($sql->rowCount() == 0)
    {
      return false;
    }
    else
    {
      return ($results_row['username']);
    }
  }

  /**
  * look for an entry with the supplied reg_number and password
  *
  * @param string reg_number the reg_number supplied
  * @param string password the password supplied
  * @return the valid username on success, or false on failure
  */
  function _reg_number_password($reg_number, $password)
  {
    global $waf;
    $con = $waf->connections[$this->_handle]->con; 

    try
    {
      $sql = $con->prepare("SELECT username FROM `user` WHERE username = ? AND password = MD5(?);");
      $sql->execute(array($reg_number, $password));
      $results_row = $sql->fetch(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e)
    {
      $this->_log_sql_error($e, $class, "_reg_number_password()");
    }
    if ($results_row) return ($results_row->username); else return false;
  }
}

?>