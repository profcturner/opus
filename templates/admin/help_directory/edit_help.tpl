{* Smarty *}
{* Template that allows the editing of a help prompt *}

<h2>Editing Help Prompt {$prompt_info.lookup|escape:"htmlall"}<br />
for language {$language_name|escape:"htmlall"}
{if $prompt_info.channel_id}
in channel {$channel_name|escape:"htmlall"}
{/if} {* $prompt_info.channel_id *}
</h2>


<form method="post" action="{$conf.scripts.admin.edithelp}?mode=Help_Update&id={$prompt_info.id}">
<table>
  <tr>
    <th>Description</th>
    <td><input name="description" type="text" size="40" value="{$prompt_info.description|escape:"htmlall"}"></td>
  </tr>
  <tr>
    <th>Contents</th>
    <td>
      <textarea name="contents" rows="20" cols="60">{$prompt_info.contents|escape:"htmlall"}</textarea>
    </td>
  </tr>
</table>
<input type="submit" value="Update Prompt">

{if $prompt_info.contents}
<hr />
<h3>Existing Prompt Renders As Follows</h3>
{$parsed_xml}
{/if} {* $prompt_info.contents *}