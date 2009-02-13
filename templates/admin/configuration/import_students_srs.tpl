{* Smarty *}
{* Displays the result of an import from SRS *}
{* Actually, using it for files now too! *}
{* And can be used in testing CSV mode *}

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
    {if $csvformattest}
    <tr><th>Name</th><th>Registration Number</th><th>Year</th><th>Email</th><th>Programme</th><th>Disability</th><th>Username</th></tr>    
    {else}
    <tr><th>Name</th><th>Registration Number</th><th>Year</th><th>Email</th><th>Result</th></tr>
    {/if}
{/if}
{eval var=$students[student].reg_number assign="reg_number"}
    <tr class="{cycle values="dark_row,light_row"}">
      <td>{$students[student].person_title|escape:"htmlall"} {$students[student].first_name|escape:"htmlall"} {$students[student].last_name|escape:"htmlall"}</td>
      <td>{$students[student].reg_number}</td>
      <td>{$students[student].year_on_course}</td>
      <td>{eval var=$students[student].email_address}</td>
      {if $csvformattest}
      <td>{$students[student].programme_code}</td>
      <td>{$students[student].disability_code}</td>
      <td>{$students[student].username}</td>
      {else}
      <td>{$students[student].result}</td>
      {/if}
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

{* Excluded lines *}

<div id="table_list">
{foreach from=$excluded_lines item=line name=lines}
{if $smarty.foreach.lines.first}
{#excluded_lines#}
<table class="table_list">
{/if}
<tr class="{cycle values="light_row,dark_row"}">
  <td>{$smarty.foreach.lines.index+1}</td>
  <td>{$line}</td>
</tr>
{if $smarty.foreach.lines.last}
</table>
{/if}
{foreachelse}
{/foreach}
</div>

{* Rejected lines *}

<div id="table_list">
{foreach from=$rejected_lines item=line name=lines}
{if $smarty.foreach.lines.first}
{#rejected_lines#}
{if $user.opus.user_type == 'root'}
{#link_to_csv#}<a href="?section=superuser&function=edit_csvmapping&id={$csvmapping->id}">{#here#}.</a>
{/if}
<table class="table_list">
{/if}
<tr class="{cycle values="light_row,dark_row"}">
  <td>{$smarty.foreach.lines.index+1}</td>
  <td>{$line}</td>
</tr>
{if $smarty.foreach.lines.last}
</table>
{/if}
{foreachelse}
{/foreach}
</div>

{* Mismapped lines *}

<div id="table_list">
{foreach from=$mismapped_lines item=line name=lines}
{if $smarty.foreach.lines.first}
{#mismapped_lines#}
{if $user.opus.user_type == 'root'}
{#link_to_csv#}<a href="?section=superuser&function=edit_csvmapping&id={$csvmapping->id}">{#here#}.</a>
{/if}
<table class="table_list">
{/if}
<tr class="{cycle values="light_row,dark_row"}">
  <td>{$smarty.foreach.lines.index+1}</td>
  <td>{$line}</td>
</tr>
{if $smarty.foreach.lines.last}
</table>
{/if}
{foreachelse}
{/foreach}
</div>



