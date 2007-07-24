<h2 align="center">Contacts Search Results</h2>

{section name=hr_results loop=$hr_results}
{if $smarty.section.hr_results.first}
<h3 align="center">HR Contacts</h3>
<table>
<tr><th>Name</th><th>Action</th></tr>
{/if}
<tr><td>
{$hr_results[hr_results].title|escape:"htmlall"}
{$hr_results[hr_results].firstname|escape:"htmlall"}
{$hr_results[hr_results].surname|escape:"htmlall"}
</td>
<td>
<a href={$conf.scripts.company.contacts}?mode=CONTACT_BASICEDIT&contact_id={$hr_results[hr_results].contact_id}>[ Edit ]</a>
</td>
</tr>
{if $smarty.section.hr_results.last}
</table>
{/if}
{sectionelse}
No HR contacts matched your query
{/section}

{section name=is_results loop=$is_results}
{if $smarty.section.is_results.first}
<h3 align="center">Supervisors</h3>
<table>
<tr><th>Name</th><th>Company</th><th>Options</th></tr>
{/if}
<tr><td>
{$is_results[is_results].supervisor_title|escape:"htmlall"}
{$is_results[is_results].supervisor_firstname|escape:"htmlall"}
{$is_results[is_results].supervisor_surname|escape:"htmlall"}
</td>
<td>
{$is_results[is_results].company_name|escape:"htmlall"}
</td>
<td>
<a href={$conf.scripts.admin.studentdir}?mode=STUDENT_DISPLAYSTATUS&student_id={$is_results[is_results].student_id}#placement-{$is_results[is_results].placement_id}>[ Edit ]</a>

<a href={$conf.scripts.company.contacts}?mode=SUPERVISOR_NEWPASSWORD&placement_id={$is_results[is_results].placement_id}>[ New Password ]</a>


</td>
</tr>
{if $smarty.section.is_results.last}
</table>
{/if}
{sectionelse}
No supervisors matched your query
{/section}

