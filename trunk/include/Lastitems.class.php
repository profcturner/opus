<?php

class LastitemQueue
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

  function add($lastitem)
  {
    $index = $this->get_index($lastitem);
    if($index)
    {
      //echo "This items exists at offset $index...\n";
      //echo "There are " . count($this->queue) . " items now...\n";
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

  function get_index($lastitem)
  {
    // Already present?
    for($index = 0; $index <= $this->maxindex; $index++)
    {
      //echo "@";
      //if($lastitem->type == $this->queue[$index]->type) echo "t";
      //if($lastitem->id == $this->queue[$index]->id) echo "i";
      //echo "\n$index : " . $lastitem->id . " : " . $this->queue[$index]->id; 
      //echo "\n$index : " . $lastitem->type . " : " . $this->queue[$index]->type; 


      if(($lastitem->type == $this->queue[$index]->type) &&
        ($lastitem->id == $this->queue[$index]->id)) return($index);
    }
    return(false);
  }
  

}

class Lastitem
{
  var $type;
  var $id;
  var $human;
  var $human_long;
  var $url;
  var $when;

  function __construct($type, $id, $human, $url, $human_long="")
  {
    $this->type = $type;
    $this->id = $id;
    $this->human = $human;
    $this->url = $url;
    $this->when = date("YmdHis");
    $this->human_long = $human_long;
  }

}

?>