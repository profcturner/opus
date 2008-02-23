{* Smarty *}

{#last_login#} {$user.last_login}<br />

{$help_prompter->display("StudentHome")}

{#pre_company_activity#}
{include file="student/placement/company_activity.tpl"}