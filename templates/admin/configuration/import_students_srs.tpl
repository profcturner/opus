{* Smarty *}
{* Displays the result of an import from SRS *}
{* Actually, using it for files now too! *}

<h3>{$programme->name|escape:"htmlall"} ({$programme->srs_ident|escape:"htmlall"})</h3>

{if $test}
<div id="warning">{#test_on#}</div>
{/if}
{if $csvmapping}
Format: {$csvmapping->name|escape:"htmlall"}
{/if}
<div id="table_list">
{section name=student loop=$students}
{if $smarty.section.student.first}
<form method="post">
  <input type="hidden" name="section" value="configuration" />
  <input type="hidden" name="function" value="import_students_do" />
  <input type="hidden" name="programme_id" value="{$programme->id}" />
  <input type="hidden" name="year" value="{$year}" />
  <input type="hidden" name="onlyyear" value="{$onlyyear}" />
  <input type="hidden" name="status" value="{$status}" />
  <input type="hidden" name="csvmapping_id" value="{$csvmapping_id}" />
  <input type="hidden" name="filename" value="{$filename}" />
  <table>
    <tr><th>Name</th><th>Registration Number</th><th>Year</th><th>Email</th><th>Result</th></tr>
{/if}
{eval var=$students[student].reg_number assign="reg_number"}
    <tr class="{cycle values="dark_row,light_row"}">
      <td>{$students[student].person_title|escape:"htmlall"} {$students[student].first_name|escape:"htmlall"} {$students[student].last_name|escape:"htmlall"}</td>
      <td>{$students[student].reg_number}</td>
      <td>{$students[student].year_on_course}</td>
      <td>{eval var=$students[student].email_address}</td>
      <td>{$students[student].result}</td>
    </tr>
{if $smarty.section.student.last}
  </table>
    {if $test}
      {if $csvmapping}<p>Re-run this process with the test option de-selected</p>
      {else}<input class="button" type="submit" value="confirm" />{/if}
    {/if}
</form>
{/if}
{sectionelse}
No students were found.
{/section}
</div>