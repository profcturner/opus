{* Smarty *}

<h3>{#basic_information#}</h3>
<div id="table_manage">
<table>
<tr>
  <td class="property">Name</td>
  <td>{$student->real_name|escape:"htmlall"}</td>
</tr>
<tr>
  <td class="property">Registration Number</td>
  <td>{$student->reg_number|escape:"htmlall"}</td>
</tr>
<tr>
  <td class="property">Email</td>
  <td><a href="mailto:{$student->email|escape:"htmlall"}">{$student->email|escape:"htmlall"}</a></td>
</tr>
</table>
</div>

<h3>{#placement_history#}</h3>
{if $placements}
{include file="list.tpl" objects=$placements headings=$placement_fields actions=$placement_options}
{else}
{#no_history#}{* should never happen! *}
{/if}

<h3>Photograph</h3>
<a href="?section=directories&function=display_photo&user_id={$object->user_id}&fullsize=true" >
<img width="200" border="0"  src="?section=directories&function=display_photo&user_id={$object->user_id}" /></a>

<h3>Assessment</h3>
{include file="general/assessment/assessment_results.tpl"}

<h3>testing</h3>

<table>
  <tr>
    <th width="50%">Placement Information</th>
    <th>Vacancy Address</th>
  </tr>
  <tr>
    <td>{include file="staff/student/edit_student_placement.tpl"}</td>
    <td>{include file="staff/student/edit_student_vacancy.tpl"}</td>
  </tr>
  <tr>
    <th>Supervisor Contact Details</th>
    <th>Company HR Contact Details</th>
  </tr>
  <tr>
    <td>{include file="staff/student/edit_student_supervisor.tpl"}</td>
    <td>{include file="staff/student/edit_student_contact.tpl"}</td>
  </tr>
</table>
