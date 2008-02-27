<?php

/**
* allows lightweight multipart messaging from OPUS
* @package OPUS
*/

/**
* allows lightweight multipart messaging from OPUS
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/

class OPUSMail
{
  var $to;
  var $extra_headers;
  var $subject;
  var $message_body;
  var $attachments;

  /**
  * sets up initial variables for a message
  *
  * @param string $to the recipient addresses for the message
  * @param string $message_body the main text of the message
  * @param string $extra_headers any extra headers should be added here
  */
  function __construct($to, $subject, $message_body, $extra_headers="", $from="")
  {
    $this->from = $from;
    $this->subject = $subject;
    $this->to = $to;
    $this->message_body = $message_body;
    $this->extra_headers = $extra_headers;

    $this->attachments = array();
  }

  /**
  * adds an attachment given directly as a string
  *
  * @param string $attachment a binary string with the file to attach
  * @param string $content_type an optional content type (mime type)
  * @param string $filename an optional filename to use
  */
  function add_direct_attachment($attachment, $content_type="", $filename="")
  {
    $new_attachment = array();
    $new_attachment['storage'] = 'direct';
    $new_attachment['data'] = $attachment;
    $new_attachment['type'] = $content_type;
    $new_attachment['filename'] = $filename;

    array_push($this->attachments, $new_attachment);
  }

  /**
  * adds an attachment given directly as a file to fetch
  *
  * @param string $attachment a filename to fetch from
  * @param string $content_type an optional content type (mime type)
  * @param string $filename an optional filename to use
  */
  function add_file_attachment($attachment, $content_type="", $filename="")
  {
    $file = @file_get_contents($attachment);

    // If we couldn't fetch the file, fail silently
    if(!file) return;

    // Call the other function to attach directly
    $this->add_direct_attachment($file, $content_type);
  }

  /**
  * form the mime separated, encoded list of attachments
  *
  * @param string $mime_boundary the mime boundary separator to use
  */
  function form_message_body($mime_boundary)
  {
    $newline = "\r\n";
    $body = "";
    foreach($this->attachments as $attachment)
    {
      // Mime boundary;
      $body .= $newline . $newline .
        "--$mime_boundary" . $newline;

      // Content header if needed
      if(!empty($attachment['type']))
      {
        $body .= "Content-Type: " . $attachment['type'];
        if(!empty($attachment['filename']))
        {
          $body .= "; name=\"" . $attachment['filename'] .
          "\"";
        }
        $body .= $newline . "Content-Transfer-Encoding: base64" . $newline;
        $body .= $newline . $newline;

      }
      // Encoded part
      $body .= $this->encode($attachment);
    }
    return($body);
  }

  /**
  * encode an attachment with MIME
  *
  * @param array $attachment the internal array holding attachment details
  * @return the mime encoded attachments
  * @todo probably not everything should be encoded here, e.g. text/plain should bypass
  */
  function encode($attachment)
  {
    $encoded_data =
      chunk_split(base64_encode($attachment['data']));

    return($encoded_data);
  }

  function send()
  {
    $newline = "\r\n";
    // The from address goes in extra_headers
    if(!empty($this->from))
      $this->extra_headers .= "From: " . $this->from . $newline;

    // Hardcode extra headers to contain a version
    //$this->extra_headers .= "X-OPUS-Version: " . OPUS::get_version() . $newline;

    $mime_boundary = "MIME-" . md5(time());
    // Form message body, we need to know if material was (successfully) attached
    $encoded_attachments = $this->form_message_body($mime_boundary);

    if(!empty($encoded_attachments))
    {
      $this->extra_headers .= "Content-Type: multipart/mixed; boundary=\"$mime_boundary\""
        . $newline . "MIME-version: 1.0" . $newline;
      $message_body .= 
        "This is a multi-part message in MIME format." . $newline . $newline .
        "--$mime_boundary" . $newline . "Content-Type: text/plain" . 
        $newline . $newline;
    }
    // Add "main" text
    $message_body .= $this->message_body;
    // Add the encoded attachments
    if(!empty($encoded_attachments))
    {
      $message_body .= $encoded_attachments .
        $newline . "--$mime_boundary--" . $newline;

    }

    mail($this->to, $this->subject, $message_body, $this->extra_headers);
  }
}
?>