{* Smarty *}

<div id="table_list">
<table cellpadding="0" cellspacing="0" border="0">
  <tr>
    <th>Report Name</th>
    <th>Description</th>
    <th class="action">View</th>
  </tr>
  {section name=report loop=$reports}
  <tr class="{cycle name="cycle1" values="dark_row,light_row"}">
    <td>{$reports[report].human_name|escape:"htmlall"}</td>
    <td>{$reports[report].description|escape:"htmlall"}</td>
    <td class="action"><a href="?section=information&function=report_input&name={$reports[report].unique_name}&input_stage=1">view</a></td>
  </tr>
  {/section}
</table>
</div>
