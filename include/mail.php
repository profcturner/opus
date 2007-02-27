<?php

include("Mail.php");
include("Mail/mime.php");

function send_email($headers, $body, $files, $recipient)
{
  $mime = new Mail_mime("\n");
  
  $mime->setTXTBody($body);

  // $files is an array of embedded _data_ not filenames
  foreach($files as $file)
  {
    $mime->addAttachment($file['data'],
			 $file['type'],
			 $file['name'],
			 FALSE);
  }
  $body = $mime->get();
  $headers = $mime->headers($headers);

  $mail =& Mail::factory('mail');
  $mail->send($recipient, $headers, $body);
}
?>