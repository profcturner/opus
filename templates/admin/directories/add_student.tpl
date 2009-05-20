{* Smarty *}

{if $ws_enabled}
{#import_by_ws#}

<div id="table_manage">
  <form enctype="multipart/form-data" action="" method="post">
    <input type="hidden" name="section" value="directories" />
    <input type="hidden" name="function" value="auto_add_student_do" />

    <table>
      <tr>
        <td colspan="2" class="button">
          <input type="submit" class="submit" value="add" />
        </td>
      </tr>
      <tr>
        <td class="property">Reg Number</td>
        <td><input name="reg_number" type="text" size="12" /></td>
      </tr>
      <tr>
        <td colspan="2" class="button">
          <input type="submit" class="submit" value="add" />
        </td>
      </tr>
    </form>
  </table>
</div>
<br />
{#import_manual#}
<br />
{/if}

{include file="manage.tpl"}
