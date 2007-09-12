<?php

/**
* The model object for Channels
* @package OPUS
*/
require_once("dto/DTO_Channel.class.php");

/**
* The Channel model class
*/
class Channel extends DTO_Channel 
{
  var $name = "";        // Very brief channel name
  var $description = ""; // Brief description of channel

  static $_field_defs = array(
    'name'=>array('type'=>'text', 'size'=>30, 'maxsize'=>30, 'title'=>'Name', 'header'=>true, 'listclass'=>'channel_name'),
    'description'=>array('type'=>'text', 'size'=>80, 'maxsize'=>250, 'title'=>'Description', 'header'=>true, 'listclass'=>'channel_description')
    );

  function __construct() 
  {
    parent::__construct('default');
  }

  /**
  * returns the statically defined field definitions
  */
  function get_field_defs()
  {
    return(self::$_field_defs);
  }

  function load_by_id($id) 
  {
    $channel = new Channel;
    $channel->id = $id;
    if($id)
    {
      $channel->_load_by_id();
    }
    else
    {
      $channel->name="Global";
      $channel->description="Visible to all";
    }
    return $channel;
  }

  function insert($fields) 
  {
    $channel = new Channel;
    $channel->_insert($fields);
  }
  
  function update($fields) 
  {
    $channel = Channel::load_by_id($fields[id]);
    $channel->_update($fields);
  }
  
  /**
  * Wasteful
  */
  function exists($id) 
  {
    $channel = new Channel;
    $channel->id = $id;
    return $channel->_exists();
  }
  
  /**
  * Wasteful
  */
  function count() 
  {
    $channel = new Channel;
    return $channel->_count();
  }

  function get_all($where_clause="", $order_by="ORDER BY id", $page=0)
  {
    $channel = new Channel;
    
    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $channels = $channel->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $channels = $channel->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $channels;
  }

  function get_id_and_field($fieldname) 
  {
    $channel = new Channel;
    $channel_array = $channel->_get_id_and_field($fieldname);
    $channel_array[0] = 'Global';
    return $channel_array;
  }


  function remove($id=0) 
  {  
    $channel = new Channel;
    $channel->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {  
    $channel = new Channel;
    return  $channel->_get_fieldnames($include_id); 
  }
  function request_field_values($include_id = false) 
  {
    $fieldnames = Channel::get_fields($include_id);
    $nvp_array = array();
 
    foreach ($fieldnames as $fn) {
 
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
 
    }

    return $nvp_array;

  }


  /**
  * Checks to see if a user is "in" a channel
  *
  * @param integer $channel_id the channel to check against
  * @param integer $user_id optionally a user_id to check, otherwise the logged in user is checked
  * @return boolean answer
  */
  function user_in_channel($channel_id, $user_id = 0)
  {
    // Limit even internal injection possibilities
    $channel_id = (int) $channel_id;
    // Assume no...
    $in_channel = false;

    require_once("model/Channelassociation.class.php");
    $associations = Channelassociation::get_all("where channel_id=$channel_id");

    foreach($associations as $association)
    {
      // Does this association include this person
      if($association->user_in_channel_association($user_id))
      {
        if($association->permission == 'enable') $in_channel = true;
        else
        {
          // Admin users almost certainly don't want to be removed for this...
          if(!User::is_admin($user_id)) $in_channel = false;
        }
      }
    }
    return($in_channel);
  }
  

}
?>