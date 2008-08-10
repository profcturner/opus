{* Smarty *}
{* Template for SEME Industrial Supervisor *}

{* Assessment specific layout *}
{* Really, only the form contents need to be added *}
{* Note the use of get_value and flag_error to *}
{* bring in assessment specific material *}


{*
<table>
<tr>
  <th>Please outline the training / experience programme</th>
  
</tr>
<tr>
*}

<div id="table_manage">
<table>
<tr>
  <td colspan="6" class="button"><input type="submit" class="submit" value="confirm" /></td>
</tr>

<tr>
  <td class="property">1. Interest in Work</td>
  <td width="15%">
   <strong>5.</strong> <input type="radio" name="interest" value="5" {if $assessment->get_value('interest')==5} CHECKED{/if}>
   <br /><small>High interest in job, very enthusiastic</small>
  </td>
  <td width="15%">
   <strong>4.</strong><input type="radio" name="interest" value="4" {if $assessment->get_value('interest')==4} CHECKED{/if}>
   <br /><small>More than average amount of interest in the job</small>
  </td>
  <td width="15%">
   <strong>3.</strong><input type="radio" name="interest" value="3" {if $assessment->get_value('interest')==3} CHECKED{/if}>
   <br /><small>Satisfactory interest and enthusiasm in the job</small>
  </td>
  <td width="15%">
   <strong>2.</strong><input type="radio" name="interest" value="2" {if $assessment->get_value('interest')==2} CHECKED{/if}>
   <br /><small>Interest spasmodic, occasionally enthusiastic</small>
  </td>
  <td width="15%">
   <strong>1.</strong><input type="radio" name="interest" value="1" {if $assessment->get_value('interest')==1} CHECKED{/if}>
   <br /><small>Little interest or enthusiasm for the job</small>
  </td>
</tr>
<tr>
  <td class="property">2. Enterprise</td>
  
  <td>
   <strong>5.</strong> <input type="radio" name="enterprise" value="5" {if $assessment->get_value('enterprise')==5} CHECKED{/if}>
   <br /><small>Self-starter, asks for new jobs, looks for work to do</small>
  </td>
  <td>
   <strong>4.</strong><input type="radio" name="enterprise" value="4" {if $assessment->get_value('enterprise')==4} CHECKED{/if}>
   <br /><small>Acts voluntarily in most matters</small>
  </td>
  <td>
   <strong>3.</strong><input type="radio" name="enterprise" value="3" {if $assessment->get_value('enterprise')==3} CHECKED{/if}>
   <br /><small>Acts voluntarily in routine matters</small>
  </td>
  <td>
   <strong>2.</strong><input type="radio" name="enterprise" value="2" {if $assessment->get_value('enterprise')==2} CHECKED{/if}>
   <br /><small>Relies on others, must be told often what to do</small>
  </td>
  <td>
   <strong>1.</strong><input type="radio" name="enterprise" value="1" {if $assessment->get_value('enterprise')==1} CHECKED{/if}>
   <br /><small>Usually waits to be told what to do next</small>
  </td>
</tr>

<tr>
  <td class="property">3. Organisation and Planning</td>
  
  <td>
   <strong>5.</strong> <input type="radio" name="organisation" value="5" {if $assessment->get_value('organisation')==5} CHECKED{/if}>
   <br /><small>Does an excellent job of organising and planning work</small>
  </td>
  <td>
   <strong>4.</strong><input type="radio" name="organisation" value="4" {if $assessment->get_value('organisation')==4} CHECKED{/if}>
   <br /><small>Usually organises work well</small>
  </td>
  <td>
   <strong>3.</strong><input type="radio" name="organisation" value="3" {if $assessment->get_value('organisation')==3} CHECKED{/if}>
   <br /><small>Does normal amount of planning and organising</small>
  </td>
  <td>
   <strong>2.</strong><input type="radio" name="organisation" value="2" {if $assessment->get_value('organisation')==2} CHECKED{/if}>
   <br /><small>More often than not fails to organise and plan work effectively</small>
  </td>
  <td>
   <strong>1.</strong><input type="radio" name="organisation" value="1" {if $assessment->get_value('organisation')==1} CHECKED{/if}>
   <br /><small>More often than not fails to organise and plan work effectively</small>
  </td>
</tr>

<tr>
  <td class="property">4. Ability to Learn</td>
  
  <td>
   <strong>5.</strong> <input type="radio" name="learn" value="5" {if $assessment->get_value('learn')==5} CHECKED{/if}>
   <br /><small>Exceptionally quick</small>
  </td>
  <td>
   <strong>4.</strong><input type="radio" name="learn" value="4" {if $assessment->get_value('learn')==4} CHECKED{/if}>
   <br /><small>Quick to learn</small>
  </td>
  <td>
   <strong>3.</strong><input type="radio" name="learn" value="3" {if $assessment->get_value('learn')==3} CHECKED{/if}>
   <br /><small>Average</small>
  </td>
  <td>
   <strong>2.</strong><input type="radio" name="learn" value="2" {if $assessment->get_value('learn')==2} CHECKED{/if}>
   <br /><small>Slow to learn</small>
  </td>
  <td>
   <strong>1.</strong><input type="radio" name="learn" value="1" {if $assessment->get_value('learn')==1} CHECKED{/if}>
   <br /><small>Very slow to learn</small>
  </td>
</tr>

<tr>
  <td class="property">5. Quality of Work</td>
  
  <td>
   <strong>5.</strong> <input type="radio" name="quality" value="5" {if $assessment->get_value('quality')==5} CHECKED{/if}>
   <br /><small>Very thorough in performing work, very few errors if any</small>
  </td>
  <td>
   <strong>4.</strong><input type="radio" name="quality" value="4" {if $assessment->get_value('quality')==4} CHECKED{/if}>
   <br /><small>Usually thorough, good work, few errors</small>
  </td>
  <td>
   <strong>3.</strong><input type="radio" name="quality" value="3" {if $assessment->get_value('quality')==3} CHECKED{/if}>
   <br /><small>Work usually passes review, has normal amount of errors</small>
  </td>
  <td>
   <strong>2.</strong><input type="radio" name="quality" value="2" {if $assessment->get_value('quality')==2} CHECKED{/if}>
   <br /><small>More than average amount of errors for a trainee</small>
  </td>
  <td>
   <strong>1.</strong><input type="radio" name="quality" value="1" {if $assessment->get_value('quality')==1} CHECKED{/if}>
   <br /><small>Work usually done in careless manner, makes errors often</small>
  </td>
</tr>

<tr>
  <td class="property">6. Quantity of Work</td>
  
  <td>
   <strong>5.</strong> <input type="radio" name="quantity" value="5" {if $assessment->get_value('quantity')==5} CHECKED{/if}>
   <br /><small>Highly productive in relation to other students</small>
  </td>
  <td>
   <strong>4.</strong><input type="radio" name="quantity" value="4" {if $assessment->get_value('quantity')==4} CHECKED{/if}>
   <br /><small>More than expected in comparison with other students</small>
  </td>
  <td>
   <strong>3.</strong><input type="radio" name="quantity" value="3" {if $assessment->get_value('quantity')==3} CHECKED{/if}>
   <br /><small>Expected amount of productivity for students</small>
  </td>
  <td>
   <strong>2.</strong><input type="radio" name="quantity" value="2" {if $assessment->get_value('quantity')==2} CHECKED{/if}>
   <br /><small>Less than expected in comparison with other students</small>
  </td>
  <td>
   <strong>1.</strong><input type="radio" name="quantity" value="1" {if $assessment->get_value('quantity')==1} CHECKED{/if}>
   <br /><small>Very low in comparison with other students</small>
  </td>
 </tr>

<tr>
  <td class="property">7. Judgement</td>
  
  <td>
   <strong>5.</strong> <input type="radio" name="judgement" value="5" {if $assessment->get_value('judgement')==5} CHECKED{/if}>
   <br /><small>Exceptionally good, decisions based on thorough analysis of problem</small>
  </td>
  <td>
   <strong>4.</strong><input type="radio" name="judgement" value="4" {if $assessment->get_value('judgement')==4} CHECKED{/if}>
   <br /><small>Uses good common sense, usually makes good decisions</small>
  </td>
  <td>
   <strong>3.</strong><input type="radio" name="judgement" value="3" {if $assessment->get_value('judgement')==3} CHECKED{/if}>
   <br /><small>Judgement usually good in routine situations</small>
  </td>
  <td>
   <strong>2.</strong><input type="radio" name="judgement" value="2" {if $assessment->get_value('judgement')==2} CHECKED{/if}>
   <br /><small>Judgement often undependable</small>
  </td>
  <td>
   <strong>1.</strong><input type="radio" name="judgement" value="1" {if $assessment->get_value('judgement')==1} CHECKED{/if}>
   <br /><small>Poor judgement, jumps to conclusions without sufficient knowledge</small>
  </td>
</tr>
<tr>
  <td class="property">8. Dependability</td>
  
  <td>
   <strong>5.</strong> <input type="radio" name="dependability" value="5" {if $assessment->get_value('dependability')==5} CHECKED{/if}>
   <br /><small>Can always be depended upon in any situation</small>
  </td>
  <td>
   <strong>4.</strong><input type="radio" name="dependability" value="4" {if $assessment->get_value('dependability')==4} CHECKED{/if}>
   <br /><small>Can usually be depended upon in most situations</small>
  </td>
  <td>
   <strong>3.</strong><input type="radio" name="dependability" value="3" {if $assessment->get_value('dependability')==3} CHECKED{/if}>
   <br /><small>Can be depended upon in routine situations</small>
  </td>
  <td>
   <strong>2.</strong><input type="radio" name="dependability" value="2" {if $assessment->get_value('dependability')==2} CHECKED{/if}>
   <br /><small>Somewhat unreliable, needs checking</small>
  </td>
  <td>
   <strong>1.</strong><input type="radio" name="dependability" value="1" {if $assessment->get_value('dependability')==1} CHECKED{/if}>
   <br /><small>Unreliable</small>
  </td>
</tr>

<tr>
  <td class="property">9. Relations with Others</td>
  
  <td>
   <strong>5.</strong> <input type="radio" name="relations" value="5" {if $assessment->get_value('relations')==5} CHECKED{/if}>
   <br /><small>Always works in harmony with others, an excellent team worker</small>
  </td>
  <td>
   <strong>4.</strong><input type="radio" name="relations" value="4" {if $assessment->get_value('relations')==4} CHECKED{/if}>
   <br /><small>Congenial and helpful, works well with associates</small>
  </td>
  <td>
   <strong>3.</strong><input type="radio" name="relations" value="3" {if $assessment->get_value('relations')==3} CHECKED{/if}>
   <br /><small>Most relations with others are harmonious under normal circumstances</small>
  </td>
  <td>
   <strong>2.</strong><input type="radio" name="relations" value="2" {if $assessment->get_value('relations')==2} CHECKED{/if}>
   <br /><small>Difficult to work with at times, sometimes antagonises others</small>
  </td>
  <td>
   <strong>1.</strong><input type="radio" name="relations" value="1" {if $assessment->get_value('relations')==1} CHECKED{/if}>
   <br /><small>Frequently quarrelsome and causes friction</small>
  </td>
</tr>
<tr>
  <td class="property">10. Creativity</td>
  
  <td>
   <strong>5.</strong> <input type="radio" name="creativity" value="5" {if $assessment->get_value('creativity')==5} CHECKED{/if}>
   <br /><small>Continually seeks new and better ways of doing things, is extremely innovative</small>
  </td>
  <td>
   <strong>4.</strong><input type="radio" name="creativity" value="4" {if $assessment->get_value('creativity')==4} CHECKED{/if}>
   <br /><small>Frequently suggests new ways of doing things, is very imaginative</small>
  </td>
  <td>
   <strong>3.</strong><input type="radio" name="creativity" value="3" {if $assessment->get_value('creativity')==3} CHECKED{/if}>
   <br /><small>Has average amount of imagination, has reasonable amount of new ideas</small>
  </td>
  <td>
   <strong>2.</strong><input type="radio" name="creativity" value="2" {if $assessment->get_value('creativity')==2} CHECKED{/if}>
   <br /><small>Occasionally comes up with a new idea</small>
  </td>
  <td>
   <strong>1.</strong><input type="radio" name="creativity" value="1" {if $assessment->get_value('creativity')==1} CHECKED{/if}>
   <br /><small>Rarely has a new idea, is not very imaginative</small>
  </td>
</tr>
<tr>
  <td class="property">11. Communication Skills - Written Expression</td>
  
  <td>
   <strong>5.</strong> <input type="radio" name="comm_written" value="5" {if $assessment->get_value('comm_written')==5} CHECKED{/if}>
   <br /><small>Very Good</small>
  </td>
  <td>
   <strong>4.</strong><input type="radio" name="comm_written" value="4" {if $assessment->get_value('comm_written')==4} CHECKED{/if}>
   <br /><small>Good</small>
  </td>
  <td>
   <strong>3.</strong><input type="radio" name="comm_written" value="3" {if $assessment->get_value('comm_written')==3} CHECKED{/if}>
   <br /><small>Satisfactory</small>
  </td>
  <td>
   <strong>2.</strong><input type="radio" name="comm_written" value="2" {if $assessment->get_value('comm_written')==2} CHECKED{/if}>
   <br /><small>Needs Improvement</small>
  </td>
  <td>
   <strong>1.</strong><input type="radio" name="comm_written" value="1" {if $assessment->get_value('comm_written')==1} CHECKED{/if}>
   <br /><small>Poor</small>
  </td>
</tr>
<tr>
  <td class="property">12. Communication Skills - Oral Expression</td>
  
  <td>
   <strong>5.</strong> <input type="radio" name="comm_oral" value="5" {if $assessment->get_value('comm_oral')==5} CHECKED{/if}>
   <br /><small>Very Good</small>
  </td>
  <td>
   <strong>4.</strong><input type="radio" name="comm_oral" value="4" {if $assessment->get_value('comm_oral')==4} CHECKED{/if}>
   <br /><small>Good</small>
  </td>
  <td>
   <strong>3.</strong><input type="radio" name="comm_oral" value="3" {if $assessment->get_value('comm_oral')==3} CHECKED{/if}>
   <br /><small>Satisfactory</small>
  </td>
  <td>
   <strong>2.</strong><input type="radio" name="comm_oral" value="2" {if $assessment->get_value('comm_oral')==2} CHECKED{/if}>
   <br /><small>Needs Improvement</small>
  </td>
  <td>
   <strong>1.</strong><input type="radio" name="comm_oral" value="1" {if $assessment->get_value('comm_oral')==1} CHECKED{/if}>
   <br /><small>Poor</small>
  </td>
</tr>

<tr>
  <td class="property">13. Acceptance of criticism</td>
  
  <td>
   <strong>5.</strong> <input type="radio" name="accept_crit" value="5" {if $assessment->get_value('accept_crit')==5} CHECKED{/if}>
   <br /><small>Overtly welcomes critique and advice on his/her performance</small>
  </td>
  <td>
   <strong>4.</strong><input type="radio" name="accept_crit" value="4" {if $assessment->get_value('accept_crit')==4} CHECKED{/if}>
   <br /><small>Accepts criticism willingly</small>
  </td>
  <td>
   <strong>3.</strong><input type="radio" name="accept_crit" value="3" {if $assessment->get_value('accept_crit')==3} CHECKED{/if}>
   <br /><small>Passive acceptance of criticism</small>
  </td>
  <td>
   <strong>2.</strong><input type="radio" name="accept_crit" value="2" {if $assessment->get_value('accept_crit')==2} CHECKED{/if}>
   <br /><small>Does not take criticism well</small>
  </td>
  <td>
   <strong>1.</strong><input type="radio" name="accept_crit" value="1" {if $assessment->get_value('accept_crit')==1} CHECKED{/if}>
   <br /><small>Becomes argumentative on criticism</small>
  </td>
</tr>

<tr>
  <td class="property">14. Attendance</td>
  
  <td>
   <strong>5.</strong> <input type="radio" name="attendance" value="5" {if $assessment->get_value('attendance')==5} CHECKED{/if}>
   <br /><small>Attends more than is expected</small>
  </td>
  <td>
   <strong>4.</strong><input type="radio" name="attendance" value="4" {if $assessment->get_value('attendance')==4} CHECKED{/if}>
   <br /><small>Attends all planned work sessions</small>
  </td>
  <td>
   <strong>3.</strong><input type="radio" name="attendance" value="3" {if $assessment->get_value('attendance')==3} CHECKED{/if}>
   <br /><small>Occasional unplanned absence</small>
  </td>
  <td>
   <strong>2.</strong><input type="radio" name="attendance" value="2" {if $assessment->get_value('attendance')==2} CHECKED{/if}>
   <br /><small>Absent more than acceptable</small>
  </td>
  <td>
   <strong>1.</strong><input type="radio" name="attendance" value="1" {if $assessment->get_value('attendance')==1} CHECKED{/if}>
   <br /><small>Attendance unsatisfactory</small>
  </td>
</tr>

<tr>
  <td class="property">15. Punctuality</td>
  
  <td>
   <strong>5.</strong> <input type="radio" name="punctuality" value="5" {if $assessment->get_value('punctuality')==5} CHECKED{/if}>
   <br /><small>Always early for appointments</small>
  </td>
  <td>
   <strong>4.</strong><input type="radio" name="punctuality" value="4" {if $assessment->get_value('punctuality')==4} CHECKED{/if}>
   <br /><small>Always on time</small>
  </td>
  <td>
   <strong>3.</strong><input type="radio" name="punctuality" value="3" {if $assessment->get_value('punctuality')==3} CHECKED{/if}>
   <br /><small>Occasionally late for appointments</small>
  </td>
  <td>
   <strong>2.</strong><input type="radio" name="punctuality" value="2" {if $assessment->get_value('punctuality')==2} CHECKED{/if}>
   <br /><small>Frequently late for appointments</small>
  </td>
  <td>
   <strong>1.</strong><input type="radio" name="punctuality" value="1" {if $assessment->get_value('punctuality')==1} CHECKED{/if}>
   <br /><small>Disregards appointment times</small>
  </td>
</tr>

<tr>
  <td class="property">16. Supervisor's comments on the student's performance{$assessment->flag_error("performance_comments")}
  </td>
  
  <td colspan="5">
    {include file="general/assessment/textarea.tpl" name="performance_comments" rows="6" cols="60"}
  </td>
</tr>

<tr>
  <td class="property">
  17. Observations, comments and recommendations on the knowledge and skills displayed by the student at the start of the placement {$assessment->flag_error("skills_comments")}
  </td>
  
  <td colspan="5">
    {include file="general/assessment/textarea.tpl" name="skills_comments" rows="6" cols="60"}
  </td>
</tr>

<tr>
  <td class="property">
  18. Observations, comments and recommendations on the Placement Process {$assessment->flag_error("process_comments")}
  </td>
  
  <td colspan="5">
    {include file="general/assessment/textarea.tpl" name="process_comments" rows="6" cols="60"}
  </td>
</tr>
<tr>
  <td colspan="6" class="button"><input type="submit" class="submit" value="confirm" /></td>
</tr>
</table>
</div>