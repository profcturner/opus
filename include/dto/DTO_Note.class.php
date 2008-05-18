<?php

/**
* DTO handling for Note
* @package OPUS
*/
require_once("dto/DTO.class.php");
/**
* DTO handling for Note
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see Note.class.php
* @package OPUS
*
*/

class DTO_Note extends DTO
{

  function __construct($handle) 
  {
    parent::__construct($handle);
  }

  function _load_by_id($id = 0)
  {
    parent::_load_by_id($id);
    require_once("model/User.class.php");
    $this->_author_id = User::get_name($this->author_id);
  }

  function _get_all_by_links($object_type, $object_id = 0)
  {
    global $waf;

    $con = $waf->connections[$this->_handle]->con;

    // Make sure object type is valid and safe
    if(!in_array($object_type, array('Student','Staff','Admin','Company','Vacancy', 'Contact')))
    {
      $waf->security_log("invalid object type $object_type for notes requested");
      $waf->halt("error:notes:invalid_object");
    }

    // Check if the object exists
    //require_once("model/$object_type.class.php");

    $results_array = array();
    try
    {
      $sql = $con->prepare("select note.*, notelink.main from note left join notelink on note.id = notelink.note_id where link_type = ? and link_id = ? order by notelink.main DESC, note.date DESC");
      $sql->execute(array($object_type, $object_id));

      while($results_row = $sql->fetch(PDO::FETCH_ASSOC))
      {
        // Check authorization
        if(!User::check_auth($results_row['auth'])) continue;

        $results_row['author_name'] = User::get_name($results_row['author_id']);
        array_push($results_array, $results_row);
      }
    }
    catch (PDOException $e)
    {
      $this->_log_sql_error($e, $class, "_get_all_by_links()");
    }
    return($results_array);
  }
}

?>