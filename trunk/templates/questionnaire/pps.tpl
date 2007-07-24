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

<form action="{$script_path}?questions=pps" method="post">
<input type="hidden" name="mode" value="QuestionnaireSave">

<h2>Professional Portfolio System Questionnaire</h2>

<p>The Professional Portfolio System has been designed to support all University of Ulster Staff in their Continual Professional Development. The system records the CPD activity, personal reflection and evaluation and forward planning. This 1st Version of the system is currently undergoing a pilot. Please give detailed feedback concerning your views on its effectiveness.<br />
<b>Responses should be submitted here before the 31st March 2007.</b></p>

<!-- Interface starts -->

<h3>Interface</h3>

<table width="100%">
<tr>
  <th width="5%"></th>
  <th width="45%"></th>
  <th colspan="5"><center>1 is Not at all easy, 3 is Quite easy, 5 is very easy</center></th>
</tr>
<tr>
  <th width="5%"></th>
  <th width="45%"></th>
  <th>1</th><th>2</th><th>3</th><th>4</th><th>5</th>
</tr>
<tr>
  <th width="5%">1</th>
  <th width="45%">On first engaging with the Professional Portfolio System, how easy was the
  system to understand?</th>
  <td width="10%"><input type="radio" name="interface_1" value="1"></td>
  <td width="10%"><input type="radio" name="interface_1" value="2"></td>
  <td width="10%"><input type="radio" name="interface_1" value="3" checked></td>
  <td width="10%"><input type="radio" name="interface_1" value="4"></td>
  <td width="10%"><input type="radio" name="interface_1" value="5"></td>
</tr>
<tr>
  <th width="5%">2</th>
  <th width="45%">How easy was it to learn how to use the PPS?</th>
  <td width="10%"><input type="radio" name="interface_2" value="1"></td>
  <td width="10%"><input type="radio" name="interface_2" value="2"></td>
  <td width="10%"><input type="radio" name="interface_2" value="3" checked></td>
  <td width="10%"><input type="radio" name="interface_2" value="4"></td>
  <td width="10%"><input type="radio" name="interface_2" value="5"></td>
</tr>
<tr>
  <th width="5%">3</th>
  <th width="45%">How easy was it to navigate through the system?</th>
  <td width="10%"><input type="radio" name="interface_3" value="1"></td>
  <td width="10%"><input type="radio" name="interface_3" value="2"></td>
  <td width="10%"><input type="radio" name="interface_3" value="3" checked></td>
  <td width="10%"><input type="radio" name="interface_3" value="4"></td>
  <td width="10%"><input type="radio" name="interface_3" value="5"></td>
</tr>
<tr>
  <th width="5%">4</th>
  <th width="45%">How easy were the <u>User Guidelines</u> to use?</th>
  <td width="10%"><input type="radio" name="interface_4" value="1"></td>
  <td width="10%"><input type="radio" name="interface_4" value="2"></td>
  <td width="10%"><input type="radio" name="interface_4" value="3" checked></td>
  <td width="10%"><input type="radio" name="interface_4" value="4"></td>
  <td width="10%"><input type="radio" name="interface_4" value="5"></td>
</tr>
</table>

<!-- Design and layout -->

<table width="100%">
<tr>
  <th colspan="3">Design and Layout</th>
</tr>
<tr>
  <td width="25%"></td>
  <td width="25%" class="company">
    Please consider the following ideas for your comments:
    <ul>
      <li>colour</li>
      <li>layout</li>
      <li>font</li>
      <li>tabs</li>
      <li>text boxes</li>
      <li>menu</li>
    </ul>
  </td>
  <td width="50%">
    <em>Comment on these issues (shown to the left):</em><br />
    <textarea name="designlayout_0" rows="6" cols="60" wrap="VIRTUAL"></textarea><br />
    <em>Comment on effectiveness.</em><br />
    <textarea name="designlayout_1" rows="6" cols="60" wrap="VIRTUAL"></textarea><br />
    <em>Comment on possible enhancements.</em><br />
    <textarea name="designlayout_2" rows="6" cols="60" wrap="VIRTUAL"></textarea><br />  
  </td>
</tr>
</table>

<!-- Suitability starts -->

<h3>Suitability</h3>

<table width="100%">
<tr>
  <th width="5%"></th>
  <th width="35%"></th>
  <th colspan="6"><center>1 is Not at all useful, 3 is Quite useful, 5 is very useful</center></th>
</tr>
<tr>
  <th width="5%"></th>
  <th width="35%"></th>
  <th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>Not Applicable</th>
</tr>
<tr>
  <th width="5%">1</th>
  <th width="35%">How useful is PPS for recording all aspects of your CPD activities?</th>
  <td width="10%"><input type="radio" name="suitability_1" value="1"></td>
  <td width="10%"><input type="radio" name="suitability_1" value="2"></td>
  <td width="10%"><input type="radio" name="suitability_1" value="3"></td>
  <td width="10%"><input type="radio" name="suitability_1" value="4"></td>
  <td width="10%"><input type="radio" name="suitability_1" value="5"></td>
  <td width="10%"><input type="radio" name="suitability_1" value="0" checked></td>
</tr>
<tr>
  <th width="5%">2</th>
  <th width="35%">How useful is PPS for CV preparation?</th>
  <td width="10%"><input type="radio" name="suitability_2" value="1"></td>
  <td width="10%"><input type="radio" name="suitability_2" value="2"></td>
  <td width="10%"><input type="radio" name="suitability_2" value="3"></td>
  <td width="10%"><input type="radio" name="suitability_2" value="4"></td>
  <td width="10%"><input type="radio" name="suitability_2" value="5"></td>
  <td width="10%"><input type="radio" name="suitability_2" value="0" checked></td>
</tr>
<tr>
  <th width="5%">3</th>
  <th width="35%">How useful is PPS for staff appraisal preparation?</th>
  <td width="10%"><input type="radio" name="suitability_3" value="1"></td>
  <td width="10%"><input type="radio" name="suitability_3" value="2"></td>
  <td width="10%"><input type="radio" name="suitability_3" value="3"></td>
  <td width="10%"><input type="radio" name="suitability_3" value="4"></td>
  <td width="10%"><input type="radio" name="suitability_3" value="5"></td>
  <td width="10%"><input type="radio" name="suitability_3" value="0" checked></td>
</tr>
<tr>
  <th width="5%">4</th>
  <th width="35%">How useful is PPS for staff probation preparation?</th>
  <td width="10%"><input type="radio" name="suitability_4" value="1"></td>
  <td width="10%"><input type="radio" name="suitability_4" value="2"></td>
  <td width="10%"><input type="radio" name="suitability_4" value="3"></td>
  <td width="10%"><input type="radio" name="suitability_4" value="4"></td>
  <td width="10%"><input type="radio" name="suitability_4" value="5"></td>
  <td width="10%"><input type="radio" name="suitability_4" value="0" checked></td>
</tr>
<tr>
  <th width="5%">5</th>
  <th width="35%">How useful is PPS for staff promotion preparation?</th>
  <td width="10%"><input type="radio" name="suitability_5" value="1"></td>
  <td width="10%"><input type="radio" name="suitability_5" value="2"></td>
  <td width="10%"><input type="radio" name="suitability_5" value="3"></td>
  <td width="10%"><input type="radio" name="suitability_5" value="4"></td>
  <td width="10%"><input type="radio" name="suitability_5" value="5"></td>
  <td width="10%"><input type="radio" name="suitability_5" value="0" checked></td>
</tr>
</table>

<!-- Fit For Purpose -->

<table width="100%">
<tr>
  <th colspan="3">Fitness for Purpose</th>
</tr>
<tr>
  <td width="25%"></td>
  <td width="25%" class="company">
    The system is designed to help record and maintain information for the following purposes:
    <ul>
      <li>CPD</li>
      <li>appraisal</li>
      <li>promotion</li>
      <li>probation</li>
      <li>external / professional CPD</li>
    </ul>
  </td>
  <td width="50%">
    <em>Comment on these issues (shown to the left):</em><br />
    <textarea name="fitpurpose_0" rows="6" cols="60" wrap="VIRTUAL"></textarea><br />    
    <em>Comment on effectiveness.</em><br />
    <textarea name="fitpurpose_1" rows="6" cols="60" wrap="VIRTUAL"></textarea><br />
    <em>Comment on possible enhancements.</em><br />
    <textarea name="fitpurpose_2" rows="6" cols="60" wrap="VIRTUAL"></textarea><br />  
  </td>
</tr>
</table>

<!-- Appropriateness -->

<h3>System Navigation</h3>

<table width="100%">
<tr>
  <th width="5%"></th>
  <th width="45%"></th>
  <th colspan="5"><center>1 is Not at all appropriate, 3 is Quite appropriate, 5 is very appropriate</center></th>
</tr>
<tr>
  <th width="5%"></th>
  <th width="45%"></th>
  <th>1</th><th>2</th><th>3</th><th>4</th><th>5</th>
</tr>
<tr>
  <th width="5%">1</th>
  <th width="45%">How appropriate was the <em>Home</em> section to your CPD requirements?</th>
  <td width="10%"><input type="radio" name="appropriate_1" value="1"></td>
  <td width="10%"><input type="radio" name="appropriate_1" value="2"></td>
  <td width="10%"><input type="radio" name="appropriate_1" value="3" checked></td>
  <td width="10%"><input type="radio" name="appropriate_1" value="4"></td>
  <td width="10%"><input type="radio" name="appropriate_1" value="5"></td>
</tr>
<tr>
  <th width="5%">2</th>
  <th width="45%">How appropriate was the <em>Professional Development</em> section to your CPD requirements?</th>
  <td width="10%"><input type="radio" name="appropriate_2" value="1"></td>
  <td width="10%"><input type="radio" name="appropriate_2" value="2"></td>
  <td width="10%"><input type="radio" name="appropriate_2" value="3" checked></td>
  <td width="10%"><input type="radio" name="appropriate_2" value="4"></td>
  <td width="10%"><input type="radio" name="appropriate_2" value="5"></td>
</tr>
<tr>
  <th width="5%">3</th>
  <th width="45%">How appropriate was the <em>Teaching and Learning</em> section to your CPD requirements?</th>
  <td width="10%"><input type="radio" name="appropriate_3" value="1"></td>
  <td width="10%"><input type="radio" name="appropriate_3" value="2"></td>
  <td width="10%"><input type="radio" name="appropriate_3" value="3" checked></td>
  <td width="10%"><input type="radio" name="appropriate_3" value="4"></td>
  <td width="10%"><input type="radio" name="appropriate_3" value="5"></td>
</tr>
<tr>
  <th width="5%">4</th>
  <th width="45%">How appropriate was the <em>Research and Innovation</em> section to your CPD requirements?</th>
  <td width="10%"><input type="radio" name="appropriate_4" value="1"></td>
  <td width="10%"><input type="radio" name="appropriate_4" value="2"></td>
  <td width="10%"><input type="radio" name="appropriate_4" value="3" checked></td>
  <td width="10%"><input type="radio" name="appropriate_4" value="4"></td>
  <td width="10%"><input type="radio" name="appropriate_4" value="5"></td>
</tr>
<tr>
  <th width="5%">5</th>
  <th width="45%">How appropriate was the <em>Academic Enterprise</em> section to your CPD requirements?</th>
  <td width="10%"><input type="radio" name="appropriate_5" value="1"></td>
  <td width="10%"><input type="radio" name="appropriate_5" value="2"></td>
  <td width="10%"><input type="radio" name="appropriate_5" value="3" checked></td>
  <td width="10%"><input type="radio" name="appropriate_5" value="4"></td>
  <td width="10%"><input type="radio" name="appropriate_5" value="5"></td>
</tr>
<tr>
  <th width="5%">6</th>
  <th width="45%">How appropriate was the <em>Plan and Reflect</em> section to your CPD requirements?</th>
  <td width="10%"><input type="radio" name="appropriate_6" value="1"></td>
  <td width="10%"><input type="radio" name="appropriate_6" value="2"></td>
  <td width="10%"><input type="radio" name="appropriate_6" value="3" checked></td>
  <td width="10%"><input type="radio" name="appropriate_6" value="4"></td>
  <td width="10%"><input type="radio" name="appropriate_6" value="5"></td>
</tr>
<tr>
  <th width="5%">7</th>
  <th width="45%">How appropriate was the <em>Document Ouput</em> section to your CPD requirements?</th>
  <td width="10%"><input type="radio" name="appropriate_7" value="1"></td>
  <td width="10%"><input type="radio" name="appropriate_7" value="2"></td>
  <td width="10%"><input type="radio" name="appropriate_7" value="3" checked></td>
  <td width="10%"><input type="radio" name="appropriate_7" value="4"></td>
  <td width="10%"><input type="radio" name="appropriate_7" value="5"></td>
</tr>
<tr>
  <th width="5%">8</th>
  <th width="45%">How appropriate was the <em>HE Academy Help</em> section to your CPD requirements?</th>
  <td width="10%"><input type="radio" name="appropriate_8" value="1"></td>
  <td width="10%"><input type="radio" name="appropriate_8" value="2"></td>
  <td width="10%"><input type="radio" name="appropriate_8" value="3" checked></td>
  <td width="10%"><input type="radio" name="appropriate_8" value="4"></td>
  <td width="10%"><input type="radio" name="appropriate_8" value="5"></td>
</tr>
<tr>
  <th width="5%"></th>
  <th width="45%"></th>
  <th colspan="5"><center>1 is Not at all helpful, 3 is Quite helpful, 5 is very helpful</center></th>
</tr>
<tr>
  <th width="5%">9</th>
  <th width="45%">How helpful was the <em>Help</em> section to your CPD requirements?</th>
  <td width="10%"><input type="radio" name="appropriate_9" value="1"></td>
  <td width="10%"><input type="radio" name="appropriate_9" value="2"></td>
  <td width="10%"><input type="radio" name="appropriate_9" value="3" checked></td>
  <td width="10%"><input type="radio" name="appropriate_9" value="4"></td>
  <td width="10%"><input type="radio" name="appropriate_9" value="5"></td>
</tr>
</table>

<!-- Personal Data -->

<table width="100%">
<tr>
  <th colspan="3">Personal Data</th>
</tr>
<tr>
  <td width="25%"></td>
  <td width="25%" class="company">
    The system accesses and presents the following information from core university databases:
    <ul>
      <li>Staff personal details (HR)</li>
      <li>Internal courses attended (Staff Development Department Database)</li>
      <li>External courses attended (Finance Prior Approval Database)</li>
    </ul>
    In addition the system provides tabs to record under the following headings
    <ul>
      <li>Journal</li>
      <li>Personal Details</li>
      <li>Qualifications</li>
      <li>External Activities</li>
    </ul>
  </td>
  <td width="50%">
    <em>Comment on these issues (shown to the left):</em><br />
    <textarea name="personaldata_0" rows="6" cols="60" wrap="VIRTUAL"></textarea><br />
    <em>Comment on effectiveness.</em><br />
    <textarea name="personaldata_1" rows="6" cols="60" wrap="VIRTUAL"></textarea><br />
    <em>Comment on possible enhancements.</em><br />
    <textarea name="personaldata_2" rows="6" cols="60" wrap="VIRTUAL"></textarea><br />  
  </td>
</tr>
</table>

<!-- Development and Planning -->

<table width="100%">
<tr>
  <th colspan="3">Development and Planning</th>
</tr>
<tr>
  <td width="25%"></td>
  <td width="25%" class="company">
    <ul>
      The system provides a framework for the following aspects of the development planning process in terms of Self Evaluation i.e.:
      <li>Continuing Professional Development Action Planning</li>
      <li>Reflection on activities undertaken</li>
      <li>Personal Development</li>
      <li>CV Management</li>
    </ul>
  </td>
  <td width="50%">
    <em>Comment on these issues (shown to the left):</em><br />
    <textarea name="devplan_0" rows="6" cols="60" wrap="VIRTUAL"></textarea><br />
    <em>Comment on effectiveness.</em><br />
    <textarea name="devplan_1" rows="6" cols="60" wrap="VIRTUAL"></textarea><br />
    <em>Comment on possible enhancements.</em><br />
    <textarea name="devplan_2" rows="6" cols="60" wrap="VIRTUAL"></textarea><br />  
  </td>
</tr>
</table>

<!-- Functionality -->

<table width="100%">
<tr>
  <th colspan="3">Functionality</th>
</tr>
<tr>
  <td width="25%"></td>
  <td width="25%" class="company">
    The system provides the following functionality
    <ul>
      <li>System help information</li>
      <li>CPD help (information on the Higher Education Academy Professional Standards area of prefessional activity and core knowledge)</li>
      <li>News items</li>
      <li>Advice resources (including bookmarks)</li>
      <li>Ability to transfer information out of the system</li>
      <li>Ability to manage the look and feel of an individual's screen</li>
      <li>Security</li>
    </ul>
  </td>
  <td width="50%">
    <em>Comment on these issues (shown to the left):</em><br />
    <textarea name="functionality_0" rows="6" cols="60" wrap="VIRTUAL"></textarea><br />
    <em>Comment on effectiveness.</em><br />
    <textarea name="functionality_1" rows="6" cols="60" wrap="VIRTUAL"></textarea><br />
    <em>Comment on possible enhancements.</em><br />
    <textarea name="functionality_2" rows="6" cols="60" wrap="VIRTUAL"></textarea><br />  
  </td>
</tr>
</table>

<!-- Information Export -->

<table width="100%">
<tr>
  <th colspan="3">Information Export</th>
</tr>
<tr>
  <td width="25%"></td>
  <td width="25%" class="company">
      The system provides a mechanism for staff to export the information stored in the CPD system for several reasons, for example to provide selected data for accreditation use with a professional body, export selected data when a staff member moves to another institution or to provide information for use as input to an appraisal.
  </td>
  <td width="50%">
    <em>Comment on these issues (shown to the left):</em><br />
    <textarea name="information_export_0" rows="6" cols="60" wrap="VIRTUAL"></textarea><br />
    <em>Comment on effectiveness.</em><br />
    <textarea name="information_export_1" rows="6" cols="60" wrap="VIRTUAL"></textarea><br />
    <em>Comment on possible enhancements.</em><br />
    <textarea name="information_export_2" rows="6" cols="60" wrap="VIRTUAL"></textarea><br />  
  </td>
</tr>
</table>



<!-- Summary -->

<table align="center" width="100%">
<tr>
  <th width="20%"></th><th>Summary Overview</th><th width="20%"></th>
</tr>
<tr>
  <td></td>
  <td>
    <em>Comment on the overall effectiveness of the PPS.</em><br />
    <textarea name="summary_0" rows="6" cols="80" wrap="VIRTUAL"></textarea><br />
    <em>Highlight the main strengths of the PPS.</em><br />
    <textarea name="summary_1" rows="6" cols="80" wrap="VIRTUAL"></textarea><br />
    <em>Highlight the main weaknesses of the PPS.</em><br />
    <textarea name="summary_2" rows="6" cols="80" wrap="VIRTUAL"></textarea><br />  
  </td>
  <td></td>
</tr>
</table>

<p align="center">Please give your name<br />
{$Quest_name->FlagError()}
<input type="text" name="name" size="40"></p>

<p align="center">Finally, would you prefer to use your own system or method for recording this information?
{$Quest_own_method->FlagError()}
<input type="radio" name="own_method" value="Yes"> Yes 
<input type="radio" name="own_method" value="No"> No
</p>

<input type="submit" value="Submit Answers">

</form>
<p>
Thank you for your time in completing this form.<br />
Tim McLernon &amp; Barbara Dass
</p>
<div id="footer">
</body>
</html>
