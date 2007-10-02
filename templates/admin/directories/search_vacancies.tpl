<div id="table_list">
<table cellpadding="0" cellspacing="0" border="0">
  <tr>
    <th>Description</th>
    <th>Company Name</th>
    <th>Locality</th>
    <th class="action">View</th>
    <th class="action">Edit</th>
  </tr>
  {section name=vacancies loop=$vacancies}
  <tr class="{cycle name="cycle1" values="dark_row,light_row"}">
    <td>{$vacancies[vacancies].description|escape:"htmlall"}</td>
    <td>{$vacancies[vacancies].company_name|escape:"htmlall"}</td>
    <td>{$vacancies[vacancies].locality|escape:"htmlall"}</td>
    <td rowspan="2" class="action"><a href="?section=directories&function=view_vacancy&id={$vacancies[vacancies].id}">view</a></td>
    <td rowspan="2"class="action"><a href="?section=directories&function=edit_vacancy&id={$vacancies[vacancies].id}">edit</a></td>
  </tr>
  <tr class="{cycle name="cycle2" values="dark_row,light_row"}">
    <td colspan="3"><small><span class="status_{$vacancies[vacancies].status}">Status: {$vacancies[vacancies].status|default:"None"}</span> Close Date: {$vacancies[vacancies].closedate|default:"None Specified"}</small></td>
  </tr>
  {/section}
</table>
</div>
