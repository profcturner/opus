{* Smarty *}
{* Template produces a listing of resources for editing *}

<h2>Resources Available</h2>
{if $company_name}<h2>for company {$company_name|escape:"htmlall"}</h2>{/if}

{section name=resource loop=$resources}
{if $smarty.section.resource.first}
<table class="opus_resource_edit_table" border="1">
<tr>
  <th>Description</th><th>Channel</th><th>Language</th><th>Options</th>
</tr>
{/if}
<tr class="{cycle values="list_row_light,list_row_dark"}">
{*  <td>{$resources[resource].lookup|escape:"htmlall"}</td> *}
  <td>{$resources[resource].description|escape:"htmlall"}</td>
  <td>{$resources[resource].channel_name|escape:"htmlall"}</td>
  <td>{$resources[resource].language_name|escape:"htmlall"}</td>
  <td><a href="{$conf.scripts.admin.resourcedir}?mode=ResourceEdit&lang={$resources[resource].language_id}&lookup={$resources[resource].lookup}&resource_id={$resources[resource].resource_id}">[&nbsp;Edit&nbsp;]</a> <a href="{$conf.scripts.admin.resourcedir}?mode=ResourceDelete&lang={$resources[resource].language_id}&lookup={$resources[resource].lookup}&resource_id={$resources[resource].resource_id}"">[&nbsp;Delete&nbsp;]</a></td>
</tr>
{if $smarty.section.resource.last}
</table>
{/if}
{sectionelse}
<p>There are no resources available.</p>
{/section}
