<div id="table_list">
<table cellpadding="0" cellspacing="0" border="0">
  <tr>
    <th>Company Name</th>
    <th>Locality</th>
    <th class="action">View</th>
    <th class="action">Contacts</th>
    <th class="action">Vacancies</th>
    <th class="action">Students</th>
    <th class="action">Edit</th>
  </tr>
  {section name=companies loop=$companies}
  <tr class="{cycle name="cycle1" values="dark_row,light_row"}">
    <td>{$companies[companies]->name|escape:"htmlall"}</td>
    <td>{$companies[companies]->locality|escape:"htmlall"}</td>
    <td class="action"><a href="?section=directories&function=view_company&company_id={$companies[companies]->id}">view</a></td>
    <td class="action"><a href="?section=directories&function=manage_contacts&company_id={$companies[companies]->id}">contacts</a></td>
    <td class="action"><a href="?section=directories&function=manage_vacancies&company_id={$companies[companies]->id}">vacancies</a></td>
    <td class="action"><a href="?section=directories&function=manage_applicants&company_id={$companies[companies]->id}">students</a></td>
    <td class="action"><a href="?section=directories&function=edit_company&id={$companies[companies]->id}">edit</a></td>
  </tr>
  {/section}
</table>
</div>
