{* Smarty *}

<div id="nav">
<ul>
{section name=tab loop=$tabs}
<li {if $smarty.section.loop.first}class=first{/if}>
<a class="daddy{if $tabs_active == $tabs[tab].handle} active{/if}" title="{$tabs[tab].name|escape:"htmlall"}" href="{$tabs[tab].url}">{$tabs[tab].name|escape:"htmlall"}</a>
</li>
{/section}
</ul>
</div>
