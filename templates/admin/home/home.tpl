{* Smarty *}

{#last_login#} {$user.last_login}<br />
{if $user.user_type == "root"}
{$help_prompter->display("RootHome")}
{else}
{$help_prompter->display("AdminHome")}
{/if}