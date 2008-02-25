<div id="table_list">
<table cellpadding="0" cellspacing="0" border="0">
  <tr>
    <th>Description</th>
    <th>Valid / Problem</th>
    <th>Approval</th>
    <th class="action">view</th>
    <th class="action">approve</th>
    <th class="action">revoke</th>
  </tr>
  {section name=cvs loop=$cvs}
  <tr class="{cycle name="cycle1" values="dark_row,light_row"}">
    <td>{$cvs[cvs]->description|escape:"htmlall"}</td>
    <td>{if $cvs[cvs]->valid}Valid{else}{$cvs[cvs]->problem}{/if}</td>
    <td>{if $cvs[cvs]->approval}Approved{else}Unapproved{/if}</td>
    <td class="action"><a href="?section=directories&function=view_cv&student_id={$student_id}&cv_ident={$cvs[cvs]->cv_ident}">view</a></td>
    <td class="action"><a href="?section=directories&function=approve_cv&student_id={$student_id}&cv_ident={$cvs[cvs]->cv_ident}">approve</a></td>
    <td class="action"><a href="?section=directories&function=revoke_cv&student_id={$student_id}&cv_ident={$cvs[cvs]->cv_ident}">revoke</a></td>
  </tr>
  {/section}
</table>
</div>


