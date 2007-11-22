<?php

class Activities
{
  function get_indexed_array()
  {
    $query = "select * from vacancytype order by name";
    $result = mysql_query($query)
      or print_mysql_error2("Unable to get activities", $query);
  
    $vacancies = array();

    while($vacancy = mysql_fetch_array($result))
    {
      $vacancies[$vacancy["vacancy_id"]] = $vacancy["name"];
    }
    mysql_free_result($result);
    return($vacancies);
  }

}
?>