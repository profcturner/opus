{* Smarty *}
<div id="table_manage">
<table>
  <tr>
    <td class="property">Company</td>
    <td><a href="?section=directories&function=view_company&company_id={$company->id}">{$company->name|escape:"htmlall"}</a></td>
  </tr>
  <tr>
    <td class="property">Vacancy</td>
    <td><a href="?section=directories&function=view_vacancy&id={$vacancy->id}">{$vacancy->description|escape:"htmlall"}</a></td>
  </tr>
</table>
</div>
