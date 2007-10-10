{* Smarty *}
{* assessment/assessment_footer.tpl *}

{* this file completes standard assessments *}
</form>

{* If errors occured this is where we list them *}
{if $assessment->getError() && $mode=="AssessmentSubmitResults"}
<div class="error">
<a name="errors">
<h2 class="error" align="center">Errors occured</h2>
<p>
{$assessment->getError()}
</p>
</div>
{/if}
