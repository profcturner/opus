{* Smarty *}
{* Assessment matrix *}

<!-- Assessment Matrix begins -->
<h3 align="center">Assessment Information</h2>
{section name=assessment_regime loop=$regime_items}
{if $smarty.section.assessment_regime.first}
<table border="1" align="center">
<tr>
  <th>Assessment</th>
  <th>Assessor</th>
  <th>Weighting</th>
  <th>Percentage</th>
  <th>Aggregate</th>
</tr>
{/if}

{* Main part of loop *}
<tr>
  <td>
{* Check if there is a template for the assessment, if so link it *}
{if $regime_items[assessment_regime].template_filename}
<a href="{$conf.scripts.user.assessment}?mode=AssessmentDisplayForm&cassessment_id={$regime_items[assessment_regime].cassessment_id}&assessed_id={$student_id}">
{/if}
{$regime_items[assessment_regime].student_description|escape:"htmlall"}</td>
{if $regime_items[assessment_regime].template_filename}
</a>
{/if}
  <td>{$regime_items[assessment_regime].assessor|escape:"htmlall"}</td>
  <td>{$regime_items[assessment_regime].weighting*100}%</td>
  <td>{$regime_items[assessment_regime].percentage|escape:"htmlall"}</td>
  <td>{$regime_items[assessment_regime].aggregate|escape:"htmlall"}</td>
</tr>

{if $smarty.section.assessment_regime.last}
<tr>
  <th>Totals</th>
  <td>--</td>
  <td>{$weighting_total*100}%</td>
  <td>--</td>
  <td>{$aggregate_total}%</td>
</tr>
</table>
<p align="center">
Note that all results are provisional until confirmed by the board of
examiners.</p>
{/if}
{sectionelse}
<p align="center">
There is no assessment schedule recorded on the system for this course yet.
</p>
{/section}
<!-- Assessment Matrix ends -->
