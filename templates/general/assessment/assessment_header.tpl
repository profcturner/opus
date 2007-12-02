{* Smarty *}
{* assessment/assessment_header.tpl *}

{if !$canEdit}
<div id="warning">
{#cannot_edit#}
</div>
{/if}

<h2>
{$assessment->regime->student_description|escape:"htmlall"}
</h2>
<h3>
{$assessment->assessed_name|escape:"htmlall"}
</h3>

{* If we are submitting, and errors occured, warn the user *}
{*
{if $assessment->get_error() && $mode=="AssessmentSubmitResults"}
<div class="warning">
<h2>Errors occurred</h2>
<p align="center">See the <a href="#errors">bottom</a> of the 
page for more detail. These errors must be corrected before 
the data can be submitted.</p>
</div>
{/if}*} {* on error *}


{* If results exist, give a summary *}
{if $assessment->assessment_results.created}
<p><strong>This assessment has  been carried out.</strong>
<div id="table_list">
<table>
<tr>
  <th>Assessment Date</th>
  <th>Recorded Date</th>
  {if $assessment->assessment_results.modified}
  <th>Modified Date</th>
  {/if}
  <th>Assessed by</th>
  {if $assessment->assessment_results.percentage != 0}
  <th>Result</th>
  {/if}
</tr>
<tr>
  <td>{$assessment->assessment_results.assessed}</td>
  <td>{$assessment->assessment_results.created}</td>
  {if $assessment->assessment_results.modified}
  <td>{$assessment->assessment_results.modified}</td>
  {/if}
  <td>{$assessment->assessor_name|escape:"htmlall"}</td>
  {if $assessment->assessment_results.percentage != 0}
  <td>{$assessment->assessment_results.percentage}% ({$assessment->assessment_results.mark}/{$assessment->assessment_results.outof})</td>
  {/if}
</tr>
</table>
</div>
{#provisional_results#}
</p>
{/if} {* if results exist end *}

{* Common information required in all forms *}

<form method="post">
<input type="hidden" name="section" value="{$target_section}" />
<input type="hidden" name="function" value="edit_assessment_do" />
<input type="hidden" name="regime_id" value="{$assessment->regime->id}" />
<input type="hidden" name="mode" value="AssessmentSubmitResults" />
<input type="hidden" name="assessed_id" value="{$assessment->assessed_id}" />

