<?php

//define(DEBUG, TRUE);

class Questionnaire
{
  /**
   *  the filename of the control file which gives variable structures
   *  @var string
   */
  var $control_filename;
    
  /**
   *  an array of question data
   *  @var array
   */

  var $questions;
  
  function __construct($filename)
  {
    $this->control_filename = $filename;
    $this->questions = array();
    
    $this->LoadControlFile();
    
    //if(DEBUG) echo (count($this->questions) . " questions loaded");
  }
  
  function LoadControlFile()
  {
    $fp = fopen($this->control_filename, "r");
    if(!$fp)
    {
      echo "Error: Unable to open control file";
    }
    while($row = fgetcsv($fp))
    {
      trim($row);
      // Comments in the control file start with a semi colon
      if($row[0][0] == ';') continue;
      $question = new QuestionnaireData($row);
      array_push($this->questions, $question);
    }
    fclose($fp);
    
  }
}


class QuestionnaireData
{
  /**
   *  The variable name to be used in the script
   *  @var string
   */ 
  var $name;

  /**
   *  The type of variable, current the supported values
   *  are text, textarea, checkbox and radios (note the s).
   *  @var string
   */ 
  var $type;

  /**
   *  Extra data dependent upon the type
   *  For example, radio labels
   *  @var string
   */ 
  var $type_data;

  /**
   *  Extra data dependent upon the type
   *  For example, radio labels
   *  @var string
   */ 
  var $validation;

  /**
   *  Parses and stores the array of data from the control file
   *  @var array data already parsed into an array
   */
  function __construct($csv_data)
  {
    global $smarty;
    
    $this->name = $csv_data[0];
    $this->type = $csv_data[1];
    $this->type_data = $csv_data[2];
    $this->validation = $csv_data[3];
    
    $smarty->assign("Quest_" . $this->name, $this);
  }
  
  function Validate()
  {
    if(empty($this->validation)) return TRUE;
    
    return(preg_match($this->validation, $_REQUEST[$this->name]));
  
  }
  
  function FlagError()
  {
    // This needs more refinement
    if(isset($_REQUEST[$this->name]))
    {
      // Variable is set...
    
      if(!$this->Validate()) echo "<span class=\"error\">**</span>";
    }
  }
  
  function WriteVariable()
  {
    // Check for any errors yet...
    if(isset($_REQUEST[$this->name]))
    {
      // Variable is set...
      if(!$this->Validate()) echo "<span class=\"error\">**</span>";
    
    }
    switch($this->type)
    {
      case "text":
        $this->WriteVariableText();
        break;
      case "textarea":
        $this->WriteVariableTextArea();
        break;
      case "checkbox":
        $this->WriteVariableCheckbox();
        break;
      case "radios":
        $this->WriteVariableRadios();
        break;
    }
  }
  
  function WriteVariableCheckbox()
  {
    echo "<input type=\"checkbox\" name=\"" . $this->name .
      "\">";
  
  }
  
  function WriteVariableText()
  {
    echo "<input type=\"text\" name=\"" . $this->name .
      "\" size=\"" . $this->type_data . "\">";
  }
  
  
  function WriteVariableTextArea()
  {
    $dimensions = explode("x", $this->type_data);
    echo "<textarea name=\"" . $this->name .
      "\" rows=\"" . $dimensions[0] . "\" " .
      "cols =\"" . $dimensions[1] . "\">";
    echo "</textarea>";
  
  }
  
  function WriteVariableRadios()
  {
    global $radios_rating;
    global $radios_required;
    global $radios_agreement;
    global $radios_lowhigh;
    
    $radio_array = array();
    //echo $this->type_data;
    //print_r($$this->type_data);
    switch($this->type_data)
    {
      case "radios_rating":
        $radio_array = $radios_rating;
        break;
      case "radios_required":
        $radio_array = $radios_required;
        break;
        
      case "radios_agreement":
        $radio_array = $radios_agreement;
        break;
        
      case "radios_lowhigh":
        $radio_array = $radios_lowhigh;
        break;
        
    }
    $labels_count = count($radio_array);
    $labels_index = 0;
    //echo count($radio_array);
    $html = "";
    // Go through the selected array of checkboxes
    foreach($radio_array as $key => $value)
    {
      if($labels_index == 0)
      {
        $html .= htmlspecialchars($key);
        $html .= "&nbsp; &nbsp;";
      }
      $html .= "<input type=\"radio\" " .
        "name=\"" . $this->name . "\" " .
        "value=\"$value\"> &nbsp;&nbsp; ";
      if($labels_index == ($labels_count - 1))
      {
        $html .= htmlspecialchars($key);
      }      
      $labels_index++;
    }
    echo $html;
    return $html;
   
  }

}


$radios_rating = array();
$radios_rating["Poor"] = 1;
$radios_rating["Satisfactory"] = 2;
$radios_rating["Good"] = 3;
$radios_rating["Very Good"] = 4;
$radios_rating["Excellent"] = 5;

$radios_required = array();
$radios_required["Not Required"] = 5;
$radios_required["Slightly Desirable"] = 4;
$radios_required["Desirable"] = 3;
$radios_required["Very Desirable"] = 2;
$radios_required["Essential"] = 1;

$radios_agreement = array();
$radios_agreement["Strongly disagree"] = 5;
$radios_agreement["Disagree"] = 4;
$radios_agreement["Neutral"] = 3;
$radios_agreement["Agree"] = 2;
$radios_agreement["Strongly agree"] = 1;

$radios_lowhigh = array();
$radios_lowhigh["Very low"] = 1;
$radios_lowhigh["Low"] = 2;
$radios_lowhigh["Medium"] = 3;
$radios_lowhigh["High"] = 4;
$radios_lowhigh["Very High"] = 5;


?>