{* Smarty *}
{* Template for listing help prompts *}

<h2>Help Prompts</h2>

<p>This tool is used to alter the text shown in key parts of the system. Subject to your permissions you can edit the help
for all the system, or the help for a given channel. If you edit a channel prompt, the global help message will be shown
<strong>before</strong> your channel version to all users in that channel. The lookup field is critical, this must match
a code in the system.</p>

{section name=prompt loop=$prompts}
{if $smarty.section.prompt.first}
<table class="opus_table_help_list" border="1">
<tr>
  <th>Language</th>
  <th>Channel</th>
  <th>Key</th>
  <th>Description</th>
  <th>Options</th>
</tr>
{/if}
<tr class="{cycle values="list_row_light,list_row_dark"}">
  <td>{$prompts[prompt].language_name|escape:"htmlall"}</td>
  <td>{$prompts[prompt].channel_name|escape:"htmlall"}</td>
  <td>{$prompts[prompt].lookup|escape:"htmlall"}</td>
  <td>{$prompts[prompt].description|escape:"htmlall"}</td>
  <td><a href="{$conf.scripts.admin.edithelp}?mode=Help_Edit&id={$prompts[prompt].id}">[&nbsp;Edit&nbsp;]</a> <a href="{$conf.scripts.admin.edithelp}?mode=Help_Edit&id={$prompts[prompt].id}">[&nbsp;Delete&nbsp;]</a>
</tr>
{if $smarty.section.prompt.last}
</table>
{/if}
{sectionelse}
<p>There are no help prompts yet.</p>
{/section}

<h3>Add New Prompt</h3>

<p>Use the form below to add a new prompt. Remember, the prompt lookup must match some point provided by the system.
Ask the system administrator for details. If you use an existing lookup with a channel then you can add extra help for that
channel.</p>

<form method="post" action="{$conf.scripts.admin.edithelp}?mode=Help_Insert">
<table>
  <tr>
    <th>Lookup</th>
    <td><input name="lookup" type="text" size="20"></td>
  </tr>
  <tr>
    <th>Language</th>
    <td><select name="language">{html_options options=$languages}</select></td>
  </tr>
  <tr>
    <th>Channel</th>
    <td><select name="channel_id">{html_options options=$channels}</select></td>
  </tr>
</table>
<input type="submit" value="Add New Prompt">
</form>
