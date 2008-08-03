{* Smarty *}

{#last_login#} {$user.opus.last_login}<br />
{$help_prompter->display("StaffHome", $staff->user_id)}

{#your_students#}
{include file="list.tpl" headings=$alt_headings}
