{* Smarty *}
{* Confirms the deletion of an Assessment group *}

<h2>Are You Sure?</h2>

<p>You have selected to delete a assessment group "{$group_name|escape:"htmlall"}". Generally there is no
requirement to do this, and you should be very sure. Are you sure?</p>

<a href="{$conf.scripts.admin.courses}?mode=AssessmentGroups_Delete&group_id={$group_id}&confirmed=1">OK</a>&nbsp;|&nbsp;<a href="{$conf.scripts.admin.courses}?mode=AssessmentGroups_List">Cancel</a>