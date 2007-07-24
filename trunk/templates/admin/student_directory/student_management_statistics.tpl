{* Smarty *}

<h2 align="center">Student Management Statistics</h2>

<p align="center">These menu items make it possible to extract some summary statistics from
the system. These are complex queries and take a little time to execute.</p>

<h3 align="center">Student Broadsheet</h3>

<p align="center">
The student broadsheet is a list of all students within a given
assessment regime. Assessment and company contact information
can be exported to aid with mail merge and examination activity.
</p>

<h3 align="center">Course Breakdown Report</h3>

<p align="center">
The course breakdown report attempts to give some of the statistics
used for end of year analysis of placement.
</p>

<form method="post" action="{$conf.scripts.admin.studentdir}">
<table align="center">
<tr>
  <th colspan="2">Report Type</th>
</tr>
<tr>
  <td><input type="radio" name="mode" value="StudentBroadSheet" CHECKED> Student Broadsheet</td>
  <td><input type="radio" name="format" value="HTML"> HTML Format
<input type="radio" name="format" value="TSV" CHECKED> TSV Format
<input type="radio" name="format" value="TSVCSV"> TSV, with CSV extension<br />
<input type="checkbox" name="extras[]" value="disability" > Add Disability Info<br />
<input type="checkbox" name="extras[]" value="company" CHECKED> Add Company Info<br />
<input type="checkbox" name="extras[]" value="vacancy" CHECKED> Add Vacancy Info<br />
<input type="checkbox" name="extras[]" value="supervisor" CHECKED> Add Supervisor Info<br />
<input type="checkbox" name="extras[]" value="assessment" CHECKED> Add Assessment Info<br >
</td>
</tr>
<tr>
  <td><input type="radio" name="mode" value="StudentAssessmentDetails"> Detailed assessment report</td>
  <td><input type="radio" name="format" value="HTML"> HTML Format
<input type="radio" name="format" value="TSV" CHECKED> TSV Format
<input type="radio" name="format" value="TSVCSV"> TSV, with CSV extension
</tr>
<tr>
  <td><input type="radio" name="mode" value="StudentReportCourses"> Course Breakdown Report</td><td></td>
</tr>
<tr>
  <th colspan="2">Assessment Groups</th>
</tr>
{section name=ass_group loop=$assessment_groups}
<tr>
  <td colspan="2"><input type="radio" name="group_id" value="{$assessment_groups[ass_group].group_id}">
{$assessment_groups[ass_group].name|escape:"htmlall"}</td>
</tr>
{/section}
<tr>
  <th>Year Seeking Placement</th>
  <td><input type="text" size="5" name="year" value="{$year}"></td>
</tr>
<tr>
  <td colspan="2"><input type="submit" value="Generate Report"></td>
</tr>
</table>
</form>

