{* Smarty *}

<h3>Superuser (root) administrators </h3>
{include file="list.tpl" objects=$root_objects}

<h3>Normal Administrators</h3>
{include file="list.tpl" objects=$admin_objects}
