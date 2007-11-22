{* Smarty *}
{* For inserting into other templates for allowing activity types to be selected *}
<table>
{section name=activity loop=$activities}
<tr><td><label><input type="checkbox" name="activities[]" value="{$activities[activity].activity_id}"
{if $activities[activity].selected}
 SELECTED
{/if}
>{$activities[activity].name|escape:"htmlall"}</td><tr>
{/section}
</table>