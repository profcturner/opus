{* Smarty *}

{literal}
<script language="JavaScript" type="text/javascript">
<!--
function toggleAll(checked)
{
  for (i = 0; i < document.applicants.elements.length; i++) {
    if (document.applicants.elements[i].name.indexOf('send') >= 0) 
    {
      document.applicants.elements[i].checked = checked;
    }
  }
}
// -->
</script>
{/literal}

{if !$placed && !$available && !$unavailable }
{#no_list#}
{else}
<form name="applicants" method="post">
  <input type="hidden" name="section" value="directories" />
  <input type="hidden" name="function" value="manage_applicants_do" />
  <input type="hidden" name="vacancy_id" value="{$vacancy_id}" />
  <span style="float: right"><input class="button" type="submit" class="submit" value="update" /></span>

<a href="" onclick="toggleAll(true); return false;" onmouseover="status='Select all'; return true;">Select All</a> |
<a href="" onclick="toggleAll(false); return false;" onmouseover="status='Select all'; return true;">Deselect All</a><br />

{if $placed}
{include file="admin/directories/manage_applicants_subsection.tpl" applications=$placed subsection_title="Already Selected"}
{/if}

{if $available}
{include file="admin/directories/manage_applicants_subsection.tpl" applications=$available subsection_title="Still Available"}
{/if}

{if $unavailable}
{include file="admin/directories/manage_applicants_subsection.tpl" applications=$unavailable subsection_title="No Longer Available"}
{/if}
  <span style="float: right"><input class="button" type="submit" class="submit" value="update" /></span>
</form>

<a href="" onclick="toggleAll(true); return false;" onmouseover="status='Select all'; return true;">Select All</a> |
<a href="" onclick="toggleAll(false); return false;" onmouseover="status='Select all'; return true;">Deselect All</a><br />
{/if}