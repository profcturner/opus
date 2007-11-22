{* Smarty *}

{section name=new_vacancy loop=$new_vacancies}
{if $smarty.section.new_vacancy.first}
<h3 align="center">New Vacancies</h3>
<p align="center">The following vacancies have been added
{if $days}
in the last {$days} days.
{else}
since your last visit.
{/if}
</p>
<ol>
{/if}
<li>
<a href="{$conf.scripts.company.directory}?mode=VacancyView&vacancy_id={$new_vacancies[new_vacancy].vacancy_id}&{if $student_id}student_id={$student_id}{/if}">
{$new_vacancies[new_vacancy].description|escape:"htmlall"}
<small>({$new_vacancies[new_vacancy].company_name|escape:"htmlall"})</small>
</a>
{if $session.user.type == "root" || $session.user.type == "admin"}
<a href="{$conf.scripts.company.edit}?mode=VacancyEdit&vacancy_id={$new_vacancies[new_vacancy].vacancy_id}{if $student_id}&student_id={$student_id}{/if}">[ Edit ]</a>
{/if}
</li>
{if $smarty.section.new_vacancy.last}
</ol>
{/if}
{sectionelse}
No new vacancies
{if $days}
in the last {$days} days.
{else}
since your last visit.
{/if}
<br>
{/section}

{section name=mod_vacancy loop=$mod_vacancies}
{if $smarty.section.mod_vacancy.first}
<h3 align="center">Modified Vacancies</h3>
<p align="center">The following vacancies have been modified
{if $days}
in the last {$days} days.
{else}
since your last visit.
{/if}
</p>
<ol>
{/if}
<li>
<a href="{$conf.scripts.company.directory}?mode=VacancyView&vacancy_id={$mod_vacancies[mod_vacancy].vacancy_id}&{if $student_id}student_id={$student_id}{/if}">
{$mod_vacancies[mod_vacancy].description|escape:"htmlall"}
<small>({$mod_vacancies[mod_vacancy].company_name|escape:"htmlall"})</small>
</a>
{if $session.user.type == "root" || $session.user.type == "admin"}
<a href="{$conf.scripts.company.edit}?mode=VacancyEdit&vacancy_id={$new_vacancies[new_vacancy].vacancy_id}{if $student_id}&student_id={$student_id}{/if}">[ Edit ]</a>
{/if}
</li>
{if $smarty.section.mod_vacancy.last}
</ol>
{/if}
{sectionelse}
No modified vacancies
{if $days}
in the last {$days} days/
{else}
since your last visit.
{/if}
<br>
{/section}
