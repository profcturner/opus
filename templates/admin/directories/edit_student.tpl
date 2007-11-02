{* Smarty *}

<h3>Basic Information</h3>
{if $changes}{#changes_applied#}{/if}
{include file="manage.tpl"}

<h3>{#placement_history#}</h3>
{if $placements}
{include file="list.tpl" objects=$placements headings=$placement_fields actions=$placement_options}
{else}
{#no_history#}
{/if}

<h3>Timeline</h3>
<img width="600" height="100" src="?section=directories&function=display_timeline&student_id={$object->id}" />

<h3>Photograph</h3>
<a href="?section=directories&function=display_photo&user_id={$object->user_id}&fullsize=true" >
<img width="200" border="0"  src="?section=directories&function=display_photo&user_id={$object->user_id}" /></a>

<h3>Assessment</h3>
{include file="general/assessment/assessment_results.tpl"}