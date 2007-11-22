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

<form action="{$script_path}" method="post">
<input type="hidden" name="questions" value="pg_quest">
<input type="hidden" name="mode" value="QuestionnaireSave">

<h2>PDSystem for Post-Graduate students</h2>
<h3>Evaluation form</h3>

<table width="100%">
<tr>
  <th width="5%">1</th>
  <th width="35%">Retention of current MyPDS Tab</th>
  <td>{$Quest_1->WriteVariable()}</td>
</tr>
<tr>
  <th>2</th>
  <th>Retention of current MyProfile Tab</th>
  <td>{$Quest_2->WriteVariable()}</td>
</tr>
<tr>
  <th>3</th>
  <th>Retention of current MyProgress Tab</th>
  <td>{$Quest_3->WriteVariable()}</td>
</tr>
<tr>
  <th>4</th>
  <th>Retention of current MyReflection Tab</th>
  <td>{$Quest_4->WriteVariable()}</td>
</tr>
<tr>
  <th>5</th>
  <th>Retention of current MyCV Tab</th>
  <td>{$Quest_5->WriteVariable()}</td>
</tr>
<tr>
  <th>6</th>
  <th>Retention of current MyPlacement Tab</th>
  <td>{$Quest_6->WriteVariable()}</td>
</tr>
<tr>
  <th>7</th>
  <th>Retention of current MyPortfolio Tab</th>
  <td>{$Quest_7->WriteVariable()}</td>
</tr>
<tr>
  <th>8</th>
  <th>Ability to press a button on any page that seems difficult to navigate so that a log of where navigational issues  occur are noted and can be investigated</th>
  <td>{$Quest_8->WriteVariable()}</td>
</tr>
<tr>
  <th>9</th>
  <th>Ability to set preferences of the page that will be viewed on entry each time for example, MyPDS, Last used or a specific Tab</th>
  <td>{$Quest_9->WriteVariable()}</td>
</tr>
<tr>
  <th>10</th>
  <th>Preference to allow any HTTP link selected in the PDSystem to either open in a separate window for each link selected or to drill-down using the single window view</th>
  <td>{$Quest_10->WriteVariable()}</td>
</tr>
<tr>
  <th>11</th>
  <th>One easy way to access systems such as the Library, RefWorks, etc.</th>
  <td>{$Quest_11->WriteVariable()}</td>
</tr>
<tr>
  <th>12</th>
  <th>Discussion groups online (extracurricular)</th>
  <td>{$Quest_12->WriteVariable()}</td>
</tr>
<tr>
  <th>13</th>
  <th>Microsoft Messenger type chat or Voice over IP to allow access to other researchers if they are currently logged on</th>
  <td>{$Quest_13->WriteVariable()}</td>
</tr>
<tr>
  <th>14</th>
  <th>Pre-population of the Calendar with events that relate to academic progress at your Course and School level</th>
  <td>{$Quest_14->WriteVariable()}</td>
</tr>
<tr>
  <th>15</th>
  <th>Public storage area in the PDSystem that allows easy sharing to all in the university via the Intranet and also to the world if selected.  (Clear prompt to note that the sharing will be visible to many others)</th>
  <td>{$Quest_15->WriteVariable()}</td>
</tr>
<tr>
  <th>16</th>
  <th>Able to change the email address to control the place where alerts are delivered</th>
  <td>{$Quest_16->WriteVariable()}</td>
</tr>
<tr>
  <th>17</th>
  <th>Ability to access the email address book in a similar way to NetMail for sharing portfolios</th>
  <td>{$Quest_17->WriteVariable()}</td>
</tr>
<tr>
  <th>18</th>
  <th>SMS alerting messages as reminders to events and to flag that messages have been left on the PDSystem</th>
  <td>{$Quest_18->WriteVariable()}</td>
</tr>
<tr>
  <th>19</th>
  <th>A postgraduate skills audit</th>
  <td>{$Quest_19->WriteVariable()}</td>
</tr>
<tr>
  <th>20</th>
  <th>Ability for students to request meetings (e.g., studies advice)</th>
  <td>{$Quest_20->WriteVariable()}</td>
</tr>
<tr>
  <th>21</th>
  <th>Email to recipients via the PDSystem to include the subject of the request being made and include any associated attachments.  (This allows both parties to collect the attachments from other computers besides their regular desktop machine)</th>
  <td>{$Quest_21->WriteVariable()}</td>
</tr>
<tr>
  <th>22</th>
  <th>On acceptance of any meeting (e.g., studies advice) the reply should automatically connect to the PDSystem to set the event (all events can be downloaded to Outlook for integration with personal calendars)</th>
  <td>{$Quest_22->WriteVariable()}</td>
</tr>
<tr>
  <th>23</th>
  <th>Use of a Targets Tab for a list of goals or action plans to be achieved in the next week, month, etc.  Should include key events for Course and School as well as tutor and student created</th>
  <td>{$Quest_23->WriteVariable()}</td>
</tr>
<tr>
  <th>24</th>
  <th>Possibility of the action plan linking forward to the appropriate meeting and automatically appearing in the Targets tab</th>
  <td>{$Quest_24->WriteVariable()}</td>
</tr>
<tr>
  <th>25</th>
  <th>Electronic meeting form protected so that it can not be altered after signing off by both parties.  Only available to the Student and Studies Advisor (or staff that would normally see paper copy)</th>
  <td>{$Quest_25->WriteVariable()}</td>
</tr>
<tr>
  <th>26</th>
  <th>Inclusion of good practice through examples of other CVs</th>
  <td>{$Quest_26->WriteVariable()}</td>
</tr>
<tr>
  <th>27</th>
  <th>Logging of any official Staff Development training</th>
  <td>{$Quest_27->WriteVariable()}</td>
</tr>
<tr>
  <th>28</th>
  <th>Personal account should stay available after graduation (at least one year) so that it can be updated and accessed for CV, possible jobs, etc.</th>
  <td>{$Quest_28->WriteVariable()}</td>
</tr>
<tr>
  <th>29</th>
  <th>Ability to change contact information such as mobile number to keep up to date after graduating</th>
  <td>{$Quest_29->WriteVariable()}</td>
</tr>
<tr>
  <th>30</th>
  <th>Any other features not listed in the requirements document or above that you would like to see included.</th>
  <td>{$Quest_30->WriteVariable()}</td>
</tr>


<tr>
  <th>31</th>
  <th>Would you consider being involved in the pilot group to try the revised PDSystem?</th>
  <td>Yes {$Quest_31a->WriteVariable()} <br />If Yes: Name: {$Quest_31b->WriteVariable()} Email: {$Quest_31c->WriteVariable()}</td>
</tr>
</table>

<input type="submit" value="Submit Answers">

</form>
<p></p>
<div id="footer">
</body>
</html>