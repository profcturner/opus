{* Smarty *}
<div id="table_manage">
<table>
  <tr>
    <td class="property">Name</td>
    <td>
      {$placements.0->supervisor_title|escape:"htmlall"}
      {$placements.0->supervisor_firstname|escape:"htmlall"}
      {$placements.0->supervisor_lastname|escape:"htmlall"}
    </td>
  </tr>
  <tr>
    <td class="property">Phone</td>
    <td>{$placements.0->supervisor_voice|escape:"htmlall"}</td>
  </tr>
  <tr>
    <td class="property">Email</td>
    <td><a href="mailto:{$placements.0->supervisor_email|escape:"htmlall"}">{$placements.0->supervisor_email|escape:"htmlall"}</a></td>
  </tr>
</table>
</div>
