{* Smarty *}
{* Template for SEME Industrial Supervisor *}

{* Standard Header for assessments *}
{include file="assessment/assessment_header.tpl"}

{* Assessment specific layout *}
{* Really, only the form contents need to be added *}
{* Note the use of getValue and flagVariable to *}
{* bring in assessment specific material *}


{*
<table>
<tr>
  <th>Please outline the training / experience programme</th>
  <td></td>
</tr>
<tr>
*}

<table>
<tr>
  <th colspan="6">
  1. Interest in Work
  </th>
</tr>
<tr>
  <td width="10%"></td>
  <td width="15%">
   <strong>5.</strong> <input type="radio" name="interest" value="5" {if $assessment->getValue('interest')==5} CHECKED{/if}>
   <br /><small>High interest in job, very enthusiastic</small>
  </td>
  <td width="15%">
   <strong>4.</strong><input type="radio" name="interest" value="4" {if $assessment->getValue('interest')==4} CHECKED{/if}>
   <br /><small>More than average amount of interest in the job</small>
  </td>
  <td width="15%">
   <strong>3.</strong><input type="radio" name="interest" value="3" {if $assessment->getValue('interest')==3} CHECKED{/if}>
   <br /><small>Satisfactory interest and enthusiasm in the job</small>
  </td>
  <td width="15%">
   <strong>2.</strong><input type="radio" name="interest" value="2" {if $assessment->getValue('interest')==2} CHECKED{/if}>
   <br /><small>Interest spasmodic, occasionally enthusiastic</small>
  </td>
  <td width="15%">
   <strong>1.</strong><input type="radio" name="interest" value="1" {if $assessment->getValue('interest')==1} CHECKED{/if}>
   <br /><small>Little interest or enthusiasm for the job</small>
  </td>
</tr>
<tr>
  <th colspan="6">
  2. Enterprise
  </th>
</tr>
<tr>
  <td></td>
  <td>
   <strong>5.</strong> <input type="radio" name="enterprise" value="5" {if $assessment->getValue('enterprise')==5} CHECKED{/if}>
   <br /><small>Self-starter, asks for new jobs, looks for work to do</small>
  </td>
  <td>
   <strong>4.</strong><input type="radio" name="enterprise" value="4" {if $assessment->getValue('enterprise')==4} CHECKED{/if}>
   <br /><small>Acts voluntarily in most matters</small>
  </td>
  <td>
   <strong>3.</strong><input type="radio" name="enterprise" value="3" {if $assessment->getValue('enterprise')==3} CHECKED{/if}>
   <br /><small>Acts voluntarily in routine matters</small>
  </td>
  <td>
   <strong>2.</strong><input type="radio" name="enterprise" value="2" {if $assessment->getValue('enterprise')==2} CHECKED{/if}>
   <br /><small>Relies on others, must be told often what to do</small>
  </td>
  <td>
   <strong>1.</strong><input type="radio" name="enterprise" value="1" {if $assessment->getValue('enterprise')==1} CHECKED{/if}>
   <br /><small>Usually waits to be told what to do next</small>
  </td>
</tr>

<tr>
  <th colspan="6">
  3. Organisation and Planning
  </th>
</tr>
<tr>
  <td></td>
  <td>
   <strong>5.</strong> <input type="radio" name="organisation" value="5" {if $assessment->getValue('organisation')==5} CHECKED{/if}>
   <br /><small>Does an excellent job of organising and planning work</small>
  </td>
  <td>
   <strong>4.</strong><input type="radio" name="organisation" value="4" {if $assessment->getValue('organisation')==4} CHECKED{/if}>
   <br /><small>Usually organises work well</small>
  </td>
  <td>
   <strong>3.</strong><input type="radio" name="organisation" value="3" {if $assessment->getValue('organisation')==3} CHECKED{/if}>
   <br /><small>Does normal amount of planning and organising</small>
  </td>
  <td>
   <strong>2.</strong><input type="radio" name="organisation" value="2" {if $assessment->getValue('organisation')==2} CHECKED{/if}>
   <br /><small>More often than not fails to organise and plan work effectively</small>
  </td>
  <td>
   <strong>1.</strong><input type="radio" name="organisation" value="1" {if $assessment->getValue('organisation')==1} CHECKED{/if}>
   <br /><small>More often than not fails to organise and plan work effectively</small>
  </td>
</tr>

<tr>
  <th colspan="6">
  4. Ability to Learn
  </th>
</tr>
<tr>
  <td></td>
  <td>
   <strong>5.</strong> <input type="radio" name="learn" value="5" {if $assessment->getValue('learn')==5} CHECKED{/if}>
   <br /><small>Exceptionally quick</small>
  </td>
  <td>
   <strong>4.</strong><input type="radio" name="learn" value="4" {if $assessment->getValue('learn')==4} CHECKED{/if}>
   <br /><small>Quick to learn</small>
  </td>
  <td>
   <strong>3.</strong><input type="radio" name="learn" value="3" {if $assessment->getValue('learn')==3} CHECKED{/if}>
   <br /><small>Average</small>
  </td>
  <td>
   <strong>2.</strong><input type="radio" name="learn" value="2" {if $assessment->getValue('learn')==2} CHECKED{/if}>
   <br /><small>Slow to learn</small>
  </td>
  <td>
   <strong>1.</strong><input type="radio" name="learn" value="1" {if $assessment->getValue('learn')==1} CHECKED{/if}>
   <br /><small>Very slow to learn</small>
  </td>
</tr>

<tr>
  <th colspan="6">
  5. Quality of Work
  </th>
</tr>
<tr>
  <td></td>
  <td>
   <strong>5.</strong> <input type="radio" name="quality" value="5" {if $assessment->getValue('quality')==5} CHECKED{/if}>
   <br /><small>Very thorough in performing work, very few errors if any</small>
  </td>
  <td>
   <strong>4.</strong><input type="radio" name="quality" value="4" {if $assessment->getValue('quality')==4} CHECKED{/if}>
   <br /><small>Usually thorough, good work, few errors</small>
  </td>
  <td>
   <strong>3.</strong><input type="radio" name="quality" value="3" {if $assessment->getValue('quality')==3} CHECKED{/if}>
   <br /><small>Work usually passes review, has normal amount of errors</small>
  </td>
  <td>
   <strong>2.</strong><input type="radio" name="quality" value="2" {if $assessment->getValue('quality')==2} CHECKED{/if}>
   <br /><small>More than average amount of errors for a trainee</small>
  </td>
  <td>
   <strong>1.</strong><input type="radio" name="quality" value="1" {if $assessment->getValue('quality')==1} CHECKED{/if}>
   <br /><small>Work usually done in careless manner, makes errors often</small>
  </td>
</tr>

<tr>
  <th colspan="6">
  6. Quantity of Work
  </th>
</tr>
<tr>
  <td></td>
  <td>
   <strong>5.</strong> <input type="radio" name="quantity" value="5" {if $assessment->getValue('quantity')==5} CHECKED{/if}>
   <br /><small>Highly productive in relation to other students</small>
  </td>
  <td>
   <strong>4.</strong><input type="radio" name="quantity" value="4" {if $assessment->getValue('quantity')==4} CHECKED{/if}>
   <br /><small>More than expected in comparison with other students</small>
  </td>
  <td>
   <strong>3.</strong><input type="radio" name="quantity" value="3" {if $assessment->getValue('quantity')==3} CHECKED{/if}>
   <br /><small>Expected amount of productivity for students</small>
  </td>
  <td>
   <strong>2.</strong><input type="radio" name="quantity" value="2" {if $assessment->getValue('quantity')==2} CHECKED{/if}>
   <br /><small>Less than expected in comparison with other students</small>
  </td>
  <td>
   <strong>1.</strong><input type="radio" name="quantity" value="1" {if $assessment->getValue('quantity')==1} CHECKED{/if}>
   <br /><small>Very low in comparison with other students</small>
  </td>
 </tr>

<tr>
  <th colspan="6">
  7. Judgement
  </th>
</tr>
<tr>
  <td></td>
  <td>
   <strong>5.</strong> <input type="radio" name="judgement" value="5" {if $assessment->getValue('judgement')==5} CHECKED{/if}>
   <br /><small>Exceptionally good, decisions based on thorough analysis of problem</small>
  </td>
  <td>
   <strong>4.</strong><input type="radio" name="judgement" value="4" {if $assessment->getValue('judgement')==4} CHECKED{/if}>
   <br /><small>Uses good common sense, usually makes good decisions</small>
  </td>
  <td>
   <strong>3.</strong><input type="radio" name="judgement" value="3" {if $assessment->getValue('judgement')==3} CHECKED{/if}>
   <br /><small>Judgement usually good in routine situations</small>
  </td>
  <td>
   <strong>2.</strong><input type="radio" name="judgement" value="2" {if $assessment->getValue('judgement')==2} CHECKED{/if}>
   <br /><small>Judgement often undependable</small>
  </td>
  <td>
   <strong>1.</strong><input type="radio" name="judgement" value="1" {if $assessment->getValue('judgement')==1} CHECKED{/if}>
   <br /><small>Poor judgement, jumps to conclusions without sufficient knowledge</small>
  </td>
</tr>
<tr>
  <th colspan="6">
  8. Dependability
  </th>
</tr>
<tr>
  <td></td>
  <td>
   <strong>5.</strong> <input type="radio" name="dependability" value="5" {if $assessment->getValue('dependability')==5} CHECKED{/if}>
   <br /><small>Can always be depended upon in any situation</small>
  </td>
  <td>
   <strong>4.</strong><input type="radio" name="dependability" value="4" {if $assessment->getValue('dependability')==4} CHECKED{/if}>
   <br /><small>Can usually be depended upon in most situations</small>
  </td>
  <td>
   <strong>3.</strong><input type="radio" name="dependability" value="3" {if $assessment->getValue('dependability')==3} CHECKED{/if}>
   <br /><small>Can be depended upon in routine situations</small>
  </td>
  <td>
   <strong>2.</strong><input type="radio" name="dependability" value="2" {if $assessment->getValue('dependability')==2} CHECKED{/if}>
   <br /><small>Somewhat unreliable, needs checking</small>
  </td>
  <td>
   <strong>1.</strong><input type="radio" name="dependability" value="1" {if $assessment->getValue('dependability')==1} CHECKED{/if}>
   <br /><small>Unreliable</small>
  </td>
</tr>

<tr>
  <th colspan="6">
  9. Relations with Others
  </th>
</tr>
<tr>
  <td></td>
  <td>
   <strong>5.</strong> <input type="radio" name="relations" value="5" {if $assessment->getValue('relations')==5} CHECKED{/if}>
   <br /><small>Always works in harmony with others, an excellent team worker</small>
  </td>
  <td>
   <strong>4.</strong><input type="radio" name="relations" value="4" {if $assessment->getValue('relations')==4} CHECKED{/if}>
   <br /><small>Congenial and helpful, works well with associates</small>
  </td>
  <td>
   <strong>3.</strong><input type="radio" name="relations" value="3" {if $assessment->getValue('relations')==3} CHECKED{/if}>
   <br /><small>Most relations with others are harmonious under normal circumstances</small>
  </td>
  <td>
   <strong>2.</strong><input type="radio" name="relations" value="2" {if $assessment->getValue('relations')==2} CHECKED{/if}>
   <br /><small>Difficult to work with at times, sometimes antagonises others</small>
  </td>
  <td>
   <strong>1.</strong><input type="radio" name="relations" value="1" {if $assessment->getValue('relations')==1} CHECKED{/if}>
   <br /><small>Frequently quarrelsome and causes friction</small>
  </td>
</tr>
<tr>
  <th colspan="6">
  10. Creativity
  </th>
</tr>
<tr>
  <td></td>
  <td>
   <strong>5.</strong> <input type="radio" name="creativity" value="5" {if $assessment->getValue('creativity')==5} CHECKED{/if}>
   <br /><small>Continually seeks new and better ways of doing things, is extremely innovative</small>
  </td>
  <td>
   <strong>4.</strong><input type="radio" name="creativity" value="4" {if $assessment->getValue('creativity')==4} CHECKED{/if}>
   <br /><small>Frequently suggests new ways of doing things, is very imaginative</small>
  </td>
  <td>
   <strong>3.</strong><input type="radio" name="creativity" value="3" {if $assessment->getValue('creativity')==3} CHECKED{/if}>
   <br /><small>Has average amount of imagination, has reasonable amount of new ideas</small>
  </td>
  <td>
   <strong>2.</strong><input type="radio" name="creativity" value="2" {if $assessment->getValue('creativity')==2} CHECKED{/if}>
   <br /><small>Occasionally comes up with a new idea</small>
  </td>
  <td>
   <strong>1.</strong><input type="radio" name="creativity" value="1" {if $assessment->getValue('creativity')==1} CHECKED{/if}>
   <br /><small>Rarely has a new idea, is not very imaginative</small>
  </td>
</tr>
<tr>
  <th colspan="6">
  11. Communication Skills - Written Expression
  </th>
</tr>
<tr>
  <td></td>
  <td>
   <strong>5.</strong> <input type="radio" name="comm_written" value="5" {if $assessment->getValue('comm_written')==5} CHECKED{/if}>
   <br /><small>Very Good</small>
  </td>
  <td>
   <strong>4.</strong><input type="radio" name="comm_written" value="4" {if $assessment->getValue('comm_written')==4} CHECKED{/if}>
   <br /><small>Good</small>
  </td>
  <td>
   <strong>3.</strong><input type="radio" name="comm_written" value="3" {if $assessment->getValue('comm_written')==3} CHECKED{/if}>
   <br /><small>Satisfactory</small>
  </td>
  <td>
   <strong>2.</strong><input type="radio" name="comm_written" value="2" {if $assessment->getValue('comm_written')==2} CHECKED{/if}>
   <br /><small>Needs Improvement</small>
  </td>
  <td>
   <strong>1.</strong><input type="radio" name="comm_written" value="1" {if $assessment->getValue('comm_written')==1} CHECKED{/if}>
   <br /><small>Poor</small>
  </td>
</tr>
<tr>
  <th colspan="6">
  12. Communication Skills - Oral Expression
  </th>
</tr>
<tr>
  <td></td>
  <td>
   <strong>5.</strong> <input type="radio" name="comm_oral" value="5" {if $assessment->getValue('comm_oral')==5} CHECKED{/if}>
   <br /><small>Very Good</small>
  </td>
  <td>
   <strong>4.</strong><input type="radio" name="comm_oral" value="4" {if $assessment->getValue('comm_oral')==4} CHECKED{/if}>
   <br /><small>Good</small>
  </td>
  <td>
   <strong>3.</strong><input type="radio" name="comm_oral" value="3" {if $assessment->getValue('comm_oral')==3} CHECKED{/if}>
   <br /><small>Satisfactory</small>
  </td>
  <td>
   <strong>2.</strong><input type="radio" name="comm_oral" value="2" {if $assessment->getValue('comm_oral')==2} CHECKED{/if}>
   <br /><small>Needs Improvement</small>
  </td>
  <td>
   <strong>1.</strong><input type="radio" name="comm_oral" value="1" {if $assessment->getValue('comm_oral')==1} CHECKED{/if}>
   <br /><small>Poor</small>
  </td>
</tr>

<tr>
  <th colspan="6">
  13. Acceptance of criticism
  </th>
</tr>
<tr>
  <td></td>
  <td>
   <strong>5.</strong> <input type="radio" name="accept_crit" value="5" {if $assessment->getValue('accept_crit')==5} CHECKED{/if}>
   <br /><small>Overtly welcomes critique and advice on his/her performance</small>
  </td>
  <td>
   <strong>4.</strong><input type="radio" name="accept_crit" value="4" {if $assessment->getValue('accept_crit')==4} CHECKED{/if}>
   <br /><small>Accepts criticism willingly</small>
  </td>
  <td>
   <strong>3.</strong><input type="radio" name="accept_crit" value="3" {if $assessment->getValue('accept_crit')==3} CHECKED{/if}>
   <br /><small>Passive acceptance of criticism</small>
  </td>
  <td>
   <strong>2.</strong><input type="radio" name="accept_crit" value="2" {if $assessment->getValue('accept_crit')==2} CHECKED{/if}>
   <br /><small>Does not take criticism well</small>
  </td>
  <td>
   <strong>1.</strong><input type="radio" name="accept_crit" value="1" {if $assessment->getValue('accept_crit')==1} CHECKED{/if}>
   <br /><small>Becomes argumentative on criticism</small>
  </td>
</tr>

<tr>
  <th colspan="6">
  14. Attendance
  </th>
</tr>
<tr>
  <td></td>
  <td>
   <strong>5.</strong> <input type="radio" name="attendance" value="5" {if $assessment->getValue('attendance')==5} CHECKED{/if}>
   <br /><small>Attends more than is expected</small>
  </td>
  <td>
   <strong>4.</strong><input type="radio" name="attendance" value="4" {if $assessment->getValue('attendance')==4} CHECKED{/if}>
   <br /><small>Attends all planned work sessions</small>
  </td>
  <td>
   <strong>3.</strong><input type="radio" name="attendance" value="3" {if $assessment->getValue('attendance')==3} CHECKED{/if}>
   <br /><small>Occasional unplanned absence</small>
  </td>
  <td>
   <strong>2.</strong><input type="radio" name="attendance" value="2" {if $assessment->getValue('attendance')==2} CHECKED{/if}>
   <br /><small>Absent more than acceptable</small>
  </td>
  <td>
   <strong>1.</strong><input type="radio" name="attendance" value="1" {if $assessment->getValue('attendance')==1} CHECKED{/if}>
   <br /><small>Attendance unsatisfactory</small>
  </td>
</tr>

<tr>
  <th colspan="6">
  15. Punctuality
  </th>
</tr>
<tr>
  <td></td>
  <td>
   <strong>5.</strong> <input type="radio" name="punctuality" value="5" {if $assessment->getValue('punctuality')==5} CHECKED{/if}>
   <br /><small>Always early for appointments</small>
  </td>
  <td>
   <strong>4.</strong><input type="radio" name="punctuality" value="4" {if $assessment->getValue('punctuality')==4} CHECKED{/if}>
   <br /><small>Always on time</small>
  </td>
  <td>
   <strong>3.</strong><input type="radio" name="punctuality" value="3" {if $assessment->getValue('punctuality')==3} CHECKED{/if}>
   <br /><small>Occasionally late for appointments</small>
  </td>
  <td>
   <strong>2.</strong><input type="radio" name="punctuality" value="2" {if $assessment->getValue('punctuality')==2} CHECKED{/if}>
   <br /><small>Frequently late for appointments</small>
  </td>
  <td>
   <strong>1.</strong><input type="radio" name="punctuality" value="1" {if $assessment->getValue('punctuality')==1} CHECKED{/if}>
   <br /><small>Disregards appointment times</small>
  </td>
</tr>

<tr>
  <th colspan="6">
  16. Supervisor's comments on the student's performance{$assessment->flagVariable("performance_comments")}
  </th>
</tr>
<tr>
  <td></td>
  <td colspan="5">
    <textarea name="performance_comments" rows="6" cols="60" wrap="virtual">{$assessment->getValue('performance_comments')|escape:"htmlall"}</textarea>
  </td>
</tr>

<tr>
  <th colspan="6">
  17. Observations, comments and recommendations on the knowledge and skills displayed by the student at the start of the placement {$assessment->flagVariable("skills_comments")}
  </th>
</tr>
<tr>
  <td></td>
  <td colspan="5">
    <textarea name="skills_comments" rows="6" cols="60" wrap="virtual">{$assessment->getValue('skills_comments')|escape:"htmlall"}</textarea>
  </td>
</tr>

<tr>
  <th colspan="6">
  18. Observations, comments and recommendations on the Placement Process {$assessment->flagVariable("process_comments")}
  </th>
</tr>
<tr>
  <td></td>
  <td colspan="5">
    <textarea name="process_comments" rows="6" cols="60" wrap="virtual">{$assessment->getValue('process_comments')|escape:"htmlall"}</textarea>
  </td>
</tr>



<tr>
  <td colspan="7"><input type="submit" value="Submit Marks"></td>
</tr>

</table>



{* Standard footer for assessments *}
{include file="assessment/assessment_footer.tpl"}
