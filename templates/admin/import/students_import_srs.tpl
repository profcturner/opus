{* Smarty *}
{* Displays the result of an import from SRS *}
<h2>Import Data from Student Records</h2>

<h3>{$course_name|escape:"htmlall"}</h3>

{if $test}
<strong>You are in testing mode, you must go back and uncheck the test box to actually import students.
{/if}

{section name=student loop=$students}
{if $smarty.section.student.first}
<table border="1">
<tr><th>Name</th><th>Number</th><th>Email</th><th>Result</th></tr>
{/if}
{eval var=$students[student].reg_number assign="reg_number"}
<tr class="{cycle values="list_row_light,list_row_dark"}">
  <td>{$students[student].person_title|escape:"htmlall"} {$students[student].first_name|escape:"htmlall"} {$students[student].last_name|escape:"htmlall"}</td>
  <td>{$students[student].reg_number}</td>
  <td>{eval var=$students[student].email_address}</td>
  <td>{$students[student].result}</td>
</tr>
{if $smarty.section.student.last}
</table>
{/if}
{sectionelse}
No students were found.
{/section}