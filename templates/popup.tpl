<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <script type="text/javascript" src='/usr/share/opus/html/javascript/jquery.js'/></script>

    

{if $refresh}
  <meta http-equiv="Refresh" content="{$refresh}">
{/if}
  <title>{$config.opus.title_short}{if $section} | {$section|capitalize}{/if}{if #page_title#} | {#page_title#|capitalize}{/if}</title>
  <link rel="stylesheet" href="{$config.opus.url}/css/{$system_theme}.css" type="text/css">
  <link rel="stylesheet" href="{$config.opus.url}/css/thickbox.css" type="text/css" media="screen" />
  <!--<link rel="stylesheet" href="{$config.opus.url}/css/{$currentgroup}/default.css" type="text/css">-->
  <link rel="stylesheet" href="{$config.opus.url}/css/default.css" type="text/css">
  <!--<link rel="stylesheet" href="{$config.opus.url}/css/{$currentgroup}/local.css" type="text/css">-->

  
  <!--[if gt IE 5.0]><![if lt IE 7]>
    <style type="text/css">
    {literal}
    #menu ul ul
    {
        display:none;
    }

    #menu ul li.menutop
    {
      {/literal}
        behavior: url( {$config.opus.url}/javascript/ie_menu_patch.htc  );
      {literal}
    }

    ul.makeMenu ul
    {  /* copy of above declaration without the > selector, except left position is wrong */
      display: none; position: absolute; top: 2px; left: 78px;
    }
    {/literal}
    </style>
  <![endif]><![endif]-->
  <link rel="stylesheet" href="{$config.opus.url}/css/print.css" type="text/css" media="print" />

</head>
<body>

{if $trails || $resources || $bookmarks }
		<div id="content_block">
{else}
		<div class="content_block" style="padding-right:10px; font-size:14px;">
{/if}
   <table class="popupTable" width="100%">
      <tr>
        <td width="100%" style="float:left">
        <br/>
{$content}
        </td>
        </tr>
		</table>
	</td>
</tr>
</table>
</div>

</body>
</html>
