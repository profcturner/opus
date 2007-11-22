<h3 align="center">Placement Episodes</h3>

<p align="center">Below you can find information about the various
expisodes of placement the students has experienced, and contact details
for your visit.</p>

{section name=placement loop=$placements}
<table width="80%" align="center" border="1">
<tr><th width="50%">Placement Information</th><th>Company Address</th></tr>
<tr>
  <td><table border="0">{* General Placement Info *}
<!-- Placement Info -->
<tr><th>Company Name</th>
<td><a href="{$conf.scripts.company.directory}?mode=CompanyView&company_id={$placements[placement].company_id}">{$placements[placement].company_name|escape:"htmlall"}</a></td>
</tr>
<tr><th>Vacancy Description</th>
<td>
{if $placements[placement].vacancy_id}
<a href="{$conf.scripts.company.directory}?mode=VacancyView&vacancy_id={$placements[placement].vacancy_id}">{$placements[placement].vacancy_description|escape:"htmlall"}</a>
{else}
None (old style record)
{/if}
</td>
</tr>
<tr>
<th>Start Date</th>
<td>{$placements[placement].jobstart|escape:"htmlall"}</td>
</tr>
<tr>
<th>End Date</th>
<td>{$placements[placement].jobend|escape:"htmlall"}</td>
</tr>
<tr>
<th>Salary</th>
<td>{$placements[placement].salary|escape:"htmlall"}</td>
</tr>
<tr>
<th>Work Phone <small>(Student)</small></th>
<td>{$placements[placement].voice|escape:"htmlall"}</td>
</tr>
<tr>
<th>Work Email <small>(Student)</small></th>
<td><a href="mailto:{$placements[placement].email}">
{$placements[placement].email|escape:"htmlall"}</a></td>
</tr>


<!-- Placement Info Ends -->
  </table></td>
  <td><table border="1">{* Company Address *}
<tr>
<td>
{$placements[placement].company.address1|escape:"htmlall"}<br />
{if $placements[placement].company.address2|escape:"htmlall"}
{$placements[placement].company.address2|escape:"htmlall"}<br />{/if}
{if $placements[placement].company.address3|escape:"htmlall"}
{$placements[placement].company.address3|escape:"htmlall"}<br />{/if}
{$placements[placement].company.town|escape:"htmlall"}<br />
{$placements[placement].company.locality|escape:"htmlall"}<br />
{if $placements[placement].company.postcode|escape:"htmlall"}
{$placements[placement].company.postcode|escape:"htmlall"}
<a href="http://maps.google.co.uk/maps?saddr=bt37+0qb&daddr={$placements[placement].company.postcode|escape:"url"}" target="blank">(Google Maps)
</a><br />{/if}
{$placements[placement].company.country|escape:"htmlall"}<br />

{if $placements[placement].company.voice|escape:"htmlall"}
Phone: {$placements[placement].company.voice|escape:"htmlall"}<br />{/if}
{if $placements[placement].company.fax|escape:"htmlall"}
Fax: {$placements[placement].company.fax|escape:"htmlall"}<br />{/if}
{if $placements[placement].company.www|escape:"htmlall"}
<a href="{$placements[placement].company.www}">
{$placements[placement].company.www|escape:"htmlall"}</a><br />{/if}



</td>
</tr>
  </table></td>
</tr>
<tr><th><acronymn title="Member of staff responsible for student in company">
Industrial Supervisor</acronym></th>
<th><acronym title="Member of staff responsible for selecting student">
Company Contact (Hirer)</acronym></th>
</tr>
<tr>
  <td><table border="1">{* Industrial Supervisor *}
{if $placements[placement].supervisor_surname}
{$placements[placement].supervisor_title|escape:"htmlall"}
{$placements[placement].supervisor_firstname|escape:"htmlall"}
{$placements[placement].supervisor_surname|escape:"htmlall"}<br />
{if $placements[placement].supervisor_voice}
Phone: {$placements[placement].supervisor_voice|escape:"htmlall"}<br />
{/if}
{if $placements[placement].supervisor_email}
Email: 
<a href="mailto:{$placements[placement].supervisor_email}">
{$placements[placement].supervisor_email|escape:"htmlall"}
</a><br />
{/if}
{/if}
  </table></td>
  <td><table border="1">{* Company contact *}
{if $placements[placement].contact.surname}
{$placements[placement].contact.title|escape:"htmlall"}
{$placements[placement].contact.firstname|escape:"htmlall"}
{$placements[placement].contact.surname|escape:"htmlall"}<br />
{if $placements[placement].contact.voice}
Phone: {$placements[placement].contact.voice|escape:"htmlall"}<br />
{/if}
{if $placements[placement].contact.email}
Email: 
<a href="mailto:{$placements[placement].contact.email}">
{$placements[placement].contact.email|escape:"htmlall"}
</a><br />
{/if}
{/if}
  </table></td>
</tr>
{if !$smarty.section.placement.last}
<tr><td colspan="2"><br /><br /></td></tr>
{/if}
</table>
{/section}
<br />