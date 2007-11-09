<?php

/**
* Handles the retrieval of photographs that OPUS already knows about
* @package OPUS
*/

/**
* Handles the retrieval of photographs that OPUS already knows about
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
* @todo need to add an upload mechanism
*
*/

class Photo
{
  /**
  * exports a given photo to stdout
  *
  * if no photo is available, a suitable blank will be sent
  *
  * @param int $user_id
  */
  function display_photo($user_id, $fullsize = false)
  {
    global $waf;
    global $config;

    $user_id = (int) $user_id;

    header("Content-type: application/jpeg");
    if($fullsize)
      header("Content-Disposition: filename=photo.jpeg");
    else
      header("Content-Disposition: inline; filename=photo.jpeg");

    $fullsize_name = $config['opus']['paths']['photos'] . $user_id . ".jpg";
    $thumb_name = $config['opus']['paths']['photos'] . $user_id . "_thumb.jpg";

    if($fullsize)
    {
      // No photo at all?
      if(!file_exists($fullsize_name))
      {
        Photo::display_blank_photo();
        return;
      }
      else
      {
        readfile($fullsize_name);
      }
    }
    else
    {
      // We want a thumbnail
      if(!file_exists($thumb_name))
      {
        // No thumbnail, try to create it first
        Photo::create_thumbnail($user_id);
      }
      // Still not there?
      if(!file_exists($thumb_name))
      {
        Photo::display_blank_photo();
        return;
      }
      else
      {
        readfile($thumb_name);
      }
    }
  }

  function display_blank_photo()
  {
    $image  = ImageCreateTrueColor(200, 200);
    $bgc = imagecolorallocate ($image, 0xFA, 0xFA, 0xFA);
    $tc  = imagecolorallocate ($image, 0, 0, 0);
    imagefilledrectangle ($image, 0, 0, 200, 200, $bgc);
    imagestring ($image, 3, 5, 5, "No Photo", $tc);

    imagejpeg($image);
    imeagedestroy($image);
  }

  function create_thumbnail($user_id)
  {
    global $waf;
    global $config;

    $user_id = (int) $user_id;

    $fullsize_name = $config['opus']['paths']['photos'] . $user_id . ".jpg";
    $thumb_name = $config['opus']['paths']['photos'] . $user_id . "_thumb.jpg";

    if(!file_exists($fullsize_name)) return; // Missing, no big deal
    if(!is_readable($fullsize_name))
    {
      // inaccessible, more of a problem
      $waf->log("unable to access photo file $fullsize_name", PEAR_LOG_DEBUG, 'debug');
      return;
    }

    $source_image = ImageCreateFromJpeg($fullsize_name);
    if(!$source_image) return; // Input failed for some reason

    // Get dimensions of the original
    $width  = imagesx($source_image);
    $height = imagesy($source_image);

    // Here is the width of the thumbnail, aspect to be preserved
    $thumb_width = 200;
    $aspect = ((float) $width) / ((float) $height);
    $thumb_height = ((float) $thumb_width) / $aspect; 
    // Create the new image
    $thumb_image  = ImageCreateTrueColor($thumb_width, $thumb_height);

    // Resample the image and write to disk
    imagecopyresampled($thumb_image, $source_image, 0, 0, 0, 0, $thumb_width, $thumb_height, $width, $height);
    imagejpeg($thumb_image, $thumb_name);

    // Deallocate resources
    imagedestroy($source_image);
    imagedestroy($thumb_image);
  }
}
?>