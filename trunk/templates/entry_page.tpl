<table width="800" align="center">
<tr><td>
<h1>OPUS</h1>

{if $session.LoginFromPDSFail}
<strong>OPUS has no record for you. If you are supposed to be using
OPUS to obtain placement, please inform your placement team.</strong>
{include file="other_student_resources.tpl"}
<h2>More Information about the OPUS</h2>
<p><acronym title="Online Placement university System or OPUS for short">OPUS</acronym> is an interactive tool to help both students, staff from companies and academic staff deal easily with the process of placement. The site deals with the process of advertising vacancies, applications, company contacts, staff visits and assessment.<br/>
{else}
<p><acronym title="Online Placement university System or OPUS for short">OPUS</acronym> is an interactive tool to help both students, staff from companies and academic staff deal easily with the process of placement. The site deals with the process of advertising vacancies, applications, company contacts, staff visits and assessment.<br/>
It is closely connected to the <acronym title="Personal Development System or PDS for short">PDSystem</acronym>, which can be accessed 
<a href="http://pds.ulster.ac.uk">here</a>. 
{if $LoginFromPDSFail}
{/if}
</p>

<h2>More Information about OPUS</h2>
</td>
<td>
{include file="login_form.tpl"}
{/if}
</td>
</tr>
</table>
