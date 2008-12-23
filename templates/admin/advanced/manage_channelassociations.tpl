<div id="table_list">
<table cellpadding="0" cellspacing="0" border="0">
  <tr>
    <th>Priority</th>
    <th>Effect</th>
    <th>Type</th>
    <th>Name</th>
    <th class="action">Up</th>
    <th class="action">Down</th>
    <th class="action">Remove</th>
  </tr>
  {section name=objects loop=$objects}
  <tr class="{cycle name="cycle1" values="dark_row,light_row"}">
    <td>{$objects[objects].priority}</td>
    <td>{$objects[objects].permission|capitalize|escape:"htmlall"}</td>
    <td>{$objects[objects].type|capitalize|escape:"htmlall"}</td>
    <td>{$objects[objects].object_name|escape:"htmlall"}</td>
    <td class="action"><a href="?section=advanced&function=move_channelassociation_up&channel_id={$channel_id}&id={$objects[objects].id}">up</a></td>
    <td class="action"><a href="?section=advanced&function=move_channelassociation_down&channel_id={$channel_id}&id={$objects[objects].id}">down</a></td>
    <td class="action"><a href="?section=advanced&function=remove_channelassociation&id={$objects[objects].id}">remove</a></td>
  </tr>
  {/section}
</table>
</div>
