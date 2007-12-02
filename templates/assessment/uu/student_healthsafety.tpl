{* Smarty *}
{* Template for student Health & Safety form *}

{* Assessment specific layout *}
{* Really, only the form contents need to be added *}
{* Note the use of get_value and flag_error to *}
{* bring in assessment specific material *}

<div id="table_manage">
<table>
<tr>
  <td colspan="2" class="button"><input type="submit" class="submit" value="confirm" /></td>
</tr>
<tr>
<td><strong>Topic</strong></td><td><strong>Date of Action</strong> (DD/MM/YYYY)</td>
</tr>

<tr>
<td class="property">Emergency Procedures</td>
<td>
<input class="data_entry_required" 
name="emergency_procedures" size="11" 
value="{$assessment->get_value('emergency_procedures')}">
{$assessment->flag_error("emergency_procedures")}
</td>
</tr>

<tr>
<td class="property">H &amp; S policy received or location known</td>
<td>
<input class="data_entry_required" 
name="policy_received" size="11" 
value="{$assessment->get_value('policy_received')}">
{$assessment->flag_error("policy_received")}
</td>
</tr>

<tr>
<td class="property">Location of First Aid box or station</td>
<td>
<input class="data_entry_required" 
name="firstaid_location" size="11" 
value="{$assessment->get_value('firstaid_location')}">
{$assessment->flag_error("firstaid_location")}
</td>
</tr>

<tr>
<td class="property">First Aid arrangements</td>
<td>
<input class="data_entry_required" 
name="firstaid_arrangements" size="11" 
value="{$assessment->get_value('firstaid_arrangements')}">
{$assessment->flag_error("firstaid_arrangements")}
</td>
</tr>

<tr>
<td class="property">Fire procedures and location of extinguishers</td>
<td>
<input class="data_entry_required" 
name="fire_procedures" size="11" 
value="{$assessment->get_value('fire_procedures')}">
{$assessment->flag_error("fire_procedures")}
</td>
</tr>

<tr>
<td class="property">Accident reporting procedures</td>
<td>
<input class="data_entry_required" 
name="accident_reporting" size="11" 
value="{$assessment->get_value('accident_reporting')}">
{$assessment->flag_error("accident_reporting")}
</td>
</tr>

<tr>
<td class="property">COSHH requirements</td>
<td>
<input class="data_entry_required" 
name="coshh_regulations" size="11" 
value="{$assessment->get_value('coshh_regulations')}">
{$assessment->flag_error("coshh_regulations")}
</td>
</tr>

<tr>
<td class="property">Manual handling procedures</td>
<td>
<input class="data_entry_required" 
name="handling_procedures" size="11" 
value="{$assessment->get_value('handling_procedures')}">
{$assessment->flag_error("handling_procedures")}
</td>
</tr>

<tr>
<td class="property">Display screen equipment regulations or procedures</td>
<td>
<input class="data_entry_required" 
name="display_equipment" size="11" 
value="{$assessment->get_value('display_equipment')}">
{$assessment->flag_error("display_equipment")}
</td>
</tr>

<tr>
<td class="property">Protective clothing arrangements</td>
<td>
<input class="data_entry_required" 
name="protective_clothing" size="11" 
value="{$assessment->get_value('protective_clothing')}">
{$assessment->flag_error("protective_clothing")}
</td>
</tr>

<tr>
<td width="50%" class="property">Instructions on equipment to be used in your work</td>
<td>
<input class="data_entry_required" 
name="equipment_instructions" size="11" 
value="{$assessment->get_value('equipment_instructions')}">
{$assessment->flag_error("equipment_instructions")}
</td>
</tr>

<tr>
<td class="property">Any relevant risk assessments which have been notified to you</td>
<td>
<input class="data_entry_required" 
name="risk_assessments" size="11" 
value="{$assessment->get_value('risk_assessments')}">
{$assessment->flag_error("risk_assessments")}
</td>
</tr>

<tr>
<td class="property">Bullying and Harassment policy and procedure</td>
<td>
<input class="data_entry_required" 
name="bullying_policy" size="11" 
value="{$assessment->get_value('bullying_policy')}">
{$assessment->flag_error("bullying_policy")}
</td>
</tr>

<tr>
<td class="property">Read <em>UU Health &amp; Safety - guidance notes for students on placements</em></td>
<td>
<input class="data_entry_required" 
name="uu_safety" size="11" 
value="{$assessment->get_value('uu_safety')}">
{$assessment->flag_error("uu_safety")}
</td>
</tr>

<tr>
<td class="property">Any other Health &amp; Safety issues notified</td>
<td>
<input class="data_entry_required" 
name="other_hs" size="11" 
value="{$assessment->get_value('other_hs')}">
{$assessment->flag_error("other_hs")}
</td>
</tr>

<tr>
  <td class="property">Other Issues</td>
  <td><textarea name="issues" rows="5" cols="60">{$assessment->get_value('issues')|escape:"htmlall"}</textarea>{$assessment->flag_error("issues")}</td>
</tr>

<tr>
  <td class="property">Sign Off
  <td><input type="checkbox" name="compliance"{if $assessment->get_value('compliance')} CHECKED {/if}>
I confirm that I have no current concerns relating to any Health & Safety and Bullying & Harassment issues
associated with this placement. I have raised any other issues as discussed above with my Industrial Supervisor.
{$assessment->flag_error("compliance")}
  </td>
</tr>

<tr>
  <td colspan="2" class="button"><input type="submit" class="submit" value="confirm" /></td>
</tr>
</table>
</div>