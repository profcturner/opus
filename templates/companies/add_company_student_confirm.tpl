{* Smarty *}
{* Starts to process of a new application *}

{* @todo We'll want to add more here, once we can encapsulate help in templates *}

<p>
You have {$count_completed_cvs} completed CVs within the PDSystem, of which
{$count_allowed_cvs} are available for this application and {$count_archived_cvs} archived CVs. </p>

<h3>Application</h3>
<form action="{$conf.scripts.company.directory}" method="post">
<input type="hidden" name="mode" value="COMPANY_ADDCOMPANYSTUDENT">
<input type="hidden" name="confirm" value="1">
<input type="hidden" name="student_id" value="{$student_id}">
<input type="hidden" name="year" value="{$year}">
<input type="hidden" name="company_id" value="{$company_id}">
<input type="hidden" name="vacancy_id" value="{$vacancy_id}">

<table align="center">
<tr>
  <th>preferred cv</th>
  <td><select name="prefcvt">
{* Admin users sometimes need to be able to record an application, even with no valid CV *}
{if $session.user.type == "root" || $session.user.type == "admin"}
        <option value="0">Force Application (Admin Only)</option>
{/if}
{* Loop through valid CVs *}
{section name=cv loop=$cvs}
<option value="{$cvs[cv].template_id}" {if $cvs[cv].template_id==$default_template_id}selected {/if}>{$cvs[cv].name|escape:"htmlall"} (From PDSystem)</option>
{/section}
{* Loop through archive CVs *}
{section name=archived_cv loop=$archived_cvs}
{if $smarty.section.archived_cv.first}
<option disabled value="0">Archived CVs</option>
{/if}
<option value="hash_{$archived_cvs[archived_cv]->hash}_{$archived_cvs[archived_cv]->type}">{$archived_cvs[archived_cv]->type|escape:"htmlall"} (From PDSystem)</option>
{/section}
{* Loop through invalid CVs for information *}
{section name=invalid_cv loop=$invalid_cvs}
{if $smarty.section.invalid_cv.first}
<option disabled value="0">Disallowed CVs from the PDSystem</option>
{/if}
<option value="{$invalid_cvs[invalid_cv].template_id}">{$invalid_cvs[invalid_cv].name|escape:"htmlall"} {$invalid_cvs[invalid_cv].problem|escape:"htmlall"}</option>
{/section}
</select></td>
{* End of template field *}
<tr><th colspan="2">Cover Letter (Optional)</th></tr>
<tr><td colspan="2"><textarea  rows="20" cols="60" wrap="virtual" name="cover"></textarea></td></tr>
<tr><td colspan="2"><input type="submit" value="I am sure!"></td></tr>
</table></form>
