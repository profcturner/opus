{* Smarty *}
{* Template for viewing a company *}
<br>
<h2 align="center">Company Details<br>
{$company.name|escape:"htmlall"}</h2>
<table>
<tr>
  <th align="left">Location</th>
  <td>
{$company.town|escape:"htmlall"}, 
{$company.locality|escape:"htmlall"}, 
({$company.country|escape:"htmlall"})</td>
</tr>
{if $company.www}
<tr>
  <th align="left">Website</th>
  <td><a href="http://{$company.www}">{$company.www|escape:"htmlall"}</a></td>
</tr>
{/if}
<tr>
<th align="left" colspan="2"><br>Brief</th>
</tr>
<tr>
<td colspan="2" class="company">
{$company.brief}
<br />
</td>
</tr>
<tr><td colspan="2" align="center">
<strong>The University of Ulster is not responsible for content supplied by external companies.</strong>
</td>
</tr>
<tr>
<th valign="top" align="left">Activity Types</th>
<td>
{section name=company_activity loop=$company_activities}
{$company_activities[company_activity].name|escape:"htmlall"}<br>
{/section}
</td>
</tr>
<tr>
<th valign="top" align="left">Resources</th>
<td>
{section name=company_resource loop=$company_resources}
<a href="{$conf.scripts.user.resources}?resource_id={$company_resources[company_resource].resource_id}">
{$company_resources[company_resource].description|escape:"htmlall"}</a><br>
{sectionelse}
There are no private resources for this company.
{/section}
</td>
</tr>
{* List the vacancies for the company *}
<th valign="top" colspan="2" align="left">Vacancies</th>
</tr>
{section name=company_vacancy loop=$company_vacancies}
{if $smarty.section.company_vacancy.first}
<tr>
<td colspan="2">These are other vacancies starting in {$year}.</td>
</tr>
{/if}
<tr><td></td>
<td class="status-{$company_vacancies[company_vacancy].status}">
{if $company_vacancies[company_vacancy].vacancy_id != $vacancy_id}
<a href="{$conf.scripts.company.view}?mode=VacancyView&company_id={$company_id}&year={$year}&vacancy_id={$company_vacancies[company_vacancy].vacancy_id}{if $student_id}&student_id={$student_id}{/if}">
{$company_vacancies[company_vacancy].description|escape:"htmlall"}</a>,
Status: {$company_vacancies[company_vacancy].status|escape:"htmlall"}
</td>
</tr>
{/if}
{sectionelse}
<tr>
<td colspan="2">There are no vacancies for {$year} for this company.</td>
</tr>
{/section}
</table>