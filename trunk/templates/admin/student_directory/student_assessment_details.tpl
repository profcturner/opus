{* Smarty *}

<h2>Detailed Assessment Breakdown</h2>

<p>Please pick a specific assessment to examine, from the
{$group_name|escape:"htmlall"} assessment regime for students
seeking placement in {$year}.</p>

{section name=assessment loop=$assessments}
{if $smarty.section.assessment.first}
<table>
<tr><th>Assessment</th><th>Assessor</th></tr>
{/if}
<tr class="{cycle values="list_row_light,list_row_dark"}">
  <td><a href="{$conf.scripts.admin.student_dir}?mode=StudentAssessmentDetails&cassessment_id={$assessments[assessment].cassessment_id}&format={$format}&year={$year}&group_id={$group_id}">{$assessments[assessment].student_description|escape:"htmlall"}</a>
  </td>
  <td>{$assessments[assessment].assessor|escape:"htmlall"}
  </td>
</tr>

{if $smarty.section.assessment.last}
</table>
{/if}
{sectionelse}
<p>This regime has no assessments available to list yet.</p>
{/section}