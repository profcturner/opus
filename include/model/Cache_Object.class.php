<?php

/**
* Support for caching objects in the database
* @package OPUS
*/
require_once('dto/DTO.class.php');
/**
* Support for caching objects in the database
*
* @author Gordon Crawford <g.crawford@ulster.ac.uk>
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/

class Cache_Object extends DTO
{
  var $key = "";
  var $cache = "";
  var $timestamp = "";
  var $read_count = 0;
  var $refresh_count = 0;

  var $_ttl = 0;

  static $_field_defs = array
  (
    'key'=>array('type'=>'text'),
    'cache'=>array('type'=>'blob')
  );

  function __construct($handle = 'default', $_ttl=1800) 
  {
    parent::__construct($handle);
    $this->_ttl = $_ttl;
  }

  function if_valid($key)
  {
    if ($this->_ttl == 0) return false; // if ttl is zero return false so that the cache is refreshed immediately
    $success = $this->load_from_cache($key);
    if(!$success) return false; // doesn't exist
    if(time() > (strtotime($this->timestamp) + $this->_ttl)) return false; // stale
    return true;
  }

  /**
  * attempts to load data from the cache
  *
  * @param string $key the unique key for the cache item
  * @param boolean $return_stale whether stale data should be returned (defaults to false)
  *
  * @return a boolean value for success
  * @see cache
  */
  function load_from_cache($key, $return_stale = false)
  {
    $success = $this->_load_by_field_value("key", $key);
    if($success)
    {
      // Ok, it's in the database, but is it really valid?
      if(time() > (strtotime($this->timestamp) + $this->_ttl))
      {
        // Stale
        if(!$return_stale) return false;
      }
    }
    $this->read_count = $this->read_count + 1;
    $this->_update();
    $this->cache = unserialize($this->cache);
    return $success;
  }

  /**
  * updates the data in the cache
  *
  * @param string $key the unique key for the cache item
  * @param mixed $cache the item to place in the cache
  */
  function update_cache($key, $cache)
  {
    $wscache = new Cache_Object;

    if ($wscache->_count("WHERE `key`=\"$key\"") == 0)
    {
      // insert
      $wscache->key = $key;
      $wscache->cache = serialize($cache);
      $wscache->timestamp = date("Y-m-d H:i:s");
      $wscache->refresh_count = 0;
      $wscache->_insert();
    }
    else
    {
      // update
      $this->key = $key;
      $this->cache = serialize($cache);
      $this->timestamp = date("Y-m-d H:i:s");
      $this->refresh_count = $this->refresh_count + 1;
      $this->_update();
    }
  }
}

?>
