<!-- main navigation start -->
<div id="nav">
<ul>
{* Loop through top array of menu items *}
{foreach from=$navigation->nav_order item=menu name=nav}
<li{if $smarty.foreach.nav.first} class="first"{/if}{if $smarty.foreach.nav.last} class="last"{/if}>
<a id="{$navigation->nav.$menu.id}" class="daddy{if $section==$navigation->nav.$menu.section} active{/if}" accesskey="{$smarty.foreach.nav.index}" href="{$navigation->nav.$menu.url}">
{$navigation->nav.$menu.name|escape:"htmlall"}</a>
{* Loop through any submenu items, could put this in a subfile for readability *}
{section name=sub_nav loop=$navigation->nav.$menu.subitems}
{if $smarty.section.sub_nav.first}
<ul>
{/if}
  <li><a {if $subsection == $navigation->nav.$menu.section}class="active"{/if} title="{$navigation->nav.$menu.subitems[sub_nav].name|escape:"htmlall"}"
   href="{$navigation->nav.$menu.subitems[sub_nav].url}">{$navigation->nav.$menu.subitems[sub_nav].name|escape:"htmlall"}</a></li>
{if $smarty.section.sub_nav.last}
</ul>
{/if}
{/section}{* End submenus *}
</li>
{/foreach}{* End main menu *}
</ul>
</div>
<!-- main navigation ends -->
{if $section}
<!-- subsection navigation starts -->
<div id="secnav">
{section name=sub_nav loop=$navigation->nav.$section.subitems}
{if $smarty.section.sub_nav.first}
<ul>
{/if}
  <li><a {if $subsection == $navigation->nav.$section.section}class="active"{/if} title="{$navigation->nav.$section.subitems[sub_nav].name|escape:"htmlall"}"
   href="{$navigation->nav.$section.subitems[sub_nav].url}">{$navigation->nav.$section.subitems[sub_nav].name|escape:"htmlall"}</a></li>
{if $smarty.section.sub_nav.last}
</ul>
{/if}
{/section}{* End subsection *}
</div>
<!-- subsection navigation ends -->
{/if} {* $section *}
