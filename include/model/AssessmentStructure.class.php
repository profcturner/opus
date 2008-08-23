<?php

/**
* The model object for AssessmentStructures
* @package OPUS
*/
require_once("dto/DTO_AssessmentStructure.class.php");

/**
* The AssessmentStructure model class
*/
class AssessmentStructure extends DTO_AssessmentStructure 
{
  var $assessment_id = 0;        // The assessment this variable belongs to
  var $human = "";               // A human readable description of the variable
  var $name = "";                // The variable name
  var $type = "";                // The type of field
  var $min = 0;                  // Minimum value (if any)
  var $max = 0;                  // Maximum value (if any)
  var $weighting = 0;            // Weighting of score of this variable in assessment
  var $options = "";             // Whether the item is compulsory or not
  var $varorder = "";            // Order in which variables are examined

  static $_field_defs = array(
    'human'=>array('type'=>'text', 'size'=>40, 'maxsize'=>80, 'title'=>'Description', 'header'=>true, 'listclass'=>'assessmentstructure_description', 'mandatory'=>true),
    'name'=>array('type'=>'text', 'size'=>40, 'maxsize'=>80, 'title'=>'Variable Name', 'header'=>true, 'mandatory'=>true),
    'type'=>array('type'=>'list', 'list'=>array('textual'=>'textual','numeric'=>'numeric','checkbox'=>'checkbox','assesseddate'=>'assesseddate'), 'header'=>true),
    'min'=>array('type'=>'text', 'size'=>5, 'title'=>'Minimum Value / Characters'),
    'max'=>array('type'=>'text', 'size'=>5, 'title'=>'Maximum Value / Characters'),
    'weighting'=>array('type'=>'text', 'size'=>3, 'title'=>'Weighting'),
    'options'=>array('type'=>'list', 'list'=>array('compulsory','optional'))
  );

  function __construct() 
  {
    parent::__construct('default');
  }

  /**
  * returns the statically defined field definitions
  */
  function get_field_defs()
  {
    return(self::$_field_defs);
  }

  function move_up($assessment_id, $id)
  {
    $assessmentstructure = AssessmentStructure::load_by_id($id);
    $varorder = $assessmentstructure->varorder;
    $assessmentstructure->_move_up($assessment_id, $varorder);
  }

  function move_down($assessment_id, $id)
  {
    $assessmentstructure = AssessmentStructure::load_by_id($id);
    $varorder = $assessmentstructure->varorder;
    $assessmentstructure->_move_down($assessment_id, $varorder);
  }


  function load_by_id($id) 
  {
    $assessmentstructure = new AssessmentStructure;
    $assessmentstructure->id = $id;
    $assessmentstructure->_load_by_id();
    return $assessmentstructure;
  }

  function insert($fields) 
  {
    $assessmentstructure = new AssessmentStructure;
    $assessmentstructure->_insert($fields);
  }

  function update($fields) 
  {
    $assessmentstructure = AssessmentStructure::load_by_id($fields[id]);
    $assessmentstructure->_update($fields);
  }

  /**
  * Wasteful
  */
  function exists($id) 
  {
    $assessmentstructure = new AssessmentStructure;
    $assessmentstructure->id = $id;
    return $assessmentstructure->_exists();
  }

  /**
  * Wasteful
  */
  function count($where_clause="") 
  {
    $assessmentstructure = new AssessmentStructure;
    return $assessmentstructure->_count($where_clause);
  }

  function get_all($where_clause="", $order_by="ORDER BY varorder", $page=0)
  {
    global $config;
    $rows_per_page = $config['opus']['rows_per_page'];
    $assessmentstructure = new AssessmentStructure;

    if ($page <> 0) {
      $start = ($page-1)*$rows_per_page;
      $limit = $rows_per_page;
      $assessmentstructures = $assessmentstructure->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $assessmentstructures = $assessmentstructure->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $assessmentstructures;
  }

  function get_id_and_field($fieldname, $where_clause="") 
  {
    $assessmentstructure = new AssessmentStructure;
    $assessmentstructure_array = $assessmentstructure->_get_id_and_field($fieldname, $where_clause);
    return $assessmentstructure_array;
  }

  function remove($id=0) 
  {
    $assessmentstructure = new AssessmentStructure;
    $assessmentstructure->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {
    $assessmentstructure = new AssessmentStructure;
    return  $assessmentstructure->_get_fieldnames($include_id); 
  }

  function request_field_values($include_id = false)
  {
    $fieldnames = AssessmentStructure::get_fields($include_id);
    $nvp_array = array();

    foreach ($fieldnames as $fn)
    {
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
    }
    return $nvp_array;
  }

  /**
  * checks an inbound assessment variable against options.
  * @param value is the value entered by the user
  * @return any error that occurred, or an empty variable
  * @todo needs to use a template for error text.
  */
  function validate_variable_options($value)
  {
    $error = array();
    if(strstr($this->options, "compulsory"))
    {
      if($value != "0")
      {
        if(empty($value) || $value="")
        {
          array_push($error, $this->human . " cannot by empty");
        }
      }
    }
    return($error);
  }

  /**
  * checks an inbound assessment variable against a minimum.
  * @param string $value is the value entered by the user
  * @return any error that occurred, or an empty array
  */
  function validate_variable_minimum($value)
  {
    $error = array();
    if(!empty($this->min))
    {
      if($this->type == 'textual')
      {
        if(strlen($value) < $this->min)
        {
          array_push($error, $this->human . " must have a length greater than " . $this->min);
        }
      }
      if($this->type == 'numeric')
      {
        if($value < $this->min)
        {
          array_push($error, $this->human . " cannot have a value less than " . $this->min);
        }
      }
    }
    return($error);
  }


  /**
  * checks an inbound assessment variable against a maximum.
  * @param string $value is the value entered by the user
  * @return any error that occurred, or an empty variable
  */
  function validate_variable_maximum($value)
  {
    $error = array();
    if(!empty($this->max))
    {
      if($this->type == 'textual')
      {
        if(strlen($value) > $this->max)
        {
          array_push($error, $this->human . " must have a length less than " . $this->max);
        }
      }
      if($this->type == 'numeric')
      {
        if($value > $this->max)
        {
          array_push($error, $this->human . " cannot have a value more than " . $this->max);
        }
      }
    }
    //if(count($error)) print_r($error);
    return($error);
  }

  /**
  * Checks an individual assessment variable for validity.
  * @param string $value is the inbound value for this item
  * @return An array of error lines that will be empty if there are no errors.
  */
  function validate_variable($value)
  {
    $error = array();
    if($this->type == 'assesseddate' || $this->type == 'date')
    {
      if(!(($this->type == 'date') && empty($value)))
      {
        if(empty($value))
        {
          // Anything needed here?
        }
        else
        {
          $date = AssessmentStructure::parse_date($value);
          if(!checkdate($date['month'], $date['day'], $date['year']))
          {
            array_push($error, $this->human . " is invalid.");
          }
        }
      }
    }

    $error = array_merge($error, $this->validate_variable_minimum($value));
    $error = array_merge($error, $this->validate_variable_maximum($value));
    $error = array_merge($error, $this->validate_variable_options($value));

    return($error);
  }

  function parse_date($textdate)
  {
    $parts = explode("/", $textdate);
    $date['day'] = $parts[0];
    $date['month'] = $parts[1];
    $date['year'] = $parts[2];

    return($date);
  }



}
?>
