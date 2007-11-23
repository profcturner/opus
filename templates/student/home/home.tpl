{* Smarty *}

{#last_login#} {$user.last_login}<br />
{$help_prompter->display("StudentHome", $student->user_id)}
{eval assign="student_year" var="StudentHome"|cat:$student->placement_year} 
{$help_prompter->display($student_year, $student->user_id)}
{if $student->placement_status == 'Placed'}
{$help_prompter->display("StudentHomePlaced", $student->user_id)}
{/if}
{eval assign="student_year" var="StudentHomePlaced"|cat:$student->placement_year} 
{if $student->placement_status == 'Placed'}
{$help_prompter->display($student_year, $student->user_id)}
{/if}