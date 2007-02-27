{* Smarty *}
{* Template for editing a given CV group *}

<h3>Editing CV Group: {$group_info.name|escape:"htmlall"}</h3>

<form method="post" action="{$conf.scripts.admin.courses}?mode=CVGroups_Update&group_id={$group_info.group_id}">
<table>
<tr>
  <th>Name</th>
  <td><input type="text" size="40" name="name" value="{$group_info.name}"></td>
</tr>
<tr>
  <th>Comments</th>
  <td><input type="text" size="40" name="comments" value="{$group_info.comments}"></td>
</tr>
<tr>
  <th>Options</th>
  <td><input name="allowCustom" type="checkbox" {if $allowCustom}CHECKED{/if}>Allow custom (archived) CVs</td>
</tr>
</table>

<h3>PDSystem Template Permissions</h3>
{section name=pdp_template loop=$pdp_templates}
{if $smarty.section.pdp_template.first}
<table class="opus_cv_group_template_list" border="1">
<tr><th>Name</th><th width="50%">Description</th><th>Options</th>
{/if}
{* The template id is an XML object, we need to evaluate it to get an integer value. This took me a LONG time to find out. *}
{eval var=$pdp_templates[pdp_template]->id assign="template_id"}
<tr class="{cycle values="list_row_light,list_row_dark"}">
  <td>{$pdp_templates[pdp_template]->name|escape:"htmlall"}</td>
  <td>{$pdp_templates[pdp_template]->description|escape:"htmlall"}</td>
  <td>
    <input name="tallow_{$template_id}" type="checkbox"{if $opus_permissions[$template_id].allow} CHECKED {/if} /> Allowed<br />
    <input name="tapprove_{$template_id}" type="checkbox"{if $opus_permissions[$template_id].requiresApproval} CHECKED {/if}/> Approval Required<br />
    <input name="default_template" type="radio" value="{$pdp_templates[pdp_template]->id}" {if ($pdp_templates[pdp_template]->id==$group_info.default_template)}CHECKED{/if}> Default for this group<br />
  </td>
</tr>
{if $smarty.section.pdp_template.last}
</table>
{/if}
{sectionelse}
<p>There are no templates enabled yet.</p>
{/section}
<input type="submit" value="Update">
</form>