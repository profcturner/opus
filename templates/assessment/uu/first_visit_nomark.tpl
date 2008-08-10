{* Smarty *}
{* Template for SEME First Visit *}

{* Assessment specific layout *}
{* Really, only the form contents need to be added *}
{* Note the use of get_value and flag_error to *}
{* bring in assessment specific material *}

<div id="table_manage">
<table>
<tr>
  <th>checklist</th>
  <th>yes / no</th>
  <th>comments</th>
</tr>
<tr>
  <td class="property">Training / experience programme arranged</td>
  <td><input type="checkbox" name="check1" {if $assessment->get_value("check1")} checked{/if}></td>
  <td>{$assessment->flag_error("comment1")}
  {include file="general/assessment/textarea.tpl" name="comment1" rows="3" cols="60"}</td>
</tr>

<tr><td class="property">Industrial supervisor appointed</td>
<td><input type="checkbox" name="check2"
{if $assessment->get_value("check2")} checked {/if}
></td>
  <td>{$assessment->flag_error("comment2")}
  {include file="general/assessment/textarea.tpl" name="comment2" rows="3" cols="60"}</td>
</tr>

<tr><td class="property">Student interviewed</td>
<td><input type="checkbox" name="check3"
{if $assessment->get_value("check3")} checked {/if}
></td>
  <td>{$assessment->flag_error("comment3")}
  {include file="general/assessment/textarea.tpl" name="comment3" rows="3" cols="60"}</td>
</tr>

<tr><td class="property">Company representative interviewed</td>
<td><input type="checkbox" name="check4"
{if $assessment->get_value("check4")} checked {/if}
></td>
  <td>{$assessment->flag_error("comment4")}
  {include file="general/assessment/textarea.tpl" name="comment4" rows="3" cols="60"}</td>
</tr>

<tr><td class="property">Log book inspected</td>
<td><input type="checkbox" name="check5"
{if $assessment->get_value("check5")} checked {/if}
></td>
  <td>{$assessment->flag_error("comment5")}
  {include file="general/assessment/textarea.tpl" name="comment5" rows="3" cols="60"}</td>
</tr>

<tr><td class="property">Health and Safety Checklist Inspected</td>
<td><input type="checkbox" name="check6"
{if $assessment->get_value("check6")} checked {/if}
></td>
  <td>{$assessment->flag_error("comment6")}
  {include file="general/assessment/textarea.tpl" name="comment6" rows="3" cols="60"}</td>
</tr>

<tr><td class="property">Student accomodation satisfactory</td>
<td><input type="checkbox" name="check7"
{if $assessment->get_value("check7")} checked {/if}
></td>
  <td>{$assessment->flag_error("comment7")}
  {include file="general/assessment/textarea.tpl" name="comment7" rows="3" cols="60"}</td>
</tr>
</table>

<table>

<tr>
  <td class="property">Changes to the training / experience programme</td>
  <td>{$assessment->flag_error("changes")}</td>
  <td>{include file="general/assessment/textarea.tpl" name="changes" rows="10" cols="80"}</td>
</tr>

<tr>
  <td class="property">Comments on the student and programme</td>
  <td>{$assessment->flag_error("scomments")}</td>
  <td>{include file="general/assessment/textarea.tpl" name="scomments" rows="10" cols="80"}</td>
</tr>

<tr>
  <td class="property">Advice given to the student</td>
  <td>{$assessment->flag_error("advice")}</td>
  <td>{include file="general/assessment/textarea.tpl" name="advice" rows="10" cols="80"}</td>
</tr>

<tr>
  <td class="property">Assessment Date (dd/mm/yyyy)</td>
  <td>{$assessment->flag_error("assesseddate")}</td>
  <td><input type="text" name="assesseddate" value="{$assessment->get_value("assesseddate")}"></td>
<tr>
<tr><td></td><td></td><td><input type="submit" name="button" value="confirm"></td></tr>

</table>
</div>
