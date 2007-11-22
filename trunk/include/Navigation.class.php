<?php

class Navigation
{
  var $nav;
  var $nav_order;

  function __construct()
  {
    $this->nav = array();
    $this->nav_order = array();
  }

  function add_menu($name, $section, $url, $id="")
  {
    $menu = array();
    $menu['name'] = $name;
    $menu['section'] = $section;
    $menu['url'] = $url;
    $menu['id'] = $id;
    $menu['subitems'] = array();

    // Store this in an associative array
    $this->nav[$section] = $menu;

    // And its order
    array_push($this->nav_order, $section);
  }

  function add_menu_item($name, $section, $url, $id="")
  {
    $menu_item = array();
    $menu_item['name'] = $name;
    $menu_item['url'] = $url;
    $menu_item['id'] = $id;

    array_push($this->nav[$section]['subitems'], $menu_item);
  }

}

?>