{* Smarty *}
<h2 align="center">Are you sure?</h2>
<h3 align="center">Confirm Deletion of Placement Record</h3>
<p align="center">You have chosen to delete a placement record for job description
{$placement_info.position|escape:"htmlall"} for student {$placement_info.student_name|escape:"htmlall"}. This should not normally be done. Are you sure?</p>
<p align="center">
<a href="{$conf.scripts.admin.studentdir}?mode=StudentDeletePlacement&confirmed=TRUE&placement_id={$placement_info.placement_id}">Click here to confirm the deletion</a>