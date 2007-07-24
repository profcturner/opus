{* Smarty *}
{* Template produces a listing of resources for viewing *}

<h2>Resources Available</h2>

{section name=resource loop=$resources}
{if $smarty.section.resource.first}
<table class="opus_resource_list_table" border="1">
<tr>
  <th>Description</th><th>Channel</th><th>Language</th>
</tr>
{/if}
<tr class="{cycle values="list_row_light,list_row_dark"}">
{*  <td>{$resources[resource].lookup|escape:"htmlall"}</td> *}
  <td><a href="{$conf.scripts.user.resources}?resource_id={$resources[resource].resource_id}">{$resources[resource].description|escape:"htmlall"}</a></td>
  <td>{if $resources[resource].channel_id}<acronym title="{$resources[resource].channel_description|escape:"htmlall"}">{/if}{$resources[resource].channel_name|escape:"htmlall"}{if $resources[resource].channel_id}</acronym>{/if}</td>
  <td>{$resources[resource].language_name|escape:"htmlall"}</td>
</tr>
{if $smarty.section.resource.last}
</table>
There are {$count} resources available to you.
{/if}
{sectionelse}
<p>There are no resources available.</p>
{/section}
