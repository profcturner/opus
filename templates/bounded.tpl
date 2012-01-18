

{* uses tables for layout still for now, this should move more to CSS *}

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>{#application_title#} {#version_text#} {#major_version#}.{#minor_version#} {if #page_title#} | {#page_title#|capitalize}{/if}</title>
<link REL="stylesheet" href="{$config.opus.url}/css/default.css" type="text/css" />
<link REL="stylesheet" href="{$config.opus.url}/css/{$currentgroup}/default.css" type="text/css" />
<style type="text/css"> @import url("{$config.opus.url}/css/local.css");</style>
<style type="text/css"> @import url("{$config.opus.url}/css/{$currentgroup}/local.css");</style>
<link href="{$config.opus.url}/css/login.css" rel="stylesheet" type="text/css" />
  </head>

<table width="800" height="600"  border="0" align="center" cellpadding="0" cellspacing="0">

 <tr align="center" valign="middle">
  <!-- Border Top -->
  <td width="20" height="19" align="right" valign="bottom" background="{$config.opus.url}/images/corner1.jpg"></td>
  <td width="760" height="19" background="{$config.opus.url}/images/top.jpg"><div align="center"></div></td>  
  <td width="20" height="19" align="left" valign="bottom" background="{$config.opus.url}/images/corner2.jpg"></td>
 </tr>

 <tr align="center" valign="middle">
  <!-- Border Left -->
  <td width="20" align="center" valign="middle" background="{$config.opus.url}/images/left.jpg"><div align="center"></div></td>
  <td width="760" align="center" valign="middle">

{* Start cell in which embedded content appears *}

  {if $show_banners}
    <!-- Top table (logos) -->
    <br />
    <table width="700" height="90"  border="0" align="center" cellspacing="0">
      <tr valign="top">
        <td>
          <div align="left"><img src="{if $ulster_logo}{#uu_logo#}{else}{#institution_logo#}{/if}" width="{if $ulster_lgo}{#uu_logo_width#}{else}{#institution_logo_width#}{/if}"></div></td>
          <td width="350"><div align="right"><a href="{$config.opus.url}"><img src="{$config.opus.url}/images/opus_blue.png" border="0"></a></div></td>
      </tr>
    </table>
    <br/>
  {/if}

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
  </td>
  <td width="20" align="center" valign="middle" background="{$config.opus.url}/images/right.jpg"><div align="center"></div></td>
  </tr>

 <tr align="center" valign="middle">
  <td width="20" height="18" align="right" valign="bottom" background="{$config.opus.url}/images/corner4.jpg"></td>
  <td width="760" height="18" background="{$config.opus.url}/images/bottom.jpg"><div align="center"></div></td>  
  <td width="20" height="18" align="left" valign="bottom" background="{$config.opus.url}/images/corner3.jpg"></td>
 </tr>
</table>
