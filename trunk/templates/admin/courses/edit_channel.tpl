{* Smarty *}
{* Template for editing a given channel *}

<h3>Editing Channel: {$channel_info.name|escape:"htmlall"}</h3>

<form method="post" action="{$conf.scripts.admin.courses}?mode=Channels_Update&channel_id={$channel_info.channel_id}">
<table>
<tr>
  <th>Name</th>
  <td><input type="text" size="40" name="name" value="{$channel_info.name}"></td>
</tr>
<tr>
  <th>Comments</th>
  <td><input type="text" size="40" name="description" value="{$channel_info.description}"></td>
</tr>
</table>
<input type="submit" value="Update Channel">
</form>

<h3>Channel Associations</h3>
{section name=association loop=$associations}
{if $smarty.section.association.first}
<table class="opus_channel_association_list" border="1">
<tr><th>Priority</th><th>Permission</th><th>Object</th><th>Object Name</th><th>Options</th>
{/if}
{eval var=$pdp_templates[pdp_template]->id assign="template_id"}
<tr class="{cycle values="list_row_light,list_row_dark"}">
  <td>{$associations[association].priority}</td>
  <td>{$associations[association].permission|escape:"htmlall"}</td>
  <td>{$associations[association].type|escape:"htmlall"}</td>
  <td>{$associations[association].name|escape:"htmlall"}</td>
  <td>
    <a href="{$conf.script.admin.courses}?mode=ChannelAssociation_MoveUp&channel_id={$channel_info.channel_id}&association_id={$associations[association].id}">[ Up ]</a> <a href="{$conf.script.admin.courses}?mode=ChannelAssociation_MoveDown&channel_id={$channel_info.channel_id}&association_id={$associations[association].id}">[ Down ]</a> <a href="{$conf.script.admin.courses}?mode=ChannelAssociation_Delete&channel_id={$channel_info.channel_id}&association_id={$associations[association].id}">[ Delete ]</a>
  </td>
</tr>
{if $smarty.section.association.last}
</table>
{/if}
{sectionelse}
<p>There are no associations setup yet.</p>
{/section}
<h3>Add a School as an Association</h3>

<form method="post" action="{$conf.scripts.admin.courses}?mode=ChannelAssociation_Insert&channel_id={$channel_info.channel_id}">
<input type="hidden" name="type" value="school" />
<table border="1">
  <tr>
    <th>Permission</th><th>School</th>
  </tr>
  <tr>
    <td>
      <select name="permission">
        <option selected>enable</option>
        <option>disable</option>
      </select>
    </td>
    <td><select name="object_id">{html_options options=$schools}</select>
    </td>
  </tr>
</table>
<input type="submit" value="Add Association">
</form>

<h3>Add a Course as an Association</h3>

<form method="post" action="{$conf.scripts.admin.courses}?mode=ChannelAssociation_Insert&channel_id={$channel_info.channel_id}">
<input type="hidden" name="type" value="course" />
<table border="1">
  <tr>
    <th>Permission</th><th>Course</th>
  </tr>
  <tr>
    <td>
      <select name="permission">
        <option selected>enable</option>
        <option>disable</option>
      </select>
    </td>
    <td><select name="object_id">{html_options options=$courses}</select>
    </td>
  </tr>
</table>
<input type="submit" value="Add Association">
</form>

<h3>Add an Assessment Group as an Association</h3>

<form method="post" action="{$conf.scripts.admin.courses}?mode=ChannelAssociation_Insert&channel_id={$channel_info.channel_id}">
<input type="hidden" name="type" value="assessmentgroup" />
<table border="1">
  <tr>
    <th>Permission</th><th>Assessment Group</th>
  </tr>
  <tr>
    <td>
      <select name="permission">
        <option selected>enable</option>
        <option>disable</option>
      </select>
    </td>
    <td><select name="object_id">{html_options options=$assessment_groups}</select>
    </td>
  </tr>
</table>
<input type="submit" value="Add Association">
</form>

<h3>Add an Activity Type</h3>

<form method="post" action="{$conf.scripts.admin.courses}?mode=ChannelAssociation_Insert&channel_id={$channel_info.channel_id}">
<input type="hidden" name="type" value="activity" />
<table border="1">
  <tr>
    <th>Permission</th><th>Activity</th>
  </tr>
  <tr>
    <td>
      <select name="permission">
        <option selected>enable</option>
      </select>
    </td>
    <td><select name="object_id">{html_options options=$activities}</select>
    </td>
  </tr>
</table>
<input type="submit" value="Add Association">
</form>