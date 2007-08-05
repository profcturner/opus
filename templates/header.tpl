{* Smarty *}
<!--

  OPUS {$opus_version}
  http://foss.ulster.ac.uk/projects/opus/

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
<link rel="stylesheet" type="text/css" href="http://{$conf.webhost}/{$conf.paths.base}jsincludes/htmlarea/htmlarea.css">
<title>{$conf.institution|escape:"htmlall"} | {$page_title|escape:"htmlall"}</title>
<script language="JavaScript" type="text/javascript" src="http://{$conf.webhost}/{$conf.paths.base}jsincludes/menu_jb.js"></script>
{* Load HTML Area if the "editor" component of page is set *}
{if $legacy_page.editor}
<script language="JavaScript" type="text/javascript">
  _editor_url = "/jsincludes/htmlarea/";
  _editor_lang = "en";
</script>
<script language="JavaScript" type="text/javascript" src="http://{$conf.webhost}/{$conf.paths.base}jsincludes/htmlarea/htmlarea.js"></script>
<script language="JavaScript" type="text/javascript" src="http://{$conf.webhost}/{$conf.paths.base}jsincludes/htmlarea/dialog.js"></script>
<script language="JavaScript" type="text/javascript" src="http://{$conf.webhost}/{$conf.paths.base}jsincludes/htmlarea/en.js"></script>
<script language="JavaScript" type="text/javascript" src="http://{$conf.webhost}/{$conf.paths.base}jsincludes/htmlarea/pms_custom.js"></script>
{/if}
<script language="JavaScript" type="text/javascript" src="http://{$conf.webhost}/{$conf.paths.base}jsincludes/popcalendar.js"></script>
</head>
{* Start the real header *}
{if !($legacy_page.nopmsheader)}
{*
<TABLE BORDER="0" WIDTH="100%%" BGCOLOR="#000031" CELLSPACING="0" CELLPADDING="0">
  <TR>
    <TD WIDTH="50%%" ALIGN="left"><IMG SRC="/images/uulogo.gif" WIDTH="263" HEIGHT="69"></TD>
    <TD WIDTH="50%%" class="align-right"><IMG SRC="/images/pmslogo.gif" WIDTH="263" HEIGHT="69"></TD>
  </TR>
  <TR>
    <TD WIDTH="100%%" COLSPAN="2" BGCOLOR="#C9e1e8" ALIGN="center">
      <B>{$page_title|escape:"htmlall"}</B>
    </TD>
  </TR>
</TABLE>
*}

{/if}
{if $legacy_page.editor}
<body onload="HTMLArea.init(); HTMLArea.onload=initEditor">
{else}
<body>
{/if}

