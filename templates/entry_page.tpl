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


<h2>Version 3.0.0</h2>
<p>Version 3.0.0 of the OPUS (previously PMS) was released on Wednesday, 27th September 2006. This release sees some major changes
in the system, in particular:
<ul>
<li>Channels<br />
Channels are a new feature allowing course teams to more effectively communicate, via on-line help and resources with the specific
students, staff members and companies they need to do business with.</li>
<li>PDSystem CVs<br />
OPUS allows more control over which groups of students can use which CV templates from the PDSystem. Custom CVs will also soon
be allowed in a release likely to follow in the next fortnight.</li>
<li>Student Records Integration<br />
It is now possible to obtain some information directly from student records, for example, when importing students into OPUS, and courses. More
integration will follow</li>
<li>Clean up of company listings<br />
For companies with many vacancies the listings have been changed slightly to greatly improve readability.</li>
<li>Login with WebCT credentials<br />
Students can now login directly on this page using their single sign on details.</li>
</ul>
</p>

<h2>More Information about OPUS</h2>
</td>
<td>
{include file="login_form.tpl"}
{/if}
</td>
</tr>
</table>
