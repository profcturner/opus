{* Smarty *}
{* Template for SEME Final Visit *}

{* Standard Header for assessments *}
{include file="assessment/assessment_header.tpl"}

{* Assessment specific layout *}
{* Really, only the form contents need to be added *}
{* Note the use of getValue and flagVariable to *}
{* bring in assessment specific material *}
<p align="center">
For the following categories, employ the following marking scheme.
<table align="center">
<tr><TD>5. Outstanding</TD><TD>4. Excellent</TD></TR>
<TR><TD>3. Very Good</TD><TD>2. Good</TD></TR>
<TR><TD>1. Poor</TD><TD>0. Weak</TD></TR></TABLE>
<P ALIGN=\"CENTER\"><B>Warning: The system distinguishes between 0. (Weak) and
blank fields. A blank field is taken to be non applicable to the student and the
category will not be counted. Therefore only leave a field blank if it is not
applicable.</b></p>

<TABLE ALIGN="CENTER" COLS="3" BORDER="1">
<TR><TH COLSPAN=2>Attribute</TH><TH>Mark</TH></TR>
<TR><TH COLSPAN="3" ALIGN="CENTER">Work Activities</TH></TR>

<TR><TH>A1</TH><TD>
<B>Ability to describe and understanding of tasks performed</B><BR>
<SMALL>
Is the student able to <B>describe clearly what tasks</B> (s)he
is performing and able to respond fluently to questions on these
tasks? Does the student <B>understand the technical principles</B>
of the tasks and their functions and <B>why they are being carried out?</B></SMALL>
</TD><TD>
{$assessment->flagVariable("A1")}
<INPUT TYPE="TEXT" SIZE="2" NAME="A1" VALUE="{$assessment->getValue('A1')}">
</TD></TR>

<TR><TH>A2</TH><TD>
<B>Analytical Skills</B><BR>
<SMALL>
Is the student able to <B>tackle complex problems</B> and <B>
use analytical skills</B> to arrive at a solution? Can (s)he
<B>draw conclusions</B> from given data?</SMALL>
</TD><TD>
{$assessment->flagVariable("A2")}
<INPUT TYPE="TEXT" SIZE="2" NAME="A2" VALUE="{$assessment->getValue("A2")}"></TD></TR>


<TR><TH>A3</TH><TD>
<B>Attempts to be creative/innovative/display initiative</B><BR>
<SMALL>Has the student <B>suggested any improvements</B> to his/her tasks
or done things in a new way? What <B>ideas</B> does (s)he have?</SMALL>
</TD><TD>
{$assessment->flagVariable("A3")}
<INPUT TYPE="TEXT" SIZE="2" NAME="A3" VALUE="{$assessment->getValue("A3")}"></TD></TR>

<TR><TH COLSPAN="3" ALIGN="CENTER">Placement Organisation</TH></TR>

<TR><TH>B1</TH><TD>
<B>Ability to describe the business of the employer</B><BR>
<SMALL>What <B>products</B> are made by the organisation/company?
Who are the <B>competitors</B>? Is the student aware of the
relative positions of companies operating in the field and what
affects <B>market share</B>? Where do the <B>raw materials</B>
come from? Is the student familiar with the company's <B>annual
report/statement</B> and therefore its growth, turnover and profit?
(Note: for public sector employers equivalent questions should be
asked).</SMALL>
</TD><TD>
{$assessment->flagVariable("B1")}
<INPUT TYPE="TEXT" SIZE="2" NAME="B1" VALUE="{$assessment->getValue("B1")}"></TD></TR>

<TR><TH>B2</TH><TD>
<B>Knowledge of the company/organisation and its structures</B><BR>
<SMALL>Can the student describe the <B>organisation's structure</B>,
the departments and their functions and interactions? Are career
opportunities similar in the various departments?</SMALL>
</TD><TD>
{$assessment->flagVariable("B2")}
<INPUT TYPE="TEXT" SIZE="2" NAME="B2" VALUE="{$assessment->getValue("B2")}"></TD></TR>

<TR><TH>B3</TH><TD>
<B>Knowledge of role of others in the company/organisation?</B><BR>
<SMALL>Does the student know the <B>names of others</B> in supervisory 
roles as well as their functions? Does (s)he <B>know how decisions 
affecting his/her work are made?</B></SMALL>
</TD><TD>
{$assessment->flagVariable("B3")}
<INPUT TYPE="TEXT" SIZE="2" NAME="B3" VALUE="{$assessment->getValue("B3")}"></TD></TR>

<TR><TH>B4</TH><TD>
<B>Health and Safety</B><BR>
<SMALL>Is the student familiar with the <B>safety code</B> within the 
workplace? What measures/suggestions have been made by the student to 
<B>improve safety</B>? How does the organisation monitor <B>compliance 
with safety regulations?</B></SMALL>
</TD><TD>
{$assessment->flagVariable("B4")}
<INPUT TYPE="TEXT" SIZE="2" NAME="B4" VALUE="{$assessment->getValue("B4")}"></TD></TR>

<TR><TH>B5</TH><TD>
<B>Environmental Awareness</B><BR>
<SMALL>Has the student developed an awareness of the <B>impact of the 
company's activities</B> on people and the environment? Can (s)he 
quantify the role played by the profession in protecting the 
environment?</SMALL>
</TD><TD>
{$assessment->flagVariable("B5")}
<INPUT TYPE="TEXT" SIZE="2" NAME="B5" VALUE="{$assessment->getValue("B5")}"></TD></TR>

<TR><TH>B6</TH><TD>
<B>Integration within the company</B><BR>
<SMALL>Is the <B>student involved</B> in union affaies/sports/social
 events/meetings with in the organisation?</SMALL>
</TD><TD>
{$assessment->flagVariable("B6")}
<INPUT TYPE="TEXT" SIZE="2" NAME="B6" VALUE="{$assessment->getValue("B6")}"></TD></TR>

{* Involvement *}

<TR><TH COLSPAN="3" ALIGN="CENTER">Involvement</TH></TR>

<TR><TH>C1</TH><TD>
<B>Flexibility and attitude to change</B><BR>
<SMALL>Does the student enjoy or dislike <B>change</B>? Has (s)he 
enjoyed the <B>transition from university</B> to work? Does the student 
find it easy or difficult to <B>change from one task to another</B>, or 
to use new equipment?</SMALL>
</TD><TD>
{$assessment->flagVariable("C1")}
<INPUT TYPE="TEXT" SIZE="2" NAME="C1" VALUE="{$assessment->getValue("C1")}"></TD></TR>

<TR><TH>C2</TH><TD>
<B>Self organisation</B><BR>
<SMALL>Does the student <B>make good use of his/her time</B>. Are tasks 
<B>completed within the allotted time</B>? Are jobs left unfinished or seen 
through to their conclusion? WHat does the student do <B>when all tasks 
have been completed</B>? Is unnecessary <B>work avoided</B>?</SMALL>
</TD><TD>
{$assessment->flagVariable("C2")}
<INPUT TYPE="TEXT" SIZE="2" NAME="C2" VALUE="{$assessment->getValue("C2")}"></TD></TR>

<TR><TH>C3</TH><TD>
<B>Teamwork</B><BR>
<SMALL>How <B>many people does the student work with</B>? Does (s)he 
have firm <B>likes/dislikes</B>? Does the student have a good 
<B>"professional attitude"</B> to workmates or are there 
<B>personality clashes</B>.</SMALL>
</TD><TD>
{$assessment->flagVariable("C3")}
<INPUT TYPE="TEXT" SIZE="2" NAME="C3" VALUE="{$assessment->getValue("C3")}"></TD></TR>

<TR><TH>C4</TH><TD>
<B>Leadership</B><BR>
<SMALL>Is there an opportunity to <b>take responsibility</b> or to 
supervise others? If so what is the <B>attitude</B> of the student 
to this responsibility? Is there an opportunity to <B>make decisions</B>?
If so what is the students attitude?</SMALL>
</TD><TD>
{$assessment->flagVariable("C4")}
<INPUT TYPE="TEXT" SIZE="2" NAME="C4" VALUE="{$assessment->getValue("C4")}"></TD></TR>

<TR><TH COLSPAN="3" ALIGN="CENTER">Self Development</TH></TR>

<TR><TH>D1</TH><TD>
<B>Information Technology</B><BR>
<SMALL>Is the student <B>exposed to information technology</B>? 
Can the student <B>perform competently</B> when interfacing with 
computers; is (s)he <B>at ease with software</b> applications; are 
<B>keyboard skills</B> being developed?</SMALL>
</TD><TD>
{$assessment->flagVariable("D1")}
<INPUT TYPE="TEXT" SIZE="2" NAME="D1" VALUE="{$assessment->getValue("D1")}"></TD></TR>

<TR><TH>D2</TH><TD>
<B>Communication</B><BR>
<SMALL>Can the student contribute to the development of his/her 
department by <B>oral and written communications</B>? Are <B>reports</B> 
required as part of his/her duties? is(s)he required to make <B>oral 
presentations</B> to his/her peers or superiors? Is the student's 
<log book a good record</B> of the student's activities?</SMALL>
</TD><TD>
{$assessment->flagVariable("D2")}
<INPUT TYPE="TEXT" SIZE="2" NAME="D2" VALUE="{$assessment->getValue("D2")}"></TD></TR>

<TR><TH>D3</TH><TD>
<B>Career planning</B><BR>
<SMALL>Does the student have <B>clearer ideas</B> on how (s)he would 
like his/her <B>career to develop</B>? What steps has the student taken 
to determine where the <B>best career prospects</B> are and what 
<B>qualifications</B> are required?</SMALL>
</TD><TD>
{$assessment->flagVariable("D3")}
<INPUT TYPE="TEXT" SIZE="2" NAME="D3" VALUE="{$assessment->getValue("D3")}"></TD></TR>

<TR><TH COLSPAN="3" ALIGN="CENTER">Logbook</TH></TR>
<TR><TD COLSPAN="3" ALIGN="CENTER">
Inspected? <INPUT TYPE="CHECKBOX" NAME="logbook"
{if $assessment->getValue("logbook")} CHECKED {/if}>
 Satisfactory? <INPUT TYPE="CHECKBOX" NAME="logbookok"
{if $assessment->getValue("logbookok")} CHECKED {/if}>
</TD></TR>

<TR><TH COLSPAN="3" ALIGN="CENTER">Comments on the Student</TH></TR>
<TR><TD COLSPAN="3" ALIGN="CENTER">
{$assessment->flagVariable("scomments")}
<TEXTAREA COLS="60" ROWS="10" NAME="scomments">
{$assessment->getValue("scomments")|escape:"htmlall"}</TEXTAREA></TD></TR>

<TR><TH COLSPAN="3" ALIGN="CENTER">Comments on the Company and future participation</TD></TR>
<TR><TD COLSPAN="3" ALIGN="CENTER">
{$assessment->flagVariable("ccomments")}
<TEXTAREA COLS="60" ROWS="10" NAME="ccomments">
{$assessment->getValue("ccomments")|escape:"htmlall"}</TEXTAREA></TH></TR>

<TR><TD COLSPAN="3" ALIGN="CENTER">Assessment date (DD/MM/YYYY)<BR>
{$assessment->flagVariable("assesseddate")}
<INPUT TYPE="TEXT" NAME="assesseddate" VALUE="{$assessment->getValue("assesseddate")}"><BR>
<INPUT TYPE="SUBMIT" NAME="BUTTON" VALUE="Submit"></TD></TR>

</TABLE>


{* Standard footer for assessments *}
{include file="assessment/assessment_footer.tpl"}

