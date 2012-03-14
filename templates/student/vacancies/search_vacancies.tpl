<div id="table_list">
<table cellpadding="0" cellspacing="0" border="0">
  <tr>
    <th>Description</th>
    <th>Status</th>
    <th>Close Date</th>
    <th>Company Name</th>
    <th>Locality</th>
    <th class="action">View</th>
  </tr>
  {section name=vacancies loop=$vacancies}
  <tr class="{cycle name="cycle1" values="dark_row,light_row"}">
    <td>{$vacancies[vacancies].description|escape:"htmlall"}</td>
    <td><span class="status_{$vacancies[vacancies].status}">{$vacancies[vacancies].status|default:"None"}</span></td>
    <td>{$vacancies[vacancies].closedate|default:"None Specified"}</td>
    <td>{$vacancies[vacancies].company_name|escape:"htmlall"}</td>
    <td>{$vacancies[vacancies].locality|escape:"htmlall"}</td>
    <td class="action"><a href="?section=vacancies&function=view_vacancy&id={$vacancies[vacancies].id}">view</a></td>
  </tr>
  {*
  <tr class="{cycle name="cycle2" values="dark_row,light_row"}">
    <td colspan="3"><small><span class="status_{$vacancies[vacancies].status}">Status: {$vacancies[vacancies].status|default:"None"}</span> Close Date: {$vacancies[vacancies].closedate|default:"None Specified"}</small></td>
  </tr>
  *}
  {/section}
</table>
</div>

{section name=activities loop=$activities}
{sectionelse}
{#no_activities_selected#}
{/section}

{section name=vacancy_types loop=$vacancy_types}
{sectionelse}
{#no_vacancies_selected#}
{/section}
