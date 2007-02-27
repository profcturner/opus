{* Smarty *}
<script language="JavaScript" type="text/javascript">
<!--
function toggleAll(checked)
{ldelim}
  for (i = 0; i < document.search_results.elements.length; i++) {ldelim}
    if (document.search_results.elements[i].name.indexOf('student_ids') >= 0) {ldelim}
      document.search_results.elements[i].checked = checked;
    {rdelim}
  {rdelim}
{rdelim}
//-->
</script>


<H2 ALIGN="CENTER">Advanced Search</H2>

{section name=student loop=$students}
{if $smarty.section.student.first}
<form method="post" name="search_results" action="{$conf.scripts.admin.studentdir}?mode=StudentMassEmail">
<table align="center" border="1">
<tr>
  <th>Email</th>
  <th>Name</th>
  <th>Student Number</th>
  <th>Last Access</th>
  <th>Status</th>
  <th>Options</th>
</tr>
{/if} {* first *}
{* Actual row of data *}
<tr>
  <td><input type="checkbox" name="student_ids[]" value="{$students[student].id_number}"></td>
  <td><a href="{$students[student].cv_link}">{$students[student].real_name|escape:"htmlall"}</a></td>
  <td>{$students[student].username|escape:"htmlall"}</td>
  <td>{$students[student].last_time|escape:"htmlall"}</td>
  <td>{$students[student].status|escape:"htmlall"}</td>
  <td><a href="{$conf.scripts.admin.studentdir}?mode=STUDENT_DISPLAYSTATUS&student_id={$students[student].id_number}">[ Edit ]</a><a href="{$conf.scripts.user.helpdir}?student_id={$students[student].id_number}">[ Help ]</a></td>
</tr>
{if $show_timelines}
<tr>
  <td colspan="6"><img width="600" height="100" src="{$conf.scripts.student.timeline}?student_id={$students[student].id_number}">
  </td>
</tr>
{/if} {* show_timelines *}
{if $smarty.section.student.last}
<tr>
  <th colspan="6">Send email to selected students</th>
</tr>
<tr>
  <th colspan="2">Subject</th>
  <td colspan="4"><input type="text" name="subject" size="60"></td>
</tr>
<tr>
  <th colspan="2">Message</th>
  <td colspan="4">
    <textarea name="message" rows="10" cols="60"></textarea>
  </td>
</tr>
<tr>
  <td colspan="6">
    <input type="checkbox" name="CC" CHECKED> Send a copy of the message to me
  </td>
</tr>
<tr>
  <td colspan="6"><input type="submit" value="Send Message"></td>
</tr>
<tr>
  <td colspan="6">
  <a href="" onclick="toggleAll(true); return false;" onmouseover="status='Select all'; return true;">Select all</a> | <a href="" onclick="toggleAll(false); return false;" onmouseover="status='Select none'; return true;">Select none</a></Td>


</tr>
</table>
</form>
<p align="center">{$studentcount} students met the criteria.</p>
<P ALIGN="CENTER"><A HREF={$conf.scripts.admin.studentdir}>[ Simple Search ]</a> <A HREF="{$conf.scripts.admin.studentdir}?mode=STUDENT_ADVANCEDSEARCHFORM">[ Advanced Search ]</A></P>
{/if} {* last *}
{sectionelse}
<P ALIGN="CENTER">No students could be found to match
the search criteria.</P>
{/section}
