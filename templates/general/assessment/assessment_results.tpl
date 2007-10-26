{* Smarty *}
{* Assessment matrix *}

<!-- Assessment Matrix begins -->
<div id="table_list">
{section name=regime_items loop=$regime_items}
{if $smarty.section.regime_items.first}
  <table cellpadding="0" cellspacing="0" border="0">
    <tr>
      <th>Assessment</th>
      <th>Assessor</th>
      <th>Weighting</th>
      <th>Percentage</th>
      <th>Aggregate</th>
      <th class="action">View</th>
    </tr>
{/if}
{* Main part of loop *}
    <tr>
      <td>{$regime_items[regime_items]->student_description|escape:"htmlall"}</td>
      <td>{$regime_items[regime_items]->assessor|escape:"htmlall"}</td>
      <td>{$regime_items[regime_items]->weighting*100}%</td>
      <td>{$regime_items[regime_items]->percentage|escape:"htmlall"}</td>
      <td>{$regime_items[regime_items]->aggregate|escape:"htmlall"}</td>
     <td class="action"><a href="?section=directories&function=edit_assessment&id={$regime_items[regime_items]->id}&assessed_id={$assessed_id}">view</a></td>
    </tr>
{if $smarty.section.regime_items.last}
    <tr>
      <th>Totals</th>
      <td>--</td>
      <td>{$weighting_total*100}%</td>
      <td>--</td>
      <td>{$aggregate_total}%</td>
    </tr>
  </table>
  {#provisional_results#}
{/if}
{sectionelse}
{#no_assessment_schedule#}
{/section}
<!-- Assessment Matrix ends -->
