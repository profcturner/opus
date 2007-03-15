{* Smarty *}

{* Used to create a form to begin recording a student as placed *}

{* Smarty *}

<!-- Start of placement_form -->

<h2 align="center">Choose Placement Vacancy</h2>

<p><b>Note:</b> the student will not be registered as placed until this process is completed. First please choose the
company with which the student is to be placed, and then further details may be edited as required.</p>

{include file="form_start.tpl" form=$form}
<table align="center">

<tr>
  <th>Vacancy</th>
  <td>
<select name="vacancy_id">
{html_options values=$form.data.vacancies.ids output=$form.data.vacancies.titles}
</select>
  </td>
</tr>
<tr>
  <td colspan="2" align="center"><input type="submit" value="submit">
</tr>
</table>
</form>

<!-- End of placement_form -->