<?php

/**
* Handles the filing and recall of recently visited items
* @package OPUS
*/

/**
* Handles the filing and recall of recently visited items
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/

class Lastitems
{
  var $queue;
  var $maxsize;
  var $maxindex;

  function __construct($maxsize)
  {
    $this->maxsize = $maxsize;
    $this->queue = array();
    $this->maxindex = -1;
  }

  function add_here($human, $unique_id, $human_long="")
  {
    $lastitem = new Lastitem($human, $unique_id, $_SERVER['REQUEST_URI'], $human_long);
    $this->add($lastitem);
  }

  function add($lastitem)
  {
    $index = $this->get_index($lastitem);
    if($index != -1)
    {
      // This is already on the list, unset it...
      unset($this->queue[$index]);
      // and then add...
      //echo "There are " . count($this->queue) . " items now...\n";
      $this->add($lastitem);
    }
    else
    {
      //echo "Unknown item...\n";
      if(count($this->queue) >= $this->maxsize)
      {
        // One has to go! Off the end...
        // This is not working...?
        array_shift($this->queue);
      }
      // Now add the new one...
      $this->maxindex++;
      $this->queue[$this->maxindex]=$lastitem;
      }
  }

  function get_nav()
  {
    if(!$this->get_count()) return(array());
    $items = array();
    foreach($this->queue as $lastitem)
    {
      array_push($items,
        array($lastitem->human, "recent", "last_items", "last_items", "", $lastitem->url));
    }
    $nav = array
    (
      "recent"=>$items
    );
    return($nav);
  }

  function get_count()
  {
    return(count($this->queue));
  }

  function get_index($lastitem)
  {
    // Already present?
    for($index = 0; $index <= $this->maxindex; $index++)
    {
      if($lastitem->unique_ident == $this->queue[$index]->unique_ident) return($index);
    }
    return(-1);
  }
}

class Lastitem
{
  var $human;
  var $human_long;
  var $url;
  var $unique_ident;
  var $when;

  function __construct($human, $unique_ident, $url, $human_long="")
  {
    $this->human = $human;
    $this->unique_ident = $unique_ident;
    $this->url = $url;
    $this->when = date("YmdHis");
    if(strlen($human_long))
      $this->human_long = $human_long;
    else
      $this->human_long = $human;
  }

}

?>