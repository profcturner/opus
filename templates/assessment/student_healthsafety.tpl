{* Smarty *}
{* Template for student Health & Safety form *}

{* Standard Header for assessments *}
{include file="assessment/assessment_header.tpl"}

{* Assessment specific layout *}
{* Really, only the form contents need to be added *}
{* Note the use of getValue and flagVariable to *}
{* bring in assessment specific material *}
<table align="center" border="1">
<tr>
<th>Topic</th><th>Date of Action</th>
</tr>

<tr>
<td>Emergency Procedures</td>
<td>
<input class="data_entry_required" 
name="emergency_procedures" size="11" 
value="{$assessment->getValue('emergency_procedures')}">
{$assessment->flagVariable("emergency_procedures")}
</td>
</tr>

<tr>
<td>H &amp; S policy received or location known</td>
<td>
<input class="data_entry_required" 
name="policy_received" size="11" 
value="{$assessment->getValue('policy_received')}">
{$assessment->flagVariable("policy_received")}
</td>
</tr>

<tr>
<td>Location of First Aid box or station</td>
<td>
<input class="data_entry_required" 
name="firstaid_location" size="11" 
value="{$assessment->getValue('firstaid_location')}">
{$assessment->flagVariable("firstaid_location")}
</td>
</tr>

<tr>
<td>First Aid arrangements</td>
<td>
<input class="data_entry_required" 
name="firstaid_arrangements" size="11" 
value="{$assessment->getValue('firstaid_arrangements')}">
{$assessment->flagVariable("firstaid_arrangements")}
</td>
</tr>

<tr>
<td>Fire procedures and location of extinguishers</td>
<td>
<input class="data_entry_required" 
name="fire_procedures" size="11" 
value="{$assessment->getValue('fire_procedures')}">
{$assessment->flagVariable("fire_procedures")}
</td>
</tr>

<tr>
<td>Accident reporting procedures</td>
<td>
<input class="data_entry_required" 
name="accident_reporting" size="11" 
value="{$assessment->getValue('accident_reporting')}">
{$assessment->flagVariable("accident_reporting")}
</td>
</tr>

<tr>
<td>COSHH requirements</td>
<td>
<input class="data_entry_required" 
name="coshh_regulations" size="11" 
value="{$assessment->getValue('coshh_regulations')}">
{$assessment->flagVariable("coshh_regulations")}
</td>
</tr>

<tr>
<td>Manual handling procedures</td>
<td>
<input class="data_entry_required" 
name="handling_procedures" size="11" 
value="{$assessment->getValue('handling_procedures')}">
{$assessment->flagVariable("handling_procedures")}
</td>
</tr>

<tr>
<td>Display screen equipment regulations or procedures</td>
<td>
<input class="data_entry_required" 
name="display_equipment" size="11" 
value="{$assessment->getValue('display_equipment')}">
{$assessment->flagVariable("display_equipment")}
</td>
</tr>

<tr>
<td>Protective clothing arrangements</td>
<td>
<input class="data_entry_required" 
name="protective_clothing" size="11" 
value="{$assessment->getValue('protective_clothing')}">
{$assessment->flagVariable("protective_clothing")}
</td>
</tr>

<tr>
<td>Instructions on equipment to be used in your work</td>
<td>
<input class="data_entry_required" 
name="equipment_instructions" size="11" 
value="{$assessment->getValue('equipment_instructions')}">
{$assessment->flagVariable("equipment_instructions")}
</td>
</tr>

<tr>
<td>Any relevant risk assessments which have been notified to you</td>
<td>
<input class="data_entry_required" 
name="risk_assessments" size="11" 
value="{$assessment->getValue('risk_assessments')}">
{$assessment->flagVariable("risk_assessments")}
</td>
</tr>

<tr>
<td>Bullying and Harassment policy and procedure</td>
<td>
<input class="data_entry_required" 
name="bullying_policy" size="11" 
value="{$assessment->getValue('bullying_policy')}">
{$assessment->flagVariable("bullying_policy")}
</td>
</tr>

<tr>
<td>Read <em>UU Health &amp; Safety - guidance notes for students on placements</em></td>
<td>
<input class="data_entry_required" 
name="uu_safety" size="11" 
value="{$assessment->getValue('uu_safety')}">
{$assessment->flagVariable("uu_safety")}
</td>
</tr>

<tr>
<td>Any other Health &amp; Safety issues notified</td>
<td>
<input class="data_entry_required" 
name="other_hs" size="11" 
value="{$assessment->getValue('other_hs')}">
{$assessment->flagVariable("other_hs")}
</td>
</tr>

<tr>
<th colspan="2">Other Issues
</th>
</tr>
<tr>
<td colspan="2">
<textarea name="issues" rows="5" cols="60">
{$assessment->getValue('issues')|escape:"htmlall"}
</textarea>
{$assessment->flagVariable("issues")}
</td>
</tr>

<tr>
<th colspan="2">Sign Off
</th>
</tr>
<tr>
<td colspan="2">
<input type="checkbox" name="compliance"
{if $assessment->getValue('compliance')} CHECKED {/if}
>
I confirm that I have no current concerns relating to any Health & Safety and Bullying & Harassment issues
associated with this placement. I have raised any other issues as discussed above with my Industrial Supervisor.
{$assessment->flagVariable("compliance")}
</td>
</tr>


<tr>
<td align="center" colspan="2">
<input type="submit" value="Submit Marks">
</td>
</tr>



</table>

{* Standard footer for assessments *}
{include file="assessment/assessment_footer.tpl"}

