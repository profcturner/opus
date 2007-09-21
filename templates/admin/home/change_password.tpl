
{if ($failed_old || $failed_new_equal || $failed_new_simple) }
<div id="warning">
{if $failed_old}{#failed_old#} {/if}
{if $failed_new_equal}{#failed_new_equal#} {/if}
{if $failed_new_simple}{#failed_new_simple#} {/if}
</div>
{/if}

<div id="table_manage">
  <form method="post">
    <input type="hidden" name="section" value="home" />
    <input type="hidden" name="function" value="change_password_do" />

    <table>
      <tr>
        <td colspan="2" class="button"><input type="submit" class="submit" value="change" /></td>
      </tr>
      <tr>
        <td class="property">Old Password</td>
        <td><input type="password" size="30" name="old_password"></td>
      </tr>
      <tr>
        <td class="property">New Password</td>
        <td><input type="password" size="30" name="new_password"></td>
      </tr>
      <tr>
        <td class="property">New Password (again)</td>
        <td><input type="password" size="30" name="new_password_copy"></td>
      </tr>
      <tr>
        <td colspan="2" class="button"><input type="submit" class="submit" value="change" /></td>
      </tr>
    </table>
  </form>
</div>