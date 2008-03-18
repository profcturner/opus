{* Smarty *}

{#preferences_for#} {$pref_user->real_name} ({$pref_user->reg_number})<br />
{if !$pref_user->reg_number}
{#no_reg_number#}
{/if}

<div id="table_list">
  <table cellpadding="0" cellspacing="0" border="0">
    <tr>
      <th>Name</th>
      <th>Value</th>
    </tr>
    {section name=preferences loop=$preferences}
    <tr class="{cycle name="cycle1" values="dark_row,light_row"}">
      <td>{$preferences[preferences]->name|escape:"htmlall"}</td>
      <td>{$preferences[preferences]->decoded_value|escape:"htmlall"|nl2br}</td>
    </tr>
    {sectionelse}
    {#none_found#}
    {/section}
  </table>
</div>
