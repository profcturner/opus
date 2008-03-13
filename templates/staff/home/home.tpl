{* Smarty *}

{#last_login#} {$user.last_login}<br />
{$help_prompter->display("StaffHome", $staff->user_id)}

{include file="list.tpl" headings=$alt_headings}
