{if $password_reset}
{#password_reset#}
{else}
<div id="warning">
{if $disabled_password_reset}
{#disabled_password_reset#}
{/if}
{if $disabled_password_reset_by_user}
{#disabled_password_reset_by_user#}
{/if}
{if $expired_hash}
{#expired_hash#}
{/if}
</div>
{/if}

<br /><br />
<a href="{$config.opus.url}">{#login_link#}</a>
