{* Smarty *}
{* List of course administrators *}
{section name=course_admin loop=$course_admins}
{if $smarty.section.course_admin.first}
<h2 align="center">Course Administrators</h2>
<h3 align="center">{$course_name|escape:"htmlall"}</h3>
<table border="1" align="center">
<tr>
<th>Name</th><th>Email</th><th>Phone</th><th>Position</th>
</tr>
{/if}
<tr>
<td>
{$course_admins[course_admin].title|escape:"htmlall"} 
{$course_admins[course_admin].firstname|escape:"htmlall"} 
{$course_admins[course_admin].surname|escape:"htmlall"} 
</td>
<td>
<a href="mailto:{$course_admins[course_admin].email}">
{$course_admins[course_admin].email|escape:"htmlall"} 
</a>
</td>
<td>
{$course_admins[course_admin].voice|escape:"htmlall"} 
</td>
<td>
{$course_admins[course_admin].position|escape:"htmlall"} 
</td>
</tr>

{if $smarty.section.course_admin.last}
</table>
{/if}
{/section}
