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
  <textarea rows="3" cols="60" name="comment1">{$assessment->get_value("comment1")|escape:"htmlall"}
  </textarea>
</tr>

<tr><td class="property">Industrial supervisor appointed</td>
<td><input type="checkbox" name="check2"
{if $assessment->get_value("check2")} checked {/if}
></td>
<td>{$assessment->flag_error("comment2")}
<textarea rows="3" cols="60" name="comment2">
{$assessment->get_value("comment2")|escape:"htmlall"}
</textarea></tr>

<tr><td class="property">Student interviewed</td>
<td><input type="checkbox" name="check3"
{if $assessment->get_value("check3")} checked {/if}
></td>
<td>{$assessment->flag_error("comment3")}
<textarea rows="3" cols="60" name="comment3">
{$assessment->get_value("comment3")|escape:"htmlall"}
</textarea></tr>

<tr><td class="property">Company representative interviewed</td>
<td><input type="checkbox" name="check4"
{if $assessment->get_value("check4")} checked {/if}
></td>
<td>{$assessment->flag_error("comment4")}
<textarea rows="3" cols="60" name="comment4">
{$assessment->get_value("comment4")|escape:"htmlall"}
</textarea></tr>

<tr><td class="property">Log book inspected</td>
<td><input type="checkbox" name="check5"
{if $assessment->get_value("check5")} checked {/if}
></td><td>
  {$assessment->flag_error("comment5")}
<textarea rows="3" cols="60" name="comment5">
{$assessment->get_value("comment5")|escape:"htmlall"}
</textarea></tr>

<tr><td class="property">Health and Safety Checklist Inspected</td>
<td><input type="checkbox" name="check6"
{if $assessment->get_value("check6")} checked {/if}
></td><td>
  {$assessment->flag_error("comment6")}
<textarea rows="3" cols="60" name="comment6">
{$assessment->get_value("comment6")|escape:"htmlall"}
</textarea></tr>

<tr><td class="property">Student accomodation satisfactory</td>
<td><input type="checkbox" name="check7"
{if $assessment->get_value("check7")} checked {/if}
></td><td>
  {$assessment->flag_error("comment7")}
<textarea rows="3" cols="60" name="comment7">
{$assessment->get_value("comment7")|escape:"htmlall"}
</textarea></tr>
</table>

<table>

<tr>
  <td class="property">Changes to the training / experience programme</td>
  <td>{$assessment->flag_error("changes")}</td>
  <td><textarea cols="80" rows="10" name="changes">{$assessment->get_value("changes")|escape:"htmlall"}</textarea></td>
</tr>

<tr>
  <td class="property">Comments on the student and programme</td>
  <td>{$assessment->flag_error("scomments")}</td>
  <td><textarea cols="80" rows="10" name="scomments">{$assessment->get_value("scomments")|escape:"htmlall"}</textarea></td>
</tr>

<tr>
  <td class="property">Advice given to the student</td>
  <td>{$assessment->flag_error("advice")}</td>
  <td><textarea cols="80" rows="10" name="advice">{$assessment->get_value("advice")|escape:"htmlall"}</textarea></td>
</tr>

<tr>
  <td class="property">Assessment Date (dd/mm/yyyy)</td>
  <td>{$assessment->flag_error("assesseddate")}</td>
  <td><input type="text" name="assesseddate" value="{$assessment->get_value("assesseddate")}"></td>
<tr>
<tr><td></td><td></td><td><input type="submit" name="button" value="confirm"></td></tr>

</table>
</div>
