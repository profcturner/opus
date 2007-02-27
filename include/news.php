<?php
/**
**	news.php
**
** Implements a news system.
**
** Initial coding: Colin Turner
**
*/

class PigletNews
{
  var $table = "news";         // Table name to use for news
  var $limit = 0;              // Maximum entries per display (0 is unlimited)

  function NewsAdd($author_id, $keywords, $article, $expiry=NULL, $category=NULL)
  {
    $query = echo "INSERT INTO $table VALUES($author_id, " .
                  make_null($keywords) . ", " .
                  make_null($article) . ", " .
                  make_null($category) . ", " .
                  date("YmdHis") . ", NULL " .
                  make_null($expiry) . ", 0)";

    mysql_query($query)
      or print_mysql_error2("Unable to write news item.", $query);
  }

  function NewsDisplay(


}


?>