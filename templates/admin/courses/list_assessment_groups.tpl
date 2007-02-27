{* Smarty *}
{* Template for listing all assessment groups *}

<p>You can group courses together so that you can assess them all in a particular way.
Of course, a group could contain only one course is you want particular handling.</p>

<h3>Assessment Groups</h3>
{section name=assessment_group loop=$assessment_groups}
{if $smarty.section.assessment_group.first}
<table class="opus_assessment_group_list" border="1">
<tr><th>Name</th><th>Description</th><th>Options</th></tr>
{/if}
<tr  class="{cycle values="list_row_light,list_row_dark"}">
  <td>{$assessment_groups[assessment_group].name|escape:"htmlall"}</td>
  <td>{$assessment_groups[assessment_group].comments|escape:"htmlall"}</td>
  <td><a href="{$conf.scripts.admin.courses}?mode=AssessmentGroups_Edit&group_id={$assessment_groups[assessment_group].group_id}">[ Edit ]</a> <a href="{$conf.scripts.admin.courses}?mode=AssessmentGroups_Delete&group_id={$assessment_groups[assessment_group].group_id}">[ Delete ]</a></td>
</tr>
{if $smarty.section.assessment_group.last}
</table>
{/if}
{sectionelse}
<p>There are no assessment groups defined yet.</p>
{/section}

<h3>Add New Group</h3>
<form method="post" action="{$conf.scripts.admin.courses}?mode=AssessmentGroups_Insert">
Name: 
<input name="name" size="30" type="text">
<input type="submit" value="Add New Group">
</form>