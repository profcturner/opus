{* Smarty *}

<ul>
{foreach from=$lastitems->queue item=lastitem}
<li><a href="{$lastitem->url}">{$lastitem->human_long}</a></li>
{/foreach}
</ul>