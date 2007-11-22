{* Smarty *}
{* assessment/assessment_header.tpl *}

<h2 align="center">
{$assessment->assessment_regime_data.student_description|escape:"htmlall"}
</h2>
<h3 align="center">
{$assessment->assessed_name|escape:"htmlall"}
</h3>

{* If we are submitting, and errors occured, warn the user *}
{if $assessment->getError() && $mode=="AssessmentSubmitResults"}
<div class="error">
<h2 class="error" align="center">Errors occured</h2>
<p align="center">See the <a href="#errors">bottom</a> of the 
page for more detail. These errors must be corrected before 
the data can be submitted.</p>
</div>
{/if} {* on error *}

{* If results exist, give a summary *}
{if $assessment->assessment_results}
<h3 align="center">Assessment Information</h3>
<p align="center">This assessment has  been carried out.<br />
{* if we have specific marks, give that information *}
{if $assessment->assessment_results.percentage}
The mark was
{$assessment->assessment_results.mark}
 out of
{$assessment->assessment_results.outof}
 which is
{$assessment->assessment_results.percentage}%.
<br/><strong>All results are provisional until agreed by the board of examiners.</strong>
{/if} {* specific marks *}
</p>

<table align="center">
<tr>
<th>Assessment Date</th><td>
{$assessment->assessment_results.assessed}
</td>
</tr>
<tr>
<th>Recorded Date</th><td>
{$assessment->assessment_results.created}
</td>
</tr>
{if $assessment->assessment_results.modified}
<tr>
<th>Modified Date</th><td>
{$assessment->assessment_results.modified}
</td>
</tr>
{/if}
<tr>
<th>Assessed by</th><td>
{$assessment->assessment_results.assessor_name|escape:"htmlall"}
</td>
</tr>
</table>
<br />
{/if} {* if results exist end *}

{if !$canEdit}
<h3>Warning</h3>
<p><strong>Please note that you cannot edit, alter or submit this assessment. You are being shown it
for information only.</strong><br />
By looking at this form you can establish exactly under which criteria will or have been used to perform the assessment.</p>
{/if}

{* Common information required in all forms *}

<form method="post" action="{$conf.scripts.user.assessment}">
<input type="hidden" name="mode" value="AssessmentSubmitResults">
<input type="hidden" name="cassessment_id" value="{$assessment->getCassessment_id()}">
<input type="hidden" name="assessed_id" value="{$assessment->assessed_id}">
