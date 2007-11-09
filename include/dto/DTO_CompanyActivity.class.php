<?php

/**
* DTO handling for CompanyActivity
* @package OPUS
*/
require_once("dto/DTO.class.php");
/**
* DTO handling for CompanyActivity
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see CompanyActivity.class.php
* @package OPUS
*
*/

class DTO_CompanyActivity extends DTO
{

  function __construct($handle) 
  {
    parent::__construct($handle);
  }

  function _get_activity_ids_for_company($company_id)
  {
    global $waf;
    $con = $waf->connections[$this->_handle]->con;

    $results_array = array();
    try
    {
      $sql = $con->prepare("select activity_id from companyactivity where company_id=?");
      $sql->execute(array($company_id));

      while($results = $sql->fetch(PDO::FETCH_ASSOC))
      {
        array_push($results_array, $results['activity_id']);
      }
    }
    catch (PDOException $e)
    {
      $this->_log_sql_error($e, "CompanyActivity", "_get_all($company_id)");
    }
    return $results_array;
  }
}

?>