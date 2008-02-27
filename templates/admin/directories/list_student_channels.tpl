<div id="table_list">
<table cellpadding="0" cellspacing="0" border="0">
  <tr>
    <th>Channel Name</th>
    <th>Description</th>
  </tr>
  {section name=channels loop=$channels}
  <tr class="{cycle name="cycle1" values="dark_row,light_row"}">
    <td>{$channels[channels]->name|escape:"htmlall"}</td>
    <td>{$channels[channels]->description|escape:"htmlall"}</td>
  </tr>
  {/section}
</table>
</div>

<h3>{#add_to_channel#}</h3>

<div id="table_list">
<table cellpadding="0" cellspacing="0" border="0">
  <tr>
    <th>Channel Name</th>
    <th>Description</th>
    <th class="action">add</th>
  </tr>
  {section name=all_channels loop=$all_channels}
  <tr class="{cycle name="cycle1" values="dark_row,light_row"}">
    <td>{$all_channels[all_channels]->name|escape:"htmlall"}</td>
    <td>{$all_channels[all_channels]->description|escape:"htmlall"}</td>
    <td class="action"><a href="?section=advanced&function=add_channelassociation_student&id={$all_channels[all_channels]->id}&student_id={$student_id}">add</a></td>
  </tr>
  {/section}
</table>
</div>


