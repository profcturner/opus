{* Smarty *}

<h3>Specific Admins</h3>
{include file="list.tpl" objects=$specific_admins headings=$admin_headings}

<h3>Institutional Administrators</h3>
{include file="list.tpl" objects=$inst_admins headings=$admin_headings}


<h3>Superuser (root) administrators </h3>
{include file="list.tpl" objects=$root_admins headings=$root_headings}
