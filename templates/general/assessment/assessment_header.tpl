{* Smarty *}
{* assessment/assessment_header.tpl *}

{if !$canEdit}
<div id="warning">
{#cannot_edit#}
</div>
{/if}

<h2>
{$regime_item->student_description|escape:"htmlall"}
</h2>
<h3>
{$assessed_user->real_name|escape:"htmlall"}
</h3>

{* If we are submitting, and errors occured, warn the user *}
{*
{if $assessment->getError() && $mode=="AssessmentSubmitResults"}
<div class="warning">
<h2>Errors occurred</h2>
<p align="center">See the <a href="#errors">bottom</a> of the 
page for more detail. These errors must be corrected before 
the data can be submitted.</p>
</div>
{/if}*} {* on error *}


{* If results exist, give a summary *}
{if $assessment_total->created}
<h3 align="center">Assessment Information</h3>
<p align="center">This assessment has  been carried out.<br />
{* if we have specific marks, give that information *}
{if $assessment_total->percentage}
The mark was
{$assessment_total->mark}
 out of
{$assessment_total->outof}
 which is
{$assessment_total->percentage}%.
<br/>
{#provisional_results#}
{/if} {* specific marks *}
</p>

<table align="center">
<tr>
<th>Assessment Date</th><td>
{$assessment_total->assessed}
</td>
</tr>
<tr>
<th>Recorded Date</th><td>
{$assessment_total->created}
</td>
</tr>
{if $assessment_total->modified}
<tr>
<th>Modified Date</th><td>
{$assessment_total->modified}
</td>
</tr>
{/if}
<tr>
<th>Assessed by</th><td>
{$assessor->real_name|escape:"htmlall"}
</td>
</tr>
</table>
<br />
{/if} {* if results exist end *}

{* Common information required in all forms *}

{*
<form method="post" action="{$conf.scripts.user.assessment}">
<input type="hidden" name="mode" value="AssessmentSubmitResults">
<input type="hidden" name="cassessment_id" value="{$assessment->getCassessment_id()}">
<input type="hidden" name="assessed_id" value="{$assessment->assessed_id}">
*}