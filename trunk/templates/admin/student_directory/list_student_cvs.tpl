<p>This student is in the CV group <em>{$group_name}</em>.<br />

They have {$count_completed_cvs} completed CVs within the PDSystem, and {$count_archived_cvs} archived CVs. Please
note that you will not have access to those CV templates that are not used by the student's CV group.</p>

<h3>Available CVs</h3>
{section name=cv loop=$cvs}
{if $smarty.section.cv.first}
<table>
<tr><th>CV Name</th><th>Options</th></tr>
{/if}
<tr class="{cycle values="list_row_light,list_row_dark"}">
  <td>{$cvs[cv].name|escape:"htmlall"}</td>
  <td><a href="{$conf.scripts.student.pdpcvpdf}?student_id={$student_id}&template_id={$cvs[cv].template_id}">[ View ]</a> {if $cvs[cv].approved}<a href="{$conf.scripts.admin.studentdir}?mode=RevokeCV&student_id={$student_id}&template_id={$cvs[cv].template_id}">[ Revoke Approval ]</a>{/if}</td>
</tr>
{if $smarty.section.cv.last}
</table>
{/if}
{sectionelse}
No appropriate CVs are yet complete.
{/section}

{section name=invalid_cv loop=$invalid_cvs}
{if $smarty.section.invalid_cv.first}
<h3>Disallowed CVs</h3>
<p>The following CVs were registered as complete, but disallowed due to the the CV group settings, or a lack of appropriate approval.</p>
<table>
<tr><th>CV Name</th><th>Problem</th><th>Options</th></tr>
{/if}
<tr class="{cycle values="list_row_light,list_row_dark"}">
  <td>{$invalid_cvs[invalid_cv].name|escape:"htmlall"}</td>
  <td>{$invalid_cvs[invalid_cv].problem|escape:"htmlall"}</td>
  <td>{if $invalid_cvs[invalid_cv].template_allowed}<a href="{$conf.scripts.student.pdpcvpdf}?student_id={$student_id}&template_id={$invalid_cvs[invalid_cv].template_id}">[ View ]</a> {if !$invalid_cvs[invalid_cv].approved}<a href="{$conf.scripts.admin.studentdir}?mode=ApproveCV&student_id={$student_id}&template_id={$invalid_cvs[invalid_cv].template_id}">[ Approve CV] </a>{/if}{/if}
  </td>
</tr>
{if $smarty.section.invalid_cv.last}
</table>
{/if}
{/section}