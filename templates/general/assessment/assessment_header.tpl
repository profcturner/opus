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
{if $assessment->assessment_results->created}
<h3 align="center">Assessment Information</h3>
<p align="center">This assessment has  been carried out.<br />
{* if we have specific marks, give that information *}
{if $assessment->assessment_results->percentage}
The mark was
{$assessment->assessment_results->mark}
 out of
{$assessment->assessment_results->outof}
 which is
{$assessment_assessment_results->percentage}%.
<br/>
{#provisional_results#}
{/if} {* specific marks *}
</p>

<table>
<tr>
<th>Assessment Date</th><td>
{$assessment->assessment_results->assessed}
</td>
</tr>
<tr>
<th>Recorded Date</th><td>
{$assessment->assessment_results->created}
</td>
</tr>
{if $assessment->assessment_results->modified}
<tr>
<th>Modified Date</th><td>
{$assessment->assessment_results->modified}
</td>
</tr>
{/if}
<tr>
<th>Assessed by</th><td>
{$assessment->assessor_name|escape:"htmlall"}
</td>
</tr>
</table>
<br />
{/if} {* if results exist end *}

{* Common information required in all forms *}

<form method="post">
<input type="hidden" name="section" value="{$target_section}" />
<input type="hidden" name="function" value="edit_assessment_do" />
<input type="hidden" name="regime_id" value="{$assessment->regime->id}" />
<input type="hidden" name="mode" value="AssessmentSubmitResults" />
<input type="hidden" name="assessed_id" value="{$assessment->assessed_id}" />

