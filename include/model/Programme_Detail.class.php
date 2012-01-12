<?php
/**
 * @package PDSystem
 *
 */

require_once("dto/DTO_Programme_Detail.class.php");
require_once("model/Cache_Object.class.php");

Class Programme_Detail extends DTO_Programme_Detail
{
  var $programme_code = '';
  var $programme_title = '';

  function load_by_programme_code($programme_code, $ttl=1800)
  {
    $key = "Programme_Detail:load_by_programme_code:$programme_code";

    $cached_programme_detail = new Cache_Object("default", $ttl);
    $cached_programme_detail->load_from_cache($key);

    if (time() > (strtotime($cached_programme_detail->timestamp) + $cached_programme_detail->_ttl))
    {
      $found_programme_detail = Programme_Detail::_load_by_programme_code($programme_code);
      $cached_programme_detail->update_cache($key, $found_programme_detail);
    }
    else
    {
      $found_programme_detail = $cached_programme_detail->cache;
    }

    return $found_programme_detail;
  }

}

?>
