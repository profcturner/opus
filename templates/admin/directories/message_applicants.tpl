{* Smarty *}

<form name="student_list" method="post">
  <input type="hidden" name="section" value="directories" />
  <input type="hidden" name="function" value="mass_email" />
  {foreach from=$status_changes item=user}
  <input type="hidden" name="users[]" value="{$user}" />
  {/foreach}
  <input type="hidden" name="redirect_url" value="section=directories&function=manage_applicants&id={$vacancy_id}" />
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

{#reminder_of_changes#}
<div id="table_list">
  <table cellpadding="0" cellspacing="0" border="0">
    <tr>
      <th>{#student_name#}</th>
      <th>{#old_status#}</th>
      <th>{#new_status#}</th>
    </tr>
    {foreach from=$status_changes item=user}
    <tr class="{cycle name="cycle1" values="dark_row,light_row"}">
      <td>{$student_name[$user]}</td>
      <td>{$old_status[$user]}</td>
      <td>{$status[$user]}</td>
    </tr>
    {/foreach}
  </table>
</div>

