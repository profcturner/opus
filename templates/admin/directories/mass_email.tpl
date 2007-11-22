{* Smarty *}

{if $invalid_email}
  {#invalid_email#}
{else}
  {section name=valid_recipients loop=$valid_recipients}
  {if $smarty.section.valid_recipients.first}
    {#valid_recipients#}
    <div id="table_list">
    <table>
    <tr><th>Name</th><th>Email</th></tr>
  {/if}
    <tr class="{cycle values="dark_row,light_row"}">
      <td>{$valid_recipients[valid_recipients]->real_name}</td>
      <td>{$valid_recipients[valid_recipients]->email}</td>
    </tr>
  {if $smarty.section.valid_recipients.last}
    </table>
    </div>
  {/if}
  {/section}

  {section name=invalid_recipients loop=$invalid_recipients}
  {if $smarty.section.invalid_recipients.first}
    {#invalid_recipients#}
    <div id="table_list">
    <table>
    <tr><th>Name</th></tr>
  {/if}
    <tr class="{cycle values="dark_row,light_row"}">
      <td>{$invalid_recipients[invalid_recipients]->real_name}</td>
    </tr>
  {if $smarty.section.invalid_recipients.last}
    </table>
    </div>
  {/if}
  {/section}
{/if}
