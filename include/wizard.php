<?php


class TabbedContainer
{
  var $tabs = array();
  var $smarty;
  var $name;

  function TabbedContainer(&$smarty, $name)
    {
      $this->smarty = &$smarty;
      $this->name = $name;
    }

  function addTab($name, $url, $handle = "")
    {
      $item = array();
      $item['name'] = $name;
      $item['url'] = $url;
      if(empty($handle))
      {
	$item['handle'] = $name;
      }
      else
      {
	$item['handle'] = $handle;
      }
      array_push($this->tabs, $item);
    }

  function displayTab($handle = "")
    {
      $this->smarty->assign($this->name, $this->tabs);
      $this->smarty->assign($this->name . "_active", $handle);
      $this->smarty->display("tabbedContainer.tpl");
    }
}

?>