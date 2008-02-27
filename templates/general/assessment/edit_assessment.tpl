{* Smarty *}

{* Important logic we don't want in the header template *}
{if $assessment->can_view}

{if !$assessment->can_edit}
<div id="warning">
{#cannot_edit#}
</div>
{/if}

{include file="general/assessment/assessment_header.tpl"}
<!-- Start of custom template -->
{include file=$assessment->assessment->template_filename}
<!-- End of custom template -->
{include file="general/assessment/assessment_footer.tpl"}
{else} {* can_view *}
<div id="warning">
{#cannot_view#}
</div>
{/if}