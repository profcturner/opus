{* Smarty *}
{foreach from=$header item=part name=header}"{$part|escape:quotes}"{if !$smarty.foreach.header.last}{$tab}{/if}{/foreach}
{foreach from=$body item=line}

{foreach from=$line item=part name=body}"{$part|regex_replace:"/[\r\t\n]/":" "|escape:"quote"}"{if !$smarty.foreach.body.last}{$tab}{/if}{/foreach}
{/foreach}
