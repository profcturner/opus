<?php

/**
* The model object for Timelines
* @package OPUS
*/
require_once("dto/DTO_Timeline.class.php");

/**
* The Timeline model class
*
* Timelines are a novel way of showing student application activity. Because the
* creation of these graphs is computational intensive they are produced offline
* using, at least for the moment, Perl. PHP is used to reduce the numbers of
* redraws. If timelines don't work for you, check your cron framework is operational.
*/
class Timeline extends DTO_Timeline 
{
  var $student_id = 0;   // The user id of the student the timeline belongs to
  var $last_updated = 0; // The last updated timestamp, sometimes set to zero to invalidate
  var $image;            // The BLOB containing the image itself.

  // This is not added by humans
  static $_field_defs = array(
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

  function load_by_id($id) 
  {
    $timeline = new Timeline;
    $timeline->id = $id;
    $timeline->_load_by_id();
    return $timeline;
  }

  function insert($fields) 
  {
    $timeline = new Timeline;
    $timeline->_insert($fields);
  }

  function update($fields) 
  {
    $timeline = Timeline::load_by_id($fields[id]);
    $timeline->_update($fields);
  }

  /**
  * Wasteful
  */
  function exists($id) 
  {
    $timeline = new Timeline;
    $timeline->id = $id;
    return $timeline->_exists();
  }

  /**
  * Wasteful
  */
  function count() 
  {
    $timeline = new Timeline;
    return $timeline->_count();
  }

  function get_all($where_clause="", $order_by="ORDER BY name", $page=0)
  {
    $timeline = new Timeline;

    if ($page <> 0) {
      $start = ($page-1)*ROWS_PER_PAGE;
      $limit = ROWS_PER_PAGE;
      $timelines = $timeline->_get_all($where_clause, $order_by, $start, $limit);
    } else {
      $timelines = $timeline->_get_all($where_clause, $order_by, 0, 1000);
    }
    return $timelines;
  }

  function get_id_and_field($fieldname, $where_clause="") 
  {
    $timeline = new Timeline;
    $timeline_array = $timeline->_get_id_and_field($fieldname, $where_clause);
    unset($timeline_array[0]);
    return $timeline_array;
  }

  function remove($id=0) 
  {
    $timeline = new Timeline;
    $timeline->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {
    $timeline = new Timeline;
    return  $timeline->_get_fieldnames($include_id); 
  }

  function request_field_values($include_id = false) 
  {
    $fieldnames = Timeline::get_fields($include_id);
    $nvp_array = array();

    foreach ($fieldnames as $fn)
    {
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
    }
    return $nvp_array;
  }

  /**
  * exports a given timeline to stdout
  *
  * if no timeline is available, a suitable blank will be sent
  *
  * @param int $student_id the user id of the student to look for
  */
  function display_timeline($student_id = 0)
  {
    $student_id = (int) $student_id;

    $timeline = new Timeline;

    $data = Timeline::get_id_and_field("image","where student_id='$student_id'");
    $split = each($data);

    header("Content-type: application/jpeg");
    header("Content-Disposition: inline; filename=timeline.jpeg");
    if(!count($data)) Timeline::display_blank_timeline();
    else print $split['value'];
  }

  function display_blank_timeline()
  {
    $image  = ImageCreateTrueColor(600, 100);
    $bgc = imagecolorallocate ($image, 0xFA, 0xFA, 0xFA);
    $tc  = imagecolorallocate ($image, 0, 0, 0);
    imagefilledrectangle ($image, 0, 0, 600, 100, $bgc);
    imagestring ($image, 3, 5, 5, "No Timeline Available", $tc);

    imagejpeg($image);
    imeagedestroy($image);
  }

  function update_all_years()
  {
    // Check academic year
    $year = get_academic_year();

    Timeline::update_year($year - 1);
    Timeline::update_year($year);
    Timeline::update_year($year + 1);
  }

  function update_year($year)
  {
    global $waf;

    $message = "Updating timelines for $year";
    if($waf->unattended) echo "$message\n";
    $waf->log($message);

    require_once("model/Student.class.php");
    $student_ids = Student::get_ids("where placement_year=$year");

    foreach($student_ids as $student_id)
    {
      // echo " Looking at student $user_id\n";
      // For each student, get the user_id
      $user_id = $row["user_id"];

      $last_updated = Timeline::get_id_and_field("last_updated", "where student_id=$student_id");
      if(!$last_updated)
      {
        // No image exists in the database, add one...
        Timeline::add_image($student_id);
      }
      else
      {
        $data = each($last_updated);
        $key = $data['key'];
        // Is it up-to-date?
        $last_application = Student::get_last_application_time($student_id);
        if(($last_application > $data['value']) || ($data['value'] = '0000-00-00 00:00:00'))
        {
          // No, so modify image
          Timeline::modify_image($student_id, $data['key']);
        }
      }
    }
    $message = "Updating timelines for $year complete";
    if($waf->unattended) echo "$message\n";
    $waf->log($message);
  }

  function add_image($student_id)
  {
    global $waf;

    $message = "Adding new timeline for student $student_id";
    if($waf->unattended) echo "  $message\n";
    $waf->log($message);

    $image = Timeline::create_image($student_id);
    if($image)
    {
      $fields['last_updated'] = date("YmdHis");
      $fields['image'] = $image;
      $fields['student_id'] = $student_id;
      Timeline::insert($fields);
    }
    else
    {
      $waf->log("error updating timeline image");
    }
  }

  function modify_image($student_id, $timeline_id)
  {
    global $waf;

    $message = "Modifying timeline for student $student_id";
    if($waf->unattended) echo "  $message\n";
    $waf->log($message);

    $image = Timeline::create_image($student_id);
    if($image)
    {
      $timeline = Timeline::load_by_id($timeline_id);
      $fields['last_updated'] = date("YmdHis");
      $fields['image'] = $image;
      $timeline->update($fields);
    }
    else
    {
      $waf->log("error updating timeline image");
    }
  }

  function create_image($student_id)
  {
    global $waf;

    $perl_script = $waf->base_dir . "/cron/timeline.pl";
    $fp = popen("$perl_script $student_id", "rb");

    if(!$fp)
    {
      $waf->log("unable to generate timeline");
      return FALSE;
    }
    $image = stream_get_contents($fp); // Requires PHP 5.1+ (already required for UUWAF)
    pclose($fp);

    return($image);
  }

  /**
  * sets the last_updated stamp to zero, invalidating the timeline
  *
  * @param int $student_id the id from the student table
  */
  function invalidate($student_id)
  {
    $student_id = (int) $student_id; // security
    $timeline = new Timeline;
    $timeline->_load_where("where student_id = $student_id");
    $timeline->last_updated = 0;
    $timeline->_update();
  }
}
?>