{* Smarty *}
<div id="table_manage">
<table>
  <tr>
    <td class="property">Name</td>
    <td>{$contact->real_name|escape:"htmlall"}</td>
  </tr>
  <tr>
    <td class="property">Phone</td>
    <td>{$contact->voice|escape:"htmlall"}</td>
  </tr>
{if $contact->fax}
  <tr>
    <td class="property">Fax</td>
    <td>{$contact->fax|escape:"htmlall"}</td>
  </tr>
{/if}
{if $contact->email}
  <tr>
    <td class="property">Email</td>
    <td><a href="mailto:{$contact->email|escape:"htmlall"}">{$contact->email|escape:"htmlall"}</a></td>
  </tr>
{/if}
</table>
</div>
