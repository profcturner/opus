<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
{if $refresh}
  <meta http-equiv="Refresh" content="{$refresh}">
{/if}
  <title>{$config.opus.title_short}{if $section} | {$section|capitalize}{/if}{if #page_title#} | {#page_title#|capitalize}{/if}</title>
  <link REL="stylesheet" href="{$config.opus.url}/css/default.css" type="text/css">
  <link REL="stylesheet" href="{$config.opus.url}/css/{$currentgroup}/default.css" type="text/css">
  <style type="text/css"> @import url("{$config.opus.url}/css/local.css");</style>
  <style type="text/css"> @import url("{$config.opus.url}/css/{$currentgroup}/local.css");</style>
</head>
<body>
<div id="page_wrapper">
  <div id="header">  {* start of the header div *}
    <div id="app_title">{if $config.opus.logo}<img src="images/{$config.opus.logo}" title="{$config.opus.title}"/>{else}{$config.opus.title}{/if}
    </div>
    <div id="app_tagline">{$config.opus.tagline} {#version#} {$config.opus.version}.{$config.opus.minor_version}.{$config.opus.patch_version}
    </div>
    <div id="mini_menu">
      <div id="mini_menu_item"><a href="">preferences</a></div>
      <div id="mini_menu_item"><a href="">print version</a></div> 
      <div id="mini_menu_item"><a href="">help</a></div>
    </div>
    <div id="username">{$user.opus.salutation} {$user.opus.firstname} {$user.opus.lastname}</div>
    <div id="groups">
      Groups :
{foreach from=$user.groups key="key" item="group" name="group_loop"}
{if $currentgroup == $group}&nbsp;{$group}{else}&nbsp;<a href="?section=home&function=home&currentgroup={$group}">{$group}</a>{/if}
{/foreach}
    </div>
    <div id="menu">
      <ul>
{foreach from=$nav key="sec_name" item="sec" name="sec_loop"}
{if $sec_name != "search"}
        <li {if $sec[0][2]}class="{$sec[0][2]}"{/if}>

{if $config[opus][cleanurls]}
          <a title="click to access section: {$sec_name|capitalize}" {if $sec[0][4]}class="{$sec[0][4]}"{else}class="{if $section == "$sec[0][1]"}current{/if}{if $smarty.foreach.sec_loop.first}first{/if}"{/if} href="{$sec[0][1]}/{$sec[0][3]}">{$sec_name|capitalize}</a>
{else}        
          <a title="click to access section: {$sec_name|capitalize}" {if $sec[0][4]}class="{$sec[0][4]}"{else}class="{if $section == "$sec[0][1]"}current{/if}{if $smarty.foreach.sec_loop.first}first{/if}"{/if} href="?section={$sec[0][1]}&function={$sec[0][3]}">{$sec_name|capitalize}</a>
{if $sec_name != "logout"}
          <ul>
{section loop=$sec name="subsec"}
            <li> 
              <a title="go to subsection: {$sec[subsec][0]|capitalize}" {if $subsection == "$sec[subsec][2]"}class="current"{/if} href="?section={$sec[subsec][1]}&function={$sec[subsec][3]}">{$sec[subsec][0]|capitalize}</a>
            </li>
{/section}
          </ul>
{/if}
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
          <a title="go to subsection: {$sec[subsec][0]|capitalize}" {if $subsection == "$sec[subsec][2]"}class="current"{/if} href="{#application_url#}{$sec[subsec][1]}/{$sec[subsec][3]}">{$sec[subsec][0]|capitalize}</a>
{else}
         <a title="go to subsection: {$sec[subsec][0]|capitalize}" {if $subsection == "$sec[subsec][2]"}class="current"{/if} href="?section={$sec[subsec][1]}&function={$sec[subsec][3]}">{$sec[subsec][0]|capitalize}</a>
{/if} 
        </li>
{/section}
      </ul>
     </div>
  
{/if}
{/foreach}

    </div>
  <div id="main_content">
    <div id="page_title">{#page_title#|default:$page_title|default:"No \$page_title"}
    </div>
{if $navigation_history}
    <div id="navigation_history">
{section loop=$navigation_history name=link} 
{if !$smarty.section.link.last}
      <a href="{$navigation_history[link][1]}">{$navigation_history[link][0]}</a>&nbsp;<b>></b>&nbsp;{else}<b>{$navigation_history[link][0]}</b>{/if}
{/section}
    </div>
{/if}
    <div id="tag_line">{#tag_line#|default:$tag_line|default:"No \$tag_line"}
    </div>
{if $SQL_error}
    <div id="sql_error">{#sql_error#}{if $config.waf.debugging}<br />[{$SQL_error}]{/if} </div>
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

{config_load file=lang_en.conf section=footer}
  </div>
  <div id="footer">
    <a href="?section=information&function=help_directory">{#help_directory#}</a> |
    <a href="http://foss.ulster.ac.uk/bugs/?func=additem&group=opus">{#copyright#}</a> |
    <a href="http://foss.ulster.ac.uk/bugs/?func=additem&group=opus">{#privacy#}</a> |
    <a href="http://foss.ulster.ac.uk/bugs/?func=additem&group=opus">{#terms_conditions#}</a> |
    <a href="http://foss.ulster.ac.uk/bugs/?func=additem&group=opus">{#get_support#}</a> |
    <a href="http://foss.ulster.ac.uk/bugs/?func=additem&group=opus">{#report_a_bug#}</a>
    {if $config.opus.benchmarking}| <small>Compile Time: {$benchmark->elapsed()|string_format:"%.2f"} seconds</small>{/if}
  </div>
</div>

</body>
</html>