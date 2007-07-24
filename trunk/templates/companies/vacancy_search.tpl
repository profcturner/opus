{* Smarty *}
{* Template for displaying a list of vacancies arising from a search *}

<!-- Start of Vacancy Listing -->
<H3 align="center">Vacancies</H3>
{section name=vacancy loop=$vacancies}
  {if $smarty.section.vacancy.first}
<table align="center" border="1">
  <tr><th>Name</th><th>Company</th><th>Locality</th></tr>
  {/if}
  <tr>
    <td>
{if $edit}
{if ($session.user.type == "root" || $session.user.type == "admin")}
<a href="{$conf.scripts.company.edit}?mode=VacancyEdit&vacancy_id={$vacancies[vacancy].vacancy_id}">
{/if}
{else}
<a href="{$conf.scripts.company.directory}?mode=VacancyView&vacancy_id={$vacancies[vacancy].vacancy_id}{if $student_id}&student_id={$student_id}{/if}">
{/if}
{$vacancies[vacancy].description|escape:"htmlall"}
{if $edit}
{if ($session.user.type == "root" || $session.user.type == "admin")}
</a>
{/if}
{else}
</a>
{/if}

    </td>
    <td>{$vacancies[vacancy].company_name|escape:"htmlall"}</td>
    <td>{$vacancies[vacancy].locality|escape:"htmlall"}</td>
</tr>
<tr class="list_row_dark">
    <td colspan="3">
<small>
Close date:{$vacancies[vacancy].closedate},
<span class="status-{$vacancies[vacancy].status}"> Status:{$vacancies[vacancy].status}</span>
</small></td>
  </tr>
  {if $smarty.section.vacancy.last}
</table>
<p align="center">There are {$smarty.section.vacancy.total} vacancies in this listing.</p>
  {/if}
{sectionelse}
<p align="center">No vacancies matched the search criteria</p>
{/section}
<!-- End of Vacancy Listing -->
