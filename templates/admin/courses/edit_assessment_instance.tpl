{* Smarty *}
{* Allows the editing of an existing assessment instance *}

<h3>Edit Assessment Instance {$assessment_name|escape:"htmlall"}</h3>

<p>This will allow you to edit the specific instance of an assessment used within the
assessment regime {$group_name|escape:"htmlall"}. You should still undertake this action
with care if some students have already been assessed.</p>

<form method="post" action="{$conf.scripts.admin.courses}?mode=AssessmentGroups_UpdateAssessment&group_id={$group_id}&cassessment_id={$cassessment_id}">
<table>
<tr>
  <th>Student Description</th>
  <td><input name="student_description" size="30" type="text" value="{$assessment_info.student_description}"></td>
</tr>
<tr>
  <th>Assessor</th>
  <td>
    <select name="assessor">
    {html_options values=$assessor_options output=$assessor_options selected=$assessment_info.assessor}
    </select>
  </td>
</tr>
<tr>
  <th>Year</th>
  <td>
    <select name="year">
    {html_options options=$assessment_years selected=$assessment_info.year}
    </select>
  </td>
</tr>
<tr>
  <th>Start (DDMM)</th>
  <td><input type="text" name="start" size="5" value="{$assessment_info.start}"></td>
</tr>
<tr>
  <th>End (DDMM)</th>
  <td><input type="text" name="end" size="5" value="{$assessment_info.end}"></td>
</tr>
<tr>
  <th>Weighting</th>
  <td><input type="text" name="weighting" size="4" value="{$assessment_info.weighting}"></td>
</tr>
<tr>
  <th>Outcomes</th>
  <td><textarea name="outcomes" wrap="virtual" rows="10" cols="50">{$assessment_info.outcomes|escape:"htmlall"}</textarea></td>
</tr>
</table>
<input type="submit" value="Update Assessment">
</form>