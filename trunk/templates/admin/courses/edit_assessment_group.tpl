{* Smarty *}
{* Template for editing a given CV group *}

<h3>Editing Assessment Group: {$group_info.name|escape:"htmlall"}</h3>

<form method="post" action="{$conf.scripts.admin.courses}?mode=AssessmentGroups_Update&group_id={$group_info.group_id}">
<table>
<tr>
  <th>Name</th>
  <td><input type="text" size="40" name="name" value="{$group_info.name}"></td>
</tr>
<tr>
  <th>Comments</th>
  <td><input type="text" size="40" name="comments" value="{$group_info.comments}"></td>
</tr>
</table>
<input type="submit" value="Update">
</form>

<h3>Assessment Structure</h3>
<p><strong>Warning:</strong> you should not make substantive changes to the assessment regime that is active.</p>
{section name=assessment loop=$assessments}
{if $smarty.section.assessment.first}
<table class="opus_assessment_group_regime_list" border="1">
<tr><th>Description</th><th>Assessor</th><th>Year</th><th>Start</th><th>End</th><th>Weight</th><th>Options</th>
{/if}
<tr  class="{cycle values="list_row_light,list_row_dark"}">
  <td>{$assessments[assessment].student_description|escape:"htmlall"}</td>
  <td>{$assessments[assessment].assessor|escape:"htmlall"}</td>
  <td>{$assessments[assessment].year}</td>
  <td>{$assessments[assessment].start}</td>
  <td>{$assessments[assessment].end}</td>  
  <td>{$assessments[assessment].weighting|string_format:"%.2f"}</td>
  <td><a href="{$conf.scripts.admin.courses}?mode=AssessmentGroups_EditAssessment&group_id={$group_info.group_id}&cassessment_id={$assessments[assessment].cassessment_id}">[ Edit ]</a> <a href="{$conf.scripts.admin.courses}?mode=AssessmentGroups_DeleteAssessment&group_id={$group_info.group_id}&cassessment_id={$assessments[assessment].cassessment_id}">[ Remove ]</a></td>
</tr>
{if $smarty.section.assessment.last}
</table>
Total weighting for assessment regime is {$total_weight|string_format:"%.2f"}.
{/if}
{sectionelse}
<p>There are no assessments defined yet.</p>
{/section}

<h3>Add New Assessment Element</h3>
<p>Enter the details of a new assessment element below.</p>
<form method="post" action="{$conf.scripts.admin.courses}?mode=AssessmentGroups_AddAssessment&group_id={$group_info.group_id}">
<table>
<tr>
  <th>Assessment</th>
  <td>
  <select name="assessment_id">
  {section name=possible_assessment loop=$possible_assessments}<option value="{$possible_assessments[possible_assessment].assessment_id}">{$possible_assessments[possible_assessment].description}</option>{/section}
  </select>
  </td>
</tr>
<tr>
  <th>Student Description</th>
  <td><input name="student_description" size="30" type="text"></td>
</tr>
<tr>
  <th>Assessor</th>
  <td>
    <select name="assessor">
    <option>academic</option>
    <option>industrial</option>
    <option>student</option>
    <option>other</option>
    </select>
  </td>
</tr>
<tr>
  <th>Year</th>
  <td>
    <select name="year">
    <option value="-1">pre-placement year (-1)</option>
    <option value="0" SELECTED>placement year (0)</option>
    <option value="1">post-placement year (1)</option>
    </select>
  </td>
</tr>
<tr>
  <th>Start (DDMM)</th>
  <td><input type="text" name="start" size="5"></td>
</tr>
<tr>
  <th>End (DDMM)</th>
  <td><input type="text" name="end" size="5"></td>
</tr>
<tr>
  <th>Weighting</th>
  <td><input type="text" name="weighting" size="4" value="0"></td>
</tr>
</table>
<input type="submit" value="Add Assessment">
</form>