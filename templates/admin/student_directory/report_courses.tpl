<h1 align="center">Report by Course</h1>
<h2 align="center">Assessment group "{$groupname|escape:"htmlall"}"</h2>
<h3 align="center">Students seeking placement in academic year ({$year} - {$year+1})</h3>
<table border="1">
<tr>
  <th>Code</th>
  <th>Name</th>
  <th>Required</th>
  <th>Placed</th>
  <th>Left</th>
  <th>Exempt?</th>
  <th>Exempt</th>
  <th>NoInfo</th>
  <th>Suspended</th>
  <th>FinalYear</th>
  <th>Countries</th>
</tr>

{section name=course loop=$courses}
<tr>
  <td>{$courses[course].course_code|escape:"htmlall"}</td>
  <td>{$courses[course].course_name|escape:"htmlall"}</td>
  <td>{$courses[course].status.Required}</td>
  <td>{$courses[course].status.Placed}</td>
  <td>{$courses[course].status.LeftCourse}</td>
  <td>{$courses[course].status.ExemptApplied}</td>
  <td>{$courses[course].status.ExemptGiven}</td>
  <td>{$courses[course].status.NoInfo}</td>
  <td>{$courses[course].status.Suspended}</td>
  <td>{$courses[course].status.ToFinalYear}</td>
  <td>
  {foreach key=key item=item  from=$courses[course].countries}
    {$key} {$item}<br>
  {/foreach}
  </td>
</tr>

{/section}
</table>
