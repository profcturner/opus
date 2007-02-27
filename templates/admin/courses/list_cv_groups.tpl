{* Smarty *}
{* Template for listing all CV groups *}

<p>You can create policies that dictate how CVs can be used here.
Normally many courses are grouped together in a particular way, but
a group can consist of only one course if you want particular handling.</p>

<h3>CV Groups</h3>
{section name=cv_group loop=$cv_groups}
{if $smarty.section.cv_group.first}
<table class="opus_cv_group_list" border="1">
<tr><th>Name</th><th>Description</th><th>Options</th></tr>
{/if}
<tr class="{cycle values="list_row_light,list_row_dark"}">
  <td>{$cv_groups[cv_group].name|escape:"htmlall"}</td>
  <td>{$cv_groups[cv_group].comments|escape:"htmlall"}</td>
  <td><a href="{$conf.scripts.admin.courses}?mode=CVGroups_Edit&group_id={$cv_groups[cv_group].group_id}">[ Edit ]</a> <a href="{$conf.scripts.admin.courses}?mode=CVGroups_Delete&group_id={$cv_groups[cv_group].group_id}">[ Delete ]</a></td>
</tr>
{if $smarty.section.cv_group.last}
</table>
{/if}
{sectionelse}
<p>There are no CV groups defined yet.</p>
{/section}

<h3>Add New Group</h3>
<form method="post" action="{$conf.scripts.admin.courses}?mode=CVGroups_Insert">
Name: 
<input name="name" size="30" type="text">
<input type="submit" value="Add New Group">
</form>