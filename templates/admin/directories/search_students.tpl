{* Smarty *}

{literal}
<script language="JavaScript" type="text/javascript">
<!--
function toggleAll(checked)
{
  for (i = 0; i < document.student_list.elements.length; i++) {
    if (document.student_list.elements[i].name.indexOf('students') >= 0) 
    {
      document.student_list.elements[i].checked = checked;
    }
  }
}
// -->
</script>
{/literal}

<div id="table_list">
<form name="student_list" method="post">
<a href="" onclick="toggleAll(true); return false;" onmouseover="status='Select all'; return true;">Select All</a> |
<a href="" onclick="toggleAll(false); return false;" onmouseover="status='Select all'; return true;">Deselect All</a><br />
<table cellpadding="0" cellspacing="0" border="0">
  <tr>
    <th>Email</th>
    <th>Name</th>
    <th>Student Number</th>
    <th>Last Access</th>
    <th>Status</th>
    <th class="action">CV</th>
    <th class="action">Help</th>
    <th class="action">Edit</th>
  </tr>
  {section name=students loop=$students}
  <tr class="{cycle name="cycle1" values="dark_row,light_row"}">
    <td><input type="checkbox" name="students[]" value="{$students[students].user_id}" /></td>
    <td>{$students[students].real_name|escape:"htmlall"}</td>
    <td>{$students[students].reg_number|escape:"htmlall"}</td>
    <td>{$students[students].last_time|default:#never#|escape:"htmlall"}</td>
    <td>{$students[students].placement_status|escape:"htmlall"}</td>
    <td {if $show_timelines}rowspan="2"{/if} class="action"><a href="">CV</a></td>
    <td {if $show_timelines}rowspan="2"{/if} class="action"><a href="">help</a></td>
    <td {if $show_timelines}rowspan="2"{/if} class="action"><a href="?section=directories&function=edit_vacancy&id={$students[students].id}">edit</a></td>
  </tr>
  {if $show_timelines}
  <tr class="{cycle name="cycle2" values="dark_row,light_row"}">
    <td colspan="4"><small><span class="status_{$students[students].status}"><img src="" /></td>
  </tr>
  {/if}
  {sectionelse}
  {#none_found#}
  {/section}
</table>
<a href="" onclick="toggleAll(true); return false;" onmouseover="status='Select all'; return true;">Select All</a> |
<a href="" onclick="toggleAll(false); return false;" onmouseover="status='Select all'; return true;">Deselect All</a><br />
{$students|count}
</form>
</div>
