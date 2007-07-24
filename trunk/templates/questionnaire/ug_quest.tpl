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

<form action="{$script_path}?questions=ug_quest" method="post">
<input type="hidden" name="mode" value="QuestionnaireSave">

<h2>PDP Questionnaire</h2>

Personal Development Planning (PDP) is a process designed to assist you to get the most from your time at University, and help you to plan and reflect upon the knowledge and skills you are developing.  This process concentrates not only on academic knowledge and ability but will also focus on your career and personal development.


<h3>About You</h3>

<table width="100%">
<tr>
  <th width="40%">How much have you used the PDSystem?</th>
  <th>Never Used</th><th>Logged in a few times</th><th>Some Use</th><th>Quite Frequently</th><th>Use Frequently</th><th></th>
</tr>
<tr>
  <td width="40%"></td>
  <td width="10%"><input type="radio" name="usage" value="1"></td>
  <td width="10%"><input type="radio" name="usage" value="2"></td>
  <td width="10%"><input type="radio" name="usage" value="3"></td>
  <td width="10%"><input type="radio" name="usage" value="4"></td>
  <td width="10%"><input type="radio" name="usage" value="5"></td>
  <td width="10%"></td>
<tr>

</table>

<h3>Usage Questions</h3>

<p>These questions concern how you have used the PDSystem. If you haven't used a feature please indicate that,
otherwise please indicate how useful you have found a given feature.</p>

<table width="100%">
<tr>
  <th width="5%"></th>
  <th width="35%"></th>
  <th colspan="5"><center>1 is Not at all useful, 3 is Quite useful, 5 is Very Useful</center></th>
  <th width="10%"></th>
</tr>
<tr>
  <th width="5%"></th>
  <th width="35%"></th>
  <th>1</th><th>2</th><th>3</th><th>4</th><th>5</th>
  <th width="10%">Didn't Use</th>
</tr>
<tr>
  <th width="5%">1</th>
  <th width="35%">How useful are the <u>messaging features</u> in the PDSystem?<br />
	<small>For example, have you received messages about feedback on your shared portfolios or any other topic
        </small></th>
  <td width="10%"><input type="radio" name="usage_1" value="1"></td>
  <td width="10%"><input type="radio" name="usage_1" value="2"></td>
  <td width="10%"><input type="radio" name="usage_1" value="3"></td>
  <td width="10%"><input type="radio" name="usage_1" value="4"></td>
  <td width="10%"><input type="radio" name="usage_1" value="5"></td>
  <td width="10%"><input type="radio" name="usage_1" value="0" checked /></td>
</tr>
<tr>
  <th width="5%">2</th>
  <th width="35%">How useful are the <u>calendar features</u> in the PDSystem?<br />
	<small>For example, do use the calendar to store important dates. Have you used it to agree meetings
	with members of staff?</small></th>
  <td width="10%"><input type="radio" name="usage_2" value="1"></td>
  <td width="10%"><input type="radio" name="usage_2" value="2"></td>
  <td width="10%"><input type="radio" name="usage_2" value="3"></td>
  <td width="10%"><input type="radio" name="usage_2" value="4"></td>
  <td width="10%"><input type="radio" name="usage_2" value="5"></td>
  <td width="10%"><input type="radio" name="usage_2" value="0" checked /></td>
</tr>
<tr>
  <th width="5%">3</th>
  <th width="35%">How useful is the PDSystem for <u>Recording Achievement</u>?<br />
	<small>For example, do you store information about your qualifications and more 
 	(held under the <u>MyProfile</u> tab)?<small></th>
  <td width="10%"><input type="radio" name="usage_3" value="1"></td>
  <td width="10%"><input type="radio" name="usage_3" value="2"></td>
  <td width="10%"><input type="radio" name="usage_3  value="3"></td>
  <td width="10%"><input type="radio" name="usage_3" value="4"></td>
  <td width="10%"><input type="radio" name="usage_3" value="5"></td>
  <td width="10%"><input type="radio" name="usage_3" value="0" checked /></td>
</tr>
<tr>
  <th width="5%">4</th>
  <th width="35%">How useful are the features that deal with your <u>course/programme</u> (under the
	<u>MyProgramme</u> tab)?<br />
	<small>For example, do you use resources and forms made available by academic staff?</small></th>
  <td width="10%"><input type="radio" name="usage_4" value="1"></td>
  <td width="10%"><input type="radio" name="usage_4" value="2"></td>
  <td width="10%"><input type="radio" name="usage_4" value="3"></td>
  <td width="10%"><input type="radio" name="usage_4" value="4"></td>
  <td width="10%"><input type="radio" name="usage_4" value="5"></td>
  <td width="10%"><input type="radio" name="usage_4" value="0" checked /></td>
</tr>
<tr>
  <th width="5%">5</th>
  <th width="35%">How useful is the <u>Transcipt</u> feature in the PDSystem?<br />
	<small>Held under the <u>MyProgramme</u> tab this shows information about your marks in your various
	modules./small></th>
  <td width="10%"><input type="radio" name="usage_5" value="1"></td>
  <td width="10%"><input type="radio" name="usage_5" value="2"></td>
  <td width="10%"><input type="radio" name="usage_5" value="3"></td>
  <td width="10%"><input type="radio" name="usage_5" value="4"></td>
  <td width="10%"><input type="radio" name="usage_5" value="5"></td>
  <td width="10%"><input type="radio" name="usage_5" value="0" checked /></td>
</tr>
<tr>
  <th width="5%">6</th>
  <th width="35%">How useful is the <u>Skill Assessment</u> in the PDSystem?<br />
	<small>Available under <u>MyReflection</u></th>
  <td width="10%"><input type="radio" name="usage_6" value="1"></td>
  <td width="10%"><input type="radio" name="usage_6" value="2"></td>
  <td width="10%"><input type="radio" name="usage_6" value="3"></td>
  <td width="10%"><input type="radio" name="usage_6" value="4"></td>
  <td width="10%"><input type="radio" name="usage_6" value="5"></td>
  <td width="10%"><input type="radio" name="usage_6" value="0" checked /></td>
</tr>
<tr>
  <th width="5%">7</th>
  <th width="35%">How useful are the <u>Goals</u> and <u>Action Planning</u> features in the PDSystem?<br />
  	<small>Available under <u>MyReflection</u></small></th>
  <td width="10%"><input type="radio" name="usage_7" value="1"></td>
  <td width="10%"><input type="radio" name="usage_7" value="2"></td>
  <td width="10%"><input type="radio" name="usage_7" value="3"></td>
  <td width="10%"><input type="radio" name="usage_7" value="4"></td>
  <td width="10%"><input type="radio" name="usage_7" value="5"></td>
  <td width="10%"><input type="radio" name="usage_7" value="0" checked /></td>
</tr>
<tr>
  <th width="5%">8</th>
  <th width="35%">How useful are the <u>Journal</u> features in the PDSystem?<br />
	<small>Available under <u>MyReflections</u></small></th>
  <td width="10%"><input type="radio" name="usage_8" value="1"></td>
  <td width="10%"><input type="radio" name="usage_8" value="2"></td>
  <td width="10%"><input type="radio" name="usage_8" value="3"></td>
  <td width="10%"><input type="radio" name="usage_8" value="4"></td>
  <td width="10%"><input type="radio" name="usage_8" value="5"></td>
  <td width="10%"><input type="radio" name="usage_8" value="0" checked /></td>
</tr>
<tr>
  <th width="5%">9</th>
  <th width="35%">How useful are the <u>CV</u> features in the PDSystem?<br />
	<small>Found under the <u>MyCV</u> tab.</small></th>
  <td width="10%"><input type="radio" name="usage_9" value="1"></td>
  <td width="10%"><input type="radio" name="usage_9" value="2"></td>
  <td width="10%"><input type="radio" name="usage_9" value="3"></td>
  <td width="10%"><input type="radio" name="usage_9" value="4"></td>
  <td width="10%"><input type="radio" name="usage_9" value="5"></td>
  <td width="10%"><input type="radio" name="usage_9" value="0" checked /></td>
</tr>
<tr>
  <th width="5%">10</th>
  <th width="35%">How useful are the <u>Placement</u> features in the PDSystem?<br />
	<small>Found under the <u>MyPlacement</u> tab.</small></th>
  <td width="10%"><input type="radio" name="usage_10" value="1"></td>
  <td width="10%"><input type="radio" name="usage_10" value="2"></td>
  <td width="10%"><input type="radio" name="usage_10" value="3"></td>
  <td width="10%"><input type="radio" name="usage_10" value="4"></td>
  <td width="10%"><input type="radio" name="usage_10" value="5"></td>
  <td width="10%"><input type="radio" name="usage_10" value="0" checked /></td>
</tr>
<tr>
  <th width="5%">11</th>
  <th width="35%">How useful are the <u>Portfolio</u> features in the PDSystem?<br />
	<small>These are the portfolios you create of information from the PDSystem and that
	you may share. Found under the <u>MyPortfolio</u> tab.</small></th>
  <td width="10%"><input type="radio" name="usage_11" value="1"></td>
  <td width="10%"><input type="radio" name="usage_11" value="2"></td>
  <td width="10%"><input type="radio" name="usage_11" value="3"></td>
  <td width="10%"><input type="radio" name="usage_11" value="4"></td>
  <td width="10%"><input type="radio" name="usage_11" value="5"></td>
  <td width="10%"><input type="radio" name="usage_11" value="0" checked /></td>
</tr>
</table>

<h3>PDP and reflective learning</h3>

Next we make a serious of statements about PDP and how it may have helped your studies. Please indicate
your agreement or otherwise with these statements. If you don't understand the statement please indicate that.

<table width="100%">
<tr>
  <th width="5%"></th>
  <th width="35%"></th>
  <th colspan="5"><center>1 is Strongly disagree, 3 is Neutral, 5 is Strongly agree</center></th>
  <th width="10%"></th>
</tr>
<tr>
  <th width="5%"></th>
  <th width="35%"></th>
  <th>1</th><th>2</th><th>3</th><th>4</th><th>5</th>
  <th width="10%">Don't Understand the statement</th>
</tr>
<tr>
  <th width="5%">1</th>
  <th width="35%">I have a good understanding of what <u>PDP</u> is.</th>
  <td width="10%"><input type="radio" name="pdp_0" value="1"></td>
  <td width="10%"><input type="radio" name="pdp_0" value="2"></td>
  <td width="10%"><input type="radio" name="pdp_0" value="3"></td>
  <td width="10%"><input type="radio" name="pdp_0" value="4"></td>
  <td width="10%"><input type="radio" name="pdp_0" value="5"></td>
  <td width="10%"><input type="radio" name="pdp_0" value="0" checked /></td>
</tr>
<tr>
  <th width="5%">1</th>
  <th width="35%">PDP helps me achieve my maximum potential.</th>
  <td width="10%"><input type="radio" name="pdp_1" value="1"></td>
  <td width="10%"><input type="radio" name="pdp_1" value="2"></td>
  <td width="10%"><input type="radio" name="pdp_1" value="3"></td>
  <td width="10%"><input type="radio" name="pdp_1" value="4"></td>
  <td width="10%"><input type="radio" name="pdp_1" value="5"></td>
  <td width="10%"><input type="radio" name="pdp_1" value="0" checked /></td>
</tr>
<tr>
  <th width="5%">2</th>
  <th width="35%">PDP makes me more employable.</th>
  <td width="10%"><input type="radio" name="pdp_2" value="1"></td>
  <td width="10%"><input type="radio" name="pdp_2" value="2"></td>
  <td width="10%"><input type="radio" name="pdp_2" value="3"></td>
  <td width="10%"><input type="radio" name="pdp_2" value="4"></td>
  <td width="10%"><input type="radio" name="pdp_2" value="5"></td>
  <td width="10%"><input type="radio" name="pdp_2" value="0" checked /></td>
</tr>
<tr>
  <th width="5%">3</th>
  <th width="35%">Reflecting upon my learning helps my formal studies.<br />
	<small>This is looking at skills, setting goals, making action plans, and journals.</small></th>
  <td width="10%"><input type="radio" name="pdp_3" value="1"></td>
  <td width="10%"><input type="radio" name="pdp_3" value="2"></td>
  <td width="10%"><input type="radio" name="pdp_3" value="3"></td>
  <td width="10%"><input type="radio" name="pdp_3" value="4"></td>
  <td width="10%"><input type="radio" name="pdp_3" value="5"></td>
  <td width="10%"><input type="radio" name="pdp_3" value="0" checked /></td>
</tr>
<tr>
  <th width="5%">4</th>
  <th width="35%">Members of staff have used the PDSystem to help me.<br />
	<small>For example, in studies advice, or programme resources</small></th>
  <td width="10%"><input type="radio" name="pdp_4" value="1"></td>
  <td width="10%"><input type="radio" name="pdp_4" value="2"></td>
  <td width="10%"><input type="radio" name="pdp_4" value="3"></td>
  <td width="10%"><input type="radio" name="pdp_4" value="4"></td>
  <td width="10%"><input type="radio" name="pdp_4" value="5"></td>
  <td width="10%"><input type="radio" name="pdp_4" value="0" checked /></td>
</tr>
<tr>
  <th width="5%">5</th>
  <th width="35%">Most of my PDP and reflective learning activity uses the PDSystem.<br />
	<small>For example, do you undertake these sorts of activities in traditional paper exercises too?</small></th>
  <td width="10%"><input type="radio" name="pdp_5" value="1"></td>
  <td width="10%"><input type="radio" name="pdp_5" value="2"></td>
  <td width="10%"><input type="radio" name="pdp_5" value="3"></td>
  <td width="10%"><input type="radio" name="pdp_5" value="4"></td>
  <td width="10%"><input type="radio" name="pdp_5" value="5"></td>
  <td width="10%"><input type="radio" name="pdp_5" value="0" checked /></td>
</tr>
<tr>
  <th width="5%">6</th>
  <th width="35%">My adviser of studies and I use the PDSystem to facilitate our meetings</th>
  <td width="10%"><input type="radio" name="pdp_6" value="1"></td>
  <td width="10%"><input type="radio" name="pdp_6" value="2"></td>
  <td width="10%"><input type="radio" name="pdp_6" value="3"></td>
  <td width="10%"><input type="radio" name="pdp_6" value="4"></td>
  <td width="10%"><input type="radio" name="pdp_6" value="5"></td>
  <td width="10%"><input type="radio" name="pdp_6" value="0" checked /></td>
</tr>
</table>

<h3>Best and Worst Things</h3>

<table width="100%">
<tr>
  <th width="5%"></th>
  <th width="35%">What is the <u>best</u> thing about the <u>PDSystem</u> in your opinion?</th>
  <td>{$Quest_pds_best->WriteVariable()}</td>
</tr>
<tr>
  <th width="5%"></th>
  <th width="35%">What is the <u>worst</u> thing about the <u>PDSystem</u> in your opinion?</th>
  <td>{$Quest_pds_worst->WriteVariable()}</td>
</tr>
<tr>
  <th width="5%"></th>
  <th width="35%">What is the <u>best</u> thing about <u>PDP</u> in your opinion?</th>
  <td>{$Quest_pdp_best->WriteVariable()}</td>
</tr>
<tr>
  <th width="5%"></th>
  <th width="35%">What is the <u>worst</u> thing about <u>PDP</u> in your opinion?</th>
  <td>{$Quest_pdp_worst->WriteVariable()}</td>
</tr>
</table>


<input type="submit" value="Submit Answers">

</form>
<p>
Thank you for your time in completing this form.<br />
Colin Turner
</p>
<div id="footer">
</body>
</html>
