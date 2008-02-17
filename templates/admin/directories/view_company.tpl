{* Smarty *}
<div id="table_manage">
  <table>
    <tr>
      <td class="property">Name</td>
      <td>{$company->name|escape:htmlall}</td>
    </tr>
      <td class="property">Activities</td>
      <td>
        {foreach from=$company_activity_names item=activity_name}
        {$activity_name|escape:"htmlall"}<br />
        {/foreach}
      </td>
    </tr>
    <tr>
      <td class="property">Address</td>
      <td>
        {if $company->address1}{$company->address1}{/if}<br />
        {if $company->address2}{$company->address2}{/if}<br />
        {if $company->address3}{$company->address3}{/if}<br />
        {if $company->town}{$company->town}{/if}<br />
        {if $company->postcode}{$company->postcode}{/if}<br />
        {if $company->locality}{$company->locality}{/if}<br />
        {if $company->country}{$company->country}{/if}<br />
      </td>
    </tr>
{if $company->www}
    <tr>
      <td class="property">Web Address</td>
      <td><a href="{$company->www}">{$company->www}</a></td>
    </tr>
{/if}
  </table>
</div>
<div id="brief">
{$company->display()}
</div>
<strong>{#institution#} {#disclaimer#}</strong>

{include file="list.tpl" objects=$resources headings=$resource_headings nopage=true}