{* Smarty *}
{* Student Home Page *}

<p align="center">Please note there are <a href="#otherresources">other resources</a> to help you find placement</p>

{* Information for placed students *}

{* Academic Tutor, if he/she exists *}
{if($academic_tutor)}
<!-- Start Academic Tutor -->
<h3>Academic Tutor</h3>
<p>Here are some contact details for the academic tutor you have been allocated.</p>

<table>
<tr>
<td>

<table>
<tr>
  <th>Name</th>
  <td>{$academic_tutor.title|escape:"htmlall" $academic_tutor.firstname|escape:"htmlall" $academic_tutor.surname|escape:"htmlall"}</td>
</tr>
<tr>
  <th>Position</th>
  <td>{$academic_tutor.position|escape:"htmlall"}</td>
</tr>
<tr>
  <th>Room</th>
  <td>{$academic_tutor.room|escape:"htmlall"}</td>
</tr>
<tr>
   <th>Department</th>
   <td>>{$academic_tutor.department|escape:"htmlall"}</td>
</tr>
<tr>
   <th>Address</th>
   <td>{$academic_tutor.row|escape"htmlall"}</td>
</tr>
<tr>
  <th>Phone</th>
  <td>{$academic_tutor.voice|escape:"htmlall"}</td>
</tr>
<tr>
  <th>Email</th>
  <td><a href="mailto:{$academic_tutor|escape:"htmlall"}">{$academic_tutor|escape:"htmlall"}</a></td>
</tr>
</table>

</td>
<td>

</td>
</tr>

{/if}
