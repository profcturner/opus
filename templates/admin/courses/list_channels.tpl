{* Smarty *}
{* Template for listing all Channels *}

<p>You can create channels here to facilitate communication with students. This then allows resources
and help prompts to be customised for a given channel.</p>

<h3>Channels</h3>
{section name=channel loop=$channels}
{if $smarty.section.channel.first}
<table class="opus_channel_group_list" border="1">
<tr><th>Name</th><th>Description</th><th>Options</th></tr>
{/if}
<tr class="{cycle values="list_row_light,list_row_dark"}">
  <td>{$channels[channel].name|escape:"htmlall"}</td>
  <td>{$channels[channel].description|escape:"htmlall"}</td>
  <td><a href="{$conf.scripts.admin.courses}?mode=Channels_Edit&channel_id={$channels[channel].channel_id}">[ Edit ]</a> <a href="{$conf.scripts.admin.courses}?mode=Channels_Delete&channel_id={$channels[channel].channel_id}">[ Delete ]</a></td>
</tr>
{if $smarty.section.channel.last}
</table>
{/if}
{sectionelse}
<p>There are no channels defined yet.</p>
{/section}

<h3>Add New Channel</h3>
<form method="post" action="{$conf.scripts.admin.courses}?mode=Channels_Insert">
Name: 
<input name="name" size="30" type="text">
<input type="submit" value="Add New Channel">
</form>