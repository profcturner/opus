{* Smarty *}
{* this file completes standard assessments *}
</form>

{* If errors occured this is where we list them *}
{if $assessment->get_error() && $mode=="AssessmentSubmitResults"}
<div class="error">
<a name="errors">
<h2 class="error" align="center">Errors occured</h2>
<p>
{$assessment->get_error()}
</p>
</div>
{/if}
