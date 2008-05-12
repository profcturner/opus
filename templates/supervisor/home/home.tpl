{* Smarty *}

{$help_prompter->display("SupervisorHome")}

<br />
<h3>{#student_details#}</h3>
<div class="user_details">
{include file="manage.tpl" mode="view" object=$student headings=$student_headings}
</div>
<div class="user_photo">
<a href="?section=home&function=display_photo&username={$student->username}&fullsize=true" >
<img width="200" border="0"  src="?section=home&function=display_photo&username={$student->username}" /></a>
</div>
<h3>{#placement_details#}</h3>
{include file="manage.tpl" mode="edit" object=$placement headings=$placement_headings action_button=$placement_action}
<h3>{#academic_tutor#}</h3>
{if $academic->id}
<div class="user_details_photo">
<div class="user_details">
{include file="manage.tpl" mode="view" object=$academic headings=$academic_headings}
</div>
<div class="user_photo">
<a href="?section=home&function=display_photo&username={$academic->username}&fullsize=true" >
<img width="200" border="0"  src="?section=home&function=display_photo&username={$academic->username}" /></a>
</div>
{else}
{#no_tutor_yet#}
</div>
{/if}
<h3>Assessment</h3>
{include file="general/assessment/assessment_results.tpl"}


