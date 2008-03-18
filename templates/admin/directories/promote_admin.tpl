{* Smarty *}
<div id="table_manage">
<form action="" method="POST">
<input type="hidden" name="section" value="directories" />
<input type="hidden" name="function" value="promote_admin_do" />
<input type="hidden" name="id" value="{$admin->id}" />
  <table>
    <tr>
      <td colspan="2" class="button"><input type="submit" class="submit" value="promote" /></td>
    </tr>
    <tr>
      <td class="property">Name</td>
      <td>{$admin->real_name|escape:htmlall}</td>
    </tr>
    <tr>
      <td class="property">Policy</td>
      <td>{$admin->_policy_id|escape:htmlall}</td>
    </tr>
    <tr>
      <td class="property">Email</td>
      <td>{$admin->email}</td>
    </tr>
    <tr>
      <td colspan="2" class="button"><input type="submit" class="submit" value="promote" /></td>
    </tr>
  </table>
</form>
</div>
