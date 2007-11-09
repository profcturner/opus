<?php

/**
* Used to create a small object for displaying help within templates
* @package OPUS
*/

/**
* Used to create a small object for displaying help within templates
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/

class HelpPrompter
{
  function display($lookup, $user_id = 0)
  {
    $output = "";

    if(!preg_match("/[A-Za-z0-9]+/", $lookup)) return $output;

    // Fetch all potential help prompts
    require_once("model/Help.class.php");
    $prompts = Help::get_all("where lookup='$lookup'");
    if(count($prompts) == 0) return $output;

    // Need to establish if the user should see these
    require_once("model/Channel.class.php");
    require_once("model/XMLdisplay.class.php");
    foreach($prompts as $prompt)
    {
      if(Channel::user_in_channel($prompt->channel_id, $user_id))
      {
        $xml_parser = new XMLdisplay($prompt->contents);
        echo $xml_parser->xml_output;
      }
    }
  }
}
?>