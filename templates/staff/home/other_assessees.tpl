{* Smarty *}
{* Shows other students who can be assessed *}

<div id="table_list">
  <table cellpadding="0" cellspacing="0" border="0">
    <tr>
      <th>Name</th>
      <th>Placement Year</th>
      <th>Assessment</th>
      <th>Percentage</th>
      <th>Due</th>
      <th class="action">Edit</th>
    </tr>
    {section name=other_assessments loop=$other_assessments}
    <tr class="{cycle name="cycle1" values="dark_row,light_row"}">
      <td>{$other_assessments[other_assessments]->assessed_name|escape:"htmlall"}</td>
      <td>{$other_assessments[other_assessments]->placement_year|escape:"htmlall"}</td>
      <td>{$other_assessments[other_assessments]->assessment_name|escape:"htmlall"}</td>
      <td>{$other_assessments[other_assessments]->percentage|escape:"htmlall"}</td>
      <td>{$other_assessments[other_assessments]->punctuality|escape:"htmlall"}</td>      
      <td class="action"><a href="?section=student&function=edit_assessment&id={$other_assessments[other_assessments]->regime_id}&assessed_id={$other_assessments[other_assessments]->assessed_id}">edit</a></td>
    </tr>
    {sectionelse}
    {#none_found#}
    {/section}
  </table>
{$student_count} {#matching_students#}
</div>
