{* Smarty *}

{* {#quick_links#} *}

{#annual_statistics#}
<div id="table_list">
<table>
<tr>
  <th>Year</th>
	<th>Vacancies</th>
	<th>Applications</th>
	<th>Placements</th>
	<th>Assessments</th>
</tr>
{foreach from=$annual_statistics key=year item=value}
<tr class="{cycle values="dark_row,light_row"}">
  <td>{$year}</td>
	<td>{$value.vacancy}</td>
	<td>{$value.application}</td>
	<td>{$value.placement}</td>
	<td>{$value.assessment|default:"0"}</td>
</tr>  
{/foreach}
</table>
</div>
