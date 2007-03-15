{* Smarty *}
{* Activity Admins *}
{section name=activity_admin loop=$activity_admins}
{if $smarty.section.activity_admin.first}
<h2 align="center">Activity Administrators</h2>
<h3 align="center">{$activity_name|escape:"htmlall"}</h3>
<p align="center">The following administrators should be able to help you with queries
related to {$activity_name|escape:"htmlall"}.</p>
<table border="1" align="center">
<tr>
<th>Name</th><th>Email</th><th>Phone</th><th>Position</th>
</tr>
{/if}
<tr>
<td>
{$activity_admins[activity_admin].title|escape:"htmlall"} 
{$activity_admins[activity_admin].firstname|escape:"htmlall"} 
{$activity_admins[activity_admin].surname|escape:"htmlall"} 
</td>
<td>
<a href="mailto:{$activity_admins[activity_admin].email}">
{$activity_admins[activity_admin].email|escape:"htmlall"} 
</a>
</td>
<td>
{$activity_admins[activity_admin].voice|escape:"htmlall"} 
</td>
<td>
{$activity_admins[activity_admin].position|escape:"htmlall"} 
</td>
</tr>

{if $smarty.section.activity_admin.last}
</table>
{/if}
{/section}
