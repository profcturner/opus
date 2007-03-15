<?php

/**
**	view_cvpdf.php
**
** This student script attempts to produce a one page summary from
** the available CV information in PDF form.
**
** Initial coding : Colin Turner
*/

// The include files
include('common.php');
include('authenticate.php');
include('lookup.php');

// Connect to the database on the server
db_connect()
  or die("Unable to connect to server");

// Authenticate user so that the right people see the right thing
auth_user("user");

$header_test = $conf['scripts']['student']['index'];

if(is_student())
  $header_test = $conf['scripts']['student']['index'];

if(is_company())
  $header_test = $conf['scripts']['company']['index'];

if(is_supervisor())
  $header_test = $conf['scripts']['supervisor']['index'];

if(is_staff())
  $header_test = $conf['scripts']['staff']['index'];

if(is_admin())
  $header_test = $conf['scripts']['admin']['index'];

header("Location: " . $header_test);

?>