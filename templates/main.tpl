<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
     <script type="text/javascript" src='../../opus/javascript/jquery.js'></script>
     <script type="text/javascript" src='../../opus/javascript/thickbox.js'></script>


{if $refresh}
  <meta http-equiv="Refresh" content="{$refresh}" />
{/if}
  <title>{$config.opus.title_short}{if $section} | {$section|capitalize}{/if}{if #page_title#} | {#page_title#|capitalize}{/if}</title>

  <link rel="stylesheet" href="{$config.opus.url}/css/{$system_theme}.css" type="text/css">
  <!--<link rel="stylesheet" href="{$config.opus.url}/css/{$currentgroup}/default.css" type="text/css">-->
  <link rel="stylesheet" href="{$config.opus.url}/css/default.css" type="text/css">
  <!--<link rel="stylesheet" href="{$config.opus.url}/css/{$currentgroup}/local.css" type="text/css">-->

  <link rel="stylesheet" href="{$config.opus.url}/css/print.css" type="text/css" media="print" />
  <link rel="stylesheet" href="{$config.opus.url}/css/thickbox.css" type="text/css" media="screen" />
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

{* Load HTML Area if the "editor" component of page is set *}
{if $xinha_editor}
<script type="text/javascript" src="{$config.opus.tinymce_url}"></script>
{literal}
<script type="text/javascript">
  tinyMCE.init({
    // General options
    mode : "exact",
    elements : "xhtmlArea",
    theme : "advanced",
    plugins : "safari,pagebreak,style,layer,save,advlink,emotions,iespell,inlinepopups,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras",

    // Theme options
    theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,outdent,indent,blockquote,|,formatselect,",
    theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,undo,redo,|,link,unlink,cleanup,code,|,fullscreen",
    theme_advanced_buttons3 : "",
    theme_advanced_buttons4 : "",
    theme_advanced_blockformats : "p,address,pre,h4",
    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left",
    theme_advanced_statusbar_location : "bottom",
    theme_advanced_resizing : true,

    // Example content CSS (should be your site CSS)
    content_css : "css/default.css",

    gecko_spellcheck : true,

    // Replace values for the template plugin
    template_replace_values : {
    }
  });
</script>
<!-- /TinyMCE -->
{/literal}
<!-- /TinyMCE -->
</head>
<body>
{else}
</head>
<body>
{/if}
<div id="page_wrapper">
  <div id="header">  {* start of the header div *}
    <div id="app_title">{if $config.opus.logo}<img src="images/{$config.opus.logo}" title="{$config.opus.title}"/>{else}{$config.opus.title}{/if}
    </div>
    <div id="app_tagline">{if $config.opus.tagline}<img src="images/{$config.opus.tagline}" title="{$config.opus.title}"/>{else}{$config.opus.title}{/if}
    </div>
    <div id="mini_menu">
      {* Currently off <div id="mini_menu_item"><a href="">preferences</a></div>
      <div id="mini_menu_item"><a href="">print version</a></div> 
      <div id="mini_menu_item"><a href="">help</a></div> *}
    </div>
{*    <div id="username">{$user.opus.salutation} {$user.opus.firstname} {$user.opus.lastname}</div>
      <div id="groups">
      Groups :
{foreach from=$user.groups key="key" item="group" name="group_loop"}
{if $currentgroup == $group}&nbsp;{$group}{else}&nbsp;<a href="?section=home&function=home&currentgroup={$group}">{$group}</a>{/if}
{/foreach}
    </div>*}
    <div id="menu">
      <ul>

{foreach from=$nav key="sec_name" item="sec" name="sec_loop"}
{if $sec_name != "search"}
        <li class="menutop{if $sec[0][2]} {$sec[0][2]}{/if}">

{if $config[opus][cleanurls]}
          <a title="click to access section: {$sec_name}" {if $sec[0][4]}class="{$sec[0][4]}"{else}class="{if $section == "$sec[0][1]"}current{/if}{if $smarty.foreach.sec_loop.first}first{/if}"{/if} href="{if $sec[0][5]}{$sec[0][5]}{else}{$sec[0][1]}/{$sec[0][3]}{/if}">{$sec_name}</a>
{else}        
          <a title="click to access section: {$sec_name}" {if $sec[0][4]}class="{$sec[0][4]}"{else}class="{if $section == "$sec[0][1]"}current{/if}{if $smarty.foreach.sec_loop.first}first{/if}"{/if} href="{if $sec[0][5]}{$sec[0][5]}{else}?section={$sec[0][1]}&function={$sec[0][3]}&page=1{/if}">{$sec_name}</a>

{/if}
        </li>
{/if}
{/foreach}
      </ul>
    </div>
{foreach from=$nav key="sec_name" item="sec" name="sec_loop"}
{if $section == $sec[0][1]}
    <div id="submenu">
      <ul>
{section loop=$sec name="subsec"}
        <li> 
{if $config[opus][cleanurls]} 
          <a title="go to subsection: {$sec[subsec][0]|capitalize}" {if $subsection == "$sec[subsec][2]"}class="current"{/if} href="{if $sec[subsec][5]}{$sec[subsec][5]}{else}{$sec[subsec][1]}/{$sec[subsec][3]}{/if}">{$sec[subsec][0]|capitalize}</a>
{else}
          <a title="go to subsection: {$sec[subsec][0]|capitalize}" {if $subsection == "$sec[subsec][2]"}class="current"{/if} href="{if $sec[subsec][5]}{$sec[subsec][5]}{else}?section={$sec[subsec][1]}&function={$sec[subsec][3]}&page=1{/if}">{$sec[subsec][0]|capitalize}</a>
{/if} 
        </li>
{/section}
		<li><a title= "Preferences" href="?section=main&function=edit_preferences" class="thickbox">Preferences</a></li>

<div id="username">{$user.opus.salutation} {$user.opus.firstname} {$user.opus.lastname}</div>
      </ul>
     </div>
  
{/if}
{/foreach}

    </div>
  <div id="main_content">
{*
{if $navigation_history}
    <div id="navigation_history">
{section loop=$navigation_history name=link} 
{if !$smarty.section.link.last}
      <a href="{$navigation_history[link][1]}">{$navigation_history[link][0]}</a>&nbsp;<b>></b>&nbsp;{else}<b>{$navigation_history[link][0]}</b>{/if}
{/section}
    </div>
{/if}
*}
    <div id="tag_line">{#tag_line#|default:$tag_line|default:"No \$tag_line"}
    </div>
{if ($opus_closed)}
  <div id="warning">{#opus_closed#}{if $user.opus.user_type == 'root'}{#opus_closed_root#}{/if}</div>
{/if}
{if ($opus_oldschema)}
  <div id="warning">{#opus_oldschema#}</div>
{/if}
{if $SQL_error}
    <div id="sql_error">{#sql_error#}{if $config.waf.debugging}<br />[{$SQL_error}]{/if} </div>
{/if}
{if $action_links}

    <div id="action_area">
				{section loop=$action_links name=action_link}
					<span id="action_button"><a {if $action_links[action_link][2] === "thickbox"} class="thickbox" {/if} title="{$action_links[action_link][0]}" href="?{$action_links[action_link][1]}">{$action_links[action_link][0]|capitalize}</a></span>
				{/section}
    </div>
{/if}
    <div id="content_block">
{$content} 
    </div>

{config_load file=lang_en.conf section=footer}
  </div>
  <div id="footer">
  <p>
    <a href="?section=information&function=copyright">{#copyright#}</a> |
    <a href="?section=information&function=privacy">{#privacy#}</a> |
    <a href="?section=information&function=terms_conditions">{#terms_conditions#}</a> |
    <a href="?section=information&function=about">{#about#}</a> |
    <a href="http://foss.ulster.ac.uk/support/?func=additem&group=opus" target="_blank">{#get_support#}</a> |
    <a href="http://foss.ulster.ac.uk/bugs/?func=additem&group=opus" target="_blank">{#report_a_bug#}</a>


	<div id="compile_time"><a href="">{if $config.opus.benchmarking}<small>Compile Time: {$benchmark->elapsed()|string_format:"%.2f"} seconds</small>{/if}</a></div>
  </p></div>
</div>

</body>
</html>
