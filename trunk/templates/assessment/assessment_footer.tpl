{* Smarty *}
{* assessment/assessment_footer.tpl *}

{* this file completes standard assessments *}
</form>

{* Provide links for more printer friendly views *}
{if $printer_friendly}
<p align="center">
<a href="?mode=AssessmentDisplayForm&cassessment_id={$assessment->getCassessment_id()}&assessed_id={$assessment->assessed_id}">Normal View (show menu)</a>
</p>
{else}
<p align="center">
<a href="?mode=AssessmentDisplayForm&cassessment_id={$assessment->getCassessment_id()}&assessed_id={$assessment->assessed_id}&printer_friendly=yes">Printer Friendly Version (hide menu)</a></p>
{/if}

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
