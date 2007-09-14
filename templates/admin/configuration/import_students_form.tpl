{* Smarty *}

{if $ws_enabled == false}
<div id="warning">
{#no_ws#}
</div>
{/if}

<div id="table_manage">
  <form enctype="multipart/form-data" action="" method="post">
    <input type="hidden" name="section" value="configuration" />
    <input type="hidden" name="function" value="import_students_do" />

    <table>
      <tr>
        <td colspan="2" class="button"><input type="submit" class="submit" value="import" /></td>
      </tr>
      <tr>
        <td class="property">Filename</td>
        <td><input type="file" size="30" name="userfile"></td>
      </tr>
      <tr>
        <td class="property">Programme</td>
        <td>
          {html_options name="programme_id" options=$programmes}
        </td>
      </tr>
      <tr>
        <td class="property">Only import year number</td>
        <td><input type="text" name="onlyyear" value="2" size="3" /></td>
      </tr>
      <tr>
        <td class="property">status</td>
        <td>
            <select name="status"><option>required</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="property">Password (optional)</td>
        <td><input type="text" size="10" name="password"></td>
      </tr>
      <tr>
        <td class="property">for placement starting in year</td>
        <td><input type="text" size="4" value ="{$year}" name="year"></td>
      </tr>
      <tr>
        <td class="property">test only</td>
        <td><input type="checkbox" name="test" checked> (you must uncheck this to commit changes)</td>
      </tr>
      <tr>
        <td colspan="2" class="button"><input type="submit" class="submit" value="import" /></td>
      </tr>
    </table>
  </form>
</div>