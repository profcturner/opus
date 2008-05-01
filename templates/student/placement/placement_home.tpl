{* Smarty *}

{#last_login#} {$user.opus.last_login}<br />
{$help_prompter->display("StudentHome", $student->user_id)}
{eval assign="student_year" var="StudentHome"|cat:$student->placement_year} 
{$help_prompter->display($student_year, $student->user_id)}
{if $student->placement_status == 'Required'}
{$help_prompter->display("StudentHomeRequired", $student->user_id)}
{/if}
{eval assign="student_year" var="StudentHomeRequired"|cat:$student->placement_year} 
{if $student->placement_status == 'Required'}
{$help_prompter->display($student_year, $student->user_id)}
{/if}
{if $student->placement_status == 'Placed'}
{$help_prompter->display("StudentHomePlaced", $student->user_id)}
{if $placement}
{#placement_details#}
{include file="manage.tpl" mode="view" headings=$placement_headings object=$placement action_button=$placement_action}
<div id="student_academic_tutor">
{#academic_tutor#}
{if $academic_tutor}
<div class="user_details">
{include file="manage.tpl" mode="view" headings=$academic_headings object=$academic_tutor}
</div>
<div class="user_photo">
<a href="?section=placement&function=display_photo&user_id={$academic_tutor->username}&fullsize=true" >
<img width="200" border="0"  src="?section=placement&function=display_photo&user_id={$academic_tutor->username}" /></a>
</div>
{else}
{#no_academic_tutor#}
{/if}
</div>
{/if}
{/if}
{eval assign="student_year" var="StudentHomePlaced"|cat:$student->placement_year} 
{if $student->placement_status == 'Placed'}
{$help_prompter->display($student_year, $student->user_id)}
{/if}

{#pre_company_activity#}
{include file="student/placement/company_activity.tpl"}