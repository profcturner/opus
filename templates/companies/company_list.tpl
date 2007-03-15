{* Smarty *}
{* Template for displaying a list of companies fed in *}

<!-- Start of Company Listing -->
<H3 align="center">Companies</H3>
{section name=company loop=$companies}
  {if $smarty.section.company.first}
<table align="center" border="1">
  <tr><th>Name</th><th>Locality</th><th>Last Access</th></tr>
  {/if}
  <tr>
    <td>{$companies[company].name|escape:"htmlall"}</td>
    <td>{$companies[company].locality|escape:"htmlall"}</td>
    <td>{$companies[company].last_access}</td>
  </tr>
  {if $smarty.section.company.last}
</table>
<p align="center">There are {$smarty.section.company.total} companies in this listing.</p>
  {/if}
{sectionelse}
<p align="center">No companies matched the search criteria</p>
{/section}
<p align="center"><a href="{$conf.scripts.company.edit}?mode=VacancyAdd&company_id={$company_id}">
Click here to add a new vacancy
</a>
</p>
<!-- End of Company Listing -->
