{* Smarty *}
{* Confirms the deletion of a CV group *}

<h2>Are You Sure?</h2>

<p>You have selected to delete a CV group "{$group_name|escape:"htmlall"}". Generally there is no
requirement to do this, and you should be very sure. Are you sure?</p>

<a href="{$conf.scripts.admin.courses}?mode=CVGroups_Delete&group_id={$group_id}&confirmed=1">OK</a>&nbsp;|&nbsp;<a href="{$conf.scripts.admin.courses}?mode=CVGroups_List">Cancel</a>