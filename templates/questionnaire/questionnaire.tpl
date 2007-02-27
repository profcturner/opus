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
<input type="hidden" name="mode" value="QuestionnaireSave">

<h2>Graduate School Training Programme: PDSystem for PhD students</h2>
<h3>Evaluation form</h3>

<table width="100%">
<tr>
  <th width="5%">1</th>
  <th width="40%">Ability to see RTCredits</th>
  <td>{$Quest_B1->WriteVariable()}</td>
</tr>
<tr>
  <th>2</th>
  <th>Ability to see RTCredits and retain your own comments</th>
  <td>{$Quest_B2->WriteVariable()}</td>
</tr>
<tr>
  <th>3</th>
  <th>Ability to see Conference and Seminar attendances and record your own thoughts</th>
  <td>{$Quest_B3->WriteVariable()}</td>
</tr>
<tr>
  <th>4</th>
  <th>Retention of current MyPDS Tab</th>
  <td>{$Quest_B4->WriteVariable()}</td>
</tr>
<tr>
  <th>5</th>
  <th>Retention of current MyProfile Tab</th>
  <td>{$Quest_B5->WriteVariable()}</td>
</tr>
<tr>
  <th>6</th>
  <th>Retention of current MyProgress Tab</th>
  <td>{$Quest_B6->WriteVariable()}</td>
</tr>
<tr>
  <th>7</th>
  <th>Retention of current MyReflection Tab</th>
  <td>{$Quest_B7->WriteVariable()}</td>
</tr>
<tr>
  <th>8</th>
  <th>Retention of current MyCV Tab</th>
  <td>{$Quest_B8->WriteVariable()}</td>
</tr>
<tr>
  <th>9</th>
  <th>Retention of current MyPlacement Tab</th>
  <td>{$Quest_B9->WriteVariable()}</td>
</tr>
<tr>
  <th>10</th>
  <th>Retention of current MyPortfolio Tab</th>
  <td>{$Quest_B10->WriteVariable()}</td>
</tr>
<tr>
  <th>11</th>
  <th>Access template file from the Research Office and submit your documents electronically to your supervisor or Graduate School or Research Office</th>
  <td>{$Quest_B11->WriteVariable()}</td>
</tr>
<tr>
  <th>12</th>
  <th>One easy way to access systems such as the library, RefWorks etc.</th>
  <td>{$Quest_B12->WriteVariable()}</td>
</tr>
<tr>
  <th>13</th>
  <th>
  Discussion groups online</th>
  <td>{$Quest_B13->WriteVariable()}</td>
</tr>
<tr>
  <th>14</th>
  <th>Pre-population of the Calendar with 'events' that relate to academic progress at your project level and School and Research Office level</th>
  <td>{$Quest_B14->WriteVariable()}</td>
</tr>
<tr>
  <th>15</th>
  <th>Replacement of the triplicate record of meeting form with a single online form that can be digitally signed</th>
  <td>{$Quest_B15->WriteVariable()}</td>
</tr>
<tr>
  <th>16</th>
  <th>Ability to set preferences of the page that will be viewed on entry each time for example, MyPDS, last used, or a specific tab</th>
  <td>{$Quest_B16->WriteVariable()}</td>
</tr>
<tr>
  <th>17</th>
  <th>Any other feature not listed in the requirements document or above that you would like to see included.</th>
  <td>{$Quest_B17->WriteVariable()}</td>
</tr>
<tr>
  <th>18</th>
  <th>Would you consider being involved in the pilot group to try the revised PDSystem?</th>
  <td>Yes {$Quest_B18a->WriteVariable()} <br />If Yes: Name: {$Quest_B18b->WriteVariable()} Email: {$Quest_B18c->WriteVariable()}</td>
</tr>
</table>

<hr />
<!-- Networking / Team Building -->

<h2>Using the PDSystem (Networking and Teambuilding)</h2>
<h3>Evaluation</h3>

<table width="100%">
<tr>
  <th width="5%">1</th>
  <th width="40%">How would you rate the employability event overall?</th>
  <td>{$Quest_A1->WriteVariable()}</td>
</tr>
<tr>
  <th>2</th>
  <th>How would you rate<br />Networking<br />Using PDS</th>
  <td><br />{$Quest_A2a->WriteVariable()}<br />{$Quest_A2b->WriteVariable()}</td>
</tr>
<tr>
  <th>3</th>
  <th>What were the best aspects of the course?</th>
  <td>{$Quest_A3->WriteVariable()}</td>
</tr>
<tr>
  <th>4</th>
  <th>What improvements would you suggest</th>
  <td>{$Quest_A4->WriteVariable()}</td>
</tr>
<tr>
  <th>5</th>
  <th>Any other comments about delivery, organisation, timing, content and value of the course.</th>
  <td>{$Quest_A3->WriteVariable()}</td>
</tr>
</table>

<hr />
<!-- Generic Research -->


<h2>Graduate School Training Programme: Generic Research Skills</h2>
<h3>Evaluation</h3>

<p>The purpose of this form is to enable us to evaluate and improve our programme of GST events.</p>

<table width="100%">
<tr>
  <th width="45%">Event Title</th>
  <td>{$Quest_Cevent_title->WriteVariable()}</td>
</tr>
<tr>
  <th>Event Date</th>
  <td>{$Quest_Cevent_date->WriteVariable()}</td>
</tr>
<tr>
  <th>Session Leader</th>
  <td>{$Quest_Csession_leader->WriteVariable()}</td>
</tr>
</table>

<h3>Content</h3>
<table width="100%">
<tr>
  <th width="5%">(a)</th>
  <th width="40%">The event content reflected the published aims / learning outcomes</th>
  <td>{$Quest_Ca->WriteVariable()}</td>
</tr>
<tr>
  <th>(b)</th>
  <th>The pace of the event was just right</th>
  <td>{$Quest_Cb->WriteVariable()}</td>
</tr>
<tr>
  <th>(c)</th>
  <th>The strong points of the session were (please state)</th>
  <td>{$Quest_Cc->WriteVariable()}</td>
</tr>
<tr>
  <th>(d)</th>
  <th>The weak points of the session were (please state)</th>
  <td>{$Quest_Cd->WriteVariable()}</td>
</tr>
<tr>
  <th>(e)</th>
  <th>Comments (please give further comments on any of the above)</th>
  <td>{$Quest_Ce->WriteVariable()}</td>
</tr>
</table>

<h3>Event Leader</h3>
<table width="100%">
<tr>
  <th width="5%">(f)</th>
  <th width="40%">The session leader was knowledgeable</th>
  <td>{$Quest_Cf->WriteVariable()}</td>
</tr>
<tr>
  <th>(g)</th>
  <th>The session leader answered questions satisfactorily</th>
  <td>{$Quest_Cg->WriteVariable()}</td>
</tr>
<tr>
  <th>(h)</th>
  <th>The session kepy my attention</th>
  <td>{$Quest_Ch->WriteVariable()}</td>
</tr>
<tr>
  <th>(i)</th>
  <th>The session leader made it easy for me to participate fully</th>
  <td>{$Quest_Ci->WriteVariable()}</td>
</tr>
<tr>
  <th>(j)</th>
  <th>Comments (please give further comments on any of the above)</th>
  <td>{$Quest_Cj->WriteVariable()}</td>
</tr>
</table>

<h3>Your contribution</h3>
<table width="100%">
<tr>
  <th width="45%">What did you hope to get out of the session?</th>
  <td>{$Quest_Ccontrib->WriteVariable()}</td>
</tr>
</table>

<h3>Event Materials</h3>
<table width="100%">
<tr>
  <th width="5%">(k)</th>
  <th width="40%">The handouts complemented the session</th>
  <td>{$Quest_Ck->WriteVariable()}</td>
</tr>
<tr>
  <th>(l)</th>
  <th>The handouts aided understanding</th>
  <td>{$Quest_Cl->WriteVariable()}</td>
</tr>
<tr>
  <th>(m)</th>
  <th>Comments (please give further comments on any of the above)</th>
  <td>{$Quest_Cm->WriteVariable()}</td>
</tr>
</table>


<h3>Logistics</h3>
<table width="100%">
<tr>
  <th width="5%">(n)</th>
  <th width="40%" >The pre-session administration was satisfactory</th>
  <td>{$Quest_Cn->WriteVariable()}</td>
</tr>
<tr>
  <th>(o)</th>
  <th>The venue was adequate</th>
  <td>{$Quest_Co->WriteVariable()}</td>
</tr>
<tr>
  <th>(p)</th>
  <th>Comments (please give further comments on any of the above)</th>
  <td>{$Quest_Cp->WriteVariable()}</td>
</tr>
</table>

<h3>Reflections</h3>
<table width="100%">
<tr>
  <th width="5%">(q)</th>
  <th width="40%">Overall the event was valuable</th>
  <td>{$Quest_Cq->WriteVariable()}</td>
</tr>
<tr>
  <th>(r)</th>
  <th>I would recommend this event to others</th>
  <td>{$Quest_Cr->WriteVariable()}</td>
</tr>
<tr>
  <th>(s)</th>
  <th colspan="2">Please rate your level of knowledge/skill/ability on this subject:</th>
</tr>
<tr>
  <th></th>
  <th>a) prior to the event</th>
  <td>{$Quest_Csa->WriteVariable()}</td>
</tr>
<tr>
  <th></th>
  <th>b) after the event</th>
  <td>{$Quest_Csb->WriteVariable()}</td>
</tr>
<tr>
  <th>(t)</th>
  <th>Overall comments about the event</th>
  <td>{$Quest_Ct->WriteVariable()}</td>
</tr>
</table>

<h3>Contact Details</h3>
<p>You may give your name and contact details below if you wish:</p>
<table width="100%">
<tr>
  <th width="45%">Name</th>
  <td>{$Quest_Cname->WriteVariable()}</td>
</tr>
<tr>
  <th>School / Dept</th>
  <td>{$Quest_Cschool->WriteVariable()}</td>
</tr>
<tr>
  <th>Email Address</th>
  <td>{$Quest_Cemail->WriteVariable()}</td>
</tr>
</table>


<input type="submit" value="Submit Answers">

</form>
<p></p>
<div id="footer">
</body>
</html>