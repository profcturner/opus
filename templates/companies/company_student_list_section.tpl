{* Smarty *}
{* A subsection of company_student_list.tpl which triages various students *}

{section name=placed loop=$students}
{if $smarty.section.placed.first}
<h3 align="center">{$subtitle|escape:"htmlall"}</h3>
<table align="center" width="95%">
{/if}

<tr><td>
{* Template variable $show_link is a boolean which controls whether a link is shown or not *}
{* This is overridden for admin or root users *}
{if ($session.user.type == "admin" || $session.user.type == "root")}
{assign var="show_link" value="true"}
{/if}
{if $show_link}
<a href="{$students[placed].cv_link}">
{/if}
{$students[placed].title|escape:"htmlall"}
{$students[placed].firstname|escape:"htmlall"}
{$students[placed].surname|escape:"htmlall"}
{if $show_link}
</a>
{/if}
</td>
<td>{$students[placed].course_name|escape:"htmlall"}</td><td align="right"><b><small>{$students[placed].changed}</small></b></td></tr>
<tr class="list_row_dark"><td></td><td><small>
Applied : {$students[placed].created}
{if $students[placed].cover}
, <a href="{$conf.scripts.company.cover}?company_id={$company_id}&vacancy_id={$vacancy_id}&student_id={$students[placed].id}">Letter</a>
{/if}
{if $students[placed].modified}
, Modified : {$students[placed].modified}
{/if}
</td>
<td align="right"><small>
<a href="{$conf.scripts.user.helpdir}?student_id={$students[placed].id}">Help</a></small>
</td></tr>
<TR><TD></TD><TD COLSPAN="2">
{* Small used to reduce problem with newline at end of form *}
<table cellpadding="0" width="100%"><tr><td>
{* Start of form to update the status *}
<small>
<small>
<form action="{$conf.scripts.company.edit}" method="post">
<big>Status</big>
<input type="hidden" name="mode" value="COMPANYSTUDENT_STATUS_UPDATE">
<input type="hidden" name="year" value="{$year}">
<input type="hidden" name="vacancy_id" value="{$vacancy_id}">
<input type="hidden" name="company_id" value="{$company_id}">
<input type="hidden" name="student_id" value="{$students[placed].student_id}">
<select name="status">
{html_options output=$status_options values=$status_options selected=$students[placed].status}
</select>
<input class="button" type="submit" value="Update">
</form>
</small>
</small>
</td>
<td align="right" valign="top">
 <a href="{$conf.scripts.company.edit}?mode=EmailCV&student_id={$students[placed].student_id}&company_id={$company_id}&vacancy_id={$vacancy_id}&template_id={$students[placed].prefcvt}"><small>[ Email CV to me ]</small></a>
{if ($session.user.type == "admin" || $session.user.type == "root")}
<a href="{$conf.scripts.admin.studentdir}?mode=STUDENT_DISPLAYSTATUS&student_id={$students[placed].student_id}">
<small>[ View Status ]</small></a>
{/if}
</td>
</tr>
</table>
</td>
</tr>

{if $smarty.section.placed.last}
</table>
{/if}
{/section}
