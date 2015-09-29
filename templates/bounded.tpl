<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>{#application_title#} {#version_text#} {#major_version#}.{#minor_version#}</title>
<link href="{$config.opus.url}/css/login.css" rel="stylesheet" type="text/css" />
<link href="{$config.opus.url}/css/blue.css" rel="stylesheet" type="text/css" />
  </head>

<body>
	
<div id="bounded_page">

   
  {if $show_banners}     
  <!-- Top table (logos) -->
  <br />
  <table width="700" height="90"  border="0" align="center" cellspacing="0">
    <tr valign="top">
    <td>
		<div align="left"><img src="{#institution_logo#}"></div></td>
    <td width="350"><div align="right"><img src="{$config.opus.url}/images/{#application_logo#}"></div></td>
    </tr>
  </table><br>
  {/if}
     
   
  <!-- Main Body table (text area's) -->

{* Start cell in which embedded content appears *}

{if $action_links}
    <div id="action_area">
Actions 
{section loop=$action_links name=action_link}
      <span id="action_button"><a href="?{$action_links[action_link][1]}">{$action_links[action_link][0]}</a></span>
{/section}
    </div>
{/if}
    <div id="content_block">
{$content} 
    </div>

{* End cell in which main embedded content appears *}

</div>
