{* Smarty *}
{* This is for viewing the list of students for a vacancy or company *}

{if $vacancy_id}
	{assign var="companyvacancy" value="vacancy"}
{else}
	{assign var="companyvacancy" value="company"}
{/if}

{* List the vacancies *}
<table border="1" align="center" width="100%">
{section name=vacancy loop=$vacancies}
<tr class="{cycle values="list_row_light,list_row_dark"}"><th align="left">
{if ($vacancies[vacancy].vacancy_id)}
Vacancy:
{else}
Company:
{/if}
{* Output vacancy / company details *}
</th>
<td align="left">
{$vacancies[vacancy].description|escape:"htmlall"}
{if ($vacancies[vacancy].vacancy_id)}
<span class="status-{$vacancies[vacancy].status}">
<small>({$vacancies[vacancy].status|escape:"htmlall"})</small>
</span>
{/if}
{if $vacancies[vacancy].vacancy_id!=$vacancy_id}
</td>
<td>
<a href="{$conf.scripts.company.edit}?mode=COMPANY_DISPLAYSTUDENTS&company_id={$company_id}&year={$year}&vacancy_id={$vacancies[vacancy].vacancy_id}#selected">
Show applications
({$vacancies[vacancy].applications})
</a>
</td>
</tr>
{else}
{* This is the selected company! Spit out the triage of students. *}
<a name="selected">
<!-- Start of student listing -->
<tr><td colspan="3">
{include file="company_student_list_section.tpl"
	students=$placed_students
	subtitle="Students placed with this $companyvacancy"
	show_link=TRUE
}

{include file="company_student_list_section.tpl"
	students=$available_students
	subtitle="Students still available"
	show_link=TRUE
}

{include file="company_student_list_section.tpl" 
	students=$unavailable_students
	subtitle="Students no longer available"
	show_link=FALSE
}
{if $student_count == 0}
There are no applicants to this {$companyvacancy} yet.
{else}
There
{if $student_count == 1}
is
{else}
 are
{/if}
{$student_count}
applicant{if $student_count != 1}s{/if}.
{/if}
</td>
</tr>
<!-- End of student listing -->

{/if}

{/section}
</table>
<p>NB: Applications were made against companies in the past. Although it is
still possible to see applications made directly against the company in future
all applications will be made against specific vacancies.</p>
<p>Students marked as <B><small>Changed</small></B> have had some alteration
made to their application since you have last seen it.</p>