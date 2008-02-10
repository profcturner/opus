{* Smarty *}
<div id="table_list">
<table>
<!-- Begin Report Header -->
<tr>{section name=header loop=$header}<th>{$header[header]}</th>{/section}</tr>
<!-- End Report Header -->
<!-- Begin Report Data -->
{foreach from=$body item=line}
<tr class="{cycle name="cycle1" values="dark_row,light_row"}">
{foreach from=$line item=part}<td>{$part|escape:"htmlall"}</td>{/foreach}
</tr>
{/foreach}
<!-- End Report Data -->
</table>
</div>
