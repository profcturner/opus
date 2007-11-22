{* Smarty *}
{* Template for SEME Final Placement Report *}

{* Standard Header for assessments *}
{include file="assessment/assessment_header.tpl"}

{* Assessment specific layout *}
{* Really, only the form contents need to be added *}
{* Note the use of getValue and flagVariable to *}
{* bring in assessment specific material *}

<TABLE WIDTH="700" COLS="6" ALIGN="CENTER" BORDER="1">

{* Employer and Personal Work *}
<TR>
<TH ROWSPAN="5">Employer and Personal Work</TH>
<TD COLSPAN="1"><B>Mark</B></TD><TD COLSPAN="4"><B>Comments</B></TD></TR>

<TR><TD COLSPAN="1">
{$assessment->flagVariable("mark1")}
<INPUT type="text" class="data_entry_required" SIZE="2" VALUE="{$assessment->getValue("mark1")}" NAME="mark1">
</TD>
<TD COLSPAN="4">
{$assessment->flagVariable("comment1")}
<TEXTAREA ROWS="5" COLS="30" NAME="comment1">{$assessment->getValue("comment1")|escape:"htmlall"}</TEXTAREA></TD>
</TR>

<TR><TD COLSPAN="5">Marking Scheme</TD></TR>
<TR>
<td><small>Full description, markets, organisation, products / services, IR, personal work</small></TD>
<td><small>Covers most points, short or long</small></td>
<td><small>States the obvious, lacks investigation and detail</small></td>
<td><small>Brief mention of a few obvious facts, vague on work</small></td>
<td><small>Very brief, does know the organization, shallow on work description</small></td>
</TR>
<TR>
<TD ALIGN="CENTER">20 .. 18</TD>
<TD ALIGN="CENTER">16 .. 14</TD>
<TD ALIGN="CENTER">12 .. 10</TD>
<TD ALIGN="CENTER">08 .. 05</TD>
<TD ALIGN="CENTER">04 .. 02</TD>
</TR>
  
{* Innovation in the Organisation *}

<TR>
<TH ROWSPAN="5">Innovation in the Organisation</TH>
<TD COLSPAN="1"><B>Mark</B></TD><TD COLSPAN="4"><B>Comments</B></TD></TR>

<TR><TD COLSPAN="1">
{$assessment->flagVariable("mark2")}
<INPUT type="text" class="data_entry_required" SIZE="2" VALUE="{$assessment->getValue("mark2")}" NAME="mark2">
</TD>
<TD COLSPAN="4">
{$assessment->flagVariable("comment2")}
<TEXTAREA ROWS="5" COLS="30" NAME="comment2">{$assessment->getValue("comment2")|escape:"htmlall"}</TEXTAREA></TD>
</TR>

<TR><TD COLSPAN="5">Marking Scheme</TD></TR>
<TR>
<td><small>Understands innovation, its impact, perceptive</small></td>
<td><small>Has a grasp of the topic, good comment on organisation profile</small></td>
<td><small>Shows some understanding, a few points well made</small></td>
<td><small>Limited grasp, few examples, unclear on organisational profile</small></td>
<td><small>Has not grasped the issues, limited view on organisation profile</small></td>
</TR>
<TR>
<TD ALIGN="CENTER">20 .. 18</TD>
<TD ALIGN="CENTER">16 .. 14</TD>
<TD ALIGN="CENTER">12 .. 10</TD>
<TD ALIGN="CENTER">08 .. 05</TD>
<TD ALIGN="CENTER">04 .. 02</TD>
</TR>

{* Reflection on Benefit of Placement *}

<TR>
<TH ROWSPAN="5">Reflection on Benefit of Placement</TH>
<TD COLSPAN="1"><B>Mark</B></TD><TD COLSPAN="4"><B>Comments</B></TD></TR>

<TR><TD COLSPAN="1">
  {$assessment->flagVariable("mark3")}
<INPUT type="text" class="data_entry_required" SIZE="2" VALUE="{$assessment->getValue("mark3")}" NAME="mark3">
</TD>
<TD COLSPAN="4">
  {$assessment->flagVariable("comment3")}
<TEXTAREA ROWS="5" COLS="30" NAME="comment3">{$assessment->getValue("comment3")|escape:"htmlall"}</TEXTAREA></TD>
</TR>

<TR><TD COLSPAN="5">Marking Scheme</TD></TR>
<TR>
<td><small>Rational, extensive, wide range of skills, perceptive, valuable comment</small></td>
<td><small>Outlines most skill areas adequately, credible comments on placement</small></td>
<td><small>A few skills identified and explained, lacks reflection</small></td>
<td><small>Limited benefit, few skills identified, brief comment</small></td>
<td><small>Minor comment, no benefit, lacks application, no suggestions</small></td>
</TR>
<TR>
<TD ALIGN="CENTER">30 .. 25</TD>
<TD ALIGN="CENTER">24 .. 19</TD>
<TD ALIGN="CENTER">18 .. 13</TD>
<TD ALIGN="CENTER">12 .. 07</TD>
<TD ALIGN="CENTER">06 .. 0</TD>
</TR>


{* Spelling and punctuation row *}

<TR>
<TH ROWSPAN="5">Spelling and Punctuation</TH>
<TD COLSPAN="1"><B>Mark</B></TD><TD COLSPAN="4"><B>Comments</B></TD></TR>

<TR><TD COLSPAN="1">
  {$assessment->flagVariable("mark4")}
<INPUT type="text" class="data_entry_required" SIZE="2" VALUE="{$assessment->getValue("mark4")}" NAME="mark4">
</TD>
<TD COLSPAN="4">
  {$assessment->flagVariable("comment4")}
<TEXTAREA ROWS="5" COLS="30" NAME="comment4">{$assessment->getValue("comment4")|escape:"htmlall"}</TEXTAREA></TD>
</TR>

<TR><TD COLSPAN="5">Marking Scheme</TD></TR>
<TR>
<TD>No errors</TD>
<TD>Very few minor errors</TD>
<TD>Some errors indicating some carelessness</TD>
<TD>Careless errors</TD>
<TD>Significant level of error</TD>
</TR>
<TR>
<TD ALIGN="CENTER">10 .. 09</TD>
<TD ALIGN="CENTER">08 .. 07</TD>
<TD ALIGN="CENTER">06 .. 05</TD>
<TD ALIGN="CENTER">04 .. 03</TD>
<TD ALIGN="CENTER">02 .. 01</TD>
</TR>
{* Use of english row.... *}

<TR>
<TH ROWSPAN="5">Use of English</TH>
<TD COLSPAN="1"><B>Mark</B></TD><TD COLSPAN="4"><B>Comments</B></TD></TR>

<TR><TD COLSPAN="1">
  {$assessment->flagVariable("mark5")}
<INPUT type="text" class="data_entry_required" SIZE="2" VALUE="{$assessment->getValue("mark5")}" NAME="mark5">
</TD>
<TD COLSPAN="4">
  {$assessment->flagVariable("comment5")}
<TEXTAREA ROWS="5" COLS="30" NAME="comment5">{$assessment->getValue("comment5")|escape:"htmlall"}</TEXTAREA></TD>
</TR>

<TR><TD COLSPAN="5">Marking Scheme</TD></TR>
<TR>
<TD> Uses language to clearly express views concisely </TD>
<TD> Expresses clearly but with some minor errors </TD>
<TD> Good expression, logical flow, reasonably concise </TD>
<TD> Reasonable flow, some contorted expressions, a little verbose </TD>
<TD> Poor expression, verbose, some colloquialisms </TD>
</TR>
<TR>
<TD ALIGN="CENTER">10 .. 09</TD>
<TD ALIGN="CENTER">08 .. 07</TD>
<TD ALIGN="CENTER">06 .. 05</TD>
<TD ALIGN="CENTER">04 .. 03</TD>
<TD ALIGN="CENTER">02 .. 01</TD>
</TR>

{* Presentation *}

<TR>
<TH ROWSPAN="5">Structure of Ideas</TH>
<TD COLSPAN="1"><B>Mark</B></TD><TD COLSPAN="4"><B>Comments</B></TD></TR>

<TR><TD COLSPAN="1">
  {$assessment->flagVariable("mark6")}
<INPUT type="text" class="data_entry_required" SIZE="2" VALUE="{$assessment->getValue("mark6")}" NAME="mark6">
</TD>
<TD COLSPAN="4">
  {$assessment->flagVariable("comment6")}
<TEXTAREA ROWS="5" COLS="30" NAME="comment6">{$assessment->getValue("comment6")|escape:"htmlall"}</TEXTAREA></TD>
</TR>

<TR><TD COLSPAN="5">Marking Scheme</TD></TR>
<TR>
<td><small> Follows the specification completely, within limits set </small></td>
<td><small> Generally follows the specification but with some deviation </small></td>
<td><small> Introduces minor personal style to the detriment of the specification </small></td>
<td><small> Pays scant regard to specification, personal style dominant </small></td>
<td><small> Unattractive or overdone to detriment, careless </small></td>
</TR>
<TR>
<TD ALIGN="CENTER">10 .. 09</TD>
<TD ALIGN="CENTER">08 .. 07</TD>
<TD ALIGN="CENTER">06 .. 05</TD>
<TD ALIGN="CENTER">04 .. 03</TD>
<TD ALIGN="CENTER">02 .. 01</TD>
</TR>

<TR><TD ALIGN="CENTER" COLSPAN="6">
<B>General Comments</B><BR>
<TEXTAREA NAME="comments" ROWS="15" COLS="70">{$assessment->getValue("comments")|escape:"htmlall"}</TEXTAREA><BR>
<INPUT TYPE="SUBMIT" NAME="BUTTON" VALUE="Submit"></TD></TR>

</TABLE>
  

{* Standard footer for assessments *}
{include file="assessment/assessment_footer.tpl"}
