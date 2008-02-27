{* Smarty *}

{include file="manage.tpl"}
<div id="brief">
{$object->display()}
</div>
{assign value=$object->display(true) var='xml_error'}
{if $xml_error}
{#xml_error#}
<div id="warning">
{$xml_error}
</div>
{/if}