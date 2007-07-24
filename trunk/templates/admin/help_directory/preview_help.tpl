{* Smarty *}
{* Template that allows the previewing of an updated help prompt *}
<h2>Previewing Help Prompt {$prompt_info.lookup|escape:"htmlall"}<br />
for language {$language_name|escape:"htmlall"}
{if $prompt_info.channel_id}
in channel {$channel_name|escape:"htmlall"}
{/if} {* $prompt_info.channel_id *}
</h2>

<p>You can see the contents of your prompt below.</p>

<a href="{$conf.scripts.admin.edithelp}?mode=Help_Edit&id={$prompt_info.id}">Edit Again</a> | <a href="{$conf.scripts.admin.edithelp}">Finished</a>

<hr />
<h3>Existing Prompt Renders As Follows</h3>
{$parsed_xml}
 