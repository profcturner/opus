{* Smarty *}

<h3>{#basic_information#|capitalize}</h3>
{if $changes}{#changes_applied#}{/if}
{include file="manage.tpl"}

<h3>{#placement_history#|capitalize}</h3>
{if $placements}
{include file="list.tpl" objects=$placements headings=$placement_fields actions=$placement_options}
{else}
{#no_history#}
{/if}

<h3>Timeline</h3>
<img width="600" height="100" src="?section=directories&function=display_timeline&student_id={$object->user_id}" />

<h3>Photograph</h3>
<a href="?section=directories&function=display_photo&username={$object->username}&fullsize=true" >
<img width="200" border="0"  src="?section=directories&function=display_photo&username={$object->username}" /></a>

<h3>Assessment</h3>
{include file="general/assessment/assessment_results.tpl"}

{section name=others loop=$other_items}
{if $smarty.section.others.first}
<h3>Configure Other Assessors</h3>
<div id="table_manage">
<form method="post">
  <input type="hidden" name="section" value="directories" />
  <input type="hidden" name="function" value="edit_assessor_do" />
  <input type="hidden" name="student_id" value="{$object->id}" />
  <table>
    <tr>
      <td colspan="2" class="button"><input type="submit" class="submit" value="confirm"/></td>
    </tr>
{/if}
    <tr>
      <td class="property">{$other_items[others]->student_description}</td>
      <td>
        <select name="tutor_{$other_items[others]->id}">
          {html_options options=$academic_tutors selected=$other_items[others]->assessor_id}
        </select>
      </td>
    </tr>
{if $smarty.section.others.last}
    <tr>
      <td colspan="2" class="button"><input type="submit" class="submit" value="confirm"/></td>
    </tr>
  </table>
</form>
</div>
{/if}
{/section}
