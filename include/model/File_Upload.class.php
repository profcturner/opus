<?php

/**
* Encapsulates file uploading
* @package OPUS
*/
require_once "model/Mimetype.class.php";
/**
* Encapsulates file uploading
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/

class File_Upload
{
  /**
  * Checks if an uploaded file is acceptable
  *
  * The proferred file is checked for various internal PHP errors in the
  * upload process, whether it is of a known and valid mime type, and
  * whether it is too large. The file is deleted on appropriate error conditions.
  *
  * @param string $file_var_name the request fields for the file upload
  * @param int $space_allowed the amount of space in bytes that are allowed, zero indicates no limit
  * @return false on upload success, a config file lookup to an error on failure
  * @todo check proposed_name
  */
  function test_file($file_var_name, $proposed_name="", $space_allowed = 0)
  {
    $waf =& UUWAF::get_instance();

    $result = array();
    // failsafe condition
    $result['error'] = true;
    $result['filesize'] = filesize($_FILES[$file_var_name]['tmp_name']);
    $result['mimetype'] = $_FILES[$file_var_name]['type'];

    // Check for various basic failures...
    switch($_FILES[$file_var_name]['error'])
    {
      case UPLOAD_ERR_INI_SIZE:
      case UPLOAD_ERR_FORM_SIZE:
        $waf->log("uploaded file exceeds PHP or form limits");
        $result['config_error'] = "error:file_upload:too_big";
        return($result);
        break;
      case UPLOAD_ERR_PARTIAL:
        // We need to delete the partial upload (security)
        $waf->log("only partial file received from upload");
        unlink($_FILES['userfile']['tmp_name']);   
        $result['config_error'] = "error:file_upload:partial_file";
        return($result);
        break;
      case UPLOAD_ERR_NO_FILE:
        $waf->log("no file was received");
        $result['config_error'] = "error:file_upload:no_file";
        return($result);
        break;
    }

    // Check for mime type violations
    $mimetypes = Mimetype::get_all("where type='" . $_FILES[$file_var_name]['type'] . "'");
    if(!count($mimetypes))
    {
      $result['config_error'] = "error:file_upload:unknown_mimetype";
      return($result);
    }

    $mime_allowable = false;
    foreach($mimetypes as $mimetype)
    {
      if($mimetype->uploadable == 'yes')
      {
        $mime_allowable = true;
        $result['mime_id'] = $mimetype->id;
      }
    }
    if(!$mime_allowable)
    {
      $waf->log("file is not of an allowed type [" . $_FILES[$file_var_name]['type'] . "]", PEAR_LOG_ERR, 'general');
      $result['config_error'] = "error:file_upload:disallowed_mimetype";
      return($result);
    }

    // Finally check for size
    if($space_allowed)
    {
      if(filesize($_FILES[$file_var_name]['tmp_name']) > $space_allowed)
      {
        $waf->log("insufficient space for uploaded file");
        $result['config_error'] = "error:file_upload:insufficient_space";
        return($result);
      }
    }

    // Must be OK! ;-)
    $result['error'] = false;
    return $result;
  }

  function move_file($file_var_name, $new_name)
  {
    $waf =& UUWAF::get_instance();

    if(move_uploaded_file($_FILES[$file_var_name]['tmp_name'], $new_name))
    {
      $waf->log("Upload of file successful", PEAR_LOG_INFO);
    }
    else
    {
      $waf->log("Move of uploaded file failed", PEAR_LOG_ERR);
      $waf->log("Move of uploaded file failed, destination was [$new_name]", PEAR_LOG_ERR, 'debug');
    }
  }
}

?>