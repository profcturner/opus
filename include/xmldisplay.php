<?php

/**
**	xmldisplay.php
**
** This file contains code to parse the xml style fields
** used in the script suite.
**
*/


/**
**	output_xml_field
**
** Unfortunately we cannot use the proper XML support in
** PHP. As the support required is very simple an
** algorithm of our own is here.
**
** This parses documents containing XML tags.
**
** See process_xml_tag for more details.
*/
function output_xml_field($field)
{
  echo parse_xml_field($field);
}


function parse_xml_field($field)
{
  $output = "";
  // Split the information so that any XML tags are extracted.
  // Note that no data is "eaten" in the splitting process.
  // Split if there is a < to the right or a > to the left...
  $array = preg_split("/(?=<)|(?<=>)/", $field);

  // Look at each of the split "chunks" in turn...
  foreach($array as $chunk){
   
    // Is it an XML tag?
    // We allow a possible / at the beginning or end to indicate negation
    if(preg_match("/<(\/)?(\w+)((?:\s+[\w:\"\'=.\/]*)*)(\/)?>/i", $chunk, $matches))
    {
      // Send the matching tag and subexpression for further processing
      $output .= process_xml_tag($matches);
    }
    else
    {
      
      // Process as normal text
      $index = 0;
      while($index < strlen($chunk)){
        switch($chunk[$index]){
          case "\n" :
            // Newline
            $output .= "\n";
            break;
          case '<' :
            $output .= "&lt;";
            break;
          case '>' :
            $output .= "&gt;";
            break;
          case '&' :
            // Sometimes we want this... &lt, &gt
            $end = strpos($chunk, ";", $index);
            if($end == FALSE)
              $output .= "&amp;";
            else{
              // This is probably a deliberate encoding
              $output .= substr($chunk, $index, $end-$index+1);
              $index+= ($end-$index);
            }
            break;
          default :
      
            // Normally just print the character
            $output .= htmlentities($chunk[$index]);
            break;
        }
        $index++;
      }
    }
  }
  // hyperlink email addresses and url's
  $output = preg_replace(
    "/\b(\w[-.%\w]*\@[-a-z0-9]+(\.[-a-z0-9]+)*)\b/ix",
    "<a href=\"mailto:$1\">$1</a>", $output);
  $output = preg_replace(
    "/\bhttp:\/\/([-a-z0-9]+(\.[-a-z0-9]+)*(\/[-%~a-z0-9]+)*(\.[-a-z0-9]+)*)\b/ix",
    "<a href=\"http://$1\">$1</a>", $output);

  return $output;
}


/**
**	process_xml_tag
**
** This function translates the various XML tags supported into
** appropiate HTML tags.
**
**
**	@param $matches[0] The whole matches tag including internal material
**      @param $matches[1] The optional '/' at the start of the tag contents
**	@param $matches[2] The compulsory tag name
**	@param $matches[3] The rest of the optional tag contents (after any white space)
**	@param $matches[4] The optional '/' at the end of the tag contents
*/

function process_xml_tag($matches)
{
  global $conf;
  $output = ""; 

  // Some simple tags can be passed straight through
  // No parameters allowed though...
  if(empty($matches[3]))
  {
    $allowed = explode(" ", $conf['xml']['matched']['pass']);
    {
      foreach($allowed as $htmltag)
      {
        if(!strcasecmp($htmltag, $matches[2]))
        {
          $output .= $matches[0];
          return $output;
        } 
      }
    }

    $allowed = explode(" ", $conf['xml']['unmatched']['pass']);
    {
      foreach($allowed as $htmltag)
      {
        if(!strcasecmp($htmltag, $matches[2]))
        {
          $output .= $matches[0];
          return $output;
        } 
      }
    }
  }
 

  // Now special handling...
  switch(strtoupper($matches[2]))
  {
    // Permit british spelling
    case "CENTRE":
      $output .= ( "<" . $matches[1] . "CENTER>" );
      break;
    case "LEFT":
      if($matches[1]=='/') $output .= "</DIV>";
      $output .= "<DIV ALIGN=\"LEFT\">";
      break;
    case "RIGHT":
      if($matches[1]=='/') $output .= "</DIV>";
      else $output .= "<DIV ALIGN=\"RIGHT\">";
      break;
    case "JUSTIFY":
      if($matches[1]=='/') $output .= "</DIV>";
      else $output .= "<DIV ALIGN=\"JUSTIFY\">";
      break;
    case "TITLE":
      if($matches[1]=='/') $output .= "</H4>";
      else $output.= "<H4 class=\"red\">";
      break;
    default:
      $output .= ("<B>Invalid tag : " . htmlspecialchars($matches[0]) . "</B>");
      break;

  }
  return $output;

}

function xml_parse_include($matches)
{
  static $lookup;

  if($matches[1]=='/')
  {
    OutputHelp($lookup);
  }
}  
  


$conf['xml']['matched']['pass']   = "CENTER B PRE TT UL OL LI";
$conf['xml']['unmatched']['pass'] = "BR HR";



?>