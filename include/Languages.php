<?php

/**
* Languages
*
* A class to encapsulate Language handling within OPUS
*
* Language handling is far from complete, this sort of thing is a stub
* to make it possible in the future.
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/

class Languages
{
  function get_indexed_array()
  {
    $query = "select * from languages order by language";
    $result = mysql_query($query)
      or print_mysql_error2("Unable to get languages", $query);
  
    $languages = array();
    while($language = mysql_fetch_array($result))
    {
      $languages[$language["language_id"]] = $language["language"];
    }
    mysql_free_result($result);
    return($languages);
  }
}


?>