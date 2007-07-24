{section name=root_admin loop=$root_admins}
{if $smarty.section.root_admin.first}
<h2 align="center">Root Administrators</h2>
<table border="1" align="center">
<tr>
<th>Name</th><th>Email</th><th>Phone</th><th>Position</th>
</tr>
{/if}
<tr>
<td>
{$root_admins[root_admin].title|escape:"htmlall"} 
{$root_admins[root_admin].firstname|escape:"htmlall"} 
{$root_admins[root_admin].surname|escape:"htmlall"} 
</td>
<td>
<a href="mailto:{$root_admins[root_admin].email}">
{$root_admins[root_admin].email|escape:"htmlall"} 
</a>
</td>
<td>
{$root_admins[root_admin].voice|escape:"htmlall"} 
</td>
<td>
{$root_admins[root_admin].position|escape:"htmlall"} 
</td>
</tr>

{if $smarty.section.root_admin.last}
</table>
{/if}
{/section}
