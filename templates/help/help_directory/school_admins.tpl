{* Smarty *}
{* School Admins *}
{section name=school_admin loop=$school_admins}
{if $smarty.section.school_admin.first}
<h2 align="center">School Administrators</h2>
<h3 align="center">{$school_name|escape:"htmlall"}</h3>
<table border="1" align="center">
<tr>
<th>Name</th><th>Email</th><th>Phone</th><th>Position</th>
</tr>
{/if}
<tr>
<td>
{$school_admins[school_admin].title|escape:"htmlall"} 
{$school_admins[school_admin].firstname|escape:"htmlall"} 
{$school_admins[school_admin].surname|escape:"htmlall"} 
</td>
<td>
<a href="mailto:{$school_admins[school_admin].email}">
{$school_admins[school_admin].email|escape:"htmlall"} 
</a>
</td>
<td>
{$school_admins[school_admin].voice|escape:"htmlall"} 
</td>
<td>
{$school_admins[school_admin].position|escape:"htmlall"} 
</td>
</tr>

{if $smarty.section.school_admin.last}
</table>
{/if}
{/section}
