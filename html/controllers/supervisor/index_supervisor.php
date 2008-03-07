<?php

function nav_supervisor() 
{
  return array
  (
    "Home"=>array
    (
      array("home", "home", "home", "home"),
      array("change password", "home", "change_password", "change_password")
    ), 
    "Information"=>array
    (
      array("resources", "information", "list_resources", "list_resources")
    )
  );
}

?>