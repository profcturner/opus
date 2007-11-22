{* Smarty *}
{* Template for displaying a list of companies fed in *}

<!-- Start of Company Listing -->
<H3 align="center">Companies</H3>
{section name=company loop=$companies}
  {if $smarty.section.company.first}
<table align="center" border="1">
  <tr><th>Name</th><th>Locality</th>{if $edit}<th>Last Access</th>{/if}</tr>
  {/if}
  <tr>
    <td><a href="
{if $edit}
{$conf.scripts.company.edit}?mode=COMPANY_BASICEDIT&company_id={$companies[company].company_id}
{else}
{$conf.scripts.company.directory}?mode=CompanyView&company_id={$companies[company].company_id}{if $student_id}&student_id={$student_id}{/if}
{/if}
">{$companies[company].name|escape:"htmlall"}
{if $edit}
</a>
{/if}
</td>
    <td>{$companies[company].locality|escape:"htmlall"}</td>
{if $edit}
    <td>{$companies[company].last_access}</td>
{/if}
  </tr>
  {if $smarty.section.company.last}
</table>
<p align="center">There are {$smarty.section.company.total} companies in this listing.</p>
  {/if}
{sectionelse}
<p align="center">No companies matched the search criteria</p>
{/section}
<!-- End of Company Listing -->

