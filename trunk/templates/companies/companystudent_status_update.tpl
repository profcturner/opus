{* Smarty *}

<H2 align="center">Status Update</H2>
<p align="center">You have chosen to change the status for 
<strong>{$student_name|escape:"htmlall"}</strong> to
<strong>{$status|escape:"htmlall"}</strong>
on
their application for the vacancy 
<strong>{$vacancy_name|escape:"htmlall"}</strong>. <br />
If you would like to email the student a message related to this status change, please
insert it in the box below. Leave the "message" box empty to send no message, in this case the status change will still be visible 
to the student when they next login.</p>

<form method="post" action="{$conf.scripts.company.edit}">
<input type="hidden" name="mode" value="COMPANYSTUDENT_STATUS_UPDATE">
<input type="hidden" name="company_id" value="{$company_id}">
<input type="hidden" name="vacancy_id" value="{$vacancy_id}">
<input type="hidden" name="student_id" value="{$student_id}">
<input type="hidden" name="confirmed" value="TRUE">
<input type="hidden" name="year" value="{$year}">
<input type="hidden" name="status" value="{$status}">

<table border="0">
<tr>
  <th>Subject</th>
  <td><input type="text" size="60" name="subject" value="{$company_name|escape:"htmlall"}; {$vacancy_name|escape:"htmlall"}; {$status|escape:"htmlall"}"></td>
</tr>
<tr>
  <th>Message</th>
  <td><textarea rows="10" cols="60" name="message"></textarea></td>
</tr>
<tr>
  <td colspan="2"><input type="checkbox" name="CC" CHECKED> Send me a copy of any email</td></tr>
<tr><td colspan="2"><input type="submit" value="Confirm Status Change"></td></tr>
</table>
</form>

