{* Smarty *}

{#last_login#} {$user.last_login}<br />
{$help_prompter->display("StaffHome")}

{include file="list.tpl" headings=$alt_headings}
