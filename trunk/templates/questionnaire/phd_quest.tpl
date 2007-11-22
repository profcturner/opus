<html>
<head>
<link rel="stylesheet" type="text/css" HREF="http://pms.ulster.ac.uk/css/blue.css">
<link rel="stylesheet" type="text/css" HREF="http://pms.ulster.ac.uk/css/placement.css">
<body>

{if $errors}
<h2 class="error">Errors Occurred</h2>
<p>Some of your fields were either compulsory and not filled in,
or contain invalid data. You will see <span class="error">**</span>
beside any fields that need attention</p>.
{/if}

<form action="{$script_path}?questions=phd_quest" method="post">
<input type="hidden" name="mode" value="QuestionnaireSave">

<h2>PDSystem for researchers</h2>

<p>
The University of Ulster is committed to creating opportunities to record evidence of Personal Development for all levels of courses.  This document is aimed at all parties that relate to research.  Confidentially within the PDSytem is paramount and only people that should have access to the information will be allowed to access it.
</p>

<p>
Each supervisor, research student and Graduate School has their own process of managing the academic progress and therefore a tool is required that can be used in different ways to support all parties.
</p>

<p>
Can you please select, using the scale indicated, the importance that you would place on each of the statements listed in the creation of a PDSystem for Researchers.
</p>

<h3>About You</h3>

<table width="100%">
<tr>
  <th width="50%">Select the role you are most likely to adopt for use with the proposed PDSystem for Researchers</th>
  <th width="10%">Student</th>
  <th width="10%">Supervisor</th>
  <th width="10%">Head of Graduate School</th>
  <th width="10%">Secretarial staff in Graduate School</th>
  <th width="10%">Research Office Staff</th>
</tr>
<tr>
  <td width="50%"></td>
  <td width="10%"><input type="radio" name="category" value="student"></td>
  <td width="10%"><input type="radio" name="category" value="supervisor"></td>
  <td width="10%"><input type="radio" name="category" value="hos"></td>
  <td width="10%"><input type="radio" name="category" value="secretary"></td>
  <td width="10%"><input type="radio" name="category" value="research_office"></td>
</tr>
<tr>
  <th width="50%">How much have you used the PDSystem?</th>
  <th>Never Used</th><th>Logged in a few times</th><th>Some Use</th><th>Quite Frequently</th><th>Use Frequently</th>
</tr>
<tr>
  <td width="50%"></td>
  <td width="10%"><input type="radio" name="usage" value="1"></td>
  <td width="10%"><input type="radio" name="usage" value="2"></td>
  <td width="10%"><input type="radio" name="usage" value="3"></td>
  <td width="10%"><input type="radio" name="usage" value="4"></td>
  <td width="10%"><input type="radio" name="usage" value="5"></td>
<tr>
</table>

<h3>General Questions</h3>

<table width="100%">
<tr>
  <th width="5%"></th>
  <th width="35%"></th>
  <th colspan="5"><center>1 is Not Required, 3 is Diserable, 5 is Essential</center></th>
  <th width="10%"></th>
</tr>
<tr>
  <th width="5%"></th>
  <th width="35%"></th>
  <th>1</th><th>2</th><th>3</th><th>4</th><th>5</th>
  <th width="10%">Don't understand the requirement</th>
</tr>
<tr>
  <th width="5%">1</th>
  <th width="35%">Creation of a bespoke tool for the recording of Personal Development Planning for Researchers</th>
  <td width="10%"><input type="radio" name="general_1" value="1"></td>
  <td width="10%"><input type="radio" name="general_1" value="2"></td>
  <td width="10%"><input type="radio" name="general_1" value="3"></td>
  <td width="10%"><input type="radio" name="general_1" value="4"></td>
  <td width="10%"><input type="radio" name="general_1" value="5"></td>
  <td width="10%"><input type="radio" name="general_1" value="0" checked /></td>
</tr>
<tr>
  <th width="5%">2</th>
  <th width="35%">Integration of any existing user information to the Researcher version of the system</th>
  <td width="10%"><input type="radio" name="general_2" value="1"></td>
  <td width="10%"><input type="radio" name="general_2" value="2"></td>
  <td width="10%"><input type="radio" name="general_2" value="3"></td>
  <td width="10%"><input type="radio" name="general_2" value="4"></td>
  <td width="10%"><input type="radio" name="general_2" value="5"></td>
  <td width="10%"><input type="radio" name="general_2" value="0" checked /></td>
</tr>
<tr>
  <th width="5%">3</th>
  <th width="35%">Personal account should stay available after graduation so that it can be updated and accessed for CV, possible jobs and keeping up with current research in area</th>
  <td width="10%"><input type="radio" name="general_3" value="1"></td>
  <td width="10%"><input type="radio" name="general_3" value="2"></td>
  <td width="10%"><input type="radio" name="general_3" value="3"></td>
  <td width="10%"><input type="radio" name="general_3" value="4"></td>
  <td width="10%"><input type="radio" name="general_3" value="5"></td>
  <td width="10%"><input type="radio" name="general_3" value="0" checked /></td>
</tr>
<tr>
  <th width="5%">4</th>
  <th width="35%">Account on the PDSystem should remain active at least one year after leaving UU</th>
  <td width="10%"><input type="radio" name="general_4" value="1"></td>
  <td width="10%"><input type="radio" name="general_4" value="2"></td>
  <td width="10%"><input type="radio" name="general_4" value="3"></td>
  <td width="10%"><input type="radio" name="general_4" value="4"></td>
  <td width="10%"><input type="radio" name="general_4" value="5"></td>
  <td width="10%"><input type="radio" name="general_4" value="0" checked /></td>
</tr>
<tr>
  <th width="5%">5</th>
  <th width="35%">Have general wording convention that relates to Research and not specifically PhD to cover MPhil and MRes</th>
  <td width="10%"><input type="radio" name="general_5" value="1"></td>
  <td width="10%"><input type="radio" name="general_5" value="2"></td>
  <td width="10%"><input type="radio" name="general_5" value="3"></td>
  <td width="10%"><input type="radio" name="general_5" value="4"></td>
  <td width="10%"><input type="radio" name="general_5" value="5"></td>
  <td width="10%"><input type="radio" name="general_5" value="0" checked /></td>
</tr>
</table>

<h3>Supervisor and Student meeting</h3>

<table width="100%">
<tr>
  <th width="5%"></th>
  <th width="35%"></th>
  <th colspan="5"><center>1 is Not Required, 3 is Diserable, 5 is Essential</center></th>
  <th width="10%"></th>
</tr>
<tr>
  <th width="5%"></th>
  <th width="35%"></th>
  <th>1</th><th>2</th><th>3</th><th>4</th><th>5</th>
  <th width="10%">Don't understand the requirement</th>
</tr>
<tr>
  <th width="5%">1</th>
  <th width="35%">Undertaking the Postgraduate Skills Audit at the start of the Programme and sharing it with your supervisor</th>
  <td width="10%"><input type="radio" name="meeting_1" value="1"></td>
  <td width="10%"><input type="radio" name="meeting_1" value="2"></td>
  <td width="10%"><input type="radio" name="meeting_1" value="3"></td>
  <td width="10%"><input type="radio" name="meeting_1" value="4"></td>
  <td width="10%"><input type="radio" name="meeting_1" value="5"></td>
  <td width="10%"><input type="radio" name="meeting_1" value="0" checked /></td>
</tr>
<tr>
  <th width="5%">2</th>
  <th width="35%">Pre-population of the Calendar with events that relate to academic progress at School and Research Office level</th>
  <td width="10%"><input type="radio" name="meeting_2" value="1"></td>
  <td width="10%"><input type="radio" name="meeting_2" value="2"></td>
  <td width="10%"><input type="radio" name="meeting_2" value="3"></td>
  <td width="10%"><input type="radio" name="meeting_2" value="4"></td>
  <td width="10%"><input type="radio" name="meeting_2" value="5"></td>
  <td width="10%"><input type="radio" name="meeting_2" value="0" checked /></td>
</tr>
<tr>
  <th width="5%">3</th>
  <th width="35%">Use of a Targets tab for the list of goals to be achieved in the next week, month, or long term period.  Should be pre-populated to include the key events that relate to tasks that need to be completed for the Research Office and Graduate School as well as supervisor and student created
  </th>
  <td width="10%"><input type="radio" name="meeting_3" value="1"></td>
  <td width="10%"><input type="radio" name="meeting_3" value="2"></td>
  <td width="10%"><input type="radio" name="meeting_3" value="3"></td>
  <td width="10%"><input type="radio" name="meeting_3" value="4"></td>
  <td width="10%"><input type="radio" name="meeting_3" value="5"></td>
  <td width="10%"><input type="radio" name="meeting_3" value="0" checked /></td>
</tr>
<tr>
  <th width="5%">4</th>
  <th width="35%">Ability of Students to instigate meetings with others which is currently only available to staff</th>
  <td width="10%"><input type="radio" name="meeting_4" value="1"></td>
  <td width="10%"><input type="radio" name="meeting_4" value="2"></td>
  <td width="10%"><input type="radio" name="meeting_4" value="3"></td>
  <td width="10%"><input type="radio" name="meeting_4" value="4"></td>
  <td width="10%"><input type="radio" name="meeting_4" value="5"></td>
  <td width="10%"><input type="radio" name="meeting_4" value="0" checked /></td>
</tr>
<tr>
  <th width="5%">5</th>
  <th width="35%">Email to recipients via the PDSystem to include the subject of the request being made and include any associated attachments.  (This allows both parties to collect the attachments from other computers besides their regular desktop machine)
</th>
  <td width="10%"><input type="radio" name="meeting_5" value="1"></td>
  <td width="10%"><input type="radio" name="meeting_5" value="2"></td>
  <td width="10%"><input type="radio" name="meeting_5" value="3"></td>
  <td width="10%"><input type="radio" name="meeting_5" value="4"></td>
  <td width="10%"><input type="radio" name="meeting_5" value="5"></td>
  <td width="10%"><input type="radio" name="meeting_5" value="0" checked /></td>
</tr>
<tr>
  <th width="5%">6</th>
  <th width="35%">On acceptance of any meeting the reply should automatically connect to the PDSystem to set the event (all events can be downloaded to Outlook for integration with personal calendars)</th>
  <td width="10%"><input type="radio" name="meeting_6" value="1"></td>
  <td width="10%"><input type="radio" name="meeting_6" value="2"></td>
  <td width="10%"><input type="radio" name="meeting_6" value="3"></td>
  <td width="10%"><input type="radio" name="meeting_6" value="4"></td>
  <td width="10%"><input type="radio" name="meeting_6" value="5"></td>
  <td width="10%"><input type="radio" name="meeting_6" value="0" checked /></td>

</tr>
<tr>
  <th width="5%">7</th>
  <th width="35%">Replacement of the Triplicate Record of Meeting form by a pre-populated form with Issues discussed and Suggested Further Work to be electronically signed off by both parties.  (Allow for either student or supervisor to initially enter the information and further work before agreeing)</th>
  <td width="10%"><input type="radio" name="meeting_7" value="1"></td>
  <td width="10%"><input type="radio" name="meeting_7" value="2"></td>
  <td width="10%"><input type="radio" name="meeting_7" value="3"></td>
  <td width="10%"><input type="radio" name="meeting_7" value="4"></td>
  <td width="10%"><input type="radio" name="meeting_7" value="5"></td>
  <td width="10%"><input type="radio" name="meeting_7" value="0" checked /></td>

</tr>
<tr>
  <th width="5%">8</th>
  <th width="35%">Possibility of the Further Work linking forward to the appropriate meeting and automatically appearing on the Issues  discussed and in the Targets tab
</th>
  <td width="10%"><input type="radio" name="meeting_8" value="1"></td>
  <td width="10%"><input type="radio" name="meeting_8" value="2"></td>
  <td width="10%"><input type="radio" name="meeting_8" value="3"></td>
  <td width="10%"><input type="radio" name="meeting_8" value="4"></td>
  <td width="10%"><input type="radio" name="meeting_8" value="5"></td>
  <td width="10%"><input type="radio" name="meeting_8" value="0" checked /></td>
 
</tr>
<tr>
  <th width="5%">9</th>
  <th width="35%">Electronic meeting form protected so that it can not be altered after signing off by both parties.  Only available to the Student, Supervisor and School staff that would normally see the paper copy
</th>
  <td width="10%"><input type="radio" name="meeting_9" value="1"></td>
  <td width="10%"><input type="radio" name="meeting_9" value="2"></td>
  <td width="10%"><input type="radio" name="meeting_9" value="3"></td>
  <td width="10%"><input type="radio" name="meeting_9" value="4"></td>
  <td width="10%"><input type="radio" name="meeting_9" value="5"></td>
  <td width="10%"><input type="radio" name="meeting_9" value="0" checked /></td>
 
</tr>
<tr>
  <th width="5%">10</th>
  <th width="35%">Confidential area in the Triplicate form for supervisor Aide memoir
</th>
  <td width="10%"><input type="radio" name="meeting_10" value="1"></td>
  <td width="10%"><input type="radio" name="meeting_10" value="2"></td>
  <td width="10%"><input type="radio" name="meeting_10" value="3"></td>
  <td width="10%"><input type="radio" name="meeting_10" value="4"></td>
  <td width="10%"><input type="radio" name="meeting_10" value="5"></td>
  <td width="10%"><input type="radio" name="meeting_10" value="0" checked /></td>
 
</tr>
<tr>
  <th width="5%">11</th>
  <th width="35%">Recognition that a 'Learning Agreement' should be created at the start of
the PhD and used throughout the work undertaken.  The 'Learning Agreement'
would be a brief document listing goals and plans to achieve the goal that
have been agreed by both parties.  It should be periodically reviewed, at
least once a year, in light of the meetings between supervisor and student
and shared between student, supervisor and Head of Graduate School.</th>
  <td width="10%"><input type="radio" name="meeting_11" value="1"></td>
  <td width="10%"><input type="radio" name="meeting_11" value="2"></td>
  <td width="10%"><input type="radio" name="meeting_11" value="3"></td>
  <td width="10%"><input type="radio" name="meeting_11" value="4"></td>
  <td width="10%"><input type="radio" name="meeting_11" value="5"></td>
  <td width="10%"><input type="radio" name="meeting_11" value="0" checked /></td>
</tr>
</table>


<h3>Research Studies</h3>

<table width="100%">
<tr>
  <th width="5%"></th>
  <th width="35%"></th>
  <th colspan="5"><center>1 is Not Required, 3 is Diserable, 5 is Essential</center></th>
  <th width="10%"></th>
</tr>
<tr>
  <th width="5%"></th>
  <th width="35%"></th>
  <th>1</th><th>2</th><th>3</th><th>4</th><th>5</th>
  <th width="10%">Don't understand the requirement</th>
</tr>
<tr>
  <th width="5%">1</th>
  <th width="35%">Access to the Research Training Credits (RTC) record via the PDSystem</th>
  <td width="10%"><input type="radio" name="research_1" value="1"></td>
  <td width="10%"><input type="radio" name="research_1" value="2"></td>
  <td width="10%"><input type="radio" name="research_1" value="3"></td>
  <td width="10%"><input type="radio" name="research_1" value="4"></td>
  <td width="10%"><input type="radio" name="research_1" value="5"></td>
  <td width="10%"><input type="radio" name="research_1" value="0" checked /></td>
</tr>
<tr>
  <th width="5%">2</th>
  <th width="35%">Any records of attendance of conferences can be shared to the PDSystem (possibly from a database)</th>
  <td width="10%"><input type="radio" name="research_2" value="1"></td>
  <td width="10%"><input type="radio" name="research_2" value="2"></td>
  <td width="10%"><input type="radio" name="research_2" value="3"></td>
  <td width="10%"><input type="radio" name="research_2" value="4"></td>
  <td width="10%"><input type="radio" name="research_2" value="5"></td>
  <td width="10%"><input type="radio" name="research_2" value="0" checked /></td>
</tr>
<tr>
  <th width="5%">3</th>
  <th width="35%">See seminars and conferences attended as part of the RTC record</th>
  <td width="10%"><input type="radio" name="research_3" value="1"></td>
  <td width="10%"><input type="radio" name="research_3" value="2"></td>
  <td width="10%"><input type="radio" name="research_3" value="3"></td>
  <td width="10%"><input type="radio" name="research_3" value="4"></td>
  <td width="10%"><input type="radio" name="research_3" value="5"></td>
  <td width="10%"><input type="radio" name="research_3" value="0" checked /></td>

</tr>
<tr>
  <th width="5%">4</th>
  <th width="35%">Logging of any official Staff Development training so that a researcher can carry the credit through to their RTC and CV</th>
  <td width="10%"><input type="radio" name="research_4" value="1"></td>
  <td width="10%"><input type="radio" name="research_4" value="2"></td>
  <td width="10%"><input type="radio" name="research_4" value="3"></td>
  <td width="10%"><input type="radio" name="research_4" value="4"></td>
  <td width="10%"><input type="radio" name="research_4" value="5"></td>
  <td width="10%"><input type="radio" name="research_4" value="0" checked /></td>

</tr>
<tr>
  <th width="5%">5</th>
  <th width="35%">Ability to add comments or evidence to RTC records for either personal or shared use</th>
  <td width="10%"><input type="radio" name="research_5" value="1"></td>
  <td width="10%"><input type="radio" name="research_5" value="2"></td>
  <td width="10%"><input type="radio" name="research_5" value="3"></td>
  <td width="10%"><input type="radio" name="research_5" value="4"></td>
  <td width="10%"><input type="radio" name="research_5" value="5"></td>
  <td width="10%"><input type="radio" name="research_5" value="0" checked /></td>

</tr>
<tr>
  <th width="5%">6</th>
  <th width="35%">HTTP links to details of available Research Training not already undertaken</th>
  <td width="10%"><input type="radio" name="research_6" value="1"></td>
  <td width="10%"><input type="radio" name="research_6" value="2"></td>
  <td width="10%"><input type="radio" name="research_6" value="3"></td>
  <td width="10%"><input type="radio" name="research_6" value="4"></td>
  <td width="10%"><input type="radio" name="research_6" value="5"></td>
  <td width="10%"><input type="radio" name="research_6" value="0" checked /></td>

</tr>
<tr>
  <th width="5%">7</th>
  <th width="35%">Ability of Graduate HoS to use student entered evidence, that has been shared to HoS for exemption of RTC credit value, and recorded in PDSystem</th>
  <td width="10%"><input type="radio" name="research_7" value="1"></td>
  <td width="10%"><input type="radio" name="research_7" value="2"></td>
  <td width="10%"><input type="radio" name="research_7" value="3"></td>
  <td width="10%"><input type="radio" name="research_7" value="4"></td>
  <td width="10%"><input type="radio" name="research_7" value="5"></td>
  <td width="10%"><input type="radio" name="research_7" value="0" checked /></td>
</tr>
<tr>
  <th width="5%">8</th>
  <th width="35%">Retention of full online record of 100 day and transfer events.  Student able to submit the document for review.  The documents can be managed by the secretary for the School in terms of sending it to reviewers and setting up the meeting.  The resulting documentation to be available confidentially. (Could include automatic plagiarism checking.  Once the documents are submitted they can not be changed by the student)</th>
  <td width="10%"><input type="radio" name="research_8" value="1"></td>
  <td width="10%"><input type="radio" name="research_8" value="2"></td>
  <td width="10%"><input type="radio" name="research_8" value="3"></td>
  <td width="10%"><input type="radio" name="research_8" value="4"></td>
  <td width="10%"><input type="radio" name="research_8" value="5"></td>
  <td width="10%"><input type="radio" name="research_8" value="0" checked /></td>
</tr>
<tr>
  <th width="5%">9</th>
  <th width="35%">Discussion groups (forums) to help with Networking outside of the topic being researched to offer encouragement between researchers</th>
  <td width="10%"><input type="radio" name="research_9" value="1"></td>
  <td width="10%"><input type="radio" name="research_9" value="2"></td>
  <td width="10%"><input type="radio" name="research_9" value="3"></td>
  <td width="10%"><input type="radio" name="research_9" value="4"></td>
  <td width="10%"><input type="radio" name="research_9" value="5"></td>
  <td width="10%"><input type="radio" name="research_9" value="0" checked /></td>
</tr>
<tr>
  <th width="5%">10</th>
  <th width="35%">Microsoft Messenger type chat or Voice over IP to allow access to other researchers if they are currently logged on</th>
  <td width="10%"><input type="radio" name="research_10" value="1"></td>
  <td width="10%"><input type="radio" name="research_10" value="2"></td>
  <td width="10%"><input type="radio" name="research_10" value="3"></td>
  <td width="10%"><input type="radio" name="research_10" value="4"></td>
  <td width="10%"><input type="radio" name="research_10" value="5"></td>
  <td width="10%"><input type="radio" name="research_10" value="0" checked /></td>
</tr>
<tr>
  <th width="5%">11</th>
  <th width="35%">Ability to press a button on any page that seems difficult to navigate so that a log of where navigational issues  occur are noted and can be investigated</th>
  <td width="10%"><input type="radio" name="research_11" value="1"></td>
  <td width="10%"><input type="radio" name="research_11" value="2"></td>
  <td width="10%"><input type="radio" name="research_11" value="3"></td>
  <td width="10%"><input type="radio" name="research_11" value="4"></td>
  <td width="10%"><input type="radio" name="research_11" value="5"></td>
  <td width="10%"><input type="radio" name="research_11" value="0" checked /></td>
</tr>
</table>

<h3>Navigation</h3>

<table width="100%">
<tr>
  <th width="5%"></th>
  <th width="35%"></th>
  <th colspan="5"><center>1 is Not Required, 3 is Diserable, 5 is Essential</center></th>
  <th width="10%"></th>
</tr>
<tr>
  <th width="5%"></th>
  <th width="35%"></th>
  <th>1</th><th>2</th><th>3</th><th>4</th><th>5</th>
  <th width="10%">Don't understand the requirement</th>
</tr>
<tr>
  <th width="5%">1</th>
  <th width="35%">HTTP link to RefWorks</th>
  <td width="10%"><input type="radio" name="navigation_1" value="1"></td>
  <td width="10%"><input type="radio" name="navigation_1" value="2"></td>
  <td width="10%"><input type="radio" name="navigation_1" value="3"></td>
  <td width="10%"><input type="radio" name="navigation_1" value="4"></td>
  <td width="10%"><input type="radio" name="navigation_1" value="5"></td>
  <td width="10%"><input type="radio" name="navigation_1" value="0" checked /></td>
</tr>
<tr>
  <th width="5%">2</th>
  <th width="35%">HTTP link to the Library systems</th>
  <td width="10%"><input type="radio" name="navigation_2" value="1"></td>
  <td width="10%"><input type="radio" name="navigation_2" value="2"></td>
  <td width="10%"><input type="radio" name="navigation_2" value="3"></td>
  <td width="10%"><input type="radio" name="navigation_2" value="4"></td>
  <td width="10%"><input type="radio" name="navigation_2" value="5"></td>
  <td width="10%"><input type="radio" name="navigation_2" value="0" checked /></td>
</tr>
<tr>
  <th width="5%">3</th>
  <th width="35%">Ability to access the email address book in a similar way to NetMail for sharing portfolios</th>
  <td width="10%"><input type="radio" name="navigation_3" value="1"></td>
  <td width="10%"><input type="radio" name="navigation_3" value="2"></td>
  <td width="10%"><input type="radio" name="navigation_3" value="3"></td>
  <td width="10%"><input type="radio" name="navigation_3" value="4"></td>
  <td width="10%"><input type="radio" name="navigation_3" value="5"></td>
  <td width="10%"><input type="radio" name="navigation_3" value="0" checked /></td>
</tr>
<tr>
  <th width="5%">4</th>
  <th width="35%">Public storage area in the PDSystem that allows easy sharing to all in the university via the Intranet and also to the world if selected.  (Clear prompt to note that the sharing will be visible to many others)</th>
  <td width="10%"><input type="radio" name="navigation_4" value="1"></td>
  <td width="10%"><input type="radio" name="navigation_4" value="2"></td>
  <td width="10%"><input type="radio" name="navigation_4" value="3"></td>
  <td width="10%"><input type="radio" name="navigation_4" value="4"></td>
  <td width="10%"><input type="radio" name="navigation_4" value="5"></td>
  <td width="10%"><input type="radio" name="navigation_4" value="0" checked /></td>
</tr>
<tr>
  <th width="5%">5</th>
  <th width="35%">Ability to use SMS alerting messages as reminders to events and to flag that messages have been left on the PDSystem</th>
  <td width="10%"><input type="radio" name="navigation_5" value="1"></td>
  <td width="10%"><input type="radio" name="navigation_5" value="2"></td>
  <td width="10%"><input type="radio" name="navigation_5" value="3"></td>
  <td width="10%"><input type="radio" name="navigation_5" value="4"></td>
  <td width="10%"><input type="radio" name="navigation_5" value="5"></td>
  <td width="10%"><input type="radio" name="navigation_5" value="0" checked /></td>
</tr>
<tr>
  <th width="5%">6</th>
  <th width="35%">Able to change the email address to control the place where alerts are delivered</th>
  <td width="10%"><input type="radio" name="navigation_6" value="1"></td>
  <td width="10%"><input type="radio" name="navigation_6" value="2"></td>
  <td width="10%"><input type="radio" name="navigation_6" value="3"></td>
  <td width="10%"><input type="radio" name="navigation_6" value="4"></td>
  <td width="10%"><input type="radio" name="navigation_6" value="5"></td>
  <td width="10%"><input type="radio" name="navigation_6" value="0" checked /></td>
</tr>
<tr>
  <th width="5%">7</th>
  <th width="35%">Ability to change contact information such as mobile number to keep up to date after graduating</th>
  <td width="10%"><input type="radio" name="navigation_7" value="1"></td>
  <td width="10%"><input type="radio" name="navigation_7" value="2"></td>
  <td width="10%"><input type="radio" name="navigation_7" value="3"></td>
  <td width="10%"><input type="radio" name="navigation_7" value="4"></td>
  <td width="10%"><input type="radio" name="navigation_7" value="5"></td>
  <td width="10%"><input type="radio" name="navigation_7" value="0" checked /></td>
</tr>
<tr>
  <th width="5%">8</th>
  <th width="35%">Preference to allow any HTTP link selected in the PDSystem to either open in a separate window for each link selected or to drill-down using the single window view</th>
  <td width="10%"><input type="radio" name="navigation_8" value="1"></td>
  <td width="10%"><input type="radio" name="navigation_8" value="2"></td>
  <td width="10%"><input type="radio" name="navigation_8" value="3"></td>
  <td width="10%"><input type="radio" name="navigation_8" value="4"></td>
  <td width="10%"><input type="radio" name="navigation_8" value="5"></td>
  <td width="10%"><input type="radio" name="navigation_8" value="0" checked /></td>

</tr>
<tr>
  <th width="5%">9</th>
  <th width="35%">HTTP links to all relevant forms to do with reporting and progress for use by students, supervisors and Graduate School staff</th>
  <td width="10%"><input type="radio" name="navigation_9" value="1"></td>
  <td width="10%"><input type="radio" name="navigation_9" value="2"></td>
  <td width="10%"><input type="radio" name="navigation_9" value="3"></td>
  <td width="10%"><input type="radio" name="navigation_9" value="4"></td>
  <td width="10%"><input type="radio" name="navigation_9" value="5"></td>
  <td width="10%"><input type="radio" name="navigation_9" value="0" checked /></td>

</tr>
<tr>
  <th width="5%">10</th>
  <th width="35%">HTTP links to examples of completed forms</th>
  <td width="10%"><input type="radio" name="navigation_10" value="1"></td>
  <td width="10%"><input type="radio" name="navigation_10" value="2"></td>
  <td width="10%"><input type="radio" name="navigation_10" value="3"></td>
  <td width="10%"><input type="radio" name="navigation_10" value="4"></td>
  <td width="10%"><input type="radio" name="navigation_10" value="5"></td>
  <td width="10%"><input type="radio" name="navigation_10" value="0" checked /></td>

</tr>
<tr>
  <th width="5%">11</th>
  <th width="35%">CV tab, more suited to PhD needs. Templates to be made available</th>
  <td width="10%"><input type="radio" name="navigation_11" value="1"></td>
  <td width="10%"><input type="radio" name="navigation_11" value="2"></td>
  <td width="10%"><input type="radio" name="navigation_11" value="3"></td>
  <td width="10%"><input type="radio" name="navigation_11" value="4"></td>
  <td width="10%"><input type="radio" name="navigation_11" value="5"></td>
  <td width="10%"><input type="radio" name="navigation_11" value="0" checked /></td>
</tr>
<tr>
  <th width="5%">11a</th>
  <th width="35%">CV tab, more suited to PhD needs. Please specify types (e.g. Full UU Academic)</th>
  <td colspan="6">
      {$Quest_navigation_11a->WriteVariable()}
  </td>
</tr>
<tr>
  <th width="5%">12</th>
  <th width="35%">Inclusion of good practice through examples of other CVs</th>
  <td width="10%"><input type="radio" name="navigation_12" value="1"></td>
  <td width="10%"><input type="radio" name="navigation_12" value="2"></td>
  <td width="10%"><input type="radio" name="navigation_12" value="3"></td>
  <td width="10%"><input type="radio" name="navigation_12" value="4"></td>
  <td width="10%"><input type="radio" name="navigation_12" value="5"></td>
  <td width="10%"><input type="radio" name="navigation_12" value="0" checked /></td>
</tr>
<tr>
  <th width="5%">13</th>
  <th width="35%">Ability to create a PDF version of a document for archiving on own machine</th>
  <td width="10%"><input type="radio" name="navigation_13" value="1"></td>
  <td width="10%"><input type="radio" name="navigation_13" value="2"></td>
  <td width="10%"><input type="radio" name="navigation_13" value="3"></td>
  <td width="10%"><input type="radio" name="navigation_13" value="4"></td>
  <td width="10%"><input type="radio" name="navigation_13" value="5"></td>
  <td width="10%"><input type="radio" name="navigation_13" value="0" checked /></td>
</tr>
<tr>
  <th width="5%">14</th>
  <th width="35%">Other (Please specify)</th>
  <td colspan="6">{$Quest_navigation_14->WriteVariable()}</td>
</tr>
</table>

<input type="submit" value="Submit Answers">

</form>
<p>
Thank you for your time in completing this form.<br />
Peter Nicholl
</p>
<div id="footer">
</body>
</html>
