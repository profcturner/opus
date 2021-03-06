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

  <!--<link rel="stylesheet" href="{$config.opus.url}/css/{$currentgroup}/default.css" type="text/css">-->
  <!--<link rel="stylesheet" href="{$config.opus.url}/css/{$currentgroup}/local.css" type="text/css">-->
  <link rel="stylesheet" href="{$config.opus.url}/css/{$system_theme}.css" type="text/css">
  <link rel="stylesheet" href="{$config.opus.url}/css/default.css" type="text/css">
  <link rel="stylesheet" href="{$config.opus.url}/css/print.css" type="text/css" media="print" />
  <link rel="stylesheet" href="{$config.opus.url}/css/thickbox.css" type="text/css" media="screen" />
  <link rel="apple-touch-icon-precomposed" sizes="57x57" href="apple-touch-icon-57x57.png" />
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="apple-touch-icon-114x114.png" />
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="apple-touch-icon-72x72.png" />
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="apple-touch-icon-144x144.png" />
  <link rel="apple-touch-icon-precomposed" sizes="60x60" href="apple-touch-icon-60x60.png" />
  <link rel="apple-touch-icon-precomposed" sizes="120x120" href="apple-touch-icon-120x120.png" />
  <link rel="apple-touch-icon-precomposed" sizes="76x76" href="apple-touch-icon-76x76.png" />
  <link rel="apple-touch-icon-precomposed" sizes="152x152" href="apple-touch-icon-152x152.png" />
  <link rel="icon" type="image/png" href="favicon-196x196.png" sizes="196x196" />
  <link rel="icon" type="image/png" href="favicon-96x96.png" sizes="96x96" />
  <link rel="icon" type="image/png" href="favicon-32x32.png" sizes="32x32" />
  <link rel="icon" type="image/png" href="favicon-16x16.png" sizes="16x16" />
  <link rel="icon" type="image/png" href="favicon-128.png" sizes="128x128" />
  <meta name="application-name" content="&nbsp;"/>
  <meta name="msapplication-TileColor" content="#FFFFFF" />
  <meta name="msapplication-TileImage" content="mstile-144x144.png" />
  <meta name="msapplication-square70x70logo" content="mstile-70x70.png" />
  <meta name="msapplication-square150x150logo" content="mstile-150x150.png" />
  <meta name="msapplication-wide310x150logo" content="mstile-310x150.png" />
  <meta name="msapplication-square310x310logo" content="mstile-310x310.png" />
  

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
    <div id="institution_logo">{if #institution_logo#}<img src="{#institution_logo#}" {if #institution_logo_height#}height="{#institution_logo_height#}"{/if} title="Institution Logo"/>{else}{$config.opus.institution}{/if}
    </div>
    <div id="application_logo">{if $config.opus.logo}<img src="images/{$config.opus.logo}" title="Application Logo"/>{else}{$config.opus.title}{/if}
    </div>
    
{*	Currently off 
    <div id="mini_menu">
       <div id="mini_menu_item"><a href="">preferences</a></div>
      <div id="mini_menu_item"><a href="">print version</a></div> 
      <div id="mini_menu_item"><a href="">help</a></div>
    </div>
*}

{*	Currently off
<div id="username">{$user.opus.salutation} {$user.opus.firstname} {$user.opus.lastname}</div>
<div id="groups">
      Groups :
{foreach from=$user.groups key="key" item="group" name="group_loop"}
{if $currentgroup == $group}&nbsp;{$group}{else}&nbsp;<a href="?section=home&function=home&currentgroup={$group}">{$group}</a>{/if}
{/foreach}
</div>
*}
    
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
   

		<div id="submenu">
			{foreach from=$nav key="sec_name" item="sec" name="sec_loop"}
				{if $section == $sec[0][1]}
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
							
							<div id="username">{$test}{$user.opus.salutation} {$user.opus.firstname} {$user.opus.lastname}</div>
					
					  </ul>
					  
				 
				  
				{/if}
		{/foreach}
         
         </div>	
    </div>
    
<div id="main_content">
{*	Currently off
	{if $navigation_history}
		<div id="navigation_history">
	{section loop=$navigation_history name=link} 
	{if !$smarty.section.link.last}
		  <a href="{$navigation_history[link][1]}">{$navigation_history[link][0]}</a>&nbsp;<b>></b>&nbsp;{else}<b>{$navigation_history[link][0]}</b>{/if}
	{/section}
		</div>
	{/if}
*}

   {if !$welcome_page}
    <div id="tag_line">
    {#tag_line#|default:$tag_line|default:"No \$tag_line"}
    </div>
   {/if}
   
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

{config_load file="lang_en.conf" section=footer}
</div> {* end of main content div *}

{*{if $trails || $resources || $bookmarks }
	<div id="tools">
		{if $currentgroup == "student" || $currentgroup == "academic"}
			{include file=tools/tools.tpl}
		{/if}
	</div>
{/if}*}

  <div id="footer">
	  <p>
	{if $currentgroup == "admin"}
		<a href="?section=information&function=help_directory">{#help_directory#|capitalize}</a> |
	{else}
		<a href="?section=information&function=help_directory" class="thickbox">{#help_directory#|capitalize}</a> |
	{/if}
		<a href="?section=information&function=about" class="thickbox">{#about#}</a> |
		<a href="?section=information&function=copyright" class="thickbox">{#copyright#}</a> |
		<a href="?section=information&function=privacy" class="thickbox">{#privacy#}</a> |
		<a href="?section=information&function=terms_conditions" class="thickbox">{#terms_conditions#}</a> |
		<a href="http://foss.ulster.ac.uk/projects/opus/issues" target="_blank">{#get_support#}</a> 

		<div id="compile_time"><a href="">{if $config.opus.benchmarking}<small>Compile Time: {$benchmark->elapsed()|string_format:"%.2f"} seconds</small>{/if}</a></div>
	  </p>
  </div>
  
</div> {* end of page wrapper div *}

</body>
</html>
