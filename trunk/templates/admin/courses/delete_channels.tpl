{* Smarty *}
{* Confirms the deletion of a Channel *}

<h2>Are You Sure?</h2>

<p>You have selected to delete a channel "{$channel_name|escape:"htmlall"}". Generally there is no
requirement to do this, and you should be very sure. Are you sure?</p>

<a href="{$conf.scripts.admin.courses}?mode=Channels_Delete&channel_id={$channel_id}&confirmed=1">OK</a>&nbsp;|&nbsp;<a href="{$conf.scripts.admin.courses}?mode=Channels_List">Cancel</a>