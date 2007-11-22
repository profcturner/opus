This page shows the channel subscriptions for this student. Please note that subscriptions are
automatic, and depend upon the course, school, CV group as Assessment group for the student.


<h3>Channels</h3>
{foreach from=$channels item=channel name=channel_list}
{if $smarty.foreach.channel_list.first}
<table>
{/if}
  <tr class="{cycle values="list_row_light,list_row_dark"}">
    <td>
    {$channel|escape:"htmlall"}
    </td>
  </tr>
{if $smarty.foreach.channel_list.last}
</table>
{/if}
{foreachelse}
This student is currently not in any channels
{/foreach}
