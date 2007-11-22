{* Smarty *}
{* Confirms the deletion of an assessment instance for an assessment group *}

<h2>Are You Sure?</h2>

<p>You have selected to delete an assessment "{$assessment_name|escape:"htmlall"}" from
the assessment group "{$group_name|escape:"htmlall"}". While this will not remove
assessment information already recorded, it will make it inaccessable. You should
clearly understand the ramification before you do this.</p>

<a href="{$conf.scripts.admin.courses}?mode=AssessmentGroups_DeleteAssessment&cassessment_id={$cassessment_id}&group_id={$group_id}&confirmed=1">OK</a>&nbsp;|&nbsp;<a href="{$conf.scripts.admin.courses}?mode=AssessmentGroups_Edit&group_id={$group_id}">Cancel</a>