{* Smarty *}
{* Template for displaying a list of vacancies available in a company *}

<!-- Start of Vacancy Listing -->
<H3 align="center">Vacancies</H3>
<p align="center">
<form method="post" action="{$conf.scripts.company.edit}">
<input type="hidden" name="mode" value="CompanyVacancyList">
<input type="hidden" name="company_id" value="{$company_id}">
Show only vacancies for a single year
<input type="checkbox" name="showyear" {if $showyear}CHECKED{/if} >
for students seeking placement in 
<input type="text" size="5" name="year" value="{$year}">
<input type="submit" value="Show Vacancies">
</form>
</p>

{section name=vacancy loop=$vacancies}
  {if $smarty.section.vacancy.first}
{if $showyear}
<p align="center">Only vacancies for the students seeking placement in the academic year {$year}-{$year+1} are 
being shown. Use the form to show other vacancies.</p>
{/if}
<table align="center" border="1">
  <tr><th>Description</th><th>Locality</th><th>Close Date</th><th>Start Year</th><th>Status</th><th>Options</th></tr>
  {/if}
  <tr class="{cycle values="list_row_light,list_row_dark"}">
    <td>{$vacancies[vacancy].description|escape:"htmlall"}</td>
    <td>{$vacancies[vacancy].locality|escape:"htmlall"}
    <td>{$vacancies[vacancy].closedate}</td>
    <td>{$vacancies[vacancy].start_year}</td>
    <td class="status-{$vacancies[vacancy].status}">{$vacancies[vacancy].status}</td>
    <td><a href="{$conf.scripts.company.edit}?mode=VacancyEdit&vacancy_id={$vacancies[vacancy].vacancy_id}">[ Edit ]</a>
<a href="{$conf.scripts.company.edit}?mode=VacancyClone&vacancy_id={$vacancies[vacancy].vacancy_id}">[ Clone ]</a>
{if ($session.user.type == "root") || ($session.user.type == "admin")}
        <a href="{$conf.scripts.company.edit}?mode=VacancyDelete&vacancy_id={$vacancies[vacancy].vacancy_id}">[ Delete ]</a></td>
{/if}
  </tr>
  {if $smarty.section.vacancy.last}
</table>
<p align="center">There are {$smarty.section.vacancy.total} vacancies.</p>
  {/if}
{sectionelse}
<h3 align="center">There are currently no vacancies listed for this company</h3>
{if $showyear}
<p align="center">Only vacancies for the students seeking placement in the academic year {$year}-{$year+1} are 
being shown.</p>
{/if}
{/section}
<p align="center"><a href="{$conf.scripts.company.edit}?mode=VacancyAdd&company_id={$company_id}">
Click here to add a new vacancy
</a> <br />(Use the "clone" option add a new vacancy based on an existing one)
</p>
<!-- End of Vacancy Listing -->

