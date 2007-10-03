{* Smarty *}

<div id="table_manage">
  <form enctype="multipart/form-data" action="" method="post">
    <input type="hidden" name="section" value="configuration" />
    <input type="hidden" name="{$type}" value="{$object_id}" />
    <input type="hidden" name="function" value="{$function}" />

    <table>
      <tr>
        <td colspan="2" class="button"><input type="submit" class="submit" value="add" /></td>
      </tr>
      <tr>
        <td class="property">Administrator</td>
        <td>
          {html_options name="admin_id" options=$admins}
        </td>
      </tr>
      <tr>
        <td class="property">Policy</td>
        <td>
          {html_options name="policy_id" options=$policies selected="0"}
        </td>
      </tr>
      <tr>
        <td colspan="2" class="button"><input type="submit" class="submit" value="add" /></td>
      </tr>
    </table>
  </form>
</div>
