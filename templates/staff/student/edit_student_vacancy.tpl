{* Smarty *}
<div id="table_manage">
<table>
  <tr>
    <td class="property">Address</td>
    <td>
      {if $vacancy->address2}{$vacancy->address1|escape:"htmlall"}<br />{/if}
      {if $vacancy->address2}{$vacancy->address2|escape:"htmlall"}<br />{/if}
      {if $vacancy->address3}{$vacancy->address3|escape:"htmlall"}<br />{/if}
      {if $vacancy->town}{$vacancy->town|escape:"htmlall"}<br />{/if}
      {if $vacancy->locality != $vacancy->town}{$vacancy->locality|escape:"htmlall"}<br />{/if}
      {if $vacancy->postcode}{$vacancy->postcode|escape:"htmlall"}<br />{/if}
      {if $vacancy->country}{$vacancy->country|escape:"htmlall"}<br />{/if}
      {if $vacancy->postcode}
        <a href="http://maps.google.co.uk/maps?saddr=bt37+0qb&daddr={$vacancy->postcode|escape:"url"}" target="blank">(Google Maps)</a>
      {/if}
    </td>
  </tr>
{if $vacancy->www}
  <tr>
    <td class="property">Web</td>
    <td><a href="{$vacancy->www|escape:"htmlall"}">{$vacancy->www|escape:"htmlall"}</a></td>
  </tr>
{/if}
{if $company->www != $vacancy->www}
  <tr>
    <td class="property">Company Web</td>
    <td><a href="{$company->www|escape:"htmlall"}">{$company->www|escape:"htmlall"}</a></td>
  </tr>
{/if}
{if $company->voice}
  <tr>
    <td class="property">Company Phone</td>
    <td>{$company->voice|escape:"htmlall"}</td>
  </tr>
{/if}
{if $company->fax}
  <tr>
    <td class="property">Company Fax</td>
    <td>{$company->fax|escape:"htmlall"}</td>
  </tr>
{/if}

</table>
</div>
