{* Smarty *}

{literal}
<script language="JavaScript" type="text/javascript">
<!--
function toggleAll(checked)
{
  for (i = 0; i < document.student_list.elements.length; i++) {
    if (document.student_list.elements[i].name.indexOf('users') >= 0) 
    {
      document.student_list.elements[i].checked = checked;
    }
  }
}
// -->
</script>
{/literal}

<form name="student_list" method="post">
  <input type="hidden" name="section" value="directories" />
  <input type="hidden" name="function" value="mass_email" />
  <input type="hidden" name="redirect_url" value="section=directories&function=student_directory" />
  <div id="table_list">
  <a href="" onclick="toggleAll(true); return false;" onmouseover="status='Select all'; return true;">Select All</a> |
  <a href="" onclick="toggleAll(false); return false;" onmouseover="status='Deselect all'; return true;">Deselect All</a><br />
    <table cellpadding="0" cellspacing="0" border="0">
      <tr>
        <th>Email</th>
        <th>Name</th>
        <th>Student Number</th>
        <th>Last Access</th>
        <th>Year</th>
        <th>Status</th>
        <th class="action">CV</th>
        <th class="action">Help</th>
        <th class="action">Edit</th>
      </tr>
      {section name=students loop=$students}
      <tr class="{cycle name="cycle1" values="dark_row,light_row"}">
        <td><input type="checkbox" name="users[]" value="{$students[students].user_id}" /></td>
        <td>{$students[students].real_name|escape:"htmlall"}</td>
        <td>{$students[students].reg_number|escape:"htmlall"}</td>
        <td>{$students[students].last_time|default:#never#|escape:"htmlall"}</td>
        <td>{$students[students].placement_year|default:#unknown#|escape:"htmlall"}</td>
        <td>{$students[students].placement_status|escape:"htmlall"}</td>
        <td {if $show_timelines}rowspan="2"{/if} class="action"><a href="?section=directories&function=list_student_cvs&student_id={$students[students].user_id}">CV</a></td>
        <td {if $show_timelines}rowspan="2"{/if} class="action"><a href="?section=information&function=help_directory&student_id={$students[students].user_id}">help</a></td>
        <td {if $show_timelines}rowspan="2"{/if} class="action"><a href="?section=directories&function=edit_student&id={$students[students].id}">edit</a></td>
      </tr>
      {if $show_timelines}
      <tr class="{cycle name="cycle2" values="dark_row,light_row"}">
        <td></td>
        <td colspan="5"><img width="600" height="100" src="?section=directories&function=display_timeline&student_id={$students[students].user_id}" />
        </td>
      </tr>
      {/if}
      {sectionelse}
      {#none_found#}
      {/section}
    </table>
  <a href="" onclick="toggleAll(true); return false;" onmouseover="status='Select all'; return true;">Select All</a> |
  <a href="" onclick="toggleAll(false); return false;" onmouseover="status='Deselect all'; return true;">Deselect All</a><br />
  {$student_count} {#matching_students#}
  </div>
  <h3>{#email_students#}</h3>
  <div id="table_manage">
    <table>
      <tr>
        <td colspan="2" class="button">
          <input type="submit" class="submit" value="send email" />
        </td>
      </tr>
      <tr>
        <td class="property">{#subject#}</td>
        <td><input type="text" name="subject" size="60" /></td>
      </tr>
      <tr>
        <td class="property">{#message#}</td>
        <td><textarea name="message" rows="10" cols="60" wrap="physical"></textarea></td>
      </tr>
      <tr>
        <td class="property">{#cc_me#}</td>
        <td><input type="checkbox" name="cc_me" checked /></td>
      </tr>
      <tr>
        <td colspan="2" class="button">
          <input type="submit" class="submit" value="send email" />
        </td>
      </tr>
    </table>
  </div>
</form>
