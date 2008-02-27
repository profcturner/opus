{* Smarty *}
{* assessment/assessment_header.tpl *}

{* If we are submitting, and errors occured, warn the user *}
{if $assessment->get_error()}
<div id="warning">
{#errors_occurred#}
<br /><br />
{foreach from=$assessment->get_error() item=error_line}
{$error_line}<br />
{/foreach}
</div>
{/if} {* on error *}

{if $assessment->early}
<div id="warning">
{#early_assessment#}
</div>
{/if}

{if $assessment->late}
<div id="warning">
{#late_assessment#}
</div>
{/if}

{if $assessment->time_left}
{#not_locked#}
{/if}


<h2>
{$assessment->regime->student_description|escape:"htmlall"}
</h2>
<h3>
{$assessment->assessed_name|escape:"htmlall"}
</h3>

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
<input type="hidden" name="section" value="{$section}" />
<input type="hidden" name="function" value="edit_assessment_do" />
<input type="hidden" name="regime_id" value="{$assessment->regime->id}" />
<input type="hidden" name="assessed_id" value="{$assessment->assessed_id}" />

