{* Smarty *}
{* Template for SEME Final Visit *}

{* Assessment specific layout *}
{* Really, only the form contents need to be added *}
{* Note the use of get_value and flag_error to *}
{* bring in assessment specific material *}
<p>
For the following categories, the following marking scheme is employed.<br />
<em>5. Outstanding, 4. Excellent, 3. Very Good, 2. Good, 1. Poor, 0. Weak</em>
</p>

<p><strong>Warning: The system distinguishes between 0. (Weak) and
blank fields. A blank field is taken to be non applicable to the student and the
category will not be counted. Therefore only leave a field blank if it is not
applicable.</strong></p>

<div id="table_manage">
<table cols="3">
<tr>
  <td colspan="3" class="button"><input type="submit" class="submit" value="confirm" /></td>
</tr>
<tr>
  <td colspan="2"><strong>Attribute</strong></td>
  <td><strong>Mark</strong></td>
</tr>
<tr>
  <td colspan="3"><em>Work Activities</em></td>
</tr>

<tr>
  <td class="property">A1</td>
  <td><strong>Ability to describe and understanding of tasks performed</strong><br />
<small>
Is the student able to <strong>describe clearly what tasks</strong> (s)he
is performing and able to respond fluently to questions on these
tasks? Does the student <strong>understand the technical principles</strong>
of the tasks and their functions and <strong>why they are being carried out?</strong></small>
</td><td>
{$assessment->flag_error("A1")}
<input type="text" size="2" name="A1" value="{$assessment->get_value('A1')}">
</td></tr>

<tr><td class="property">A2</td><td>
<strong>Analytical Skills</strong><br />
<small>
Is the student able to <strong>tackle complex problems</strong> and <strong>
use analytical skills</strong> to arrive at a solution? Can (s)he
<strong>draw conclusions</strong> from given data?</small>
</td><td>
{$assessment->flag_error("A2")}
<input type="TEXT" size="2" name="A2" value="{$assessment->get_value("A2")}"></td></tr>


<tr><td class="property">A3</td><td>
<strong>Attempts to be creative/innovative/display initiative</strong><br />
<small>Has the student <strong>suggested any improvements</strong> to his/her tasks
or done things in a new way? What <strong>ideas</strong> does (s)he have?</small>
</td><td>
{$assessment->flag_error("A3")}
<input type="TEXT" size="2" name="A3" value="{$assessment->get_value("A3")}"></td></tr>

<tr><td colspan="3"><em>Placement Organisation</em></td></tr>

<tr><td class="property">B1</td><td>
<strong>Ability to describe the business of the employer</strong><br />
<small>What <strong>products</strong> are made by the organisation/company?
Who are the <strong>competitors</strong>? Is the student aware of the
relative positions of companies operating in the field and what
affects <strong>market share</strong>? Where do the <strong>raw materials</strong>
come from? Is the student familiar with the company's <strong>annual
report/statement</strong> and therefore its growth, turnover and profit?
(Note: for public sector employers equivalent questions should be
asked).</small>
</td><td>
{$assessment->flag_error("B1")}
<input type="TEXT" size="2" name="B1" value="{$assessment->get_value("B1")}"></td></tr>

<tr><td class="property">B2</td><td>
<strong>Knowledge of the company/organisation and its structures</strong><br />
<small>Can the student describe the <strong>organisation's structure</strong>,
the departments and their functions and interactions? Are career
opportunities similar in the various departments?</small>
</td><td>
{$assessment->flag_error("B2")}
<input type="TEXT" size="2" name="B2" value="{$assessment->get_value("B2")}"></td></tr>

<tr><td class="property">B3</td><td>
<strong>Knowledge of role of others in the company/organisation?</strong><br />
<small>Does the student know the <strong>names of others</strong> in supervisory 
roles as well as their functions? Does (s)he <strong>know how decisions 
affecting his/her work are made?</strong></small>
</td><td>
{$assessment->flag_error("B3")}
<input type="TEXT" size="2" name="B3" value="{$assessment->get_value("B3")}"></td></tr>

<tr><td class="property">B4</td><td>
<strong>Health and Safety</strong><br />
<small>Is the student familiar with the <strong>safety code</strong> within the 
workplace? What measures/suggestions have been made by the student to 
<strong>improve safety</strong>? How does the organisation monitor <strong>compliance 
with safety regulations?</strong></small>
</td><td>
{$assessment->flag_error("B4")}
<input type="TEXT" size="2" name="B4" value="{$assessment->get_value("B4")}"></td></tr>

<tr><td class="property">B5</td><td>
<strong>Environmental Awareness</strong><br />
<small>Has the student developed an awareness of the <strong>impact of the 
company's activities</strong> on people and the environment? Can (s)he 
quantify the role played by the profession in protecting the 
environment?</small>
</td><td>
{$assessment->flag_error("B5")}
<input type="TEXT" size="2" name="B5" value="{$assessment->get_value("B5")}"></td></tr>

<tr><td class="property">B6</td><td>
<strong>Integration within the company</strong><br />
<small>Is the <strong>student involved</strong> in union affaies/sports/social
 events/meetings with in the organisation?</small>
</td><td>
{$assessment->flag_error("B6")}
<input type="TEXT" size="2" name="B6" value="{$assessment->get_value("B6")}"></td></tr>

{* Involvement *}

<tr><td colspan="3"><em>Involvement</em></td></tr>

<tr><td class="property">C1</td><td>
<strong>Flexibility and attitude to change</strong><br />
<small>Does the student enjoy or dislike <strong>change</strong>? Has (s)he 
enjoyed the <strong>transition from university</strong> to work? Does the student 
find it easy or difficult to <strong>change from one task to another</strong>, or 
to use new equipment?</small>
</td><td>
{$assessment->flag_error("C1")}
<input type="TEXT" size="2" name="C1" value="{$assessment->get_value("C1")}"></td></tr>

<tr><td class="property">C2</td><td>
<strong>Self organisation</strong><br />
<small>Does the student <strong>make good use of his/her time</strong>. Are tasks 
<strong>completed within the allotted time</strong>? Are jobs left unfinished or seen 
through to their conclusion? WHat does the student do <strong>when all tasks 
have been completed</strong>? Is unnecessary <strong>work avoided</strong>?</small>
</td><td>
{$assessment->flag_error("C2")}
<input type="TEXT" size="2" name="C2" value="{$assessment->get_value("C2")}"></td></tr>

<tr><td class="property">C3</td><td>
<strong>Teamwork</strong><br />
<small>How <strong>many people does the student work with</strong>? Does (s)he 
have firm <strong>likes/dislikes</strong>? Does the student have a good 
<strong>"professional attitude"</strong> to workmates or are there 
<strong>personality clashes</strong>.</small>
</td><td>
{$assessment->flag_error("C3")}
<input type="TEXT" size="2" name="C3" value="{$assessment->get_value("C3")}"></td></tr>

<tr><td class="property">C4</td><td>
<strong>Leadership</strong><br />
<small>Is there an opportunity to <b>take responsibility</b> or to 
supervise others? If so what is the <strong>attitude</strong> of the student 
to this responsibility? Is there an opportunity to <strong>make decisions</strong>?
If so what is the students attitude?</small>
</td><td>
{$assessment->flag_error("C4")}
<input type="TEXT" size="2" name="C4" value="{$assessment->get_value("C4")}"></td></tr>

<tr><td colspan="3"><em>Self Development</em></td></tr>

<tr><td class="property">D1</td><td>
<strong>Information Technology</strong><br />
<small>Is the student <strong>exposed to information technology</strong>? 
Can the student <strong>perform competently</strong> when interfacing with 
computers; is (s)he <strong>at ease with software</b> applications; are 
<strong>keyboard skills</strong> being developed?</small>
</td><td>
{$assessment->flag_error("D1")}
<input type="TEXT" size="2" name="D1" value="{$assessment->get_value("D1")}"></td></tr>

<tr><td class="property">D2</td><td>
<strong>Communication</strong><br />
<small>Can the student contribute to the development of his/her 
department by <strong>oral and written communications</strong>? Are <strong>reports</strong> 
required as part of his/her duties? is(s)he required to make <strong>oral 
presentations</strong> to his/her peers or superiors? Is the student's log book a good record</strong> of the student's activities?</small>
</td><td>
{$assessment->flag_error("D2")}
<input type="TEXT" size="2" name="D2" value="{$assessment->get_value("D2")}"></td></tr>

<tr><td class="property">D3</td><td>
<strong>Career planning</strong><br />
<small>Does the student have <strong>clearer ideas</strong> on how (s)he would 
like his/her <strong>career to develop</strong>? What steps has the student taken 
to determine where the <strong>best career prospects</strong> are and what 
<strong>qualifications</strong> are required?</small>
</td><td>
{$assessment->flag_error("D3")}
<input type="TEXT" size="2" name="D3" value="{$assessment->get_value("D3")}"></td></tr>

<tr>
  <td class="property">Logbook</td>
  <td colspan="2">Inspected? <input type="CHECKBOX" name="logbook"
{if $assessment->get_value("logbook")} CHECKED {/if}>
 Satisfactory? <input type="CHECKBOX" name="logbookok"
{if $assessment->get_value("logbookok")} CHECKED {/if}></td>
</tr>

<tr>
  <td class="property">Comments<br />(on and for the Student)</td>
  <td colspan="2">{$assessment->flag_error("scomments")}<textarea cols="60" rows="10" name="scomments">{$assessment->get_value("scomments")|escape:"htmlall"}</textarea></td>
</tr>

<tr>
  <td class="property">Comments<br />(on the Company and<br />future participation)</td>
  <td colspan="2">{$assessment->flag_error("ccomments")}
    <textarea cols="60" rows="10" name="ccomments">{$assessment->get_value("ccomments")|escape:"htmlall"}</textarea>
  </td>
</tr>

<tr>
  <td class="property">Assessment date<br />(DD/MM/YYYY)</td>
  <td colspan="2">{$assessment->flag_error("assesseddate")}<input type="TEXT" name="assesseddate" value="{$assessment->get_value("assesseddate")}"></tr>
</tr>
<tr>
  <td colspan="3" class="button"><input type="submit" class="submit" value="confirm" /></td>
</tr>
</table>
</div>
