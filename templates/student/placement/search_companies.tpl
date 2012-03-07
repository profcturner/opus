<div id="table_list">
<table cellpadding="0" cellspacing="0" border="0">
  <tr>
    <th>Company Name</th>
    <th>Locality</th>
    <th class="action">View</th>
  </tr>
  {section name=companies loop=$companies}
  <tr class="{cycle name="cycle1" values="dark_row,light_row"}">
    <td>{$companies[companies].name|escape:"htmlall"}</td>
    <td>{$companies[companies].locality|escape:"htmlall"}</td>
    <td class="action"><a class="thickbox" href="?section=vacancies&function=view_company&company_id={$companies[companies].id}">view</a></td>
  </tr>
  {/section}
</table>
</div>
