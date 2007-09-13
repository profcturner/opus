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
  <tr class="{cycle values="dark_row,light_row"}">
    <td>{$vacancies[vacancies].description|escape:"htmlall"}</td>
    <td>{$vacancies[vacancies].company_name|escape:"htmlall"}</td>
    <td>{$vacancies[vacancies].locality|escape:"htmlall"}</td>
    <td class="action"><a href="">view</a></td>
    <td class="action"><a href="?section=directories&function=edit_vacancy&id={$vacancies[vacancies].id}">edit</a></td>
  </tr>
  {/section}
</table>
</div>
