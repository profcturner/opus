{* Smarty *}

<h3>Superuser (root) administrators </h3>
{include file="list.tpl" objects=$root_objects headings=$root_headings page="0" object_num="0"}

<h3>Normal Administrators</h3>
{include file="list.tpl" objects=$admin_objects headings=$admin_headings}
