{* Smarty *}

<div id="table_manage">
  <table>
    <tr>
      <td class="property">Description</td>
      <td>{$vacancy->description|escape:htmlall}</td>
    </tr>
    <tr>
      <td class="property">Applications</td>
      <td>
        <span class="status_{$vacancy->status}">
          {$vacancy->status}
          {if $vacancy->status == "open"}{#status_open#}{/if}
          {if $vacancy->status == "closed"}{#status_closed#}{/if}
          {if $vacancy->status == "special"}{#status_special#}{/if}
        </span>
      </td>
    </tr>
    <tr>
      <td class="property">Close Date</td>
      <td>{$vacancy->closedate|default:"None"}</td>
    </tr>
    <tr>
      <td class="property">Activities</td>
      <td>
        {foreach from=$vacancy_activity_names item=activity_name}
        {$activity_name|escape:"htmlall"}<br />
        {/foreach}
      </td>
    </tr>
    <tr>
      <td class="property">Address</td>
      <td>
        {if $vacancy->address1}{$vacancy->address1}{/if}<br />
        {if $vacancy->address2}{$vacancy->address2}{/if}<br />
        {if $vacancy->address3}{$vacancy->address3}{/if}<br />
        {if $vacancy->town}{$vacancy->town}{/if}<br />
        {if $vacancy->postcode}{$vacancy->postcode}{/if}<br />
        {if $vacancy->locality}{$vacancy->locality}{/if}<br />
        {if $vacancy->country}{$vacancy->country}{/if}<br />
      </td>
    </tr>
    <tr>
      <td class="property">Start Date</td>
      <td>{$vacancy->jobstart|default:"unknown"}</td>
    </tr>
    <tr>
      <td class="property">End Date</td>
      <td>{$vacancy->jobend|default:"unknown"}</td>
    </tr>
    <tr>
      <td class="property">Salary</td>
      <td>{$vacancy->salary|default:"unknown"}</td>
    </tr>
{if $vacancy->www}
    <tr>
      <td class="property">Web Address</td>
      <td><a href="{$vacancy->www}">{$vacancy->www}</a></td>
    </tr>
{/if}
  </table>
</div>
<div id="brief">
{$vacancy->display()}
</div>
<strong>{#institution#} {#disclaimer#}</strong>
<br /><br />
<h3>{#company_details#}</h3>
<br />
{include file="admin/directories/view_company.tpl"}