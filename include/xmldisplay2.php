<?php

$xml_allowed = array(
  "PMSDATA" => 'nodisplay',
  "B" => 'matched',
  "STRONG" => 'matched',
  "EM" => 'matched',
  "I" => 'matched',
  "U" => 'matched',
  "P" => 'matched',
//  "H1" => 'matched',
//  "H2" => 'matched',
//  "H3" => 'matched',
  "H4" => 'matched',
  "HR" => 'matched',
  "A" => 'matched',
  "UL" => 'matched',
  "OL" => 'matched',
  "LI" => 'matched',
  "TABLE" => 'matched',
  "TR" => 'matched',
  "TD" => 'matched',
  "TH" => 'matched',
  "IMG" => 'matched',
  "PRE" => 'matched',
  "ADDRESS" => 'matched',
  "BR" => 'matched'
);

function startElement($parser, $name, $attrs)
{
  global $conf;
   global $xml_allowed;
   global $xml_output;
   global $xml_warning;

   // Preprocess some attributes
/*
   if($name == 'IMG')
   {
     if(substr($attrs['SRC'], 0, 8) == 'CONTENT:')
     {
       // Rewrite
       $attrs['SRC'] = "http://" . $conf['hostname'] . $conf['scripts']['controller'] .
	 "?mode=FetchContent&content_id=" . substr($attrs['SRC'], 8);
     }
     if(!isset($attrs['ALT']))
     {
       $xml_warning .= "XML: All images should have ALT attributes<br>\n";
     }
   }

   if($name == 'A')
   {
     global $smarty;
     $smarty->assign('attrs', $attrs);
     if(substr($attrs['HREF'], 0, 8) == 'CONTENT:')
     {
       // Rewrite
       $attrs['HREF'] = "http://" . $conf['hostname'] . $conf['scripts']['controller'] .
	 "?mode=FetchContent&content_id=" . substr($attrs['HREF'], 8);
     }
     if(substr($attrs['HREF'], 0, 5) == 'NODE:')
     {
       // Rewrite
       $attrs['HREF'] = "http://" . $conf['hostname'] . $conf['scripts']['controller'] .
	 "?mode=DisplayNode&node_id=" . substr($attrs['HREF'], 5);
     }
   }
*/
//   if($name == 'B') $name = 'STRONG';
//   if($name == 'I') $name = 'EM';

   if (isset($xml_allowed[$name]))
   {
     $options = $xml_allowed[$name];
     if(!strstr($options, "nodisplay"))
     {
       $xml_output .= "<" . strtolower($name);
       
       // Append any attributes
       foreach($attrs as $attrib => $value)
       {
	 $xml_output .= " " . strtolower($attrib) . " =\"$value\"";
       }

       $xml_output .= ">";
     }
   }
   else
   {
     $xml_warning .= "XML: Disallowed tag $name<br>\n";
   }
}

function endElement($parser, $name)
{
   global $xml_allowed;
   global $xml_output;
   global $xml_warning;

//   if($name == 'B') $name = 'STRONG';
//   if($name == 'I') $name = 'EM';


   if (isset($xml_allowed[$name]))
   {
     $options = $xml_allowed[$name];
     if(!strstr($options, "nodisplay"))
     {
       $xml_output .= "</" . strtolower($name) . ">";
     }
   }
   else
   {
     $xml_warning .= "XML: Disallowed tag $name<br>\n";
   }
}


function characterData($parser, $data)
{
  global $xml_output;

  $xml_output .= $data;
}


function xml_parser($input)
{
  global $xml_output;
  global $xml_warning;

  $output = array();

  // Encapsulate input...required for valid XML...
  $xml_header = 
    "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n";
    
  // DTD header allows special characters and so on...
  $xml_dtd_header =  
    "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" " .
    "\"http:\//www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n" .
    "<PMSDATA>\n";

  $xml_footer="\n</PMSDATA>\n";

  $input_size = strlen($input);
  $wrapped_input = $xml_header . $xml_dtd_header . $input . $xml_footer;
  
  // Initialise variables for output and warning.
  $xml_output="";
  $xml_warning="";
  $xml_error="";

  $xml_parser = xml_parser_create("ISO-8859-1"); // Changed from UTF-8
  
  // use case-folding so we are sure to find the tag in $map_array
  xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, true);
  xml_set_element_handler($xml_parser, "startElement", "endElement");
  xml_set_character_data_handler($xml_parser, "characterData");

  if (!xml_parse($xml_parser, $wrapped_input))
  {
    $xml_error .= "XML error: " .
      xml_error_string(xml_get_error_code($xml_parser)) .
      " at line " .
      // Adjust for PMSDATA encapsulation
      (xml_get_current_line_number($xml_parser) - 3) .
      " byte offset " .
      // Adjust for PMSDATA encapsulation
      (xml_get_current_byte_index($xml_parser) - strlen($xml_dtd_header));

    // Convert the offset into something meaningful, inside the user supplied data
    $suspect_char_offset =
      xml_get_current_byte_index($xml_parser) - strlen($xml_dtd_header);
    
    // Try to detemine the text around the error
    $near_chars = "";
    for($loop = $suspect_char_offset - 20; $loop < $suspect_char_offset + 20; $loop++)
    {
      // Stop it including the PMSDATA wrapper...
      if($loop < 0) continue;
      if($loop > strlen($input)) continue;
      $near_chars .= htmlentities(utf8_decode($input[$loop]));
    }
    $xml_error .= "<br/>The error seems to be related to the string [$near_chars]";
    
    //$xml_warning .= ("Debug: numerator" . ($suspect_char_offset));
    //$xml_warning .= ("Debug: demo" . ($input_size));
    $xml_percentage = (int) ((100*($suspect_char_offset)) / (strlen($input)));
   
    // For backwards compatibility add error to warning
    $xml_warning .= $xml_error;
  }
  xml_parser_free($xml_parser);

  $output['output'] = $xml_output;
  $output['warnings'] = $xml_warning;
  $output['errors'] = $xml_error;
  $output['percentage'] = $xml_percentage;

  // Now regular expressions to auto link web addresses and email addresses
  $output['output'] = preg_replace(
    "/\b(?<!\")(\w[-.%\w]*\@[-a-z0-9]+(\.[-a-z0-9]+)*)\b/ix",
    "<a href=\"mailto:$1\">$1</a>", $output['output']);
  $output['output'] = preg_replace(
    "/\b(?<!\")http:\/\/([-a-z0-9]+(\.[-a-z0-9]+)*(\/[-%~a-z0-9_]+)*(\.[-a-z0-9]+)*(\?[-=&\%~a-z0-9]*)?)*\b/ix",
    "<a href=\"http://$1\">$1</a>", $output['output']);


  return($output);


}


?>