<h2 align="center">Vacancy Details<br>
{$vacancy.description|escape:"htmlall"}</h2>
<table>
<tr>
  <th align="left">Company</th>
  <td>{$vacancy.company_name|escape:"htmlall"}</td>
</tr>
<tr>
  <th align="left">Location</th>
  <td>
{$vacancy.town|escape:"htmlall"},
{$vacancy.locality|escape:"htmlall"},
({$vacancy.country|escape:"htmlall"})</td>
</tr>
<tr>
  <th align="left">Status</th>
  <td class="status-{$vacancy.status}">{$vacancy.status|escape:"htmlall"}
{if $vacancy.status == "open"}
(Applications still allowed - see bottom of page)
{/if}
{if $vacancy.status == "closed"}
(Applications no longer allowed)
{/if}
{if $vacancy.status == "special"}
(Applications not allowed through this website)
{/if}
</td>
</tr>
{if $vacancy.salary}
<tr>
  <th align="left">Salary</th>
  <td>{$vacancy.salary|escape:"htmlall"}</td>
</tr>
{/if}
{if $vacancy.closedate}
<tr>
  <th align="left">Application close date</th>
  <td>{$vacancy.closedate|escape:"htmlall"}</td>
</tr>
{/if}
{if $vacancy.jobstart}
<tr>
  <th align="left">Start date</th>
  <td>{$vacancy.jobstart|escape:"htmlall"}</td>
</tr>
{/if}
{if $vacancy.jobend}
<tr>
  <th align="left">End date</th>
  <td>{$vacancy.jobend|escape:"htmlall"}</td>
</tr>
{/if}
{if $vacancy.www}
<tr>
  <th align="left">Website</th>
  <td><a href="http://{$vacancy.www}">{$vacancy.www|escape:"htmlall"}</a></td>
</tr>
{/if}
<tr>
<th align="left" colspan="2"><br>Brief</th>
</tr>
<tr>
<td colspan="2" class="company">
{$vacancy.brief}
<br>
</td>
</tr>
<tr><td colspan="2" align="center">
<strong>The University of Ulster is not responsible for content supplied by external companies.</strong>
</td>
</tr>
<tr>
<th valign="top" align="left">Activity Types</th>
<td>
{section name=vacancy_activity loop=$vacancy_activities}
{$vacancy_activities[vacancy_activity].name|escape:"htmlall"}<br>
{/section}
</td>
</tr>
</table>