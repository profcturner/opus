{* Smarty *}
{if $format=="HTML"}
<table align="center" border="1">
<!-- Begin Data -->
{$row_start}created{$seperator}username{$seperator}{section name=question loop=$questions}{$questions[question]->name|escape:"htmlall"}{$seperator}{/section}{$row_end}

{section name=answer loop=$answers}
{$row_start}{section name=cell loop=$answers[answer]}{$answers[answer][cell]}{$seperator}{/section}{$row_end}
{/section}
</table>
<!-- End Data -->
{else}{* Not HTML *}
{$row_start}created{$seperator}username{$seperator}{section name=question loop=$questions}{$questions[question]->name|escape:"htmlall"}{$seperator}{/section}{$row_end}{section name=answer loop=$answers}{$row_start}{section name=cell loop=$answers[answer]}{$answers[answer][cell]}{$seperator}{/section}{$row_end}{/section}
{/if}

