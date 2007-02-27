{* Smarty *}

{section name=placement loop=$placements}

<!-- Start of placement_form -->
<h3 align="center">Placement Information</h3>
<p align="center">Ensure that you keep this information correct and up-to-date. Alert your
<a href="{$conf.scripts.user.helpdir}">Placement Team</a> to correct those fields you cannot
edit.<br /><strong>It is very important you enter the correct details for your industrial
supervisor below.</strong></p>
{include file="form_start.tpl" form=$form}
<input type="hidden" name="placement_id" value="{$placements[placement].placement_id}">
<input type="hidden" name="supervisor_oldemail" value="{$placements[placement].supervisor_email}">
<table align="center">


<a name="placement-{$placements[placement].placement_id}">
<tr>
  <th>Company</th>
  <td>{$placements[placement].company_name|escape:"htmlall"}</td>
</tr>
{* Display vacany information if it exists *}
{if $placements[placement].vacancy_id}
<tr>
  <th>Vacancy</th>
  <td>{$placements[placement].description|escape:"htmlall"}</td>
</tr>
{/if}
<tr>
  <th>Position</th>
  <td>{$placements[placement].position|escape:"htmlall"}</td>
</tr>
<tr>
  <th>Start date</th>
  <td>{$placements[placement].jobstart|escape:"htmlall"}</td>
</tr>
<tr>
  <th>End date</th>
  <td>{$placements[placement].jobend|escape:"htmlall"}</td>
</tr>
<tr>
  <th>Salary</th>
  <td>{$placements[placement].salary|escape:"htmlall"}</td>
</tr>
<tr>
  <th>Work phone</th>
  <td><input type="text" name="voice" SIZE="30" VALUE="{$placements[placement].voice}"></td>
</tr>
<tr>
  <th>Work email</th>
  <td><input type="text" name="email" SIZE="30" VALUE="{$placements[placement].email}"></td>
</tr>
<tr>
  <th colspan="2" align="center">Industrial Supervisor Contact Details</th>
</tr>
<tr>
  <th>Title</th>
  <td><input type="text" name="supervisor_title" SIZE="5" VALUE="{$placements[placement].supervisor_title}"></td>
</tr>
<tr>
  <th>First name</th>
  <td><input type="text" name="supervisor_firstname" SIZE="30" VALUE="{$placements[placement].supervisor_firstname}"></td>
</tr>
<tr>
  <th>Surname</th>
  <td><input type="text" name="supervisor_surname" SIZE="30" VALUE="{$placements[placement].supervisor_surname}"></td>
</tr>
<tr>
  <th>Email</th>
  <td><input type="text" name="supervisor_email" SIZE="30" VALUE="{$placements[placement].supervisor_email}"></td>
</tr>
<tr>
  <th>Phone</th>
  <td><input type="text" name="supervisor_voice" SIZE="30" VALUE="{$placements[placement].supervisor_voice}"></td>
</tr>

<tr>
  <td colspan="2" align="center"><input type="submit" value="Submit Changes"><input type="reset">
</tr>
</table>
</form>
{if $session.user.type == "root" || $session.user.type == "admin"}
<p align="center">
<a href="{$conf.scripts.admin.studentdir}?mode=StudentDeletePlacement&placement_id={$placements[placement].placement_id}">Click here to delete the record above</a>
</p>
{/if}
<!-- End of placement_form -->
{/section}
