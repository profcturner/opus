{* Smarty *}

{* This template simplifies form opening, assuming the most important data *}
{* is in an array called "form" *}
<form name="{$form.name}" action="{$form.action}" method="{$form.method}" {if $form.charset}accept-charset="{$form.charset}"{/if}>
{foreach key=key item=value from=$form.hidden}
  <input type="hidden" name="{$key}" value="{$value}">
{/foreach}