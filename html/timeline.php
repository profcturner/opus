<?php

/**
**	photo.php
**
** This script attempts to display an image of a specified
** user, by default using a thumbnail. If no thumbnail
** exists one will be created.
**
*/
include("common.php");
include("authenticate.php");


// Connect to the database on the server
db_connect()
  or die("Unable to connect to server");

// Non authenticated users can download nothing
auth_user("user");

if(empty($student_id))
{
  page_header();
  die_gracefully("A student id is required by this script.");
}

$sql = "SELECT image FROM timelines WHERE student_id=$student_id";
$result = mysql_query($sql)
  or print_mysql_error2("Unable to fetch timeline data", $sql);

$image_data = mysql_fetch_row($result);
$image_data = $image_data[0];
mysql_free_result($result);

header("Content-type: application/jpeg");
header("Content-Disposition: inline; filename=timeline.jpeg");
print $image_data;

function output_noimage()
{
  $image  = ImageCreateTrueColor(100, 150); /* Create a blank image */
  $bgc = imagecolorallocate ($image, 255, 255, 255);
  $tc  = imagecolorallocate ($image, 0, 0, 0);
  imagefilledrectangle ($image, 0, 0, 100, 150, $bgc);
  imagestring ($image, 1, 5, 5, "No Photo", $tc);
  
  output_headers();
  imagejpeg($image);
  imeagedestroy($image);
}
    
?>