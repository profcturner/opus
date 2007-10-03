{* Smarty *}
{* Template for editing a given CV group *}
{* @todo: this template is officially icky... it breaks the rules in several places *}
{$group_info->name|escape:"htmlall"}

<form method="post" action="">
  <input type="hidden" name="section" value="configuration" />
  <input type="hidden" name="function" value="manage_cvgroup_templates_do" />
  <input type="hidden" name="group_id" value="{$group_info->id}" />

{section name=pdp_template loop=$pdp_templates}
{if $smarty.section.pdp_template.first}
<div id="table_list">
<table class="opus_cv_group_template_list">
{*<tr><th>Name</th><th width="50%">Description</th><th>Options</th>*}
  <tr>
    <td style="text-align: right" colspan="3" class="button"><input type="submit" class="submit" value="update" /></td>
  </tr>
{/if}
{* The template id is an XML object, we need to evaluate it to get an integer value. This took me a LONG time to find out. *}
{eval var=$pdp_templates[pdp_template]->id assign="template_id"}
<tr style="white-space:normal;" class="{cycle values="dark_row,light_row"}">
  <td width="20%">{$pdp_templates[pdp_template]->name|escape:"htmlall"}</td>
  <td style="white-space:normal;" width="60%">{$pdp_templates[pdp_template]->description|escape:"htmlall"}</td>
  <td width="20%">
    <input name="allowed[]" value="{$template_id}" type="checkbox"{if $opus_permissions[$template_id].allow} checked {/if} /> Allowed<br />
    <input name="approval[]" value="{$template_id}" type="checkbox"{if $opus_permissions[$template_id].requiresApproval} checked {/if} /> Approval Required<br />
    <input name="default_template" type="radio" value="{$pdp_templates[pdp_template]->id}" {if ($pdp_templates[pdp_template]->id==$group_info->default_template)}checked{/if}> Default for this group<br />
  </td>
</tr>
{if $smarty.section.pdp_template.last}
  <tr>
    <td style="text-align: right" colspan="3" class="button"><input type="submit" class="submit" value="update" /></td>
  </tr>
</table>
</div>
{/if}
{sectionelse}
<p>There are no templates enabled yet.</p>
{/section}
</form>