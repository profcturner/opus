{* Smarty *}
<!--

  OPUS {$opus_version}

  Copyright applies.

  Development Team:

  Principal developer
  Dr Colin Turner

  Contributions from
  Andrew Hunter
  Ronan O'Donnell
  
  Design advice
  Mr Ron Laird

-->
<html>
<head>
{if $session.preferences.style}
<link rel="stylesheet" type="text/css" href="{$conf.paths.css}{$session.preferences.style}">
{else}
<link rel="stylesheet" type="text/css" href="{$conf.paths.css}blue.css">
{/if}
<link rel="stylesheet" type="text/css" href="{$conf.paths.style}">
{if $session.display_prefs.edit_channels}
<link rel="stylesheet" type="text/css" href="{$conf.paths.css}placement_channel_edit.css">
{/if}
<link rel="stylesheet" type="text/css" href="http://pms.ulster.ac.uk/jsincludes/htmlarea/htmlarea.css">
<title>{$conf.institution|escape:"htmlall"} | {$page.title|escape:"htmlall"}</title>
<!-- <BASEFONT NAME="Arial, Helvetica, sans-serif" SIZE="2"> -->
{* Load HTML Area if the "editor" component of page is set *}
{if $page.editor}
<script language="JavaScript" type="text/javascript">
  _editor_url = "/jsincludes/htmlarea/";
  _editor_lang = "en";
</script>
<script language="JavaScript" type="text/javascript" src="http://pms.ulster.ac.uk/jsincludes/htmlarea/htmlarea.js"></script>
<script language="JavaScript" type="text/javascript" src="http://pms.ulster.ac.uk/jsincludes/htmlarea/dialog.js"></script>
<script language="JavaScript" type="text/javascript" src="http://pms.ulster.ac.uk/jsincludes/htmlarea/en.js"></script>
<script language="JavaScript" type="text/javascript" src="http://pms.ulster.ac.uk/jsincludes/htmlarea/pms_custom.js"></script>
{/if}
<script language="JavaScript" type="text/javascript" src="http://pms.ulster.ac.uk/jsincludes/popcalendar.js"></script>
</head>
{* Start the real header *}
{if !($page.nopmsheader)}
{*
<TABLE BORDER="0" WIDTH="100%%" BGCOLOR="#000031" CELLSPACING="0" CELLPADDING="0">
  <TR>
    <TD WIDTH="50%%" ALIGN="left"><IMG SRC="/images/uulogo.gif" WIDTH="263" HEIGHT="69"></TD>
    <TD WIDTH="50%%" class="align-right"><IMG SRC="/images/pmslogo.gif" WIDTH="263" HEIGHT="69"></TD>
  </TR>
  <TR>
    <TD WIDTH="100%%" COLSPAN="2" BGCOLOR="#C9e1e8" ALIGN="center">
      <B>{$page.title|escape:"htmlall"}</B>
    </TD>
  </TR>
</TABLE>
*}

{/if}
{if $page.editor}
<body onload="HTMLArea.init(); HTMLArea.onload=initEditor">
{else}
<body>
{/if}

