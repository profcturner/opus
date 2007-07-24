{* Smarty *}
{if $format=="HTML"}
<table align="center" border="1">
<!-- Begin Broadsheet -->
{section name=data loop=$broadsheet_data}{$row_start}{section name=cell loop=$broadsheet_data[data]}{$broadsheet_data[data][cell]|escape:"htmlall"}{$separator}{/section}{$row_end}{/section}
</table>
<!-- End Broadsheet -->
{else}{* Not HTML *}
{section name=data loop=$broadsheet_data}{$row_start}{section name=cell loop=$broadsheet_data[data]}{$broadsheet_data[data][cell]}{$separator}{/section}{$row_end}{/section}
{/if}