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

if(empty($user_id))
{
  page_header();
  die_gracefully("A user id is required by this script.");
}

$photo_file = $conf['paths']['photos'] . $user_id . ".jpg";
$thumb_file = $conf['paths']['photos'] . $user_id . "_thumb.jpg";

if(file_exists($photo_file))
{
  if($mode=="full") output_photo();
  else
  {
    if(!file_exists($thumb_file)) create_thumbnail();
 
    output_thumbnail();
  }
}
else output_noimage();

function output_headers()
{
  global $thumb_file;

  header("Content-type: image/jpeg");
  //header("Content-Length: $filesize");
  header("Content-Disposition: inline; filename=" . $thumb_file);
}

function create_thumbnail()
{
  global $photo_file;
  global $thumb_file;

  $source_image = ImageCreateFromJpeg($photo_file);
  if(!$source_image){
    // The open failed for some reason
    output_noimage();
  }
  $width  = imagesx($source_image);
  $height = imagesy($source_image);

  $thumb_width = 100;
  $aspect = ((float) $width) / ((float) $height);
  //echo "height: $height, aspect: $aspect";
  $thumb_height = ((float) $thumb_width) / $aspect; 

  $thumb_image  = ImageCreateTrueColor($thumb_width, $thumb_height);

  // Resample the image and write to disk
  imagecopyresampled($thumb_image, $source_image, 0, 0, 0, 0, $thumb_width, $thumb_height,
                     $width, $height);
  
  //echo $aspect . " " . $thumb_width . " " . $thumb_height . " " . $width . " " . $height;
  imagejpeg($thumb_image, $thumb_file); 

  // Deallocate resources
  imagedestroy($source_image);
  imagedestroy($thumb_image);
}

function output_thumbnail()
{
  global $thumb_file;

  $source_image = imagecreatefromjpeg($thumb_file);
  if(!$source_image){
    // The open failed for some reason
    output_noimage();
  }
  // Create the image straight to stdout
  output_headers();
  imagejpeg($source_image); 
  imagedestroy($source_image);
}


function output_photo()
{
  global $photo_file;

  $source_image = imagecreatefromjpeg($photo_file);
  if(!$source_image){
    // The open failed for some reason
    output_noimage();
  }
  // Create the image straight to stdout
  output_headers();
  imagejpeg($source_image); 
  imagedestroy($source_image);
}

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