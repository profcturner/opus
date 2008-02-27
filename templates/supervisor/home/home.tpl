{* Smarty *}

{$help_prompter->display("SupervisorHome")}

<br />
<h3>{#placement_details#}</h3>
{include file="manage.tpl" mode="edit" object=$placement headings=$placement_headings action_button=$placement_action}
<h3>{#academic_tutor#}</h3>
{if $academic->id}
{include file="manage.tpl" mode="view" object=$academic headings=$academic_headings}
{else}
{#no_tutor_yet#}
{/if}
<a href="?section=home&function=display_photo&user_id={$student->user_id}&fullsize=true" >
<img width="200" border="0"  src="?section=home&function=display_photo&user_id={$student->user_id}" /></a>

<h3>Assessment</h3>
{include file="general/assessment/assessment_results.tpl"}

