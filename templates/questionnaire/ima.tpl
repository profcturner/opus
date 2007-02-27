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
<input type="hidden" name="questions" value="ima">
<input type="hidden" name="mode" value="QuestionnaireSave">

<h2>Institute of Mathematics and its Applications (Irish Branch)</h2>
<h3>Activities Questionnaire</h3>

<p>In recent years the activities organised by the committee of the Irish Branch of the IMA
have been poorly attended. This is your branch, and we would like to support the activities
you want to see. Please take the time to fill in the questionnaire below to help us provide
activities for you.</p>

<table width="100%">
<tr>
  <th width="5%">1</th>
  <th width="35%">I would attend events held <emph>outside</emph> working hours (9am - 5pm)</th>
  <td>{$Quest_1->WriteVariable()}</td>
</tr>
<tr>
  <th>2</th>
  <th>I would attend events held <emph>during</emph> working hours (9am - 5pm)</th>
  <td>{$Quest_2->WriteVariable()}</td>
</tr>
<tr>
  <th>3</th>
  <th>I would like to see more light talks based on recreational mathematics</th>
  <td>{$Quest_3->WriteVariable()}</td>
</tr>
<tr>
  <th>4</th>
  <th>I would like to see more talks aimed at issues in the post-primary level curricula</th>
  <td>{$Quest_4->WriteVariable()}</td>
</tr>
<tr>
  <th>5</th>
  <th>I would like to see more serious research seminar based talks</th>
  <td>{$Quest_5->WriteVariable()}</td>
</tr>
<tr>
  <th>6</th>
  <th>I would attend more talks if they were more local to me</th>
  <td>{$Quest_6->WriteVariable()}</td>
</tr>
<tr>
  <th>6a</th>
  <th>If yes to Q6, what is your locality?</th>
  <td>{$Quest_6a->WriteVariable()}</td>
</tr>
<tr>
  <th>7</th>
  <th>Can you give other information on events you would <emph>like</emph> to see</th>
  <td>{$Quest_7->WriteVariable()}</td>
</tr>
<tr>
  <th>8</th>
  <th>Can you give other information on events you would <emph>not like</emph> to see</th>
  <td>{$Quest_8->WriteVariable()}</td>
</tr>
<tr>
  <th>9</th>
  <th>Roughly how many IMA events have you attended in the last three years?</th>
  <td>{$Quest_9->WriteVariable()}</td>
</tr>
<tr>
  <th>10</th>
  <th>What do you think we could do to attract new members?</th>
  <td>{$Quest_10->WriteVariable()}</td>
</tr>
<tr>
  <th>11</th>
  <th>Any other comments?</th>
  <td>{$Quest_11->WriteVariable()}</td>
</tr>
<tr>
  <th>12</th>
  <th>Optionally will you give us your contact details?</th>
  <td>Name: {$Quest_12a->WriteVariable()} Email: {$Quest_12b->WriteVariable()}</td>
</tr>
</table>

<input type="submit" value="Submit Answers">

<p>Thank you for taking the time to complete this questionnaire.</p>

<p>If you are using a paper copy of this form, could you please return it to

<pre>
Mr Seamus Bellew, DKIT, Dundalk
</pre>

Thank you
</p>

</form>
<p></p>
<div id="footer">
</body>
</html>