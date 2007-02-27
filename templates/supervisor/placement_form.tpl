{* Smarty *}

<!-- Start of placement_form -->
<h3 align="center">Placement Information</h3>
<p align="center">You can use the form below to keep your contact information correct. <br />Alert the
<a href="{$conf.scripts.user.helpdir}?student_id={$student_id}">Placement Team</a> for your student to correct those fields you cannot
edit.<br />
{include file="form_start.tpl" form=$form}
<input type="hidden" name="placement_id" value="{$placement_info.placement_id}">
<input type="hidden" name="supervisor_oldemail" value="{$placement_info.supervisor_email}">
<table align="center">


<a name="placement-{$placement_info.placement_id}">
<tr>
  <th>Company</th>
  <td>
<a href="{$conf.scripts.company.directory}?company_id={$placement_info.company_id}&mode=CompanyView">
{$placement_info.company_name|escape:"htmlall"}</a></td>
</tr>
{* Display vacany information if it exists *}
{if $placement_info.vacancy_id}
<tr>
  <th>Vacancy</th>
  <td>
<a href="{$conf.scripts.company.directory}?vacancy_id={$placement_info.vacancy_id}&mode=VacancyView">
{$placement_info.description|escape:"htmlall"}</a></td>
</tr>
{/if}
<tr>
  <th>Position</th>
  <td>{$placement_info.position|escape:"htmlall"}</td>
</tr>
<tr>
  <th>Start date</th>
  <td>{$placement_info.jobstart|escape:"htmlall"}</td>
</tr>
<tr>
  <th>End date</th>
  <td>{$placement_info.jobend|escape:"htmlall"}</td>
</tr>
<tr>
  <th>Salary</th>
  <td>{$placement_info.salary|escape:"htmlall"}</td>
</tr>
<tr>
  <th>Work phone</th>
  <td>{$placement_info.voice|escape:"htmlall"}</td>
</tr>
<tr>
  <th>Work email</th>
  <td>{$placement_info.email|escape:"htmlall"}</td>
</tr>
<tr>
  <th colspan="2" align="center">Industrial Supervisor Contact Details</th>
</tr>
<tr>
  <th>Title</th>
  <td><input type="text" name="supervisor_title" SIZE="5" VALUE="{$placement_info.supervisor_title}"></td>
</tr>
<tr>
  <th>First name</th>
  <td><input type="text" name="supervisor_firstname" SIZE="30" VALUE="{$placement_info.supervisor_firstname}"></td>
</tr>
<tr>
  <th>Surname</th>
  <td><input type="text" name="supervisor_surname" SIZE="30" VALUE="{$placement_info.supervisor_surname}"></td>
</tr>
<tr>
  <th>Email</th>
  <td><input type="text" name="supervisor_email" SIZE="30" VALUE="{$placement_info.supervisor_email}"></td>
</tr>
<tr>
  <th>Phone</th>
  <td><input type="text" name="supervisor_voice" SIZE="30" VALUE="{$placement_info.supervisor_voice}"></td>
</tr>

<tr>
  <td colspan="2" align="center"><input type="submit" value="Submit Changes"><input type="reset">
</tr>
</table>
</form>
<!-- End of placement_form -->

