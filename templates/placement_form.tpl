{* Smarty *}

{section name=placement loop=$placements}

<!-- Start of placement_form -->
<h3 align="center">Placement Information</h3>
{include file="form_start.tpl" form=$form}
<input type="hidden" name="placement_id" value="{$placements[placement].placement_id}">
<input type="hidden" name="supervisor_oldemail" value="{$placements[placement].supervisor_email}">
<table align="center">


<a name="placement-{$placements[placement].placement_id}">
<tr>
  <th>Company</th>
{if $session.user.type == admin || $session.user.type == root}
  <td><a href="{$conf.scripts.company.edit}?mode=COMPANY_BASICEDIT&company_id={$placements[placement].company_id}">
{else}
  <td><a href="{$conf.scripts.company.directory}?mode=CompanyView&company_id={$placements[placement].company_id}">
{/if}
{$placements[placement].company_name|escape:"htmlall"}</td>

</tr>
{* Display vacany information if it exists *}
{if $placements[placement].vacancy_id}
<tr>
  <th>Vacancy</th>
{if $session.user.type == admin || $session.user.type == root}
  <td><a href="{$conf.scripts.company.edit}?mode=VacancyEdit&vacancy_id={$placements[placement].vacancy_id}">
{else}
  <td><a href="{$conf.scripts.company.directory}?mode=VacancyView&vacancy_id={$placements[placement].vacancy_id}">
{/if}
{$placements[placement].description|escape:"htmlall"}</td>
</tr>
{/if}
<tr>
  <th>Position</th>
  <td><input name="position" size="30" value="{$placements[placement].position}"></td>
</tr>
<tr>
  <th>Start date</th>
  <td><INPUT TYPE="TEXT" NAME="jobstart" SIZE="20" VALUE="{$placements[placement].jobstart}">
      {include file="calendar_popup.tpl" date_input="jobstart"}</td>
</tr>
<tr>
  <th>End date</th>
  <td><INPUT TYPE="TEXT" NAME="jobend" SIZE="20" VALUE="{$placements[placement].jobend}">
      {include file="calendar_popup.tpl" date_input="jobend"}</td>
</tr>
<tr>
  <th>Salary</th>
  <td><input type="text" name="salary" SIZE="20" VALUE="{$placements[placement].salary}"></td>
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

