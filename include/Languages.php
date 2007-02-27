<?php

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