<?php

/**
* The model object for security policies
* @package OPUS
*/

/**
* The HelpPrompter class
*/
class HelpPrompter
{
  function display($lookup, $user_id = 0)
  {
    $output = "";

    if(!preg_match("/[A-Za-z0-9]+/", $lookup)) return $output;

    require_once("model/Help.class.php");
    $prompts = Help::get_all("where lookup='$lookup'");
    if(count($prompts) == 0) return $output;

    require_once("model/XMLdisplay.class.php");
    foreach($prompts as $prompt)
    {
      $xml_parser = new XMLdisplay($prompt->contents);
      echo $xml_parser->xml_output;
    }
  }
}
?>