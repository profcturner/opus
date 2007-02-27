{if $session.user.type == "student"}
{assign var="with_for" value="for"}
{else}
{assign var="with_for" value="with"}
{/if}

<h2 align="center">Help {$with_for} {$student_name|escape:"htmlall"}</h2>

<p align="center">
These administrators are those most able to give help {$with_for}
<strong>{$student_name|escape:"htmlall"}</strong>. Generally speaking
the list is designed to give those people closest to the
student first.
</p>

{include file="help/help_directory/course_admins.tpl"}

{include file="help/help_directory/school_admins.tpl"}

{include file="help/help_directory/root_admins.tpl"}
