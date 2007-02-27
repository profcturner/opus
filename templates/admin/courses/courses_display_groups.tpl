{* Smarty *}
{* Shows which groups (CVs / Assessment) that a course belongs to *}

<h3>Group Memberships</h3>
<div align="center">
{* This is centred since it has to coexist with legacy code for now *}
<form method="post" action="{$conf.scripts.admin.courses}?mode=Courses_UpdateCVGroup&course_id={$course_id}&school_id={$school_id}">
<table border="1">
<tr>
  <th>CV Group</th>
  <td>
    <select name="group_id">
      {html_options options=$cv_groups selected=$cv_group_id}
    </select>
  </td>
</tr>
</table>
<input type="submit" value="Change CV Group">
</form>

{if $number_assessment_groups}
<form method="post" action="{$conf.scripts.admin.courses}?mode=Courses_UpdateAssessmentGroups&course_id={$course_id}&school_id={$school_id}">

<table border="1">
<tr>
  <th>Assessment Group</th><th>From</th><th>To</th><th>Options</th>
</tr>
{section name=assessment_group loop=$assessment_groups}
{eval assign="group_id" var=$assessment_groups[assessment_group].group_id}
<tr>
  <td>{$assessment_groups[assessment_group].name|escape:"htmlall"}</td>
  <td>
    <input type="text" size="5" name="startyear_{$group_id}" value="{$assessment_groups[assessment_group].startyear}" />
  </td>
  <td>
    <input type="text" size="5" name="endyear_{$group_id}" value="{$assessment_groups[assessment_group].endyear}" />
  </td>
  <td>
    <a href="{$conf.scripts.admin.courses}?mode=Courses_RemoveAssessmentGroup&course_id={$course_id}&school_id={$school_id}&id={$assessment_groups[assessment_group].id}">[ Delete ]</a>
  </td>
</tr>
{/section}
</table>
<input type="submit" value="Update Assessment Group Information">
</form>
{else} {* $number_assessment_groups *}
<p><strong>Warning:</strong> There is no assessment group defined for this course yet.</p>
{/if} {* $number_assessment_groups *}
<form method="post" action="{$conf.scripts.courses.admin}?mode=Courses_AddAssessmentGroup&course_id={$course_id}&school_id={$school_id}">
<select name="group_id">{html_options options=$all_assessment_groups}</select> from year 
<input name="startyear" type="text" size="5" /> to year
<input name="endyear" type="text" size="5" />
<input type="submit" value="Add Assessment Group">
</form>
</div>