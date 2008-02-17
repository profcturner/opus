{* Smarty *}

{$help_prompter->display("SupervisorHome")}

<h3>Photograph</h3>
<a href="?section=directories&function=display_photo&user_id={$student->user_id}&fullsize=true" >
<img width="200" border="0"  src="?section=home&function=display_photo&user_id={$student->user_id}" /></a>

<h3>Assessment</h3>
{include file="general/assessment/assessment_results.tpl"}

