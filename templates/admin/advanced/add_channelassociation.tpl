{* Smarty *}

<div id="table_manage">
  <form method="post" name="channel_association" action="">
    <input type="hidden" name="section" value="advanced" />
    <input type="hidden" name="function" value="add_channelassociation_do" />
    <input type="hidden" name="channel_id" value="{$channel_id}" />

    <table class="table_manage">
      <tr>
        <td colspan="2" class="button"><input type="submit" class="submit" value="add" /></td>
      </tr>
      <tr>
        <td class="property">Permission</td>
        <td>
          {html_options options=$permission_array name="permission"}
        </td>
      </tr>
      <tr>
        <td class="property">Association Type</td>
        <td>
          {html_options options=$type_array name="type"}
        </td>
      </tr>
      <tr>
        <td class="property">{$object_name}</td>
        <td>
          {html_options options=$id_array name="object_id"}
        </td>
      </tr>
      <tr>
        <td colspan="2" class="button"><input type="submit" class="submit" value="add" /></td>
      </tr>
    </table>
  </form>
</div>
