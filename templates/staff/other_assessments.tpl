{* Smarty *}
{* Displays other students for whom a staff member must prepare assessments *}

{section name=assessment loop=$other_assessments}
{if $smarty.section.assessment.first}
<h3>Assessments required for other students</h3>
<p>You are not the academic tutor for the following students, but are indicated
as responsible for assessments for them, at the appropriate time.</p>
<table align="center" border="1">
<tr><th>Student Name</th><th>Assessment</th><th>Mark</th></tr>
{/if}
<tr>
    <td>{$other_assessments[assessment].user_name|escape:"htmlall"}</td>
    <td><a href="{$conf.scripts.user.assessment}?mode=AssessmentDisplayForm&cassessment_id={$other_assessments[assessment].cassessment_id}&assessed_id={$other_assessments[assessment].assessed_id}">{$other_assessments[assessment].assessment_description|escape:"htmlall"}</a></td>
    <td>{$other_assessments[assessment].percentage}</td>
</tr>
{if $smarty.section.assessment.last}
</table>
{/if}
{sectionelse}

{/section}